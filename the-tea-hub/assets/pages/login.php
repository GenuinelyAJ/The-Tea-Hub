<div class="login">
    <div class="col-sm-12 col-md-4 bg-white border rounded p-4 shadow-sm">
        <!-- Using a form to call an action to retrieve the users information -->
        <form method="post" action="assets/php/actions.php?login">
            <div class="d-flex justify-content-center">
                <img class="mb-4" src="assets/images/TTH.png" alt="" height="45">
            </div>
            <h1 class="h5 mb-3 fw-normal">Login to your TTH account</h1>

            <div class="form-floating">
                <input type="text" name="username_email" value="<?=showFormData('username_email')?>" class="form-control rounded-0" placeholder="username/email">
                <label for="floatingInput">Username or Email</label>
            </div>
            <?=showError('username_email')?>

            <div class="form-floating mt-1">
                <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <a href="?forgotpassword&newfp" class="text-decoration-none">Forgot password?</a>
            <!-- Error Messages -->
            <?=showError('password')?>
            <?=showError('checkuser')?>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit">Sign in</button>
                <a href="?signup" class="text-decoration-none">Create A TTH Account</a>
            </div>
        </form>
    </div>
</div>