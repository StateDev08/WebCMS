<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;

class AuthController
{
    public function loginForm(): void
    {
        View::render('site/login', ['csrf' => Csrf::token()], 'layouts/site');
    }

    public function adminLoginForm(): void
    {
        View::render('admin/login', ['csrf' => Csrf::token()], 'layouts/admin');
    }

    public function registerForm(): void
    {
        View::render('site/register', ['csrf' => Csrf::token()], 'layouts/site');
    }

    public function login(): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (Auth::attempt($email, $password)) {
            redirect('/admin');
        }

        View::render('site/login', [
            'error' => 'Login fehlgeschlagen.',
            'csrf' => Csrf::token(),
        ], 'layouts/site');
    }

    public function adminLogin(): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (Auth::attempt($email, $password)) {
            redirect('/admin');
        }

        View::render('admin/login', [
            'error' => 'Login fehlgeschlagen.',
            'csrf' => Csrf::token(),
        ], 'layouts/admin');
    }

    public function register(): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            View::render('site/register', [
                'error' => 'Bitte alle Felder ausfüllen.',
                'csrf' => Csrf::token(),
            ], 'layouts/site');
            return;
        }

        $userId = Auth::register(['name' => $name, 'email' => $email, 'password' => $password]);
        $_SESSION['user_id'] = $userId;
        redirect('/admin');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }

    public function adminLogout(): void
    {
        Auth::logout();
        redirect('/admin/login');
    }
}
