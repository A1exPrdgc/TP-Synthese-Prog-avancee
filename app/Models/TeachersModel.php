<?php
namespace App\Models;
use CodeIgniter\Model;
class TeachersModel extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'class', 'email'];

    public function getAllTeachersByResources($resourceId)
    {
        return ["Legrix", "Thorel"];
    }

    public function getRole()
    {
        return "ENS";
    }
}
