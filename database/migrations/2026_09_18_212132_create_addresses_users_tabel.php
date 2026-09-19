<?php

use App\Models\Address;
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
        Schema::create('addresses_users', function (Blueprint $table) {
            $table->foreignIdFor(Address::class, 'address_id')
                ->constrained('addresses')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained('users')
                ->onDelete('cascade');    
            $table->unique(['address_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses_users');
    }
};
