<?php
namespace App\Models;
use CodeIgniter\Model;
class RessourceModel extends Model
{
    protected $table = 'resources';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'class', 'email'];

    public function getAllResourcesBySemesters($semesterId)
    {
        return ["R1.05 blabla", "R1.02 blibli"];
    }
}
