<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'enseignant';
    protected $primaryKey = 'code';          // identifiant universitaire

    protected $returnType     = 'array';
    protected $useTimestamps  = false;

    // colonnes dispo grâce à l’héritage + colonnes propres
    protected $allowedFields = [
        'code',
        'nom',
        'prenom',
        'email',
        'password',
        'fonction',
        'reset_token',
        'reset_expires',
    ];

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
