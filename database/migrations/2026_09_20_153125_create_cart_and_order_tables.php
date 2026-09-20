<?php

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatus;
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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class, 'user_id')
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

        Schema::create('orders_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');

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

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignIdFor(OrderStatus::class, 'order_status_id')
                ->constrained('orders_status')
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

        Schema::create('carts_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cart::class, 'cart_id')
                ->constrained('carts')
                ->onDelete('cascade');
            $table->foreignIdFor(Product::class, 'product_id')
                ->constrained('products')
                ->onDelete('cascade');
                
            $table->unsignedSmallInteger('quantity');
            $table->timestamps();
        });

        Schema::create('archive_products', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->unsignedMediumInteger('quantity')->default(0);
            $table->string('code')->nullable();
            $table->unsignedTinyInteger('percentage')->default(0);

            $table->foreignIdFor(Order::class, 'order_id')
                ->nullable()
                ->constrained('orders')
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

        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('percentage')->default(0);
            $table->dateTime('start');
            $table->dateTime('end')->nullable();

            $table->foreignIdFor(Product::class, 'product_id')
                ->constrained('products')
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
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('archive_products');
        Schema::dropIfExists('carts_products');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('orders_status');
        Schema::dropIfExists('carts');
    }
};
