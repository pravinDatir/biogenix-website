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
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('company_type')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
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

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type', 50)->default('b2c')->after('email');
            }

            if (! Schema::hasColumn('users', 'b2b_type')) {
                $table->string('b2b_type', 50)->nullable()->after('user_type');
            }

            if (! Schema::hasColumn('users', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('b2b_type')->constrained('companies')->nullOnDelete();
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('active')->after('company_id');
            }
        });

        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['role_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['permission_id', 'role_id']);
            });
        }

        if (! Schema::hasTable('b2b_client_assignments')) {
            Schema::create('b2b_client_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('b2b_user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('client_company_id')->constrained('companies')->cascadeOnDelete();
                $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->unique(['b2b_user_id', 'client_company_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('b2b_client_assignments')) {
            Schema::drop('b2b_client_assignments');
        }

        if (Schema::hasTable('permission_role')) {
            Schema::drop('permission_role');
        }

        if (Schema::hasTable('role_user')) {
            Schema::drop('role_user');
        }

        if (Schema::hasTable('permissions')) {
            Schema::drop('permissions');
        }

        if (Schema::hasTable('roles')) {
            Schema::drop('roles');
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (Schema::hasColumn('users', 'company_id')) {
                    $table->dropForeign(['company_id']);
                }

                $columns = [];

                if (Schema::hasColumn('users', 'user_type')) {
                    $columns[] = 'user_type';
                }

                if (Schema::hasColumn('users', 'b2b_type')) {
                    $columns[] = 'b2b_type';
                }

                if (Schema::hasColumn('users', 'company_id')) {
                    $columns[] = 'company_id';
                }

                if (Schema::hasColumn('users', 'status')) {
                    $columns[] = 'status';
                }

                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('companies')) {
            Schema::drop('companies');
        }
    }
};
