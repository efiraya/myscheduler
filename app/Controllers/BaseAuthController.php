<?php

namespace App\Controllers;

use App\Models\UserModel;

abstract class BaseAuthController extends BaseController
{
    protected $user;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        // Load user data if logged in
        $userId = session()->get('user_id');
        if ($userId) {
            $userModel = new UserModel();
            $this->user = $userModel->find($userId);
        }
    }
}