<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\Cookie\Cookie;
use Config\Services;
use DateTime;

class AuthController extends Controller
{
    public function register()
    {
        // Load the registration view
        return view('auth/register');
    }

    public function registerSubmit()
    {
        $userModel = new UserModel();

        // Get data from the form
        $data = [
            'username'   => $this->request->getPost('username'),
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'password_confirm' => $this->request->getPost('password_confirm')
        ];

        // Validate and save to the database
        if ($userModel->insert($data)) {
            return redirect()->to('/auth/login')
                ->with('success', 'Registration successful. You can now log in.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('errors', $userModel->errors());
        }
    }

    public function login()
    {
        $userModel = new \App\Models\UserModel();

        // Retrieve the remembered username
        $rememberedUsername = $userModel->where('remember_username IS NOT NULL', null, false)
            ->orderBy('id', 'DESC')
            ->first();

        return view('auth/login', [
            'rememberedUsername' => $rememberedUsername['remember_username'] ?? ''
        ]);
    }

    public function loginSubmit()
    {
        // $this->checkRememberMe();

        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $request = $this->request;
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        // $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');
        
        // Validate input
        if (empty($username) || empty($password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username and password are required');
        }

        // Check rate limiting
        if (!$userModel->checkLoginAttempts($username)) {
            $remainingTime = $userModel->getLoginLockoutTime($username);
            return redirect()->back()
                ->withInput()
                ->with('error', "Too many login attempts. Please try again after {$remainingTime} minutes.");
        }

        $user = $userModel->where('username', $username)->first();
        
        
        if ($user && password_verify($password, $user['password'])) {
            // Reset login attempts
            $userModel->resetLoginAttempts($username);

            // Set session data
            $sessionData = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'isLoggedIn' => true,
            ];

            session()->set($sessionData);

            // Remember the username if checkbox is selected
            if ($remember) {
                // Save username in the database
                $this->setRememberMe($user);
                $userModel->update($user['id'], ['remember_username' => $username]);
            } else {
                // Clear the remember_username value in the database
                $userModel->update($user['id'], ['remember_username' => null]);
            }

            return redirect()->to('/dashboard')->with('success', 'Login successful.');
        } else {
            // Increment failed login attempts
            $userModel->incrementLoginAttempts($username);

            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    private function setRememberMe($user)
    {
        helper('cookie');
        $rememberToken = bin2hex(random_bytes(16)); // Token acak
        $hashedToken = password_hash($rememberToken, PASSWORD_DEFAULT); // Hash token
        $cookieLifetime = 1209600; // 2 minggu dalam detik
    
        $dateWeeks = new DateTime(); // Tanggal dan waktu saat ini
        $dateWeeks->modify('+2 weeks'); // Tambahkan 2 minggu
    
        // Simpan token yang di-hash ke database
        $userModel = new UserModel();
        $userModel->update($user['id'], [
            'remember_token' => $hashedToken,
            'remember_expires' => $dateWeeks->format('Y-m-d H:i:s'),
            'last_login' => date('Y-m-d H:i:s'),
        ]);
    
        // Set token mentah di cookie
        $success = set_cookie([
            'name'     => 'remember_token',
            'value'    => $rememberToken,
            'expire'   => 1209600,
            'path'     => '/',
            'domain'   => '',       // Kosongkan untuk default
            'secure'   => true,     // Set `true` jika HTTPS
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    
        log_message('debug', 'Set Cookie Success: ' . ($success ? 'Yes' : 'No'));
    }

    // public function loginSubmit()
    // {
    //     // Check for remember me cookie first
    //     $this->checkRememberMe();
    //     if (session()->get('isLoggedIn')) {
    //         return redirect()->to('/dashboard');
    //     }
    //     $userModel = new UserModel();
    //     $username = $this->request->getPost('username');
    //     $email = $this->request->getPost('email');
    //     $password = $this->request->getPost('password');
    //     $remember = (bool)$this->request->getPost('remember');

    //     // Validate required fields
    //     if (empty($username) || empty($password)) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', 'Username and password are required');
    //     }

    //     // Check rate limiting
    //     if (!$userModel->checkLoginAttempts($username)) {
    //         $remainingTime = $userModel->getLoginLockoutTime($username);
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', "Too many login attempts. Please try again after {$remainingTime} minutes.");
    //     }

    //     $user = $userModel->where('username', $username)->first();

    //     // Verify user and password
    //     if ($user && password_verify($password, $user['password'])) {
    //         // Reset login attempts on successful login
    //         $userModel->resetLoginAttempts($username);

    //         // Set session data
    //         $sessionData = [
    //             'user_id' => $user['id'],
    //             'email' => $user['email'],
    //             'username' => $user['username'],
    //             'full_name' => $user['full_name'],
    //             'isLoggedIn' => true
    //         ];

    //         session()->set($sessionData);

    //         // Handle remember me
    //         if ($remember) {
    //             $this->setRememberMeCookie($user['id']);
    //         }

    //         return redirect()->to('/dashboard')
    //             ->with('success', 'Login successful.');
    //     } else {
    //         // Increment failed login attempts
    //         $userModel->incrementLoginAttempts($username);

    //         return redirect()->back()
    //             ->with('error', 'Invalid credentials');
    //     }
    // }

    private function validateRequest(): bool
    {
        return $this->request->getPost('csrf_token') === session()->get('csrf_token');
    }

    public function checkRememberMe()
    {
        if (session()->get('isLoggedIn')) {
            return;
        }

        helper('cookie'); // Load helper cookie
        $rememberToken = get_cookie('remember_token');

        if ($rememberToken) {
            $userModel = new UserModel();
            $user = $userModel->where('remember_token', $rememberToken)->first();

            if ($user) {
                // Set session
                $sessionData = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'isLoggedIn' => true,
                ];
                session()->set($sessionData);
                session()->regenerate();
                return redirect()->to('/dashboard');
            }
        }
    }

    // public function checkRememberMe()
    // {
    //     if (session()->get('isLoggedIn')) {
    //         return;
    //     }

    //     $rememberToken = $_COOKIE['remember_token'] ?? null;
    //     if ($rememberToken) {
    //         $userModel = new UserModel();
    //         $user = $userModel->verifyRememberToken($rememberToken);

    //         if ($user) {
    //             // Set session data
    //             $sessionData = [
    //                 'user_id' => $user['id'],
    //                 'email' => $user['email'],
    //                 'username' => $user['username'],
    //                 'full_name' => $user['full_name'],
    //                 'isLoggedIn' => true,
    //                 'last_activity' => time()
    //             ];

    //             session()->set($sessionData);

    //             // Refresh remember me token
    //             $this->setRememberMeCookie($user['id']);

    //             // Update last login
    //             $userModel->update($user['id'], [
    //                 'last_login' => date('Y-m-d H:i:s')
    //             ]);
    //         }
    //     }
    // }

    public function dashboard() {}

    public function logout()
    {
        helper('cookie'); 
        $userId = session()->get('user_id');
        if ($userId) {
            // Clear remember me field
            $userModel = new UserModel();
            $userModel->update($userId, [
                'remember_username' => null,
            ]);
        }
        $session = Services::session();
        // Destroy the session
        $session->destroy();
        delete_cookie('remember_token');
        return redirect()->to('/auth/login')->with('success', 'Logged out successfully.');
    }

    // public function logout()
    // {
    //     $userId = session()->get('user_id');
    //     if ($userId) {
    //         // Clear remember me token
    //         $userModel = new UserModel();
    //         $userModel->clearRememberToken($userId);

    //         // Clear remember me cookie
    //         setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    //     }

    //     // Destroy the session
    //     session()->destroy();
    //     return redirect()->to('/auth/login')->with('success', 'Logged out successfully');
    // }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function resetPasswordSubmit()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');

        if (!$email) {
            return redirect()->back()->with('error', 'Email is required.');
        }

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'If the email exists in our system, you will receive a password reset link.');
        }
        date_default_timezone_set('Asia/Jakarta');
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $userModel->update($user['id'], [
            'token' => $token,
            'token_expires' => $expires
        ]);

        // Prepare email
        $resetLink = base_url("/auth/resetPasswordForm/$token");
        $message = "Hello,<br><br>";
        $message .= "You have requested to reset your password. Please click the following link to reset it<br><br>";
        $message .= '<a href="' . $resetLink . '" style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #ffffff; background-color: #007bff; text-decoration: none; border-radius: 5px;">Reset Password</a><br><br>';
        $message .= "This link will expire in 1 hour.<br><br>";
        $message .= "If you didn't request this, please ignore this email.\n";

        $email = \Config\Services::email();
        $email->setTo($user['email']);
        $email->setSubject('Password Reset Request');
        $email->setMessage($message);
        $email->setMailType('html');

        if ($email->send()) {
            return redirect()->to('/auth/login')
                ->with('success', 'Password reset instructions have been sent to your email.');
        } else {
            log_message('error', 'Failed to send password reset email: ' . $email->printDebugger(['headers']));
            return redirect()->back()
                ->with('error', 'Failed to send reset link. Please try again later.');
        }
    }

    public function resetPasswordForm($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('token', $token)
            ->where('token_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/auth/login')
                ->with('error', 'Invalid or expired reset link.');
        }

        $data = ['token' => $token];
        return view('auth/reset_password', $data);
    }

    public function updatePassword()
    {
        $userModel = new UserModel();
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirm');

        // Validate password match
        if ($password !== $passwordConfirm) {
            return redirect()->back()
                ->with('error', 'Passwords do not match.');
        }

        // Find user with valid token
        $user = $userModel->where('token', $token)
            ->where('token_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/auth/login')
                ->with('error', 'Invalid or expired reset link.');
        }

        // Update password and clear token
        $userModel->update($user['id'], [
            'password' => $password,  // Model will hash this automatically
            'token' => null,
            'token_expires' => null,
            'remember_expires' => null
        ]);

        return redirect()->to('/auth/login')
            ->with('success', 'Password has been reset successfully. You can now log in.');
    }
}
