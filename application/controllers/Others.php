<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Others extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	private $nik;
	function __construct()
	{
		parent::__construct();
		// if ($this->session->userdata('nik')=="") {
		// 	redirect('Welcome/index');
		// }
		$this->load->helper('comman_helper');

		// $this->load->model('M_admin');
		$this->nik = $this->session->userdata('nik');

		// pr($this->nik);

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
	}

	function faq()
	{
		$this->load->helper('comman_helper');
		$date = date('Y-m-d H:i:s');

		$this->load->view('others/v_faq');
	}
}
