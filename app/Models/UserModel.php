<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'username';
    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'username',
        'id_personne',
        'password',
        'role',
        'reset_token',
        'reset_expires',
    ];

    protected $useTimestamps = false;

    public function findByUsernameWithPersonne(string $username)
    {
        return $this->select('users.*, personne.nom, personne.prenom, personne.email')
                    ->join('personne', 'personne.id_personne = users.id_personne')
                    ->where('users.username', $username)
                    ->first();
    }

    public function findByResetToken(string $token)
    {
        return $this->select('users.*, personne.nom, personne.prenom, personne.email')
                    ->join('personne', 'personne.id_personne = users.id_personne')
                    ->where('users.reset_token', $token)
                    ->where('users.reset_expires >=', date('Y-m-d H:i:s'))
                    ->first();
    }
}
