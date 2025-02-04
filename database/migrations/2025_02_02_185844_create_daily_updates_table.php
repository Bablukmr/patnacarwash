<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('daily_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_assignment_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('attendance', ['present', 'absent'])->default('present');
            $table->enum('cleaning_status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->text('daily_notes')->nullable();
            $table->text('defects_found')->nullable();
            $table->text('images')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_updates');
    }
};