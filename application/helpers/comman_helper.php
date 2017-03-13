<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function pr($myArray = array(), $terminate = true) 
{
	echo "<pre>";
	print_r($myArray);
	if($terminate) {
		die;
	}

	echo "</pre>";
}
?>