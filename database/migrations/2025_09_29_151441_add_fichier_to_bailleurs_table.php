<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bailleurs', function (Blueprint $table) {
            //
             
          $table->string('fichier')->nullable(); // chemin de l'image ou du fichier
   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bailleurs', function (Blueprint $table) {
            //
        });
    }
};
