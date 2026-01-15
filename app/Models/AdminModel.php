<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields    = [
        'name', 'username', 'email', 'contact',
        'password_hash', 'reset_token', 'reset_expires_at', 'role',
    ];

    /**
     * Get admin user by email or username
     */
    public function getAdminByLogin($login)
    {
        return $this->where('role', 'admin')
                    ->groupStart()
                        ->where('email', $login)
                        ->orWhere('username', $login)
                    ->groupEnd()
                    ->first();
    }

    /**
     * Verify admin password
     */
    public function verifyPassword($admin, $password)
    {
        return password_verify($password, $admin['password_hash']);
    }
}
