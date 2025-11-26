<?php
namespace App\Models;
use CodeIgniter\Model;
class TeachersModel extends Model
{
    protected $table = 'enseignant';
    protected $primaryKey = 'code';
    protected $allowedFields = ['nom', 'prenom', 'email', 'password', 'fonction', 'reset_token', 'reset_expires'];

    public function getAllTeachersByResources($resourceId)
    {
        //return ["Legrix", "Thorel"];
        return $this->select('nom')->
        from('enseignant')->
        join('ds d', 'd.codeenseignant = enseignant.code')->
        where('d.coderessource', $resourceId)->findAll();
    }

    public function getRole()
    {
        return "ENS";
    }
}
