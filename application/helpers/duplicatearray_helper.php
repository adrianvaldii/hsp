<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// function has_dupes($array) {
//   // $dupe_array = array();
//   // foreach($array as $val){
//   //   if(++$dupe_array[$val] > 1){
//   //     return true;
//   //   }
//   // }
//   // return false;
// }

function arrayContainsDuplicate($array)  
{  
      return count($array) != count(array_unique($array));    
}

?>