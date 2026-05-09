<?php

namespace App\Providers;

use App\Models\Authentication;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        Gate::define('page-access', function ($user, $formId, $type = 'view') {
            $auth = Authentication::where('role_id', $user -> role_id)
                ->where('form_id', $formId)
                ->first();

            if (!$auth) return false;

            if ($type === 'view') {
                return in_array($auth->access_level, [1, 2]);
            } elseif ($type === 'edit') {
                return $auth->access_level === 1;
            }

            return false;
        });
    }
}
