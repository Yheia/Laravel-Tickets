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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->json('image')->nullable();
            $table->enum('sector', ['Network and Infrastructure', 'Portal and site', 'Complain','general'])->default('general');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
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
            ])->default('Other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
