<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate ini akan menangkap semua permintaan hak akses
        Gate::before(function (User $user) {
            // Jika user adalah admin, selalu izinkan semuanya
            if ($user->role_level === 'admin') {
                return true;
            }
        });

        // Gate dinamis yang memeriksa izin dari database
        Gate::define('access', function (User $user, $module, $action) {
            $permission = $user->permissions()
                              ->where('module_name', $module)
                              ->first();

            if ($permission) {
                switch ($action) {
                    case 'create': return $permission->can_create;
                    case 'read': return $permission->can_read;
                    case 'update': return $permission->can_update;
                    case 'delete': return $permission->can_delete;
                    default: return false;
                }
            }
            return false;
        });
    }
}
