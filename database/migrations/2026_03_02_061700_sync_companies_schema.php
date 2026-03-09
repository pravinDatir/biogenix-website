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
        if (! Schema::hasTable('companies')) {
            return;
        }

        Schema::table('companies', function (Blueprint $table): void {
            if (! Schema::hasColumn('companies', 'company_type')) {
                $table->string('company_type')->nullable()->after('name');
            }

            if (! Schema::hasColumn('companies', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('company_type');
            }

            if (! Schema::hasColumn('companies', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('companies', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left empty to avoid removing pre-existing columns on rollback.
    }
};
