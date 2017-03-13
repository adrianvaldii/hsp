<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_data');
		$this->load->helper('url');
	}

	function index() {
		$data['company'] = $this->m_data->tampil_data()->result();

		$this->load->view('v_tampil', $data);
	}


}