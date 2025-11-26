<?php
namespace App\Models;
use CodeIgniter\Model;
class StudentsModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'class', 'email'];

    public function getPaginatedStudents( int $perPage, string $keyword = null ) {
        // if ($keyword) {
        //     $this->like('name', $keyword);
        // }
        // return $this->orderBy("name", "asc")->paginate( $perPage, 'default');

        return [
            ['id' => 1, 'nom' => 'Doe', 'prenom' => 'John', 'classe' => 'R5.01', 'absent' => false, 'justifie' => false],
            ['id' => 3, 'nom' => 'Brown', 'prenom' => 'Charlie', 'classe' => 'R5.03', 'absent' => false, 'justifie' => false],
            ['id' => 2, 'nom' => 'Smith', 'prenom' => 'Jane', 'classe' => 'R5.02', 'absent' => false, 'justifie' => false],
            ['id' => 1, 'nom' => 'Doe', 'prenom' => 'John', 'classe' => 'R5.01', 'absent' => false, 'justifie' => false],
            ['id' => 3, 'nom' => 'Brown', 'prenom' => 'Charlie', 'classe' => 'R5.03', 'absent' => false, 'justifie' => false],
            ['id' => 2, 'nom' => 'Smith', 'prenom' => 'Jane', 'classe' => 'R5.02', 'absent' => false, 'justifie' => false],
            ['id' => 1, 'nom' => 'Doe', 'prenom' => 'John', 'classe' => 'R5.01', 'absent' => false, 'justifie' => false],
            ['id' => 3, 'nom' => 'Brown', 'prenom' => 'Charlie', 'classe' => 'R5.03', 'absent' => false, 'justifie' => false],
            ['id' => 2, 'nom' => 'Smith', 'prenom' => 'Jane', 'classe' => 'R5.02', 'absent' => false, 'justifie' => false],
            ['id' => 1, 'nom' => 'Doe', 'prenom' => 'John', 'classe' => 'R5.01', 'absent' => false, 'justifie' => false],
            ['id' => 3, 'nom' => 'Brown', 'prenom' => 'Charlie', 'classe' => 'R5.03', 'absent' => false, 'justifie' => false],
            ['id' => 2, 'nom' => 'Smith', 'prenom' => 'Jane', 'classe' => 'R5.02', 'absent' => false, 'justifie' => false],
            ['id' => 1, 'nom' => 'Doe', 'prenom' => 'John', 'classe' => 'R5.01', 'absent' => false, 'justifie' => false],
            ['id' => 3, 'nom' => 'Brown', 'prenom' => 'Charlie', 'classe' => 'R5.03', 'absent' => false, 'justifie' => false],
            ['id' => 2, 'nom' => 'Smith', 'prenom' => 'Jane', 'classe' => 'R5.02', 'absent' => false, 'justifie' => false],
            ['id' => 1, 'nom' => 'Doe', 'prenom' => 'John', 'classe' => 'R5.01', 'absent' => false, 'justifie' => false],
            ['id' => 3, 'nom' => 'Brown', 'prenom' => 'Charlie', 'classe' => 'R5.03', 'absent' => false, 'justifie' => false],
            ['id' => 2, 'nom' => 'Smith', 'prenom' => 'Jane', 'classe' => 'R5.02', 'absent' => false, 'justifie' => false],
            ['id' => 1, 'nom' => 'Doe', 'prenom' => 'John', 'classe' => 'R5.01', 'absent' => false, 'justifie' => false],
            ['id' => 3, 'nom' => 'Brown', 'prenom' => 'Charlie', 'classe' => 'R5.03', 'absent' => false, 'justifie' => false],
            ['id' => 2, 'nom' => 'Smith', 'prenom' => 'Jane', 'classe' => 'R5.02', 'absent' => false, 'justifie' => false],
        ];
    }
}