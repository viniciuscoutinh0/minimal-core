<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;
use Viniciuscoutinh0\Minimal\Enums\HttpStatus;

final class Response
{
    use StaticConstruct;

    /**
     * Response Headers.
     *
     * @var array<string,string>
     */
    private array       $headers = [];

    /**
     * HTTP Status Code for the response.
     *
     * @var HttpStatus
     */
    private HttpStatus  $httpStatusCode = HttpStatus::Ok;

    /**
     * Response content.
     *
     * @var string
     */
    private string      $content = '';

    /**
     * Sets a response header.
     *
     * @param string $key
     * @param string $value
     * @return Response
     */
    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Returns all headers defined for the response.
     *
     * @return array<string,string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @param HttpStatus $httpStatus
     * @return self
     */
    public function httpStatusCode(HttpStatus $httpStatus): self
    {
        $this->httpStatusCode = $httpStatus;

        return $this;
    }

    /**
     * Returns the current HTTP status code of the response.
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode->value;
    }

    /**
     * Sets the content of the response.
     *
     * @param string $body
     * @return self
     */
    public function content(string $body): self
    {
        $this->content = $body;

        return $this;
    }

    /**
     * Returns the content of the response.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sends the HTTP response to the client.
     *
     * @return void
     */
    public function send(): void
    {
        if (! headers_sent()) {
            $this->configureResponseHeader();
            $this->configureHttpStatusCode();
        }

        echo $this->content;
    }

    /**
     * Sends a JSON response.
     *
     * @param array|Collection $data
     * @param HttpStatus $httpStatus
     * @return void
     */
    public function toJson(array|Collection $data, HttpStatus $httpStatus = HttpStatus::Ok): void
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;

        $this
            ->header('accept', 'application/json')
            ->header('content-type', 'application/json')
            ->httpStatusCode($httpStatus)
            ->content(json_encode($data))
            ->send();
    }

    /**
     * Configures HTTP headers for the response.
     *
     * @return void
     */
    private function configureResponseHeader(): void
    {
        if (headers_sent()) {
            return;
        }

        foreach ($this->headers as $key => $value) {
            header(
                sprintf('%s: %s', $this->normalizeHeaderKeyName($key), $value)
            );
        }
    }

    /**
     * Configures the HTTP status code for the response.
     *
     * @return void
     */
    private function configureHttpStatusCode(): void
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($this->getHttpStatusCode());
    }

    /**
     * Normalizes header names to proper HTTP format.
     *
     * @param string $key
     * @return string
     */
    private function normalizeHeaderKeyName(string $key): string
    {
        return implode('-', array_map('ucfirst', explode('-', $key)));
    }
}
