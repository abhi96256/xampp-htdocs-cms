<?php include __DIR__ . '/../config.php' ?>

<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); } // Start the session

// Retrieve the admin's name and ID from the session
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 'Not available'; // Default value if not set

?>



<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-semi-dark" data-assets-path="assets/" data-template="vertical-menu-template-semi-dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php echo $siteTitle; ?> </title>


    <meta name="description" content="" />
    <meta name="keywords" content="">
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://1.envato.market/materialize_admin">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://demos.pixinvent.com/materialize-html-admin-template/assets/img/favicon/favicon.ico?v=2" />


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;ampdisplay=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-select-bs5/select.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css">

    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/vendor/js/template-customizer.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js">
    </script>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/typography.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/editor.css" />

    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/vendor/js/template-customizer.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


   

<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);

// Only load TinyMCE if the current page is not application_seating.php
if ($current_page !== 'application_seating.php') {
?>
   
   
   


<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/nvvrxu83uomwkvmxruko3nxpvantijx4098z1vj6jza3fjio/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/jv6stpinm931j6dzau3eopauuh773pn7cf30in869gx1oo2n/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: [
      // Core editing features
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      // Your account includes a free trial of TinyMCE premium features
      // Try the most popular premium features until Feb 17, 2025:
      'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
  });
</script>


   
   
   
   
   
   
   
   
   
   
<?php
}
?>



</head>

<script type="text/javascript">
    function toggle(source) {
        var aInputs = document.getElementsByTagName('input');
        for (var i = 0; i < aInputs.length; i++) {
            if (aInputs[i] != source && aInputs[i].className == source.className) {
                aInputs[i].checked = source.checked;
            }
        }
    }
</script>


