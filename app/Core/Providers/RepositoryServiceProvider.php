<?php
namespace App\Core\Providers;

use App\Src\User\UserEventSubscriber;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register
     */
    public function boot()
    {
        $this->app['events']->subscribe(new UserEventSubscriber($this->app['mailer']));
    }

    public function register()
    {
    }

}
