<?php
class RateLimiter {
    private $limit;
    private $window;
    private $cache;
    
    public function __construct($limit = 100, $window = 3600) {
        $this->limit = $limit;
        $this->window = $window;
        $this->cache = new RedisCache(); // Atau LRUCache
    }
    
    public function check($ip) {
        $key = "rate_limit:$ip";
        $count = $this->cache->get($key) ?: 0;
        
        if ($count >= $this->limit) {
            throw new RateLimitException("Too many requests");
        }
        
        $this->cache->set($key, $count + 1, $this->window);
    }
}
