<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use RuntimeException;
use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;

final class Vite
{
    use StaticConstruct;

    public function __construct(
        protected ?string $host = null,
        protected ?int $port = null,
        protected ?string $manifestPath = null,
    ) {
        [$host, $port] = explode(':', env('VITE_API_URL', 'localhost:5173'));

        $this->host = 'http://'.$host;
        $this->port = (int) $port;
    }

    public function url(): string
    {
        return $this->host.':'.$this->port;
    }

    public function asset(string $entry): string
    {
        return "\n".$this->jsTag($entry)
        ."\n".$this->jsPreloadImports($entry)
        ."\n".$this->cssTag($entry);
    }

    public function assetUrl(string $entry): string
    {
        $manifest = $this->manifest();

        return isset($manifest[$entry])
            ? '/dist/'.$manifest[$entry]['file']
            : '';
    }

    public function manifest(): array
    {
        $path = $this->manifestPath ?? __DIR__.'/../public/dist/.vite/manifest.json';

        if (! file_exists($path)) {
            throw new RuntimeException('Manifest not found, run `npm run build` first');
        }

        return json_decode(file_get_contents($path), true);
    }

    public function cssUrls(string $entry): array
    {
        $urls = [];
        $manifest = $this->manifest();

        if (! empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $file) {
                $urls[] = '/dist/'.$file;
            }
        }

        return $urls;
    }

    private function isDevMode(string $entry): bool
    {
        static $exists = null;

        if ($exists !== null) {
            return $exists;
        }

        $handle = curl_init($this->url().'/'.$entry);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);

        curl_exec($handle);
        $error = curl_errno($handle);
        curl_close($handle);

        return $exists = ! $error;
    }

    private function jsTag(string $entry): string
    {
        $url = $this->isDevMode($entry)
            ? $this->url().'/'.$entry
            : $this->assetUrl($entry);

        if (! $url) {
            return '';
        }

        if ($this->isDevMode($entry)) {
            return '<script type="module" src="'.$this->url().'/@vite/client"></script>'."\n"
                .'<script type="module" src="'.$url.'"></script>';
        }

        return '<script type="module" src="'.$url.'"></script>';
    }

    private function cssTag(string $entry): string
    {
        if ($this->isDevMode($entry)) {
            return '';
        }

        $tags = '';

        foreach ($this->cssUrls($entry) as $url) {
            $tags .= '<link rel="stylesheet" href="'
                .$url
                .'">';
        }

        return $tags;
    }

    private function jsPreloadImports(string $entry): string
    {
        if ($this->isDevMode($entry)) {
            return '';
        }

        $res = '';
        foreach ($this->importsUrls($entry) as $url) {
            $res .= '<link rel="modulepreload" href="'
                .$url
                .'">';
        }

        return $res;
    }

    private function importsUrls(string $entry): array
    {
        $urls = [];
        $manifest = $this->manifest();

        if (! empty($manifest[$entry]['imports'])) {
            foreach ($manifest[$entry]['imports'] as $imports) {
                $urls[] = '/dist/'.$manifest[$imports]['file'];
            }
        }

        return $urls;
    }
}
