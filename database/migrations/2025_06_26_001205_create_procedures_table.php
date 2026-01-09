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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('description')->nullable();
             $table->unsignedBigInteger('bailleur_id'); 
            $table->timestamps();



            $table->foreign('bailleur_id')//la clef table bailleur dans table procedure
                  ->references('id')//la reference de la table
                  ->on('procedures')//nom de la table
                  ->onDelete('cascade');

                  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedures');
    }
};