<body>
    <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>
    
    
    
    
    
    
    
    

    
    
    
      <!-- Layout wrapper -->
             <div class="layout-wrapper layout-content-navbar  ">
            <div class="layout-container">
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">


                    <div class="app-brand demo ">
                        <a href="index.php" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <span style="color:var(--bs-primary);">
                                    <svg width="268" height="150" viewBox="0 0 38 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z" fill="currentColor" />
                                        <path d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z" fill="url(#paint0_linear_2989_100980)" fill-opacity="0.4" />
                                        <path d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z" fill="currentColor" />
                                        <path d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z" fill="currentColor" />
                                        <path d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z" fill="url(#paint1_linear_2989_100980)" fill-opacity="0.4" />
                                        <path d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z" fill="currentColor" />
                                        <defs>
                                            <linearGradient id="paint0_linear_2989_100980" x1="5.36642" y1="0.849138" x2="10.532" y2="24.104" gradientUnits="userSpaceOnUse">
                                                <stop offset="0" stop-opacity="1" />
                                                <stop offset="1" stop-opacity="0" />
                                            </linearGradient>
                                            <linearGradient id="paint1_linear_2989_100980" x1="5.19475" y1="0.849139" x2="10.3357" y2="24.1155" gradientUnits="userSpaceOnUse">
                                                <stop offset="0" stop-opacity="1" />
                                                <stop offset="1" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </span>
                            </span>
                            <span class="app-brand-text demo menu-text fw-bold ms-2">Admin</span>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z" fill="currentColor" fill-opacity="0.6" />
                                <path d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z" fill="currentColor" fill-opacity="0.38" />
                            </svg>
                        </a>
                    </div>

                    <div class="menu-inner-shadow"></div>



                  
                  <?php

// Include your database connection file
include_once 'function/function.php'; // This must contain the $pdo object

// Initialize an empty string for the output message
$outputMessage = ""; // Initialize message string

// Initialize an array for menu permissions
$menuPermissions = [];

// Check if admin_id is set in session
if (isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];

    // Fetch the subadmin record
    $result = sqlfetch("SELECT * FROM subadmin WHERE id = $adminId"); // Use WHERE clause correctly

    // Debugging: Check result
    if ($result === false) {
        $outputMessage .= "<h2>Error fetching data.</h2>";
    } else {
        if (count($result) > 0) { // Check if there are results
            foreach ($result as $subadmin) {
                // Decode permissions JSON
                $permissions = json_decode($subadmin['permissions'], true); // Decode JSON string to PHP array
                if (json_last_error() === JSON_ERROR_NONE) { // Check for JSON decode errors
                    $menuPermissions = $permissions; // Assign permissions to menu permissions array
                } else {
                    $outputMessage .= "<h2>Error decoding permissions.</h2>"; // Error handling for JSON decode
                }
            }
        } else {
            $outputMessage .= "<h2>No subadmin found for this admin ID.</h2>"; // Message if no subadmin found
        }
    }
} else {
    $outputMessage .= "<h2>Error: Admin ID is not set.</h2>"; // Message if admin ID is not set
}

// Define menu items and their corresponding sub-items
$menuItems = [
    
    "Dashboards" => [
        ["name" => "Web Setting", "link" => "application_seating.php?&pid=1&edit=true"],
        ["name" => "View Frontend", "link" => "../"],
    ],
    
    
    "Manage Front Page" => [
        ["name" => "Manage Slider", "link" => "banner.php"],
        ["name" => "About Us", "link" => "about_us.php?&pid=5&edit=true"],
        ["name" => "Manage News Marque", "link" => "news.php"],
        ["name" => "Pop-Up Image", "link" => "team.php"]
    ],
    "Manage About Us" => [
        ["name" => "About Us", "link" => "about_us.php?&pid=2&edit=true"],
        ["name" => "Vision", "link" => "about_us.php?&pid=3&edit=true"],
        ["name" => "Mission", "link" => "about_us.php?&pid=4&edit=true"],
        ["name" => "Customer Feedback", "link" => "testimonial.php"]
    ],
    "Manage Blogs" => [
        ["name" => "Add Blogs", "link" => "offer.php"]
    ],
    "Manage Other Pages" => [
        ["name" => "Terms And Conditions", "link" => "about_us.php?&pid=12&edit=true"],
        ["name" => "Privacy Policy", "link" => "about_us.php?&pid=11&edit=true"]
    ],
    "Manage Projects" => [
        ["name" => "Residential Projects", "link" => "client_logo.php"],
        ["name" => "Commercial Projects", "link" => "certification.php"]
    ],
    "Manage Products" => [
        ["name" => "Add Category", "link" => "category.php"],
        ["name" => "Add Subcategory", "link" => "subcategory.php"],
        ["name" => "Add Products", "link" => "product.php"],
        ["name" => "Add Products Gallery", "link" => "sub_image.php"]
    ],
    "Manage Career" => [
        ["name" => "Add Career", "link" => "blog.php"],
        ["name" => "Job Applications", "link" => "job_applications.php"]
    ],
    "Manage Query" => [
        ["name" => "Query List", "link" => "client.php"]
    ],
    "Manage Notifications" => [
        ["name" => "Push Notifications", "link" => "notifications.php"]
    ]
];

// Generate menu based on permissions
$menuOutput = "<ul class='menu-inner py-1'>";

// Iterate over menu items and check permissions
foreach ($menuItems as $menuTitle => $subItems) {
    // Check if the current menu item exists in the permissions array
    if (in_array($menuTitle, $menuPermissions)) {
        $menuOutput .= "<li class='menu-item'>";
        $menuOutput .= "<a href='javascript:void(0);' class='menu-link menu-toggle'>";
        $menuOutput .= "<i class='menu-icon tf-icons mdi mdi-folder'></i>";
        $menuOutput .= "<div data-i18n='{$menuTitle}'>{$menuTitle}</div>";
        $menuOutput .= "</a>";
        $menuOutput .= "<ul class='menu-sub'>";

        foreach ($subItems as $subItem) {
            // Check if the sub-item's parent menu exists in permissions
            if (in_array($menuTitle, $menuPermissions)) {
                $menuOutput .= "<li class='menu-item'>";
                $menuOutput .= "<a href='{$subItem['link']}' class='menu-link'>";
                $menuOutput .= "<div data-i18n='{$subItem['name']}'>{$subItem['name']}</div>";
                $menuOutput .= "</a>";
                $menuOutput .= "</li>";
            }
        }

        $menuOutput .= "</ul></li>";
    }
}

$menuOutput .= "</ul>";

// Combine the output messages
$outputMessage .= $menuOutput;

// Display the output message on the page
echo $outputMessage; // Output the complete message
?>


                </aside>
                  <div class="layout-page">
                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">



                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="mdi mdi-menu mdi-24px"></i>
                            </a>
                        </div>


                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


                         





                            <ul class="navbar-nav flex-row align-items-center ms-auto">



<?php


try {
    // Database connection parameters
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
    if (strpos($host, 'a2prealtech.com') !== false) {
        $dsn = "mysql:host=localhost;dbname=u615712904_a2p;charset=utf8mb4";
        $username = "u615712904_a2p";
        $password = "VermaA2p@#9717";
    } else {
        $dsn = "mysql:host=localhost;dbname=u435351083_cms;charset=utf8mb4";
        $username = "u435351083_jms";
        $password = "Maydivjms1@3";
    }

    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure $admin_id is set and sanitize it
    if (!isset($admin_id) || empty($admin_id)) {
        throw new Exception("Admin ID is not provided.");
    }
    $admin_id = htmlspecialchars($admin_id);

    // Prepare and execute the SQL query
    $query = "SELECT * FROM `subadmin` WHERE id = :admin_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the results
    $data = $stmt->fetchAll();

    // Check if data is returned
    if (empty($data)) {
        throw new Exception("No admin found with ID: " . htmlspecialchars($admin_id));
    }

    // Loop through the results and display the name and photo
    foreach ($data as $menu) {
        // Display name in <h1>
        // echo "<h1>" . htmlspecialchars($menu['name']) . "</h1>";

        // Display photo if it exists
        if (!empty($menu['photo'])) {
            $photoPath = "../upload/" . htmlspecialchars($menu['photo']);
            // echo "<img src='" . $photoPath . "' alt='Admin Photo' />";
        } else {
            // echo "<p>No photo available.</p>";
        }
    }
} catch (PDOException $e) {
    // Display database connection errors
    echo "<h1>Database Error: " . $e->getMessage() . "</h1>";
} catch (Exception $e) {
    // Display general errors
    echo "<h1>Error: " . $e->getMessage() . "</h1>";
}
?>
                                     <?php
// Ensure that the admin's data is fetched correctly and store the photo path
foreach ($data as $menu) {
    // Display the photo in the dropdown
    $photoPath = "../upload/" . htmlspecialchars($menu['photo']); // Assuming 'photo' is the column holding the image filename
    $admin_name = htmlspecialchars($menu['name']); // Assuming 'name' is the admin's name
    $admin_id = htmlspecialchars($menu['id']); // Assuming 'id' is the admin's ID
    ?>



                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <!-- Display the photo if available -->
                                        <img src="<?php echo $photoPath; ?>" alt="Avatar" class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
  
                                    
                                        <li>
                                            <a class="dropdown-item" href="index.php">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                            <!-- Use the fetched photo path -->
                                                            <img src="<?php echo $photoPath; ?>" alt="Admin Photo" class="w-px-40 h-auto rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <!-- Display admin name and ID -->
                                                        <span class="fw-medium d-block"><?php echo $admin_name; ?> <?php echo $admin_id; ?></span>
                                                        <small class="text-muted">Admin</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>



                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                       

                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="logout.php">
                                                <i class="mdi mdi-logout me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
<?php } ?>


                            </ul>
                        </div>


                        <!-- Search Small Screens -->
                        <div class="navbar-search-wrapper search-input-wrapper  d-none">
                            <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search...">
                            <i class="mdi mdi-close search-toggler cursor-pointer"></i>
                        </div>



                    </nav>
                <?php } ?>
