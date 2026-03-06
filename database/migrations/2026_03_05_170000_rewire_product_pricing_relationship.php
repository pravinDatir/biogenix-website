<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'product_pricing_id')) {
            Schema::table('products', function (Blueprint $table): void {
                try {
                    $table->dropForeign(['product_pricing_id']);
                } catch (Throwable) {
                }
            });

            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('product_pricing_id');
            });
        }

        if (Schema::hasTable('product_prices') && ! Schema::hasColumn('product_prices', 'product_id')) {
            Schema::table('product_prices', function (Blueprint $table): void {
                $table->foreignId('product_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('products')
                    ->cascadeOnDelete();
            });

            Schema::table('product_prices', function (Blueprint $table): void {
                $table->index(['product_id', 'price_type'], 'product_prices_product_price_type_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_prices') && Schema::hasColumn('product_prices', 'product_id')) {
            Schema::table('product_prices', function (Blueprint $table): void {
                try {
                    $table->dropIndex('product_prices_product_price_type_index');
                } catch (Throwable) {
                }

                try {
                    $table->dropForeign(['product_id']);
                } catch (Throwable) {
                }
            });

            Schema::table('product_prices', function (Blueprint $table): void {
                $table->dropColumn('product_id');
            });
        }

        if (Schema::hasTable('products') && ! Schema::hasColumn('products', 'product_pricing_id')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->foreignId('product_pricing_id')
                    ->nullable()
                    ->after('subcategory_id')
                    ->constrained('product_prices')
                    ->nullOnDelete();
            });
        }
    }
};
