<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('cookie');

        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            // Check for remember me cookie
            $userModel = new \App\Models\UserModel();
            $rememberToken = get_cookie('remember_token');

            if ($rememberToken) {
                $user = $userModel->verifyRememberToken($rememberToken);

                if ($user) {
                    // Set up the user's session
                    session()->set([
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'isLoggedIn' => true,
                    ]);

                    // Allow request to proceed
                    return;
                }
            }

            // If no valid remember token or session, redirect to login
            return redirect()->to('/auth/login')->with('error', 'You need to log in first.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here for now
    }
}
