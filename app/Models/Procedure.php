<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *php artisan make:model Procedure –mcr 
     * @var array
     */

    protected $fillable = [
        'libelle',
        'description',
        'bailleur_id',
       // 'demande_ids'
    ];


     /**
     * Relation avec Bailleur (une Procedure appartient à un seul Bailleur).
     */
    public function bailleur()
    {
        return $this->belongsTo(Bailleur::class);
    }

    /**
     * Relation avec Demande (une Procedure appartient à plusieeurs Demandes).
     */
    public function demandes()
    {
        return $this->belongsToMany(Demande::class , 'demande_procedure')->withTimestamps();
    }

}
