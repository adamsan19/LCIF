<?php
class SearchService {
    private $fileModel;
    
    public function __construct() {
        $this->fileModel = new FileModel();
    }
    
    public function search($params) {
        // Validasi input
        if (empty($params['q'])) {
            throw new ApiException("Search query required", 400);
        }
        
        // Cek cache
        $cache = new LRUCache();
        $cacheKey = 'search:'.md5(serialize($params));
        
        if ($cached = $cache->get($cacheKey)) {
            return $cached;
        }
        
        // Multi-source fallback
        try {
            $results = $this->fileModel->search($params['q']);
        } catch (DatabaseException $e) {
            // Fallback ke file cache
            $results = $this->getFromFileCache($params['q']);
        }
        
        // Simpan ke cache
        $cache->set($cacheKey, $results, 300);
        
        return $results;
    }
    
    private function getFromFileCache($query) {
        // Implement file-based cache fallback
        return ['results' => ['cached_result1', 'cached_result2']];
    }
}
