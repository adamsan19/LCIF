<?php
class LRUCache {
    private $capacity;
    private $cache = [];
    private $queue = [];
    
    public function __construct($capacity = 1000) {
        $this->capacity = $capacity;
    }
    
    public function get($key) {
        if (!isset($this->cache[$key])) return null;
        
        // Update queue
        $this->refreshKey($key);
        
        return $this->cache[$key];
    }
    
    public function set($key, $value, $ttl = 0) {
        if (count($this->cache) >= $this->capacity) {
            $oldest = array_shift($this->queue);
            unset($this->cache[$oldest]);
        }
        
        $this->cache[$key] = $value;
        $this->refreshKey($key);
    }
    
    private function refreshKey($key) {
        unset($this->queue[array_search($key, $this->queue)]);
        $this->queue[] = $key;
    }
}
