<?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>

    </div>
<?php } ?>
<?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>

    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
            <div class="footer-container align-items-center justify-content-between py-3 flex-md-row flex-column">
                <center>
                    <div class="mb-2 mb-md-0">
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <span class="text-danger">
                            <i class="tf-icons mdi mdi-heart"></i></span>
                        by <a href="#" class="footer-link fw-medium"><?php echo $siteTitle; ?> </a>
                    </div>
                </center>

            </div>
        </div>
    </footer>
<?php } ?>


<div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>




<div class="layout-overlay layout-menu-toggle"></div>



<div class="drag-target"></div>

</div>


<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/libs/hammer/hammer.js"></script>
<script src="assets/vendor/libs/i18n/i18n.js"></script>
<script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
<script src="assets/vendor/js/menu.js"></script>
<script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="assets/vendor/libs/moment/moment.js"></script>
<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script src="assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
<script src="assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
<script src="assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>

<script src="assets/js/main.js"></script>







 <script src="assets/vendor/libs/select2/select2.js"></script>
<script src="assets/vendor/libs/tagify/tagify.js"></script>
<script src="assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
<script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
<script src="assets/vendor/libs/bloodhound/bloodhound.js"></script>

  <!-- Main JS -->
  <script src="assets/js/main.js"></script>
  

  <!-- Page JS -->
  <script src="assets/js/forms-selects.js"></script>
<script src="assets/js/forms-tagify.js"></script>
<script src="assets/js/forms-typeahead.js"></script>
<script src="assets/js/main.js"></script>

<!-- <script src="assets/js/tables-datatables-basic.js"></script> -->

<script>
    /* Initialization of datatable */
    $(document).ready(function() {
        $('#tableID').DataTable({});
    });
</script>
<!-- Quill v2 CSS -->
<script>
  ClassicEditor
    .create(document.querySelector('#mytextarea'))
    .catch(error => console.error(error));
	ClassicEditor
    .create(document.querySelector('#mytextarea1'))
    .catch(error => console.error(error));
</script>
</body>

</html>