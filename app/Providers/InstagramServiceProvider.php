<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MetzWeb\Instagram\Instagram;

class InstagramServiceProvider extends ServiceProvider {

    protected $defer = false;
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
//        $this->package('MetzWeb\Instagram');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
        $this->app['instagram'] = $this->app->share(function($app)
        {
            return new Instagram(['apiKey'=>$_ENV['INSTA_CLIENT_ID'],'apiSecret'=>$_ENV['INSTA_CLIENT_SECRET'],'apiCallback'=>$_ENV['INSTA_REDIRECT_URI']]);
        });
//        $this->app->bind('MetzWeb\Instagram\Instagram', function($app)
//        {
//            return new Instagram(['apiKey'=>$_ENV['INSTA_CLIENT_ID'],'apiSecret'=>$_ENV['INSTA_CLIENT_SECRET'],'apiCallback'=>$_ENV['INSTA_REDIRECT_URI']]);
//        });
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('instagram');
    }

}
