<?php

use App\Models\Queue;
use App\Models\User;
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
        Schema::create('users_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Queue::class);
            $table->foreignIdFor(User::class );
            $table->string('queue_number');
            $table->enum("status", ["canceled", "completed", "waiting"])->default("waiting");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_queues');
    }
};
