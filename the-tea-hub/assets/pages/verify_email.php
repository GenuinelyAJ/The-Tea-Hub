<?php
  global $user;
?>

<div class="login">
  <div class="col-md-4 col-sm-11 bg-white border rounded p-4 shadow-sm">
    <!-- Using a form to get the 6-Digit number code -->
    <form method="post" action="assets/php/actions.php?verify_email">
      <div class="d-flex justify-content-center">
      </div>
      <h1 class="h5 mb-3 fw-normal">Verify your Email</h1>

      <p>Enter the 6-Digit OTP sent to - (<?=$user['email']?>)</p>
      <div class="form-floating mt-1">
        <input type="text" name="code" class="form-control rounded-0" id="floatingPassword" placeholder="6-Digits">
        <label for="floatingPassword">OTP</label>
      </div>
      <!-- To show if the verification email is sent to the user -->
      <?php
        if(isset($_GET['resended'])){
      ?>
      <p class="text-success">OTP resent to <?=$user['email']?></p>
      <?php
        }
      ?>
      <!-- Error Message -->
      <?=showError('email_verify')?>

      <div class="mt-3 d-flex justify-content-between align-items-center">
        <button class="btn btn-primary" type="submit">Verify Email</button>

        <!-- Calling the resend_code function to send a code again to the user -->
        <a href="assets/php/actions.php?resend_code" class="text-decoration-none" type="submit">Resend OTP</a>
      </div>
      <br>
      <a href="assets/php/actions.php?logout" class="text-decoration-none mt-5">
        <i class="bi bi-arrow-left-circle-fill"></i> Logout
      </a>
    </form>
  </div>
</div>


   