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
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('sku')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('visibility_scope', 20)->default('public');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['visibility_scope', 'is_active']);
            });
        }

        if (! Schema::hasTable('product_prices')) {
            Schema::create('product_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('price_type', 30);
                $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('INR');
                $table->timestamps();
                $table->index(['product_id', 'price_type', 'company_id'], 'product_price_scope_index');
            });
        }

        if (! Schema::hasTable('proforma_invoices')) {
            Schema::create('proforma_invoices', function (Blueprint $table) {
                $table->id();
                $table->string('pi_number')->unique();
                $table->string('requester_type', 20)->default('guest');
                $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('owner_company_id')->nullable()->constrained('companies')->nullOnDelete();
                $table->string('target_type', 20)->default('self');
                $table->string('target_name')->nullable();
                $table->string('target_email')->nullable();
                $table->string('target_phone')->nullable();
                $table->foreignId('target_company_id')->nullable()->constrained('companies')->nullOnDelete();
                $table->string('status', 30)->default('draft');
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->string('guest_session_id')->nullable()->index();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['owner_user_id', 'owner_company_id'], 'pi_owner_scope_index');
                $table->index(['target_company_id', 'status'], 'pi_target_scope_index');
            });
        }

        if (! Schema::hasTable('proforma_invoice_items')) {
            Schema::create('proforma_invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proforma_invoice_id')->constrained('proforma_invoices')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('product_name');
                $table->string('sku');
                $table->unsignedInteger('quantity');
                $table->decimal('unit_price', 12, 2);
                $table->decimal('line_total', 12, 2);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('guest_activity_logs')) {
            Schema::create('guest_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('session_id')->index();
                $table->string('activity_type', 40);
                $table->string('path')->nullable();
                $table->json('payload')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('guest_activity_logs')) {
            Schema::drop('guest_activity_logs');
        }

        if (Schema::hasTable('proforma_invoice_items')) {
            Schema::drop('proforma_invoice_items');
        }

        if (Schema::hasTable('proforma_invoices')) {
            Schema::drop('proforma_invoices');
        }

        if (Schema::hasTable('product_prices')) {
            Schema::drop('product_prices');
        }

        if (Schema::hasTable('products')) {
            Schema::drop('products');
        }

        if (Schema::hasTable('categories')) {
            Schema::drop('categories');
        }
    }
};
