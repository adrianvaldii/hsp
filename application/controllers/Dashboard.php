<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
	function __construct()
	{
		parent::__construct();
		$nik = $this->input->get('Nik');
		$sess_nik['nik'] = $nik;
		$this->session->set_userdata($sess_nik);
		$this->nik = $this->session->userdata('nik');
		$this->load->model('M_dashboard');

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
	}

	public function index()
	{
		// $this->nik = $this->session->userdata('nik');
		// $data_customers = $this->M_dashboard->get_customer_month()->result();

		// foreach ($data_customers as $key => $value) {
		// 	$temp_data['y'] = $value->TOTAL_CUSTOMER;
		// 	$temp_data['legendText'] = $value->CUSTOMER_NAME;
		// 	$temp_data['label'] = $value->CUSTOMER_NAME;

		// 	$result_customer[] = $temp_data;
		// }

		// $data['data_customers'] = $result_customer;

		// $data_wo = $this->M_dashboard->get_wo_per_year()->result();

		// foreach ($data_wo as $key => $value) {
		// 	if ($value->BULAN == '1') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "January";
		// 		$temp_data['label'] = "January";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '2') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "February";
		// 		$temp_data['label'] = "February";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '3') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "March";
		// 		$temp_data['label'] = "March";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '4') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "April";
		// 		$temp_data['label'] = "April";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '5') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "May";
		// 		$temp_data['label'] = "May";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '6') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "June";
		// 		$temp_data['label'] = "June";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '7') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "July";
		// 		$temp_data['label'] = "July";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '8') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "August";
		// 		$temp_data['label'] = "August";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '9') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "September";
		// 		$temp_data['label'] = "September";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '10') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "October";
		// 		$temp_data['label'] = "October";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '11') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "November";
		// 		$temp_data['label'] = "November";

		// 		$result_wo[] = $temp_data;
		// 	}

		// 	if ($value->BULAN == '12') {
		// 		$temp_data['y'] = $value->TOTAL_WO;
		// 		$temp_data['legendText'] = "December";
		// 		$temp_data['label'] = "December";

		// 		$result_wo[] = $temp_data;
		// 	}
		// }

		// $data['data_wo'] = $result_wo;

		// $this->load->view('admin/v_home', $data);
		$this->load->view('admin/v_home');
	}

	public function logout() {
		$this->session->unset_userdata('nik');
		session_destroy();

		echo '<script>window.open(\'\', \'_self\', \'\')</script>';
		echo '<script>window.close()</script>';
	}
}
