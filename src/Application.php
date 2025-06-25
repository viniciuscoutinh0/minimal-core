<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use LogicException;
use Viniciuscoutinh0\Minimal\Providers\ServiceProvider;

final class Application
{
    private static ?Application $instance = null;

    /** @var ServiceProvider[] */
    private array $providers = [];

    private Request $request;

    private Response $response;

    private Vite $vite;

    private bool $isBooted = false;

    private function __construct(private ?string $basePath = null)
    {
        $this->registerDefaultProviders();

        $this->request ??= Request::make($_GET, $_POST, $_SERVER, $_COOKIE);

        $this->response ??= Response::make();

        $this->vite ??= Vite::make();
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

    public function registerProvider(ServiceProvider $provider): void
    {
        $this->providers[] = $provider;
    }

    public function request(): Request
    {
        return $this->request;
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
