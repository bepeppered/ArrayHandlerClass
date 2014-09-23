<?php
 
/*********************
 * ARRAYHANDLER class
 *********************
 *   This class handles and manage array, and make a few operations.
 *
 *   @author David Pauli
 *
 *   Version
 *   *******
 *      V1. version with following functions
 *		- mergeRecursive()
 *		  This function will merge 2 arrays to 1 (without reindex all indizes).
 *		- feaze()
 *		  Expands an 1-dimensional array to a more-dimensional array.
 *		- getDimension()
 *		  Gets the dimension of an array.
 *		- containsOnlyArrays()
 *		  Check if there are only arrays (no strings/ints/something like that) in the array.
 *		- clean()
 *		  clean array, so there is only the structure without values
 *		- ksortRecursive()
 *		  sorts the keys in right order recursive
 *		- searchKey()
 *		  search an array key recursive
 *		- printArray()
 *		  prints an array with some possible options
 ********************/
 
class ArrayHandler {
 
	/*******************
	 * mergeRecursive()
	 *******************
	 *   merge array recursivly WITHOUT reindex all indizes
	 *   Why not use PHP intern function array_merge_recursive (see www.php.net/array_merge_recursive)?
	 *   array_merge_recursive will overwrite numeric keys (reindex keys).
	 *
	 *   @param
	 *      array array1:	first array
	 *      array array2:	second array
	 *
	 *   @return
	 *      array merge:	merged result
	 *
	 *   @origin
	 *      http://de3.php.net/manual/en/function.array-merge-recursive.php#106985
	 */
	function mergeRecursive($array1, $array2) {
 
		$arrays = array($array1, $array2);
		$base = array_shift($arrays);
 
		foreach ($arrays as $array) {
			reset($base); //important
			while (list($key, $value) = @each($array)) {
				if (is_array($value) && @is_array($base[$key])) {
					$base[$key] = $this->mergeRecursive($base[$key], $value);
				} else {
					$base[$key] = $value;
				}
			}
		}
 
		return $base;
 
	}
 
	/**********
	 * feaze()
	 **********
	 *   Makes an 1-dimensional array to an more-dimensional array.
	 *   You can give a value for fill the last deepest array with this content.
	 *
	 *   @param
	 *      array path:	path to feaze (one dimensional array)
	 *      mixed value:	insert a value in last item
	 *
	 *   @return
	 *      array feaze:	more-dimensional array
	 *
	 *   @example
	 *      sending an 1-dimensional array path like
	 *         [key1]
	 *         [key2]
	 *         [key3]
	 *      and value
	 *         value = "test"
	 *      makes a more-dimensional array like
	 *         [key1]
	 *            [key2]
	 *               [key3] = test
	 */
	function feaze($path, $value="") {
 
		$result = array();
 
		if($path[0]==="") $path = array_splice($path, 1);
 
		if(isset($path[0]) && isset($path[1])) {
 
			$parameter = array_splice($path, 1);
			$result[$path[0]] = $this->feaze($parameter, $value);
 
		}
		else if(isset($path[0])) {
			$result[$path[0]] = $value;
		}
 
		return $result;
 
	}
 
	/*****************
	 * getDimension()
	 *****************
	 *   gets the dimension of an array
	 *
	 *   @param
	 *      array array:	array to get the dimension
	 *
	 *   @return
	 *      int dimension:	number of dimension
	 *
	 *   @example
	 *      The array
	 *         [var1]
	 *            [var2]
	 *               [var3]
	 *      has the dimension of 3.
	 */
	function getDimension($array) {
 
		$dimension = 0;
 
		if(is_Array($array)) {
			$dimension++;
			foreach($array as $key => $value) {
 
				if(is_Array($array[$key])) {
					$dimension = $this->getDimension($array[$key])+$dimension;
				}
				$this->getDimension($array[$key])+$dimension;
			}
		}
		return $dimension;
 
	}
 
	/***********************
	 * containsOnlyArrays()
	 ***********************
	 *   check whether there are only arrays in a array.
	 *
	 *   @param
	 *      array array:	array to check
	 *
	 *   @return
	 *      boolean: are there only arrays
	 */
	function containsOnlyArrays($array) {
 
		foreach($array as &$value) {
 
			if(!is_Array($value)) return false;
		}
 
		return true;
	}
 
	/**********
	 * clean()
	 **********
	 *   cleans an array: deletes the values but let the keys.
	 *   All ints are 0, all booleans are false, all strings are ""
	 *
	 *   @param
	 *      array array:	array to clean
	 *
	 *   @return
	 *	array array:	cleaned array
	 *
	 *   @example
	 *      If the posted array is
	 *         [number1] = "hey"
	 *         [intintint] = 123
	 *      the returned array will be
	 *         [number1] = ""
	 *         [intintint] = 0
	 */
	function clean($array) {
 
		foreach($array as &$value) {
 
			if(is_Array($value)) $value = $this->clean($value);
			else if(is_Int($value)) $value = 0;
			else if(is_Bool($value)) $value = false;
			else $value = "";
		}
		return $array;
 
	}
 
	/*******************
	 * ksortRecursive()
	 *******************
	 *   sorts the keys of an array recursivly
	 *
	 *   @param
	 *      array	array to sort
	 */
	function ksortRecursive(&$array) {
 
		ksort($array);
		foreach($array as &$value) {
 
			if(is_Array($value) && !empty($value)) $this->ksortRecursive($value);
		}
 
		return $array;
 
	}
 
	/**************
	 * searchKey()
	 **************
	 *   search a array key recursive, returns true or false
	 *
	 *   @param
	 *      string needle:	string to search
	 *	array array:	array where to search
	 *
	 *   @return
	 *	boolean:	string found/not found as array key
	 */
	function searchKey($needle, $array) {
 
		$result = array_key_exists($needle, $array);
		if($result) return true;
 
		foreach($array as &$value) {
 
			if(is_Array($value)) $result = $this->searchKey($needle, $value);
			if($result) return true;
		}
 
		return false;
 
	}
 
 
 
	/**************
	* printArray()
	 **************
	*   prints an array
	*   
	* @param:
	*   array array:	array to print
	*   int mode:		mode to print
	*      0 - print_r
	*      1 - var_dump
	*/
 
	function printArray($array, $mode=0) {
		echo "<pre>";
		if($mode==0) print_r($array);
		else if($mode==1) var_dump($array);
		echo "</pre>";
	}
 
}
 
?>
