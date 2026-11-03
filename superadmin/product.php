<?php
$umessage = '';
include('./function/function.php');
include('./function/push_helper.php');
check_session();

// Self-heal: Ensure table supports emojis
try {
    $pdo_fix = getPDOObject();
    $pdo_fix->exec("ALTER TABLE `subproduct` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo_fix->exec("ALTER TABLE `offer` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (Exception $e) { /* silent */ }

// Ensure developer column exists in subproduct
try {
    $pdo = getPDOObject();
    $pdo->exec("ALTER TABLE subproduct ADD COLUMN developer VARCHAR(255) DEFAULT '' AFTER pro_lable");
} catch (Exception $e) {}

try {
    $pdo->exec("ALTER TABLE subproduct ADD COLUMN city VARCHAR(255) DEFAULT '' AFTER developer");
} catch (Exception $e) {}

try {
    $pdo = getPDOObject();
    $pdo->exec("ALTER TABLE subproduct ADD COLUMN related_blogs TEXT NULL");
} catch (Exception $e) {}

try {
    $pdo = getPDOObject();
    $pdo->exec("ALTER TABLE subproduct ADD COLUMN related_products TEXT NULL");
} catch (Exception $e) {}

// Auto-create product_images table if not exists
try {
    $pdo_init = getPDOObject();
    $pdo_init->exec("CREATE TABLE IF NOT EXISTS product_images (
        id INT NOT NULL AUTO_INCREMENT,
        product_id INT NOT NULL DEFAULT 0,
        photo VARCHAR(255) NOT NULL DEFAULT '',
        title VARCHAR(255) DEFAULT '',
        caption TEXT,
        fld_order INT DEFAULT 0,
        PRIMARY KEY (id),
        KEY product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (Exception $e) {}

function save_product_images_func($product_id) {
    if (!$product_id) return;
    $pdo = getPDOObject();

    $uploadDir = "../upload/";
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0775, true);
    }

    $extra_titles   = $_POST['extra_title'] ?? [];
    $extra_captions = $_POST['extra_caption'] ?? [];
    $extra_orders   = $_POST['extra_order'] ?? [];
    $existing_imgs  = $_POST['existing_extra_photo'] ?? [];

    $files = $_FILES['extra_photos'] ?? null;

    // Clear existing ones and re-sync
    $del = $pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
    $del->execute([$product_id]);

    if (is_array($extra_titles)) {
        foreach ($extra_titles as $i => $title) {
            $fname = '';
            
            // New upload for this row
            if (isset($files['name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
                $origName = basename($files['name'][$i]);
                $safeName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $origName);
                $fname = date('YmdHis') . "_" . uniqid() . "_" . $safeName;
                $target = $uploadDir . $fname;
                move_uploaded_file($files['tmp_name'][$i], $target);
            } 
            // Reuse existing
            elseif (!empty($existing_imgs[$i])) {
                $fname = $existing_imgs[$i];
            }

            if ($fname) {
                $pdo->prepare("INSERT INTO product_images (product_id, photo, title, caption, fld_order) VALUES (?, ?, ?, ?, ?)")
                    ->execute([
                        $product_id,
                        $fname,
                        $title,
                        $extra_captions[$i] ?? '',
                        $extra_orders[$i] ?? 0
                    ]);
            }
        }
    }
}

// ✅ Reusable upload function
function handleFileUpload($fileKey, $prevFile = '')
{
    $Filename = $prevFile; // keep old file if no new one uploaded
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../upload/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $originalName = basename($_FILES[$fileKey]['name']);
            $safeName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $originalName);

            $Filename = date('YmdHis') . "_" . $safeName;
            $target = $uploadDir . $Filename;

            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $target)) {
                // remove old file
                if ($prevFile && file_exists($uploadDir . $prevFile)) {
                    @unlink($uploadDir . $prevFile);
                }
            } else {
                die("Error: Could not move uploaded file ($fileKey). Check permissions.");
            }
        } else {
            die("Upload failed ($fileKey) with error code: " . $_FILES[$fileKey]['error']);
        }
    }
    return $Filename;
}

