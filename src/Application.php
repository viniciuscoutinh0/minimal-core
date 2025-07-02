<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use LogicException;
use Viniciuscoutinh0\Minimal\Contracts\CacheInterface;
use Viniciuscoutinh0\Minimal\Factory\CacheFactory;
use Viniciuscoutinh0\Minimal\Providers\ServiceProvider;

final class Application
{
    /**
     * Application instance.
     *
     * @var Application
     */
    private static ?Application $instance = null;

    /**
     * Registered service providers.
     *
     * @var ServiceProvider[]
     */
    private array $providers = [];

    /**
     * Request instance.
     *
     * @var Request
     */
    private Request $request;

    /**
     * Response instance.
     *
     * @var Response
     */
    private Response $response;

    /**
     * Vite instance.
     *
     * @var Vite
     */
    private Vite $vite;

    /**
     * Cache instance.
     *
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * Application locale.
     *
     * @var string|null
     */
    private ?string $locale = null;

    /**
     * Application timezone.
     *
     * @var string|null
     */
    private ?string $timezone = null;

    /**
     * Determine if the application is booted.
     *
     * @var bool
     */
    private bool $isBooted = false;

    private function __construct(private ?string $basePath = null)
    {
        $this->registerDefaultProviders();

        $this->request ??= Request::make();

        $this->response ??= Response::make();
    }

    /**
     * Create a new application instance.
     *
     * @param  string|null  $basePath
     * @return Application
     */
    public static function make(?string $basePath = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($basePath);
        }

        return self::$instance;
    }

    /**
     * Get the application base path.
     *
     * @return string|null
     */
    public function basePath(): ?string
    {
        return $this->basePath;
    }

    /**
     * Get the application locale.
     *
     * @return string|null
     */
    public function locale(): ?string
    {
        return $this->locale;
    }

    /**
     * Configure the application locale.
     *
     * @return self
     */
    public function configureLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get the application timezone.
     *
     * @return string|null
     */
    public function timezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * Configure the application timezone.
     *
     * @return self
     */
    public function configureTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Register a service provider.
     *
     * @return void
     */
    public function registerProvider(ServiceProvider $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * Get query parameters.
     *
     * @return InputBag
     */
    public function query(): InputBag
    {
        return $this->request->query();
    }

    /**
     * Get request parameters.
     *
     * @return InputBag
     */
    public function input(): InputBag
    {
        return $this->request->input();
    }

    /**
     * Get server parameters.
     *
     * @return ServerBag
     */
    public function server(): ServerBag
    {
        return $this->request->server();
    }

    /**
     * Get cookie parameters.
     *
     * @return InputBag
     */
    public function cookie(): InputBag
    {
        return $this->request->cookie();
    }

    /**
     * Get the response instance.
     *
     * @return Response
     */
    public function response(): Response
    {
        return $this->response;
    }

    /**
     * Register a view.
     *
     * @param  string  $view
     * @param  array  $data
     * @return View
     */
    public function view(string $view, array $data = []): View
    {
        return View::make($view, $data);
    }

    /**
     * Render a view.
     *
     * @param  string  $name
     * @return void
     */
    public function render(string $name): void
    {
        View::render($name);
    }

    /**
     * Get the Vite instance.
     *
     * @return Vite
     */
    public function vite(): Vite
    {
        return $this->vite;
    }

    /**
     * Get the cache instance.
     *
     * @return CacheInterface
     */
    public function cache(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * Boot the application.
     *
     * @return void
     */
    final public function boot(): void
    {
        if ($this->isBooted) {
            throw new LogicException('Application is already booted');
        }

        $this->bootProviders();

        $this->configureLocale(env('APP_LOCALE', 'en'));

        $this->configureTimezone(env('APP_TIMEZONE', 'UTC'));

        date_default_timezone_set($this->timezone());

        $this->vite ??= Vite::make(manifestPath: $this->basePath());

        $this->cache ??= CacheFactory::create();

        $this->isBooted = true;
    }

    /**
     * Register default providers.
     *
     * @return void
     */
    private function registerDefaultProviders(): void
    {
        $this->registerProvider(new Providers\EnvironmentProvider($this));
        $this->registerProvider(new Providers\IgnitionProvider($this));
        $this->registerProvider(new Providers\ViewProvider($this));
    }

    /**
     * Boot providers.
     *
     * @return void
     */
    private function bootProviders(): void
    {
        foreach ($this->providers as $provider) {
            $provider->register();
            $provider->boot();
        }
    }
}
