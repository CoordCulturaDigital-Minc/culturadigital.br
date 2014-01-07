<?php

/**
* @link http://code.google.com/p/php-lzw/
* @author Jakub Vrana, http://www.vrana.cz/
* @copyright 2009 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
*/


class Mixin_Lzo_Instance_Methods extends Mixin
{
	/** LZW compression
	* @param string data to compress
	* @return string binary data
	*/
	function compress($string) {
		// compression
		$dictionary = array_flip(range("\0", "\xFF"));
		$word = "";
		$codes = array();
		for ($i=0; $i <= strlen($string); $i++) {
			$x = substr($string, $i, 1);
			if (strlen($x) && isset($dictionary[$word . $x])) {
				$word .= $x;
			} elseif ($i) {
				$codes[] = $dictionary[$word];
				$dictionary[$word . $x] = count($dictionary);
				$word = $x;
			}
		}

		// convert codes to binary string
		$dictionary_count = 256;
		$bits = 8; // ceil(log($dictionary_count, 2))
		$return = "";
		$rest = 0;
		$rest_length = 0;
		foreach ($codes as $code) {
			$rest = ($rest << $bits) + $code;
			$rest_length += $bits;
			$dictionary_count++;
			if ($dictionary_count >> $bits) {
				$bits++;
			}
			while ($rest_length > 7) {
				$rest_length -= 8;
				$return .= chr($rest >> $rest_length);
				$rest &= (1 << $rest_length) - 1;
			}
		}
		return $return . ($rest_length ? chr($rest << (8 - $rest_length)) : "");
	}


	/** LZW decompression
	* @param string compressed binary data
	* @return string original data
	*/
	function decompress($binary) {
		// convert binary string to codes
		$dictionary_count = 256;
		$bits = 8; // ceil(log($dictionary_count, 2))
		$codes = array();
		$rest = 0;
		$rest_length = 0;
		for ($i=0; $i < strlen($binary); $i++) {
			$rest = ($rest << 8) + ord($binary[$i]);
			$rest_length += 8;
			if ($rest_length >= $bits) {
				$rest_length -= $bits;
				$codes[] = $rest >> $rest_length;
				$rest &= (1 << $rest_length) - 1;
				$dictionary_count++;
				if ($dictionary_count >> $bits) {
					$bits++;
				}
			}
		}

		// decompression
		$dictionary = range("\0", "\xFF");
		$return = "";
		foreach ($codes as $i => $code) {
			$element = $dictionary[$code];
			if (!isset($element)) {
				$element = $word . $word[0];
			}
			$return .= $element;
			if ($i) {
				$dictionary[] = $word . $element[0];
			}
			$word = $element;
		}
		return $return;
	}
}

class C_Lzw extends C_Component
{
	static $_instances = array();

	function define($context=FALSE)
	{
		parent::define($context);
		$this->add_mixin('Mixin_Lzo_Instance_Methods');
		$this->implement('I_Lzw');
	}

	static function &get_instance($context=FALSE)
	{
		if (!isset(self::$_instances[$context])) {
			$klass = get_class();
			self::$_instances[$context] = new $klass($context);
		}
		return self::$_instances[$context];
	}
}

