<?php

use Reverb\SiteConfig;

require_once SiteConfig::REVERB_ROOT."/system/componentbase.php";
require_once SiteConfig::SITE_ROOT."/models/user.php";

class Login extends ComponentBase
{
    /* Login::Index()
     * Default action, will show the Log In and Create Account forms
     */
    protected function
    Index($params)
    {
    }

    /* Login::LogInToAccount()
     * Called by submitting the Log In form. Verifies the credentials and redirects to home page, 
     * or shows unsuccessful message on failure.
     */
    protected function
    LogInToAccount($params)
    {
        // validate the inputs (username, password)
        $expectedKeys = array('username'    => 'string',
                              'password'    => 'string');
        try
        {
            $this->ValidateParams($params, $expectedKeys);
        }
        catch (Exception $e)
        {
            $errorMessage = $e->getMessage();
            $this->ExposeVariable('errorMessage', $errorMessage);
            return;
        }

        foreach(array_keys($expectedKeys) as $key)
        {
            $$key = $params[$key];
        }

        // get a user model
        $userModel = new UserModel();

        // get the salt and salted hashed password for the requested user
        $user = $userModel->TryGetUserByName($username);
        if ($user === false)
        {
            $errorMessage = 'User not found with username "'.$username.'".';
            $this->ExposeVariable('errorMessage', $errorMessage);
            return;
        }

        // salt the provided password then hash it and compare to the value from the DB.
        $enteredPassword = hash('sha256', $user['generated_salt'].$password);
        if ($enteredPassword !== $user['password_hash'])
        {
            $errorMessage = 'Incorrect password entered.';
            $this->ExposeVariable('errorMessage', $errorMessage);
            return;
        }

        // user created, redirect to home page
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $username;
        header('Location: '.SiteConfig::DEFAULT_PAGE_AFTER_LOGIN);
    }

    /* Login::CreateAccount()
     * Called by submitting the Create Account form. Verifies that password fields match, then creates an account.
     * Redirects to home page on success, or shows message on failure
     */
    protected function
    CreateAccount($params)
    {
        // validate the inputs (username, email, password, repassword)
        $expectedKeys = array('username'    => 'string',
                              'password'    => 'string',
                              'repassword'  => 'string',
                              'email'       => 'string');
        try
        {
            $this->ValidateParams($params, $expectedKeys);
        }
        catch (Exception $e)
        {
            $errorMessage = $e->getMessage();
            $this->ExposeVariable('errorMessage', $errorMessage);
            return;
        }

        foreach(array_keys($expectedKeys) as $key)
        {
            $$key = $params[$key];
        }

        if ($password !== $repassword)
        {
            $errorMessage = 'Your passwords didn\'t match, please try again.';
            $this->ExposeVariable('errorMessage', $errorMessage);
            return;
        }

        // check the username isn't already taken
        $userModel = new UserModel();
        $user = $userModel->TryGetUserByName($username);

        if ($user != false)
        {
            $errorMessage = 'Username "'.$username.'" is already taken.';
            $this->ExposeVariable('errorMessage', $errorMessage);
            return;
        }

        // generate a random salt for the user
        $saltBytes = openssl_random_pseudo_bytes(32);
        $saltHex   = bin2hex($saltBytes);

        // salt and hash the password
        $hashedPassword = hash('sha256', $saltHex.$password);

        // create a new row in the user table
        $userId = $userModel->AddNewUser($username, $saltHex, $hashedPassword, $email);
        if ($userId === false)
        {
            $errorMessage = 'Failed to add user';
            $this->ExposeVariable('errorMessage', $errorMessage);
            return;
        }

        // user created, redirect to home page
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $username;
        header('Location: /html/eseye/home/index');
    }

    protected function
    LogOut($params)
    {
        session_destroy();
        header('/html/eseye/login/index');
    }
}

