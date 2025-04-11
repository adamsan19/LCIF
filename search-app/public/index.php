<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/cache.php';
require_once __DIR__.'/../libs/Utilities/HttpUtil.php';
require_once __DIR__.'/../libs/Utilities/RateLimiter.php'; // Add this line
require_once __DIR__.'/../libs/Exceptions/ApiException.php';

// Error Handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ApiException($errstr, 500);
});

// CORS Simple
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    // Rate Limiting
    $ip = $_SERVER['REMOTE_ADDR'];
    $rateLimiter = new RateLimiter(100, 3600); // 100 requests/hour
    $rateLimiter->check($ip);

    // Main Logic
    $searchService = new SearchService();
    $results = $searchService->search($_GET);
    
    // Cache Headers
    HttpUtil::setCacheHeaders(300);
    
    echo json_encode([
        'status' => 'success',
        'data' => $results,
        'meta' => [
            'ip' => $ip,
            'time' => time()
        ]
    ]);

} catch (RateLimitException $e) {
    http_response_code(429);
    echo json_encode(['error' => $e->getMessage()]);
} catch (ApiException $e) {
    http_response_code($e->getCode());
    echo json_encode(['error' => $e->getMessage()]);
}
