<?php
class RateLimitException extends ApiException {
    public function __construct($message = "Rate limit exceeded", $code = 429, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
