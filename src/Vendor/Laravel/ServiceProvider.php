<?php

namespace Blanfordia\Visitors\Vendor\Laravel;

use Blanfordia\Visitors\Visitors;
use PragmaRX\Support\PhpSession;
use PragmaRX\Support\GeoIp\GeoIp;
use Blanfordia\Visitors\Support\MobileDetect;
use Blanfordia\Visitors\Eventing\EventStorage;
use Blanfordia\Visitors\Data\Repositories\Log;
use Blanfordia\Visitors\Data\RepositoryManager;
use Blanfordia\Visitors\Data\Repositories\Path;
use Blanfordia\Visitors\Data\Repositories\Route;
use Blanfordia\Visitors\Data\Repositories\Query;
use Blanfordia\Visitors\Data\Repositories\Event;
use Blanfordia\Visitors\Services\Authentication;
use Blanfordia\Visitors\Support\CrawlerDetector;
use Blanfordia\Visitors\Support\UserAgentParser;
use Blanfordia\Visitors\Data\Repositories\Error;
use Blanfordia\Visitors\Data\Repositories\Agent;
use Blanfordia\Visitors\Data\Repositories\Device;
use Blanfordia\Visitors\Data\Repositories\Cookie;
use Blanfordia\Visitors\Data\Repositories\Domain;
use Blanfordia\Visitors\Data\Repositories\Referer;
use Blanfordia\Visitors\Data\Repositories\Session;
use Blanfordia\Visitors\Data\Repositories\EventLog;
use Blanfordia\Visitors\Data\Repositories\SqlQuery;
use Blanfordia\Visitors\Data\Repositories\RoutePath;
use Blanfordia\Visitors\Data\Repositories\Connection;
use Blanfordia\Visitors\Data\Repositories\SystemClass;
use Blanfordia\Visitors\Data\Repositories\SqlQueryLog;
use Blanfordia\Visitors\Data\Repositories\QueryArgument;
use Blanfordia\Visitors\Data\Repositories\SqlQueryBinding;
use Blanfordia\Visitors\Data\Repositories\RoutePathParameter;
use Blanfordia\Visitors\Data\Repositories\GeoIp as GeoIpRepository;
use Blanfordia\Visitors\Data\Repositories\SqlQueryBindingParameter;
use PragmaRX\Support\ServiceProvider as BlanfordiaServiceProvider;
use Blanfordia\Visitors\Vendor\Laravel\Artisan\Tables as TablesCommand;
use Blanfordia\Visitors\Support\Exceptions\Handler as VisitorsExceptionHandler;

class ServiceProvider extends BlanfordiaServiceProvider {

	protected $packageVendor = 'blanfordia';

	protected $packageName = 'visitors';

	protected $packageNameCapitalized = 'Visitors';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

	private $userChecked = false;

	private $visitors;

	/**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
	    parent::boot();

	    if ($this->getConfig('enabled'))
	    {
		    $this->loadRoutes();

		    $this->registerErrorHandler();

			if (! $this->getConfig('use_middleware'))
            {
                $this->bootVisitors();
            }
	    }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
	    parent::register();

	    if ($this->getConfig('enabled'))
	    {
		    $this->registerAuthentication();

		    $this->registerRepositories();

		    $this->registerVisitors();

		    $this->registerTablesCommand();

		    $this->registerExecutionCallback();

		    $this->registerUserCheckCallback();

		    $this->registerSqlQueryLogWatcher();

		    $this->registerGlobalEventLogger();

		    $this->registerDatatables();

		    $this->registerGlobalViewComposers();

		    $this->commands('visitors.tables.command');
	    }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('visitors');
    }

    /**
     * Takes all the components of Visitors and glues them
     * together to create Visitors.
     *
     * @return void
     */
    private function registerVisitors()
    {
        $this->app['visitors'] = $this->app->share(function($app)
        {
            $app['visitors.loaded'] = true;

            return new Visitors(
                                    $app['visitors.config'],
                                    $app['visitors.repositories'],
                                    $app['request'],
                                    $app['router'],
                                    $app['log'],
                                    $app
                                );
        });
    }

