<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('./function/function.php');
check_session();
require('include/header.php'); ?>


  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row gy-4">
        <!-- Gamification Card -->
        <div class="col-md-12 col-lg-12">
          <div class="card h-100">
            <div class="d-flex align-items-end row">
              <div class="col-md-8 order-2 order-md-1">
                <div class="card-body">
                  <h4 class="card-title pb-xl-2">Welcome  <?php echo $siteTitle; ?> Admin !🎉</h4>
                  <p class="mb-0">You have done <span class="h6 mb-0">68%</span>😎 more
                    sales today.</p>
                  <p>Check your new badge in your profile.</p>
                  <a href="javascript:;" class="btn btn-primary">View Profile</a>
                </div>
              </div>
              <div class="col-md-4 text-center text-md-end order-1 order-md-2">
                <div class="card-body pb-0 px-0 px-md-4 ps-0">
                  <img src="assets/img/illustrations/illustration-john-light.png" height="180" alt="View Profile" data-app-light-img="illustrations/illustration-john-light.png" data-app-dark-img="illustrations/illustration-john-dark.html">
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--/ Gamification Card -->

        









        

        <div class="col-md-3 col-sm-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div class="avatar">
                  <div class="avatar-initial bg-label-success rounded">
                    <i class="mdi mdi-currency-usd mdi-24px"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0 text-success me-1">+38%</p>
                  <i class="mdi mdi-chevron-up text-success"></i>
                </div>
              </div>
              <div class="card-info mt-4 pt-3">
                <h5 class="mb-2">$13.4k</h5>
                <p class="text-body">Total Sales</p>
                <div class="badge bg-label-secondary rounded-pill mt-1">Last Six
                  Month </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div class="avatar">
                  <div class="avatar-initial bg-label-success rounded">
                    <i class="mdi mdi-currency-usd mdi-24px"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0 text-success me-1">+38%</p>
                  <i class="mdi mdi-chevron-up text-success"></i>
                </div>
              </div>
              <div class="card-info mt-4 pt-3">
                <h5 class="mb-2">$13.4k</h5>
                <p class="text-body">Total Sales</p>
                <div class="badge bg-label-secondary rounded-pill mt-1">Last Six
                  Month </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div class="avatar">
                  <div class="avatar-initial bg-label-success rounded">
                    <i class="mdi mdi-currency-usd mdi-24px"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0 text-success me-1">+38%</p>
                  <i class="mdi mdi-chevron-up text-success"></i>
                </div>
              </div>
              <div class="card-info mt-4 pt-3">
                <h5 class="mb-2">$13.4k</h5>
                <p class="text-body">Total Sales</p>
                <div class="badge bg-label-secondary rounded-pill mt-1">Last Six
                  Month </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div class="avatar">
                  <div class="avatar-initial bg-label-info rounded">
                    <i class="mdi mdi-link mdi-24px"></i>
                  </div>
                </div>
                <div class="d-flex align-items-center">
                  <p class="mb-0 text-success me-1">+62%</p>
                  <i class="mdi mdi-chevron-up text-success"></i>
                </div>
              </div>
              <div class="card-info mt-4 pt-4">
                <h5 class="mb-2">142.8k</h5>
                <p class="text-body">Total Impression</p>
                <div class="badge bg-label-secondary rounded-pill">Last One Year
                </div>
              </div>
            </div>
          </div>
        </div>

        













      </div>
    </div>
    



    <?php include 'include/footer.php' ?>