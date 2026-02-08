<?php
// app/Core/AuthMiddleware.php

namespace App\Core;

class AuthMiddleware
{
    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    public static function check()
    {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    /**
     * Require authentication or redirect to login
     * 
     * @param string $redirectUrl Optional custom redirect URL
     * @return void
     */
    public static function requireAuth($redirectUrl = 'admin/login')
    {
        if (!self::check()) {
            // Store the intended URL to redirect back after login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];

            // Use BASE_URL for proper redirect
            $fullRedirectUrl = (defined('BASE_URL') ? BASE_URL : '/') . $redirectUrl;
            header('Location: ' . $fullRedirectUrl);
            exit;
        }
    }

    /**
     * Login user
     * 
     * @param string $username
     * @param string $password
     * @return bool
     */
    public static function login($username, $password)
    {
        // Static credentials
        $validUsername = 'adminsami';
        $validPassword = 'password123';

        if ($username === $validUsername && $password === $validPassword) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['login_time'] = time();

            return true;
        }

        return false;
    }

    /**
     * Logout user
     * 
     * @return void
     */
    public static function logout()
    {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destroy the session
        session_destroy();
    }

    /**
     * Get intended URL after login
     * 
     * @param string $default
     * @return string
     */
    public static function getIntendedUrl($default = 'admin/works')
    {
        if (isset($_SESSION['intended_url'])) {
            $url = $_SESSION['intended_url'];
            unset($_SESSION['intended_url']);
            return $url;
        }

        // Return default with BASE_URL
        return (defined('BASE_URL') ? BASE_URL : '/') . $default;
    }
}
