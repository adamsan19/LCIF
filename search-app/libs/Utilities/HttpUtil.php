<?php
class HttpUtil {
    public static function setCacheHeaders($maxAge = 300) {
        header("Cache-Control: public, max-age=$maxAge");
        header("Expires: ".gmdate('D, d M Y H:i:s', time() + $maxAge).' GMT');
        header("Last-Modified: ".gmdate('D, d M Y H:i:s').' GMT');
    }
    
    public static function validateETag($content) {
        $etag = md5($content);
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
            header('HTTP/1.1 304 Not Modified');
            exit;
        }
        header("ETag: $etag");
    }
}
