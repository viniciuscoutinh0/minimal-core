<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use RuntimeException;
use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;

final class View
{
    use StaticConstruct;

    private string $path = 'resources/views';

    private string $extension = 'php';

    private static string $basePath;

    private static array $shared = [];

    private static array $views = [];

    public function __construct(
        private string $view,
        private array $data = []
    ) {
    }

    public static function configureBasePath(string $path): void
    {
        self::$basePath = $path;
    }

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

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

    public function with(string $key, mixed $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function name(string $name): void
    {
        self::$views[$name] = $this;
    }

    private function normalizeViewPath(string $viewPath): string
    {
        return str_replace(['.', '\\', ' '], DIRECTORY_SEPARATOR, $viewPath);
    }
}
