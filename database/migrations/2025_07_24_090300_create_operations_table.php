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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('trainees')->onDelete('cascade');
            $table->foreignId('rotation_id')->constrained('rotations')->onDelete('cascade');
            $table->foreignId('objective_id')->constrained('objectives')->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->onDelete('set null');
            $table->string('supervisor_name')->nullable();
            $table->date('procedure_date');
            $table->time('procedure_time');
            $table->integer('duration')->comment('Duration in minutes');
            $table->enum('participation_type', ['Assist', 'Perform', 'Observe', 'Perform with Assist']);
            $table->text('procedure_notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
    }
};