    public function registerRepositories()
    {
        $this->app['visitors.repositories'] = $this->app->share(function($app)
        {
            try
            {
                $uaParser = new UserAgentParser($app->make('path.base'));
            }
            catch (\Exception $exception)
            {
                $uaParser = null;
            }

            $sessionModel = $this->instantiateModel('session_model');

            $logModel = $this->instantiateModel('log_model');

            $agentModel = $this->instantiateModel('agent_model');

            $deviceModel = $this->instantiateModel('device_model');

            $cookieModel = $this->instantiateModel('cookie_model');

	        $pathModel = $this->instantiateModel('path_model');

			$queryModel = $this->instantiateModel('query_model');

			$queryArgumentModel = $this->instantiateModel('query_argument_model');

	        $domainModel = $this->instantiateModel('domain_model');

	        $refererModel = $this->instantiateModel('referer_model');

	        $refererSearchTermModel = $this->instantiateModel('referer_search_term_model');

	        $routeModel = $this->instantiateModel('route_model');

	        $routePathModel = $this->instantiateModel('route_path_model');

	        $routePathParameterModel = $this->instantiateModel('route_path_parameter_model');

	        $errorModel = $this->instantiateModel('error_model');

	        $geoipModel = $this->instantiateModel('geoip_model');

	        $sqlQueryModel = $this->instantiateModel('sql_query_model');

            $sqlQueryBindingModel = $this->instantiateModel('sql_query_binding_model');

	        $sqlQueryBindingParameterModel = $this->instantiateModel('sql_query_binding_parameter_model');

            $sqlQueryLogModel = $this->instantiateModel('sql_query_log_model');

	        $connectionModel = $this->instantiateModel('connection_model');

	        $eventModel = $this->instantiateModel('event_model');

	        $eventLogModel = $this->instantiateModel('event_log_model');

	        $systemClassModel = $this->instantiateModel('system_class_model');

	        $logRepository = new Log($logModel);

	        $connectionRepository = new Connection($connectionModel);

	        $sqlQueryBindingRepository = new SqlQueryBinding($sqlQueryBindingModel);

	        $sqlQueryBindingParameterRepository = new SqlQueryBindingParameter($sqlQueryBindingParameterModel);

	        $sqlQueryLogRepository = new SqlQueryLog($sqlQueryLogModel);

	        $sqlQueryRepository = new SqlQuery(
		        $sqlQueryModel,
		        $sqlQueryLogRepository,
		        $sqlQueryBindingRepository,
		        $sqlQueryBindingParameterRepository,
		        $connectionRepository,
		        $logRepository,
		        $app['visitors.config']
	        );

			$eventLogRepository = new EventLog($eventLogModel);

			$systemClassRepository = new SystemClass($systemClassModel);

	        $eventRepository = new Event(
		        $eventModel,
		        $app['visitors.events'],
		        $eventLogRepository,
		        $systemClassRepository,
		        $logRepository,
		        $app['visitors.config']
	        );

	        $routeRepository = new Route(
		        $routeModel,
		        $app['visitors.config']
	        );

	        $crawlerDetect = new CrawlerDetector(
		        $app['request']->headers->all(),
		        $app['request']->server('HTTP_USER_AGENT')
	        );

	        return new RepositoryManager(
	            new GeoIp(),

	            new MobileDetect,

	            $uaParser,

	            $app['visitors.authentication'],

	            $app['session.store'],

	            $app['visitors.config'],

                new Session($sessionModel,
                            $app['visitors.config'],
                            new PhpSession()),

                $logRepository,

                new Path($pathModel),

                new Query($queryModel),

                new QueryArgument($queryArgumentModel),

                new Agent($agentModel),

                new Device($deviceModel),

                new Cookie($cookieModel,
                            $app['visitors.config'],
                            $app['request'],
                            $app['cookie']),

                new Domain($domainModel),

	            $app->make('\Blanfordia\Visitors\Data\Repositories\Referer', [$refererModel, $refererSearchTermModel, $this->getAppUrl()]),

                $routeRepository,

                new RoutePath($routePathModel),

                new RoutePathParameter($routePathParameterModel),

                new Error($errorModel),

                new GeoIpRepository($geoipModel),

				$sqlQueryRepository,

                $sqlQueryBindingRepository,

                $sqlQueryBindingParameterRepository,

                $sqlQueryLogRepository,

	            $connectionRepository,

	            $eventRepository,

	            $eventLogRepository,

	            $systemClassRepository,

		        $crawlerDetect
            );
        });
    }

    public function registerAuthentication()
    {
        $this->app['visitors.authentication'] = $this->app->share(function($app)
        {
            return new Authentication($app['visitors.config'], $app);
        });
    }

	private function registerTablesCommand()
	{
		$this->app['visitors.tables.command'] = $this->app->share(function($app)
		{
			return new TablesCommand();
		});
	}

	private function registerExecutionCallback()
	{
		$me = $this;

		$this->app['events']->listen('router.matched', function() use ($me)
		{
			$me->getVisitors()->routerMatched($me->getConfig('log_routes'));
		});
	}

	private function registerErrorHandler()
	{
		if ($this->getConfig('log_exceptions'))
		{
			if (isLaravel5())
			{
				$illuminateHandler = 'Illuminate\Contracts\Debug\ExceptionHandler';

				$handler = new VisitorsExceptionHandler(
					$this->getVisitors(),
					$this->app[$illuminateHandler]
				);

				// Replace original Illuminate Exception Handler by Visitors's
				$this->app[$illuminateHandler] = $handler;
			}
			else
			{
				$me = $this;

				$this->app->error(
					function (\Exception $exception, $code) use ($me)
					{
						$me->app['visitors']->handleException($exception, $code);
					}
				);
			}
		}
	}

	private function instantiateModel($modelName)
	{
		$model = $this->getConfig($modelName);

		if ( ! $model)
		{
			$message = "Visitors: Model not found for '$modelName'.";

			$this->app['log']->error($message);

			throw new \Exception($message);
		}

        $model = new $model;

        $model->setConfig($this->app['visitors.config']);

        if ($connection = $this->getConfig('connection'))
        {
            $model->setConnection($connection);
        }

		return $model;
	}

