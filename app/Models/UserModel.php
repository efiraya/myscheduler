<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    // Columns that can be modified
    protected $allowedFields = [
        'username',
        'full_name',
        'email',
        'password',
        'token',
        'remember_username',
        'token_expires',
        'remember_token',
        'remember_expires',
        'last_login'
    ];

    protected $returnType = 'array';

    // Optional: add validation rules
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]|alpha_numeric',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]|max_length[254]',
        'password' => 'required|min_length[8]|regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/]',
        'password_confirm' => 'required|matches[password]',
        'full_name' => 'required|min_length[3]|max_length[100]|alpha_space'
    ];

    protected $validationMessages = [
        'username' => [
            'alpha_numeric' => 'Username can only contain letters and numbers'
        ],
        'password' => [
            'regex_match' => 'Password must contain at least one number, one uppercase letter, one lowercase letter, and be at least 8 characters long'
        ]
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) return $data;

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }

    public function checkLoginAttempts($username)
    {
        $maxAttempts = 5;
        $key = 'login_attempts_' . md5($username);

        $data = cache()->get($key);

        if ($data && is_array($data)) {
            if ($data['count'] >= $maxAttempts && $data['expires_at'] > time()) {
                return false; 
            }
        }

        return true;
    }

    public function incrementLoginAttempts($username)
    {
        $lockoutMinutes = 15;
        $key = 'login_attempts_' . md5($username);

        $data = cache()->get($key);

        if ($data && is_array($data)) {
            $newCount = $data['count'] + 1;
        } else {
            $newCount = 1;
        }

        $attempts = [
            'count'      => $newCount,
            'expires_at' => time() + ($lockoutMinutes * 60)
        ];

        cache()->save($key, $attempts, $lockoutMinutes * 60);
    }

    public function resetLoginAttempts($username)
    {
        cache()->delete('login_attempts_' . md5($username));
    }

    public function getLoginLockoutTime($username)
    {
        $data = cache()->get('login_attempts_' . md5($username));
        if (!$data) return 0;

        if (isset($data['expires_at'])) {
            $ttl = $data['expires_at'] - time();
            return $ttl > 0 ? ceil($ttl / 60) : 0;
        }

        return 1;
    }

    public function verifyRememberToken($token)
    {
        $user = $this->where('remember_expires >', date('Y-m-d H:i:s'))
                     ->where('remember_token IS NOT NULL')
                     ->first();
    
        if ($user && password_verify($token, $user['remember_token'])) {
            return $user;
        }
    
        return null;
    }

    public function clearRememberToken($userId)
    {
        return $this->update($userId, [
            'remember_token' => null,
            'remember_expires' => null
        ]);
    }


    // Enables automatic timestamps for 'created_at' and 'updated_at'
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Enable soft deletes if you plan to use soft deletion
    // protected $useSoftDeletes = true;
    // protected $deletedField  = 'deleted_at';
}
