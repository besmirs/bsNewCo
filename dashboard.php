<?php
session_start();
include('mysqli.config.php');
require_once('functions.php');

if(!isset($_SESSION['uid'])) : 
  header("Location: index.php");
endif;

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>NewCo Admin CMS</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/jquery.datepick.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />

  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="dashboard.php?page=home"><img src="assets/images/logo-mini.svg" alt="logo" style="width: auto;" /> <span style="padding-left:10px; font-size: 20px; font-weight: bold; color: #a956a9;">NewCo</span></a>
          <a class="navbar-brand brand-logo-mini" href="dashboard.php?page=home"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>

          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link" href="#">
                <div class="nav-profile-img">
                  <img src="assets/images/faces/face1.jpg" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">Besmir Sadiku</p>
                </div>
              </a>
            </li>


            <li class="nav-item nav-logout d-none d-lg-block">
              <a class="nav-link" href="logout.php">
                <i class="mdi mdi-power"></i>
              </a>
            </li>

          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">

        <!-- partial:partials/_sidebar.php -->
        <?php include_once('partials/_sidebar.php'); ?>
        <!-- partial -->

        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> <?php echo breadcrumb($_GET['page']); ?> </h3>

              <nav aria-label="breadcrumb">
              </nav>
            </div>

            <?php

            /* This piece of code checks if 'page' variable is set,
               then looks if files exist in directory and returns the content back */
              if(isset($_GET['page']) && !empty($_GET['page'])) :
                if(file_exists("pages/".convertpage(htmlentities($_GET['page'])).".php")) {
                    include("pages/".convertpage(htmlentities($_GET['page'])).".php");
                } else {
                    include("pages/default.php");
                }
              endif;
            ?>
            
          </div>
          <!-- content-wrapper ends -->

          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-center text-sm-left d-block d-sm-inline-block">Besmir Sadiku 23/03/2020</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
            </div>
          </footer>
          <!-- partial -->

        </div>
        <!-- main-panel ends -->

      </div>
      <!-- page-body-wrapper ends -->

    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- End custom js for this page -->
    <!-- Include the Quill library -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="assets/js/jquery.plugin.min.js"></script>
    <script src="assets/js/jquery.datepick.js"></script>

    <!-- Initialize Quill editor -->
    <script>

      $(function() {
        
        $('#product_validity').datepick({dateFormat: 'yyyy-mm-dd'});

        $("#as_name").keyup(function(event) {

          let str = $(this).val().toLowerCase();
          let return_str = str.split(" ");

          let num = Math.floor(1000 + Math.random() * 9000);
          $("#as_username").val(return_str[0] + num);
        });

        $(".reveal").on('click', function() {
          let $pass = $("input#as_password");
          if ($pass.attr('type') === 'password') {
            $pass.attr('type', 'text');
          } else {
            $pass.attr('type', 'password');
          }
        });

        function randomPassword(length) {
          const chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
          let pass = "";
          for (let x = 0; x < length; x++) {
              let i = Math.floor(Math.random() * chars.length);
              pass += chars.charAt(i);
          }
          return pass;
        }

        $('#generatePass').on('click', function() {
          $("input#as_password").val(randomPassword(8));
        });


        $('select#productsAll').change(function() {
          let product = $(this).val();
          $.ajax({
            url: 'data.php',
            method: 'post',
            data: 'productId=' + product
          }).done(function(services){
              services = JSON.parse(services);
              $('select#servicesAll').empty();
              for (let i = 0, len = services.length; i < len; i++) {
                let str = services[i];
                let arr = str.split("-");
                $('select#servicesAll').append('<option value="' + arr[0] + '">' + arr[1] +'</option>')
              }
              
          })
        });

        $('select#sell_shop').change(function() {
          let shop = $(this).val();

          $.ajax({
            url: 'data.php',
            method: 'post',
            data: 'shopId=' + shop
          }).done(function(shop_assistants){
              shop_assistants = JSON.parse(shop_assistants);
              console.log(shop_assistants);
              $('select#shopAssistentAll').empty();
              for (let i = 0, len = shop_assistants.length; i < len; i++) {
                let str = shop_assistants[i];
                let arr = str.split("-");
                $('select#shopAssistentAll').append('<option value="' + arr[0] + '">' + arr[1] +'</option>')
              }
              
          })
        });   
        
        $('.showQuery').click(function(){
          var data = $(this).data('extra');
          $('.queryCode' + data).slideToggle();

        });             

      });

    </script>
  </body>
</html>