<?php

use App\Models\Category;
use App\Models\Product;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();

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

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();

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

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->unsignedMediumInteger('quantity')->default(0);
            $table->string('code')->nullable();
            $table->boolean('visible_in_store')->default(false);

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

        Schema::create('categories_products', function (Blueprint $table) {
            $table->foreignIdFor(Category::class, 'category_id')
                ->constrained('categories')
                ->onDelete('cascade');
            $table->foreignIdFor(Product::class, 'product_id')
                ->constrained('products')
                ->onDelete('cascade');
            $table->unique(['category_id', 'product_id']);
        });

        Schema::create('products_tags', function (Blueprint $table) {
            $table->foreignIdFor(Category::class, 'tag_id')
                ->constrained('tags')
                ->onDelete('cascade');
            $table->foreignIdFor(Product::class, 'product_id')
                ->constrained('products')
                ->onDelete('cascade');
            $table->unique(['tag_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_products');
        Schema::dropIfExists('products_tags');
        Schema::dropIfExists('products');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
    }
};