	private function registerSqlQueryLogWatcher()
	{
		$me = $this;

		$this->app['events']->listen('illuminate.query', function($query,
		                                                          $bindings,
		                                                          $time,
		                                                          $name) use ($me)
		{
			if ($me->getVisitors()->isEnabled())
			{
				$me->getVisitors()->logSqlQuery(
					$query, $bindings, $time, $name
				);
			}
		});
	}

	private function registerGlobalEventLogger()
	{
		$me = $this;

		$this->app['visitors.events'] = $this->app->share(function($app)
		{
			return new EventStorage();
		});

		$this->app['events']->listen('*', function($object = null) use ($me)
		{
			if ($me->app['visitors.events']->isOff())
			{
				return;
			}

			// To avoid infinite recursion, event tracking while logging events
			// must be turned off
			$me->app['visitors.events']->turnOff();

			// Log events even before application is ready
			$me->app['visitors.events']->logEvent(
				$me->app['events']->firing(),
				$object
			);

			// Can only send events to database after application is ready
			if (isset($me->app['visitors.loaded']))
			{
				$me->getVisitors()->logEvents();
			}

			// Turn the event tracking to on again
			$me->app['visitors.events']->turnOn();
		});

	}

	private function loadRoutes()
	{
		if (!$this->getConfig('stats_panel_enabled'))
		{
			return false;
		}

		$prefix = $this->getConfig('stats_base_uri');

		$namespace = $this->getConfig('stats_controllers_namespace');

		$filters = [];

		if ($before = $this->getConfig('stats_routes_before_filter'))
		{
			$filters['before'] = $before;
		}

		if ($after = $this->getConfig('stats_routes_after_filter'))
		{
			$filters['after'] = $after;
		}

		if ($middleware = $this->getConfig('stats_routes_middleware'))
		{
			$filters['middleware'] = $middleware;
		}

		$router = $this->app->make('router');

		$router->group(['namespace' => $namespace], function() use ($prefix, $router, $filters)
		{
			$router->group($filters, function() use ($prefix, $router)
			{
				$router->group(['prefix' => $prefix], function($router)
				{
					$router->get('/', array('as' => 'visitors.stats.index', 'uses' => 'Stats@index'));

					$router->get('log/{uuid}', array('as' => 'visitors.stats.log', 'uses' => 'Stats@log'));

					$router->get('api/pageviews', array('as' => 'visitors.stats.api.pageviews', 'uses' => 'Stats@apiPageviews'));

					$router->get('api/pageviewsbycountry', array('as' => 'visitors.stats.api.pageviewsbycountry', 'uses' => 'Stats@apiPageviewsByCountry'));

					$router->get('api/log/{uuid}', array('as' => 'visitors.stats.api.log', 'uses' => 'Stats@apiLog'));

					$router->get('api/errors', array('as' => 'visitors.stats.api.errors', 'uses' => 'Stats@apiErrors'));

					$router->get('api/events', array('as' => 'visitors.stats.api.events', 'uses' => 'Stats@apiEvents'));

					$router->get('api/users', array('as' => 'visitors.stats.api.users', 'uses' => 'Stats@apiUsers'));

					$router->get('api/visits', array('as' => 'visitors.stats.api.visits', 'uses' => 'Stats@apiVisits'));
				});
			});
		});
	}

	private function registerDatatables()
	{
		$this->registerServiceProvider('Bllim\Datatables\DatatablesServiceProvider');

		$this->registerServiceAlias('Datatable', 'Bllim\Datatables\Facade\Datatables');
	}

	/**
	 * Get the current package directory.
	 *
	 * @return string
	 */
	public function getPackageDir()
	{
		return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..';
	}

	/**
	 * Boot & Track
	 *
	 */
	private function bootVisitors()
	{
		$this->getVisitors()->boot();
	}

	/**
	 * Register global view composers
	 *
	 */
	private function registerGlobalViewComposers()
	{
		$me = $this;

		$this->app->make('view')->composer('blanfordia/visitors::*', function($view) use ($me)
		{
			$view->with('stats_layout', $me->getConfig('stats_layout'));

			$template_path = url('/') . $me->getConfig('stats_template_path');

			$view->with('stats_template_path', $template_path);
		});
	}

	private function registerUserCheckCallback()
	{
		$me = $this;

		$this->app['events']->listen('router.before', function($object = null) use ($me)
		{
			if ($me->visitors &&
				! $me->userChecked &&
				$me->getConfig('log_users') &&
				$me->app->resolved($me->getConfig('authentication_ioc_binding'))
			)
			{
				$me->userChecked = $me->getVisitors()->checkCurrentUser();
			}
		});
	}

	public function getVisitors()
	{
		if ( ! $this->visitors)
		{
			$this->visitors = $this->app['visitors'];
		}

		return $this->visitors;
	}

	public function getRootDirectory()
	{
		return __DIR__.'/../..';
	}

	private function getAppUrl()
	{
		return $this->app['request']->url();
	}

}
