<?php
namespace App\Models;
use CodeIgniter\Model;
class RessourceModel extends Model
{
    protected $table = 'resource';
    protected $primaryKey = 'coderessource';
    protected $allowedFields = ['nomressource'];

    
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    public function getAllResourcesBySemesters($semesterId)
    {
        //return ["R1.05 blabla", "R1.02 blibli"];
        return $this->select('ressource.nomressource')->from('ressource')->join('ds d', 'd.coderessource = ressource.coderessource')->where('d.id_semestre', $semesterId)->findAll();
    }
}
