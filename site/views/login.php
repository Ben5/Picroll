<div class="row">
    <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-1">
        <div class="section well">
            <h3>Sign In</h3>

<?php if (isset($errorMessage)) echo $errorMessage; ?>
            <form name="SignInForm" action="LogInToAccount" method="POST" role="form">
                <div class="form-group">
                    <label for="username"> Username </label>
                    <input type="text" class="form-control" name="username" id="username"/>
                </div>
                <div class="form-group">
                    <label for="password"> Password </label>
                    <input type="password" class="form-control" name="password" id="password"/>
                </div>

                <input type="submit" class="btn btn-primary" value="Sign In">
            </form>
        </div>
    </div>

    <div class="col-xs-12 col-sm-5 col-md-4 col-md-offset-2">
        <div class="section well">
            <h3>Create Account</h3>

            <form name="CreateAccountForm" action="CreateAccount" method="POST" role="form">
                <div class="form-group">
                    <label for="newUsername"> Username </label>
                    <input type="text" class="form-control" name="username" id="newUsername"/>
                </div>
                <div class="form-group">
                    <label for="newPassword"> Password </label>
                    <input type="password" class="form-control" name="password" id="newPassword"/>  
                </div>
                <div class="form-group">
                    <label for="repassword"> Re-enter Password </label>
                    <input type="password" class="form-control" name="repassword" id="repassword"/>
                </div>
                <div class="form-group">
                    <label for="email"> Email Address </label>
                    <input type="email" class="form-control" name="email" id="email"/>
                </div>

                <input type="submit" class="btn btn-primary" value="Create Account">
            </form>
        </div>
    </div>
</div>
