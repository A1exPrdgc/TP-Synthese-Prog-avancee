<?php
namespace App\Models;
use CodeIgniter\Model;
class SemestersModel extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'class', 'email'];

    public function getAllSemesters()
    {
        return ["S1", "S2"];
    }
}