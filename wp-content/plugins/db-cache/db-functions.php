<?php
/**
 * Cache framework
 * Author: Dmitry Svarytsevych
 * http://design.lviv.ua
 */

class pcache {

	var $lifetime = 1800;
	var $storage = WP_CONTENT_DIR;

	function load($tag = '') {
		if ($tag == '') return false;

		$file = $this->storage."/".$tag;
		$result = false;
		
		// If file exists
		if ($filemtime = @filemtime($file)) {
		
			$f = @fopen($file, 'r');
			if ($f) {
				@flock($f, LOCK_SH);
				// for PHP5
				if (function_exists('stream_get_contents')) $result = unserialize(stream_get_contents($f));
				// for PHP4
				else {
					$result = '';
					while (!feof($f)) {
	  				$result .= fgets($f, 4096);
					}
					$result = unserialize($result);
				}
				@flock($f, LOCK_UN);
				@fclose($f);

				// Remove if expired
				if (($filemtime + $this->lifetime - time()) < 0) $this->remove($tag);
			}
		}

		return $result;
	}
	
	function save($value = '', $tag = '') {
		if ($tag == '' || $value == '') return false;
		
		$file = $this->storage."/".$tag;
		
		$f = @fopen($file, 'w');
		if (!$f) return false;

			@flock($f, LOCK_EX);
			@fwrite($f, serialize($value));
			@flock($f, LOCK_UN);
			@fclose($f);
			@chmod($file, 0644);

		return true;
	}

	function remove($tag = '', $dir = false) {
		if ($tag == '') return false;
		
		if (!$dir) $dir = $this->storage;
		
		$file = $dir."/".$tag;

		if (is_file($file) && @unlink($file)) return true;
		
		return false;
	}
	
	function clean($old = true) {
		
		$folders = array("/tmp","/tmp/options","/tmp/links","/tmp/terms","/tmp/users","/tmp/posts");
		foreach($folders as $folder) {
		
			if ($dir = @opendir(WP_CONTENT_DIR.$folder)) {
			while ($tag = readdir($dir)) {
				if ($tag != '.' && $tag != '..' && $tag != '.htaccess') {
					// Clean all
					if (!$old) $this->remove($tag, WP_CONTENT_DIR.$folder);
					// Clean only old
					elseif ((@filemtime($file) + $this->lifetime - time()) < 0) $this->remove($tag);
				}
			}
			closedir($dir);
			}
			
		}
	}

}

?>