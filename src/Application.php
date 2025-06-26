<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use LogicException;
use Symfony\Component\Console\Input\Input;
use Viniciuscoutinh0\Minimal\Providers\ServiceProvider;

final class Application
{
    private static ?Application $instance = null;

    /** @var ServiceProvider[] */
    private array $providers = [];

    private Request $request;

    private Response $response;

    private Vite $vite;

    private ?string $locale = null;

    private ?string $timezone = null;

    private bool $isBooted = false;

    private function __construct(private ?string $basePath = null)
    {
        $this->registerDefaultProviders();

        $this->request ??= Request::make();

        $this->response ??= Response::make();

        $this->vite ??= Vite::make(manifestPath: $basePath);
    }

    public static function make(?string $basePath = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($basePath);
        }

        return self::$instance;
    }

    public function basePath(): ?string
    {
        return $this->basePath;
    }

    public function locale(): ?string
    {
        return $this->locale;
    }

    public function configureLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function timezone(): ?string
    {
        return $this->timezone;
    }

    public function configureTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function registerProvider(ServiceProvider $provider): void
    {
        $this->providers[] = $provider;
    }

    public function query(): InputBag
    {
        return $this->request->query();
    }

    public function request(): InputBag
    {
        return $this->request->request();
    }

    public function server(): ServerBag
    {
        return $this->request->server();
    }

    public function cookie(): InputBag
    {
        return $this->request->cookie();
    }

    public function response(): Response
    {
        return $this->response;
    }

    public function view(string $view, array $data = []): View
    {
        return View::make($view, $data);
    }

    public function vite(): Vite
    {
        return $this->vite;
    }

    public function boot(): void
    {
        if ($this->isBooted) {
            throw new LogicException('Application is already booted');
        }

        $this->bootProviders();

        $this->configureLocale(env('APP_LOCALE', 'en'));

        $this->configureTimezone(env('APP_TIMEZONE', 'UTC'));

        date_default_timezone_set($this->timezone());

        $this->isBooted = true;
    }

    private function registerDefaultProviders(): void
    {
        $this->registerProvider(new Providers\EnvironmentProvider($this));
        $this->registerProvider(new Providers\IgnitionProvider($this));
        $this->registerProvider(new Providers\ViewProvider($this));
    }

    private function bootProviders(): void
    {
        foreach ($this->providers as $provider) {
            $provider->register();
            $provider->boot();
        }
    }
}
