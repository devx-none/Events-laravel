<?php

namespace App\Providers;

use App\Policies\CalendarPolicy;
use App\Policies\EventPolicy;
use App\Models\User;
use App\Models\calendar;
use App\Models\event;
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
        User::class => UserPolicy::class,
        Calendar::class => CalendarPolicy::class,
        Event::class => EventPolicy::class,


    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        //calendar policy for user
        Gate::define('view-calendar',[CalendarPolicy::class,'view']);
        Gate::define('create-calendar',[CalendarPolicy::class,'create']);
        Gate::define('update-calendar',[CalendarPolicy::class,'update']);
        Gate::define('delete-calendar',[CalendarPolicy::class,'delete']);

        //event policy for user
        Gate::define('view-event',[EventPolicy::class,'view']);
        Gate::define('create-event',[EventPolicy::class,'create']);
        Gate::define('update-event',[EventPolicy::class,'update']);
        Gate::define('delete-event',[EventPolicy::class,'delete']);

        



    }
}
