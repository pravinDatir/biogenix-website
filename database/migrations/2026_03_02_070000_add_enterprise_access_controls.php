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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('status');
                }

                if (! Schema::hasColumn('users', 'approved_by_user_id')) {
                    $table->unsignedBigInteger('approved_by_user_id')->nullable()->after('approved_at');
                }

                if (! Schema::hasColumn('users', 'created_by_user_id')) {
                    $table->unsignedBigInteger('created_by_user_id')->nullable()->after('approved_by_user_id');
                }
            });
        }

        if (! Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('department_user')) {
            Schema::create('department_user', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('department_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                $table->unique(['department_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('user_permissions')) {
            Schema::create('user_permissions', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('permission_id');
                $table->string('grant_type', 10)->default('allow');
                $table->unsignedBigInteger('granted_by_user_id')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'permission_id']);
            });
        }

        if (! Schema::hasTable('delegated_admin_scopes')) {
            Schema::create('delegated_admin_scopes', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('delegated_admin_user_id');
                $table->string('scope_type', 40);
                $table->string('scope_value', 191);
                $table->unsignedBigInteger('assigned_by_user_id')->nullable();
                $table->timestamps();
                $table->unique(['delegated_admin_user_id', 'scope_type', 'scope_value'], 'delegated_admin_scope_unique');
            });
        }

        if (! Schema::hasTable('impersonation_audits')) {
            Schema::create('impersonation_audits', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('impersonator_user_id');
                $table->unsignedBigInteger('impersonated_user_id');
                $table->string('reason', 255)->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('started_at');
                $table->timestamp('ended_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('impersonation_audits')) {
            Schema::drop('impersonation_audits');
        }

        if (Schema::hasTable('delegated_admin_scopes')) {
            Schema::drop('delegated_admin_scopes');
        }

        if (Schema::hasTable('user_permissions')) {
            Schema::drop('user_permissions');
        }

        if (Schema::hasTable('department_user')) {
            Schema::drop('department_user');
        }

        if (Schema::hasTable('departments')) {
            Schema::drop('departments');
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                $dropColumns = [];

                foreach (['approved_at', 'approved_by_user_id', 'created_by_user_id'] as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $dropColumns[] = $column;
                    }
                }

                if (! empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }
    }
};
