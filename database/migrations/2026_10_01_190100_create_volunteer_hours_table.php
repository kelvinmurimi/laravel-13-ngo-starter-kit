<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_member_id')->constrained('volunteer_members')->cascadeOnDelete();
            $table->date('worked_on');
            $table->decimal('hours', 5, 2);
            $table->string('activity');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_hours');
    }
};
