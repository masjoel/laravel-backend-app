<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public static $permission = [
        'dashboard' => ['superadmin', 'admin', 'user'],
        'user-index' => ['admin'],
    ];
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        foreach (self::$permission as $key => $value) {
            Gate::define($key, function (User $user) use ($value) {
                if (in_array($user->role, $value)) {
                    return true;
                }
            });
        }
        // Gate::define('admin', function ($user) {
        //     if ($user->role === 'superadmin') {
        //         return true;
        //     }
        // });
    }
}
