<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;
use Viniciuscoutinh0\Minimal\Enums\HttpStatus;

final class Response
{
    use StaticConstruct;

    /**
     * Response headers.
     *
     * @var array<string,string>
     */
    private array $headers = [];

    /**
     * HTTP Status Code for the response.
     *
     * @var HttpStatus
     */
    private HttpStatus $status = HttpStatus::Ok;

    /**
     * Response content.
     *
     * @var string
     */
    private string $content = '';

    /**
     * Sets a response header.
     *
     * @param string $key
     * @param string $value
     * @return self
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
    public function statusCode(HttpStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns the current HTTP status code of the response.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status->value;
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
     * @return self
     */
    public function send(): self
    {
        if (! headers_sent()) {
            $this->sendHeaders();
            $this->sendHttpStatusCode();
        }

        echo $this->content;

        return $this;
    }

    /**
     * Sends a JSON response.
     *
     * @param array|Collection $data
     * @param HttpStatus $status
     * @return self
     */
    public function json(array|Collection $data, HttpStatus $status = HttpStatus::Ok): self
    {
        $content = $data instanceof Collection ? $data->toArray() : $data;

        $this
            ->header('content-type', 'application/json')
            ->statusCode($status)
            ->content(json_encode($content));

        return $this;
    }

    /**
     * sends HTTP headers for the response.
     *
     * @return void
     */
    private function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        foreach ($this->headers as $key => $value) {
            header(sprintf('%s: %s', $this->normalizeHeaderKeyName($key), $value));
        }
    }

    /**
     * sends the HTTP status code for the response.
     *
     * @return void
     */
    private function sendHttpStatusCode(): void
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($this->getStatus());
    }

    /**
     * Normalizes header names to proper HTTP format.
     *
     * @param string $key Header name
     * @return string Properly formatted header name
     */
    private function normalizeHeaderKeyName(string $key): string
    {
        return implode('-', array_map('ucfirst', explode('-', $key)));
    }
}
