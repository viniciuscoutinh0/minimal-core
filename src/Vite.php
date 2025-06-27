<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use RuntimeException;
use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;

final class Vite
{
    use StaticConstruct;

    public function __construct(
        protected string $manifestPath,
        protected ?string $host = null,
        protected ?int $port = null,
    ) {
        ['scheme' => $schema, 'host' => $host, 'port' => $port] = parse_url(env('VITE_API_URL', 'http://localhost:5173'));

        $this->host = "{$schema}://{$host}";
        $this->port = (int) $port;
    }

    /**
     * Get the vite url.
     *
     * @return string
     */
    public function url(): string
    {
        return $this->host.':'.$this->port;
    }

    /**
     * Get the vite asset.
     *
     * @param  string  $entry
     * @return string
     */
    public function asset(string $entry): string
    {
        return "\n".$this->jsTag($entry)
        ."\n".$this->jsPreloadImports($entry)
        ."\n".$this->cssTag($entry);
    }

    /**
     * Get the vite asset url.
     *
     * @param  string  $entry
     * @return string
     */
    public function assetUrl(string $entry): string
    {
        $manifest = $this->manifest();

        return isset($manifest[$entry])
            ? '/dist/'.$manifest[$entry]['file']
            : '';
    }

    /**
     * Get the vite manifest.
     *
     * @return array
     */
    public function manifest(): array
    {
        $path = $this->manifestPath.'/public/dist/.vite/manifest.json';

        if (! file_exists($path)) {
            throw new RuntimeException('Manifest not found, run `npm run build` first');
        }

        return json_decode(file_get_contents($path), true);
    }

    /**
     * Get the vite css urls.
     *
     * @param  string  $entry
     * @return array
     */
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

    /**
     * Check if the vite is in dev mode.
     *
     * @param  string  $entry
     * @return bool
     */
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

    /**
     * Get the vite js tag.
     *
     * @param  string  $entry
     * @return string
     */
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

    /**
     * Get the vite css tag.
     *
     * @param  string  $entry
     * @return string
     */
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

    /**
     * Get the vite js preload imports.
     *
     * @param  string  $entry
     * @return string
     */
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

    /**
     * Get the vite imports urls.
     *
     * @param  string  $entry
     * @return array
     */
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
