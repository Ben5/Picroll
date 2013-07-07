<h3>Login</h3>

<div class='section left'>
    <h3>Sign In</h3>

    <form name='SignInForm' action='LogInToAccount' method='POST'>
        Username:           <br><input type='text'     name='username'/>    <br><br>
        Password:           <br><input type='password' name='password'/>    <br><br>

        <input type='submit' value='Sign In'>
    </form>
</div>

<div class='section right'>
    <h3>Create Account</h3>

    <form name='CreateAccountForm' action='CreateAccount' method='POST'>
        Username:           <br><input type='text'     name='username'/>    <br><br>
        Password:           <br><input type='password' name='password'/>    <br><br>
        Re-enter Password:  <br><input type='password' name='repassword'/>  <br><br>
        Email Address:      <br><input type='email'    name='email'/>       <br><br>

        <input type='submit' value='Create Account'>
    </form>
</div>
