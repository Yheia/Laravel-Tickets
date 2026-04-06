<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum('faculty', [
                'Faculty of Education',
                'Faculty of Agriculture',
                'Faculty of Arts',
                'Faculty of Commerce',
                'Faculty of Nursing',
                'Faculty of Science',
                'Faculty of Pharmacy',
                'Faculty of Veterinary Medicine',
                'Faculty of Early Childhood Education',
                'Faculty of Special Education',
                'Faculty of Computers and Information',
                'Faculty of engineering',
                'Faculty of Applied Arts',
                'institute of graduate studies and environmental research',
                'Other'
            ])->nullable()->after('sector'); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
};