# ============================
# ADD SUBPRODUCT
# ============================
if (isset($_POST['addsubproduct'])) {
    $pdo = getPDOObject();
    $posted_data = $_POST;

    // handle uploads
    $posted_data['photo']  = handleFileUpload('photo');
    $posted_data['photo2'] = handleFileUpload('photo2');
    $posted_data['photo3'] = handleFileUpload('photo3');
    $posted_data['photo4'] = handleFileUpload('photo4');

    if (isset($posted_data['related_blogs']) && is_array($posted_data['related_blogs'])) {
        $posted_data['related_blogs'] = implode(',', $posted_data['related_blogs']);
    } else {
        $posted_data['related_blogs'] = '';
    }

    if (isset($posted_data['related_products']) && is_array($posted_data['related_products'])) {
        $posted_data['related_products'] = implode(',', $posted_data['related_products']);
    } else {
        $posted_data['related_products'] = '';
    }

    $affected_rows = insert('subproduct', $posted_data);
    if ($affected_rows) {
        $last_id = $pdo->lastInsertId();
        save_product_images_func($last_id);
        $umessage = '<div class="alert alert-success" role="alert">
                        Added Successfully
                     </div>';
        
        // Send Push Notification if checked
        if (isset($_POST['send_notif']) && $_POST['send_notif'] == '1') {
            $notif_title = !empty($_POST['notif_title']) ? $_POST['notif_title'] : "New Property: " . $posted_data['name'];
            $notif_msg = !empty($_POST['notif_msg']) ? $_POST['notif_msg'] : "Check out our newest property listing!";
            $notif_custom_link = !empty($_POST['notif_link']) ? $_POST['notif_link'] : SITE_URL . "service_detail/" . makeurlnamebynameCategory($posted_data['name']) . ".php";
            
            $notif_img = $posted_data['photo'] ? SITE_URL . "upload/" . $posted_data['photo'] : '';
            sendGlobalPushNotification($notif_title, $notif_msg, $notif_custom_link, $notif_img);
        }
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">
                        Something went wrong while saving!
                     </div>';
    }
}

# ============================
# DELETE SUBPRODUCT
# ============================
if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $data = sqlfetch("SELECT * FROM subproduct WHERE id='$id'");
    foreach ($data as $subproduct) {
        foreach (['photo', 'photo2', 'photo3', 'photo4'] as $field) {
            $img_path = '../upload/' . $subproduct[$field];
            if ($subproduct[$field] && file_exists($img_path)) {
                @unlink($img_path);
            }
        }
    }
    sqlfetch("DELETE FROM subproduct WHERE id='" . $id . "'");

    // Deleting extra photos from DB and File system
    $extra_imgs = sqlfetch("SELECT photo FROM product_images WHERE product_id='$id'");
    foreach ($extra_imgs as $ei) {
        if ($ei['photo'] && file_exists('../upload/' . $ei['photo'])) {
            @unlink('../upload/' . $ei['photo']);
        }
    }
    sqlfetch("DELETE FROM product_images WHERE product_id='$id'");

    echo "<script>window.open('product.php','_self')</script>";
}

# ============================
# BULK DELETE
# ============================
if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $ids = implode(",", $arr);
        $data = sqlfetch("SELECT * FROM subproduct WHERE id IN ($ids)");
        foreach ($data as $subproduct) {
            foreach (['photo', 'photo2', 'photo3', 'photo4'] as $field) {
                $img_path = '../upload/' . $subproduct[$field];
                if ($subproduct[$field] && file_exists($img_path)) {
                    @unlink($img_path);
                }
            }
        }
        $pdo = getPDOObject();
        $q = $pdo->query("DELETE FROM subproduct WHERE id IN ($ids)");
        
        // Handle extra images for bulk deletion
        $extra_imgs = sqlfetch("SELECT photo FROM product_images WHERE product_id IN ($ids)");
        foreach ($extra_imgs as $ei) {
            if ($ei['photo'] && file_exists('../upload/' . $ei['photo'])) {
                @unlink('../upload/' . $ei['photo']);
            }
        }
        $pdo->query("DELETE FROM product_images WHERE product_id IN ($ids)");

        if ($q) {
            $umessage = '<div class="alert alert-success" role="alert">Deleted Successfully</div>';
        }
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Please select items</div>';
    }
}

