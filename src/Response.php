<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use RuntimeException;
use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;

final class Response
{
    use StaticConstruct;

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
     * @throws RuntimeException
     */
    public function redirect(string $url, int $code = 301): void
    {
        if ($this->isSentHeaders()) {
            throw new RuntimeException('Headers already sent');
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
