<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bailleur extends Model
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
        'fichier'
    ];

     /**
     * Relation avec le precedure (un  Bailleurs a plusieurs precedure).
     */
    public function precedure()
    {
        return $this->hasMany(Procedure::class);
    }

    /**
     * Retourne l'URL public du fichier (image/document)
     */
    protected $appends = ['fichier_url'];

    public function getFichierUrlAttribute()
    {
        return $this->fichier 
            ? asset('storage/' . $this->fichier) 
            : null;
    }


    



 }
