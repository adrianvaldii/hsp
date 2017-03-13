<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function escape($sql) { 

    $fix_str        = stripslashes($sql); 
    $fix_str    = str_replace("'","''",$sql); 
    $fix_str     = str_replace("\0","[NULL]",$fix_str); 

    return $fix_str;
}

?>