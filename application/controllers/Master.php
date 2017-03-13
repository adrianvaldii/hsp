<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

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

	// global variable
	private $nik;

	function __construct()
	{
		parent::__construct();
		// if ($this->session->userdata('nik')=="") {
		// 	redirect('Welcome/index');
		// }
		$this->nik = $this->session->userdata('nik');
		$this->load->model('M_master');

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
	}

	public function index()
	{
		$data['general_id'] = $this->M_master->get_generalid()->result();

		$this->load->view('master/v_homegeneralid', $data);
	}

	public function add_generalid()
	{
		$data['classification'] = $this->M_master->get_classification()->result();
		// $this->load->view('master/v_homegeneralid', $data);
		// set validation rules
		 $this->form_validation->set_rules('general_id', 'General ID', 'required');
		 $this->form_validation->set_rules('classification_id', 'Classification', 'required');
		 $this->form_validation->set_rules('general_description', 'Description', 'required');
		 $this->form_validation->set_rules('general_description_short', 'Short Description', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('master/v_addgeneralid', $data);
        }else{
        	$this->db->trans_begin();
        	try {
        		$data = array(
	        		'general_id' => $this->input->post('general_id'),
	        		'classification_id' => $this->input->post('classification_id'),
	        		'general_description' => $this->input->post('general_description'),
	        		'general_description_short' => $this->input->post('general_description_short')
	        	);
	        	if (!$this->db->insert('dbo.MGENERAL_ID', $data)) {
	        		throw new Exception("Error Processing Request to Entry General ID", 1);
	        	}

	            if ($this->db->trans_status() === FALSE) {
	            	throw new Exception("Error Processing Request to Entry General ID", 1);
	            } else {
	            	$this->session->set_flashdata('success_general_id', 'General ID Submitted Successfully');
	            	$this->db->trans_commit();
	            	redirect('Master/index');
	            }
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_general_id', $e->getMessage());
            	$this->db->trans_rollback();
            	redirect('Master/index');
        	}
        }
	}

	public function floor_market_trucking()
	{
		$data['data_trucking'] = $this->M_master->get_trucking()->result();
		$data['data_selling_trucking'] = $this->M_master->get_selling_trucking()->result();

		// $this->load->helper('comman_helper');
		// pr($data['data_selling_trucking']);

		if (!$data['data_trucking']) {
			$result_trucking = array();
		} else {
			foreach ($data['data_trucking'] as $key => $value) {
				$temp_data['COMPANY_ID'] = $value->COMPANY_ID;
				$temp_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$temp_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$temp_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				$temp_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
				$temp_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$temp_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$temp_data['FROM_NAME'] = $value->FROM_NAME;
				$temp_data['TO_NAME'] = $value->TO_NAME;
				$temp_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$temp_data['START_DATE'] = $value->START_DATE;
				$temp_data['FLOOR_PRICE'] = $value->FLOOR_PRICE;
				$temp_data['MARKET_PRICE'] = $value->MARKET_PRICE;

				foreach ($data['data_selling_trucking'] as $key1 => $value1) {
					if ($value1->COMPANY_ID == $value->COMPANY_ID && $value1->FROM_LOCATION_ID == $value->FROM_LOCATION_ID && $value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == $value->CONTAINER_SIZE_ID && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID) {
						$temp_data['TARIFF_AMOUNT'] = $value1->TARIFF_AMOUNT;
					}
				}

				$result_trucking[] = $temp_data;
			}
		}

		// $this->load->helper('comman_helper');
		// pr($result_trucking);
		$data['result_trucking'] = $result_trucking;

		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('company_id[]', 'Company Service', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('master/v_floormarkettrucking', $data);
		} else {
			$this->db->trans_begin();
			try {
				// declare variable
				$company_id = $this->input->post('company_id');
				$selling_service = $this->input->post('selling_service');
				$from_location = $this->input->post('from_location');
				$to_location = $this->input->post('to_location');
				$container_size = $this->input->post('container_size');
				$container_type = $this->input->post('container_type');
				$container_category = $this->input->post('container_category');
				$start_date = $this->input->post('start_date');
				$currency = $this->input->post('currency');
				$floor_price = $this->input->post('floor_price');
				$market_price = $this->input->post('market_price');

				// proses input
				for ($i=0; $i < count($company_id); $i++) { 
					$currency_data = $this->M_master->check_update_data($company_id[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->TARIFF_CURRENCY;
					$floor_price_data = $this->M_master->check_update_data($company_id[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->FLOOR_PRICE;
					$market_price_data = $this->M_master->check_update_data($company_id[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->MARKET_PRICE;
					$start_date_data = $this->M_master->check_update_data($company_id[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->START_DATE;

					if ($market_price_data != $market_price[$i] || $floor_price_data != $floor_price[$i] || $currency_data != $currency[$i] || $start_date_data != $start_date[$i]) {
						// update data limitation
						$data_limitation = array(
							'company_id' => $company_id[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$data_history_limitation = array(
							'company_id' => $company_id[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date_data,
							'floor_price' => $floor_price_data,
							'market_price' => $market_price_data,
							'tariff_currency' => $currency_data,
							'user_id' => $this->nik,
							'user_date' => $date
						);

						$update_limit = $this->M_master->update_limit($company_id[$i], $selling_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i],'dbo.MLIMITATIONS_PRICE_CONTAINER_ATTRIBUTE', $data_limitation);
						if ($update_limit == FALSE) {
							throw new Exception("Error Processing Request to Update Floor Price Trucking", 1);
						}

						if (!$this->db->insert('dbo.HILIMITATIONS_PRICE_CONTAINER_ATTRIBUTE', $data_history_limitation)) {
							throw new Exception("Error Processing Request to Inserted Floor Price Trucking", 1);
						}

						unset($data_limitation);
						unset($data_history_limitation);
					} elseif ($market_price_data == $market_price[$i] && $floor_price_data == $floor_price[$i] && $currency_data == $currency[$i] && $start_date_data == $start_date[$i]) {
						$data_limitation = array(
							'company_id' => $company_id[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$update_limit = $this->M_master->update_limit($company_id[$i], $selling_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i],'dbo.MLIMITATIONS_PRICE_CONTAINER_ATTRIBUTE', $data_limitation);
						if ($update_limit == FALSE) {
							throw new Exception("Error Processing Request to Update Floor Price Trucking", 1);
						}
						unset($data_limitation);
					}
				}

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request to Entry Floor Market Trucking", 1);
				} else {
					$this->session->set_flashdata('success_floor_trucking', "Successfully update Floor Price or Marketplace Price!");
					$this->db->trans_commit();
					redirect(current_url());
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_floor_trucking', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
			}
		}
	}

	public function floor_market_customs()
	{
		$data['data_customs'] = $this->M_master->get_customs()->result();
		$date = date('Y-m-d H:i:s');

		$data['data_selling_customs'] = $this->M_master->get_selling_customs()->result();

		// $this->load->helper('comman_helper');
		// pr($data['data_selling_customs']);

		if (!$data['data_customs']) {
			$result_customs = array();
		} else {
			foreach ($data['data_customs'] as $key => $value) {
				$temp_data['COMPANY_ID'] = $value->COMPANY_ID;
				$temp_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$temp_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$temp_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$temp_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$temp_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
				$temp_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$temp_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$temp_data['FROM_NAME'] = $value->FROM_NAME;
				$temp_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$temp_data['START_DATE'] = $value->START_DATE;
				$temp_data['FLOOR_PRICE'] = $value->FLOOR_PRICE;
				$temp_data['MARKET_PRICE'] = $value->MARKET_PRICE;

				foreach ($data['data_selling_customs'] as $key1 => $value1) {
					if ($value1->COMPANY_ID == $value->COMPANY_ID && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CONTAINER_SIZE_ID == $value->CONTAINER_SIZE_ID && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID) {
						$temp_data['TARIFF_AMOUNT'] = $value1->TARIFF_AMOUNT;
					}
				}

				$result_customs[] = $temp_data;
			}
		}

		// $this->load->helper('comman_helper');
		// pr($result_customs);

		$data['result_customs'] = $result_customs;

		$this->form_validation->set_rules('company_id[]', 'Company Service', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('master/v_floormarketcustoms', $data);
		} else {
			$this->db->trans_begin();
			try {
				// declare variable
				$company_id = $this->input->post('company_id');
				$selling_service = $this->input->post('selling_service');
				$custom_location = $this->input->post('customs_location');
				$custom_line = $this->input->post('customs_line');
				$custom_kind = $this->input->post('customs_kind');
				$container_size = $this->input->post('container_size');
				$container_type = $this->input->post('container_type');
				$container_category = $this->input->post('container_category');
				$start_date = $this->input->post('start_date');
				$currency = $this->input->post('currency');
				$floor_price = $this->input->post('floor_price');
				$market_price = $this->input->post('market_price');

				// proses input
				for ($i=0; $i < count($company_id); $i++) { 
					$currency_data = $this->M_master->check_update_data_customs($company_id[$i], $custom_location[$i], $custom_line[$i], $custom_kind[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->TARIFF_CURRENCY;
					$floor_price_data = $this->M_master->check_update_data_customs($company_id[$i], $custom_location[$i], $custom_line[$i], $custom_kind[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->FLOOR_PRICE;
					$market_price_data = $this->M_master->check_update_data_customs($company_id[$i], $custom_location[$i], $custom_line[$i], $custom_kind[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->MARKET_PRICE;
					$start_date_data = $this->M_master->check_update_data_customs($company_id[$i], $custom_location[$i], $custom_line[$i], $custom_kind[$i], $container_size[$i], $container_type[$i], $container_category[$i])->row()->START_DATE;

					if ($market_price_data != $market_price[$i] || $floor_price_data != $floor_price[$i] || $currency_data != $currency[$i] || $start_date_data != $start_date[$i]) {
						// update data limitation
						$data_limitation = array(
							'company_id' => $company_id[$i],
							'selling_service_id' => $selling_service[$i],
							'custom_location_id' => $custom_location[$i],
							'custom_line_id' => $custom_line[$i],
							'custom_kind_id' => $custom_kind[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$data_history_limitation = array(
							'company_id' => $company_id[$i],
							'selling_service_id' => $selling_service[$i],
							'custom_location_id' => $custom_location[$i],
							'custom_line_id' => $custom_line[$i],
							'custom_kind_id' => $custom_kind[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date_data,
							'floor_price' => $floor_price_data,
							'market_price' => $market_price_data,
							'tariff_currency' => $currency_data,
							'user_id' => $this->nik,
							'user_date' => $date
						);

						$update_limit_customs = $this->M_master->update_limit_customs($company_id[$i], $selling_service[$i], $custom_location[$i], $custom_line[$i], $custom_kind[$i], $container_size[$i], $container_type[$i], $container_category[$i],'dbo.MLIMITATIONS_PRICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_limitation);
						if ($update_limit_customs == FALSE) {
							throw new Exception("Error Processing Request to Update Floor Price Customs Clearance", 1);
						}
						if (!$this->db->insert('dbo.HILIMITATIONS_PRICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_history_limitation)) {
							throw new Exception("Error Processing Request to Entry Floor Price Customs Clearance", 1);
						}

						unset($data_limitation);
						unset($data_history_limitation);
					} elseif ($market_price_data == $market_price[$i] && $floor_price_data == $floor_price[$i] && $currency_data == $currency[$i] && $start_date_data == $start_date[$i]) {
						$data_limitation = array(
							'company_id' => $company_id[$i],
							'selling_service_id' => $selling_service[$i],
							'custom_location_id' => $custom_location[$i],
							'custom_line_id' => $custom_line[$i],
							'custom_kind_id' => $custom_kind[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$update_limit_customs = $this->M_master->update_limit_customs($company_id[$i], $selling_service[$i], $custom_location[$i], $custom_line[$i], $custom_kind[$i], $container_size[$i], $container_type[$i], $container_category[$i],'dbo.MLIMITATIONS_PRICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_limitation);
						if ($update_limit_customs == FALSE) {
							throw new Exception("Error Processing Request to Update Floor Price Customs Clearance", 1);
						}
						unset($data_limitation);
					}
				}

				if ($this->db->trans_status() == FALSE) {
					throw new Exception("Error Processing Request to Entry Floor Price Customs Clearance", 1);
				} else {
					$this->session->set_flashdata('success_floor_customs', "Successfully update Floor Price or Marketplace Price!");
					$this->db->trans_commit();
					redirect(current_url());
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_floor_customs', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
			}
		}
	}

	public function floor_market_location()
	{
		$data['data_location'] = $this->M_master->get_location()->result();
		$date = date('Y-m-d H:i:s');

		$data['data_selling_location'] = $this->M_master->get_selling_location()->result();

		// $this->load->helper('comman_helper');
		// pr($data['data_location']);

		if (!$data['data_location']) {
			$result_location = array();
		} else {
			foreach ($data['data_location'] as $key => $value) {
				$temp_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
				$temp_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$temp_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$temp_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				$temp_data['FROM_NAME'] = $value->FROM_NAME;
				$temp_data['TO_NAME'] = $value->TO_NAME;
				$temp_data['TRUCK_ID'] = $value->TRUCK_ID;;
				$temp_data['TRUCK_NAME'] = $value->TRUCK_NAME;
				$temp_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$temp_data['START_DATE'] = $value->START_DATE;
				$temp_data['FLOOR_PRICE'] = $value->FLOOR_PRICE;
				$temp_data['MARKET_PRICE'] = $value->MARKET_PRICE;

				foreach ($data['data_selling_location'] as $key1 => $value1) {
					if ($value1->COMPANY_SERVICE_ID == $value->COMPANY_SERVICE_ID && $value1->FROM_LOCATION_ID == $value->FROM_LOCATION_ID && $value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->TRUCK_ID == $value->TRUCK_ID) {
						$temp_data['TARIFF_AMOUNT'] = $value1->TARIFF_AMOUNT;
					}
				}

				$result_location[] = $temp_data;
			}
		}

		// $this->load->helper('comman_helper');
		// pr($result_location);
		$data['result_location'] = $result_location;

		$this->form_validation->set_rules('company_service[]', 'Company Service', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('master/v_floormarketlocation', $data);
		} else {
			$this->db->trans_begin();
			try {
				// declare variable
				$company_service = $this->input->post('company_service');
				$selling_service = $this->input->post('selling_service');
				$from_location = $this->input->post('from_location');
				$to_location = $this->input->post('to_location');
				$truck_id = $this->input->post('truck_id');
				$start_date = $this->input->post('start_date');
				$currency = $this->input->post('currency');
				$floor_price = $this->input->post('floor_price');
				$market_price = $this->input->post('market_price');

				// proses input
				for ($i=0; $i < count($company_service); $i++) { 
					$currency_data = $this->M_master->check_update_data_location($company_service[$i], $from_location[$i], $to_location[$i], $truck_id[$i])->row()->TARIFF_CURRENCY;
					$floor_price_data = $this->M_master->check_update_data_location($company_service[$i], $from_location[$i], $to_location[$i], $truck_id[$i])->row()->FLOOR_PRICE;
					$market_price_data = $this->M_master->check_update_data_location($company_service[$i], $from_location[$i], $to_location[$i], $truck_id[$i])->row()->MARKET_PRICE;
					$start_date_data = $this->M_master->check_update_data_location($company_service[$i], $from_location[$i], $to_location[$i], $truck_id[$i])->row()->START_DATE;

					if ($market_price_data != $market_price[$i] || $floor_price_data != $floor_price[$i] || $currency_data != $currency[$i] || $start_date_data != $start_date[$i]) {
						// update data limitation
						$data_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'truck_id' => $truck_id[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$data_history_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'truck_id' => $truck_id[$i],
							'start_date' => $start_date_data,
							'floor_price' => $floor_price_data,
							'market_price' => $market_price_data,
							'tariff_currency' => $currency_data,
							'user_id' => $this->nik,
							'user_date' => $date
						);

						$update_limit_location = $this->M_master->update_limit_location($company_service[$i], $selling_service[$i], $from_location[$i], $to_location[$i], $truck_id[$i],'dbo.MLIMITATIONS_PRICE_LOCATION_ATTRIBUTE', $data_limitation);
						if ($update_limit_location == FALSE) {
							throw new Exception("Error Processing Request to Update Floor Price Location", 1);
						}
						if (!$this->db->insert('dbo.HILIMITATIONS_PRICE_LOCATION_ATTRIBUTE', $data_history_limitation)) {
							throw new Exception("Error Processing Request to Entry Floor Price Location", 1);
						}

						unset($data_limitation);
						unset($data_history_limitation);
					} elseif ($market_price_data == $market_price[$i] && $floor_price_data == $floor_price[$i] && $currency_data == $currency[$i] && $start_date_data == $start_date[$i]) {
						$data_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'truck_id' => $truck_id[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$update_limit_location = $this->M_master->update_limit_location($company_service[$i], $selling_service[$i], $from_location[$i], $to_location[$i], $truck_id[$i],'dbo.MLIMITATIONS_PRICE_LOCATION_ATTRIBUTE', $data_limitation);
						if ($update_limit_location == FALSE) {
							throw new Exception("Error Processing Request to Update Floor Price Location", 1);
						}
						unset($data_limitation);
					}
				}

				if ($this->db->trans_status() == FALSE) {
					throw new Exception("Error Processing Request to Entry Floor Price Location", 1);
				} else {
					$this->session->set_flashdata('success_floor_location', "Successfully update Floor Price or Marketplace Price!");
					$this->db->trans_commit();
					redirect(current_url());
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_floor_location', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
			}
		}
	}

	public function floor_market_weight()
	{
		$data['data_weight'] = $this->M_master->get_weight()->result();
		$date = date('Y-m-d H:i:s');

		$data['data_selling_weight'] = $this->M_master->get_selling_weight()->result();

		// $this->load->helper('comman_helper');
		// pr($data['data_selling_weight']);

		if (!$data['data_weight']) {
			$result_weight = array();
		} else {
			foreach ($data['data_weight'] as $key => $value) {
				$temp_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
				$temp_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$temp_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$temp_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				$temp_data['FROM_NAME'] = $value->FROM_NAME;
				$temp_data['TO_NAME'] = $value->TO_NAME;
				$temp_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$temp_data['START_DATE'] = $value->START_DATE;
				$temp_data['FLOOR_PRICE'] = $value->FLOOR_PRICE;
				$temp_data['MARKET_PRICE'] = $value->MARKET_PRICE;

				foreach ($data['data_selling_weight'] as $key1 => $value1) {
					if ($value1->COMPANY_SERVICE_ID == $value->COMPANY_SERVICE_ID && $value1->FROM_LOCATION_ID == $value->FROM_LOCATION_ID && $value1->TO_LOCATION_ID == $value->TO_LOCATION_ID) {
						$temp_data['TARIFF_AMOUNT'] = $value1->TARIFF_AMOUNT;
					}
				}

				$result_weight[] = $temp_data;
			}
		}

		// $this->load->helper('comman_helper');
		// pr($result_location);
		$data['result_weight'] = $result_weight;

		$this->form_validation->set_rules('company_service[]', 'Company Service', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('master/v_floormarketweight', $data);
		} else {
			$this->db->trans_begin();
			try {
				// declare variable
				$company_service = $this->input->post('company_service');
				$selling_service = $this->input->post('selling_service');
				$from_location = $this->input->post('from_location');
				$to_location = $this->input->post('to_location');
				$start_date = $this->input->post('start_date');
				$currency = $this->input->post('currency');
				$floor_price = $this->input->post('floor_price');
				$market_price = $this->input->post('market_price');

				// proses input
				for ($i=0; $i < count($company_service); $i++) { 
					$currency_data = $this->M_master->check_update_data_weight($company_service[$i], $from_location[$i], $to_location[$i])->row()->TARIFF_CURRENCY;
					$floor_price_data = $this->M_master->check_update_data_weight($company_service[$i], $from_location[$i], $to_location[$i])->row()->FLOOR_PRICE;
					$market_price_data = $this->M_master->check_update_data_weight($company_service[$i], $from_location[$i], $to_location[$i])->row()->MARKET_PRICE;
					$start_date_data = $this->M_master->check_update_data_weight($company_service[$i], $from_location[$i], $to_location[$i])->row()->START_DATE;

					if ($market_price_data != $market_price[$i] || $floor_price_data != $floor_price[$i] || $currency_data != $currency[$i] || $start_date_data != $start_date[$i]) {
						// update data limitation
						$data_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$data_history_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'start_date' => $start_date_data,
							'floor_price' => $floor_price_data,
							'market_price' => $market_price_data,
							'tariff_currency' => $currency_data,
							'user_id' => $this->nik,
							'user_date' => $date
						);

						$update_limit_weight = $this->M_master->update_limit_weight($company_service[$i], $selling_service[$i], $from_location[$i], $to_location[$i],'dbo.MLIMITATIONS_PRICE_WEIGHT_ATTRIBUTE', $data_limitation);
						if ($update_limit_weight == FALSE) {
							throw new Exception("Error Processing Request to Updated Floor Price Weight", 1);
						}
						if (!$this->db->insert('dbo.HILIMITATIONS_PRICE_WEIGHT_ATTRIBUTE', $data_history_limitation)) {
							throw new Exception("Error Processing Request to Entry Floor Price Weight", 1);
						}

						unset($data_limitation);
						unset($data_history_limitation);
					} elseif ($market_price_data == $market_price[$i] && $floor_price_data == $floor_price[$i] && $currency_data == $currency[$i] && $start_date_data == $start_date[$i]) {
						$data_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$update_limit_weight = $this->M_master->update_limit_weight($company_service[$i], $selling_service[$i], $from_location[$i], $to_location[$i],'dbo.MLIMITATIONS_PRICE_WEIGHT_ATTRIBUTE', $data_limitation);
						if ($update_limit_weight == FALSE) {
							throw new Exception("Error Processing Request to Updated Floor Price Weight", 1);
						}
						unset($data_limitation);
					}
				}

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request to Entry Floor Price Weight", 1);
				} else {
					$this->session->set_flashdata('success_floor_weight', "Successfully update Floor Price or Marketplace Price!");
					$this->db->trans_commit();
					redirect(current_url());
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_floor_weight', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
			}
		}
	}

	public function floor_market_ocean_freight()
	{
		$data['data_ocean'] = $this->M_master->get_ocean()->result();
		$date = date('Y-m-d H:i:s');

		$data['data_selling_ocean'] = $this->M_master->get_selling_ocean()->result();

		// $this->load->helper('comman_helper');
		// pr($data['data_selling_ocean']);

		if (!$data['data_ocean']) {
			$result_ocean = array();
		} else {
			foreach ($data['data_ocean'] as $key => $value) {
				$temp_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
				$temp_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$temp_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$temp_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				$temp_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
				$temp_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$temp_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$temp_data['FROM_NAME'] = $value->FROM_NAME;
				$temp_data['TO_NAME'] = $value->TO_NAME;
				$temp_data['CHARGE_ID'] = $value->CHARGE_ID;
				$temp_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$temp_data['START_DATE'] = $value->START_DATE;
				$temp_data['FLOOR_PRICE'] = $value->FLOOR_PRICE;
				$temp_data['MARKET_PRICE'] = $value->MARKET_PRICE;

				foreach ($data['data_selling_ocean'] as $key1 => $value1) {
					if ($value1->COMPANY_SERVICE_ID == $value->COMPANY_SERVICE_ID && $value1->FROM_LOCATION_ID == $value->FROM_LOCATION_ID && $value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == $value->CONTAINER_SIZE_ID && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->CHARGE_ID == $value->CHARGE_ID) {
						$temp_data['TARIFF_AMOUNT'] = $value1->TARIFF_AMOUNT;
					}
				}

				$result_ocean[] = $temp_data;
			}
		}

		// $this->load->helper('comman_helper');
		// pr($result_ocean);
		$data['result_ocean'] = $result_ocean;

		$this->form_validation->set_rules('company_service[]', 'Company Service', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('master/v_floormarketocean', $data);
		} else {
			$this->db->trans_begin();
			try {
				// declare variable
				$company_service = $this->input->post('company_service');
				$selling_service = $this->input->post('selling_service');
				$from_location = $this->input->post('from_location');
				$to_location = $this->input->post('to_location');
				$charge_id = $this->input->post('charge_id');
				$container_size = $this->input->post('container_size');
				$container_type = $this->input->post('container_type');
				$container_category = $this->input->post('container_category');
				$start_date = $this->input->post('start_date');
				$currency = $this->input->post('currency');
				$floor_price = $this->input->post('floor_price');
				$market_price = $this->input->post('market_price');

				// proses input
				for ($i=0; $i < count($company_service); $i++) { 
					$currency_data = $this->M_master->check_update_data_ocean($company_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i], $charge_id[$i])->row()->TARIFF_CURRENCY;
					$floor_price_data = $this->M_master->check_update_data_ocean($company_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i], $charge_id[$i])->row()->FLOOR_PRICE;
					$market_price_data = $this->M_master->check_update_data_ocean($company_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i], $charge_id[$i])->row()->MARKET_PRICE;
					$start_date_data = $this->M_master->check_update_data_ocean($company_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i], $charge_id[$i])->row()->START_DATE;

					if ($market_price_data != $market_price[$i] || $floor_price_data != $floor_price[$i] || $currency_data != $currency[$i] || $start_date_data != $start_date[$i]) {
						// update data limitation
						$data_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'charge_id' => $charge_id[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$data_history_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'charge_id' => $charge_id[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date_data,
							'floor_price' => $floor_price_data,
							'market_price' => $market_price_data,
							'tariff_currency' => $currency_data,
							'user_id' => $this->nik,
							'user_date' => $date
						);

						$update_limit_ocean = $this->M_master->update_limit_ocean($company_service[$i], $selling_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i], $charge_id[$i],'dbo.MLIMITATIONS_PRICE_OCEAN_FREIGHT_ATTRIBUTE', $data_limitation);
						if ($update_limit_ocean == FALSE) {
							throw new Exception("Error Processing Request to Updated Floor Price Ocean Freight", 1);
						}
						if (!$this->db->insert('dbo.HILIMITATIONS_PRICE_OCEAN_FREIGHT_ATTRIBUTE', $data_history_limitation)) {
							throw new Exception("Error Processing Request to Entry Floor Price Ocean Freight", 1);
						}

						unset($data_limitation);
						unset($data_history_limitation);
					} elseif ($market_price_data == $market_price[$i] && $floor_price_data == $floor_price[$i] && $currency_data == $currency[$i] && $start_date_data == $start_date[$i]) {
						$data_limitation = array(
							'company_service_id' => $company_service[$i],
							'selling_service_id' => $selling_service[$i],
							'from_location_id' => $from_location[$i],
							'to_location_id' => $to_location[$i],
							'charge_id' => $charge_id[$i],
							'container_size_id' => $container_size[$i],
							'container_type_id' => $container_type[$i],
							'container_category_id' => $container_category[$i],
							'start_date' => $start_date[$i],
							'floor_price' => (($floor_price[$i] == '')?'0':$floor_price[$i]),
							'market_price' => (($market_price[$i] == '')?'0':$market_price[$i]),
							'tariff_currency' => $currency[$i],
							'user_id' => $this->nik,
							'user_date' => $date
						);
						$update_limit_ocean = $this->M_master->update_limit_ocean($company_service[$i], $selling_service[$i], $from_location[$i], $to_location[$i], $container_size[$i], $container_type[$i], $container_category[$i], $charge_id[$i],'dbo.MLIMITATIONS_PRICE_OCEAN_FREIGHT_ATTRIBUTE', $data_limitation);
						if ($update_limit_ocean == FALSE) {
							throw new Exception("Error Processing Request to Updated Floor Price Ocean Freight", 1);
						}
						unset($data_limitation);
					}
				}

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request to Updated Floor Price Ocean Freight", 1);
				} else {
					$this->session->set_flashdata('success_floor_ocean', "Successfully update Floor Price or Marketplace Price!");
					$this->db->trans_commit();
					redirect(current_url());
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_floor_ocean', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
			}
		}
	}

	function term_and_condition()
	{
		$data['template_id'] = $this->M_master->get_template('1')->row()->TEMPLATE_ID;
		$data['template_description'] = $this->M_master->get_template('1')->row()->TEMPLATE_DESCRIPTION;
		$data['template_indonesia'] = $this->M_master->get_template('1')->row()->TEMPLATE_TEXT1;
		$data['template_inggris'] = $this->M_master->get_template('1')->row()->TEMPLATE_TEXT2;
		$this->form_validation->set_rules('template_description', 'Template Description', 'required');
		$this->form_validation->set_rules('template_text1', 'Syarat dan Kondisi', 'required');
		$this->form_validation->set_rules('template_text2', 'Terms and Conditions', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('master/v_termandcondition', $data);
		} else {
			$template_id = $this->input->post('template_id');
			$template_description = $this->input->post('template_description');
			$template_text1 = $this->input->post('template_text1');
			$template_text2 = $this->input->post('template_text2');

			$data_term = array(
				'template_description' => $this->input->post('template_description'),
				'template_text1' => $this->input->post('template_text1'),
				'template_text2' => $this->input->post('template_text2')
			);

			// input term and condition
			$this->M_master->update_template('dbo.MTEMPLATE', $data_term, $template_id);

			$this->session->set_flashdata('success', "Successfully Updated terms and conditions!");
			redirect(current_url());
		}
	}

	function test()
	{
		$data['term'] = $this->M_master->test()->result();

		$this->load->view('master/v_test', $data);
	}

	function index_hoarding()
	{
		$data['data_hoarding'] = $this->M_master->get_data_hoarding()->result();
		$this->load->view('master/v_indexhoarding', $data);
	}

	function entry_hoarding()
	{
		$date = date('Y-m-d');
		$temp_id = $this->M_master->get_max_hoarding();
		$s_huruf_id = substr($temp_id->row()->HOARDING_ID, 0, 2);
		$n_angka_id = substr($temp_id->row()->HOARDING_ID, 2, 5)+1;
		
		if($n_angka_id<10) {
		 	$kode='HD0000'.$n_angka_id;	
		} elseif($n_angka_id > 9 && $n_angka_id <=99) {
		 	$kode='HD000'.$n_angka_id;
		} elseif($n_angka_id > 99 && $n_angka_id <=999) {
		 	$kode='HD00'.$n_angka_id;
		} elseif($n_angka_id > 999 && $n_angka_id <=9999) {
		 	$kode='HD0'.$n_angka_id;
		} elseif($n_angka_id > 9999 && $n_angka_id <=99999) {
		 	$kode='HD'.$n_angka_id;
		} 

		// $this->load->helper('comman_helper');
		// pr($kode);
		$data['hoarding_id'] = $kode;

		$this->form_validation->set_rules('hoarding_id', 'Hoarding ID', 'required');
		$this->form_validation->set_rules('hoarding_name', 'Hoarding Location', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if ($this->form_validation->run() == false) {
			$this->load->view('master/v_entryhoarding', $data);
		} else {
			$this->db->trans_begin();
			try {
				$hoarding_id = $this->input->post('hoarding_id');
				$hoarding_name = $this->input->post('hoarding_name');

				// insert into database
				$data_hoarding = array(
					'hoarding_id' => $hoarding_id,
					'hoarding_name' => $hoarding_name,
					'user_id' => $this->nik,
					'user_date' => $date
				);

				if (!$this->db->insert('dbo.MHOARDING', $data_hoarding)) {
					throw new Exception("Error Processing Request to Entry Data Hoarding", 1);
				}

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request to Entry Data Hoarding", 1);
				} else {
					$this->session->set_flashdata('success_hoarding', "Successfully added hoarding data!");
					$this->db->trans_commit();
					redirect(current_url());
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed_hoarding', $e->getMessage());
				$this->db->trans_rollback();
				redirect(current_url());
			}
		}
	}

	function entry_truck()
	{
		$this->load->helper('comman_helper');
		$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$name_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_NAME;
		$date = date('Y-m-d H:i:s');
		$data['company_id'] = $code_cmpy;
		$data['company_name'] = $name_cmpy;

		$this->form_validation->set_rules('truck_id', 'Truck Number', 'required');
		$this->form_validation->set_rules('stnk_expired', 'STNK Expired', 'required');
		$this->form_validation->set_rules('bpkb_number', 'BPKB Number', 'required');
		$this->form_validation->set_rules('kir_number', 'KIR Number', 'required');
		$this->form_validation->set_rules('kir_expired', 'KIR Expired', 'required');
		$this->form_validation->set_rules('share_operation_cost', 'Share Operation Cost', 'required');

		if (isset($_POST)) {
			// declare variable
			$truck_id = $this->input->post('truck_id');
			$company_id = $this->input->post('company_id');
			$stnk_expired = $this->input->post('stnk_expired');
			$bpkb_number = $this->input->post('bpkb_number');
			$kir_number = $this->input->post('kir_number');
			$kir_expired = $this->input->post('kir_expired');
			$share_operation_cost = $this->input->post('share_operation_cost');

			// check truck exists
			$check_truck = $this->M_master->check_truck($truck_id)->num_rows();

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entrytruck', $data);
			} elseif ($check_truck > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Truck Number Already Exists";
				$this->load->view('master/v_entrytruck', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_truck = array(
						'truck_id' => $truck_id,
						'company_id' => $company_id,
						'stnk_expired' => $stnk_expired,
						'bpkb_number' => $bpkb_number,
						'kir_number' => $kir_number,
						'kir_expired' => $kir_expired,
						'flag' => '0',
						'share_operation_cost' => $share_operation_cost,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MTRUCK', $data_truck)) {
						throw new Exception("Error Processing Request to Entry Data Truck.", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Truck Data", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully Entry Truck Data");
						$this->db->trans_commit();
						redirect('Master/view_all_truck');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function view_all_truck()
	{
		$this->load->helper('comman_helper');
		$data['data_truck'] = $this->M_master->get_data_truck()->result();
		$this->load->view('master/v_indextruck', $data);
	}

	function edit_truck()
	{
		$this->load->helper('comman_helper');
		$date = date('Y-m-d H:i:s');
		$truck_temp = $this->uri->segment(3);
		$truck_id = str_replace('_', ' ', $truck_temp);
		$data['truck_id'] = $this->M_master->get_data_truck2($truck_id)->row()->TRUCK_ID;
		$data['stnk_expired'] = $this->M_master->get_data_truck2($truck_id)->row()->STNK_EXPIRED;
		$data['bpkb_number'] = $this->M_master->get_data_truck2($truck_id)->row()->BPKB_NUMBER;
		$data['kir_number'] = $this->M_master->get_data_truck2($truck_id)->row()->KIR_NUMBER;
		$data['kir_expired'] = $this->M_master->get_data_truck2($truck_id)->row()->KIR_EXPIRED;
		$data['share_operation_cost'] = $this->M_master->get_data_truck2($truck_id)->row()->SHARE_OPERATION_COST;
		$data['company_id'] = $this->M_master->get_data_truck2($truck_id)->row()->COMPANY_ID;

		$this->form_validation->set_rules('truck_id', 'Truck Number', 'required');
		$this->form_validation->set_rules('stnk_expired', 'STNK Expired', 'required');
		$this->form_validation->set_rules('bpkb_number', 'BPKB Number', 'required');
		$this->form_validation->set_rules('kir_number', 'KIR Number', 'required');
		$this->form_validation->set_rules('kir_expired', 'KIR Expired', 'required');
		$this->form_validation->set_rules('share_operation_cost', 'Share Operation Cost', 'required');

		if (isset($_POST)) {
			// declare variable
			$truck_id = $this->input->post('truck_id');
			$company_id = $this->input->post('company_id');
			$stnk_expired = $this->input->post('stnk_expired');
			$bpkb_number = $this->input->post('bpkb_number');
			$kir_number = $this->input->post('kir_number');
			$kir_expired = $this->input->post('kir_expired');
			$share_operation_cost = $this->input->post('share_operation_cost');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_edittruck', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_truck = array(
						'stnk_expired' => $stnk_expired,
						'bpkb_number' => $bpkb_number,
						'kir_number' => $kir_number,
						'kir_expired' => $kir_expired,
						'share_operation_cost' => $share_operation_cost,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_truck = $this->M_master->update_truck('dbo.MTRUCK', $data_truck, $truck_id);

					if ($update_truck == FALSE) {
						throw new Exception("Error Processing Request to Update Data Truck.", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Update Truck Data", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully Update Truck Data");
						$this->db->trans_commit();
						redirect('Master/view_all_truck');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function entry_chassis()
	{
		$this->load->helper('comman_helper');
		$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$name_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_NAME;
		$date = date('Y-m-d H:i:s');
		$data['company_id'] = $code_cmpy;
		$data['company_name'] = $name_cmpy;

		$this->form_validation->set_rules('chassis_id', 'Chassis Number', 'required');
		$this->form_validation->set_rules('kir_number', 'KIR Number', 'required');
		$this->form_validation->set_rules('kir_expired', 'KIR Expired', 'required');
		$this->form_validation->set_rules('share_operation_cost', 'Share Operation Cost', 'required');

		if (isset($_POST)) {
			// declare variable
			$chassis_id = $this->input->post('chassis_id');
			$company_id = $this->input->post('company_id');
			$kir_number = $this->input->post('kir_number');
			$kir_expired = $this->input->post('kir_expired');
			$share_operation_cost = $this->input->post('share_operation_cost');

			// check truck exists
			$check_chassis = $this->M_master->check_chassis($chassis_id)->num_rows();

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entrychassis', $data);
			} elseif ($check_chassis > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Chassis Number Already Exists";
				$this->load->view('master/v_entrychassis', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_chassis = array(
						'chassis_id' => $chassis_id,
						'company_id' => $company_id,
						'kir_number' => $kir_number,
						'kir_expired' => $kir_expired,
						'flag' => '0',
						'share_operation_cost' => $share_operation_cost,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MCHASSIS', $data_chassis)) {
						throw new Exception("Error Processing Request to Entry Data Chassis.", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Chassis Data", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully Entry Chassis Data");
						$this->db->trans_commit();
						redirect('Master/view_all_chassis');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function view_all_chassis()
	{
		$this->load->helper('comman_helper');
		$data['data_chassis'] = $this->M_master->get_data_chassis()->result();
		$this->load->view('master/v_indexchassis', $data);
	}

	function edit_chassis()
	{
		$this->load->helper('comman_helper');
		$chassis_id = $this->uri->segment(3);
		$date = date('Y-m-d H:i:s');
		$data['chassis_id'] = $chassis_id;
		$data['kir_number'] = $this->M_master->get_data_chassis2($chassis_id)->row()->KIR_NUMBER;
		$data['kir_expired'] = $this->M_master->get_data_chassis2($chassis_id)->row()->KIR_EXPIRED;
		$data['share_operation_cost'] = $this->M_master->get_data_chassis2($chassis_id)->row()->SHARE_OPERATION_COST;
		$data['company_id'] = $this->M_master->get_data_chassis2($chassis_id)->row()->COMPANY_ID;

		$this->form_validation->set_rules('chassis_id', 'Chassis Number', 'required');
		$this->form_validation->set_rules('kir_number', 'KIR Number', 'required');
		$this->form_validation->set_rules('kir_expired', 'KIR Expired', 'required');
		$this->form_validation->set_rules('share_operation_cost', 'Share Operation Cost', 'required');

		if (isset($_POST)) {
			// declare variable
			$chassis_id = $this->input->post('chassis_id');
			$company_id = $this->input->post('company_id');
			$kir_number = $this->input->post('kir_number');
			$kir_expired = $this->input->post('kir_expired');
			$share_operation_cost = $this->input->post('share_operation_cost');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editchassis', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_chassis = array(
						'kir_number' => $kir_number,
						'kir_expired' => $kir_expired,
						'share_operation_cost' => $share_operation_cost,
						'user_id' => $this->nik,
						'user_date' => $date
					);
					$update_chassis = $this->M_master->update_chassis('dbo.MCHASSIS', $data_chassis, $chassis_id);
					if ($update_chassis == FALSE) {
						throw new Exception("Error Processing Request to Update Data Chassis.", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Update Chassis Data", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully Update Chassis Data");
						$this->db->trans_commit();
						redirect('Master/view_all_chassis');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function view_mutation()
    {
    	$this->load->helper('comman_helper');
    	$data['data_mutation'] = $this->M_master->get_data_mutation()->result();
    	$this->load->view('Master/v_mastermutation', $data);
    }

    function edit_mutation()
    {
    	$this->load->helper('comman_helper');
    	$transaction_id = $this->uri->segment(3);
    	$data['transaction_id'] = $transaction_id;
    	$data['transaction_date'] = $this->M_master->get_detail_mutation($transaction_id)->row()->TRANS_DATE;
    	$data['bank_id'] = $this->M_master->get_detail_mutation($transaction_id)->row()->BANK_ID;
    	$data['pic_name'] = $this->M_master->get_detail_mutation($transaction_id)->row()->PIC_NAME;
    	$data['status'] = $this->M_master->get_detail_mutation($transaction_id)->row()->IS_DONE;
    	$data['work_order_number'] = $this->M_master->get_detail_mutation($transaction_id)->row()->WORK_ORDER_NUMBER;

    	$data['transaction_data'] = $this->M_master->get_detail_mutation($transaction_id)->result();
    	$data['data_wo'] = $this->M_master->get_wo_opr()->result();

    	$this->form_validation->set_rules('work_order_number', 'Work Order Number', 'required');
		// $this->form_validation->set_rules('status', 'Status', 'required');

		if (isset($_POST)) {
			// declare variable
			$transaction_id = $this->input->post('transaction_id');
			$work_order_number = $this->input->post('work_order_number');
			$status = $this->input->post('status');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editmutation', $data);
			} else {
				try {
					$data_insert = array(
						'work_order_number' => $work_order_number,
						'is_done' => $status
					);

					$this->db->trans_begin();
						$update_mut = $this->M_master->update_mut('dbo.TRBANK_STATEMENT', $data_insert, $transaction_id);

						if ($this->db->trans_status() === FALSE) {
				    		throw new Exception("Error Processing Request to Updated Mutation Data", 1);
				    	} else {
				    		$this->session->set_flashdata('success', 'Good Job! Successfully Updated Mutation Data.');
				    		$this->db->trans_commit();
				    		redirect(current_url());
				    	}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
		    		$this->db->trans_rollback();
		    		redirect(current_url());
				}
			}
		}
    }

    function update_mutation()
    {
    	$status = $this->input->post('status_mutation');
    	$mutation_id = $this->input->post('mutation_id');
    	$data = array(
    		'is_done' => $status
    	);

    	$update_mut = $this->M_master->update_mut('dbo.TRBANK_STATEMENT', $data, $mutation_id);
    }

    function entry_cost()
    {
    	$this->load->helper('comman_helper');
    	$temp_id = $this->M_master->get_max_cost()->row()->id;
    	$temp_ids = $temp_id + 1;

    	if ($temp_id != NULL || $temp_id != "") {
    		if ($temp_ids < 10) {
    			$id = "C" . "00" . $temp_ids;
    		} elseif ($temp_ids == 10 || $temp_ids < 100) {
    			$id = "C" . "0" . $temp_ids;
    		} else {
    			$id = "C" . $temp_ids;
    		}
    	} else {
    		$id = "C001";
    	}
    	$data['id'] = $id;

    	$data['cost_type'] = $this->M_master->get_cost_type()->result();
    	$data['cost_group'] = $this->M_master->get_cost_group()->result();
    	$data['gl_account'] = $this->M_master->get_gl_account()->result();
    	$date = date('Y-m-d H:i:s');

    	$this->form_validation->set_rules('cost_id', 'Cost ID', 'required');
    	$this->form_validation->set_rules('cost_name', 'Cost Name', 'required');
    	$this->form_validation->set_rules('description', 'Description', 'required');
    	$this->form_validation->set_rules('cost_kind', 'Cost Kind', 'required');
    	$this->form_validation->set_rules('cost_type', 'Cost Type', 'required');
    	$this->form_validation->set_rules('cost_group', 'Cost Group', 'required');
    	$this->form_validation->set_rules('cost_share', 'Cost Share', 'required');
    	// $this->form_validation->set_rules('gl_account', 'GL Account', 'required');
		if (isset($_POST)) {
			$cost_id = $this->input->post('cost_id');
	    	$cost_name = $this->input->post('cost_name');
	    	$description = $this->input->post('description');
	    	$cost_kind = $this->input->post('cost_kind');
	    	$cost_type = $this->input->post('cost_type');
	    	$cost_group = $this->input->post('cost_group');
	    	$cost_share = $this->input->post('cost_share');
	    	$gl_account = $this->input->post('gl_account');

	    	$check_data = $this->M_master->check_data_cost($cost_name, $cost_kind)->num_rows();
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entrycost', $data);
			} elseif ($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Oh no! Duplicate Cost. Insert other cost.";
				$this->load->view('master/v_entrycost', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data = array(
			    		'cost_id' => $cost_id,
			    		'cost_name' => strtoupper($cost_name),
			    		'description' => strtoupper($description),
			    		'cost_kind' => $cost_kind,
			    		'cost_type' => $cost_type,
			    		'cost_group' => $cost_group,
			    		'cost_share' => $cost_share,
			    		'gl_account' => $gl_account,
			    		'user_id' => $this->nik,
			    		'user_date' => $date
			    	);

			    	if (!$this->db->insert('dbo.MCOST', $data)) {
			    		throw new Exception("Error Processing Request to Entry Cost Data", 1);
			    	}

			    	if ($this->db->trans_status() === FALSE) {
			    		throw new Exception("Error Processing Request to Entry Cost Data", 1);
			    	} else {
			    		$this->session->set_flashdata('success', 'Good Job! Successfully Inserted Cost Data.');
			    		$this->db->trans_commit();
			    		redirect('Master/view_cost');
			    	}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
		    		$this->db->trans_rollback();
		    		redirect(current_url());
				}
			}
		}
    }

    function view_cost()
    {
    	$this->load->helper('comman_helper');
    	$data['data_cost'] = $this->M_master->get_all_cost()->result();
    	$this->load->view('master/v_indexcost', $data);
    }

    function edit_cost()
    {
    	$this->load->helper('comman_helper');
    	$cost_id = $this->uri->segment(3);
    	$data['cost_id'] = $cost_id;
    	$data['cost_name'] = $this->M_master->get_all_cost_param($cost_id)->row()->COST_NAME;
    	$data['cost_kind'] = $this->M_master->get_all_cost_param($cost_id)->row()->COST_KIND;
    	$data['s_cost_type'] = $this->M_master->get_all_cost_param($cost_id)->row()->COST_TYPE;
    	$data['s_cost_group'] = $this->M_master->get_all_cost_param($cost_id)->row()->COST_GROUP;
    	$data['description'] = $this->M_master->get_all_cost_param($cost_id)->row()->DESCRIPTION;
    	$data['cost_share'] = $this->M_master->get_all_cost_param($cost_id)->row()->COST_SHARE;
    	$data['gl_account'] = $this->M_master->get_all_cost_param($cost_id)->row()->GL_ACCOUNT;

    	$data['cost_type'] = $this->M_master->get_cost_type()->result();
    	$data['cost_group'] = $this->M_master->get_cost_group()->result();
    	$data['data_gl_account'] = $this->M_master->get_gl_account()->result();
    	$date = date('Y-m-d H:i:s');

    	$this->form_validation->set_rules('cost_id', 'Cost ID', 'required');
    	$this->form_validation->set_rules('cost_name', 'Cost Name', 'required');
    	$this->form_validation->set_rules('description', 'Description', 'required');
    	$this->form_validation->set_rules('cost_kind', 'Cost Kind', 'required');
    	$this->form_validation->set_rules('cost_type', 'Cost Type', 'required');
    	$this->form_validation->set_rules('cost_group', 'Cost Group', 'required');
    	$this->form_validation->set_rules('cost_share', 'Cost Share', 'required');
    	// $this->form_validation->set_rules('gl_account', 'GL Account', 'required');
		if (isset($_POST)) {
			$cost_id = $this->input->post('cost_id');
	    	$cost_name = $this->input->post('cost_name');
	    	$description = $this->input->post('description');
	    	$cost_kind = $this->input->post('cost_kind');
	    	$cost_type = $this->input->post('cost_type');
	    	$cost_group = $this->input->post('cost_group');
	    	$cost_share = $this->input->post('cost_share');
	    	$gl_account = $this->input->post('gl_account');

	    	$check_data = $this->M_master->check_data_cost($cost_name, $cost_kind)->num_rows();
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editcost', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data = array(
			    		'cost_name' => strtoupper($cost_name),
			    		'description' => strtoupper($description),
			    		'cost_kind' => $cost_kind,
			    		'cost_type' => $cost_type,
			    		'cost_group' => $cost_group,
			    		'cost_share' => $cost_share,
			    		'gl_account' => $gl_account,
			    		'user_id' => $this->nik,
			    		'user_date' => $date
			    	);

					$update_cost = $this->M_master->update_cost_model('dbo.MCOST', $data, $cost_id);

			    	if ($update_cost == FALSE) {
			    		throw new Exception("Error Processing Request to Updated Cost Data", 1);
			    	}

			    	if ($this->db->trans_status() === FALSE) {
			    		throw new Exception("Error Processing Request to Updated Cost Data", 1);
			    	} else {
			    		$this->session->set_flashdata('success', 'Good Job! Successfully Updated Cost Data.');
			    		$this->db->trans_commit();
			    		redirect('Master/view_cost');
			    	}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
		    		$this->db->trans_rollback();
		    		redirect(current_url());
				}
			}
		}
    }

    function entry_coa_truck()
    {
    	$this->load->helper('comman_helper');

    	$data['data_truck'] = $this->M_master->get_data_truck()->result();
    	$data['data_cost'] = $this->M_master->get_all_cost()->result();
    	$data['gl_account'] = $this->M_master->get_gl_account()->result();

    	$this->form_validation->set_rules('truck_id', 'Truck Number', 'required');
    	$this->form_validation->set_rules('cost_id', 'Cost', 'required');
    	$this->form_validation->set_rules('gl_account', 'GL Account', 'required');
		if (isset($_POST)) {
			$truck_id = $this->input->post('truck_id');
			$cost_id = $this->input->post('cost_id');
			$gl_account = $this->input->post('gl_account');

			// check if data was available
			$check_data = $this->M_master->check_coa_truck($truck_id, $cost_id)->num_rows();

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entrycoatruck', $data);
			} elseif ($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Oh no! Data exists, please entry something different data!";
				$this->load->view('master/v_entrycoatruck', $data);
			} else {
				$this->db->trans_begin();
				try {
					
					$data_insert = array(
						'truck_id' => $truck_id,
						'cost_id' => $cost_id,
						'account_code' => $gl_account
					);

					if (!$this->db->insert('dbo.GLCHART_TRUCK', $data_insert)) {
						throw new Exception("Error Processing Request to Entry GL Chart Truck, Something wrong!", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry GL Chart Truck", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry GL Chart for Cost Truck');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', 'Failed Entry GL Chart for Cost Truck');
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_coa_truck()
    {
    	$this->load->helper('comman_helper');
    	$data['data_coa_truck'] = $this->M_master->get_data_coa_truck()->result();
    	$this->load->view('master/v_indexcoatruck', $data);
    }

    function edit_coa_truck()
    {
    	$this->load->helper('comman_helper');
    	$trucks = $this->uri->segment(3);
    	$cost_id = $this->uri->segment(4);
    	$truck_id = str_replace("_", " ", $trucks);

    	$data['truck_id'] = $truck_id;
    	$data['cost_id'] = $cost_id;
    	$data['cost_name'] = $this->M_master->get_data_coa_truck_param($truck_id, $cost_id)->row()->COST_NAME;
    	$data['gl_account'] = $this->M_master->get_data_coa_truck_param($truck_id, $cost_id)->row()->ACCOUNT_CODE;

    	// pr($data['gl_account']);

    	$data['data_truck'] = $this->M_master->get_data_truck()->result();
    	$data['data_cost'] = $this->M_master->get_all_cost()->result();
    	$data['data_gl_account'] = $this->M_master->get_gl_account()->result();

    	$this->form_validation->set_rules('truck_id', 'Truck Number', 'required');
    	$this->form_validation->set_rules('cost_id', 'Cost', 'required');
    	$this->form_validation->set_rules('gl_account', 'GL Account', 'required');
		if (isset($_POST)) {
			$truck_id = $this->input->post('truck_id');
			$cost_id = $this->input->post('cost_id');
			$gl_account = $this->input->post('gl_account');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editcoatruck', $data);
			} else {
				$this->db->trans_begin();
				try {
					
					$data_insert = array(
						'account_code' => $gl_account
					);

					$update_coa_truck = $this->M_master->update_coa_truck('dbo.GLCHART_TRUCK', $data_insert, $truck_id, $cost_id);
					if ($update_coa_truck == FALSE) {
						throw new Exception("Error Processing Request to Updated GL Chart Cost Truck", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Update GL Chart Cost Truck", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Updated GL Chart for Cost Truck');
						$this->db->trans_commit();
						redirect('Master/view_coa_truck');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', 'Failed Updated GL Chart for Cost Truck');
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_coa_chassis()
    {
    	$this->load->helper('comman_helper');

    	$data['data_chassis'] = $this->M_master->get_data_chassis()->result();
    	$data['data_cost'] = $this->M_master->get_all_cost()->result();
    	$data['gl_account'] = $this->M_master->get_gl_account()->result();

    	$this->form_validation->set_rules('chassis_id', 'Chassis Number', 'required');
    	$this->form_validation->set_rules('cost_id', 'Cost', 'required');
    	$this->form_validation->set_rules('gl_account', 'GL Account', 'required');

		if (isset($_POST)) {
			$chassis_id = $this->input->post('chassis_id');
			$cost_id = $this->input->post('cost_id');
			$gl_account = $this->input->post('gl_account');
			// check if data was available
			$check_data = $this->M_master->check_coa_chassis($chassis_id, $cost_id)->num_rows();

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entrycoachassis', $data);
			} elseif ($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Oh no! Data exists, please entry something different data!";
				$this->load->view('master/v_entrycoachassis', $data);
			} else {
				$this->db->trans_begin();
				try {
					
					$data_insert = array(
						'chassis_id' => $chassis_id,
						'cost_id' => $cost_id,
						'account_code' => $gl_account
					);

					if (!$this->db->insert('dbo.GLCHART_CHASSIS', $data_insert)) {
						throw new Exception("Error Processing Request to Entry GL Chart Chassis, Something wrong!", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry GL Chart Chassis", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry GL Chart for Cost Chassis');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', 'Failed Entry GL Chart for Cost Chassis');
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_coa_chassis()
    {
    	$this->load->helper('comman_helper');
    	$data['data_coa_chassis'] = $this->M_master->get_data_coa_chassis()->result();
    	$this->load->view('master/v_indexcoachassis', $data);
    }

    function edit_coa_chassis()
    {
    	$this->load->helper('comman_helper');
    	$chassis_id = $this->uri->segment(3);
    	$cost_id = $this->uri->segment(4);

    	$data['chassis_id'] = $chassis_id;
    	$data['cost_id'] = $cost_id;
    	$data['cost_name'] =  $this->M_master->get_data_coa_chassis_param($chassis_id, $cost_id)->row()->COST_NAME;
    	$data['gl_account'] = $this->M_master->get_data_coa_chassis_param($chassis_id, $cost_id)->row()->ACCOUNT_CODE;

		$data['data_chassis'] = $this->M_master->get_data_chassis()->result();
    	$data['data_cost'] = $this->M_master->get_all_cost()->result();
    	$data['data_gl_account'] = $this->M_master->get_gl_account()->result();

    	$this->form_validation->set_rules('chassis_id', 'Chassis Number', 'required');
    	$this->form_validation->set_rules('cost_id', 'Cost', 'required');
    	$this->form_validation->set_rules('gl_account', 'GL Account', 'required');

		if (isset($_POST)) {
			$chassis_id = $this->input->post('chassis_id');
			$cost_id = $this->input->post('cost_id');
			$gl_account = $this->input->post('gl_account');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editcoachassis', $data);
			} else {
				$this->db->trans_begin();
				try {
					
					$data_insert = array(
						'account_code' => $gl_account
					);

					$update_chassis = $this->M_master->update_coa_chassis('dbo.GLCHART_CHASSIS', $data_insert, $chassis_id, $cost_id);

					if ($update_chassis == FALSE) {
						throw new Exception("Error Processing Request to Updated GL Chart Chassis, Something wrong!", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Updated GL Chart Chassis", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Updated GL Chart for Cost Chassis');
						$this->db->trans_commit();
						redirect('Master/view_coa_chassis');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', 'Failed Updated GL Chart for Cost Chassis');
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_company()
    {
    	$this->load->helper('comman_helper');
    	$this->load->view('master/v_entrycompany');
    }

    function entry_driver()
    {
    	$this->load->helper('comman_helper');
    	$date = date('Y-m-d H:i:s');
    	$temp_id = $this->M_master->get_max_driver()->row()->id;
    	$temp_id_subs = $temp_id + 1;

    	if ($temp_id != NULL) {
			if ($temp_id_subs < 10) {
				$id = "DR000" . $temp_id_subs;
			} elseif ($temp_id_subs == 10 || $temp_id_subs < 100) {
				$id = "DR00" . $temp_id_subs;
			} elseif ($temp_id_subs == 100 || $temp_id_subs < 1000) {
				$id = "DR0" . $temp_id_subs;
			} else {
				$id = "DR" . $temp_id_subs;
			}
		} else {
			// echo "kosong";
			$id = "DR0001";
		}

		$data['id'] = $id;

		$this->form_validation->set_rules('driver_id', 'Driver ID', 'required');
		$this->form_validation->set_rules('driver_name', 'Driver Name', 'required');
		$this->form_validation->set_rules('born_of_location', 'Place of Birth', 'required');
		$this->form_validation->set_rules('born_of_date', 'Date of Birth', 'required');
		$this->form_validation->set_rules('ktp_number', 'KTP Number', 'required');
		$this->form_validation->set_rules('license_driver_id', 'SIM Number', 'required');
		$this->form_validation->set_rules('license_driver_expired', 'SIM Expired', 'required');
		$this->form_validation->set_rules('phone_number', 'Phone Number', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entrydriver', $data);
			} else {
				$driver_id = $this->input->post('driver_id');
				$driver_name = $this->input->post('driver_name');
				$born_of_location = $this->input->post('born_of_location');
				$born_of_date = $this->input->post('born_of_date');
				$ktp_number = $this->input->post('ktp_number');
				$license_driver_id = $this->input->post('license_driver_id');
				$license_driver_expired = $this->input->post('license_driver_expired');
				$phone_number = $this->input->post('phone_number');

				$this->db->trans_begin();
				try {
					$data_driver = array(
						'driver_id' => $driver_id,
						'driver_name' => $driver_name,
						'born_of_location' => $born_of_location,
						'born_of_date' => $born_of_date,
						'ktp_number' => $ktp_number,
						'license_driver_id' => $license_driver_id,
						'license_driver_expired' => $license_driver_expired,
						'personal_phone_number' => $phone_number,
						'flag' => '0'
					);

					if (!$this->db->insert('dbo.MDRIVER', $data_driver)) {
						throw new Exception("Error Processing Request to Entry Data Driver", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Data Driver", 1);
					} else {
						$this->session->set_flashdata('success_driver', 'Successfully Entry Data Driver');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed_driver', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_driver()
    {
    	$this->load->helper('comman_helper');
    	$data['data_driver'] = $this->M_master->get_data_driver()->result();
    	$this->load->view('master/v_indexdriver', $data);
    }

    function edit_driver()
    {
    	$this->load->helper('comman_helper');
    	$driver_id = $this->uri->segment(3);
    	$data['id'] = $driver_id;

    	$data['driver_name'] = $this->M_master->get_data_driver_param($driver_id)->row()->DRIVER_NAME;
    	$data['born_of_location'] = $this->M_master->get_data_driver_param($driver_id)->row()->BORN_OF_LOCATION;
    	$data['born_of_date'] = $this->M_master->get_data_driver_param($driver_id)->row()->BORN_OF_DATE;
    	$data['ktp_number'] = $this->M_master->get_data_driver_param($driver_id)->row()->KTP_NUMBER;
    	$data['license_driver_id'] = $this->M_master->get_data_driver_param($driver_id)->row()->LICENSE_DRIVER_ID;
    	$data['license_driver_expired'] = $this->M_master->get_data_driver_param($driver_id)->row()->LICENSE_DRIVER_EXPIRED;
    	$data['phone_number'] = $this->M_master->get_data_driver_param($driver_id)->row()->PERSONAL_PHONE_NUMBER;

    	$this->form_validation->set_rules('driver_id', 'Driver ID', 'required');
		$this->form_validation->set_rules('driver_name', 'Driver Name', 'required');
		$this->form_validation->set_rules('born_of_location', 'Place of Birth', 'required');
		$this->form_validation->set_rules('born_of_date', 'Date of Birth', 'required');
		$this->form_validation->set_rules('ktp_number', 'KTP Number', 'required');
		$this->form_validation->set_rules('license_driver_id', 'SIM Number', 'required');
		$this->form_validation->set_rules('license_driver_expired', 'SIM Expired', 'required');
		$this->form_validation->set_rules('phone_number', 'Phone Number', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editdriver', $data);
			} else {
				$driver_id = $this->input->post('driver_id');
				$driver_name = $this->input->post('driver_name');
				$born_of_location = $this->input->post('born_of_location');
				$born_of_date = $this->input->post('born_of_date');
				$ktp_number = $this->input->post('ktp_number');
				$license_driver_id = $this->input->post('license_driver_id');
				$license_driver_expired = $this->input->post('license_driver_expired');
				$phone_number = $this->input->post('phone_number');

				$this->db->trans_begin();
				try {
					$data_driver = array(
						'driver_name' => $driver_name,
						'born_of_location' => $born_of_location,
						'born_of_date' => $born_of_date,
						'ktp_number' => $ktp_number,
						'license_driver_id' => $license_driver_id,
						'license_driver_expired' => $license_driver_expired,
						'personal_phone_number' => $phone_number
					);

					$update_driver = $this->M_master->update_driver('dbo.MDRIVER', $data_driver, $driver_id);
					if ($update_driver == FALSE) {
						throw new Exception("Error Processing Request to Updated Data Driver", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Updated Data Driver", 1);
					} else {
						$this->session->set_flashdata('success_edit_driver', 'Successfully Updated Data Driver');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed_edit_driver', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_vendor()
    {
    	$this->load->helper('comman_helper');
    	$date = date('Y-m-d H:i:s');

    	$temp_id = $this->M_master->get_max_vendor()->row()->id;
    	$temp_id_subs = $temp_id + 1;

    	if ($temp_id != NULL) {
			if ($temp_id_subs < 10) {
				$id = "VD0000" . $temp_id_subs;
			} elseif ($temp_id_subs == 10 || $temp_id_subs < 100) {
				$id = "VD000" . $temp_id_subs;
			} elseif ($temp_id_subs == 100 || $temp_id_subs < 1000) {
				$id = "VD00" . $temp_id_subs;
			} elseif ($temp_id_subs == 1000 || $temp_id_subs < 10000) {
				$id = "VD0" . $temp_id_subs;
			} else {
				$id = "VD" . $temp_id_subs;
			}
		} else {
			// echo "kosong";
			$id = "VD00001";
		}

		$data['id'] = $id;

		$this->form_validation->set_rules('vendor_id', 'Driver ID', 'required');
		$this->form_validation->set_rules('vendor_name', 'Driver Name', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entryvendor', $data);
			} else {
				$vendor_id = $this->input->post('vendor_id');
				$vendor_name = $this->input->post('vendor_name');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'vendor_id' => $vendor_id,
						'vendor_name' => strtoupper($vendor_name),
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MVENDOR', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Data Vendor", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Data Vendor", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Data Vendor');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_vendor()
    {
    	$this->load->helper('comman_helper');
    	$data['data_vendor'] = $this->M_master->get_data_vendor()->result();
    	$this->load->view('master/v_indexvendor', $data);
    }

    function edit_vendor()
    {
    	$this->load->helper('comman_helper');
    	$vendor_id = $this->uri->segment(3);
    	$date = date('Y-m-d H:i:s');

    	$data['vendor_id'] = $vendor_id;
    	$data['vendor_name'] = $this->M_master->get_data_vendor_param($vendor_id)->row()->VENDOR_NAME;

    	$this->form_validation->set_rules('vendor_id', 'Driver ID', 'required');
		$this->form_validation->set_rules('vendor_name', 'Driver Name', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editvendor', $data);
			} else {
				$vendor_id = $this->input->post('vendor_id');
				$vendor_name = $this->input->post('vendor_name');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'vendor_name' => $vendor_name,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_vendor = $this->M_master->update_vendor('dbo.MVENDOR', $data_insert, $vendor_id);
					if ($update_vendor == FALSE) {
						throw new Exception("Error Processing Request to Update Data Vendor", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Update Data Vendor", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Update Data Vendor');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_vendor_contract()
    {
    	$this->load->helper('comman_helper');
    	$date = date('Y-m-d H:i:s');
    	$data['data_vendor'] = $this->M_master->get_data_vendor()->result();

    	$temp_id = $this->M_master->get_max_vendor_contract()->row()->id;
    	$temp_id_subs = $temp_id + 1;

    	if ($temp_id != NULL) {
			if ($temp_id_subs < 10) {
				$id = "VD0000" . $temp_id_subs;
			} elseif ($temp_id_subs == 10 || $temp_id_subs < 100) {
				$id = "VD000" . $temp_id_subs;
			} elseif ($temp_id_subs == 100 || $temp_id_subs < 1000) {
				$id = "VD00" . $temp_id_subs;
			} elseif ($temp_id_subs == 1000 || $temp_id_subs < 10000) {
				$id = "VD0" . $temp_id_subs;
			} else {
				$id = "VD" . $temp_id_subs;
			}
		} else {
			// echo "kosong";
			$id = "VD00001";
		}

		$data['id'] = $id;
		$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;

		$data['company_id'] = $code_cmpy;
		$data['data_vendor_kind'] = $this->M_master->get_vendor_kind()->result();

		$this->form_validation->set_rules('contract_no', 'Contract Number', 'required');
		$this->form_validation->set_rules('company_id', 'Company ID', 'required');
		$this->form_validation->set_rules('vendor_id', 'Vendor ID', 'required');
		$this->form_validation->set_rules('vendor_kind', 'Vendor Kind', 'required');
		$this->form_validation->set_rules('reference_number', 'Reference Number', 'required');
		$this->form_validation->set_rules('contract_date', 'Contract Date', 'required');
		$this->form_validation->set_rules('vendor_pic', 'Vendor PIC', 'required');
		$this->form_validation->set_rules('valid_from_date', 'Valid From Date', 'required');
		$this->form_validation->set_rules('valid_to_date', 'Valid To Date', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entryvendorcontract', $data);
			} else {
				$contract_no = $this->input->post('contract_no');
				$company_id = $this->input->post('company_id');
				$vendor_id = $this->input->post('vendor_id');
				$vendor_kind = $this->input->post('vendor_kind');
				$reference_number = $this->input->post('reference_number');
				$contract_date = $this->input->post('contract_date');
				$vendor_pic = $this->input->post('vendor_pic');
				$valid_from_date = $this->input->post('valid_from_date');
				$valid_to_date = $this->input->post('valid_to_date');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'contract_no' => $contract_no,
						'company_id' => $company_id,
						'vendor_id' => $vendor_id,
						'vendor_kind' => $vendor_kind,
						'reference_number' => strtoupper($reference_number),
						'contract_date' => $contract_date,
						'vendor_pic' => strtoupper($vendor_pic),
						'valid_from_date' => $valid_from_date,
						'valid_to_date' => $valid_to_date,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MVENDOR_CONTRACT', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Vendor Contract", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Vendor Contract", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Vendor Contract');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_vendor_contract()
    {
    	$this->load->helper('comman_helper');
    	$data['data_vendor_contract'] = $this->M_master->get_data_vendor_contract()->result();
    	$this->load->view('master/v_indexvendorcontract', $data);
    }

    function edit_vendor_contract()
    {
    	$this->load->helper('comman_helper');
    	$contract_no = $this->uri->segment(3);
    	$date = date('Y-m-d H:i:s');

    	$data['id'] = $contract_no;
    	$data['company_id'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->COMPANY_ID;
    	$data['vendor_id'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->VENDOR_ID;
    	$data['vendor_kind'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->VENDOR_KIND;
    	$data['reference_number'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->REFERENCE_NUMBER;
    	$data['contract_date'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->CONTRACT_DATE;
    	$data['vendor_pic'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->VENDOR_PIC;
    	$data['valid_from_date'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->FROM_DATE;
    	$data['valid_to_date'] = $this->M_master->get_data_vendor_contract_param($contract_no)->row()->TO_DATE;

    	$data['data_vendor'] = $this->M_master->get_data_vendor()->result();
		$data['data_vendor_kind'] = $this->M_master->get_vendor_kind()->result();

    	$this->form_validation->set_rules('contract_no', 'Contract Number', 'required');
		$this->form_validation->set_rules('company_id', 'Company ID', 'required');
		$this->form_validation->set_rules('vendor_id', 'Vendor ID', 'required');
		$this->form_validation->set_rules('vendor_kind', 'Vendor Kind', 'required');
		$this->form_validation->set_rules('reference_number', 'Reference Number', 'required');
		$this->form_validation->set_rules('contract_date', 'Contract Date', 'required');
		$this->form_validation->set_rules('vendor_pic', 'Vendor PIC', 'required');
		$this->form_validation->set_rules('valid_from_date', 'Valid From Date', 'required');
		$this->form_validation->set_rules('valid_to_date', 'Valid To Date', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editvendorcontract', $data);
			} else {
				$contract_no = $this->input->post('contract_no');
				$company_id = $this->input->post('company_id');
				$vendor_id = $this->input->post('vendor_id');
				$vendor_kind = $this->input->post('vendor_kind');
				$reference_number = $this->input->post('reference_number');
				$contract_date = $this->input->post('contract_date');
				$vendor_pic = $this->input->post('vendor_pic');
				$valid_from_date = $this->input->post('valid_from_date');
				$valid_to_date = $this->input->post('valid_to_date');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'company_id' => $company_id,
						'vendor_id' => $vendor_id,
						'vendor_kind' => $vendor_kind,
						'reference_number' => strtoupper($reference_number),
						'contract_date' => $contract_date,
						'vendor_pic' => strtoupper($vendor_pic),
						'valid_from_date' => $valid_from_date,
						'valid_to_date' => $valid_to_date,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_vendor_contract = $this->M_master->update_vendor_contract('dbo.MVENDOR_CONTRACT', $data_insert, $contract_no);

					if ($update_vendor_contract == FALSE) {
						throw new Exception("Error Processing Request to Update Vendor Contract", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Update Vendor Contract", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Update Vendor Contract');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_vendor_truck()
    {
    	$this->load->helper('comman_helper');
    	$date = date('Y-m-d H:i:s');
    	$data['data_vendor_contract'] = $this->M_master->get_data_vendor_contract()->result();
    	$data['container_size'] = $this->M_master->get_container_size()->result();
		$data['container_type'] = $this->M_master->get_container_type()->result();
		$data['container_category'] = $this->M_master->get_container_category()->result();
		$data['data_location'] = $this->M_master->get_location_master()->result();
    	$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;

		$data['company_id'] = $code_cmpy;

		$this->form_validation->set_rules('contract_no', 'Contract Number', 'required');
		$this->form_validation->set_rules('company_id', 'Company ID', 'required');
		$this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		$this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		$this->form_validation->set_rules('from_qty', 'From QTY', 'required');
		$this->form_validation->set_rules('to_qty', 'To QTY', 'required');
		$this->form_validation->set_rules('from_location_id', 'From Location', 'required');
		$this->form_validation->set_rules('to_location_id', 'To Location', 'required');
		$this->form_validation->set_rules('buying_currency', 'Currency', 'required');
		$this->form_validation->set_rules('buying_rate', 'Amount', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entryvendortruck', $data);
			} else {
				$contract_no = $this->input->post('contract_no');
				$company_id = $this->input->post('company_id');
				$container_size_id = $this->input->post('container_size_id');
				$container_category_id = $this->input->post('container_category_id');
				$container_type_id = $this->input->post('container_type_id');
				$from_qty = $this->input->post('from_qty');
				$to_qty = $this->input->post('to_qty');
				$from_location_id = $this->input->post('from_location_id');
				$to_location_id = $this->input->post('to_location_id');
				$buying_currency = $this->input->post('buying_currency');
				$buying_rate = $this->input->post('buying_rate');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'contract_no' => $contract_no,
						'company_id' => $company_id,
						'container_size_id' => $container_size_id,
						'container_type_id' => $container_type_id,
						'container_category_id' => $container_category_id,
						'from_qty' => $from_qty,
						'to_qty' => $to_qty,
						'from_location_id' => $from_location_id,
						'to_location_id' => $to_location_id,
						'buying_currency' => $buying_currency,
						'buying_rate' => $buying_rate,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MVENDOR_SERVICE_CONTAINER_BUYING_RATE', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Trucking Service Vendor", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Trucking Service Vendor", 1);	
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Trucking Service Vendor');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_vendor_truck()
    {
    	$this->load->helper('comman_helper');
    	$data['data_vendor_truck'] = $this->M_master->get_data_vendor_truck()->result();
    	$this->load->view('master/v_indexvendortruck', $data);
    }

    function edit_vendor_truck()
    {
    	$this->load->helper('comman_helper');
    	$date = date('Y-m-d H:i:s');
    	$contract_no = $this->uri->segment(3);
    	$company_id = $this->uri->segment(4);
    	$container_size_id = $this->uri->segment(5);
    	$container_type_id = $this->uri->segment(6);
    	$container_category_id = $this->uri->segment(7);
    	$from_qty = $this->uri->segment(8);
    	$to_qty = $this->uri->segment(9);
    	$from_location_id = $this->uri->segment(10);
    	$to_location_id = $this->uri->segment(11);

    	// $data['from_location_id'] = $this->M_master->get_data_vendor_truck_param($contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty)->row()->FROM_LOCATION_ID;
    	// $data['to_location_id'] = $this->M_master->get_data_vendor_truck_param($contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty)->row()->TO_LOCATION_ID;
    	$data['buying_currency'] = $this->M_master->get_data_vendor_truck_param($contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)->row()->BUYING_CURRENCY;
    	$data['buying_rate'] = $this->M_master->get_data_vendor_truck_param($contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)->row()->BUYING_RATE;

    	$data['contract_no'] = $contract_no;
    	$data['company_id'] = $company_id;
    	$data['container_size_id'] = $container_size_id;
    	$data['container_type_id'] = $container_type_id;
    	$data['container_category_id'] = $container_category_id;
    	$data['from_qty'] = $from_qty;
    	$data['to_qty'] = $to_qty;
    	$data['from_location_id'] = $from_location_id;
    	$data['to_location_id'] = $to_location_id;

    	$data['data_vendor_contract'] = $this->M_master->get_data_vendor_contract()->result();
    	$data['container_size'] = $this->M_master->get_container_size()->result();
		$data['container_type'] = $this->M_master->get_container_type()->result();
		$data['container_category'] = $this->M_master->get_container_category()->result();
		$data['data_location'] = $this->M_master->get_location_master()->result();

		$this->form_validation->set_rules('contract_no', 'Contract Number', 'required');
		$this->form_validation->set_rules('company_id', 'Company ID', 'required');
		$this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		$this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		$this->form_validation->set_rules('from_qty', 'From QTY', 'required');
		$this->form_validation->set_rules('to_qty', 'To QTY', 'required');
		$this->form_validation->set_rules('from_location_id', 'From Location', 'required');
		$this->form_validation->set_rules('to_location_id', 'To Location', 'required');
		$this->form_validation->set_rules('buying_currency', 'Currency', 'required');
		$this->form_validation->set_rules('buying_rate', 'Amount', 'required');

		if (isset($_POST)) {
			$contract_no = $this->input->post('contract_no');
			$company_id = $this->input->post('company_id');
			$container_size_id = $this->input->post('container_size_id');
			$container_category_id = $this->input->post('container_category_id');
			$container_type_id = $this->input->post('container_type_id');
			$from_qty = $this->input->post('from_qty');
			$to_qty = $this->input->post('to_qty');
			$from_location_id = $this->input->post('from_location_id');
			$to_location_id = $this->input->post('to_location_id');
			$buying_currency = $this->input->post('buying_currency');
			$buying_rate = $this->input->post('buying_rate');

			// check if data is available
			$check_vendor_truck = $this->M_master->check_vendor_truck($contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id, $buying_currency, $buying_rate)->num_rows();

			$status_avail = "";
			if ($check_vendor_truck > 0) {
				$status_avail = "error";
			}

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_editvendortruck', $data);
			} elseif ($status_avail == "error") {
				$data['error_var'] = "error";
				$data['error_msg'] = "Data Exists! Please insert another data vendor trucks or cancel edit data.";
				$this->load->view('master/v_editvendortruck', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_insert = array(
						'buying_currency' => $buying_currency,
						'buying_rate' => $buying_rate,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_vendor_truck = $this->M_master->update_vendor_truck('dbo.MVENDOR_SERVICE_CONTAINER_BUYING_RATE', $data_insert, $contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id);

					if ($update_vendor_truck == FALSE) {
						throw new Exception("Error Processing Request to Updated Trucking Service Vendor", 1);	
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Updated Trucking Service Vendor", 1);	
					} else {
						$this->session->set_flashdata('success', 'Successfully Updated Trucking Service Vendor');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_selling_additional_rate()
    {
    	$this->load->helper('comman_helper');
    	$data['services'] = $this->M_master->get_service_adt()->result();
    	$data['data_currency'] = $this->M_master->get_data_currency()->result();
    	$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

    	$this->form_validation->set_rules('selling_service_id', 'Service', 'required');
    	$this->form_validation->set_rules('currency', 'Currency', 'required');
    	$this->form_validation->set_rules('amount', 'Amount', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	$selling_service_id = $this->input->post('selling_service_id');
        	// check service id
        	$check_data = $this->M_master->get_service_adt_selling_rate_param($selling_service_id)->num_rows();

        	if ($this->form_validation->run() == false) {
				$this->load->view('master/v_entrysellingadditional', $data);
			} elseif($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "This Service exists! Please entry another services.";
				$this->load->view('master/v_entrysellingadditional', $data);
			} else {
				$currency = $this->input->post('currency');
				$amount = $this->input->post('amount');

				// amount fix
				$amount = $this->input->post('amount');
				$amount_fix = str_replace(',', '', $amount);

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'selling_service_id' => $selling_service_id,
						'company_id' => $code_cmpy,
						'tariff_currency' => $currency,
						'tariff_amount' => $amount_fix,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MSELLING_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Service Additional Selling Rate", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Service Additional Selling Rate", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Service Additional Selling Rate');
						$this->db->trans_commit();
						redirect('Master/view_all_selling_additional_rate');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_selling_additional_rate()
    {
    	$this->load->helper('comman_helper');
    	$data['service_adt'] = $this->M_master->get_service_adt_selling_rate()->result();
    	$this->load->view('master/v_indexsellingadditional', $data);
    }

    function edit_selling_additional_rate()
    {
    	$this->load->helper('comman_helper');
    	$service_id = $this->uri->segment(3);

    	$data['services'] = $this->M_master->get_service_adt()->result();
    	$data['data_currency'] = $this->M_master->get_data_currency()->result();

    	$data['service_id'] = $service_id;
    	$data['currency'] = $this->M_master->get_service_adt_selling_rate_param($service_id)->row()->TARIFF_CURRENCY;
    	$data['amount'] = $this->M_master->get_service_adt_selling_rate_param($service_id)->row()->TARIFF_AMOUNT;

    	$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('selling_service_id', 'Service', 'required');
    	$this->form_validation->set_rules('currency', 'Currency', 'required');
    	$this->form_validation->set_rules('amount', 'Amount', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	$selling_service_id = $this->input->post('selling_service_id');
        	// check service id
        	$check_data = $this->M_master->get_service_adt_selling_rate_param($selling_service_id)->num_rows();

        	if ($this->form_validation->run() == false) {
				$this->load->view('Master/v_editsellingadditional', $data);
			} else {
				$currency = $this->input->post('currency');
				$amount = $this->input->post('amount');

				// amount fix
				$amount = $this->input->post('amount');
				$amount_fix = str_replace(',', '', $amount);

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'company_id' => $code_cmpy,
						'tariff_currency' => $currency,
						'tariff_amount' => $amount_fix,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_adt_service = $this->M_master->update_adt_service('dbo.MSELLING_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $data_insert, $selling_service_id);

					if ($update_adt_service == FALSE) {
						throw new Exception("Error Processing Request to Edit Service Additional Selling Rate", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Edit Service Additional Selling Rate", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Edit Service Additional Selling Rate');
						$this->db->trans_commit();
						redirect('Master/view_all_selling_additional_rate');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_competitor()
    {
    	$this->load->helper('comman_helper');

    	$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('competitor_name', 'Competitor Name', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	// competitor id
        	$temp_id = $this->M_master->get_max_competitor()->row()->id;
	    	$temp_ids = $temp_id + 1;

	    	// CR00001

	    	if ($temp_id != NULL || $temp_id != "") {
	    		if ($temp_ids < 10) {
	    			$id = "CR" . "0000" . $temp_ids;
	    		} elseif ($temp_ids == 10 || $temp_ids < 100) {
	    			$id = "CR" . "000" . $temp_ids;
	    		} elseif ($temp_ids == 100 || $temp_ids < 1000) {
	    			$id = "CR" . "00" . $temp_ids;
	    		} elseif ($temp_ids == 1000 || $temp_ids < 10000) {
	    			$id = "CR" . "0" . $temp_ids;
	    		} else {
	    			$id = "CR" . $temp_ids;
	    		}
	    	} else {
	    		$id = "CR00001";
	    	}

        	$competitor_name = $this->input->post('competitor_name');
        	// check competitor
        	$check_data = $this->M_master->get_data_competitor_param2($competitor_name)->num_rows();

        	if ($this->form_validation->run() == false) {
				$this->load->view('Master/v_entrycompetitor');
			} elseif($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Competitor already exists! Please entry another competitor.";
				$this->load->view('Master/v_entrycompetitor', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_insert = array(
						'competitor_id' => $id,
						'competitor_name' => strtoupper($competitor_name),
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MCOMPETITOR', $data_insert)) {
						throw new Exception("Error Processing Request Entry Competitor", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request Entry Competitor", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Competitor');
						$this->db->trans_commit();
						redirect('Master/view_all_competitor');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_competitor()
    {
    	$this->load->helper('comman_helper');
    	$data['data_competitor'] = $this->M_master->get_data_competitor()->result();
    	$this->load->view('Master/v_indexcompetitor', $data);
    }

    function edit_competitor()
    {
    	$this->load->helper('comman_helper');
    	$competitor_id = $this->uri->segment(3);

    	$data['competitor_name'] = $this->M_master->get_data_competitor_param($competitor_id)->row()->COMPETITOR_NAME;

    	$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('competitor_name', 'Competitor Name', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	$competitor_name = $this->input->post('competitor_name');

        	if ($this->form_validation->run() == false) {
				$this->load->view('Master/v_editcompetitor', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_insert = array(
						'competitor_name' => strtoupper($competitor_name),
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_competitor = $this->M_master->update_competitor('dbo.MCOMPETITOR', $data_insert, $competitor_id);

					if ($update_competitor == FALSE) {
						throw new Exception("Error Processing Request Edit Competitor", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request Edit Competitor", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Edit Competitor');
						$this->db->trans_commit();
						redirect('Master/view_all_competitor');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_competitor_compare()
    {
    	$this->load->helper('comman_helper');

    	$data['data_competitor'] = $this->M_master->get_data_competitor()->result();

    	$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

    	$this->form_validation->set_rules('competitor_id', 'Competitor', 'required');
    	$this->form_validation->set_rules('valid_from_date', 'Valid From Date', 'required');
    	$this->form_validation->set_rules('valid_to_date', 'Valid To Date', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	// competitor compare id
        	$temp_id = $this->M_master->get_max_competitor_compare()->row()->id;
	    	$temp_ids = $temp_id + 1;

	    	// CR00001

	    	if ($temp_id != NULL || $temp_id != "") {
	    		if ($temp_ids < 10) {
	    			$id = "CP" . "0000" . $temp_ids;
	    		} elseif ($temp_ids == 10 || $temp_ids < 100) {
	    			$id = "CP" . "000" . $temp_ids;
	    		} elseif ($temp_ids == 100 || $temp_ids < 1000) {
	    			$id = "CP" . "00" . $temp_ids;
	    		} elseif ($temp_ids == 1000 || $temp_ids < 10000) {
	    			$id = "CP" . "0" . $temp_ids;
	    		} else {
	    			$id = "CP" . $temp_ids;
	    		}
	    	} else {
	    		$id = "CP00001";
	    	}

        	if ($this->form_validation->run() == false) {
				$this->load->view('Master/v_entrycompetitorcompare', $data);
			} else {
				$competitor_id = $this->input->post('competitor_id');
				$valid_from_date = $this->input->post('valid_from_date');
				$valid_to_date = $this->input->post('valid_to_date');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'compare_id' => $id,
						'competitor_id' => $competitor_id,
						'valid_from_date' => $valid_from_date,
						'valid_to_date' => $valid_to_date,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MCOMPETITOR_COMPARE', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Competitor Compare", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Competitor Compare", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Competitor Compare');
						$this->db->trans_commit();
						redirect('Master/view_all_competitor_compare');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_competitor_compare()
    {
    	$this->load->helper('comman_helper');
    	$data['competitor_compare'] = $this->M_master->get_data_competitor_compare()->result();
    	$this->load->view('Master/v_indexcompetitorcompare', $data);
    }

    function edit_competitor_compare()
    {
    	$this->load->helper('comman_helper');
    	$compare_id = $this->uri->segment(3);

    	$data['compare_id'] = $compare_id;
    	$data['competitor_id'] = $this->M_master->get_data_competitor_compare_param($compare_id)->row()->COMPETITOR_ID;
    	$data['valid_from_date'] = $this->M_master->get_data_competitor_compare_param($compare_id)->row()->FROM_DATE;
    	$data['valid_to_date'] = $this->M_master->get_data_competitor_compare_param($compare_id)->row()->TO_DATE;

    	$data['data_competitor'] = $this->M_master->get_data_competitor()->result();

    	$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

    	$this->form_validation->set_rules('competitor_id', 'Competitor', 'required');
    	$this->form_validation->set_rules('valid_from_date', 'Valid From Date', 'required');
    	$this->form_validation->set_rules('valid_to_date', 'Valid To Date', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	if ($this->form_validation->run() == false) {
				$this->load->view('Master/v_editcompetitorcompare', $data);
			} else {
				$valid_from_date = $this->input->post('valid_from_date');
				$valid_to_date = $this->input->post('valid_to_date');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'valid_from_date' => $valid_from_date,
						'valid_to_date' => $valid_to_date,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_competitor_compare = $this->M_master->update_competitor_compare('dbo.MCOMPETITOR_COMPARE', $data_insert, $compare_id);

					if ($update_competitor_compare == FALSE) {
						throw new Exception("Error Processing Request to Edit Competitor Compare", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Edit Competitor Compare", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Edit Competitor Compare');
						$this->db->trans_commit();
						redirect('Master/view_all_competitor_compare');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_competitor_rate()
    {
    	$this->load->helper('comman_helper');

    	$data['data_compare'] = $this->M_master->get_data_competitor_compare()->result();
    	$data['container_size'] = $this->M_master->get_container_size()->result();
		$data['container_type'] = $this->M_master->get_container_type()->result();
		$data['container_category'] = $this->M_master->get_container_category()->result();
		$data['data_location'] = $this->M_master->get_location_master()->result();

		$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('compare_id', 'Compare', 'required');
		$this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		$this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		$this->form_validation->set_rules('from_qty', 'From QTY', 'required');
		$this->form_validation->set_rules('to_qty', 'To QTY', 'required');
		$this->form_validation->set_rules('from_location_id', 'From Location', 'required');
		$this->form_validation->set_rules('to_location_id', 'To Location', 'required');
		$this->form_validation->set_rules('buying_currency', 'Currency', 'required');
		$this->form_validation->set_rules('buying_rate', 'Amount', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('Master/v_entrycompetitorrate', $data);
			} else {
				$compare_id = $this->input->post('compare_id');
				$container_size_id = $this->input->post('container_size_id');
				$container_category_id = $this->input->post('container_category_id');
				$container_type_id = $this->input->post('container_type_id');
				$from_qty = $this->input->post('from_qty');
				$to_qty = $this->input->post('to_qty');
				$from_location_id = $this->input->post('from_location_id');
				$to_location_id = $this->input->post('to_location_id');
				$buying_currency = $this->input->post('buying_currency');
				$buying_rate = $this->input->post('buying_rate');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'compare_id' => $compare_id,
						'container_size_id' => $container_size_id,
						'container_type_id' => $container_type_id,
						'container_category_id' => $container_category_id,
						'from_qty' => $from_qty,
						'to_qty' => $to_qty,
						'from_location_id' => $from_location_id,
						'to_location_id' => $to_location_id,
						'buying_currency' => $buying_currency,
						'buying_rate' => $buying_rate,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MCOMPETITOR_SERVICE_CONTAINER_BUYING_RATE', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Competitor Rate", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Competitor Rate", 1);	
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Competitor Rate');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_competitor_rate()
    {
    	$this->load->helper('comman_helper');
    	$data['competitor_rate'] = $this->M_master->get_competitor_rate()->result();
    	$this->load->view('Master/v_indexcompetitorrate', $data);
    }

    function edit_competitor_rate()
    {
    	$this->load->helper('comman_helper');
    	$compare_id = $this->uri->segment(3);
    	$container_size_id = $this->uri->segment(4);
    	$container_type_id = $this->uri->segment(5);
    	$container_category_id = $this->uri->segment(6);
    	$from_qty = $this->uri->segment(7);
    	$to_qty = $this->uri->segment(8);
    	$from_location_id = $this->uri->segment(9);
    	$to_location_id = $this->uri->segment(10);

		$data['compare_id'] = $compare_id;
    	$data['container_size_id'] = $container_size_id;
    	$data['container_type_id'] = $container_type_id;
    	$data['container_category_id'] = $container_category_id;
    	$data['from_qty'] = $from_qty;
    	$data['to_qty'] = $to_qty;
    	$data['from_location_id'] = $from_location_id;
    	$data['to_location_id'] = $to_location_id;

		$data['container_size'] = $this->M_master->get_container_size()->result();
		$data['container_type'] = $this->M_master->get_container_type()->result();
		$data['container_category'] = $this->M_master->get_container_category()->result();
		$data['data_location'] = $this->M_master->get_location_master()->result();

		$data['buying_currency'] = $this->M_master->get_competitor_rate_param($compare_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)->row()->BUYING_CURRENCY;
    	$data['buying_rate'] = $this->M_master->get_competitor_rate_param($compare_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)->row()->BUYING_RATE;

		$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('compare_id', 'Compare', 'required');
		$this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		$this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		$this->form_validation->set_rules('from_qty', 'From QTY', 'required');
		$this->form_validation->set_rules('to_qty', 'To QTY', 'required');
		$this->form_validation->set_rules('from_location_id', 'From Location', 'required');
		$this->form_validation->set_rules('to_location_id', 'To Location', 'required');
		$this->form_validation->set_rules('buying_currency', 'Currency', 'required');
		$this->form_validation->set_rules('buying_rate', 'Amount', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('Master/v_editcompetitorrate', $data);
			} else {
				$buying_currency = $this->input->post('buying_currency');
				$buying_rate = $this->input->post('buying_rate');

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'buying_currency' => $buying_currency,
						'buying_rate' => $buying_rate,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$update_competitor_rate = $this->M_master->update_competitor_rate('dbo.MCOMPETITOR_SERVICE_CONTAINER_BUYING_RATE', $data_insert, $compare_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id);

					if ($update_competitor_rate == FALSE) {
						throw new Exception("Error Processing Request to Edit Competitor Rate", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Edit Competitor Rate", 1);	
					} else {
						$this->session->set_flashdata('success', 'Successfully Edit Competitor Rate');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function entry_cost_shipping()
    {
    	$this->load->helper('comman_helper');

    	$data['container_size'] = $this->M_master->get_container_size()->result();
		$data['container_type'] = $this->M_master->get_container_type()->result();
		$data['container_category'] = $this->M_master->get_container_category()->result();
		$data['data_location'] = $this->M_master->get_location_master()->result();
		$data['data_cost'] = $this->M_master->get_data_cost_do()->result();
		$data['data_calc'] = $this->M_master->get_data_calc()->result();
		$cmpy = $this->M_master->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_master->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');

		$this->form_validation->set_rules('cost_id', 'Cost ID', 'required');
		$this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		$this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		$this->form_validation->set_rules('from_qty', 'From QTY', 'required');
		$this->form_validation->set_rules('to_qty', 'To QTY', 'required');
		// $this->form_validation->set_rules('from_location_id', 'From Location', 'required');
		// $this->form_validation->set_rules('to_location_id', 'To Location', 'required');
		$this->form_validation->set_rules('cost_currency', 'Currency', 'required');
		$this->form_validation->set_rules('cost_amount', 'Amount', 'required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		$this->form_validation->set_rules('increment_qty', 'Increment QTY', 'required');

		if (isset($_POST)) {
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('master/v_entrycostshipping', $data);
			} else {
				// declare variable
				$cost_id = $this->input->post('cost_id');
				$container_size_id = $this->input->post('container_size_id');
				$container_type_id = $this->input->post('container_type_id');
				$container_category_id = $this->input->post('container_category_id');
				$from_qty = $this->input->post('from_qty');
				$to_qty = $this->input->post('to_qty');
				// $from_location_id = $this->input->post('from_location_id');
				// $to_location_id = $this->input->post('to_location_id');
				$cost_currency = $this->input->post('cost_currency');
				$cost_amount = $this->input->post('cost_amount');
				$start_date = $this->input->post('start_date');
				$end_date = $this->input->post('end_date');
				$calc_type = $this->input->post('calc_type');
				$increment_qty = $this->input->post('increment_qty');
				$cost_type = $this->M_master->get_detail_cost($cost_id)->row()->COST_TYPE;
				$cost_group = $this->M_master->get_detail_cost($cost_id)->row()->COST_GROUP;

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'cost_id' => $cost_id,
						'container_size_id' => $container_size_id,
						'container_type_id' => $container_type_id,
						'container_category_id' => $container_category_id,
						'from_qty' => $from_qty,
						'to_qty' => $to_qty,
						'cost_currency' => $cost_currency,
						'cost_amount' => $cost_amount,
						'start_date' => $start_date,
						'end_date' => $end_date,
						'calc_type' => $calc_type,
						'increment_qty' => $increment_qty,
						'cost_type_id' => $cost_type,
						'cost_group_id' => $cost_group,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MCOST_SERVICE_CONTAINER_SHIPPING_ATTRIBUTE', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Cost Shipping Data", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Cost Shipping Data", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Cost Shipping Data');
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
    }

    function view_all_cost_shipping()
    {
    	$this->load->helper('comman_helper');
    	$data['data_cost_shipping'] = $this->M_master->get_data_cost_shipping()->result();
    	$this->load->view('master/v_indexcostshipping', $data);
    }

    function view_all_customer()
    {
    	$this->load->helper('comman_helper');
    	$data['data_customer'] = $this->M_master->get_data_customer()->result();
    	$this->load->view('master/v_indexcustomer', $data);
    }

    function view_all_location()
    {
    	$this->load->helper('comman_helper');
    	$data['data_location'] = $this->M_master->get_view_location()->result();
    	$this->load->view('master/v_indexlocation', $data);
    }

    function view_cost_transfered()
    {
    	$this->load->helper('comman_helper');
    	$data['data_pic'] = $this->M_master->get_pic_cost()->result();
    	$this->load->view('master/v_indexcosttransfered', $data);
    }

    function detail_wo_pic()
    {
    	$this->load->helper('comman_helper');
    	$pic_id = $this->uri->segment(3);

    	$data['pic_id'] = $pic_id;
    	$data['data_wo'] = $this->M_master->get_wo_pic($pic_id)->result();
    	$data['pic_name'] = $this->M_master->get_name_pic($pic_id)->row()->Nm_lengkap;

    	$this->load->view('master/v_detailwopic', $data);
    }

    function detail_cost_transfered()
    {
    	$this->load->helper('comman_helper');
    	$pic_id = $this->uri->segment(3);
    	$work_order_number = $this->uri->segment(4);

    	$data['pic_id'] = $pic_id;
    	$data['work_order_number'] = $work_order_number;
    	$data['pic_name'] = $this->M_master->get_name_pic($pic_id)->row()->Nm_lengkap;

    	$data['data_cost'] = $this->M_master->get_data_cost_pic($pic_id, $work_order_number)->result();

    	$this->load->view('master/v_detailcosttransfered', $data);
    }

    function print_cost_transfered()
    {
    	$this->load->helper('comman_helper');
    	$pic_id = $this->uri->segment(3);

    	$data['pic_id'] = $pic_id;
    	$data['data_wo'] = $this->M_master->get_wo_pic($pic_id)->result();
    	$data['pic_name'] = $this->M_master->get_name_pic($pic_id)->row()->Nm_lengkap;

    	$html = $this->load->view('reports/r_costtransfer', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		// $pdf = new pdf();
		// $pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		// $pdf->SetFooter('&emsp;Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 &emsp;Jakarta Pusat 10160 Indonesia <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
		$pdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        0, // margin_left
        0, // margin right
       10, // margin top
       30, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('CostTransfer.pdf', 'I');
    }
}