<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Viniciuscoutinh0\Minimal\Enums\HttpStatus;

final class Response
{
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
     * Set the HTTP status code.
     *
     * @param  int  $code
     * @return self
     * @throws RuntimeException
     */
    public function httpStatusCode(int $code): self
    {
        if ($this->isSentHeaders()) {
            throw new RuntimeException('Headers already sent');
        }

        http_response_code($code);

        return $this;
    }

    /**
     * Redirect a specific URL.
     *
     * @param  string  $url
     * @param  int  $code
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

        $this->httpStatusCode($code);

        header(header: "Location: {$url}");

        exit;
    }

    /**
     * Check if headers are already sent.
     *
     * @return bool
     */
    private function isSentHeaders(): bool
    {
        return headers_sent();
    }
}
