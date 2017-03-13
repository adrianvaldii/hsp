<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Yudha Pratama
 * @license			None
 * @link			https://github.com/shinryu99/ci-mpdf
 */

require_once(dirname(__FILE__) . '/mpdf60/mpdf.php');

class pdf extends MPDF
{
	function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once(dirname(__FILE__) . '/mpdf60/mpdf.php');
         
        if ($params == NULL)
        {
            $param = '"utf-8","A4","","",0,0,0,0,0,0';         
        }
         
        return new mPDF($param);
    }
}
