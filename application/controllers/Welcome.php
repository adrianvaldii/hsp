<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		$this->load->model('M_welcome');
		// if ($this->session->userdata('nik')!="") {
		// 	redirect('Dashboard/index');
		// }

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
	}

	public function index()
	{
		// set validation
		$this->form_validation->set_rules('nik', 'NIK', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// input and check validation
		 // check for validation
        if ($this->form_validation->run() == FALSE) {
           $this->load->view('login/v_login');
        } else {
        	// set varible from post
			$nik = $this->input->post('nik');
			$password = $this->input->post('password');

        	// check nik
        	$check_nik = $this->M_welcome->check_nik($nik)->num_rows();

        	if ($check_nik < '1') {
        		$data['nik_error'] = "error";
        		$this->load->view('login/v_login', $data);
        	} else {
        		$company_code = $this->M_welcome->get_company_code($nik)->row()->cmpy_code;
	        	$aplikasi_code = "HSP";

	        	$result_login = $this->M_welcome->check_login($nik, $password, $company_code, $aplikasi_code)->result_array();

	        	if (array_key_exists('hasil', $result_login[0]) && $result_login[0]['hasil'] == 1) {
	        		$data['login_error'] = "error";
					$this->session->set_flashdata('login_error', $result_login[0]['informasi']);	        		
        			$this->load->view('login/v_login', $data);
	        	} elseif (array_key_exists('Nik', $result_login[0])) {
	        		$check_nik_appl = $this->M_welcome->check_nik_appl($result_login[0]['Nik'])->num_rows();

		    		if ($check_nik_appl == 0) {
		    			$data['not_akses'] = "error";
        				$this->load->view('login/v_login', $data);
		    		} else {
		    			$sess_nik['nik'] = $result_login[0]['Nik'];
		    			$this->session->set_userdata($sess_nik);
		    			$this->session->set_flashdata('success', 'Login Success!');
		        		redirect('Dashboard/index');
		    		}
	        	}
        	}
        }

	}

	public function Auth()
	{

	}

	public function Auth2()
	{
		// $data = array('username' => $this->input->post('username', TRUE),
		// 				'password' => md5($this->input->post('password', TRUE))
		// 	);
		// $this->load->model('model_user'); // load model_user
		// $hasil = $this->model_user->cek_user($data);
		// if ($hasil->num_rows() == 1) {
		// 	foreach ($hasil->result() as $sess) {
		// 		$sess_data['logged_in'] = 'Sudah Loggin';
		// 		$sess_data['uid'] = $sess->uid;
		// 		$sess_data['username'] = $sess->username;
		// 		$sess_data['level'] = $sess->level;
		// 		$this->session->set_userdata($sess_data);
		// 	}
		// 	if ($this->session->userdata('level')=='admin') {
		// 		redirect('admin/c_admin');
		// 	}
		// 	elseif ($this->session->userdata('level')=='member') {
		// 		redirect('member/c_member');
		// 	}		
		// }
		// else {
		// 	echo "<script>alert('Gagal login: Cek username, password!');history.go(-1);</script>";
		// }
	}

	public function dashboard()
	{
		$this->load->view('admin/v_home');
	}

	public function test()
	{
		$result = $this->M_welcome->test()->result_array();

		if (array_key_exists('hasil', $result[0]) && $result[0]['hasil'] == 1) {
			echo "akwdmawdmaw";
    	} elseif (array_key_exists('Nik', $result[0])) {
    		$check_nik_appl = $this->M_welcome->check_nik_appl($result[0]['Nik'])->num_rows();

   //  		$this->load->helper("comman_helper");
			// pr($check_nik_appl);

    		if ($check_nik_appl == 0) {
    			echo "User tidak memiliki akses aplikasi";
    		} else {
    			echo "User memiliki akses aplikasi";
    		}
    	}

		// $this->load->helper("comman_helper");
		// pr($result);

		// if (empty($result)) {
		// 	echo "login gagal";
		// } else {
		// 	echo "login berhasil";
		// 	echo "<br>";
		// 	echo "hai " . $result[0]['Nik'];
		// }

	}
}
