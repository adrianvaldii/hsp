<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

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

		$this->load->model('M_admin');
		$this->nik = $this->session->userdata('nik');

		// pr($this->nik);

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
	}

	function entry_menu_access()
	{
		$this->load->helper('comman_helper');
		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('nik', 'NIK', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
           	$this->load->view('admin/v_entrymenu');
        } else {
        	$nik = $this->input->post('nik');
	        $data_menu = $this->M_admin->get_menu()->result();
        	
        	$this->db->trans_begin();
        	try {
	        	foreach ($data_menu as $key => $value) {
	        		$data_insert[] = array(
	        			'nik' => $nik,
	        			'kd_menu' => $value->ID_MENU,
	        			'user_id' => $this->nik,
	        			'user_date' => $date
	        		);
	        	}

	        	if (!$this->db->insert_batch('dbo.MMENU_AKSES', $data_insert)) {
	        		throw new Exception("Error Processing Request to Entry Access Menu", 1);
	        	}

	        	$data_80 = array(
	        		'kd_menu' => '80'
	        	);

	        	$data_81 = array(
	        		'kd_menu' => '81'
	        	);

	        	$delete_80 = $this->M_admin->delete_menu('dbo.MMENU_AKSES', $nik, $data_80);
	        	if ($delete_80 == FALSE) {
	        		throw new Exception("Error Processing Request to Delete Menu 80", 1);
	        	}

	        	$delete_81 = $this->M_admin->delete_menu('dbo.MMENU_AKSES', $nik, $data_81);
	        	if ($delete_81 == FALSE) {
	        		throw new Exception("Error Processing Request to Delete Menu 81", 1);
	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Entry Access Menu", 1);
	        	} else {
	        		$this->session->set_flashdata('success_entry_menu', "Successfully Entry Access Menu!");
					$this->db->trans_commit();
					redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_entry_menu', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
        	}
        }
	}
}
