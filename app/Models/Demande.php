<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *php artisan make:model Procedure –mcr 
     * @var array
     */

    protected $fillable = [
        'libelle',
        'description'

    ];

    /**
     * Relation avec Procedure (une demande appartient plusieurs Procedures).
     */
    public function procedures()
    {
        return $this->belongsToMany(Procedure::class, 'demande_procedure')->withTimestamps();
    }
}
