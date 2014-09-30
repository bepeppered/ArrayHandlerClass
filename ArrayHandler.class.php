<?php
 
/**
 * This class handles and manage array, and make a few operations with them.
 *
 * Array handling in PHP is not that easy than you need it. This class helps
 * to optimize this array handling. All functions are static to use them
 * without making an object, so you can use ArrayHandler::function().
 *
 * @author David Pauli
 * @license GNU GPLv3
 * @link http://www.david-pauli.de/projekte/effizient-mit-arrays-umgehen german description of class
 * @version 140930
 */
 
class ArrayHandler {
 
	/**
	 * Merge two arrays recursively without reindex all indices.
	 *
	 * This function makes a new array with two given arrays. The
     * PHP given intern function array_merge_recursive
     * (see www.php.net/array_merge_recursive) overwrites the
     * numeric key (reindex keys).
	 *
     * @access public
     * @param array $firstArray This is the first array to merge.
     * @param array $secondArray This is the second array to merge.
     * @return array The merged result.
	 * @since 140930
	 * @static
	 * @version 140930
	 */
	function mergeRecursive($firstArray, $secondArray) {

        $arrays = array($firstArray, $secondArray);
		$base = array_shift($arrays);
 
		foreach ($arrays as $array) {
			reset($base);
			while (list($key, $value) = @each($array)) {
				if (is_array($value) && @is_array($base[$key])) {
					$base[$key] = self::mergeRecursive($base[$key], $value);
				} else {
					$base[$key] = $value;
				}
			}
		}
		return $base;
	}
 
	/**
     * Makes an 1-dimensional array to an more-dimensional array.
     *
     * This function helps you to explode a flat array make more dimensional.
     * Sending an 1-dimensional array like
     *    [key1]
     *    [key2]
     *    [key3]
     *    value = "test"
     *  makes a more-dimensional array like
     *    [key1]
     *       [key2]
     *          [key3] = test
     *
     * @access public
     * @param array $path This is the flat 1-dimensional array.
     * @param mixed $value You can set an element in last array. This parameter is optional.
     * @return array The more-dimensional array.
     * @since 140930
     * @static
     * @version 140930
	 */
	function feaze($path, $value="") {
 
		$result = array();
 
		if($path[0]==="") $path = array_splice($path, 1);
 
		if(isset($path[0]) && isset($path[1])) {
			$parameter = array_splice($path, 1);
			$result[$path[0]] = self::feaze($parameter, $value);
		}
		else if(isset($path[0])) {
			$result[$path[0]] = $value;
		}
 
		return $result;
	}
 
	/**
	 * Gets the dimension of an array.
     *
     * The dimension is the deepness is countable with this function.
     * The array
     *    [var1]
     *       [var2]
     *          [var3]
     * has the dimension of 3.
     *
     * @access public
     * @param array $array The array which dimension should be counted.
     * @return int Which is the dimension of the array.
     * @since 140930
     * @static
     * @version 140930
	 */
	function getDimension($array) {
 
		$dimension = 0;
 
		if(is_Array($array)) {
			$dimension++;
			foreach($array as $key => $value) {
				if(is_Array($array[$key])) {
					$dimension = self::getDimension($array[$key])+$dimension;
				}
				return self::getDimension($array[$key])+$dimension;
			}
		}
		return $dimension;
	}
 
	/**
	 * Check whether there are only arrays in a array.
     *
     * With this function you can check a flat 1-dimensional array if
     * there are only array in there.
     *
     * @access public
     * @param array $array The array which you want to check.
     * @return boolean True means there are only arrays in this array.
     * @since 140930
     * @static
     * @todo Make this function recursive.
     * @version 140930
	 */
	function containsOnlyArrays($array) {
		foreach($array as &$value) {
			if(!is_Array($value)) return false;
		}
		return true;
	}
 
	/**
	 * Clean an array.
     *
     * Delete all values of the array recursive. The function
     * change not the value of the keys. All integer will
     * change to 0, all boolean will be false, all strings
     * will be "".
     *
     * @access public
     * @param array $array The array which should be cleaned.
     * @return array Returns the cleaned array.
     * @since 140930
     * @static
     * @version 140930
	 */
	function clean($array) {
 
		foreach($array as &$value) {
			if(is_Array($value)) $value = self::clean($value);
			else if(is_Int($value)) $value = 0;
			else if(is_Bool($value)) $value = false;
			else $value = "";
		}
		return $array;
	}

	/**
     * Sorts the keys of an array recursive.
     *
     * The PHP intern function ksort only sorts in one dimension. This
     * function sorts an array in each deep.
     *
     * @access public
     * @param array $array The array which keys you want to recursive sort.
     * @return array Returns the sorted array.
     * @since 140930
     * @static
     * @version 140930
	 */
	function kSortRecursive($array) {

		ksort($array);
		foreach($array as &$value) {
 
			if(is_Array($value) && !empty($value)) self::ksortRecursive($value);
		}
		return $array;
	}
 
	/**
     * Search a  key of an array recursive.
     *
     * If there the searched key is found in array you will get
     * true.
     *
     * @access public
     * @param string $needle This is the string you want to search.
     * @param array $array This is the array you want to search.
     * @return boolean Returns true if you find this needle.
     * @since 140930
     * @static
     * @version 140930
	 */
	function searchKey($needle, $array) {
 
		$result = array_key_exists($needle, $array);
		if($result) return true;
 
		foreach($array as &$value) {
			if(is_Array($value)) $result = self::searchKey($needle, $value);
			if($result) return true;
		}
		return false;
	}
 
	/**
	 * Prints an array on display.
     *
     * There are two options to print an array. You can print
     * with print_r() or var_dump().
	 *
     * @access public
     * @param array $array This array should be printed.
     * @param int $mode Print it with print_r (0) or var_dump (1).
     * @return void
     * @since 140930
     * @static
     * @version 140930
	 */
	function printArray($array, $mode=0) {
		echo "<pre>";
		if($mode==0) print_r($array);
		else if($mode==1) var_dump($array);
		echo "</pre>";
	}
}

?>
