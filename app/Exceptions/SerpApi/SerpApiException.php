<?php

namespace App\Exceptions\SerpApi;

use Exception;

class SerpApiException extends Exception
{
    public static function notConfigured(): self
    {
        return new self('Flight search API is not configured.');
    }

    public static function fromResponse(string $message, int $code = 0): self
    {
        return new self($message, $code);
    }

    public function userMessage(): string
    {
        $message = $this->getMessage();

        if (str_contains(strtolower($message), 'rate limit')) {
            return 'Flight search is temporarily busy. Please try again in a few minutes.';
        }

        if (str_contains(strtolower($message), 'unavailable') || str_contains(strtolower($message), 'timeout')) {
            return 'Flight search is temporarily unavailable. Please try again shortly.';
        }

        if (str_contains(strtolower($message), 'no flights') || str_contains(strtolower($message), 'hasn\'t returned any results')) {
            return 'No flights found for your search. Try different dates or airports.';
        }

        return $message !== '' ? $message : 'Unable to complete flight search. Please try again.';
    }
}
