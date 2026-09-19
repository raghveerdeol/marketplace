<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use App\Models\Region;
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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'deleted_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Country::class, 'country_id')
                ->nullable()
                ->constrained('countries')
                ->onDelete('cascade');


            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'deleted_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Region::class, 'region_id')
                ->nullable()
                ->constrained('regions')
                ->onDelete('cascade');

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'deleted_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Province::class, 'province_id')
                ->nullable()
                ->constrained('provinces')
                ->onDelete('cascade');

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'deleted_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->text('address');
            $table->string('cap')->nullable();
            $table->text('district')->nullable();
            $table->text('notes')->nullable();
            $table->foreignIdFor(City::class, 'city_id')
                ->nullable()
                ->constrained('cities')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
                
            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'deleted_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('countries');
    }
};
