<?php
class RedisCache {
    private $redis;
    private $connected = false;

    public function __construct() {
        $this->redis = new Redis();
        $config = require __DIR__.'/../../config/cache.php';
        
        try {
            $this->connected = $this->redis->pconnect(
                $config['host'],
                $config['port'],
                $config['timeout'],
                null,
                0,
                0,
                ['stream' => $config['persistent'] ? 'persistent_id' : null]
            );
        } catch (RedisException $e) {
            error_log("Redis connection failed: " . $e->getMessage());
        }
    }

    public function get($key) {
        if (!$this->connected) return null;
        return $this->redis->get($key);
    }

    public function set($key, $value, $ttl = 0) {
        if (!$this->connected) return false;
        return $this->redis->set($key, $value, $ttl);
    }
}
