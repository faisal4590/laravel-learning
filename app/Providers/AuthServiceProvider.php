<?php

namespace App\Providers;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /*
            Amar app e admin, user, author various type er user thakbe.. ekekjoner role ekek rokom and
            access control o ekek rokom hobe.
            eta(Access Control) korar jonno laravel er gates use korbo.
            gates mane ami bole dibo jodi admin hoy tahole ek type er gate hbe, jodi user hoy tahole arek type er ..
            ekek gate er control ekek rokom hobe.
        */

        //defining a gate for admin
        Gate::define("isAdmin",function($user)
        {
            //isAdmin hocce gate er name. eta diye check korbo jekono file e je current user admin kina
            return $user->type === "admin";//returns boolean true/false
            //database e type name er 1ta column rakhcilam. okhane current user er type jodi admin hoy,
            //tahole se admin. so true return korbe.
        });

        //defining a gate for author
        Gate::define("isAuthor",function($user)
        {
            return $user->type === "author";//returns boolean true/false
        });

        //defining a gate for user
        Gate::define("isUser",function($user)
        {
            return $user->type === "user";//returns boolean true/false
        });

        Passport::routes();
        //
    }
}