<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use RuntimeException;
use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;

final class View
{
    use StaticConstruct;

    /**
     * Path to views.
     *
     * @var string
     */
    private string $path = 'resources/views';

    /**
     * View extension.
     *
     * @var string
     */
    private string $extension = 'php';

    /**
     * Base path.
     *
     * @var string
     */
    private static string $basePath;

    /**
     * Shared data.
     *
     * @var array
     */
    private static array $shared = [];

    /**
     * Registered views.
     *
     * @var array
     */
    private static array $views = [];

    public function __construct(
        private string $view,
        private array $data = []
    ) {
    }

    /**
     * Configure the application base path.
     *
     * @param  string  $path
     * @return void
     */
    public static function configureBasePath(string $path): void
    {
        self::$basePath = $path;
    }

    /**
     * Share var to all views.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Render a view.
     *
     * @param  string  $name
     * @return void
     */
    public static function render(string $name): void
    {
        if (! isset(self::$views[$name])) {
            throw new RuntimeException('View ['.$name.'] is not registered');
        }

        /** @var self $instance */
        $instance = self::$views[$name];

        $file = self::$basePath.'/'.$instance->path.'/'.$instance->normalizeViewPath($instance->view).'.'.$instance->extension;

        if (! file_exists($file)) {
            throw new RuntimeException('View file ['.$file.'] not found');
        }

        extract(array_merge($instance->data, self::$shared), EXTR_SKIP);

        include $file;
    }

    /**
     * Add data to view.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return self
     */
    public function with(string $key, mixed $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Define view name.
     *
     * @param  string  $name
     * @return void
     */
    public function name(string $name): void
    {
        self::$views[$name] = $this;
    }

    /**
     * Normalize view path.
     *
     * @param  string  $viewPath
     * @return string
     */
    private function normalizeViewPath(string $viewPath): string
    {
        return str_replace(['.', '\\', ' '], DIRECTORY_SEPARATOR, $viewPath);
    }
}
