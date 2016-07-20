<?php namespace HolidayPirates\BugsnagLumen;

use Bugsnag\Client;
use Bugsnag\Configuration;
use Illuminate\Support\ServiceProvider;

class BugsnagLumenServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bugsnag', function ($app) {
            
            $config = $app['config']['bugsnag'];

            $bugsnagConfig = new Configuration($config['api_key']);
            $client = new Client($bugsnagConfig);
            $client->setStripPath(base_path());
            $client->setProjectRoot(base_path('app'));
            $client->setBatchSending(false);
            $client->setReleaseStage($app->environment());
            $client->setNotifier(array(
                'name'    => 'Bugsnag Laravel',
                'version' => '1.4.2',
                'url'     => 'https://github.com/bugsnag/bugsnag-laravel'
            ));

            if (isset($config['notify_release_stages']) && is_array($config['notify_release_stages'])) {
                $client->setNotifyReleaseStages($config['notify_release_stages']);
            }

            if (isset($config['endpoint'])) {
                $client->setEndpoint($config['endpoint']);
            }

            if (isset($config['filters']) && is_array($config['filters'])) {
                $client->setFilters($config['filters']);
            }

            if (isset($config['proxy']) && is_array($config['proxy'])) {
                $client->setProxySettings($config['proxy']);
            }

            // Check if someone is logged in.
            try {
                if ($app['auth']->check()) {
                    // User is logged in.
                    $user = $app['auth']->user();
    
                    // If these attributes are available: pass them on.
                    $client->setUser(array('id' => $user->getAuthIdentifier()));
                }
            } catch (\Exception $e) {
                // Do nothing.
            }

            return $client;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array("bugsnag");
    }
}
