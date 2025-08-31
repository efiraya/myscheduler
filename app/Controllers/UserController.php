<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ScheduleModel;
use App\Models\EmailLog;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DataException;
use Config\Services;

class UserController extends Controller
{   
    public function index()
    {
        $userModel = new UserModel();
        $data = $userModel->findAll();

        return view('user/list', [
            'data' => $data
        ]);
    }
    public function create()
    {
        return view('user/create');
    }

    public function store()
    {
        $userModel = new UserModel();

        $data = [
            'username'   => $this->request->getPost('username'),
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'password_confirm' => $this->request->getPost('password_confirm')
        ];

        // Validate and save to the database
        if ($userModel->insert($data)) {
            return redirect()->to('/user/list')
                ->with('success', 'Add data successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('errors', $userModel->errors());
        }
    }

    public function edit($id)
    {
        $userModel = new UserModel();
        $data = $userModel->find($id);
        return view('user/edit', ['data' => $data]);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]|alpha_numeric",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]|max_length[254]",
            'full_name' => 'required|min_length[3]|max_length[100]|alpha_space',
        ];
        
        // Hanya tambahkan validasi password jika diinput
        if ($this->request->getPost('password')) {
            $rules['password'] = 'required|min_length[8]|regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/]';
            $rules['password_confirm'] = 'required|matches[password]';
        }
        
        // Set validasi khusus untuk update
        $userModel->setValidationRules($rules);
        
        $data = [
            'username'   => $this->request->getPost('username'),
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $this->request->getPost('email'),
        ];
        
        if (!empty($this->request->getPost('password'))) {
            $data['password'] = $this->request->getPost('password');
        }
        
        if (!empty($this->request->getPost('password_confirm'))) {
            $data['password_confirm'] = $this->request->getPost('password_confirm');
        }
        
        if ($userModel->update($id, $data)) {
            return redirect()->to('/user/list')->with('success', 'User updated successfully.');
        } else {
            log_message('error', 'Update failed for user ID: ' . $id);
            return redirect()->back()->withInput()->with('error', 'Failed to update activity.');
        }
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $activity = $userModel->find($id);

        if ($userModel->delete($id)) {

            $session = Services::session();
            // Destroy the session
            $session->destroy();
            return redirect()->to('/auth/login')->with('success', 'Logged out successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete activity.');
        }
    }
    
}
