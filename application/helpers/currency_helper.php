<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// string number_format(float $number, int $decimals = 0, string $dec_point = '.', string $thousands_sep = ',' );

function currency ($N_MONEY)
{
	$result = number_format($N_MONEY, '.');
	return $result;
}
