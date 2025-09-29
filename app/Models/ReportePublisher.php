<?php
namespace App\Models;

use CodeIgniter\Model;

class ReportePublisher extends Model
{
    protected $table = 'view_superhero_publisher'; // la vista creada en BD
    protected $primaryKey = 'publisher'; 
    protected $allowedFields = ['publisher', 'total'];
    protected $returnType = 'array';
}
