<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This software is open and free you may use it in whatever manner you deem fit. You
 * don't have to give me any credit for it, but it would be nice if you dropped me a
 * line on my blog and let me know how awesome I am.
 *
 * Enjoy!
 *
 * -JimDoesCode
 **/

class Scache
{
    var $expiration;
    var $path;
    function __construct()
    {
        $CI =& get_instance();

        $this->expiration = $CI->config->item('cache_expiration');

        $path = $CI->config->item('cache_path');
        $this->path = ($path == '') ? APPPATH.'cache/' : $path;
    }
    
    function clear($key)
    {
        $filepath = $this->path.md5($key);
        @unlink($filepath);
    }
    
    function read($key)
    {
        $filepath = $this->path.md5($key);
        if(!@file_exists($filepath))return false;
        if(!$fp = @fopen($filepath, FOPEN_READ))return false;
        
        flock($fp, LOCK_SH);
        $cache = '';
        $size = filesize($filepath);
        if($size > 0)$cache = fread($fp, $size);
        flock($fp, LOCK_UN);
        fclose($fp);
        
        $time = trim(substr($cache, 0, strpos($cache, "\n")));
        $cache = str_replace($time, "", trim($cache));
        if(time() < $time)return $cache; 
        @unlink($filepath);
        return false;
    }
    
    function write($key, $output)
    {
        if(is_dir($this->path) && is_really_writable($this->path))
        {
            $cachepath = $this->path.md5($key);
            if($fp = @fopen($cachepath, FOPEN_WRITE_CREATE_DESTRUCTIVE))
            {
                $expire = time() + ($this->expiration * 60);
                if(flock($fp, LOCK_EX))
                {
                    fwrite($fp, $expire."\n".$output);
		    flock($fp, LOCK_UN);
                }
                fclose($fp);
		@chmod($cachepath, DIR_WRITE_MODE);
            }
        }

    }
}

?>