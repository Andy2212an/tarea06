<?php
namespace App\Models;

use CodeIgniter\Model;

class SuperheroModel extends Model
{
    protected $table      = 'superhero';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'superhero_name', 'full_name', 'gender_id',
        'alignment_id', 'publisher_id', 'height_cm', 'weight_kg'
    ];
}
