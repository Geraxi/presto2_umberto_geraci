<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_accepted')
                  ->nullable()
                  ->after('price'); // Position after price column
        });
    }
    
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('is_accepted');
        });
    }
};
