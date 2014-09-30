ArrayHandler
============

This class handles and manage array, and make a few operations with them.

Array handling in PHP is not that easy than you need it. This class helps to optimize this array handling. All functions are static to use them without making an object, so you can use ArrayHandler::function().

* mergeRecursive()
   This function will merge 2 arrays to 1 (without reindex all indizes).

* feaze()
   Expands an 1-dimensional array to a more-dimensional array.

* getDimension()
   Gets the dimension of an array.

* containsOnlyArrays()
   Check if there are only arrays (no strings/ints/something like that) in the array.

* clean()
   clean array, so there is only the structure without values

* ksortRecursive()
   sorts the keys in right order recursive

* searchKey()
   search an array key recursive

* printArray()
   prints an array with some possible options
