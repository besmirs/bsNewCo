<?php
session_start();
include('mysqli.config.php');

/*** If user is logged in succesfully, when clicking 'back arrow' 
on browser will redirect to dashboard,
use logout button instead to unset session ***/
if(isset($_SESSION['uid'])) : 
  header("Location: dashboard.php");
endif;




if(isset($_POST['btn_login'])) :

  if(!empty($_POST['username']) && 
     !empty($_POST['password'])) 
  {

  $user = strip_tags($_POST['username']);
  $pass = strip_tags($_POST['password']);

  $query = "CALL getLoginData('$user')";
  $result = mysqli_query($link, $query);

  $row = mysqli_fetch_array($result);

  if(mysqli_num_rows($result) == 1) {

    if(password_verify($pass, $row['use_password'])) {

      $_SESSION['uid'] = $row['use_id'];
      header("Location: dashboard.php?page=home");

    } else {

      $message = "Invalid password!";
      $alert = "danger";

    }

  } else {
    $message = "Login credentials are invalid!";
    $alert = "danger";
  }

} else {
  $message = "All fields are required!";
  $alert = "danger";
}

endif;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>NewCo Admin Panel</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
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
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <h4>NewCo! Admin panel</h4>
                <h6 class="font-weight-light">Sign in to continue.</h6>
                
                <?php
                  if(isset($message)) :
                ?>
                  <div class="alert alert-<?= $alert; ?> fade show" role="alert">
                    <?= $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                <?php
                  endif;
                ?>


                <form class="pt-3" method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
                  <div class="form-group">
                    <input type="text" name="username" class="form-control form-control-lg" id="InputUsername" placeholder="Username">
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  <div class="mt-3">
                    <input type="submit" name="btn_login" value="LOG IN" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" />
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                    </div>
                    <a href="#" class="auth-link text-black">Forgot password?</a>
                  </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>