<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

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
		// if ($this->session->userdata('nik')=="") {
		// 	redirect('Welcome/index');
		// }
		$this->load->model('M_service');

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('url');
	}

	// function for view all service
	public function index()
	{
		$data['services'] = $this->M_service->get_service()->result();

		$this->load->view('services/v_homeservice', $data);	
	}

	// function for view all service
	public function index_edit()
	{
		$data['services'] = $this->M_service->get_service()->result();

		$this->load->view('services/v_homeeditservice', $data);	
	}

	// function for add new service
	public function new_service()
	{
		$temp_id = $this->M_service->get_max_serviceid();
		$s_huruf_id = substr($temp_id->SELLING_SERVICE_ID, 0, 2);
		$n_angka_id = substr($temp_id->SELLING_SERVICE_ID, 2, 2)+1;
		
		 if($n_angka_id<10) {
		 	$kode='SS0'.$n_angka_id;
		
		 } elseif($n_angka_id > 9 && $n_angka_id <=99) {
		 	$kode='SS'.$n_angka_id;
		 }

		$data['service_id'] = $kode;
		$data['general'] = $this->M_service->get_mgeneralid()->result();
		$data['service_type'] = $this->M_service->get_type_service()->result();


		// set validation data
		$this->form_validation->set_rules('SELLING_SERVICE_ID', 'Selling Service ID', 'required');
		$this->form_validation->set_rules('SERVICE_NAME', 'Service Name', 'required');
		$this->form_validation->set_rules('SERVICE_DESCRIPTION', 'Service Description', 'required');
		$this->form_validation->set_rules('SELL_UOM_ID', 'Selling Unit Measurement', 'required');

		// get data from user post
		$S_SELLING_SERVICE_ID = $this->input->post('SELLING_SERVICE_ID');
		$S_SERVICE_NAME = $this->input->post('SERVICE_NAME');
		$S_SERVICE_DESCRIPTION = $this->input->post('SERVICE_DESCRIPTION');
		$S_SELL_UOM_ID = $this->input->post('SELL_UOM_ID');

		$check_data_service = $this->M_service->check_data_service($S_SERVICE_NAME);
		
		if ($this->form_validation->run() == false) {
			$this->load->view('services/v_newservice', $data);
		} elseif ($check_data_service > 0) {
        	$this->session->set_flashdata('data_exist', 'Data Service Already Exists!');
        	redirect('Service/new_service');
        } else {
        	$service_type = $this->input->post('service_type');
        	$flag = $this->input->post('flag');
			$this->db->trans_begin();
			try {
				// set all data to variable
				$data = array(
					'SELLING_SERVICE_ID' => $S_SELLING_SERVICE_ID,
					'SERVICE_NAME' => $S_SERVICE_NAME,
					'SERVICE_DESCRIPTION' => $S_SERVICE_DESCRIPTION,
					'SELL_UOM_ID' => $S_SELL_UOM_ID,
					'service_type' => $service_type,
					'flag' => $flag
					);

				// input data to database
				if (!$this->db->insert('dbo.MSELLING_SERVICE', $data)) {
					throw new Exception("Error Processing Request to Entry Selling Service", 1);
				}

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request to Entry Selling Service.", 1);
				} else {
					$this->session->set_flashdata('success_entry_service', 'Successfully Entry Selling Service!');
					$this->db->trans_commit();
					redirect('Service/new_service');
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_entry_service', $e->getMessage());
				$this->db->trans_rollback();
				redirect('Service/new_service');
			}
		}
	}

	public function edit($id)
	{
		$this->load->helper('comman_helper');
		$where = array('SELLING_SERVICE_ID' => $id);
		$data['services'] = $this->M_service->edit_data($where,'dbo.MSELLING_SERVICE')->result();
		$data['general'] = $this->M_service->get_mgeneralid()->result();

		$service_id = $this->uri->segment(3);

		$data['service_id'] = $service_id;
		$data['service_name'] = $this->M_service->get_service_param($service_id)->row()->SERVICE_NAME;
		$data['service_description'] = $this->M_service->get_service_param($service_id)->row()->SERVICE_DESCRIPTION;
		$data['sell_uom_id'] = $this->M_service->get_service_param($service_id)->row()->SELL_UOM_ID;
		$data['service_type'] = $this->M_service->get_service_param($service_id)->row()->SERVICE_TYPE;
		$data['flag'] = $this->M_service->get_service_param($service_id)->row()->FLAG;

		// set validation data
		$this->form_validation->set_rules('SELLING_SERVICE_ID', 'Selling Service ID', 'required');
		$this->form_validation->set_rules('SERVICE_NAME', 'Service Name', 'required');
		$this->form_validation->set_rules('SERVICE_DESCRIPTION', 'Service Description', 'required');
		$this->form_validation->set_rules('SELL_UOM_ID', 'Selling Unit Measurement', 'required');

		// get data from user post
		$S_SELLING_SERVICE_ID = $this->input->post('SELLING_SERVICE_ID');
		$S_SERVICE_NAME = $this->input->post('SERVICE_NAME');
		$S_SERVICE_DESCRIPTION = $this->input->post('SERVICE_DESCRIPTION');
		$S_SELL_UOM_ID = $this->input->post('SELL_UOM_ID');

		$data['service_types'] = $this->M_service->get_type_service()->result();

		if ($this->form_validation->run() == false) {
			$this->load->view('services/v_editservice', $data);
		} else {
			$service_type = $this->input->post('service_type');
			$flag = $this->input->post('flag');
			$this->db->trans_begin();
			try {
				// set all data to variable
				$data = array(
					'SERVICE_NAME' => $S_SERVICE_NAME,
					'SERVICE_DESCRIPTION' => $S_SERVICE_DESCRIPTION,
					'SELL_UOM_ID' => $S_SELL_UOM_ID,
					'service_type' => $service_type,
					'flag' => $flag
					);

				// update data to database
				$where = array('SELLING_SERVICE_ID' => $S_SELLING_SERVICE_ID);
				$update_data = $this->M_service->update_data($where, $data, 'dbo.MSELLING_SERVICE');
				if ($update_data == FALSE) {
					throw new Exception("Error Processing Request to Updated Data Selling Service", 1);
				}

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request to Updated Data Selling Service", 1);
				} else {
					$this->session->set_flashdata('success_edit_service', 'Successfully updated data selling service!');
					$this->db->trans_commit();
					redirect(current_url());
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_edit_service', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
			}
		}
	}

	// function view all company service
	public function company_service()
	{
		// $this->load->helper('comman_helper');
		$data['company_services'] = $this->M_service->get_companyservice()->result();

		// pr($data);
		$this->load->view('services/v_companyservice', $data);
	}

	// function add company_service
	public function view_company_service()
	{
		$data['company'] = $this->M_service->get_company_service_detail()->result();
		$data['service'] = $this->M_service->get_service_detail()->result();

		$this->form_validation->set_rules('COMPANY_SERVICE_ID', 'Company', 'required');
		$this->form_validation->set_rules('SELLING_SERVICE_ID', 'Selling Service', 'required');

		// get data from user post
		$S_SELLING_SERVICE_ID = $this->input->post('SELLING_SERVICE_ID');
		$S_COMPANY_SERVICE_ID = $this->input->post('COMPANY_SERVICE_ID');

		$check_data = $this->M_service->check_data_companyservice($S_SELLING_SERVICE_ID, $S_COMPANY_SERVICE_ID);

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('services/v_addcompanyservice', $data);
        } elseif ($check_data > 0) {
        	$this->session->set_flashdata('data_exist', 'Data Already Exists!');
        	redirect('Service/view_company_service');
        } else{
        	$this->db->trans_begin();
        	try {
        		$data = array(
					'SELLING_SERVICE_ID' => $S_SELLING_SERVICE_ID,
					'COMPANY_SERVICE_ID' => $S_COMPANY_SERVICE_ID
				);
	        	
	        	if (!$this->db->insert('dbo.MSELLING_SERVICE_DETAIL', $data)) {
	        		throw new Exception("Error Processing Request to Entry Company Service", 1);
	        	}

	            if ($this->db->trans_status() === FALSE) {
	            	throw new Exception("Error Processing Request to Entry Company Service.", 1);
	            } else {
	            	$this->session->set_flashdata('success_entry_company_service', 'Data Submitted Successfully');
	            	$this->db->trans_commit();
	            	redirect('Service/company_service');
	            }
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_entry_company_service', $e->getMessage());
            	$this->db->trans_rollback();
            	redirect('Service/company_service');
        	}
        }
	}

}