# ============================
# ACTIVATE / DEACTIVATE
# ============================
if (isset($_POST['activate']) || isset($_POST['deactivate'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $ids = implode(",", $arr);
        $status = isset($_POST['activate']) ? '1' : '0';
        $pdo = getPDOObject();
        $pdo->query("UPDATE subproduct SET actstat='$status' WHERE id IN ($ids)");
        $msg = $status == '1' ? "Activated" : "Deactivated";
        $umessage = '<div class="alert alert-success" role="alert">' . $msg . ' Successfully</div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Please select items</div>';
    }
}

# ============================
# EDIT SUBPRODUCT
# ============================
if (isset($_POST['editdone'])) {
    extract($_POST);
    $posted_data = $_POST;

    $posted_data['photo']  = handleFileUpload('photo',  $prevphoto);
    $posted_data['photo2'] = handleFileUpload('photo2', $prevphoto2);
    $posted_data['photo3'] = handleFileUpload('photo3', $prevphoto3);
    $posted_data['photo4'] = handleFileUpload('photo4', $prevphoto4);

    if (isset($posted_data['related_blogs']) && is_array($posted_data['related_blogs'])) {
        $posted_data['related_blogs'] = implode(',', $posted_data['related_blogs']);
    } else {
        $posted_data['related_blogs'] = '';
    }

    if (isset($posted_data['related_products']) && is_array($posted_data['related_products'])) {
        $posted_data['related_products'] = implode(',', $posted_data['related_products']);
    } else {
        $posted_data['related_products'] = '';
    }

    $affected_rows = update('subproduct', $posted_data, ['id' => $pid]);
    
    save_product_images_func($pid);

    if ($affected_rows) {
        $umessage = '<div class="alert alert-success" role="alert">
                        Updated Successfully
                     </div>';
        
        // Send Push Notification if checked
        if (isset($_POST['send_notif']) && $_POST['send_notif'] == '1') {
            $notif_title = !empty($_POST['notif_title']) ? $_POST['notif_title'] : "Updated Property: " . $posted_data['name'];
            $notif_msg = !empty($_POST['notif_msg']) ? $_POST['notif_msg'] : "We've updated details for " . $posted_data['name'] . ". View now!";
            $notif_custom_link = !empty($_POST['notif_link']) ? $_POST['notif_link'] : SITE_URL . "service_detail/" . makeurlnamebynameCategory($posted_data['name']) . ".php";

            $notif_img = $posted_data['photo'] ? SITE_URL . "upload/" . $posted_data['photo'] : ($prevphoto ? SITE_URL . "upload/" . $prevphoto : '');
            sendGlobalPushNotification($notif_title, $notif_msg, $notif_custom_link, $notif_img);
        }
    }
}

# ============================
# FORM FUNCTION (unchanged)
# ============================
function subproduct_form(
    $pid = '0',
    $name = '',
    $photo = '',
    $des = '',
    $photo2 = '',
    $photo3 = '',
    $photo4 = '',
    $pro_lable = '',
    $city = '',
    $developer = '',
    $pro_specification = '',
    $pro_mainprice = '',
    $pro_discountprice = '',
    $pro_shortdes = '',
    $pro_additionalinfo = '',
    $code = '',
    $subcat2 = 0,
    $select_option1 = '',
    $fld_order = '0',
    $subcat = 0,
    $meta_title = '',
    $meta_keyword = '',
    $meta_description = '',
    $actstat = '',
    $related_blogs = '',
    $related_products = '',
    $formname = 'addsubproduct'
) { ?>


    <form action="product.php" method="post" enctype="multipart/form-data">
        <div class="form theme-form">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
            <input type="hidden" name="prevphoto2" value="<?php echo $photo2; ?>" />

            <input type="hidden" name="prevphoto3" value="<?php echo $photo3; ?>" />
            <input type="hidden" name="prevphoto4" value="<?php echo $photo4; ?>" />
            <input type="hidden" name="code" id="code" value="<?php echo $code; ?>" />
            <div class="row">
                <div class="col-lg-6  mt-3">
                    <div class="row">
                        <div class="col-lg-12  mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="name" placeholder="Name " aria-label="Name" value="<?php echo $name; ?>" />
                                    <label for="basic-addon11"> Product Name </label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="pro_lable" id="product_location" placeholder="Name " aria-label="Name" value="<?php echo $pro_lable; ?>" />
                                    <label for="basic-addon11"> Product Location (Specific Area/Sector) </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select" name="city" id="product_city" onchange="fetchDevelopers(this.value)">
                                        <option value="">Select City</option>
                                        <?php
                                        $cities = sqlfetch("SELECT DISTINCT location FROM location_developers ORDER BY location ASC");
                                        $total_cities = count($cities);
                                        foreach($cities as $c) {
                                            $selected = ($city == $c['location']) ? 'selected' : '';
                                            echo '<option value="'.$c['location'].'" '.$selected.'>'.$c['location'].'</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="basic-addon11"> City / Main Location (Total: <?php echo $total_cities; ?>) </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select" name="developer" id="developer_dropdown">
                                        <option value="">Select Developer</option>
                                        <?php if(!empty($developer)) echo "<option value='$developer' selected>$developer</option>"; ?>
                                    </select>
                                    <label for="basic-addon11"> Developer </label>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-12  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="pro_specification" placeholder="Name " aria-label="Name" value="<?php echo $pro_specification; ?>" />
                                    <label for="basic-addon11"> Product Area SQ/FT</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" name="pro_mainprice" placeholder="Name " aria-label="Name" value="<?php echo $pro_mainprice; ?>" />
                                    <label for="basic-addon11"> Product Main Price</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" name="pro_discountprice" placeholder="Name " aria-label="Name" value="<?php echo $pro_discountprice; ?>" />
                                    <label for="basic-addon11"> Product Discount Price</label>
                                </div>
                            </div>
                        </div>




                        <div class="col-lg-12  mt-4  d-none">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="select_option1" id="selectError" data-rel="chosen">
                                    <option value="no">Select Options</option>
                                    <option value="block">Yes</option>
                                    <option value="none">No</option>
                                </select>
                                <label for="floatingSelect">Add Select Option 1 </label>
                            </div>
                        </div>






                    </div>
                </div>

                <div class="col-lg-6  mt-3">



                    <div class="col-lg-12 mt-4">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="input-group input-group-merge" style="max-width:320px;">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline" style="border: 2px solid #666cff66;border-radius: 8px;">
                                    <input type="file" class="form-control" name="photo" aria-label="Upload" accept="image/*">
                                    <label for="basic-addon11"> Main Photo</label>
                                </div>
                            </div>
                            <?php if ($photo): ?>
                            <div>
                                <img src="../upload/<?php echo $photo; ?>" style="height:50px;width:50px;border-radius:8px;object-fit:cover;">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-4">
                        <div class="input-group input-group-merge">

                            <span class="input-group-text">@</span>

                            <div class="form-floating form-floating-outline">
                                <input type="file" class="form-control" name="photo4" aria-label="Upload" value="<?php echo $photo4; ?>">
                                <label for="basic-addon11">Upload Brochure (PDF/DOC/EXCEL) </label>


                            </div>
                            <a href="../upload/<?php echo $photo4; ?>" target="_blank" class="text-danger ms-2 mt-2" style="font-size:12px;">View File</a>
                
                        </div>
                    </div>



                </div>


                <div class="col-lg-6  mt-4">
                    <div class="form-floating form-floating-outline">
                        <select class="form-select" name="subcat2" id="selectError" data-rel="chosen">
                            <option>Select Category</option>
                            <?php
                            $categories = sqlfetch("SELECT * FROM `category` order by fld_order");
                            foreach ($categories as $categoreey) {
                                $select = '';
                                if (($subcat2 == ($categoreey['id'])))
                                    $select = 'selected';
                                echo '<option ' . $select . ' value="' . $categoreey['id'] . '">' . $categoreey['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <label for="floatingSelect">SELECT Category</label>
                    </div>
                </div>

                <div class="col-lg-6  mt-4">
                    <div class="form-floating form-floating-outline">
                        <select class="form-select" name="subcat" id="selectError" data-rel="chosen">
                            <option>Select Subcategory</option>
                            <?php
                            $categories = sqlfetch("SELECT * FROM `subcategory` order by fld_order");
                            foreach ($categories as $category) {
                                $select = '';
                                if (($subcat == ($category['id'])))
                                    $select = 'selected';
                                echo '<option ' . $select . ' value="' . $category['id'] . '">' . $category['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <label for="floatingSelect">SELECT Subcategory</label>
                    </div>
                </div>
                <div class="col-lg-6  mt-4">
                    <div class="form-floating form-floating-outline">
                        <select class="form-select" name="actstat" id="selectError" data-rel="chosen">
                            <option <?php if (($actstat) == '1') echo 'selected'; ?> value="1">Active</option>
                            <option <?php if (($actstat) == '0') echo 'selected'; ?> value="0">Inactive</option>
                        </select>
                        <label for="floatingSelect">Status</label>
                    </div>
                </div>

                <div class="col-lg-12 mt-4">
                    <div class="card p-4 border shadow-none" style="background: #f8faff; border-radius: 12px; border: 1px dashed #666cff !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-bell-ring-outline text-primary fs-3 me-2"></i>
                                <div>
                                    <h6 class="mb-0 text-primary">Push Notification Alert</h6>
                                    <p class="mb-0 text-muted small">Notify all subscribers about this property listing</p>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input ms-0" type="checkbox" name="send_notif" id="sendNotifProduct" value="1" style="cursor: pointer; width: 3em; height: 1.5em; margin-top: 0;">
                            </div>
                        </div>

                        <!-- Extra Notification Fields -->
                        <div id="notifFields" style="display:none; border-top: 1px dashed #666cff44; padding-top: 20px; margin-top: 15px;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control border-primary-subtle" name="notif_title" id="notif_title" placeholder="Notification Title">
                                        <label>Notification Title</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control border-primary-subtle" name="notif_link" id="notif_link" placeholder="Custom Link (Optional)">
                                        <label>Custom Link (Optional)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control border-primary-subtle" name="notif_msg" id="notif_msg" placeholder="Message Body" style="height: 80px"></textarea>
                                        <label>Notification Message / Body</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mt-4">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" placeholder="Sort Order" aria-label="Description" name="fld_order" value="<?php echo $fld_order; ?>" />
                            <label for="basic-addon11">Sort Order</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4  mt-4 ">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" aria-label="Meta Title" value="<?php echo $meta_title; ?>" />
                            <label for="basic-addon11">Meta Title</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mt-4 ">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="meta_keyword" placeholder="Meta Keyword" aria-label="Meta Keyword" value="<?php echo $meta_keyword; ?>" />
                            <label for="basic-addon11">Meta Keyword</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mt-4 ">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="meta_description" placeholder="Meta Description" aria-label="Meta Description" value="<?php echo $meta_description; ?>" />
                            <label for="basic-addon11">Meta Description</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12  mt-4">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="pro_shortdes" placeholder="Name " aria-label="Name" value="<?php echo $pro_shortdes; ?>" />
                            <label for="basic-addon11"> Location Map </label>
                        </div>
                    </div>
                </div>



                <div class="col-lg-12  mt-4">
                    <div class="input-group input-group-merge">

                        <div class="form-floating form-floating-outline">
                            <label for="basic-addon11">Description</label>
                            <br><br>
                            <textarea class="page_data editor" name="pro_additionalinfo" cols="60" rows="10"><?php echo $pro_additionalinfo; ?></textarea>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12 mt-4">
                    <div class="form-floating form-floating-outline">
                        <label for="basic-addon11">Amenities</label>
                        <br><br>
                        <textarea class="page_data editor" name="des" cols="60" rows="10"><?php echo $des; ?></textarea>
                    </div>
                </div>

                <div class="col-lg-12 mt-4">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">Select Related Blogs</h5>
                            <div class="search-box">
                                <input type="text" id="blogSearch" class="form-control form-control-sm" placeholder="Search blogs..." style="width: 250px;">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="blogList">
                                <?php
                                $all_blogs = sqlfetch("SELECT id, name FROM `offer` WHERE actstat=1 ORDER BY name ASC");
                                $selected_blogs = explode(',', $related_blogs);
                                $item_count = 0;
                                foreach ($all_blogs as $blog) {
                                    $is_checked = in_array($blog['id'], $selected_blogs) ? 'checked' : '';
                                    // Initially hide items after the first 10, unless they are checked
                                    $item_class = ($item_count < 10 || $is_checked) ? 'blog-item' : 'blog-item d-none-extra';
                                    ?>
                                    <div class="col-md-4 col-sm-6 mb-2 <?php echo $item_class; ?>" data-name="<?php echo strtolower(htmlspecialchars($blog['name'])); ?>">
                                        <div class="form-check">
                                            <input class="form-check-input blog-checkbox" type="checkbox" name="related_blogs[]" value="<?php echo $blog['id']; ?>" id="blog_<?php echo $blog['id']; ?>" <?php echo $is_checked; ?>>
                                            <label class="form-check-label" for="blog_<?php echo $blog['id']; ?>">
                                                <?php echo htmlspecialchars($blog['name']); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                    $item_count++;
                                }
                                ?>
                            </div>
                            <?php if ($item_count > 10): ?>
                            <div class="text-center mt-3" id="loadMoreContainer">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btnLoadMore">Read More</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <style>
                    .d-none-extra { display: none !important; }
                    .blog-item.filtered-out { display: none !important; }
                    .d-none-extra-prod { display: none !important; }
                    .product-item.filtered-out-prod { display: none !important; }
                </style>

                <!-- Related Products Section -->
                <div class="col-lg-12 mt-4">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0"><i class="mdi mdi-home-city-outline me-2 text-primary"></i>Select Related Products</h5>
                            <div class="search-box">
                                <input type="text" id="productSearch" class="form-control form-control-sm" placeholder="Search properties..." style="width: 250px;">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="productList">
                                <?php
                                $all_products = sqlfetch("SELECT id, name FROM `subproduct` WHERE actstat=1 ORDER BY name ASC");
                                $selected_products = !empty($related_products) ? explode(',', $related_products) : [];
                                $prod_count = 0;
                                foreach ($all_products as $prod) {
                                    // Don't show current product in selection
                                    if($prod['id'] == $pid) continue;

                                    $is_checked = in_array($prod['id'], $selected_products) ? 'checked' : '';
                                    $item_class = ($prod_count < 10 || $is_checked) ? 'product-item' : 'product-item d-none-extra-prod';
                                    ?>
                                    <div class="col-md-4 col-sm-6 mb-2 <?php echo $item_class; ?>" data-name="<?php echo strtolower(htmlspecialchars($prod['name'])); ?>">
                                        <div class="form-check">
                                            <input class="form-check-input product-checkbox" type="checkbox" name="related_products[]" value="<?php echo $prod['id']; ?>" id="prod_<?php echo $prod['id']; ?>" <?php echo $is_checked; ?>>
                                            <label class="form-check-label" for="prod_<?php echo $prod['id']; ?>">
                                                <?php echo htmlspecialchars($prod['name']); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                    $prod_count++;
                                }
                                ?>
                            </div>
                            <?php if ($prod_count > 10): ?>
                            <div class="text-center mt-3" id="loadMoreProductsContainer">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btnLoadMoreProducts">Load More</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12  mt-5">
                    <div class="input-group input-group-merge">
                        <button class="btn btn-primary waves-effect  waves-light" type="submit" value="Submit" name="<?php echo $formname; ?>">
                            <span class=" align-middle">Submit</span>
                        </button>
                    </div>
                </div>





            </div>



        </div>
    </form>




<?php
}


?>
















<?php require('include/header.php'); ?>
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span> Categories </h4>


        <?php echo $umessage; ?>


        <?php
        if (isset($_GET['edit']) and ($_GET['edit'] == 'true')) { ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Manage Here</h5>
                        <div class="card-body demo-vertical-spacing demo-only-element z4">
                            <div class="row">
                                <?php
                                $pid = $_GET['pid'];
                                $productdata = sqlfetch("SELECT * FROM `subproduct` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    subproduct_form(
                                        $product['id'] ?? '0',
                                        $product['name'] ?? '',
                                        $product['photo'] ?? '',
                                        $product['des'] ?? '',
                                        $product['photo2'] ?? '',
                                        $product['photo3'] ?? '',
                                        $product['photo4'] ?? '',
                                        $product['pro_lable'] ?? '',
                                        $product['city'] ?? '',
                                        $product['developer'] ?? '',
                                        $product['pro_specification'] ?? '',
                                        $product['pro_mainprice'] ?? '',
                                        $product['pro_discountprice'] ?? '',
                                        $product['pro_shortdes'] ?? '',
                                        $product['pro_additionalinfo'] ?? '',
                                        $product['code'] ?? '',
                                        $product['subcat2'] ?? 0,
                                        $product['select_option1'] ?? '',
                                        $product['fld_order'] ?? '0',
                                        $product['subcat'] ?? 0,
                                        $product['meta_title'] ?? '',
                                        $product['meta_keyword'] ?? '',
                                        $product['meta_description'] ?? '',
                                        $product['actstat'] ?? '',
                                        $product['related_blogs'] ?? '',
                                        $product['related_products'] ?? '',
                                        'editdone'
                                    );
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php
        } else {
        ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Manage Here</h5>
                        <div class="card-body demo-vertical-spacing demo-only-element z4">
                            <div class="row">
                                <?php subproduct_form(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>









            <div class="card">
                <div class="card-datatable dataTable_select text-nowrap table-responsive">
                    <table id="tableID" class="display dt-select-table table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                            <tr>
                                <th>S. No.</th>
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Extra Photos</th>
                                <th>Subcategory Name</th>
                                <th>Category Name</th>

                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delet</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            // Fetch subcategories from the `subcategory` table
                            $subcategories = sqlfetch("SELECT * FROM `subcategory` ORDER BY fld_order");
                            $subcategoryLookup = [];
                            foreach ($subcategories as $subcategory) {
                                $subcategoryLookup[$subcategory['id']] = $subcategory['name'];
                            }

                            // Fetch categories from the `category` table
                            $categories = sqlfetch("SELECT * FROM `category` ORDER BY fld_order");
                            $categoryLookup = [];
                            foreach ($categories as $category) {
                                $categoryLookup[$category['id']] = $category['name'];
                            }

                            // Initialize counter
                            $count = 1;

                            // Fetch the product data
                            $data = sqlfetch("SELECT * FROM `subproduct` ORDER BY fld_order");

                            // Generate the table rows
                            foreach ($data as $menu) {
                                // Get names for subcat and subcat2
                                $subcatName = isset($subcategoryLookup[$menu['subcat']]) ? $subcategoryLookup[$menu['subcat']] : '';
                                $subcat2Name = isset($categoryLookup[$menu['subcat2']]) ? $categoryLookup[$menu['subcat2']] : '';
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $menu['name']; ?></td>
                                    <td>
                                        <?php 
                                            $imagePath = "../upload/" . $menu['photo'];
                                            if (!empty($menu['photo']) && file_exists($imagePath)) { 
                                                $displayImg = $imagePath; 
                                            } else { 
                                                $displayImg = "../upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
                                            }
                                        ?>
                                        <img style="height: 32px;width: 32px;" src="<?php echo $displayImg; ?>" class="img-responsive">
                                    </td>
                                    <td>
                                        <?php 
                                        $ex_photos = sqlfetch("SELECT COUNT(*) as count FROM product_images WHERE product_id='".$menu['id']."'");
                                        $count_p = $ex_photos[0]['count'] ?? 0;
                                        if($count_p > 0) echo '<span class="badge bg-primary">'.$count_p.' photos</span>';
                                        else echo '<span class="text-muted">—</span>';
                                        ?>
                                    </td>

                                    <!-- Separate columns for subcat and subcat2 -->
                                    <td><?php echo $subcatName; ?></td>
                                    <td><?php echo $subcat2Name; ?></td>

                                    <td><?php echo $menu['fld_order']; ?></td>
                                    <td><?php echo get_active_status_text($menu['actstat']); ?></td>

                                    <td>
                                        <a class="ajax-link" href="product.php?pid=<?php echo $menu['id']; ?>&edit=true">
                                            <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"><i class="fa fa-pencil"></i></button>
                                        </a>
                                    </td>

                                    <td>
                                        <a href="product.php?id=<?php echo $menu['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                            <button type="submit" class="btn btn-xs btn-danger pull-right" style="margin:0px 10px;" name="delete"><i class="fa-solid fa-trash"></i></button>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>










        <?php } ?>
        <script>
            // Run functions when the page is loaded
            window.onload = function() {
                generateCode();
                const city = document.getElementById('product_city').value;
                if(city) fetchDevelopers(city);
            };

            function generateCode() {
                // Define the characters to use for the random code
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

                // Set the length of the random code
                const codeLength = 8;

                // Generate the random code
                let randomCode = '';
                for (let i = 0; i < codeLength; i++) {
                    const randomIndex = Math.floor(Math.random() * characters.length);
                    randomCode += characters.charAt(randomIndex);
                }

                // Set the generated code to the input field
                document.getElementById('code').value = randomCode;
            }

            function fetchDevelopers(city) {
                const dropdown = document.getElementById('developer_dropdown');
                if (!city) {
                    dropdown.innerHTML = '<option value="">Select Developer</option>';
                    return;
                }

                fetch('get_developers.php?city=' + encodeURIComponent(city))
                    .then(response => response.json())
                    .then(data => {
                        const currentVal = dropdown.value;
                        dropdown.innerHTML = '<option value="">Select Developer</option>';
                        data.forEach(dev => {
                            const option = document.createElement('option');
                            option.value = dev;
                            option.text = dev;
                            if(dev === currentVal) option.selected = true;
                            dropdown.add(option);
                        });
                    });
            }

            // --- Blog Selection Logic ---
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('blogSearch');
                const btnLoadMore = document.getElementById('btnLoadMore');
                const blogItems = document.querySelectorAll('.blog-item');
                let showingAll = false;

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase().trim();
                        blogItems.forEach(item => {
                            const name = item.getAttribute('data-name');
                            if (name.includes(query)) {
                                item.classList.remove('filtered-out');
                                // If searching, remove pagination restriction for matches
                                if(query !== "") {
                                    item.classList.remove('d-none-extra');
                                } else if(!showingAll) {
                                    // Reset to pagination if search is cleared
                                    const index = Array.from(blogItems).indexOf(item);
                                    const isChecked = item.querySelector('.blog-checkbox').checked;
                                    if(index >= 10 && !isChecked) {
                                        item.classList.add('d-none-extra');
                                    }
                                }
                            } else {
                                item.classList.add('filtered-out');
                            }
                        });
                        
                        // Hide Load More during search
                        if(btnLoadMore) {
                            btnLoadMore.parentElement.style.display = query === "" && !showingAll ? "block" : "none";
                        }
                    });
                }

                if (btnLoadMore) {
                    btnLoadMore.addEventListener('click', function() {
                        blogItems.forEach(item => {
                            item.classList.remove('d-none-extra');
                        });
                        this.parentElement.style.display = 'none';
                        showingAll = true;
                    });
                }

                // Push Notification Field Handling
                const sendNotifCheck = document.getElementById('sendNotifProduct');
                const notifFields = document.getElementById('notifFields');
                if (sendNotifCheck) {
                    sendNotifCheck.addEventListener('change', function() {
                        notifFields.style.display = this.checked ? 'block' : 'none';
                        if (this.checked) {
                            const itemName = document.querySelector('input[name="name"]').value;
                            document.getElementById('notif_title').value = itemName || "New Property Listing";
                            if (!document.getElementById('notif_msg').value) {
                                document.getElementById('notif_msg').value = itemName ? "Check out our newest property: " + itemName : "Check out our newest property listing!";
                            }
                        }
                    });
                }

                // --- Product Selection Logic ---
                const productSearchInput = document.getElementById('productSearch');
                const btnLoadMoreProducts = document.getElementById('btnLoadMoreProducts');
                const productItems = document.querySelectorAll('.product-item');
                let showingAllProducts = false;

                if (productSearchInput) {
                    productSearchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase().trim();
                        productItems.forEach(item => {
                            const name = item.getAttribute('data-name');
                            if (name.includes(query)) {
                                item.classList.remove('filtered-out-prod');
                                if (query !== "") {
                                    item.classList.remove('d-none-extra-prod');
                                } else if (!showingAllProducts) {
                                    const index = Array.from(productItems).indexOf(item);
                                    const isChecked = item.querySelector('.product-checkbox').checked;
                                    if (index >= 10 && !isChecked) {
                                        item.classList.add('d-none-extra-prod');
                                    }
                                }
                            } else {
                                item.classList.add('filtered-out-prod');
                            }
                        });
                        if (btnLoadMoreProducts) {
                            btnLoadMoreProducts.parentElement.style.display = query === "" && !showingAllProducts ? "block" : "none";
                        }
                    });
                }

                if (btnLoadMoreProducts) {
                    btnLoadMoreProducts.addEventListener('click', function() {
                        productItems.forEach(item => {
                            item.classList.remove('d-none-extra-prod');
                        });
                        this.parentElement.style.display = 'none';
                        showingAllProducts = true;
                    });
                }
            });
        </script>

        <?php require('include/footer.php'); ?>