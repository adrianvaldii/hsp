<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

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
		error_reporting(E_ALL & ~E_NOTICE);
		// if ($this->session->userdata('nik')=="") {
		// 	redirect('Welcome/index');
		// }
		$this->load->model('M_quotation');
		$this->nik = $this->session->userdata('nik');
		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->helper(array('url','html','form'));
	}

	function index()
	{
		$this->load->helper('comman_helper');
		$data['data_quotation'] = $this->M_quotation->get_quotation()->result();
		// pr($data['data_quotation']);
		$this->load->view('quotations/v_indexquotation', $data);
	}

	function add_quotation()
	{
		// selling trucking
		

		// $this->load->helper('comman_helper');
		// pr($data['data_trucking']);

		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;
		
		$data['data_trucking'] = $this->M_quotation->get_data_trucking($code_cmpy)->result();
		$data['data_customs'] = $this->M_quotation->get_data_customs($code_cmpy)->result();
		$data['data_location'] = $this->M_quotation->get_data_location()->result();
		$data['data_weight'] = $this->M_quotation->get_data_weight()->result();
		$data['data_ocean'] = $this->M_quotation->get_data_ocean()->result();
		$data['template'] = $this->M_quotation->get_template()->result();

		$temp_id = $this->M_quotation->get_max_id()->row()->id;
		$year_now = date('y');
		$date = date('Y-m-d H:i:s');

		$potongan_tahun = substr($temp_id, 0,2);

		if ($potongan_tahun == $year_now) {
			// echo "sama";

			$id = $temp_id + 1;
		} else {
			// echo "tidak";
			$id = $year_now . "0001";
		}

		$quote_number = substr($id, 2,5). "/" . $code_cmpy . "-QUO/" . date('m/Y');
		$data['id'] = $id;
		$data['document_number'] = $quote_number;

		$success = array();


		$this->form_validation->set_rules('quotation_number', 'Quotation Number', 'required');
		$this->form_validation->set_rules('company_id', 'Company ID', 'required');
		$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		$this->form_validation->set_rules('sales_id', 'Sales ID', 'required');
		$this->form_validation->set_rules('pic_id', 'PIC', 'required');
		$this->form_validation->set_rules('pic_name', 'PIC Name', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('template_text1', 'Template Syarat dan Kondisi', 'required');
		$this->form_validation->set_rules('template_text2', 'Template Terms and Condition', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// insert data
		if (isset($_POST)) {
			// declare variable
			$quotation_number = $this->input->post('quotation_number');
			$company_name = $this->input->post('company_name');
			$company_quo_id = $this->input->post('company_id');
			$sales_id = $this->input->post('sales_id');
			$pic_id = $this->input->post('pic_id');
			$remarks = $this->input->post('remarks');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$syarat = $this->input->post('template_text1');
			$term = $this->input->post('template_text2');

			// check quotation
			$check_quotation = $this->M_quotation->check_quotation_insert($id)->num_rows();
			// $this->load->helper('comman_helper');
			// pr($id);

			// declare variable error
			$error_trucking = "";
			$error_customs = "";
			$error_location = "";
			$error_weight = "";
			$error_ocean = "";

			if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
				$trucking = $this->input->post('trucking');
				if (count($trucking) < 1) {
					$error_trucking = "error";
				} else {
					$error_trucking = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
				$test = $this->input->post('customs');
				if (count($test) < 1) {
					$error_customs = "error";
				} else {
					$error_customs = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
				$location = $this->input->post('location');
				if (count($location) < 1) {
					$error_location = "error";
				} else {
					$error_location = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
				$weight = $this->input->post('weight');
				if (count($weight) < 1) {
					$error_weight = "error";
				} else {
					$error_weight = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
				$ocean = $this->input->post('ocean');
				if (count($ocean) < 1) {
					$error_ocean = "error";
				} else {
					$error_ocean = "ada";
				}
			}

			// entry to table quotation
			if ($this->form_validation->run() == false) {
				$this->load->view('quotations/v_addquotation', $data);
			} elseif ($check_quotation > 0) {
				$data['quotation_error'] = "error";
        		$this->load->view('quotations/v_addquotation', $data);
			} elseif ($error_trucking == "error") {
				$data['error_trucking'] = "error";
        		$this->load->view('quotations/v_addquotation', $data);
			} elseif ($error_customs == "error") {
				$data['error_customs'] = "error";
        		$this->load->view('quotations/v_addquotation', $data);
			} elseif ($error_location == "error") {
				$data['error_location'] = "error";
        		$this->load->view('quotations/v_addquotation', $data);
			} elseif ($error_weight == "error") {
				$data['error_weight'] = "error";
        		$this->load->view('quotations/v_addquotation', $data);
			} elseif ($error_ocean == "error") {
				$data['error_ocean'] = "error";
        		$this->load->view('quotations/v_addquotation', $data);
			} else {
				$this->db->trans_begin();
				try {
					$trucking = $this->input->post('trucking');
					$test = $this->input->post('customs');
					$location = $this->input->post('location');
					$weight = $this->input->post('weight');
					// $ocean = $this->input->post('ocean');

					$services = array();

					if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
						array_push($services, "SS01");
					}
					if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
						array_push($services, "SS02");
					}
					if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
						array_push($services, "SS04");
					}
					if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
						array_push($services, "SS05");
					}
					if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
						array_push($services, "SS03");
					}

					$data_services = array();
					$data_quotation = array();
					$data_trucking = array();
					$data_customs = array();
					$data_location = array();
					$data_weight = array();
					$data_ocean = array();
					
					// $this->load->helper('comman_helper');
					// pr($services[0]);
					
					// input quotation services
					for ($i=0; $i < count($services); $i++) { 
						$data_services[] = array(
							'quotation_number' => $id,
							'selling_service_id' => $services[$i],
							'revesion_number' => '0'
						);
					}

					if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE', $data_services)) {
						throw new Exception("Error Processing Request to Entry Quotation Services", 1);
					}
					// array_push($success, "Successfully insert data quotation services!");

					unset($services);

					// set to input quotation
					$data_quotation = array(
						'quotation_number' => $id,
						'quotation_document_number' => $quote_number,
						'company_id' => $code_cmpy,
						'revesion_number' => '0',
						'quotation_date' => $start_date,
						'quotation_periode_start' => $start_date,
						'quotation_periode_end' => $end_date,
						'customer_id' => $company_quo_id,
						'approval_status' => 'N',
						'user_id' => $this->nik,
						'user_date' => $date,
						'template_text1' => $syarat,
						'template_text2' => $term,
						'marketing_id' => $sales_id,
						'status_agreement' => '0',
						'customer_pic_id' => $pic_id
					);
					if (!$this->db->insert('dbo.TRQUOTATION', $data_quotation)) {
						throw new Exception("Error Processing Request to Entry Quotation", 1);
					}
					// array_push($success, "Successfully insert data quotation!");

					unset($data_quotation);

					// insert to approval status
					$data_trappr = array(
							'TRANSACTION_NUMBER' => $id,
							'DOCUMENT_ID' => 'D1002',
							'REVISION_NUMBER' => '0',
							'COMPANY_ID' => $code_cmpy,
							'REQUEST_APPROVAL_DATE' => $date,
							'APPROVAL_STATUS' => 'N'
						);
					if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_trappr)) {
						throw new Exception("Error Processing Request to Entry Approval Quotation", 1);
						
					}

					unset($data_trappr);

					// check email who must approve quotation
					$data_nik = array();
					$data_email = array();
					$check_approval_satu = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL1;
					$check_approval_dua = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL2;
					$check_approval_tiga = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL3;

					// $this->load->helper('comman_helper');
					// pr($check_approval_dua);

					if ($check_approval_satu == 'Y') {
						$get_nik_appr_satu = $this->M_quotation->get_nik_appr('D1002', '1')->result();
						foreach ($get_nik_appr_satu as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_dua == 'Y') {
						$get_nik_appr_dua = $this->M_quotation->get_nik_appr('D1002', '2')->result();
						foreach ($get_nik_appr_dua as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_tiga == 'Y') {
						$get_nik_appr_tiga = $this->M_quotation->get_nik_appr('D1002', '3')->result();
						foreach ($get_nik_appr_tiga as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					// get email
					for ($nik=0; $nik < count($data_nik); $nik++) { 
						$temp_email = $this->M_quotation->get_email($data_nik[$nik])->row()->email;
						array_push($data_email, $temp_email);
					}

					// $this->load->helper('comman_helper');
					// pr($data_email);

					// sent email to pic approval
					$config['protocol'] = "smtp";
					$config['smtp_host'] = "192.168.11.220";
					$config['smtp_port'] = "25";
					$config['charset'] = "utf-8";
					// $config['mailtype'] = "html";

					// for ($e=0; $e < count($data_email); $e++) { 
					// 	$this->email->initialize($config);

					// 	$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
					// 	// $this->email->to('valdi.abrar@hanomansp.com');
					// 	$this->email->to($data_email[$e]);

					// 	$this->email->subject('Quotation Approval');
					// 	$data = array('quotation' => $id, 'customer' => $company_name);
					// 	$view = $this->load->view('layouts/v_email.php', $data, true);
					// 	$this->email->message($view);
					// 	$this->email->set_mailtype('html');

					// 	$this->email->send();
					// }

					$this->email->initialize($config);
					$this->email->set_mailtype('html');

					$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
					// $this->email->to('valdi.abrar@hanomansp.com');
					$this->email->to($data_email);

					$this->email->subject('Quotation Approval');
					$data = array('quotation' => $id, 'customer' => $company_name);
					$view = $this->load->view('layouts/v_email.php', $data, true);
					$this->email->message($view);
					$this->email->send();

					// if ($this->email->send()) {
			  //       	echo 'Email sent.';
			  //       } else {
			  //           show_error($this->email->print_debugger());
			  //       }

			  //       die();

			        $this->session->set_flashdata('redirect', 'http://192.168.11.31/hsp/Approval/detail_approval_quotation/'.$id.'/D1002');

					if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
						$trucking = $this->input->post('trucking');

						$data_costtrucking = $trucking; // create copy to delete dups from
						$newtrucking = array();

						for( $i=0; $i<count($trucking); $i++ ) {

						    if ( in_array( array( $trucking[$i]['size'], $trucking[$i]['to'], $trucking[$i]['type'], $trucking[$i]['category'], $trucking[$i]['from'], $trucking[$i]['company_id'] ), $newtrucking ) ) {
						    	unset($newtrucking[$i]);
						    	unset($data_costtrucking[$i]);
						    }
						    else {
						    	$newtrucking[$i][] = $trucking[$i]['size'];
						    	$newtrucking[$i][] = $trucking[$i]['to'];
						    	$newtrucking[$i][] = $trucking[$i]['type'];
						    	$newtrucking[$i][] = $trucking[$i]['category'];
						    	$newtrucking[$i][] = $trucking[$i]['from'];
						    	$newtrucking[$i][] = $trucking[$i]['company_id'];
						    }

						}

						$this->load->helper('comman_helper');
						// pr($newtrucking);
						// pr($data_costtrucking);
						// pr($trucking);

						// input quotation trucking
						foreach ($trucking as $value) {
							$data_trucking[] = array(
								'quotation_number' => $id,
								'company_id' => $code_cmpy,
								'revesion_number' => '0',
								'selling_service_id' => $value['selling_service'],
								'container_size_id' => $value['size'],
								'container_type_id' => $value['type'],
								'container_category_id' => $value['category'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'from_qty' => $value['from_qty'],
								'to_qty' => $value['to_qty'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						foreach ($data_costtrucking as $value1) {

							// add cost trucking selling
							$data_cost_trucking = $this->M_quotation->get_trucking_cost($value1['selling_service'], $value1['type'], $value1['category'], $value1['from'], $value1['to'], $value1['size'], $value['company_id']);
							if ($data_cost_trucking->num_rows() < 1) {
								unset($data_cost_trucking);
							} else {
								for ($j=0; $j < $data_cost_trucking->num_rows(); $j++) { 
									$cost_insert_trucking[] = array(
										'quotation_number' => $id,
										'company_id' => $data_cost_trucking->row($j)->COMPANY_ID,
										'revesion_number' => '0',
										'selling_service_id' => $data_cost_trucking->row($j)->SELLING_SERVICE_ID,
										'container_size_id' => $data_cost_trucking->row($j)->CONTAINER_SIZE_ID,
										'container_type_id' => $data_cost_trucking->row($j)->CONTAINER_TYPE_ID,
										'container_category_id' => $data_cost_trucking->row($j)->CONTAINER_CATEGORY_ID,
										'from_qty' => $data_cost_trucking->row($j)->FROM_QTY,
										'to_qty' => $data_cost_trucking->row($j)->TO_QTY,
										'from_location_id' => $data_cost_trucking->row($j)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_trucking->row($j)->TO_LOCATION_ID,
										'start_date' => $data_cost_trucking->row($j)->START_DATE,
										'end_date' => $data_cost_trucking->row($j)->END_DATE,
										'cost_id' => $data_cost_trucking->row($j)->COST_ID,
										'cost_type_id' => $data_cost_trucking->row($j)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_trucking->row($j)->COST_GROUP_ID,
										'calc_type' => $data_cost_trucking->row($j)->CALC_TYPE,
										'cost_currency' => $data_cost_trucking->row($j)->COST_CURRENCY,
										'cost_amount' => $data_cost_trucking->row($j)->COST_AMOUNT,
										'increment_qty' => $data_cost_trucking->row($j)->INCREMENT_QTY,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}

								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE', $cost_insert_trucking)) {
									throw new Exception("Error Processing Request to Entry Trucking Cost Quotation", 1);
								}
								unset($data_cost_trucking);
								unset($cost_insert_trucking);
							}
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking)) {
							throw new Exception("Error Processing Request to Entry Trucking Quotation", 1);
						}
						// array_push($success, "Successfully insert data quotation trucking!");
						unset($data_trucking);
					}

					if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
						$test = $this->input->post('customs');

						$data_costcustoms = $test; // create copy to delete dups from
						$newarray = array();

						for( $i=0; $i<count($test); $i++ ) {

						    if ( in_array( array($test[$i]['line_customs'], $test[$i]['size_customs'], $test[$i]['from_customs'], $test[$i]['type_customs'], $test[$i]['kind_customs'], $test[$i]['category_customs'] ), $newarray ) ) {
						    	unset($newarray[$i]);
						    	unset($data_costcustoms[$i]);
						    }
						    else {
						    	$newarray[$i][] = $test[$i]['line_customs'];
						    	$newarray[$i][] = $test[$i]['size_customs'];
						    	$newarray[$i][] = $test[$i]['from_customs'];
						    	$newarray[$i][] = $test[$i]['type_customs'];
						    	$newarray[$i][] = $test[$i]['kind_customs'];
						    	$newarray[$i][] = $test[$i]['category_customs'];
						    }

						}
						$this->load->helper('comman_helper');
						// pr($data_costcustoms);
						// pr($test);
						// pr($newarray);

						// add selling
						foreach ($test as $value) {
							$data_customs[] = array(
								'quotation_number' => $id,
								'company_id' => $code_cmpy,
								'revesion_number' => '0',
								'custom_location_id' => $value['from_customs'],
								'custom_line_id' => $value['line_customs'],
								'custom_kind_id' => $value['kind_customs'],
								'selling_service_id' => $value['selling_customs'],
								'container_size_id' => $value['size_customs'],
								'container_type_id' => $value['type_customs'],
								'container_category_id' => $value['category_customs'],
								'selling_currency' => $value['currency_customs'],
								'selling_offering_rate' => $value['offer_customs'],
								'selling_standart_rate' => $value['amount_customs'],
								'from_qty' => $value['from_qty_customs'],
								'to_qty' => $value['to_qty_customs'],
								'calc_type' => $value['calc_customs'],
								'increment_qty' => $value['increment_customs'],
								'start_date' => $value['start_customs'],
								'end_date' => $value['end_customs'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						// add cost trucking
						foreach ($data_costcustoms as $value1) {

							// get data cost of selling
							$data_all_cost = $this->M_quotation->get_customs_cost($value1['selling_customs'], $value1['type_customs'], $value1['category_customs'], $value1['size_customs'], $value1['from_customs'], $value1['line_customs'], $value1['kind_customs'], $value1['company_id']);

							if ($data_all_cost->num_rows() < 1) {
								unset($data_all_cost);
							} else {
								for ($k=0; $k < $data_all_cost->num_rows(); $k++) { 
									$cost_insert_customs[] = array(
										'quotation_number' => $id,
										'company_id' => $data_all_cost->row($k)->COMPANY_ID,
										'revesion_number' => '0',
										'selling_service_id' => $data_all_cost->row($k)->SELLING_SERVICE_ID,
										'custom_location_id' => $data_all_cost->row($k)->CUSTOM_LOCATION_ID,
										'custom_kind_id' => $data_all_cost->row($k)->CUSTOM_KIND_ID,
										'custom_line_id' => $data_all_cost->row($k)->CUSTOM_LINE_ID,
										'container_size_id' => $data_all_cost->row($k)->CONTAINER_SIZE_ID,
										'container_type_id' => $data_all_cost->row($k)->CONTAINER_TYPE_ID,
										'container_category_id' => $data_all_cost->row($k)->CONTAINER_CATEGORY_ID,
										'cost_id' => $data_all_cost->row($k)->COST_ID,
										'start_date' => $data_all_cost->row($k)->START_DATE,
										'end_date' => $data_all_cost->row($k)->END_DATE,
										'from_qty' => $data_all_cost->row($k)->FROM_QTY,
										'to_qty' => $data_all_cost->row($k)->TO_QTY,
										'calc_type' => $data_all_cost->row($k)->CALC_TYPE,
										'cost_type_id' => $data_all_cost->row($k)->COST_TYPE_ID,
										'cost_group_id' => $data_all_cost->row($k)->COST_GROUP_ID,
										'cost_currency' => $data_all_cost->row($k)->COST_CURRENCY,
										'cost_amount' => $data_all_cost->row($k)->COST_AMOUNT,
										'increment_qty' => $data_all_cost->row($k)->INCREMENT_QTY,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}
								// insert data cost
								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $cost_insert_customs)) {
									throw new Exception("Error Processing Request to Entry Cost Customs Quotation", 1);
								}
								unset($data_all_cost);
								unset($cost_insert_customs);
							} 
						}

						// insert data selling
						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_customs)) {
							throw new Exception("Error Processing Request to Entry Customs Quotation", 1);
						}
						// array_push($success, "Successfully insert data quotation customs!");
						unset($data_customs);
					}

					if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
						$location = $this->input->post('location');

						$data_costlocation = $location; // create copy to delete dups from
						$newlocation = array();

						for( $i=0; $i<count($location); $i++ ) {

						    if ( in_array( array( $location[$i]['from'], $location[$i]['to'], $location[$i]['truck'] ), $newlocation ) ) {
						    	unset($newlocation[$i]);
						    	unset($data_costlocation[$i]);
						    }
						    else {
						    	$newlocation[$i][] = $location[$i]['from'];
						    	$newlocation[$i][] = $location[$i]['to'];
						    	$newlocation[$i][] = $location[$i]['truck'];
						    }

						}

						// $this->load->helper('comman_helper');
						// pr($location);

						// add selling location
						foreach ($location as $value) {
							$data_location[] = array(
								'quotation_number' => $id,
								'company_id' => $code_cmpy,
								'revesion_number' => '0',
								'selling_service_id' => $value['selling_service'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'truck_kind_id' => $value['truck'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'distance' => $value['distance'],
								'distance_per_litre' => $value['distanceliter'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						// add cost trucking
						foreach ($data_costlocation as $value1) {

							// get data cost of selling
							$data_cost_location = $this->M_quotation->get_location_cost($value1['selling_service'], $value1['from'], $value1['to'], $value1['truck']);

							if ($data_cost_location->num_rows() < 1) {
								unset($data_cost_location);
							} else {
								for ($k=0; $k < $data_cost_location->num_rows(); $k++) { 
									$cost_insert_location[] = array(
										'quotation_number' => $id,
										'company_service_id' => $data_cost_location->row($k)->COMPANY_SERVICE_ID,
										'revesion_number' => '0',
										'selling_service_id' => $data_cost_location->row($k)->SELLING_SERVICE_ID,
										'from_location_id' => $data_cost_location->row($k)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_location->row($k)->TO_LOCATION_ID,
										'cost_id' => $data_cost_location->row($k)->COST_ID,
										'truck_id' => $data_cost_location->row($k)->TRUCK_ID,
										'start_date' => $data_cost_location->row($k)->START_DATE,
										'end_date' => $data_cost_location->row($k)->END_DATE,
										'increment_qty' => $data_cost_location->row($k)->INCREMENT_QTY,
										'calc_type' => $data_cost_location->row($k)->CALC_TYPE,
										'cost_type_id' => $data_cost_location->row($k)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_location->row($k)->COST_GROUP_ID,
										'cost_currency' => $data_cost_location->row($k)->COST_CURRENCY,
										'cost_amount' => $data_cost_location->row($k)->COST_AMOUNT,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}
								// insert data cost
								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_LOCATION_ATTRIBUTE', $cost_insert_location)) {
									throw new Exception("Error Processing Request Cost Non Trailler Quotation", 1);
									
								}
								unset($data_cost_location);
								unset($cost_insert_location);
							} 
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE', $data_location)) {
							throw new Exception("Error Processing Request to Entry Non Trailler Quotation", 1);
						}
						// array_push($success, "Successfully insert data quotation location!");
						unset($data_location);
					}

					if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
						$weight = $this->input->post('weight');

						$data_costweight = $weight; // create copy to delete dups from
						$newweight = array();

						for( $i=0; $i<count($weight); $i++ ) {

						    if ( in_array( array( $weight[$i]['from'], $weight[$i]['to'] ), $newweight ) ) {
						    	unset($newweight[$i]);
						    	unset($data_costweight[$i]);
						    }
						    else {
						    	$newweight[$i][] = $weight[$i]['from'];
						    	$newweight[$i][] = $weight[$i]['to'];
						    }

						}

						// $this->load->helper('comman_helper');
						// pr($weight);

						foreach ($weight as $value) {
							$data_weight[] = array(
								'quotation_number' => $id,
								'company_id' => $code_cmpy,
								'revesion_number' => '0',
								'selling_service_id' => $value['selling_service'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'from_weight' => $value['from_weight'],
								'to_weight' => $value['to_weight'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'measurement_unit' => $value['measurement'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						// add cost weight
						foreach ($data_costweight as $value1) {

							// get data cost of selling
							$data_cost_weight = $this->M_quotation->get_weight_cost($value1['selling_service'], $value1['from'], $value1['to']);

							if ($data_cost_weight->num_rows() < 1) {
								unset($data_cost_weight);
							} else {
								for ($k=0; $k < $data_cost_weight->num_rows(); $k++) { 
									$cost_insert_weight[] = array(
										'quotation_number' => $id,
										'company_service_id' => $data_cost_weight->row($k)->COMPANY_SERVICE_ID,
										'revesion_number' => '0',
										'selling_service_id' => $data_cost_weight->row($k)->SELLING_SERVICE_ID,
										'from_location_id' => $data_cost_weight->row($k)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_weight->row($k)->TO_LOCATION_ID,
										'from_weight' => $data_cost_weight->row($k)->FROM_WEIGHT,
										'to_weight' => $data_cost_weight->row($k)->TO_WEIGHT,
										'cost_id' => $data_cost_weight->row($k)->COST_ID,
										'start_date' => $data_cost_weight->row($k)->START_DATE,
										'end_date' => $data_cost_weight->row($k)->END_DATE,
										'increment_qty' => $data_cost_weight->row($k)->INCREMENT_QTY,
										'calc_type' => $data_cost_weight->row($k)->CALC_TYPE,
										'cost_type_id' => $data_cost_weight->row($k)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_weight->row($k)->COST_GROUP_ID,
										'cost_currency' => $data_cost_weight->row($k)->COST_CURRENCY,
										'cost_amount' => $data_cost_weight->row($k)->COST_AMOUNT,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}
								// insert data cost
								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_WEIGHT_ATTRIBUTE', $cost_insert_weight)) {
									throw new Exception("Error Processing Request to Entry Cost Weight Measurement Quotation", 1);
								}
								unset($data_cost_weight);
								unset($cost_insert_weight);
							} 
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE', $data_weight)) {
							throw new Exception("Error Processing Request to Entry Weight Measurement Quotation", 1);
						}
						// array_push($success, "Successfully insert data quotation weight!");
						unset($data_weight);
					}

					if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
						$ocean = $this->input->post('ocean');

						$data_costocean = $ocean; // create copy to delete dups from
						$newocean = array();

						for( $i=0; $i<count($ocean); $i++ ) {

						    if ( in_array( array( $ocean[$i]['size'], $ocean[$i]['to'], $ocean[$i]['type'], $ocean[$i]['category'], $ocean[$i]['from'], $ocean[$i]['charge'] ), $newocean ) ) {
						    	unset($newocean[$i]);
						    	unset($data_costocean[$i]);
						    }
						    else {
						    	$newocean[$i][] = $ocean[$i]['size'];
						    	$newocean[$i][] = $ocean[$i]['to'];
						    	$newocean[$i][] = $ocean[$i]['type'];
						    	$newocean[$i][] = $ocean[$i]['category'];
						    	$newocean[$i][] = $ocean[$i]['from'];
						    	$newocean[$i][] = $ocean[$i]['charge'];
						    }

						}

						$this->load->helper('comman_helper');
						// pr($newtrucking);
						// pr($data_costtrucking);
						// pr($trucking);

						// input quotation trucking
						foreach ($ocean as $value) {
							$data_ocean[] = array(
								'quotation_number' => $id,
								'company_id' => $code_cmpy,
								'revesion_number' => '0',
								'selling_service_id' => $value['selling_service'],
								'container_size_id' => $value['size'],
								'container_type_id' => $value['type'],
								'container_category_id' => $value['category'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'from_qty' => $value['from_qty'],
								'to_qty' => $value['to_qty'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'user_id' => $this->nik,
								'user_date' => $date,
								'charge_id' => $value['charge']
							);
						}

						foreach ($data_costocean as $value1) {

							// add cost trucking selling
							$data_cost_ocean = $this->M_quotation->get_ocean_cost($value1['selling_service'], $value1['type'], $value1['category'], $value1['from'], $value1['to'], $value1['size'], $value1['charge']);
							// $this->load->helper('comman_helper');
							// pr($data_cost_ocean->num_rows());

							if ($data_cost_ocean->num_rows() < 1) {
								unset($data_cost_ocean);
							} else {
								for ($j=0; $j < $data_cost_ocean->num_rows(); $j++) { 
									$cost_insert_ocean[] = array(
										'quotation_number' => $id,
										'company_service_id' => $data_cost_ocean->row($j)->COMPANY_SERVICE_ID,
										'revesion_number' => '0',
										'selling_service_id' => $data_cost_ocean->row($j)->SELLING_SERVICE_ID,
										'container_size_id' => $data_cost_ocean->row($j)->CONTAINER_SIZE_ID,
										'container_type_id' => $data_cost_ocean->row($j)->CONTAINER_TYPE_ID,
										'container_category_id' => $data_cost_ocean->row($j)->CONTAINER_CATEGORY_ID,
										'from_qty' => $data_cost_ocean->row($j)->FROM_QTY,
										'to_qty' => $data_cost_ocean->row($j)->TO_QTY,
										'from_location_id' => $data_cost_ocean->row($j)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_ocean->row($j)->TO_LOCATION_ID,
										'start_date' => $data_cost_ocean->row($j)->START_DATE,
										'end_date' => $data_cost_ocean->row($j)->END_DATE,
										'cost_id' => $data_cost_ocean->row($j)->COST_ID,
										'cost_type_id' => $data_cost_ocean->row($j)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_ocean->row($j)->COST_GROUP_ID,
										'calc_type' => $data_cost_ocean->row($j)->CALC_TYPE,
										'cost_currency' => $data_cost_ocean->row($j)->COST_CURRENCY,
										'cost_amount' => $data_cost_ocean->row($j)->COST_AMOUNT,
										'increment_qty' => $data_cost_ocean->row($j)->INCREMENT_QTY,
										'user_id' => $this->nik,
										'user_date' => $date,
										'charge_id' => $data_cost_ocean->row($j)->CHARGE_ID,
										'cost_type_id' => $data_cost_ocean->row($j)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_ocean->row($j)->COST_GROUP_ID,
										'cost_id' => $data_cost_ocean->row($j)->COST_ID
									);
								}

								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE', $cost_insert_ocean)) {
									throw new Exception("Error Processing Request to Entry Cost Ocean Freight Quotation", 1);
								}
								unset($data_cost_ocean);
								unset($cost_insert_ocean);
							}
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE', $data_ocean)) {
							throw new Exception("Error Processing Request to Entry Ocean Freight Quotation", 1);
						}
						// array_push($success, "Successfully insert data quotation ocean freight!");
						unset($data_ocean);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Created Quotation", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully Created Quotation!");
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

	function cost_detail_trucking()
	{
		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;
		$selling_service = $this->uri->segment(3);
		$type = $this->uri->segment(4);
		$category = $this->uri->segment(5);
		$from_location = $this->uri->segment(6);
		$to_location = $this->uri->segment(7);
		$size = $this->uri->segment(8);
		$company = $this->uri->segment(9);

		$data['data_cost'] = $this->M_quotation->get_trucking_detail($selling_service, $type, $category, $from_location, $to_location, $size, $company)->result();

		$this->load->view('quotations/v_costdetailtrucking', $data);
	}

	function cost_detail_customs()
	{
		$selling_service = $this->uri->segment(3);
		$type = $this->uri->segment(4);
		$category = $this->uri->segment(5);
		$size = $this->uri->segment(6);
		$customs_from = $this->uri->segment(7);
		$line = $this->uri->segment(8);
		$kind = $this->uri->segment(9);
		$company = $this->uri->segment(10);

		$data['data_cost'] = $this->M_quotation->get_customs_detail($selling_service, $type, $category, $size, $customs_from, $line, $kind, $company)->result();

		$this->load->view('quotations/v_costdetailcustoms', $data);
	}

	function cost_detail_location()
	{
		$selling_service = $this->uri->segment(3);
		$from = $this->uri->segment(4);
		$to = $this->uri->segment(5);
		$truck = $this->uri->segment(6);

		$data['data_cost'] = $this->M_quotation->get_location_detail($selling_service, $from, $to, $truck)->result();

		$this->load->view('quotations/v_costdetaillocation', $data);
	}

	function cost_detail_weight()
	{
		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);

		$data['data_cost'] = $this->M_quotation->get_weight_detail($from, $to)->result();

		$this->load->view('quotations/v_costdetailweight', $data);
	}

	function vendor_detail()
	{
		$this->load->helper('comman_helper');
		$from_location = $this->uri->segment(3);
		$to_location = $this->uri->segment(4);
		$type = $this->uri->segment(5);
		$size= $this->uri->segment(6);
		$category = $this->uri->segment(7);
		$from_qty = $this->uri->segment(8);
		$to_qty = $this->uri->segment(9);

		$data['data_vendor'] = $this->M_quotation->get_data_vendor($from_location, $to_location, $type, $size, $category)->result();
		// pr($data['data_vendor']);
		$data['floor_price'] = $this->M_quotation->get_floor_price($from_location, $to_location, $type, $size, $category, $from_qty, $to_qty)->row()->FLOOR_PRICE;
		$data['currency'] = $this->M_quotation->get_floor_price($from_location, $to_location, $type, $size, $category, $from_qty, $to_qty)->row()->TARIFF_CURRENCY;
		$data['market_price'] = $this->M_quotation->get_floor_price($from_location, $to_location, $type, $size, $category, $from_qty, $to_qty)->row()->MARKET_PRICE;

		$this->load->view('quotations/v_vendordetail', $data);
	}

	function search_customer()
	{
		$kode = $this->input->get('term');
		$data['customer'] = $this->M_quotation->get_customer($kode)->result();

		foreach ($data['customer'] as $key => $value) {
			$temp_customer['value'] = $value->COMPANY_NAME;
			$temp_customer['company_id'] = $value->COMPANY_ID;
			$temp_customer['customer_id'] = $value->CUSTOMER_ID;
			$temp_customer['customer_name'] = $value->CUSTOMER_NAME;
			$result_customer[] = $temp_customer;
		}
		// $this->load->helper('comman_helper');
		// pr($result_location);
		echo json_encode($result_customer);
	}

	function print_quotation_indonesia()
	{
		$quotation_number = $this->uri->segment(3);

		$data['template'] = $this->M_quotation->get_template_quotation($quotation_number)->result();
		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['date_quotation'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DATE;
		$customer_name_get = $this->M_quotation->get_quotation_param_full2($quotation_number)->result();
		$customer_name_temp = array();
		foreach ($customer_name_get as $key => $value) {
			array_push($customer_name_temp, $value->NAME);
		}
		$data['name'] = rtrim(implode(", ", $customer_name_temp));

		$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
		$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
		$pic_namdep = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
		if ($pic_namdep == "Mr.") {
			$data['pic_namdep'] = "Bapak";
		} else {
			$data['pic_namdep'] = "Ibu";
		}
		$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
		$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;

		$service_get = $this->M_quotation->get_service_quotation($quotation_number)->result();
		$service_temp = array();
		$service_delete = array();
		foreach ($service_get as $key => $value) {
			array_push($service_temp, $value->NAME);
		}

		for ($i=0; $i < count($service_temp); $i++) { 
			$temp = substr($service_temp[$i], 0, (strlen($service_temp[$i]) - 7));
			array_push($service_delete, $temp);
		}
		$data['service'] = rtrim(implode(", ", $service_delete));

		// echo $data['customer_name'];

		// custom service jakarta
		$data['cost_custom_jakarta'] = $this->M_quotation->get_all_data_custom_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_custom_jakarta'] = $this->M_quotation->get_tarif_amount_custom_jakarta($quotation_number)->result();


		$this->load->helper('currency_helper');

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);

		// container custom jakarta		
		if (!$data['cost_custom_jakarta']) {
			$hasil_custom_jakarta = array();
		} else {
			// select data cost container custom jakarta
			foreach ($data['cost_custom_jakarta'] as $key => $value) {
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['FROM_QTY'] = $value->FROM_QTY;
				$print_data['TO_QTY'] = $value->TO_QTY;
				$print_data['CALC_TYPE'] = $value->CALC_TYPE;
				$print_data['CALC_NAME'] = $value->CALC_NAME;
				$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['END_DATE'] = $value->END_DATE;

				foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_20_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_20_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_40_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_40_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_4H_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_4H_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_45_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_45_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($print_data['TARIF_20_SELLING'])) {
					$print_data['TARIF_20_SELLING'] = currency(0);
					$print_data['TARIF_20_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_40_SELLING'])) {
					$print_data['TARIF_40_SELLING'] = currency(0);
					$print_data['TARIF_40_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_4H_SELLING'])) {
					$print_data['TARIF_4H_SELLING'] = currency(0);
					$print_data['TARIF_4H_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_45_SELLING'])) {
					$print_data['TARIF_45_SELLING'] = currency(0);
					$print_data['TARIF_45_OFFERING'] = currency(0);
				}

				$hasil_custom_jakarta[] = $print_data;
			}
		}

		// check data tariff_amount custom jakarta
		foreach ($hasil_custom_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_20_SELLING']);
			} 

			if (!$this->M_quotation->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_40_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_45_SELLING']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_custom_jakarta[$key]['TARIF_20_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_20_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_40_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_40_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_4H_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_45_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_45_SELLING'] = currency(0);
			}

		}

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);
		$data['hasil_custom_jakarta'] = $hasil_custom_jakarta;

		// location
		$data['hasil_location'] = $this->M_quotation->get_data_location_jakarta($quotation_number)->result();

		// trucking service
		// container service jakarta
		$data['cost_container_jakarta'] = $this->M_quotation->get_all_data_container_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_jakarta'] = $this->M_quotation->get_tarif_amount_jakarta($quotation_number)->result();
		$data['tarif_weight_jakarta'] = $this->M_quotation->get_tarif_weight_jakarta($quotation_number)->result();

		// container cost jakarta
		if (!$data['cost_container_jakarta']) {
			$hasil_jakarta = array();
		} else {
			// select cost continer jakarta
			foreach ($data['cost_container_jakarta'] as $key => $value) {
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['FROM_QTY'] = $value->FROM_QTY;
				$test_data['TO_QTY'] = $value->TO_QTY;
				$test_data['CALC_TYPE'] = $value->CALC;
				$test_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$test_data['START_DATE'] = $value->START_DATE;
				$test_data['END_DATE'] = $value->END_DATE;
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				
				foreach ($data['tarif_amount_jakarta'] as $key1 => $value1) {
					
					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_20'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_40'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_4H'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_45'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($test_data['TARIF_20'])) {
					$test_data['TARIF_20'] = currency(0);
				}

				if (empty($test_data['TARIF_40'])) {
					$test_data['TARIF_40'] = currency(0);
				}

				if (empty($test_data['TARIF_4H'])) {
					$test_data['TARIF_4H'] = currency(0);
				}

				if (empty($test_data['TARIF_45'])) {
					$test_data['TARIF_45'] = currency(0);
				}

				$hasil_jakarta[] = $test_data;
			}
		}

		// check data tariff_amount jakarta
		foreach ($hasil_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_container($quotation_number, '20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_quotation->check_data_container($quotation_number, '40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_jakarta[$key]['TARIF_20'])) {
					$hasil_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_40'])) {
					$hasil_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_4H'])) {
					$hasil_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_45'])) {
					$hasil_jakarta[$key]['TARIF_45'] = currency(0);
			}

		} //end of trucking service
		$data['hasil_jakarta'] = $hasil_jakarta;

		// weight
		$data['hasil_weight_jakarta'] = $this->M_quotation->get_data_weight_jakarta($quotation_number)->result();

		$data['customer_count'] = $this->M_quotation->get_quotation_param_full($quotation_number)->num_rows();
		$data['customer_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->result();
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();

		// $this->load->helper('comman_helper');
		// pr($data['count_customs']);

		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		// $this->load->view('quotations/v_detailquotation', $data);
		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;
		$cmpy_address = $this->M_quotation->get_code($cmpy)->row()->ADDRESS;

		$html = $this->load->view('reports/r_quotationindonesia', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		$pdf->SetFooter('&emsp;'.$cmpy_address .' <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
		// $pdf->SetHeader('||Page {PAGENO} of {nb}');
		$pdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        0, // margin_left
        0, // margin right
       40, // margin top
       30, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('Quotation.pdf', 'I');
	}

	function print_quotation()
	{
		// $this->load->helper('comman_helper');
		$quotation_number = $this->uri->segment(3);

		// echo $this->input->post('bahasa');
		// die();

		// get checklist
		$data['remarks'] = "no";
		$data['qty'] = "no";
		$check = $this->input->post('check');

		$bahasa = $this->input->post('bahasa');

		// if ($check != NULL) {
		// 	echo "yes";
		// } else {
		// 	echo "no";
		// }

		// die();

		if ($check != NULL && in_array('remarks', $_POST['check'])) {
			$data['remarks'] = "yes";
		} else {
			$data['remarks'] = "no";
		}

		if ($check != NULL && in_array('qty', $_POST['check'])) {
			$data['qty'] = "yes";
		} else {
			$data['qty'] = "no";
		}

		$data['template'] = $this->M_quotation->get_template_quotation($quotation_number)->result();
		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['date_quotation'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DATE;
		$customer_name_get = $this->M_quotation->get_quotation_param_full2($quotation_number)->result();
		$customer_name_temp = array();
		foreach ($customer_name_get as $key => $value) {
			array_push($customer_name_temp, $value->NAME);
		}
		$data['name'] = rtrim(implode(", ", $customer_name_temp));

		if ($bahasa == "inggris") {
			$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
			$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
			$data['pic_namdep'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
			$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
			$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;
		} elseif ($bahasa == "indo") {
			$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
			// pr($customer_pic_id);
			$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
			$pic_namdep = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
			if ($pic_namdep == "Mr.") {
				$data['pic_namdep'] = "Bapak";
			} else {
				$data['pic_namdep'] = "Ibu";
			}
			$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
			$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;
		}

		$service_get = $this->M_quotation->get_service_quotation($quotation_number)->result();
		$service_temp = array();
		$service_delete = array();
		foreach ($service_get as $key => $value) {
			array_push($service_temp, $value->NAME);
		}

		for ($i=0; $i < count($service_temp); $i++) { 
			$temp = substr($service_temp[$i], 0, (strlen($service_temp[$i]) - 7));
			array_push($service_delete, $temp);
		}
		$data['service'] = rtrim(implode(", ", $service_delete));

		// echo $data['customer_name'];

		// custom service jakarta
		$data['cost_custom_jakarta'] = $this->M_quotation->get_all_data_custom_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_custom_jakarta'] = $this->M_quotation->get_tarif_amount_custom_jakarta($quotation_number)->result();


		$this->load->helper('currency_helper');

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);

		// container custom jakarta		
		if (!$data['cost_custom_jakarta']) {
			$hasil_custom_jakarta = array();
		} else {
			// select data cost container custom jakarta
			foreach ($data['cost_custom_jakarta'] as $key => $value) {
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['FROM_QTY'] = $value->FROM_QTY;
				$print_data['TO_QTY'] = $value->TO_QTY;
				$print_data['CALC_TYPE'] = $value->CALC_TYPE;
				$print_data['CALC_NAME'] = $value->CALC_NAME;
				$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['END_DATE'] = $value->END_DATE;

				foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_20_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_20_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_40_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_40_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_4H_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_4H_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_45_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_45_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($print_data['TARIF_20_SELLING'])) {
					$print_data['TARIF_20_SELLING'] = currency(0);
					$print_data['TARIF_20_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_40_SELLING'])) {
					$print_data['TARIF_40_SELLING'] = currency(0);
					$print_data['TARIF_40_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_4H_SELLING'])) {
					$print_data['TARIF_4H_SELLING'] = currency(0);
					$print_data['TARIF_4H_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_45_SELLING'])) {
					$print_data['TARIF_45_SELLING'] = currency(0);
					$print_data['TARIF_45_OFFERING'] = currency(0);
				}

				$hasil_custom_jakarta[] = $print_data;
			}
		}

		// check data tariff_amount custom jakarta
		foreach ($hasil_custom_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_20_SELLING']);
			} 

			if (!$this->M_quotation->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_40_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_45_SELLING']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_custom_jakarta[$key]['TARIF_20_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_20_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_40_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_40_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_4H_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_45_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_45_SELLING'] = currency(0);
			}

		}

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);
		$data['hasil_custom_jakarta'] = $hasil_custom_jakarta;

		// location
		$data['hasil_location'] = $this->M_quotation->get_data_location_jakarta($quotation_number)->result();

		// trucking service
		// container service jakarta
		$data['cost_container_jakarta'] = $this->M_quotation->get_all_data_container_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_jakarta'] = $this->M_quotation->get_tarif_amount_jakarta($quotation_number)->result();
		$data['tarif_weight_jakarta'] = $this->M_quotation->get_tarif_weight_jakarta($quotation_number)->result();

		// container cost jakarta
		if (!$data['cost_container_jakarta']) {
			$hasil_jakarta = array();
		} else {
			// select cost continer jakarta
			foreach ($data['cost_container_jakarta'] as $key => $value) {
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['FROM_QTY'] = $value->FROM_QTY;
				$test_data['TO_QTY'] = $value->TO_QTY;
				$test_data['CALC_TYPE'] = $value->CALC;
				$test_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$test_data['START_DATE'] = $value->START_DATE;
				$test_data['END_DATE'] = $value->END_DATE;
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				
				foreach ($data['tarif_amount_jakarta'] as $key1 => $value1) {
					
					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_20'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_40'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_4H'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_45'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($test_data['TARIF_20'])) {
					$test_data['TARIF_20'] = currency(0);
				}

				if (empty($test_data['TARIF_40'])) {
					$test_data['TARIF_40'] = currency(0);
				}

				if (empty($test_data['TARIF_4H'])) {
					$test_data['TARIF_4H'] = currency(0);
				}

				if (empty($test_data['TARIF_45'])) {
					$test_data['TARIF_45'] = currency(0);
				}

				$hasil_jakarta[] = $test_data;
			}
		}

		// check data tariff_amount jakarta
		foreach ($hasil_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_container($quotation_number, '20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_quotation->check_data_container($quotation_number, '40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_jakarta[$key]['TARIF_20'])) {
					$hasil_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_40'])) {
					$hasil_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_4H'])) {
					$hasil_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_45'])) {
					$hasil_jakarta[$key]['TARIF_45'] = currency(0);
			}

		} //end of trucking service
		$data['hasil_jakarta'] = $hasil_jakarta;

		// weight
		$data['hasil_weight_jakarta'] = $this->M_quotation->get_data_weight_jakarta($quotation_number)->result();

		$data['customer_count'] = $this->M_quotation->get_quotation_param_full($quotation_number)->num_rows();
		$data['customer_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->result();
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();

		// $this->load->helper('comman_helper');
		// pr($data['count_customs']);

		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();
		$this->load->helper('comman_helper');
		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;
		$cmpy_address = strtolower($this->M_quotation->get_code($cmpy)->row()->ADDRESS);
		$phone = $this->M_quotation->get_code($cmpy)->row()->PHONE;
		$email = $this->M_quotation->get_code($cmpy)->row()->EMAIL;
		$website = $this->M_quotation->get_code($cmpy)->row()->WEBSITE;
		$address_fix = ucwords($cmpy_address);
		// pr();

		// $this->load->view('quotations/v_detailquotation', $data);

		if ($bahasa == "indo") {
			$foot_address = "&emsp;$address_fix <br>&emsp;Phone : $phone <br>&emsp;Email : $email <br>&emsp;$website||Page {PAGENO} of {nb}&emsp;";
			$html = $this->load->view('reports/r_quotationindonesia', $data, true);
			$this->load->library('pdf');

			$pdf = $this->pdf->load();
			$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
			// $pdf->SetFooter('&emsp;Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 &emsp;Jakarta Pusat 10160 Indonesia <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
			$pdf->SetFooter($foot_address);
			$pdf->AddPage('', // L - landscape, P - portrait 
	        '', '', '', '',
	        0, // margin_left
	        0, // margin right
	       40, // margin top
	       30, // margin bottom
	        0, // margin header
	        5); // margin footer
			// $pdf->defaultheaderfontstyle='I';
			// $pdf->defaultfooterfontstyle='I';
			// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
			
			$pdf->WriteHTML($html);
			$pdf->Output('Quotation.pdf', 'I');
		} elseif ($bahasa == "inggris") {
			$foot_address = "&emsp;$address_fix <br>&emsp;Phone : $phone <br>&emsp;Email : $email <br>&emsp;$website||Page {PAGENO} of {nb}&emsp;";
			$html = $this->load->view('reports/r_quotationinggris', $data, true);
			$this->load->library('pdf');

			$pdf = $this->pdf->load();
			$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');

			// $pdf->SetFooter('&emsp;Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 &emsp;Jakarta Pusat 10160 Indonesia <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
			$pdf->SetFooter($foot_address);
			$pdf->AddPage('', // L - landscape, P - portrait 
	        '', '', '', '',
	        0, // margin_left
	        0, // margin right
	       40, // margin top
	       30, // margin bottom
	        0, // margin header
	        5); // margin footer
			// $pdf->defaultheaderfontstyle='I';
			// $pdf->defaultfooterfontstyle='I';
			// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
			
			$pdf->WriteHTML($html);
			$pdf->Output('Quotation.pdf', 'I');
		}
	}

	function print_quotation_inggris()
	{
		$quotation_number = $this->uri->segment(3);

		$data['template'] = $this->M_quotation->get_template_quotation($quotation_number)->result();
		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['date_quotation'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DATE;
		$customer_name_get = $this->M_quotation->get_quotation_param_full2($quotation_number)->result();
		$customer_name_temp = array();
		foreach ($customer_name_get as $key => $value) {
			array_push($customer_name_temp, $value->NAME);
		}
		$data['name'] = rtrim(implode(", ", $customer_name_temp));

		$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
		$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
		$data['pic_namdep'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
		$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
		$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;

		$service_get = $this->M_quotation->get_service_quotation($quotation_number)->result();
		$service_temp = array();
		$service_delete = array();
		foreach ($service_get as $key => $value) {
			array_push($service_temp, $value->NAME);
		}

		for ($i=0; $i < count($service_temp); $i++) { 
			$temp = substr($service_temp[$i], 0, (strlen($service_temp[$i]) - 7));
			array_push($service_delete, $temp);
		}
		$data['service'] = rtrim(implode(", ", $service_delete));

		// echo $data['customer_name'];

		// custom service jakarta
		$data['cost_custom_jakarta'] = $this->M_quotation->get_all_data_custom_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_custom_jakarta'] = $this->M_quotation->get_tarif_amount_custom_jakarta($quotation_number)->result();


		$this->load->helper('currency_helper');

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);

		// container custom jakarta		
		if (!$data['cost_custom_jakarta']) {
			$hasil_custom_jakarta = array();
		} else {
			// select data cost container custom jakarta
			foreach ($data['cost_custom_jakarta'] as $key => $value) {
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['FROM_QTY'] = $value->FROM_QTY;
				$print_data['TO_QTY'] = $value->TO_QTY;
				$print_data['CALC_TYPE'] = $value->CALC_TYPE;
				$print_data['CALC_NAME'] = $value->CALC_NAME;
				$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['END_DATE'] = $value->END_DATE;

				foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_20_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_20_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_40_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_40_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_4H_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_4H_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_45_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_45_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($print_data['TARIF_20_SELLING'])) {
					$print_data['TARIF_20_SELLING'] = currency(0);
					$print_data['TARIF_20_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_40_SELLING'])) {
					$print_data['TARIF_40_SELLING'] = currency(0);
					$print_data['TARIF_40_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_4H_SELLING'])) {
					$print_data['TARIF_4H_SELLING'] = currency(0);
					$print_data['TARIF_4H_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_45_SELLING'])) {
					$print_data['TARIF_45_SELLING'] = currency(0);
					$print_data['TARIF_4H5_OFFERING'] = currency(0);
				}

				$hasil_custom_jakarta[] = $print_data;
			}
		}

		// check data tariff_amount custom jakarta
		foreach ($hasil_custom_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_20_SELLING']);
			} 

			if (!$this->M_quotation->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_40_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_45_SELLING']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_custom_jakarta[$key]['TARIF_20_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_20_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_40_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_40_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_4H_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_45_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_45_SELLING'] = currency(0);
			}

		}

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);
		$data['hasil_custom_jakarta'] = $hasil_custom_jakarta;

		// location
		$data['hasil_location'] = $this->M_quotation->get_data_location_jakarta($quotation_number)->result();

		// trucking service
		// container service jakarta
		$data['cost_container_jakarta'] = $this->M_quotation->get_all_data_container_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_jakarta'] = $this->M_quotation->get_tarif_amount_jakarta($quotation_number)->result();
		$data['tarif_weight_jakarta'] = $this->M_quotation->get_tarif_weight_jakarta($quotation_number)->result();

		// container cost jakarta
		if (!$data['cost_container_jakarta']) {
			$hasil_jakarta = array();
		} else {
			// select cost continer jakarta
			foreach ($data['cost_container_jakarta'] as $key => $value) {
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['FROM_QTY'] = $value->FROM_QTY;
				$test_data['TO_QTY'] = $value->TO_QTY;
				$test_data['CALC_TYPE'] = $value->CALC;
				$test_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$test_data['START_DATE'] = $value->START_DATE;
				$test_data['END_DATE'] = $value->END_DATE;
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				
				foreach ($data['tarif_amount_jakarta'] as $key1 => $value1) {
					
					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_20'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_40'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_4H'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_45'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($test_data['TARIF_20'])) {
					$test_data['TARIF_20'] = currency(0);
				}

				if (empty($test_data['TARIF_40'])) {
					$test_data['TARIF_40'] = currency(0);
				}

				if (empty($test_data['TARIF_4H'])) {
					$test_data['TARIF_4H'] = currency(0);
				}

				if (empty($test_data['TARIF_45'])) {
					$test_data['TARIF_45'] = currency(0);
				}

				$hasil_jakarta[] = $test_data;
			}
		}

		// check data tariff_amount jakarta
		foreach ($hasil_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_container($quotation_number, '20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_quotation->check_data_container($quotation_number, '40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_jakarta[$key]['TARIF_20'])) {
					$hasil_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_40'])) {
					$hasil_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_4H'])) {
					$hasil_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_45'])) {
					$hasil_jakarta[$key]['TARIF_45'] = currency(0);
			}

		} //end of trucking service
		$data['hasil_jakarta'] = $hasil_jakarta;

		// weight
		$data['hasil_weight_jakarta'] = $this->M_quotation->get_data_weight_jakarta($quotation_number)->result();

		$data['customer_count'] = $this->M_quotation->get_quotation_param_full($quotation_number)->num_rows();
		$data['customer_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->result();
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();

		// $this->load->helper('comman_helper');
		// pr($data['count_customs']);

		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		// $this->load->view('quotations/v_detailquotation', $data);

		$html = $this->load->view('reports/r_quotationinggris', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		$pdf->SetFooter('&emsp;Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 &emsp;Jakarta Pusat 10160 Indonesia <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
		$pdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        0, // margin_left
        0, // margin right
       40, // margin top
       30, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('Quotation.pdf', 'I');
	}

	function compare()
	{
		$custom_location = $this->uri->segment(3);
		$custom_line = $this->uri->segment(4);
		$custom_kind = $this->uri->segment(5);
		$type = $this->uri->segment(6);
		$size = $this->uri->segment(7);
		$category = $this->uri->segment(8);
		$from_qty = $this->uri->segment(9);
		$to_qty = $this->uri->segment(10);

		$data['floor_price'] = $this->M_quotation->get_price($custom_location, $custom_line, $custom_kind, $type, $size, $category, $from_qty, $to_qty)->row()->FLOOR_PRICE;

		$data['currency'] = $this->M_quotation->get_price($custom_location, $custom_line, $custom_kind, $type, $size, $category, $from_qty, $to_qty)->row()->TARIFF_CURRENCY;
		$data['market_price'] = $this->M_quotation->get_price($custom_location, $custom_line, $custom_kind, $type, $size, $category, $from_qty, $to_qty)->row()->MARKET_PRICE;

		$this->load->view('quotations/v_compare', $data);
	}

	function need_approval()
	{
		// $quotation_number = $this->uri->segment(3);
		// $document_id = "D1002";
		// $date = date('Y-m-d H:i:s');
		// $data['data_quotation'] = $this->M_quotation->get_quotation_param($quotation_number)->result();
		// $revesion_number = $this->M_quotation->get_data_quote($quotation_number)->row()->REVESION_NUMBER;
		// $company_id = $this->M_quotation->get_data_quote($quotation_number)->row()->COMPANY_ID;

		// if (isset($_POST['submit'])) {
		// 	// sent email to pic approval
		// 		$config['protocol'] = "smtp";
		// 		$config['smtp_host'] = "192.168.11.220";
		// 		$config['smtp_port'] = "25";
		// 		$config['charset'] = "utf-8";

		// 		$this->email->initialize($config);

		// 		$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
		// 		// $this->email->to('valdi.abrar@hanomansp.com');
		// 		$this->email->to('valdi.abrar@hanomansp.com');

		// 		$this->email->subject('Quotation Approval');
		// 		$data = array('username' => 'Iwan');
		// 		$view = $this->load->view('layouts/v_email.php', $data, true);
		// 		$this->email->message($view);
		// 		$this->email->set_mailtype('html');

		// 		$this->email->send();

		// 	// if ($this->email->send()) {
  //  //          	echo 'Email sent.';
	 //  //       } else {
	 //  //           show_error($this->email->print_debugger());
	 //  //       }

		// 	// check data in trapproval
		// 	$check_appr = $this->M_quotation->check_appr($quotation_number)->num_rows();
			
		// 	if ($check_appr < 1) {
		// 		$data_trappr = array(
		// 			'TRANSACTION_NUMBER' => $quotation_number,
		// 			'DOCUMENT_ID' => $document_id,
		// 			'REVISION_NUMBER' => $revesion_number,
		// 			'COMPANY_ID' => $company_id,
		// 			'REQUEST_APPROVAL_DATE' => $date,
		// 			'APPROVAL_STATUS' => 'W'
		// 		);

		// 		$data_apprquote = array('APPROVAL_STATUS' => 'W');

		// 		// update status quotation
		// 		$update_apprquote = $this->M_quotation->update_apprquote($data_apprquote, $quotation_number);

		// 		// insert into trapproval status
		// 		$this->db->insert("dbo.TRAPPROVAL_STATUS", $data_trappr);
		// 		// if success
		// 		$this->session->set_flashdata('success', "Need Approval Success!");

		// 		redirect(current_url());
		// 	} else {
		// 		$data_update_trappr = array('REQUEST_APPROVAL_DATE' => $date);
		// 		$update_trappr = $this->M_quotation->update_trappr($data_update_trappr, $quotation_number);

		// 		$this->session->set_flashdata('success', "Need Approval Success!");

		// 		redirect(current_url());
		// 	}
		// }

		// $this->load->view('quotations/v_needapproval', $data);

		// sent email to pic approval
				$config['protocol'] = "smtp";
				$config['smtp_host'] = "192.168.11.220";
				$config['smtp_port'] = "25";
				$config['charset'] = "utf-8";

				$this->email->initialize($config);

				$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
				// $this->email->to('valdi.abrar@hanomansp.com');
				$this->email->to('valdi.abrar@hanomansp.com');

				$this->email->subject('Quotation Approval');
				$data = array('username' => 'Iwan');
				$view = $this->load->view('layouts/v_email.php', $data, true);
				$this->email->message($view);
				$this->email->set_mailtype('html');

				$this->email->send();


	}

	function detail_quotation()
	{
		$quotation_number = $this->uri->segment(3);

		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['approval_status'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->APPROVAL_STATUS;
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();
		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		$this->load->view('quotations/v_detailquotation', $data);
	}

	function test()
	{
		// sent email to pic approval
		$config['protocol'] = "smtp";
		$config['smtp_host'] = "192.168.11.220";
		$config['smtp_port'] = "25";
		$config['charset'] = "utf-8";

		$dataemail = array("valdi.abrar@hanomansp.com", "coc.valdi@gmail.com");

		$this->load->helper('comman_helper');
		pr($dataemail);

		$this->email->initialize($config);

		$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
		// $this->email->to('valdi.abrar@hanomansp.com');
		$this->email->to($dataemail);

		$this->email->subject('Quotation Approval');
		$data = array('quotation' => '160001', 'customer' => 'PT. Unilox Indonesia');
		$view = $this->load->view('layouts/v_email.php', $data, true);
		$this->email->message($view);
		$this->email->set_mailtype('html');

			if ($this->email->send()) {
            	echo 'Email sent.';
	        } else {
	            show_error($this->email->print_debugger());
	        }
		// $dsn = 'mssql://userhsp:"hsp432@"@192.168.11.29/pltapol';
		// $this->db3 = $this->load->database($dsn);
		// $this->load->helper('comman_helper');
		// pr($this->db3);
	}

	function view_create_agreement()
	{
		$data['data_quotation'] = $this->M_quotation->get_quotation_approved()->result();
		$this->load->view('quotations/v_quotationapproved', $data);
	}

	function create_agreement()
	{
		$quotation_number = $this->uri->segment(3);

		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;

		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['approval_status'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->APPROVAL_STATUS;
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();
		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		$year_now = date('y');
		$date = date('Y-m-d H:i:s');

		$temp_id = $this->M_quotation->get_max_agreement()->row()->id;

		if ($temp_id == "") {
			$id = $year_now . "0001";
			$data['agreement_number'] = $year_now . "0001";
			$data['agreement_document_number'] = substr($id, 2,5). "/" . $code_cmpy . "-AGR/" . date('m/Y');
		} else {
			$potongan_tahun = substr($temp_id, 0,2);

			if ($potongan_tahun == $year_now) {
				// echo "sama";

				$id = $temp_id + 1;
				$data['agreement_number'] = $temp_id + 1;
			} else {
				// echo "tidak";
				$id = $year_now . "0001";
				$data['agreement_number'] = $year_now . "0001";
			}

			$data['agreement_document_number'] = substr($id, 2,5). "/" . $code_cmpy . "-AGR/" . date('m/Y');
			// $data['id'] = $id . "/HSPJKT-QUO/" . date('m/Y');
		}

		// $data['agreement_document_number'] = substr($id, 2,5). "/" . $code_cmpy . "-AGR/" . date('m/Y');

		// $this->load->helper("comman_helper");
		// pr($data['agreement_number']);

		$success = array();


		$this->form_validation->set_rules('agreement_number', 'Agreement Number', 'required');
		$this->form_validation->set_rules('agreement_document_number', 'Agreement Document Number', 'required');
		$this->form_validation->set_rules('start_date', 'Periode Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'Periode End Date', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         if (isset($_POST)) {
         	$agreement_number = $this->input->post("agreement_number");
         	$agreement_document_number = $this->input->post("agreement_document_number");
         	$start_date = $this->input->post('start_date');
         	$end_date = $this->input->post('end_date');
         	$remarks = $this->input->post('remarks');

         	// $check_quotation = $this->M_quotation->check_quotation($quotation_number)->num_rows();
         	// $this->load->helper('comman_helper');
         	// pr($check_quotation);

   //       	elseif ($check_quotation > 0) {
			// 	$data['quot_exists'] = "exists";
   //      		$this->load->view('quotations/v_createagreement', $data);
			// } 

         	// entry agreement
			if ($this->form_validation->run() == false) {
				$this->load->view('quotations/v_createagreement', $data);
			} else {
				$this->db->trans_begin();
				try {
					// check email who must approve quotation
					$data_nik = array();
					$data_email = array();
					$check_approval_satu = $this->M_quotation->check_approval('D1001')->row()->APPROVAL_LEVEL1;
					$check_approval_dua = $this->M_quotation->check_approval('D1001')->row()->APPROVAL_LEVEL2;
					$check_approval_tiga = $this->M_quotation->check_approval('D1001')->row()->APPROVAL_LEVEL3;

					if ($check_approval_satu == 'Y') {
						$get_nik_appr_satu = $this->M_quotation->get_nik_appr('D1001', '1')->result();
						foreach ($get_nik_appr_satu as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_dua == 'Y') {
						$get_nik_appr_dua = $this->M_quotation->get_nik_appr('D1001', '2')->result();
						foreach ($get_nik_appr_dua as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_tiga == 'Y') {
						$get_nik_appr_tiga = $this->M_quotation->get_nik_appr('D1001', '3')->result();
						foreach ($get_nik_appr_tiga as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					// get email
					for ($nik=0; $nik < count($data_nik); $nik++) { 
						$temp_email = $this->M_quotation->get_email($data_nik[$nik])->row()->email;
						array_push($data_email, $temp_email);
					}

					// $this->load->helper('comman_helper');
					// pr($data_email);

					// sent email to pic approval
					$config['protocol'] = "smtp";
					$config['smtp_host'] = "192.168.11.220";
					$config['smtp_port'] = "25";
					$config['charset'] = "utf-8";

					// for ($e=0; $e < count($data_email); $e++) { 
					// 	$this->email->initialize($config);

					// 	$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
					// 	// $this->email->to('valdi.abrar@hanomansp.com');
					// 	$this->email->to($data_email);

					// 	$this->email->subject('Quotation Approval');
					// 	$data = array('quotation' => $id, 'customer' => $company_name);
					// 	$view = $this->load->view('layouts/v_email.php', $data, true);
					// 	$this->email->message($view);
					// 	$this->email->set_mailtype('html');

					// 	$this->email->send();
					// }

					$this->email->initialize($config);

					$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
					// $this->email->to('valdi.abrar@hanomansp.com');
					$this->email->to($data_email);

					$this->email->subject('Agreement Approval');
					$data = array('agreement_number' => $agreement_number, 'customer' => $data['company_name']);
					$view = $this->load->view('layouts/v_emailagreement.php', $data, true);
					$this->email->message($view);
					$this->email->set_mailtype('html');

					$this->email->send();

					// http://192.168.11.31/hsp/Approval/detail_approval_agreement/160003/D1001
					$this->session->set_flashdata('redirect', 'http://192.168.11.31/hsp/Approval/detail_approval_agreement/'.$agreement_number.'/D1001');

					// insert to trapproval_status_transaction
					$data_trappr = array(
							'TRANSACTION_NUMBER' => $agreement_number,
							'DOCUMENT_ID' => 'D1001',
							'REVISION_NUMBER' => '0',
							'COMPANY_ID' => $code_cmpy,
							'REQUEST_APPROVAL_DATE' => $date,
							'APPROVAL_STATUS' => 'N'
						);
					if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_trappr)) {
						throw new Exception("Error Processing Request to Entry Approval Agreement", 1);
					}

					// insert data agreement
					$data_agreement = array(
						'agreement_number' => $agreement_number,
						'agreement_document_number' => $agreement_document_number,
						'agreement_date' => $start_date,
						'quotation_number' => $quotation_number,
						'agreement_periode_start' => $start_date,
						'agreement_periode_end' => $end_date,
						'approval_status' => 'N',
						'user_id' => $this->nik,
						'user_date' => $date,
						'amendment_number' => '0',
						'remarks' => $remarks
					);

					// insert
					if (!$this->db->insert("dbo.TRAGREEMENT", $data_agreement)) {
						throw new Exception("Error Processing Request to Entry Agreement", 1);
					}

					// update status agreement in quotation
					$update_quotation = array(
						'status_agreement' => 1
					);

					$update_status_agreement = $this->M_quotation->update_status_agreement($quotation_number, 'dbo.TRQUOTATION', $update_quotation);

					if ($update_status_agreement == FALSE) {
						throw new Exception("Error Processing Request to Update Availability Revision Quotation", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Agreement", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully entry agreement!");
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

	function view_agreement()
	{
		$data['data_agreement'] = $this->M_quotation->get_agreement()->result();
		$this->load->view('quotations/v_indexagreement', $data);
	}

	function agreement_detail()
	{
		$agreement_number = $this->uri->segment(3);

		$quotation_number = $this->uri->segment(4);

		// agreement
		$data['agreement_number'] = $this->M_quotation->get_agreement_param($agreement_number)->row()->AGREEMENT_NUMBER;
		// $this->load->helper("comman_helper");
		// pr($data['agreement_number']);
		$data['agreement_document_number'] = $this->M_quotation->get_agreement_param($agreement_number)->row()->AGREEMENT_DOCUMENT_NUMBER;
		$data['periode_start'] = $this->M_quotation->get_agreement_param($agreement_number)->row()->AGREEMENT_PERIODE_START;
		$data['periode_end'] = $this->M_quotation->get_agreement_param($agreement_number)->row()->AGREEMENT_PERIODE_END;
		$data['amendment_number'] = $this->M_quotation->get_agreement_param($agreement_number)->row()->AMENDMENT_NUMBER;
		$data['approval_status'] = $this->M_quotation->get_agreement_param($agreement_number)->row()->APPROVAL_STATUS;

		// quotation
		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();
		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		$this->load->view('quotations/v_agreementdetail', $data);
	}

	function detail_trucking_cost()
	{
		$from_location = $this->uri->segment(3);
		$to_location = $this->uri->segment(4);
		$type = $this->uri->segment(5);
		$size = $this->uri->segment(6);
		$category = $this->uri->segment(7);
		$quotation_number = $this->uri->segment(8);

		$data['data_cost'] = $this->M_quotation->get_detail_trucking_cost($quotation_number, $from_location, $to_location, $type, $size, $category)->result();
		$data['cost_detail_20'] = $this->M_quotation->get_container_cost_detail_20($quotation_number, $from_location, $to_location, $type, $size, $category)->result();
		$data['cost_detail_40'] = $this->M_quotation->get_container_cost_detail_40($quotation_number, $from_location, $to_location, $type, $size, $category)->result();
		$data['cost_detail_4h'] = $this->M_quotation->get_container_cost_detail_4h($quotation_number, $from_location, $to_location, $type, $size, $category)->result();
		$data['cost_detail_45'] = $this->M_quotation->get_container_cost_detail_45($quotation_number, $from_location, $to_location, $type, $size, $category)->result();
		$data['details'] = $this->M_quotation->get_detail_trucking_name($from_location, $to_location, $type, $size, $category)->result();
		$data['floor_price'] = $this->M_quotation->get_floor_market_trucking($from_location, $to_location, $type, $size, $category)->row()->FLOOR_PRICE;
		$data['market_price'] = $this->M_quotation->get_floor_market_trucking($from_location, $to_location, $type, $size, $category)->row()->MARKET_PRICE;
		$data['tariff_currency'] = $this->M_quotation->get_floor_market_trucking($from_location, $to_location, $type, $size, $category)->row()->TARIFF_CURRENCY;

		// $this->load->helper("comman_helper");
		// pr($data['details']);

		$this->load->view('quotations/v_detailtruckingcost', $data);
	}

	function detail_customs_cost()
	{
		$customs_from = $this->uri->segment(3);
		$customs_line = $this->uri->segment(4);
		$customs_kind = $this->uri->segment(5);
		$type = $this->uri->segment(6);
		$size = $this->uri->segment(7);
		$category = $this->uri->segment(8);
		$quotation_number = $this->uri->segment(9);

		// get all data cost
		$data['custom_cost_detail_20'] = $this->M_quotation->get_container_custom_cost_detail_20($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)->result();
		$data['custom_cost_detail_40'] = $this->M_quotation->get_container_custom_cost_detail_40($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)->result();
		$data['custom_cost_detail_4h'] = $this->M_quotation->get_container_custom_cost_detail_4h($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)->result();
		$data['custom_cost_detail_45'] = $this->M_quotation->get_container_custom_cost_detail_45($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)->result();

		// get data to detail
		$data['details'] = $this->M_quotation->get_detail_of_custom_cost($customs_from, $customs_line, $customs_kind, $type, $size, $category);
		$data['floor_price'] =  $this->M_quotation->get_floor_market_customs($customs_from, $customs_line, $customs_kind, $type, $size, $category)->row()->FLOOR_PRICE;
		$data['market_price'] =  $this->M_quotation->get_floor_market_customs($customs_from, $customs_line, $customs_kind, $type, $size, $category)->row()->MARKET_PRICE;

		$this->load->view('quotations/v_detailcustomscost', $data);

	}

	function detail_location_cost()
	{
		$from_location = $this->uri->segment(3);
		$to_location = $this->uri->segment(4);
		$truck_id = $this->uri->segment(5);
		$quotation_number = $this->uri->segment(6);

		$data['details'] = $this->M_quotation->get_detail_location($from_location, $to_location, $truck_id)->result();

		$data['cost_detail'] = $this->M_quotation->get_location_cost_detail($quotation_number, $from_location, $to_location, $truck_id)->result();

		$data['floor_price'] =  $this->M_quotation->get_floor_market_location($from_location, $to_location, $truck_id)->row()->FLOOR_PRICE;
		$data['market_price'] =  $this->M_quotation->get_floor_market_location($from_location, $to_location, $truck_id)->row()->MARKET_PRICE;
		$data['tariff_currency'] = $this->M_quotation->get_floor_market_location($from_location, $to_location, $truck_id)->row()->TARIFF_CURRENCY;

		$this->load->view('quotations/v_detaillocationcost', $data);
	}

	function detail_weight_cost()
	{
		$from_location = $this->uri->segment(3);
		$to_location = $this->uri->segment(4);
		$quotation_number = $this->uri->segment(7);

		$data['details'] = $this->M_quotation->get_detail_weight2($from_location, $to_location);

		$data['cost_detail'] = $this->M_quotation->get_cost_weight($quotation_number, $from_location, $to_location)->result();

		$data['floor_price'] =  $this->M_quotation->get_floor_market_weight($from_location, $to_location)->row()->FLOOR_PRICE;
		$data['market_price'] =  $this->M_quotation->get_floor_market_weight($from_location, $to_location)->row()->MARKET_PRICE;
		$data['tariff_currency'] = $this->M_quotation->get_floor_market_weight($from_location, $to_location)->row()->TARIFF_CURRENCY;

		$this->load->view('quotations/v_detailweightcost', $data);
	}

	function revision_quotation()
	{
		$quotation_number = $this->uri->segment(3);

		$data['quotation_number'] = $quotation_number;

		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;

		// selling trucking
		$data['data_trucking'] = $this->M_quotation->get_data_trucking($code_cmpy)->result();
		$data['data_customs'] = $this->M_quotation->get_data_customs($code_cmpy)->result();
		$data['data_location'] = $this->M_quotation->get_data_location()->result();
		$data['data_weight'] = $this->M_quotation->get_data_weight()->result();
		$data['data_ocean'] = $this->M_quotation->get_data_ocean()->result();
		$data['template'] = $this->M_quotation->get_template()->result();

		$quote_number = substr($quotation_number, 2,5). "/" . $code_cmpy . "-QUO/" . date('m/Y');

		$date = date("Y-m-d");

		$data['document_number'] = $quote_number;

		// $quotation_number = $this->input->post('quotation_number');

		$data['data_trucking2'] = $this->M_quotation->get_revision_trucking($quotation_number)->result();
		$data['data_customs2'] = $this->M_quotation->get_revision_customs($quotation_number)->result();
		$data['data_location2'] = $this->M_quotation->get_revision_location($quotation_number)->result();
		$data['data_weight2'] = $this->M_quotation->get_revision_weight($quotation_number)->result();
		$data['data_ocean_freight2'] = $this->M_quotation->get_revision_ocean_freight($quotation_number)->result();
		$data['quotation_number2'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number2'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['approval_status2'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->APPROVAL_STATUS;
		$data['company_name2'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date2'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date2'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision2'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking2'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs2'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();
		$data['count_location2'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight2'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight2'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		// lets test
		$data['combo_trucking'] = $this->M_quotation->check_combo($quotation_number, 'SS01')->num_rows();
		$data['combo_customs'] = $this->M_quotation->check_combo($quotation_number, 'SS02')->num_rows();
		$data['combo_location'] = $this->M_quotation->check_combo($quotation_number, 'SS04')->num_rows();
		$data['combo_weight'] = $this->M_quotation->check_combo($quotation_number, 'SS05')->num_rows();
		$data['combo_ocean'] = $this->M_quotation->check_combo($quotation_number, 'SS03')->num_rows();
		$company_id = $this->M_quotation->get_data_quotation($quotation_number)->row()->CUSTOMER_ID;
		$data['marketing_id'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->MARKETING_ID;
		$data['remarks'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->REMARKS;
		// $this->load->helper('comman_helper');
		// pr($data['combo_ocean']);
		$data['template_text1'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->TEMPLATE_TEXT1;
		$data['template_text2'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->TEMPLATE_TEXT2;
		$data['customer_id_revision'] = $this->M_quotation->check_data_revision($company_id)->row()->COMPANY_ID;
		$data['customer_name_revision'] = $this->M_quotation->check_data_revision($company_id)->row()->NAME;
		$data['start_date_revision'] = $this->M_quotation->get_date_revision($quotation_number)->row()->START_DATE;
		$data['end_date_revision'] = $this->M_quotation->get_date_revision($quotation_number)->row()->END_DATE;

		$this->form_validation->set_rules('quotation_number', 'Quotation Number', 'required');
		$this->form_validation->set_rules('company_id', 'Company ID', 'required');
		$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		$this->form_validation->set_rules('sales_id', 'Sales ID', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('template_text1', 'Template Syarat dan Kondisi', 'required');
		$this->form_validation->set_rules('template_text2', 'Template Terms and Condition', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// insert data
		if (isset($_POST)) {
			// declare variable
			// $quotation_number = $this->input->post('quotation_number');
			$company_name = $this->input->post('company_name');
			$company_quo_id = $this->input->post('company_id');
			$sales_id = $this->input->post('sales_id');
			$pic_id = $this->input->post('pic_id');
			$remarks = $this->input->post('remarks');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$syarat = $this->input->post('template_text1');
			$term = $this->input->post('template_text2');

			// check quotation
			// $check_quotation = $this->M_quotation->check_quotation_insert($id)->num_rows();
			// $this->load->helper('comman_helper');
			// pr($id);

			// declare variable error
			$error_trucking = "";
			$error_customs = "";
			$error_location = "";
			$error_weight = "";
			$error_ocean = "";

			if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
				$trucking = $this->input->post('trucking');
				if (count($trucking) < 1) {
					$error_trucking = "error";
				} else {
					$error_trucking = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
				$test = $this->input->post('customs');
				if (count($test) < 1) {
					$error_customs = "error";
				} else {
					$error_customs = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
				$location = $this->input->post('location');
				if (count($location) < 1) {
					$error_location = "error";
				} else {
					$error_location = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
				$weight = $this->input->post('weight');
				if (count($weight) < 1) {
					$error_weight = "error";
				} else {
					$error_weight = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
				$ocean = $this->input->post('ocean');
				if (count($ocean) < 1) {
					$error_ocean = "error";
				} else {
					$error_ocean = "ada";
				}
			}

			// check last revision number
			$check_revision = $this->M_quotation->check_revision($quotation_number)->row()->REVESION_NUMBER;
			
			$new_revision = $check_revision + 1;

			// declare check
			$trucking_check = "";
			$customs_check = "";
			$location_check = "";
			$weight_check = "";
			$ocean_check = "";

			if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
				$trucking = $this->input->post('trucking');
				$data_costtrucking = $trucking; // create copy to delete dups from
				$newtrucking = array();

				for( $i=0; $i<count($trucking); $i++ ) {

				    if ( isset($trucking) && in_array( array( $trucking[$i]['size'], $trucking[$i]['to'], $trucking[$i]['type'], $trucking[$i]['category'], $trucking[$i]['from'], $trucking[$i]['from_qty'], $trucking[$i]['to_qty'], $trucking[$i]['company_id'] ), $newtrucking ) ) {
				    	unset($newtrucking[$i]);
				    	unset($data_costtrucking[$i]);
				    	$trucking_check = "error";
				    }
				    else {
				    	$newtrucking[$i][] = $trucking[$i]['size'];
				    	$newtrucking[$i][] = $trucking[$i]['to'];
				    	$newtrucking[$i][] = $trucking[$i]['type'];
				    	$newtrucking[$i][] = $trucking[$i]['category'];
				    	$newtrucking[$i][] = $trucking[$i]['from'];
				    	$newtrucking[$i][] = $trucking[$i]['from_qty'];
				    	$newtrucking[$i][] = $trucking[$i]['to_qty'];
				    	$newtrucking[$i][] = $trucking[$i]['company_id'];
				    }

				}
			}

			if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
				
				$test = $this->input->post('customs');

				$data_costcustoms = $test; // create copy to delete dups from
				$newarray = array();

				for( $i=0; $i<count($test); $i++ ) {

				    if ( isset($test) && in_array( array($test[$i]['line_customs'], $test[$i]['size_customs'], $test[$i]['from_customs'], $test[$i]['type_customs'], $test[$i]['kind_customs'], $test[$i]['category_customs'], $test[$i]['from_qty_customs'], $test[$i]['to_qty_customs'], $test[$i]['calc_customs'], $test[$i]['increment_customs'], $test[$i]['company_id'] ), $newarray ) ) {
				    	unset($newarray[$i]);
				    	unset($data_costcustoms[$i]);
				    	$customs_check = "error";
				    }
				    else {
				    	$newarray[$i][] = $test[$i]['line_customs'];
				    	$newarray[$i][] = $test[$i]['size_customs'];
				    	$newarray[$i][] = $test[$i]['from_customs'];
				    	$newarray[$i][] = $test[$i]['type_customs'];
				    	$newarray[$i][] = $test[$i]['kind_customs'];
				    	$newarray[$i][] = $test[$i]['category_customs'];
				    	$newarray[$i][] = $test[$i]['from_qty_customs'];
				    	$newarray[$i][] = $test[$i]['to_qty_customs'];
				    	$newarray[$i][] = $test[$i]['calc_customs'];
				    	$newarray[$i][] = $test[$i]['increment_customs'];
				    	$newarray[$i][] = $test[$i]['company_id'];
				    }

				}
			}

			if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
				$location = $this->input->post('location');

				// filter for duplicate data
				$data_costlocation3 = $location;
				$newlocation3 = array();

				// , $location[$i]['start_date'], $location[$i]['end_date']

				for( $i=0; $i<count($location); $i++ ) {

				    if ( isset($location) && in_array( array( $location[$i]['from'], $location[$i]['to'], $location[$i]['truck'], $location[$i]['calc'], $location[$i]['increment'], $location[$i]['distance'], $location[$i]['distanceliter'] ), $newlocation3 ) ) {
				    	unset($newlocation3[$i]);
				    	unset($data_costlocation3[$i]);
				    	$location_check = "error";
				    	// return 0;
				    }
				    else {
				    	$newlocation3[$i][] = $location[$i]['from'];
				    	$newlocation3[$i][] = $location[$i]['to'];
				    	$newlocation3[$i][] = $location[$i]['truck'];
				    	$newlocation3[$i][] = $location[$i]['calc'];
				    	$newlocation3[$i][] = $location[$i]['increment'];
				    	// $newlocation3[$i][] = $location[$i]['start_date'];
				    	// $newlocation3[$i][] = $location[$i]['end_date'];
				    	$newlocation3[$i][] = $location[$i]['distance'];
				    	$newlocation3[$i][] = $location[$i]['distanceliter'];
				    	// return 0;
				    }

				}

				// $this->load->helper('comman_helper');
				// pr($newlocation3);
			}

			if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
				$weight = $this->input->post('weight');

				// filter for duplicate data
				$data_costweight = $weight;
				$newweight = array();

				for( $i=0; $i<count($weight); $i++ ) {

				    if ( isset($weight) && in_array( array( $weight[$i]['from'], $weight[$i]['to'], $weight[$i]['from_weight'], $weight[$i]['to_weight'], $weight[$i]['calc'], $weight[$i]['increment'] ), $newweight ) ) {
				    	unset($newweight[$i]);
				    	unset($data_costweight[$i]);
				    	$weight_check = "error";
				    }
				    else {
				    	$newweight[$i][] = $weight[$i]['from'];
				    	$newweight[$i][] = $weight[$i]['to'];
				    	$newweight[$i][] = $weight[$i]['from_weight'];
				    	$newweight[$i][] = $weight[$i]['to_weight'];
				    	$newweight[$i][] = $weight[$i]['calc'];
				    	$newweight[$i][] = $weight[$i]['increment'];
				    }

				}
				// $this->load->helper('comman_helper');
				// pr($data_costweight);
			}

			if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
				$ocean = $this->input->post('ocean');

				// for cost
				$data_costocean = $ocean;
				$newocean = array();

				for( $i=0; $i<count($ocean); $i++ ) {

				    if ( in_array( array( $ocean[$i]['size'], $ocean[$i]['to'], $ocean[$i]['type'], $ocean[$i]['category'], $ocean[$i]['from'], $ocean[$i]['charge'], $ocean[$i]['from_qty'], $ocean[$i]['to_qty'], $ocean[$i]['calc'], $ocean[$i]['increment'] ), $newocean ) ) {
				    	unset($newocean[$i]);
				    	unset($data_costocean[$i]);
				    }
				    else {
				    	$newocean[$i][] = $ocean[$i]['size'];
				    	$newocean[$i][] = $ocean[$i]['to'];
				    	$newocean[$i][] = $ocean[$i]['type'];
				    	$newocean[$i][] = $ocean[$i]['category'];
				    	$newocean[$i][] = $ocean[$i]['from'];
				    	$newocean[$i][] = $ocean[$i]['charge'];
				    	$newocean[$i][] = $ocean[$i]['from_qty'];
				    	$newocean[$i][] = $ocean[$i]['to_qty'];
				    	$newocean[$i][] = $ocean[$i]['calc'];
				    	$newocean[$i][] = $ocean[$i]['increment'];
				    }

				}
			}

			// entry to table quotation
			if ($this->form_validation->run() == false) {
				$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($error_trucking == "error") {
				$data['error_trucking'] = "error";
        		$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($error_customs == "error") {
				$data['error_customs'] = "error";
        		$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($error_location == "error") {
				$data['error_location'] = "error";
        		$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($error_weight == "error") {
				$data['error_weight'] = "error";
        		$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($error_ocean == "error") {
				$data['error_ocean'] = "error";
        		$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($trucking_check == "error") {
				$data['truck_error'] = "error";
				$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($customs_check == "error") {
				$data['customs_error'] = "error";
				$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($location_check == "error") {
				$data['location_error'] = "error";
				$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($weight_check == "error") {
				$data['weight_error'] = "error";
				$this->load->view('quotations/v_revisionquotation', $data);
			} elseif ($ocean_check == "error") {
				$data['ocean_error'] = "error";
				$this->load->view('quotations/v_revisionquotation', $data);
			} else {
				$this->db->trans_begin();
				try {
					// $trucking = $this->input->post('trucking');
					// $test = $this->input->post('customs');
					// $location = $this->input->post('location');
					// $weight = $this->input->post('weight');

					$services = array();

					if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
						array_push($services, "SS01");
					}
					if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
						array_push($services, "SS02");
					}
					if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
						array_push($services, "SS04");
					}
					if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
						array_push($services, "SS05");
					}
					if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
						array_push($services, "SS03");
					}

					$data_services = array();
					$data_quotation = array();
					$data_trucking = array();
					$data_customs = array();
					$data_location = array();
					$data_weight = array();
					$data_ocean = array();

					// $test = $this->input->post('customs');
					// $this->load->helper("comman_helper");
					// pr($test);
					
					// $this->load->helper('comman_helper');
					// pr($services);
					
					// input quotation services
					// backup to history
					$this->M_quotation->backup_quotation($quotation_number);
					$this->M_quotation->backup_quotation_services($quotation_number);

					for ($i=0; $i < count($services); $i++) { 
						$data_services[] = array(
							'quotation_number' => $quotation_number,
							'selling_service_id' => $services[$i],
							'revesion_number' => $new_revision
						);
					}

					if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE', $data_services)) {
						throw new Exception("Error Processing Request to Insert Quotation Services", 1);
					}
					// array_push($success, "Successfully insert data quotation services!");
					unset($data_services);
					unset($services);

					// set to input quotation
					$data_quotation = array(
						'quotation_number' => $quotation_number,
						'quotation_document_number' => $quote_number,
						'company_id' => $code_cmpy,
						'revesion_number' => $new_revision,
						'quotation_date' => $start_date,
						'quotation_periode_start' => $start_date,
						'quotation_periode_end' => $end_date,
						'customer_id' => $company_quo_id,
						'approval_status' => 'N',
						'user_id' => $this->nik,
						'user_date' => $date,
						'template_text1' => $syarat,
						'template_text2' => $term,
						'marketing_id' => $sales_id
					);
					if (!$this->db->insert('dbo.TRQUOTATION', $data_quotation)) {
						throw new Exception("Error Processing Request to Insert Quotation", 1);
					}
					// array_push($success, "Successfully insert data quotation!");

					unset($data_quotation);

					// insert to approval status
					$data_trappr = array(
							'TRANSACTION_NUMBER' => $quotation_number,
							'DOCUMENT_ID' => 'D1002',
							'REVISION_NUMBER' => $new_revision,
							'COMPANY_ID' => $code_cmpy,
							'REQUEST_APPROVAL_DATE' => $date,
							'APPROVAL_STATUS' => 'N'
						);
					if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_trappr)) {
						throw new Exception("Error Processing Request to Insert Approval Quotation", 1);
					}

					unset($data_trappr);

					// check email who must approve quotation
					$data_nik = array();
					$data_email = array();
					$check_approval_satu = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL1;
					$check_approval_dua = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL2;
					$check_approval_tiga = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL3;

					if ($check_approval_satu == 'Y') {
						$get_nik_appr_satu = $this->M_quotation->get_nik_appr('D1002', '1')->result();
						foreach ($get_nik_appr_satu as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_dua == 'Y') {
						$get_nik_appr_dua = $this->M_quotation->get_nik_appr('D1002', '2')->result();
						foreach ($get_nik_appr_dua as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_tiga == 'Y') {
						$get_nik_appr_tiga = $this->M_quotation->get_nik_appr('D1002', '3')->result();
						foreach ($get_nik_appr_tiga as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					// get email
					for ($nik=0; $nik < count($data_nik); $nik++) { 
						$temp_email = $this->M_quotation->get_email($data_nik[$nik])->row()->email;
						array_push($data_email, $temp_email);
					}

					// $this->load->helper('comman_helper');
					// pr($data_email);

					// sent email to pic approval
					$config['protocol'] = "smtp";
					$config['smtp_host'] = "192.168.11.220";
					$config['smtp_port'] = "25";
					$config['charset'] = "utf-8";

					for ($e=0; $e < count($data_email); $e++) { 
						$this->email->initialize($config);

						$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
						// $this->email->to('valdi.abrar@hanomansp.com');
						$this->email->to($data_email[$e]);

						$this->email->subject('Revision Quotation Approval');
						$data = array('quotation' => $quotation_number, 'customer' => $company_name);
						$view = $this->load->view('layouts/v_email_revision.php', $data, true);
						$this->email->message($view);
						$this->email->set_mailtype('html');

						$this->email->send();
					}

					if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
						// revision process
						// insert into history selling
						$insert_revision_trucking_selling = $this->M_quotation->insert_revision_trucking_selling($quotation_number);
						if ($insert_revision_trucking_selling == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Selling (History)", 1);
						}
						// insert into history cost
						$insert_revision_trucking_cost = $this->M_quotation->insert_revision_trucking_cost($quotation_number);
						if ($insert_revision_trucking_cost == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Cost (History)", 1);
						}
						// delete data quotation with last revision in database
						$delete_revision_trucking_selling = $this->M_quotation->delete_revision_trucking_selling($quotation_number);
						if ($delete_revision_trucking_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Selling Revision", 1);
						}
						$delete_revision_trucking_cost = $this->M_quotation->delete_revision_trucking_cost($quotation_number);
						if ($delete_revision_trucking_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Cost Revision", 1);
						}

						$trucking = $this->input->post('trucking');

						$data_costtrucking = $trucking; // create copy to delete dups from
						$newtrucking = array();

						for( $i=0; $i<count($trucking); $i++ ) {

						    if ( in_array( array( $trucking[$i]['size'], $trucking[$i]['to'], $trucking[$i]['type'], $trucking[$i]['category'], $trucking[$i]['from'], $trucking[$i]['from_qty'], $trucking[$i]['to_qty'], $trucking[$i]['calc'], $trucking[$i]['increment'], $trucking[$i]['company_id'] ), $newtrucking ) ) {
						    	unset($newtrucking[$i]);
						    	unset($data_costtrucking[$i]);
						    }
						    else {
						    	$newtrucking[$i][] = $trucking[$i]['size'];
						    	$newtrucking[$i][] = $trucking[$i]['to'];
						    	$newtrucking[$i][] = $trucking[$i]['type'];
						    	$newtrucking[$i][] = $trucking[$i]['category'];
						    	$newtrucking[$i][] = $trucking[$i]['from'];
						    	$newtrucking[$i][] = $trucking[$i]['from_qty'];
						    	$newtrucking[$i][] = $trucking[$i]['to_qty'];
						    	$newtrucking[$i][] = $trucking[$i]['calc'];
						    	$newtrucking[$i][] = $trucking[$i]['increment'];
						    	$newtrucking[$i][] = $trucking[$i]['company_id'];
						    }
						}

						$data_costtrucking2 = $trucking; // create copy to delete dups from
						$newtrucking2 = array();

						for( $i=0; $i<count($trucking); $i++ ) {

						    if ( in_array( array( $trucking[$i]['size'], $trucking[$i]['to'], $trucking[$i]['type'], $trucking[$i]['category'], $trucking[$i]['from'], $trucking[$i]['company_id'] ), $newtrucking2 ) ) {
						    	unset($newtrucking2[$i]);
						    	unset($data_costtrucking2[$i]);
						    }
						    else {
						    	$newtrucking2[$i][] = $trucking[$i]['size'];
						    	$newtrucking2[$i][] = $trucking[$i]['to'];
						    	$newtrucking2[$i][] = $trucking[$i]['type'];
						    	$newtrucking2[$i][] = $trucking[$i]['category'];
						    	$newtrucking2[$i][] = $trucking[$i]['from'];
						    	$newtrucking2[$i][] = $trucking[$i]['company_id'];
						    }
						}

						$this->load->helper('comman_helper');
						// pr($newtrucking);
						// pr($data_costtrucking2);
						// pr($trucking);

						// input quotation trucking
						foreach ($data_costtrucking as $value) {
							$data_trucking[] = array(
								'quotation_number' => $quotation_number,
								'company_id' => $code_cmpy,
								'revesion_number' => $new_revision,
								'selling_service_id' => $value['selling_service'],
								'container_size_id' => $value['size'],
								'container_type_id' => $value['type'],
								'container_category_id' => $value['category'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'from_qty' => $value['from_qty'],
								'to_qty' => $value['to_qty'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						foreach ($data_costtrucking2 as $value1) {

							// add cost trucking selling
							$data_cost_trucking = $this->M_quotation->get_trucking_cost($value1['selling_service'], $value1['type'], $value1['category'], $value1['from'], $value1['to'], $value1['size'], $value['company_id']);
							if ($data_cost_trucking->num_rows() < 1) {
								unset($data_cost_trucking);
							} else {
								for ($j=0; $j < $data_cost_trucking->num_rows(); $j++) { 
									$cost_insert_trucking[] = array(
										'quotation_number' => $quotation_number,
										'company_id' => $data_cost_trucking->row($j)->COMPANY_ID,
										'revesion_number' => $new_revision,
										'selling_service_id' => $data_cost_trucking->row($j)->SELLING_SERVICE_ID,
										'container_size_id' => $data_cost_trucking->row($j)->CONTAINER_SIZE_ID,
										'container_type_id' => $data_cost_trucking->row($j)->CONTAINER_TYPE_ID,
										'container_category_id' => $data_cost_trucking->row($j)->CONTAINER_CATEGORY_ID,
										'from_qty' => $data_cost_trucking->row($j)->FROM_QTY,
										'to_qty' => $data_cost_trucking->row($j)->TO_QTY,
										'from_location_id' => $data_cost_trucking->row($j)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_trucking->row($j)->TO_LOCATION_ID,
										'start_date' => $data_cost_trucking->row($j)->START_DATE,
										'end_date' => $data_cost_trucking->row($j)->END_DATE,
										'cost_id' => $data_cost_trucking->row($j)->COST_ID,
										'cost_type_id' => $data_cost_trucking->row($j)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_trucking->row($j)->COST_GROUP_ID,
										'calc_type' => $data_cost_trucking->row($j)->CALC_TYPE,
										'cost_currency' => $data_cost_trucking->row($j)->COST_CURRENCY,
										'cost_amount' => $data_cost_trucking->row($j)->COST_AMOUNT,
										'increment_qty' => $data_cost_trucking->row($j)->INCREMENT_QTY,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}

								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE', $cost_insert_trucking)) {
									throw new Exception("Error Processing Request to Entry Cost Container Trucking", 1);
								}
								unset($data_cost_trucking);
								unset($cost_insert_trucking);
							}
						}

						

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking)) {
							throw new Exception("Error Processing Request to Entry Selling Container Trucking", 1);
						}

						// array_push($success, "Successfully insert data quotation trucking!");
						unset($data_trucking);
					} else {
						// delete data quotation with last revision in database
						$delete_revision_trucking_selling = $this->M_quotation->delete_revision_trucking_selling($quotation_number);
						if ($delete_revision_trucking_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Trucking Selling", 1);
						}
						$delete_revision_trucking_cost = $this->M_quotation->delete_revision_trucking_cost($quotation_number);
						if ($delete_revision_trucking_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Trucking Cost", 1);
						}
					}

					if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
						// revision process
						// insert into history selling
						$insert_revision_customs_selling = $this->M_quotation->insert_revision_customs_selling($quotation_number);
						if ($insert_revision_customs_selling == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Customs Selling", 1);
						}
						// insert into history cost
						$insert_revision_customs_cost = $this->M_quotation->insert_revision_customs_cost($quotation_number);
						if ($insert_revision_customs_cost == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Customs Cost", 1);
						}
						// delete data quotation with last revision in database
						$delete_revision_customs_selling = $this->M_quotation->delete_revision_customs_selling($quotation_number);
						if ($delete_revision_customs_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Customs Selling", 1);
						}
						$delete_revision_customs_cost = $this->M_quotation->delete_revision_customs_cost($quotation_number);
						if ($delete_revision_customs_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Customs Cost", 1);
						}

						$test = $this->input->post('customs');

						// $this->load->helper('comman_helper');
						// pr($test);

						$data_costcustoms = $test; // create copy to delete dups from
						$newarray = array();

						for( $i=0; $i<count($test); $i++ ) {

						    if ( in_array( array($test[$i]['line_customs'], $test[$i]['size_customs'], $test[$i]['from_customs'], $test[$i]['type_customs'], $test[$i]['kind_customs'], $test[$i]['category_customs'], $test[$i]['from_qty_customs'], $test[$i]['to_qty_customs'], $test[$i]['calc_customs'], $test[$i]['increment_customs'], $test[$i]['company_id'] ), $newarray ) ) {
						    	unset($newarray[$i]);
						    	unset($data_costcustoms[$i]);
						    }
						    else {
						    	$newarray[$i][] = $test[$i]['line_customs'];
						    	$newarray[$i][] = $test[$i]['size_customs'];
						    	$newarray[$i][] = $test[$i]['from_customs'];
						    	$newarray[$i][] = $test[$i]['type_customs'];
						    	$newarray[$i][] = $test[$i]['kind_customs'];
						    	$newarray[$i][] = $test[$i]['category_customs'];
						    	$newarray[$i][] = $test[$i]['from_qty_customs'];
						    	$newarray[$i][] = $test[$i]['to_qty_customs'];
						    	$newarray[$i][] = $test[$i]['calc_customs'];
						    	$newarray[$i][] = $test[$i]['increment_customs'];
						    	$newarray[$i][] = $test[$i]['company_id'];
						    }

						}

						$data_costcustoms2 = $test; // create copy to delete dups from
						$newarray2 = array();

						for( $i=0; $i<count($test); $i++ ) {

						    if ( in_array( array($test[$i]['line_customs'], $test[$i]['size_customs'], $test[$i]['from_customs'], $test[$i]['type_customs'], $test[$i]['kind_customs'], $test[$i]['category_customs'], $test[$i]['company_id'] ), $newarray2 ) ) {
						    	unset($newarray2[$i]);
						    	unset($data_costcustoms2[$i]);
						    }
						    else {
						    	$newarray2[$i][] = $test[$i]['line_customs'];
						    	$newarray2[$i][] = $test[$i]['size_customs'];
						    	$newarray2[$i][] = $test[$i]['from_customs'];
						    	$newarray2[$i][] = $test[$i]['type_customs'];
						    	$newarray2[$i][] = $test[$i]['kind_customs'];
						    	$newarray2[$i][] = $test[$i]['category_customs'];
						    	$newarray2[$i][] = $test[$i]['company_id'];
						    }

						}

						// $this->load->helper('comman_helper');
						// pr($test);
						// pr($test);
						// pr($newarray);

						// add selling
						foreach ($data_costcustoms as $value) {
							$data_customs[] = array(
								'quotation_number' => $quotation_number,
								'company_id' => $code_cmpy,
								'revesion_number' => $new_revision,
								'custom_location_id' => $value['from_customs'],
								'custom_line_id' => $value['line_customs'],
								'custom_kind_id' => $value['kind_customs'],
								'selling_service_id' => $value['selling_customs'],
								'container_size_id' => $value['size_customs'],
								'container_type_id' => $value['type_customs'],
								'container_category_id' => $value['category_customs'],
								'selling_currency' => $value['currency_customs'],
								'selling_offering_rate' => $value['offer_customs'],
								'selling_standart_rate' => $value['amount_customs'],
								'from_qty' => $value['from_qty_customs'],
								'to_qty' => $value['to_qty_customs'],
								'calc_type' => $value['calc_customs'],
								'increment_qty' => $value['increment_customs'],
								'start_date' => $value['start_customs'],
								'end_date' => $value['end_customs'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						// add cost trucking
						foreach ($data_costcustoms2 as $value1) {

							// get data cost of selling
							$data_all_cost = $this->M_quotation->get_customs_cost($value1['selling_customs'], $value1['type_customs'], $value1['category_customs'], $value1['size_customs'], $value1['from_customs'], $value1['line_customs'], $value1['kind_customs'], $value1['company_id']);

							if ($data_all_cost->num_rows() < 1) {
								unset($data_all_cost);
							} else {
								for ($k=0; $k < $data_all_cost->num_rows(); $k++) { 
									$cost_insert_customs[] = array(
										'quotation_number' => $quotation_number,
										'revesion_number' => $new_revision,
										'selling_service_id' => $data_all_cost->row($k)->SELLING_SERVICE_ID,
										'custom_location_id' => $data_all_cost->row($k)->CUSTOM_LOCATION_ID,
										'company_id' => $data_all_cost->row($k)->COMPANY_ID,
										'custom_kind_id' => $data_all_cost->row($k)->CUSTOM_KIND_ID,
										'custom_line_id' => $data_all_cost->row($k)->CUSTOM_LINE_ID,
										'container_size_id' => $data_all_cost->row($k)->CONTAINER_SIZE_ID,
										'container_type_id' => $data_all_cost->row($k)->CONTAINER_TYPE_ID,
										'container_category_id' => $data_all_cost->row($k)->CONTAINER_CATEGORY_ID,
										'cost_id' => $data_all_cost->row($k)->COST_ID,
										'start_date' => $data_all_cost->row($k)->START_DATE,
										'end_date' => $data_all_cost->row($k)->END_DATE,
										'from_qty' => $data_all_cost->row($k)->FROM_QTY,
										'to_qty' => $data_all_cost->row($k)->TO_QTY,
										'calc_type' => $data_all_cost->row($k)->CALC_TYPE,
										'cost_type_id' => $data_all_cost->row($k)->COST_TYPE_ID,
										'cost_group_id' => $data_all_cost->row($k)->COST_GROUP_ID,
										'cost_currency' => $data_all_cost->row($k)->COST_CURRENCY,
										'cost_amount' => $data_all_cost->row($k)->COST_AMOUNT,
										'increment_qty' => $data_all_cost->row($k)->INCREMENT_QTY,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}
								// insert data cost
								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $cost_insert_customs)) {
									throw new Exception("Error Processing Request to Entry Cost Customs", 1);
								}
								unset($data_all_cost);
								unset($cost_insert_customs);
							} 
						}

						// insert data selling
						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_customs)) {
							throw new Exception("Error Processing Request to Entry Selling Customs", 1);
						}
						// array_push($success, "Successfully insert data quotation customs!");
						unset($data_customs);
					} else {
						// delete data quotation with last revision in database
						$delete_revision_customs_selling = $this->M_quotation->delete_revision_customs_selling($quotation_number);
						if ($delete_revision_customs_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Customs Selling", 1);
						}
						$delete_revision_customs_cost = $this->M_quotation->delete_revision_customs_cost($quotation_number);
						if ($delete_revision_customs_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Customs Cost", 1);
						}
					}

					if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
						// revision process
						// insert into history selling
						$insert_revision_location_selling = $this->M_quotation->insert_revision_location_selling($quotation_number);
						if ($insert_revision_location_selling == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Location Selling", 1);
						}
						// insert into history cost
						$insert_revision_location_cost = $this->M_quotation->insert_revision_location_cost($quotation_number);
						if ($insert_revision_location_cost == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Location Cost", 1);
						}
						// delete data quotation with last revision in database
						$delete_revision_location_selling = $this->M_quotation->delete_revision_location_selling($quotation_number);
						if ($delete_revision_location_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Location Selling", 1);
						}
						$delete_revision_location_cost = $this->M_quotation->delete_revision_location_cost($quotation_number);
						if ($delete_revision_location_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Location Cost", 1);
						}

						$location = $this->input->post('location');

						// filter for duplicate data
						$data_costlocation = $location;
						$newlocation = array();

						for( $i=0; $i<count($location); $i++ ) {

						    if ( isset($location) && in_array( array( $location[$i]['from'], $location[$i]['to'], $location[$i]['truck'], $location[$i]['calc'], $location[$i]['increment'], $location[$i]['start_date'], $location[$i]['end_date'] ), $newlocation ) ) {
						    	unset($newlocation[$i]);
						    	unset($data_costlocation[$i]);
						    }
						    else {
						    	$newlocation[$i][] = $location[$i]['from'];
						    	$newlocation[$i][] = $location[$i]['to'];
						    	$newlocation[$i][] = $location[$i]['truck'];
						    	$newlocation[$i][] = $location[$i]['calc'];
						    	$newlocation[$i][] = $location[$i]['increment'];
						    	$newlocation[$i][] = $location[$i]['start_date'];
						    	$newlocation[$i][] = $location[$i]['end_date'];
						    }

						}

						// filter for cost data
						$data_costlocation2 = $location;
						$newlocation2 = array();

						for( $i=0; $i<count($location); $i++ ) {

						    if ( isset($location) && in_array( array( $location[$i]['from'], $location[$i]['to'], $location[$i]['truck'] ), $newlocation2 ) ) {
						    	unset($newlocation2[$i]);
						    	unset($data_costlocation2[$i]);
						    }
						    else {
						    	$newlocation2[$i][] = $location[$i]['from'];
						    	$newlocation2[$i][] = $location[$i]['to'];
						    	$newlocation2[$i][] = $location[$i]['truck'];
						    }

						}

						// $this->load->helper('comman_helper');
						// pr($location);

						// add selling location
						foreach ($data_costlocation as $value) {
							$data_location[] = array(
								'quotation_number' => $quotation_number,
								'company_id' => $code_cmpy,
								'revesion_number' => $new_revision,
								'selling_service_id' => $value['selling_service'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'truck_kind_id' => $value['truck'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'distance' => $value['distance'],
								'distance_per_litre' => $value['distanceliter'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						// add cost trucking
						foreach ($data_costlocation2 as $value1) {

							// get data cost of selling
							$data_cost_location = $this->M_quotation->get_location_cost($value1['selling_service'], $value1['from'], $value1['to'], $value1['truck']);

							if ($data_cost_location->num_rows() < 1) {
								unset($data_cost_location);
							} else {
								for ($k=0; $k < $data_cost_location->num_rows(); $k++) { 
									$cost_insert_location[] = array(
										'quotation_number' => $quotation_number,
										'company_service_id' => $data_cost_location->row($k)->COMPANY_SERVICE_ID,
										'revesion_number' => $new_revision,
										'selling_service_id' => $data_cost_location->row($k)->SELLING_SERVICE_ID,
										'from_location_id' => $data_cost_location->row($k)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_location->row($k)->TO_LOCATION_ID,
										'cost_id' => $data_cost_location->row($k)->COST_ID,
										'truck_id' => $data_cost_location->row($k)->TRUCK_ID,
										'start_date' => $data_cost_location->row($k)->START_DATE,
										'end_date' => $data_cost_location->row($k)->END_DATE,
										'increment_qty' => $data_cost_location->row($k)->INCREMENT_QTY,
										'calc_type' => $data_cost_location->row($k)->CALC_TYPE,
										'cost_type_id' => $data_cost_location->row($k)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_location->row($k)->COST_GROUP_ID,
										'cost_currency' => $data_cost_location->row($k)->COST_CURRENCY,
										'cost_amount' => $data_cost_location->row($k)->COST_AMOUNT,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}
								// insert data cost
								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_LOCATION_ATTRIBUTE', $cost_insert_location)) {
									throw new Exception("Error Processing Request to Entry Location Cost", 1);
								}
								unset($data_cost_location);
								unset($cost_insert_location);
							} 
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE', $data_location)) {
							throw new Exception("Error Processing Request to Entry Location Selling", 1);
						}
						// array_push($success, "Successfully insert data quotation location!");
						unset($data_location);
					} else {
						// delete data quotation with last revision in database
						$delete_revision_location_selling = $this->M_quotation->delete_revision_location_selling($quotation_number);
						if ($delete_revision_location_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Location Selling", 1);
						}
						$delete_revision_location_cost = $this->M_quotation->delete_revision_location_cost($quotation_number);
						if ($delete_revision_location_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Location Cost", 1);
						}
					}

					if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
						// revision process
						// insert into history selling
						$insert_revision_weight_selling = $this->M_quotation->insert_revision_weight_selling($quotation_number);
						if ($insert_revision_weight_selling == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Weight Selling", 1);
						}
						// insert into history cost
						$insert_revision_weight_cost = $this->M_quotation->insert_revision_weight_cost($quotation_number);
						if ($insert_revision_weight_cost == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Weight Cost", 1);
						}
						// delete data quotation with last revision in database
						$delete_revision_weight_selling = $this->M_quotation->delete_revision_weight_selling($quotation_number);
						if ($delete_revision_weight_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Weight Selling", 1);
						}
						$delete_revision_weight_cost = $this->M_quotation->delete_revision_weight_cost($quotation_number);
						if ($delete_revision_weight_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Weight Cost", 1);
						}

						$weight = $this->input->post('weight');

						// filter for duplicate data
						$data_costweight = $weight;
						$newweight = array();

						for( $i=0; $i<count($weight); $i++ ) {

						    if ( isset($weight) && in_array( array( $weight[$i]['from'], $weight[$i]['to'], $weight[$i]['from_weight'], $weight[$i]['to_weight'], $weight[$i]['calc'], $weight[$i]['increment'] ), $newweight ) ) {
						    	unset($newweight[$i]);
						    	unset($data_costweight[$i]);
						    	$weight_check = "";
						    }
						    else {
						    	$newweight[$i][] = $weight[$i]['from'];
						    	$newweight[$i][] = $weight[$i]['to'];
						    	$newweight[$i][] = $weight[$i]['from_weight'];
						    	$newweight[$i][] = $weight[$i]['to_weight'];
						    	$newweight[$i][] = $weight[$i]['calc'];
						    	$newweight[$i][] = $weight[$i]['increment'];
						    }

						}

						// filter for cost data
						$data_costweight2 = $weight;
						$newweight2 = array();

						for( $i=0; $i<count($weight); $i++ ) {

						    if ( isset($weight) && in_array( array( $weight[$i]['from'], $weight[$i]['to'] ), $newweight2 ) ) {
						    	unset($newweight2[$i]);
						    	unset($data_costweight2[$i]);
						    }
						    else {
						    	$newweight2[$i][] = $weight[$i]['from'];
						    	$newweight2[$i][] = $weight[$i]['to'];
						    }

						}

						// $this->load->helper('comman_helper');
						// pr($weight);

						foreach ($data_costweight as $value) {
							$data_weight[] = array(
								'quotation_number' => $quotation_number,
								'company_id' => $code_cmpy,
								'revesion_number' => $new_revision,
								'selling_service_id' => $value['selling_service'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'from_weight' => $value['from_weight'],
								'to_weight' => $value['to_weight'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'measurement_unit' => $value['measurement'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						// add cost weight
						foreach ($data_costweight2 as $value1) {

							// get data cost of selling
							$data_cost_weight = $this->M_quotation->get_weight_cost($value1['selling_service'], $value1['from'], $value1['to']);

							if ($data_cost_weight->num_rows() < 1) {
								unset($data_cost_weight);
							} else {
								for ($k=0; $k < $data_cost_weight->num_rows(); $k++) { 
									$cost_insert_weight[] = array(
										'quotation_number' => $quotation_number,
										'company_service_id' => $data_cost_weight->row($k)->COMPANY_SERVICE_ID,
										'revesion_number' => $new_revision,
										'selling_service_id' => $data_cost_weight->row($k)->SELLING_SERVICE_ID,
										'from_location_id' => $data_cost_weight->row($k)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_weight->row($k)->TO_LOCATION_ID,
										'from_weight' => $data_cost_weight->row($k)->FROM_WEIGHT,
										'to_weight' => $data_cost_weight->row($k)->TO_WEIGHT,
										'cost_id' => $data_cost_weight->row($k)->COST_ID,
										'start_date' => $data_cost_weight->row($k)->START_DATE,
										'end_date' => $data_cost_weight->row($k)->END_DATE,
										'increment_qty' => $data_cost_weight->row($k)->INCREMENT_QTY,
										'calc_type' => $data_cost_weight->row($k)->CALC_TYPE,
										'cost_type_id' => $data_cost_weight->row($k)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_weight->row($k)->COST_GROUP_ID,
										'cost_currency' => $data_cost_weight->row($k)->COST_CURRENCY,
										'cost_amount' => $data_cost_weight->row($k)->COST_AMOUNT,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}
								// insert data cost
								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_WEIGHT_ATTRIBUTE', $cost_insert_weight)) {
									throw new Exception("Error Processing Request to Entry Weight Cost", 1);
								}
								unset($data_cost_weight);
								unset($cost_insert_weight);
							} 
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE', $data_weight)) {
							throw new Exception("Error Processing Request to Entry Weight Selling", 1);
						}
						// array_push($success, "Successfully insert data quotation weight!");
						unset($data_weight);
					} else {
						// delete data quotation with last revision in database
						$delete_revision_weight_selling = $this->M_quotation->delete_revision_weight_selling($quotation_number);
						if ($delete_revision_weight_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Weight Selling", 1);
						}
						$delete_revision_weight_cost = $this->M_quotation->delete_revision_weight_cost($quotation_number);
						if ($delete_revision_weight_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Weight Cost", 1);
						}
					}

					if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
						// revision process
						// insert into history selling
						$insert_revision_ocean_selling = $this->M_quotation->insert_revision_ocean_selling($quotation_number);
						if ($insert_revision_ocean_selling == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Ocean Selling", 1);
						}
						// insert into history cost
						$insert_revision_ocean_cost = $this->M_quotation->insert_revision_ocean_cost($quotation_number);
						if ($insert_revision_ocean_cost == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Ocean Cost", 1);
						}
						// delete data quotation with last revision in database
						$delete_revision_ocean_selling = $this->M_quotation->delete_revision_ocean_selling($quotation_number);
						if ($delete_revision_ocean_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Ocean Selling", 1);
						}
						$delete_revision_ocean_cost = $this->M_quotation->delete_revision_ocean_cost($quotation_number);
						if ($delete_revision_ocean_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Ocean Cost", 1);
						}

						$ocean = $this->input->post('ocean');

						// for cost
						$data_costocean = $ocean;
						$newocean = array();

						for( $i=0; $i<count($ocean); $i++ ) {

						    if ( in_array( array( $ocean[$i]['size'], $ocean[$i]['to'], $ocean[$i]['type'], $ocean[$i]['category'], $ocean[$i]['from'], $ocean[$i]['charge'], $ocean[$i]['from_qty'], $ocean[$i]['to_qty'], $ocean[$i]['calc'], $ocean[$i]['increment'] ), $newocean ) ) {
						    	unset($newocean[$i]);
						    	unset($data_costocean[$i]);
						    }
						    else {
						    	$newocean[$i][] = $ocean[$i]['size'];
						    	$newocean[$i][] = $ocean[$i]['to'];
						    	$newocean[$i][] = $ocean[$i]['type'];
						    	$newocean[$i][] = $ocean[$i]['category'];
						    	$newocean[$i][] = $ocean[$i]['from'];
						    	$newocean[$i][] = $ocean[$i]['charge'];
						    	$newocean[$i][] = $ocean[$i]['from_qty'];
						    	$newocean[$i][] = $ocean[$i]['to_qty'];
						    	$newocean[$i][] = $ocean[$i]['calc'];
						    	$newocean[$i][] = $ocean[$i]['increment'];
						    }

						}

						// for cost
						$data_costocean2 = $ocean;
						$newocean2 = array();

						for( $i=0; $i<count($ocean); $i++ ) {

						    if ( in_array( array( $ocean[$i]['size'], $ocean[$i]['to'], $ocean[$i]['type'], $ocean[$i]['category'], $ocean[$i]['from'], $ocean[$i]['charge'] ), $newocean2 ) ) {
						    	unset($newocean2[$i]);
						    	unset($data_costocean2[$i]);
						    }
						    else {
						    	$newocean2[$i][] = $ocean[$i]['size'];
						    	$newocean2[$i][] = $ocean[$i]['to'];
						    	$newocean2[$i][] = $ocean[$i]['type'];
						    	$newocean2[$i][] = $ocean[$i]['category'];
						    	$newocean2[$i][] = $ocean[$i]['from'];
						    	$newocean2[$i][] = $ocean[$i]['charge'];
						    }

						}

						$this->load->helper('comman_helper');
						// pr($newtrucking);
						// pr($data_costtrucking);
						// pr($trucking);

						// input quotation trucking
						foreach ($data_costocean as $value) {
							$data_ocean[] = array(
								'quotation_number' => $quotation_number,
								'company_id' => $code_cmpy,
								'revesion_number' => $new_revision,
								'selling_service_id' => $value['selling_service'],
								'container_size_id' => $value['size'],
								'container_type_id' => $value['type'],
								'container_category_id' => $value['category'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'from_qty' => $value['from_qty'],
								'to_qty' => $value['to_qty'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'user_id' => $this->nik,
								'user_date' => $date,
								'charge_id' => $value['charge']
							);
						}

						foreach ($data_costocean2 as $value1) {

							// add cost trucking selling
							$data_cost_ocean = $this->M_quotation->get_ocean_cost($value1['selling_service'], $value1['type'], $value1['category'], $value1['from'], $value1['to'], $value1['size'], $value1['charge']);
							if ($data_cost_ocean->num_rows() < 1) {
								unset($data_cost_ocean);
							} else {
								for ($j=0; $j < $data_cost_ocean->num_rows(); $j++) { 
									$cost_insert_ocean[] = array(
										'quotation_number' => $quotation_number,
										'company_service_id' => $data_cost_ocean->row($j)->COMPANY_SERVICE_ID,
										'revesion_number' => $new_revision,
										'selling_service_id' => $data_cost_ocean->row($j)->SELLING_SERVICE_ID,
										'container_size_id' => $data_cost_ocean->row($j)->CONTAINER_SIZE_ID,
										'container_type_id' => $data_cost_ocean->row($j)->CONTAINER_TYPE_ID,
										'container_category_id' => $data_cost_ocean->row($j)->CONTAINER_CATEGORY_ID,
										'from_qty' => $data_cost_ocean->row($j)->FROM_QTY,
										'to_qty' => $data_cost_ocean->row($j)->TO_QTY,
										'from_location_id' => $data_cost_ocean->row($j)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_ocean->row($j)->TO_LOCATION_ID,
										'start_date' => $data_cost_ocean->row($j)->START_DATE,
										'end_date' => $data_cost_ocean->row($j)->END_DATE,
										'cost_id' => $data_cost_ocean->row($j)->COST_ID,
										'cost_type_id' => $data_cost_ocean->row($j)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_ocean->row($j)->COST_GROUP_ID,
										'calc_type' => $data_cost_ocean->row($j)->CALC_TYPE,
										'cost_currency' => $data_cost_ocean->row($j)->COST_CURRENCY,
										'cost_amount' => $data_cost_ocean->row($j)->COST_AMOUNT,
										'increment_qty' => $data_cost_ocean->row($j)->INCREMENT_QTY,
										'user_id' => $this->nik,
										'user_date' => $date,
										'charge_id' => $data_cost_ocean->row($j)->CHARGE_ID
									);
								}

								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE', $cost_insert_ocean)) {
									throw new Exception("Error Processing Request to Entry Ocean Freight Cost", 1);
								}
								unset($data_cost_ocean);
								unset($cost_insert_ocean);
							}
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE', $data_ocean)) {
							throw new Exception("Error Processing Request to Ocean Freight Selling", 1);
						}
						// array_push($success, "Successfully insert data quotation ocean freight!");
						unset($data_ocean);
					} else {
						// delete data quotation with last revision in database
						$delete_revision_ocean_selling = $this->M_quotation->delete_revision_ocean_selling($quotation_number);
						if ($delete_revision_ocean_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Ocean Selling", 1);
						}
						$delete_revision_ocean_cost = $this->M_quotation->delete_revision_ocean_cost($quotation_number);
						if ($delete_revision_ocean_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Ocean Cost", 1);
						}
					}

					// delete quotation
					$delete_quotation = $this->M_quotation->delete_quotation($quotation_number);
					if ($delete_quotation == FALSE) {
						throw new Exception("Error Processing Request to Delete Quotation", 1);
					}
					$delete_quotation_services = $this->M_quotation->delete_quotation_services($quotation_number);
					if ($delete_quotation_services == FALSE) {
						throw new Exception("Error Processing Request to Delete Quotation Services", 1);
					}
					$this->M_quotation->delete_transaction($quotation_number, 'D1002');

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Revision Quotation", 1);
					} else {
						$this->session->set_flashdata('success_revision_quotation', "Successfully revision quotation!");
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed_revision_quotation', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function amendment_agreement()
	{
		$agreement_number = $this->uri->segment(3);
		$quotation_number = $this->uri->segment(4);

		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;
		
		// revision quotation
		// selling trucking
		$data['data_trucking'] = $this->M_quotation->get_data_trucking($code_cmpy)->result();
		$data['data_customs'] = $this->M_quotation->get_data_customs($code_cmpy)->result();
		$data['data_location'] = $this->M_quotation->get_data_location()->result();
		$data['data_weight'] = $this->M_quotation->get_data_weight()->result();
		$data['data_ocean'] = $this->M_quotation->get_data_ocean()->result();
		$data['template'] = $this->M_quotation->get_template()->result();

		// $quotation_number = $this->uri->segment(3);

		$data['agreement_number'] = $agreement_number;
		$data['quotation_number'] = $quotation_number;

		$data['start_date_agreement'] = $this->M_quotation->get_data_agreement($agreement_number)->row()->START_DATE;
		$data['end_date_agreement'] = $this->M_quotation->get_data_agreement($agreement_number)->row()->END_DATE;
		$data['document_number'] = $this->M_quotation->get_data_agreement2($agreement_number)->row()->AGREEMENT_DOCUMENT_NUMBER;

		

		$quote_number = substr($quotation_number, 2,5). "/" . $code_cmpy . "-QUO/" . date('m/Y');

		$date = date("Y-m-d");

		// $quotation_number = $this->input->post('quotation_number');

		$data['data_trucking2'] = $this->M_quotation->get_revision_trucking($quotation_number)->result();
		$data['data_customs2'] = $this->M_quotation->get_revision_customs($quotation_number)->result();
		$data['data_location2'] = $this->M_quotation->get_revision_location($quotation_number)->result();
		$data['data_weight2'] = $this->M_quotation->get_revision_weight($quotation_number)->result();
		$data['data_ocean_freight2'] = $this->M_quotation->get_revision_ocean_freight($quotation_number)->result();
	
		$data['count_trucking2'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs2'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();
		$data['count_location2'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight2'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight2'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		// lets test
		$data['combo_trucking'] = $this->M_quotation->check_combo($quotation_number, 'SS01')->num_rows();
		$data['combo_customs'] = $this->M_quotation->check_combo($quotation_number, 'SS02')->num_rows();
		$data['combo_location'] = $this->M_quotation->check_combo($quotation_number, 'SS04')->num_rows();
		$data['combo_weight'] = $this->M_quotation->check_combo($quotation_number, 'SS05')->num_rows();
		$data['combo_ocean'] = $this->M_quotation->check_combo($quotation_number, 'SS03')->num_rows();
		$company_id = $this->M_quotation->get_data_quotation($quotation_number)->row()->CUSTOMER_ID;

		$data['marketing_id'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->MARKETING_ID;

		$data['remarks'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->REMARKS;
		// // $this->load->helper('comman_helper');
		// // pr($company_id);
		$data['template_text1'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->TEMPLATE_TEXT1;
		$data['template_text2'] = $this->M_quotation->get_data_quotation($quotation_number)->row()->TEMPLATE_TEXT2;
		$data['customer_id_revision'] = $this->M_quotation->check_data_revision($company_id)->row()->COMPANY_ID;

		// $this->load->helper('comman_helper');
		// pr($data['customer_id_revision'] );
		$data['customer_name_revision'] = $this->M_quotation->check_data_revision($company_id)->row()->NAME;
		$data['start_date_revision'] = $this->M_quotation->get_date_revision($quotation_number)->row()->START_DATE;
		$data['end_date_revision'] = $this->M_quotation->get_date_revision($quotation_number)->row()->END_DATE;

		$this->form_validation->set_rules('agreement_number', 'Agreement Number', 'required');
		$this->form_validation->set_rules('start_date_agreement', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date_agreement', 'End Date', 'required');

		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// insert data
		if (isset($_POST)) {
			// declare variable
			// $quotation_number = $this->input->post('quotation_number');

			// quotation
			$company_name = $this->input->post('company_name');
			$company_quo_id = $this->input->post('company_id');
			$sales_id = $this->input->post('sales_id');
			$pic_id = $this->input->post('pic_id');
			$remarks = $this->input->post('remarks');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$syarat = $this->input->post('template_text1');
			$term = $this->input->post('template_text2');

			// agreement
			$start_date_agreement = $this->input->post('start_date_agreement');
			$end_date_agreement = $this->input->post('end_date_agreement');

			// declare variable error
			$error_trucking = "";
			$error_customs = "";
			$error_location = "";
			$error_weight = "";
			$error_ocean = "";

			if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
				$trucking = $this->input->post('trucking');
				if (count($trucking) < 1) {
					$error_trucking = "error";
				} else {
					$error_trucking = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
				$test = $this->input->post('customs');
				if (count($test) < 1) {
					$error_customs = "error";
				} else {
					$error_customs = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
				$location = $this->input->post('location');
				if (count($location) < 1) {
					$error_location = "error";
				} else {
					$error_location = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
				$weight = $this->input->post('weight');
				if (count($weight) < 1) {
					$error_weight = "error";
				} else {
					$error_weight = "ada";
				}
			}
			if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
				$ocean = $this->input->post('ocean');
				if (count($ocean) < 1) {
					$error_ocean = "error";
				} else {
					$error_ocean = "ada";
				}
			}

			// check last revision number
			$check_revision = $this->M_quotation->check_revision($quotation_number)->row()->REVESION_NUMBER;
			
			$new_revision = $check_revision + 1;

			// check last amendment agreement
			$check_agreement = $this->M_quotation->check_agreement($agreement_number)->row()->AMENDMENT_NUMBER;
			
			$new_agreement = $check_agreement + 1;

			// declare check
			$trucking_check = "";
			$customs_check = "";
			$location_check = "";
			$weight_check = "";
			$ocean_check = "";

			if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
				$trucking = $this->input->post('trucking');
				$data_costtrucking = $trucking; // create copy to delete dups from
				$newtrucking = array();

				for( $i=0; $i<count($trucking); $i++ ) {

				    if ( isset($trucking) && in_array( array( $trucking[$i]['size'], $trucking[$i]['to'], $trucking[$i]['type'], $trucking[$i]['category'], $trucking[$i]['from'], $trucking[$i]['from_qty'], $trucking[$i]['to_qty'], $trucking[$i]['company_id'] ), $newtrucking ) ) {
				    	unset($newtrucking[$i]);
				    	unset($data_costtrucking[$i]);
				    	$trucking_check = "error";
				    }
				    else {
				    	$newtrucking[$i][] = $trucking[$i]['size'];
				    	$newtrucking[$i][] = $trucking[$i]['to'];
				    	$newtrucking[$i][] = $trucking[$i]['type'];
				    	$newtrucking[$i][] = $trucking[$i]['category'];
				    	$newtrucking[$i][] = $trucking[$i]['from'];
				    	$newtrucking[$i][] = $trucking[$i]['from_qty'];
				    	$newtrucking[$i][] = $trucking[$i]['to_qty'];
				    	$newtrucking[$i][] = $trucking[$i]['company_id'];
				    }

				}
			}

			if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
				
				$test = $this->input->post('customs');

				$data_costcustoms = $test; // create copy to delete dups from
				$newarray = array();

				for( $i=0; $i<count($test); $i++ ) {

				    if ( isset($test) && in_array( array($test[$i]['line_customs'], $test[$i]['size_customs'], $test[$i]['from_customs'], $test[$i]['type_customs'], $test[$i]['kind_customs'], $test[$i]['category_customs'], $test[$i]['from_qty_customs'], $test[$i]['to_qty_customs'], $test[$i]['calc_customs'], $test[$i]['increment_customs'], $test[$i]['company_id'] ), $newarray ) ) {
				    	unset($newarray[$i]);
				    	unset($data_costcustoms[$i]);
				    	$customs_check = "error";
				    }
				    else {
				    	$newarray[$i][] = $test[$i]['line_customs'];
				    	$newarray[$i][] = $test[$i]['size_customs'];
				    	$newarray[$i][] = $test[$i]['from_customs'];
				    	$newarray[$i][] = $test[$i]['type_customs'];
				    	$newarray[$i][] = $test[$i]['kind_customs'];
				    	$newarray[$i][] = $test[$i]['category_customs'];
				    	$newarray[$i][] = $test[$i]['from_qty_customs'];
				    	$newarray[$i][] = $test[$i]['to_qty_customs'];
				    	$newarray[$i][] = $test[$i]['calc_customs'];
				    	$newarray[$i][] = $test[$i]['increment_customs'];
				    	$newarray[$i][] = $test[$i]['company_id'];
				    }

				}
			}

			if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
				$location = $this->input->post('location');

				// filter for duplicate data
				$data_costlocation3 = $location;
				$newlocation3 = array();

				// , $location[$i]['start_date'], $location[$i]['end_date']

				for( $i=0; $i<count($location); $i++ ) {

				    if ( isset($location) && in_array( array( $location[$i]['from'], $location[$i]['to'], $location[$i]['truck'], $location[$i]['calc'], $location[$i]['increment'], $location[$i]['distance'], $location[$i]['distanceliter'] ), $newlocation3 ) ) {
				    	unset($newlocation3[$i]);
				    	unset($data_costlocation3[$i]);
				    	$location_check = "error";
				    	// return 0;
				    }
				    else {
				    	$newlocation3[$i][] = $location[$i]['from'];
				    	$newlocation3[$i][] = $location[$i]['to'];
				    	$newlocation3[$i][] = $location[$i]['truck'];
				    	$newlocation3[$i][] = $location[$i]['calc'];
				    	$newlocation3[$i][] = $location[$i]['increment'];
				    	// $newlocation3[$i][] = $location[$i]['start_date'];
				    	// $newlocation3[$i][] = $location[$i]['end_date'];
				    	$newlocation3[$i][] = $location[$i]['distance'];
				    	$newlocation3[$i][] = $location[$i]['distanceliter'];
				    	// return 0;
				    }

				}
			}

			if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
				$weight = $this->input->post('weight');

				// filter for duplicate data
				$data_costweight = $weight;
				$newweight = array();

				for( $i=0; $i<count($weight); $i++ ) {

				    if ( isset($weight) && in_array( array( $weight[$i]['from'], $weight[$i]['to'], $weight[$i]['from_weight'], $weight[$i]['to_weight'], $weight[$i]['calc'], $weight[$i]['increment'] ), $newweight ) ) {
				    	unset($newweight[$i]);
				    	unset($data_costweight[$i]);
				    	$weight_check = "error";
				    }
				    else {
				    	$newweight[$i][] = $weight[$i]['from'];
				    	$newweight[$i][] = $weight[$i]['to'];
				    	$newweight[$i][] = $weight[$i]['from_weight'];
				    	$newweight[$i][] = $weight[$i]['to_weight'];
				    	$newweight[$i][] = $weight[$i]['calc'];
				    	$newweight[$i][] = $weight[$i]['increment'];
				    }

				}
			}

			if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
				$ocean = $this->input->post('ocean');

				// for cost
				$data_costocean = $ocean;
				$newocean = array();

				for( $i=0; $i<count($ocean); $i++ ) {

				    if ( in_array( array( $ocean[$i]['size'], $ocean[$i]['to'], $ocean[$i]['type'], $ocean[$i]['category'], $ocean[$i]['from'], $ocean[$i]['charge'], $ocean[$i]['from_qty'], $ocean[$i]['to_qty'], $ocean[$i]['calc'], $ocean[$i]['increment'] ), $newocean ) ) {
				    	unset($newocean[$i]);
				    	unset($data_costocean[$i]);
				    }
				    else {
				    	$newocean[$i][] = $ocean[$i]['size'];
				    	$newocean[$i][] = $ocean[$i]['to'];
				    	$newocean[$i][] = $ocean[$i]['type'];
				    	$newocean[$i][] = $ocean[$i]['category'];
				    	$newocean[$i][] = $ocean[$i]['from'];
				    	$newocean[$i][] = $ocean[$i]['charge'];
				    	$newocean[$i][] = $ocean[$i]['from_qty'];
				    	$newocean[$i][] = $ocean[$i]['to_qty'];
				    	$newocean[$i][] = $ocean[$i]['calc'];
				    	$newocean[$i][] = $ocean[$i]['increment'];
				    }

				}
			}

			// entry to table quotation
			if ($this->form_validation->run() == false) {
				$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($error_trucking == "error") {
				$data['error_trucking'] = "error";
        		$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($error_customs == "error") {
				$data['error_customs'] = "error";
        		$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($error_location == "error") {
				$data['error_location'] = "error";
        		$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($error_weight == "error") {
				$data['error_weight'] = "error";
        		$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($error_ocean == "error") {
				$data['error_ocean'] = "error";
        		$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($trucking_check == "error") {
				$data['truck_error'] = "error";
				$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($customs_check == "error") {
				$data['customs_error'] = "error";
				$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($location_check == "error") {
				$data['location_error'] = "error";
				$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($weight_check == "error") {
				$data['weight_error'] = "error";
				$this->load->view('quotations/v_amendmentagreement', $data);
			} elseif ($ocean_check == "error") {
				$data['ocean_error'] = "error";
				$this->load->view('quotations/v_amendmentagreement', $data);
			} else {
				$this->db->trans_begin();
				try {

					$services = array();

					if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
						array_push($services, "SS01");
					}
					if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
						array_push($services, "SS02");
					}
					if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
						array_push($services, "SS04");
					}
					if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
						array_push($services, "SS05");
					}
					if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
						array_push($services, "SS03");
					}

					$data_services = array();
					$data_quotation = array();
					$data_trucking = array();
					$data_customs = array();
					$data_location = array();
					$data_weight = array();
					$data_ocean = array();

					// backup to history
					$backup_quotation = $this->M_quotation->backup_quotation($quotation_number);
					if ($backup_quotation == FALSE) {
						throw new Exception("Error Processing Request to Backup Quotation", 1);
					}
					$backup_quotation_services = $this->M_quotation->backup_quotation_services($quotation_number);
					if ($backup_quotation_services == FALSE) {
						throw new Exception("Error Processing Request to Backup Quotation Services", 1);
					}
					$backup_agreement = $this->M_quotation->backup_agreement($agreement_number);
					if ($backup_agreement == FALSE) {
						throw new Exception("Error Processing Request to Backup Agreement", 1);
					}
					
					// input quotation services

					for ($i=0; $i < count($services); $i++) { 
						$data_services[] = array(
							'quotation_number' => $quotation_number,
							'selling_service_id' => $services[$i],
							'revesion_number' => $new_revision
						);
					}

					if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE', $data_services)) {
						throw new Exception("Error Processing Request to Insert Quotation Services", 1);
					}
					// array_push($success, "Successfully insert data quotation services!");
					unset($data_services);
					unset($services);

					// set to input quotation
					$data_quotation = array(
						'quotation_number' => $quotation_number,
						'quotation_document_number' => $quote_number,
						'company_id' => $code_cmpy,
						'revesion_number' => $new_revision,
						'quotation_date' => $start_date,
						'quotation_periode_start' => $start_date,
						'quotation_periode_end' => $end_date,
						'customer_id' => $company_quo_id,
						'approval_status' => 'A',
						'user_id' => $this->nik,
						'user_date' => $date,
						'template_text1' => $syarat,
						'template_text2' => $term,
						'marketing_id' => $sales_id,
						'status_agreement' => '1'
					);
					if (!$this->db->insert('dbo.TRQUOTATION', $data_quotation)) {
						throw new Exception("Error Processing Request to Entry Quotation", 1);
					}
					// array_push($success, "Successfully insert data quotation!");

					unset($data_quotation);

					// update data agreement
					$update_agreement = array(
						'APPROVAL_STATUS' => 'N',
						'amendment_number' => $new_agreement,
						'agreement_periode_start' => $start_date_agreement,
						'agreement_periode_end' => $end_date_agreement
					);
					$update_agreements = $this->M_quotation->update_agreement($agreement_number, 'dbo.TRAGREEMENT', $update_agreement);
					if ($update_agreements == FALSE) {
						throw new Exception("Error Processing Request to Update Agreement", 1);
					}

					// insert to trapproval_status_transaction
					$data_trappr = array(
							'TRANSACTION_NUMBER' => $agreement_number,
							'DOCUMENT_ID' => 'D1001',
							'REVISION_NUMBER' => $new_agreement,
							'COMPANY_ID' => $code_cmpy,
							'REQUEST_APPROVAL_DATE' => $date,
							'APPROVAL_STATUS' => 'N'
						);
					if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_trappr)) {
						throw new Exception("Error Processing Request to Insert Approval Status Transaction", 1);
					}

					unset($data_trappr);

					// check email who must approve quotation
					$data_nik = array();
					$data_email = array();
					$check_approval_satu = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL1;
					$check_approval_dua = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL2;
					$check_approval_tiga = $this->M_quotation->check_approval('D1002')->row()->APPROVAL_LEVEL3;

					if ($check_approval_satu == 'Y') {
						$get_nik_appr_satu = $this->M_quotation->get_nik_appr('D1002', '1')->result();
						foreach ($get_nik_appr_satu as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_dua == 'Y') {
						$get_nik_appr_dua = $this->M_quotation->get_nik_appr('D1002', '2')->result();
						foreach ($get_nik_appr_dua as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					if ($check_approval_tiga == 'Y') {
						$get_nik_appr_tiga = $this->M_quotation->get_nik_appr('D1002', '3')->result();
						foreach ($get_nik_appr_tiga as $key => $value) {
							array_push($data_nik, $value->APPROVAL_USER_ID);
						}
					}

					// get email
					for ($nik=0; $nik < count($data_nik); $nik++) { 
						$temp_email = $this->M_quotation->get_email($data_nik[$nik])->row()->email;
						array_push($data_email, $temp_email);
					}

					// sent email to pic approval
					$config['protocol'] = "smtp";
					$config['smtp_host'] = "192.168.11.220";
					$config['smtp_port'] = "25";
					$config['charset'] = "utf-8";

					for ($e=0; $e < count($data_email); $e++) { 
						$this->email->initialize($config);

						$this->email->from('no-reply@hanomansp.com', 'Hanoman Sakti Application');
						// $this->email->to('valdi.abrar@hanomansp.com');
						$this->email->to($data_email[$e]);

						$this->email->subject('Amendment Agreement Approval');
						$data = array('amendment_number' => $agreement_number, 'customer' => $company_name);
						$view = $this->load->view('layouts/v_email_amendment.php', $data, true);
						$this->email->message($view);
						$this->email->set_mailtype('html');

						$this->email->send();
					}

					if (isset($_POST['service']) && in_array('trucking', $_POST['service'])) {
						// revision process
						// insert into history selling
						$insert_revision_trucking_selling = $this->M_quotation->insert_revision_trucking_selling($quotation_number);
						if ($insert_revision_trucking_selling == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Trucking Selling", 1);
						}
						// insert into history cost
						$insert_revision_trucking_cost = $this->M_quotation->insert_revision_trucking_cost($quotation_number);
						if ($insert_revision_trucking_cost == FALSE) {
							throw new Exception("Error Processing Request to Insert Revision Trucking Cost", 1);
						}
						// delete data quotation with last revision in database
						$delete_revision_trucking_selling = $this->M_quotation->delete_revision_trucking_selling($quotation_number);
						if ($delete_revision_trucking_selling == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Trucking Selling", 1);
						}
						$delete_revision_trucking_cost = $this->M_quotation->delete_revision_trucking_cost($quotation_number);
						if ($delete_revision_trucking_cost == FALSE) {
							throw new Exception("Error Processing Request to Delete Revision Trucking Cost", 1);
						}

						$trucking = $this->input->post('trucking');

						$data_costtrucking = $trucking; // create copy to delete dups from
						$newtrucking = array();

						for( $i=0; $i<count($trucking); $i++ ) {

						    if ( in_array( array( $trucking[$i]['size'], $trucking[$i]['to'], $trucking[$i]['type'], $trucking[$i]['category'], $trucking[$i]['from'], $trucking[$i]['from_qty'], $trucking[$i]['to_qty'], $trucking[$i]['calc'], $trucking[$i]['increment'], $trucking[$i]['company_id'] ), $newtrucking ) ) {
						    	unset($newtrucking[$i]);
						    	unset($data_costtrucking[$i]);
						    }
						    else {
						    	$newtrucking[$i][] = $trucking[$i]['size'];
						    	$newtrucking[$i][] = $trucking[$i]['to'];
						    	$newtrucking[$i][] = $trucking[$i]['type'];
						    	$newtrucking[$i][] = $trucking[$i]['category'];
						    	$newtrucking[$i][] = $trucking[$i]['from'];
						    	$newtrucking[$i][] = $trucking[$i]['from_qty'];
						    	$newtrucking[$i][] = $trucking[$i]['to_qty'];
						    	$newtrucking[$i][] = $trucking[$i]['calc'];
						    	$newtrucking[$i][] = $trucking[$i]['increment'];
						    	$newtrucking[$i][] = $trucking[$i]['company_id'];
						    }
						}

						$data_costtrucking2 = $trucking; // create copy to delete dups from
						$newtrucking2 = array();

						for( $i=0; $i<count($trucking); $i++ ) {

						    if ( in_array( array( $trucking[$i]['size'], $trucking[$i]['to'], $trucking[$i]['type'], $trucking[$i]['category'], $trucking[$i]['from'], $trucking[$i]['company_id'] ), $newtrucking2 ) ) {
						    	unset($newtrucking2[$i]);
						    	unset($data_costtrucking2[$i]);
						    }
						    else {
						    	$newtrucking2[$i][] = $trucking[$i]['size'];
						    	$newtrucking2[$i][] = $trucking[$i]['to'];
						    	$newtrucking2[$i][] = $trucking[$i]['type'];
						    	$newtrucking2[$i][] = $trucking[$i]['category'];
						    	$newtrucking2[$i][] = $trucking[$i]['from'];
						    	$newtrucking2[$i][] = $trucking[$i]['company_id'];
						    }
						}

						// input quotation trucking
						foreach ($data_costtrucking as $value) {
							$data_trucking[] = array(
								'quotation_number' => $quotation_number,
								'company_id' => $code_cmpy,
								'revesion_number' => $new_revision,
								'selling_service_id' => $value['selling_service'],
								'container_size_id' => $value['size'],
								'container_type_id' => $value['type'],
								'container_category_id' => $value['category'],
								'from_location_id' => $value['from'],
								'to_location_id' => $value['to'],
								'selling_currency' => $value['currency'],
								'selling_offering_rate' => $value['offer_price'],
								'selling_standart_rate' => $value['amount'],
								'from_qty' => $value['from_qty'],
								'to_qty' => $value['to_qty'],
								'calc_type' => $value['calc'],
								'increment_qty' => $value['increment'],
								'start_date' => $value['start_date'],
								'end_date' => $value['end_date'],
								'user_id' => $this->nik,
								'user_date' => $date
							);
						}

						foreach ($data_costtrucking2 as $value1) {

							// add cost trucking selling
							$data_cost_trucking = $this->M_quotation->get_trucking_cost($value1['selling_service'], $value1['type'], $value1['category'], $value1['from'], $value1['to'], $value1['size'], $value1['company_id']);
							if ($data_cost_trucking->num_rows() < 1) {
								unset($data_cost_trucking);
							} else {
								for ($j=0; $j < $data_cost_trucking->num_rows(); $j++) { 
									$cost_insert_trucking[] = array(
										'quotation_number' => $quotation_number,
										'company_id' => $data_cost_trucking->row($j)->COMPANY_ID,
										'revesion_number' => $new_revision,
										'selling_service_id' => $data_cost_trucking->row($j)->SELLING_SERVICE_ID,
										'container_size_id' => $data_cost_trucking->row($j)->CONTAINER_SIZE_ID,
										'container_type_id' => $data_cost_trucking->row($j)->CONTAINER_TYPE_ID,
										'container_category_id' => $data_cost_trucking->row($j)->CONTAINER_CATEGORY_ID,
										'from_qty' => $data_cost_trucking->row($j)->FROM_QTY,
										'to_qty' => $data_cost_trucking->row($j)->TO_QTY,
										'from_location_id' => $data_cost_trucking->row($j)->FROM_LOCATION_ID,
										'to_location_id' => $data_cost_trucking->row($j)->TO_LOCATION_ID,
										'start_date' => $data_cost_trucking->row($j)->START_DATE,
										'end_date' => $data_cost_trucking->row($j)->END_DATE,
										'cost_id' => $data_cost_trucking->row($j)->COST_ID,
										'cost_type_id' => $data_cost_trucking->row($j)->COST_TYPE_ID,
										'cost_group_id' => $data_cost_trucking->row($j)->COST_GROUP_ID,
										'calc_type' => $data_cost_trucking->row($j)->CALC_TYPE,
										'cost_currency' => $data_cost_trucking->row($j)->COST_CURRENCY,
										'cost_amount' => $data_cost_trucking->row($j)->COST_AMOUNT,
										'increment_qty' => $data_cost_trucking->row($j)->INCREMENT_QTY,
										'user_id' => $this->nik,
										'user_date' => $date
									);
								}

								if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE', $cost_insert_trucking)) {
									throw new Exception("Error Processing Request to Entry Container Trucking Cost", 1);
								}
								unset($data_cost_trucking);
								unset($cost_insert_trucking);
							}
						}

						if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking)) {
							throw new Exception("Error Processing Request to Entry Container Trucking Selling", 1);
						}

						// array_push($success, "Successfully insert data quotation trucking!");
						unset($data_trucking);
				} else {
					// delete data quotation with last revision in database
					$delete_revision_trucking_selling = $this->M_quotation->delete_revision_trucking_selling($quotation_number);
					if ($delete_revision_trucking_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Trucking Selling", 1);
					}
					$delete_revision_trucking_cost = $this->M_quotation->delete_revision_trucking_cost($quotation_number);
					if ($delete_revision_trucking_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Trucking Cost", 1);
					}
				}

				if (isset($_POST['service']) && in_array('customs', $_POST['service'])) {
					// revision process
					// insert into history selling
					$insert_revision_customs_selling = $this->M_quotation->insert_revision_customs_selling($quotation_number);
					if ($insert_revision_customs_selling == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Customs Selling", 1);
					}
					// insert into history cost
					$insert_revision_customs_cost = $this->M_quotation->insert_revision_customs_cost($quotation_number);
					if ($insert_revision_customs_cost == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Customs Cost", 1);
					}
					// delete data quotation with last revision in database
					$delete_revision_customs_selling = $this->M_quotation->delete_revision_customs_selling($quotation_number);
					if ($delete_revision_customs_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Customs Selling", 1);
					}
					$delete_revision_customs_cost = $this->M_quotation->delete_revision_customs_cost($quotation_number);
					if ($delete_revision_customs_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Customs Cost", 1);
					}

					$test = $this->input->post('customs');

					$data_costcustoms = $test; // create copy to delete dups from
					$newarray = array();

					for( $i=0; $i<count($test); $i++ ) {

					    if ( in_array( array($test[$i]['line_customs'], $test[$i]['size_customs'], $test[$i]['from_customs'], $test[$i]['type_customs'], $test[$i]['kind_customs'], $test[$i]['category_customs'], $test[$i]['from_qty_customs'], $test[$i]['to_qty_customs'], $test[$i]['calc_customs'], $test[$i]['increment_customs'], $test[$i]['company_id'] ), $newarray ) ) {
					    	unset($newarray[$i]);
					    	unset($data_costcustoms[$i]);
					    }
					    else {
					    	$newarray[$i][] = $test[$i]['line_customs'];
					    	$newarray[$i][] = $test[$i]['size_customs'];
					    	$newarray[$i][] = $test[$i]['from_customs'];
					    	$newarray[$i][] = $test[$i]['type_customs'];
					    	$newarray[$i][] = $test[$i]['kind_customs'];
					    	$newarray[$i][] = $test[$i]['category_customs'];
					    	$newarray[$i][] = $test[$i]['from_qty_customs'];
					    	$newarray[$i][] = $test[$i]['to_qty_customs'];
					    	$newarray[$i][] = $test[$i]['calc_customs'];
					    	$newarray[$i][] = $test[$i]['increment_customs'];
					    	$newarray[$i][] = $test[$i]['company_id'];
					    }

					}

					$data_costcustoms2 = $test; // create copy to delete dups from
					$newarray2 = array();

					for( $i=0; $i<count($test); $i++ ) {

					    if ( in_array( array($test[$i]['line_customs'], $test[$i]['size_customs'], $test[$i]['from_customs'], $test[$i]['type_customs'], $test[$i]['kind_customs'], $test[$i]['category_customs'], $test[$i]['company_id'] ), $newarray2 ) ) {
					    	unset($newarray2[$i]);
					    	unset($data_costcustoms2[$i]);
					    }
					    else {
					    	$newarray2[$i][] = $test[$i]['line_customs'];
					    	$newarray2[$i][] = $test[$i]['size_customs'];
					    	$newarray2[$i][] = $test[$i]['from_customs'];
					    	$newarray2[$i][] = $test[$i]['type_customs'];
					    	$newarray2[$i][] = $test[$i]['kind_customs'];
					    	$newarray2[$i][] = $test[$i]['category_customs'];
					    	$newarray2[$i][] = $test[$i]['company_id'];
					    }

					}

					// add selling
					foreach ($data_costcustoms as $value) {
						$data_customs[] = array(
							'quotation_number' => $quotation_number,
							'company_id' => $code_cmpy,
							'revesion_number' => $new_revision,
							'custom_location_id' => $value['from_customs'],
							'custom_line_id' => $value['line_customs'],
							'custom_kind_id' => $value['kind_customs'],
							'selling_service_id' => $value['selling_customs'],
							'container_size_id' => $value['size_customs'],
							'container_type_id' => $value['type_customs'],
							'container_category_id' => $value['category_customs'],
							'selling_currency' => $value['currency_customs'],
							'selling_offering_rate' => $value['offer_customs'],
							'selling_standart_rate' => $value['amount_customs'],
							'from_qty' => $value['from_qty_customs'],
							'to_qty' => $value['to_qty_customs'],
							'calc_type' => $value['calc_customs'],
							'increment_qty' => $value['increment_customs'],
							'start_date' => $value['start_customs'],
							'end_date' => $value['end_customs'],
							'user_id' => $this->nik,
							'user_date' => $date
						);
					}

					// add cost trucking
					foreach ($data_costcustoms2 as $value1) {

						// get data cost of selling
						$data_all_cost = $this->M_quotation->get_customs_cost($value1['selling_customs'], $value1['type_customs'], $value1['category_customs'], $value1['size_customs'], $value1['from_customs'], $value1['line_customs'], $value1['kind_customs'], $value1['company_id']);

						if ($data_all_cost->num_rows() < 1) {
							unset($data_all_cost);
						} else {
							for ($k=0; $k < $data_all_cost->num_rows(); $k++) { 
								$cost_insert_customs[] = array(
									'quotation_number' => $quotation_number,
									'revesion_number' => $new_revision,
									'selling_service_id' => $data_all_cost->row($k)->SELLING_SERVICE_ID,
									'custom_location_id' => $data_all_cost->row($k)->CUSTOM_LOCATION_ID,
									'company_id' => $data_all_cost->row($k)->COMPANY_ID,
									'custom_kind_id' => $data_all_cost->row($k)->CUSTOM_KIND_ID,
									'custom_line_id' => $data_all_cost->row($k)->CUSTOM_LINE_ID,
									'container_size_id' => $data_all_cost->row($k)->CONTAINER_SIZE_ID,
									'container_type_id' => $data_all_cost->row($k)->CONTAINER_TYPE_ID,
									'container_category_id' => $data_all_cost->row($k)->CONTAINER_CATEGORY_ID,
									'cost_id' => $data_all_cost->row($k)->COST_ID,
									'start_date' => $data_all_cost->row($k)->START_DATE,
									'end_date' => $data_all_cost->row($k)->END_DATE,
									'from_qty' => $data_all_cost->row($k)->FROM_QTY,
									'to_qty' => $data_all_cost->row($k)->TO_QTY,
									'calc_type' => $data_all_cost->row($k)->CALC_TYPE,
									'cost_type_id' => $data_all_cost->row($k)->COST_TYPE_ID,
									'cost_group_id' => $data_all_cost->row($k)->COST_GROUP_ID,
									'cost_currency' => $data_all_cost->row($k)->COST_CURRENCY,
									'cost_amount' => $data_all_cost->row($k)->COST_AMOUNT,
									'increment_qty' => $data_all_cost->row($k)->INCREMENT_QTY,
									'user_id' => $this->nik,
									'user_date' => $date
								);
							}
							// insert data cost
							if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $cost_insert_customs)) {
								throw new Exception("Error Processing Request to Entry Cost Customs", 1);
							}
							unset($data_all_cost);
							unset($cost_insert_customs);
						} 
					}

					// insert data selling
					if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_customs)) {
						throw new Exception("Error Processing Request to Entry Selling Customs", 1);
					}
					// array_push($success, "Successfully insert data quotation customs!");
					unset($data_customs);
				} else {
					// delete data quotation with last revision in database
					$delete_revision_customs_selling = $this->M_quotation->delete_revision_customs_selling($quotation_number);
					if ($delete_revision_customs_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Customs Selling", 1);
					}
					$delete_revision_customs_cost = $this->M_quotation->delete_revision_customs_cost($quotation_number);
					if ($delete_revision_customs_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Customs Cost", 1);
					}
				}

				if (isset($_POST['service']) && in_array('location', $_POST['service'])) {
					// revision process
					// insert into history selling
					$insert_revision_location_selling = $this->M_quotation->insert_revision_location_selling($quotation_number);
					if ($insert_revision_location_selling == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Location Selling", 1);
					}
					// insert into history cost
					$insert_revision_location_cost = $this->M_quotation->insert_revision_location_cost($quotation_number);
					if ($insert_revision_location_cost == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Location Cost", 1);
					}
					// delete data quotation with last revision in database
					$delete_revision_location_selling = $this->M_quotation->delete_revision_location_selling($quotation_number);
					if ($delete_revision_location_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Location Selling", 1);
					}
					$delete_revision_location_cost = $this->M_quotation->delete_revision_location_cost($quotation_number);
					if ($delete_revision_location_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Location Cost", 1);
					}

					$location = $this->input->post('location');

					// filter for duplicate data
					$data_costlocation = $location;
					$newlocation = array();

					for( $i=0; $i<count($location); $i++ ) {

					    if ( isset($location) && in_array( array( $location[$i]['from'], $location[$i]['to'], $location[$i]['truck'], $location[$i]['calc'], $location[$i]['increment'], $location[$i]['start_date'], $location[$i]['end_date'] ), $newlocation ) ) {
					    	unset($newlocation[$i]);
					    	unset($data_costlocation[$i]);
					    }
					    else {
					    	$newlocation[$i][] = $location[$i]['from'];
					    	$newlocation[$i][] = $location[$i]['to'];
					    	$newlocation[$i][] = $location[$i]['truck'];
					    	$newlocation[$i][] = $location[$i]['calc'];
					    	$newlocation[$i][] = $location[$i]['increment'];
					    	$newlocation[$i][] = $location[$i]['start_date'];
					    	$newlocation[$i][] = $location[$i]['end_date'];
					    }

					}

					// filter for cost data
					$data_costlocation2 = $location;
					$newlocation2 = array();

					for( $i=0; $i<count($location); $i++ ) {

					    if ( isset($location) && in_array( array( $location[$i]['from'], $location[$i]['to'], $location[$i]['truck'] ), $newlocation2 ) ) {
					    	unset($newlocation2[$i]);
					    	unset($data_costlocation2[$i]);
					    }
					    else {
					    	$newlocation2[$i][] = $location[$i]['from'];
					    	$newlocation2[$i][] = $location[$i]['to'];
					    	$newlocation2[$i][] = $location[$i]['truck'];
					    }

					}

					// $this->load->helper('comman_helper');
					// pr($location);

					// add selling location
					foreach ($data_costlocation as $value) {
						$data_location[] = array(
							'quotation_number' => $quotation_number,
							'company_id' => $code_cmpy,
							'revesion_number' => $new_revision,
							'selling_service_id' => $value['selling_service'],
							'from_location_id' => $value['from'],
							'to_location_id' => $value['to'],
							'truck_kind_id' => $value['truck'],
							'selling_currency' => $value['currency'],
							'selling_offering_rate' => $value['offer_price'],
							'selling_standart_rate' => $value['amount'],
							'calc_type' => $value['calc'],
							'increment_qty' => $value['increment'],
							'start_date' => $value['start_date'],
							'end_date' => $value['end_date'],
							'distance' => $value['distance'],
							'distance_per_litre' => $value['distanceliter'],
							'user_id' => $this->nik,
							'user_date' => $date
						);
					}

					// add cost trucking
					foreach ($data_costlocation2 as $value1) {

						// get data cost of selling
						$data_cost_location = $this->M_quotation->get_location_cost($value1['selling_service'], $value1['from'], $value1['to'], $value1['truck']);

						if ($data_cost_location->num_rows() < 1) {
							unset($data_cost_location);
						} else {
							for ($k=0; $k < $data_cost_location->num_rows(); $k++) { 
								$cost_insert_location[] = array(
									'quotation_number' => $quotation_number,
									'company_service_id' => $data_cost_location->row($k)->COMPANY_SERVICE_ID,
									'revesion_number' => $new_revision,
									'selling_service_id' => $data_cost_location->row($k)->SELLING_SERVICE_ID,
									'from_location_id' => $data_cost_location->row($k)->FROM_LOCATION_ID,
									'to_location_id' => $data_cost_location->row($k)->TO_LOCATION_ID,
									'cost_id' => $data_cost_location->row($k)->COST_ID,
									'truck_id' => $data_cost_location->row($k)->TRUCK_ID,
									'start_date' => $data_cost_location->row($k)->START_DATE,
									'end_date' => $data_cost_location->row($k)->END_DATE,
									'increment_qty' => $data_cost_location->row($k)->INCREMENT_QTY,
									'calc_type' => $data_cost_location->row($k)->CALC_TYPE,
									'cost_type_id' => $data_cost_location->row($k)->COST_TYPE_ID,
									'cost_group_id' => $data_cost_location->row($k)->COST_GROUP_ID,
									'cost_currency' => $data_cost_location->row($k)->COST_CURRENCY,
									'cost_amount' => $data_cost_location->row($k)->COST_AMOUNT,
									'user_id' => $this->nik,
									'user_date' => $date
								);
							}
							// insert data cost
							if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_LOCATION_ATTRIBUTE', $cost_insert_location)) {
								throw new Exception("Error Processing Request to Entry Location Cost", 1);
							}
							unset($data_cost_location);
							unset($cost_insert_location);
						} 
					}

					if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE', $data_location)) {
						throw new Exception("Error Processing Request to Entry Location Selling", 1);
					}
					// array_push($success, "Successfully insert data quotation location!");
					unset($data_location);
				} else {
					// delete data quotation with last revision in database
					$delete_revision_location_selling = $this->M_quotation->delete_revision_location_selling($quotation_number);
					if ($delete_revision_location_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Location Selling", 1);
					}
					$delete_revision_location_cost = $this->M_quotation->delete_revision_location_cost($quotation_number);
					if ($delete_revision_location_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Location Cost", 1);
					}
				}

				if (isset($_POST['service']) && in_array('weight', $_POST['service'])) {
					// revision process
					// insert into history selling
					$insert_revision_weight_selling = $this->M_quotation->insert_revision_weight_selling($quotation_number);
					if ($insert_revision_weight_selling == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Weight Selling", 1);
					}
					// insert into history cost
					$insert_revision_weight_cost = $this->M_quotation->insert_revision_weight_cost($quotation_number);
					if ($insert_revision_weight_cost == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Weight Cost", 1);
					}
					// delete data quotation with last revision in database
					$delete_revision_weight_selling = $this->M_quotation->delete_revision_weight_selling($quotation_number);
					if ($delete_revision_weight_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Weight Selling", 1);
					}
					$delete_revision_weight_cost = $this->M_quotation->delete_revision_weight_cost($quotation_number);
					if ($delete_revision_weight_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Weight Cost", 1);
					}

					$weight = $this->input->post('weight');

					// filter for duplicate data
					$data_costweight = $weight;
					$newweight = array();

					for( $i=0; $i<count($weight); $i++ ) {

					    if ( isset($weight) && in_array( array( $weight[$i]['from'], $weight[$i]['to'], $weight[$i]['from_weight'], $weight[$i]['to_weight'], $weight[$i]['calc'], $weight[$i]['increment'] ), $newweight ) ) {
					    	unset($newweight[$i]);
					    	unset($data_costweight[$i]);
					    	$weight_check = "";
					    }
					    else {
					    	$newweight[$i][] = $weight[$i]['from'];
					    	$newweight[$i][] = $weight[$i]['to'];
					    	$newweight[$i][] = $weight[$i]['from_weight'];
					    	$newweight[$i][] = $weight[$i]['to_weight'];
					    	$newweight[$i][] = $weight[$i]['calc'];
					    	$newweight[$i][] = $weight[$i]['increment'];
					    }

					}

					// filter for cost data
					$data_costweight2 = $weight;
					$newweight2 = array();

					for( $i=0; $i<count($weight); $i++ ) {

					    if ( isset($weight) && in_array( array( $weight[$i]['from'], $weight[$i]['to'] ), $newweight2 ) ) {
					    	unset($newweight2[$i]);
					    	unset($data_costweight2[$i]);
					    }
					    else {
					    	$newweight2[$i][] = $weight[$i]['from'];
					    	$newweight2[$i][] = $weight[$i]['to'];
					    }

					}

					// $this->load->helper('comman_helper');
					// pr($weight);

					foreach ($data_costweight as $value) {
						$data_weight[] = array(
							'quotation_number' => $quotation_number,
							'company_id' => $code_cmpy,
							'revesion_number' => $new_revision,
							'selling_service_id' => $value['selling_service'],
							'from_location_id' => $value['from'],
							'to_location_id' => $value['to'],
							'selling_currency' => $value['currency'],
							'selling_offering_rate' => $value['offer_price'],
							'selling_standart_rate' => $value['amount'],
							'from_weight' => $value['from_weight'],
							'to_weight' => $value['to_weight'],
							'calc_type' => $value['calc'],
							'increment_qty' => $value['increment'],
							'measurement_unit' => $value['measurement'],
							'start_date' => $value['start_date'],
							'end_date' => $value['end_date'],
							'user_id' => $this->nik,
							'user_date' => $date
						);
					}

					// add cost weight
					foreach ($data_costweight2 as $value1) {

						// get data cost of selling
						$data_cost_weight = $this->M_quotation->get_weight_cost($value1['selling_service'], $value1['from'], $value1['to']);

						if ($data_cost_weight->num_rows() < 1) {
							unset($data_cost_weight);
						} else {
							for ($k=0; $k < $data_cost_weight->num_rows(); $k++) { 
								$cost_insert_weight[] = array(
									'quotation_number' => $quotation_number,
									'company_service_id' => $data_cost_weight->row($k)->COMPANY_SERVICE_ID,
									'revesion_number' => $new_revision,
									'selling_service_id' => $data_cost_weight->row($k)->SELLING_SERVICE_ID,
									'from_location_id' => $data_cost_weight->row($k)->FROM_LOCATION_ID,
									'to_location_id' => $data_cost_weight->row($k)->TO_LOCATION_ID,
									'from_weight' => $data_cost_weight->row($k)->FROM_WEIGHT,
									'to_weight' => $data_cost_weight->row($k)->TO_WEIGHT,
									'cost_id' => $data_cost_weight->row($k)->COST_ID,
									'start_date' => $data_cost_weight->row($k)->START_DATE,
									'end_date' => $data_cost_weight->row($k)->END_DATE,
									'increment_qty' => $data_cost_weight->row($k)->INCREMENT_QTY,
									'calc_type' => $data_cost_weight->row($k)->CALC_TYPE,
									'cost_type_id' => $data_cost_weight->row($k)->COST_TYPE_ID,
									'cost_group_id' => $data_cost_weight->row($k)->COST_GROUP_ID,
									'cost_currency' => $data_cost_weight->row($k)->COST_CURRENCY,
									'cost_amount' => $data_cost_weight->row($k)->COST_AMOUNT,
									'user_id' => $this->nik,
									'user_date' => $date
								);
							}
							// insert data cost
							if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_WEIGHT_ATTRIBUTE', $cost_insert_weight)) {
								throw new Exception("Error Processing Request to Entry Weight Service Cost", 1);
							}
							unset($data_cost_weight);
							unset($cost_insert_weight);
						} 
					}

					if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE', $data_weight)) {
						throw new Exception("Error Processing Request to Entry Weight Service Selling", 1);
					}
					// array_push($success, "Successfully insert data quotation weight!");
					unset($data_weight);
				} else {
					// delete data quotation with last revision in database
					$delete_revision_weight_selling = $this->M_quotation->delete_revision_weight_selling($quotation_number);
					if ($delete_revision_weight_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Weight Selling", 1);
					}
					$delete_revision_weight_cost = $this->M_quotation->delete_revision_weight_cost($quotation_number);
					if ($delete_revision_weight_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Weight Cost", 1);
					}
				}

				if (isset($_POST['service']) && in_array('ocean', $_POST['service'])) {
					// revision process
					// insert into history selling
					$insert_revision_ocean_selling = $this->M_quotation->insert_revision_ocean_selling($quotation_number);
					if ($insert_revision_ocean_selling == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Ocean Selling", 1);
					}
					// insert into history cost
					$insert_revision_ocean_cost = $this->M_quotation->insert_revision_ocean_cost($quotation_number);
					if ($insert_revision_ocean_cost == FALSE) {
						throw new Exception("Error Processing Request to Insert Revision Ocean Cost", 1);
					}
					// delete data quotation with last revision in database
					$delete_revision_ocean_selling = $this->M_quotation->delete_revision_ocean_selling($quotation_number);
					if ($delete_revision_ocean_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Ocean Selling", 1);
					}
					$delete_revision_ocean_cost = $this->M_quotation->delete_revision_ocean_cost($quotation_number);
					if ($delete_revision_ocean_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Ocean Cost", 1);
					}

					$ocean = $this->input->post('ocean');

					// for cost
					$data_costocean = $ocean;
					$newocean = array();

					for( $i=0; $i<count($ocean); $i++ ) {

					    if ( in_array( array( $ocean[$i]['size'], $ocean[$i]['to'], $ocean[$i]['type'], $ocean[$i]['category'], $ocean[$i]['from'], $ocean[$i]['charge'], $ocean[$i]['from_qty'], $ocean[$i]['to_qty'], $ocean[$i]['calc'], $ocean[$i]['increment'] ), $newocean ) ) {
					    	unset($newocean[$i]);
					    	unset($data_costocean[$i]);
					    }
					    else {
					    	$newocean[$i][] = $ocean[$i]['size'];
					    	$newocean[$i][] = $ocean[$i]['to'];
					    	$newocean[$i][] = $ocean[$i]['type'];
					    	$newocean[$i][] = $ocean[$i]['category'];
					    	$newocean[$i][] = $ocean[$i]['from'];
					    	$newocean[$i][] = $ocean[$i]['charge'];
					    	$newocean[$i][] = $ocean[$i]['from_qty'];
					    	$newocean[$i][] = $ocean[$i]['to_qty'];
					    	$newocean[$i][] = $ocean[$i]['calc'];
					    	$newocean[$i][] = $ocean[$i]['increment'];
					    }

					}

					// for cost
					$data_costocean2 = $ocean;
					$newocean2 = array();

					for( $i=0; $i<count($ocean); $i++ ) {

					    if ( in_array( array( $ocean[$i]['size'], $ocean[$i]['to'], $ocean[$i]['type'], $ocean[$i]['category'], $ocean[$i]['from'], $ocean[$i]['charge'] ), $newocean2 ) ) {
					    	unset($newocean2[$i]);
					    	unset($data_costocean2[$i]);
					    }
					    else {
					    	$newocean2[$i][] = $ocean[$i]['size'];
					    	$newocean2[$i][] = $ocean[$i]['to'];
					    	$newocean2[$i][] = $ocean[$i]['type'];
					    	$newocean2[$i][] = $ocean[$i]['category'];
					    	$newocean2[$i][] = $ocean[$i]['from'];
					    	$newocean2[$i][] = $ocean[$i]['charge'];
					    }

					}

					// input quotation trucking
					foreach ($data_costocean as $value) {
						$data_ocean[] = array(
							'quotation_number' => $quotation_number,
							'company_id' => $code_cmpy,
							'revesion_number' => $new_revision,
							'selling_service_id' => $value['selling_service'],
							'container_size_id' => $value['size'],
							'container_type_id' => $value['type'],
							'container_category_id' => $value['category'],
							'from_location_id' => $value['from'],
							'to_location_id' => $value['to'],
							'selling_currency' => $value['currency'],
							'selling_offering_rate' => $value['offer_price'],
							'selling_standart_rate' => $value['amount'],
							'from_qty' => $value['from_qty'],
							'to_qty' => $value['to_qty'],
							'calc_type' => $value['calc'],
							'increment_qty' => $value['increment'],
							'start_date' => $value['start_date'],
							'end_date' => $value['end_date'],
							'user_id' => $this->nik,
							'user_date' => $date,
							'charge_id' => $value['charge']
						);
					}

					foreach ($data_costocean2 as $value1) {

						// add cost trucking selling
						$data_cost_ocean = $this->M_quotation->get_ocean_cost($value1['selling_service'], $value1['type'], $value1['category'], $value1['from'], $value1['to'], $value1['size'], $value1['charge']);
						if ($data_cost_ocean->num_rows() < 1) {
							unset($data_cost_ocean);
						} else {
							for ($j=0; $j < $data_cost_ocean->num_rows(); $j++) { 
								$cost_insert_ocean[] = array(
									'quotation_number' => $quotation_number,
									'company_service_id' => $data_cost_ocean->row($j)->COMPANY_SERVICE_ID,
									'revesion_number' => $new_revision,
									'selling_service_id' => $data_cost_ocean->row($j)->SELLING_SERVICE_ID,
									'container_size_id' => $data_cost_ocean->row($j)->CONTAINER_SIZE_ID,
									'container_type_id' => $data_cost_ocean->row($j)->CONTAINER_TYPE_ID,
									'container_category_id' => $data_cost_ocean->row($j)->CONTAINER_CATEGORY_ID,
									'from_qty' => $data_cost_ocean->row($j)->FROM_QTY,
									'to_qty' => $data_cost_ocean->row($j)->TO_QTY,
									'from_location_id' => $data_cost_ocean->row($j)->FROM_LOCATION_ID,
									'to_location_id' => $data_cost_ocean->row($j)->TO_LOCATION_ID,
									'start_date' => $data_cost_ocean->row($j)->START_DATE,
									'end_date' => $data_cost_ocean->row($j)->END_DATE,
									'cost_id' => $data_cost_ocean->row($j)->COST_ID,
									'cost_type_id' => $data_cost_ocean->row($j)->COST_TYPE_ID,
									'cost_group_id' => $data_cost_ocean->row($j)->COST_GROUP_ID,
									'calc_type' => $data_cost_ocean->row($j)->CALC_TYPE,
									'cost_currency' => $data_cost_ocean->row($j)->COST_CURRENCY,
									'cost_amount' => $data_cost_ocean->row($j)->COST_AMOUNT,
									'increment_qty' => $data_cost_ocean->row($j)->INCREMENT_QTY,
									'user_id' => $this->nik,
									'user_date' => $date,
									'charge_id' => $data_cost_ocean->row($j)->CHARGE_ID
								);
							}

							if (!$this->db->insert_batch('dbo.TRQUOTATION_COST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE', $cost_insert_ocean)) {
								throw new Exception("Error Processing Request to Entry Ocean Freight Cost", 1);
							}
							unset($data_cost_ocean);
							unset($cost_insert_ocean);
						}
					}

					if (!$this->db->insert_batch('dbo.TRQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE', $data_ocean)) {
						throw new Exception("Error Processing Request to Entry Ocean Freight Selling", 1);
					}
					// array_push($success, "Successfully insert data quotation ocean freight!");
					unset($data_ocean);
				} else {
					// delete data quotation with last revision in database
					$delete_revision_ocean_selling = $this->M_quotation->delete_revision_ocean_selling($quotation_number);
					if ($delete_revision_ocean_selling == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Ocean Selling", 1);
					}
					$delete_revision_ocean_cost = $this->M_quotation->delete_revision_ocean_cost($quotation_number);
					if ($delete_revision_ocean_cost == FALSE) {
						throw new Exception("Error Processing Request to Delete Revision Ocean Cost", 1);
					}
				}

					// delete quotation
					$delete_quotation = $this->M_quotation->delete_quotation($quotation_number);
					if ($delete_quotation == FALSE) {
						throw new Exception("Error Processing Request to Delete Quotation", 1);
					}
					$delete_quotation_services = $this->M_quotation->delete_quotation_services($quotation_number);
					if ($delete_quotation_services == FALSE) {
						throw new Exception("Error Processing Request to Delete Quotation Service", 1);
					}
					$delete_transaction = $this->M_quotation->delete_transaction($agreement_number, 'D1001');
					if ($delete_transaction == FALSE) {
						throw new Exception("Error Processing Request to Delete Transaction", 1);
					}
					// $this->M_quotation->delete_agreement($agreement_number);

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Amendment Agreement", 1);
					} else {
						$this->session->set_flashdata('success_amendment', "Successfully amendment agreement!");
						$this->db->trans_commit();
						redirect(current_url());
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed_amendment', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function print_agreement_indonesia()
	{
		$agreement_number = $this->uri->segment(3);
		$quotation_number = $this->uri->segment(4);

		// agreement
		$data['agreement_number'] = $agreement_number;
		$data['agreement_document_number'] = $this->M_quotation->get_data_agreement2($agreement_number)->row()->AGREEMENT_DOCUMENT_NUMBER;
		// $this->load->helper('comman_helper');
		// pr($data['agreement_document_number']);
		$data['agreement_date'] = $this->M_quotation->get_data_agreement2($agreement_number)->row()->AGREEMENT_DATE;

		// quotation
		$data['template'] = $this->M_quotation->get_template_quotation($quotation_number)->result();
		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['date_quotation'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DATE;
		$customer_name_get = $this->M_quotation->get_quotation_param_full2($quotation_number)->result();
		$customer_name_temp = array();
		foreach ($customer_name_get as $key => $value) {
			array_push($customer_name_temp, $value->NAME);
		}
		$data['name'] = rtrim(implode(", ", $customer_name_temp));

		$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
		$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
		$pic_namdep = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
		if ($pic_namdep == "Mr.") {
			$data['pic_namdep'] = "Bapak";
		} else {
			$data['pic_namdep'] = "Ibu";
		}
		$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
		$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;

		$service_get = $this->M_quotation->get_service_quotation($quotation_number)->result();
		$service_temp = array();
		$service_delete = array();
		foreach ($service_get as $key => $value) {
			array_push($service_temp, $value->NAME);
		}

		for ($i=0; $i < count($service_temp); $i++) { 
			$temp = substr($service_temp[$i], 0, (strlen($service_temp[$i]) - 7));
			array_push($service_delete, $temp);
		}
		$data['service'] = rtrim(implode(", ", $service_delete));

		// echo $data['customer_name'];

		// custom service jakarta
		$data['cost_custom_jakarta'] = $this->M_quotation->get_all_data_custom_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_custom_jakarta'] = $this->M_quotation->get_tarif_amount_custom_jakarta($quotation_number)->result();


		$this->load->helper('currency_helper');

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);

		// container custom jakarta		
		if (!$data['cost_custom_jakarta']) {
			$hasil_custom_jakarta = array();
		} else {
			// select data cost container custom jakarta
			foreach ($data['cost_custom_jakarta'] as $key => $value) {
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['FROM_QTY'] = $value->FROM_QTY;
				$print_data['TO_QTY'] = $value->TO_QTY;
				$print_data['CALC_TYPE'] = $value->CALC_TYPE;
				$print_data['CALC_NAME'] = $value->CALC_NAME;
				$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['END_DATE'] = $value->END_DATE;

				foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_20_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_20_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_40_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_40_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_4H_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_4H_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_45_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_45_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($print_data['TARIF_20_SELLING'])) {
					$print_data['TARIF_20_SELLING'] = currency(0);
					$print_data['TARIF_20_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_40_SELLING'])) {
					$print_data['TARIF_40_SELLING'] = currency(0);
					$print_data['TARIF_40_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_4H_SELLING'])) {
					$print_data['TARIF_4H_SELLING'] = currency(0);
					$print_data['TARIF_4H_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_45_SELLING'])) {
					$print_data['TARIF_45_SELLING'] = currency(0);
					$print_data['TARIF_45_OFFERING'] = currency(0);
				}

				$hasil_custom_jakarta[] = $print_data;
			}
		}

		// check data tariff_amount custom jakarta
		foreach ($hasil_custom_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_20_SELLING']);
			} 

			if (!$this->M_quotation->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_40_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_45_SELLING']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_custom_jakarta[$key]['TARIF_20_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_20_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_40_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_40_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_4H_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_45_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_45_SELLING'] = currency(0);
			}

		}

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);
		$data['hasil_custom_jakarta'] = $hasil_custom_jakarta;

		// location
		$data['hasil_location'] = $this->M_quotation->get_data_location_jakarta($quotation_number)->result();

		// trucking service
		// container service jakarta
		$data['cost_container_jakarta'] = $this->M_quotation->get_all_data_container_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_jakarta'] = $this->M_quotation->get_tarif_amount_jakarta($quotation_number)->result();
		$data['tarif_weight_jakarta'] = $this->M_quotation->get_tarif_weight_jakarta($quotation_number)->result();

		// container cost jakarta
		if (!$data['cost_container_jakarta']) {
			$hasil_jakarta = array();
		} else {
			// select cost continer jakarta
			foreach ($data['cost_container_jakarta'] as $key => $value) {
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['FROM_QTY'] = $value->FROM_QTY;
				$test_data['TO_QTY'] = $value->TO_QTY;
				$test_data['CALC_TYPE'] = $value->CALC;
				$test_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$test_data['START_DATE'] = $value->START_DATE;
				$test_data['END_DATE'] = $value->END_DATE;
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				
				foreach ($data['tarif_amount_jakarta'] as $key1 => $value1) {
					
					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_20'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_40'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_4H'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_45'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($test_data['TARIF_20'])) {
					$test_data['TARIF_20'] = currency(0);
				}

				if (empty($test_data['TARIF_40'])) {
					$test_data['TARIF_40'] = currency(0);
				}

				if (empty($test_data['TARIF_4H'])) {
					$test_data['TARIF_4H'] = currency(0);
				}

				if (empty($test_data['TARIF_45'])) {
					$test_data['TARIF_45'] = currency(0);
				}

				$hasil_jakarta[] = $test_data;
			}
		}

		// check data tariff_amount jakarta
		foreach ($hasil_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_container($quotation_number, '20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_quotation->check_data_container($quotation_number, '40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_jakarta[$key]['TARIF_20'])) {
					$hasil_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_40'])) {
					$hasil_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_4H'])) {
					$hasil_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_45'])) {
					$hasil_jakarta[$key]['TARIF_45'] = currency(0);
			}

		} //end of trucking service
		$data['hasil_jakarta'] = $hasil_jakarta;

		// weight
		$data['hasil_weight_jakarta'] = $this->M_quotation->get_data_weight_jakarta($quotation_number)->result();

		$data['customer_count'] = $this->M_quotation->get_quotation_param_full($quotation_number)->num_rows();
		$data['customer_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->result();
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();

		// $this->load->helper('comman_helper');
		// pr($data['count_customs']);

		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		// $this->load->view('quotations/v_detailquotation', $data);

		$html = $this->load->view('reports/r_agreementindonesia', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		$pdf->SetFooter('&emsp;Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 &emsp;Jakarta Pusat 10160 Indonesia <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
		$pdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        0, // margin_left
        0, // margin right
       40, // margin top
       30, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('Agreement.pdf', 'I');
	}

	function print_agreement()
	{
		$agreement_number = $this->uri->segment(3);
		$quotation_number = $this->uri->segment(4);

		// get checklist
		$data['remarks'] = "no";
		$data['qty'] = "no";
		$check = $this->input->post('check');

		$bahasa = $this->input->post('bahasa');

		// if ($check != NULL) {
		// 	echo "yes";
		// } else {
		// 	echo "no";
		// }

		// die();

		if ($check != NULL && in_array('remarks', $_POST['check'])) {
			$data['remarks'] = "yes";
		} else {
			$data['remarks'] = "no";
		}

		if ($check != NULL && in_array('qty', $_POST['check'])) {
			$data['qty'] = "yes";
		} else {
			$data['qty'] = "no";
		}

		// agreement
		$data['agreement_number'] = $agreement_number;
		$data['agreement_document_number'] = $this->M_quotation->get_data_agreement2($agreement_number)->row()->AGREEMENT_DOCUMENT_NUMBER;
		// $this->load->helper('comman_helper');
		// pr($data['agreement_document_number']);
		$data['agreement_date'] = $this->M_quotation->get_data_agreement2($agreement_number)->row()->AGREEMENT_DATE;

		// quotation
		$data['template'] = $this->M_quotation->get_template_quotation($quotation_number)->result();
		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['date_quotation'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DATE;
		$customer_name_get = $this->M_quotation->get_quotation_param_full2($quotation_number)->result();
		$customer_name_temp = array();
		foreach ($customer_name_get as $key => $value) {
			array_push($customer_name_temp, $value->NAME);
		}
		$data['name'] = rtrim(implode(", ", $customer_name_temp));

		if ($bahasa == "inggris") {
			$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
			$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
			$data['pic_namdep'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
			$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
			$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;
		} elseif ($bahasa == "indo") {
			$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
			$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
			$pic_namdep = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
			if ($pic_namdep == "Mr.") {
				$data['pic_namdep'] = "Bapak";
			} else {
				$data['pic_namdep'] = "Ibu";
			}
			$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
			$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;
		}

		$service_get = $this->M_quotation->get_service_quotation($quotation_number)->result();
		$service_temp = array();
		$service_delete = array();
		foreach ($service_get as $key => $value) {
			array_push($service_temp, $value->NAME);
		}

		for ($i=0; $i < count($service_temp); $i++) { 
			$temp = substr($service_temp[$i], 0, (strlen($service_temp[$i]) - 7));
			array_push($service_delete, $temp);
		}
		$data['service'] = rtrim(implode(", ", $service_delete));

		// echo $data['customer_name'];

		// custom service jakarta
		$data['cost_custom_jakarta'] = $this->M_quotation->get_all_data_custom_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_custom_jakarta'] = $this->M_quotation->get_tarif_amount_custom_jakarta($quotation_number)->result();


		$this->load->helper('currency_helper');

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);

		// container custom jakarta		
		if (!$data['cost_custom_jakarta']) {
			$hasil_custom_jakarta = array();
		} else {
			// select data cost container custom jakarta
			foreach ($data['cost_custom_jakarta'] as $key => $value) {
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['FROM_QTY'] = $value->FROM_QTY;
				$print_data['TO_QTY'] = $value->TO_QTY;
				$print_data['CALC_TYPE'] = $value->CALC_TYPE;
				$print_data['CALC_NAME'] = $value->CALC_NAME;
				$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['END_DATE'] = $value->END_DATE;

				foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_20_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_20_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_40_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_40_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_4H_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_4H_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_45_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_45_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($print_data['TARIF_20_SELLING'])) {
					$print_data['TARIF_20_SELLING'] = currency(0);
					$print_data['TARIF_20_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_40_SELLING'])) {
					$print_data['TARIF_40_SELLING'] = currency(0);
					$print_data['TARIF_40_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_4H_SELLING'])) {
					$print_data['TARIF_4H_SELLING'] = currency(0);
					$print_data['TARIF_4H_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_45_SELLING'])) {
					$print_data['TARIF_45_SELLING'] = currency(0);
					$print_data['TARIF_45_OFFERING'] = currency(0);
				}

				$hasil_custom_jakarta[] = $print_data;
			}
		}

		// check data tariff_amount custom jakarta
		foreach ($hasil_custom_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_20_SELLING']);
			} 

			if (!$this->M_quotation->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_40_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_45_SELLING']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_custom_jakarta[$key]['TARIF_20_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_20_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_40_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_40_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_4H_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_45_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_45_SELLING'] = currency(0);
			}

		}

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);
		$data['hasil_custom_jakarta'] = $hasil_custom_jakarta;

		// location
		$data['hasil_location'] = $this->M_quotation->get_data_location_jakarta($quotation_number)->result();

		// trucking service
		// container service jakarta
		$data['cost_container_jakarta'] = $this->M_quotation->get_all_data_container_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_jakarta'] = $this->M_quotation->get_tarif_amount_jakarta($quotation_number)->result();
		$data['tarif_weight_jakarta'] = $this->M_quotation->get_tarif_weight_jakarta($quotation_number)->result();

		// container cost jakarta
		if (!$data['cost_container_jakarta']) {
			$hasil_jakarta = array();
		} else {
			// select cost continer jakarta
			foreach ($data['cost_container_jakarta'] as $key => $value) {
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['FROM_QTY'] = $value->FROM_QTY;
				$test_data['TO_QTY'] = $value->TO_QTY;
				$test_data['CALC_TYPE'] = $value->CALC;
				$test_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$test_data['START_DATE'] = $value->START_DATE;
				$test_data['END_DATE'] = $value->END_DATE;
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				
				foreach ($data['tarif_amount_jakarta'] as $key1 => $value1) {
					
					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_20'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_40'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_4H'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_45'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($test_data['TARIF_20'])) {
					$test_data['TARIF_20'] = currency(0);
				}

				if (empty($test_data['TARIF_40'])) {
					$test_data['TARIF_40'] = currency(0);
				}

				if (empty($test_data['TARIF_4H'])) {
					$test_data['TARIF_4H'] = currency(0);
				}

				if (empty($test_data['TARIF_45'])) {
					$test_data['TARIF_45'] = currency(0);
				}

				$hasil_jakarta[] = $test_data;
			}
		}

		// check data tariff_amount jakarta
		foreach ($hasil_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_container($quotation_number, '20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_quotation->check_data_container($quotation_number, '40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_jakarta[$key]['TARIF_20'])) {
					$hasil_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_40'])) {
					$hasil_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_4H'])) {
					$hasil_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_45'])) {
					$hasil_jakarta[$key]['TARIF_45'] = currency(0);
			}

		} //end of trucking service
		$data['hasil_jakarta'] = $hasil_jakarta;

		// weight
		$data['hasil_weight_jakarta'] = $this->M_quotation->get_data_weight_jakarta($quotation_number)->result();

		$data['customer_count'] = $this->M_quotation->get_quotation_param_full($quotation_number)->num_rows();
		$data['customer_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->result();
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();

		// $this->load->helper('comman_helper');
		// pr($data['count_customs']);

		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		$cmpy = $this->M_quotation->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_quotation->get_code($cmpy)->row()->COMPANY_ID;
		$cmpy_address = strtolower($this->M_quotation->get_code($cmpy)->row()->ADDRESS);
		$phone = $this->M_quotation->get_code($cmpy)->row()->PHONE;
		$email = $this->M_quotation->get_code($cmpy)->row()->EMAIL;
		$website = $this->M_quotation->get_code($cmpy)->row()->WEBSITE;
		$address_fix = ucwords($cmpy_address);

		if ($bahasa == "indo") {
			$html = $this->load->view('reports/r_agreementindonesia', $data, true);
			$this->load->library('pdf');

			$pdf = $this->pdf->load();
			$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
			$foot_address = "&emsp;$address_fix <br>&emsp;Phone : $phone <br>&emsp;Email : $email <br>&emsp;$website||Page {PAGENO} of {nb}&emsp;";
			$pdf->SetFooter($foot_address);
			$pdf->AddPage('', // L - landscape, P - portrait 
	        '', '', '', '',
	        0, // margin_left
	        0, // margin right
	       40, // margin top
	       30, // margin bottom
	        0, // margin header
	        5); // margin footer
			// $pdf->defaultheaderfontstyle='I';
			// $pdf->defaultfooterfontstyle='I';
			// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
			
			$pdf->WriteHTML($html);
			$pdf->Output('Agreement.pdf', 'I');
		} elseif ($bahasa == "inggris") {
			$html = $this->load->view('reports/r_agreementinggris', $data, true);
			$this->load->library('pdf');

			$pdf = $this->pdf->load();
			$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
			$foot_address = "&emsp;$address_fix <br>&emsp;Phone : $phone <br>&emsp;Email : $email <br>&emsp;$website||Page {PAGENO} of {nb}&emsp;";
			$pdf->SetFooter($foot_address);
			$pdf->AddPage('', // L - landscape, P - portrait 
	        '', '', '', '',
	        0, // margin_left
	        0, // margin right
	       40, // margin top
	       30, // margin bottom
	        0, // margin header
	        5); // margin footer
			// $pdf->defaultheaderfontstyle='I';
			// $pdf->defaultfooterfontstyle='I';
			// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
			
			$pdf->WriteHTML($html);
			$pdf->Output('Agreement.pdf', 'I');
		}
	}

	function print_agreement_inggris()
	{
		$agreement_number = $this->uri->segment(3);
		$quotation_number = $this->uri->segment(4);

		// agreement
		$data['agreement_number'] = $agreement_number;
		$data['agreement_document_number'] = $this->M_quotation->get_data_agreement($agreement_number)->AGREEMENT_DOCUMENT_NUMBER;
		$data['agreement_date'] = $this->M_quotation->get_data_agreement($agreement_number)->AGREEMENT_DATE;

		$data['template'] = $this->M_quotation->get_template_quotation($quotation_number)->result();
		$data['data_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['date_quotation'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_DATE;
		$customer_name_get = $this->M_quotation->get_quotation_param_full2($quotation_number)->result();
		$customer_name_temp = array();
		foreach ($customer_name_get as $key => $value) {
			array_push($customer_name_temp, $value->NAME);
		}
		$data['name'] = rtrim(implode(", ", $customer_name_temp));

		$customer_pic_id = $this->M_quotation->get_data_pic($quotation_number)->row()->CUSTOMER_PIC_ID;
		$data['pic_name'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAME;
		$data['pic_namdep'] = $this->M_quotation->get_pic($customer_pic_id)->row()->NAMDEP;
		$data['pic_jabatan'] = $this->M_quotation->get_pic($customer_pic_id)->row()->JABATAN;
		$data['pic_company'] = $this->M_quotation->get_pic($customer_pic_id)->row()->COMPANY_NAME;

		$service_get = $this->M_quotation->get_service_quotation($quotation_number)->result();
		$service_temp = array();
		$service_delete = array();
		foreach ($service_get as $key => $value) {
			array_push($service_temp, $value->NAME);
		}

		for ($i=0; $i < count($service_temp); $i++) { 
			$temp = substr($service_temp[$i], 0, (strlen($service_temp[$i]) - 7));
			array_push($service_delete, $temp);
		}
		$data['service'] = rtrim(implode(", ", $service_delete));

		// echo $data['customer_name'];

		// custom service jakarta
		$data['cost_custom_jakarta'] = $this->M_quotation->get_all_data_custom_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_custom_jakarta'] = $this->M_quotation->get_tarif_amount_custom_jakarta($quotation_number)->result();


		$this->load->helper('currency_helper');

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);

		// container custom jakarta		
		if (!$data['cost_custom_jakarta']) {
			$hasil_custom_jakarta = array();
		} else {
			// select data cost container custom jakarta
			foreach ($data['cost_custom_jakarta'] as $key => $value) {
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['FROM_QTY'] = $value->FROM_QTY;
				$print_data['TO_QTY'] = $value->TO_QTY;
				$print_data['CALC_TYPE'] = $value->CALC_TYPE;
				$print_data['CALC_NAME'] = $value->CALC_NAME;
				$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['END_DATE'] = $value->END_DATE;

				foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_20_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_20_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_40_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_40_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_4H_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_4H_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_45_SELLING'] = currency($value1->SELLING_STANDART_RATE);
						$print_data['TARIF_45_OFFERING'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($print_data['TARIF_20_SELLING'])) {
					$print_data['TARIF_20_SELLING'] = currency(0);
					$print_data['TARIF_20_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_40_SELLING'])) {
					$print_data['TARIF_40_SELLING'] = currency(0);
					$print_data['TARIF_40_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_4H_SELLING'])) {
					$print_data['TARIF_4H_SELLING'] = currency(0);
					$print_data['TARIF_4H_OFFERING'] = currency(0);
				}

				if (empty($print_data['TARIF_45_SELLING'])) {
					$print_data['TARIF_45_SELLING'] = currency(0);
					$print_data['TARIF_4H5_OFFERING'] = currency(0);
				}

				$hasil_custom_jakarta[] = $print_data;
			}
		}

		// check data tariff_amount custom jakarta
		foreach ($hasil_custom_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_20_SELLING']);
			} 

			if (!$this->M_quotation->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_40_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING']);
			}

			if (!$this->M_quotation->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $quotation_number)->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_45_SELLING']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_custom_jakarta[$key]['TARIF_20_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_20_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_40_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_40_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_4H_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_4H_SELLING'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_45_SELLING'])) {
					$hasil_custom_jakarta[$key]['TARIF_45_SELLING'] = currency(0);
			}

		}

		// $this->load->helper('comman_helper');
		// pr($hasil_custom_jakarta);
		$data['hasil_custom_jakarta'] = $hasil_custom_jakarta;

		// location
		$data['hasil_location'] = $this->M_quotation->get_data_location_jakarta($quotation_number)->result();

		// trucking service
		// container service jakarta
		$data['cost_container_jakarta'] = $this->M_quotation->get_all_data_container_cost_jakarta($quotation_number)->result();
		$data['tarif_amount_jakarta'] = $this->M_quotation->get_tarif_amount_jakarta($quotation_number)->result();
		$data['tarif_weight_jakarta'] = $this->M_quotation->get_tarif_weight_jakarta($quotation_number)->result();

		// container cost jakarta
		if (!$data['cost_container_jakarta']) {
			$hasil_jakarta = array();
		} else {
			// select cost continer jakarta
			foreach ($data['cost_container_jakarta'] as $key => $value) {
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['FROM_QTY'] = $value->FROM_QTY;
				$test_data['TO_QTY'] = $value->TO_QTY;
				$test_data['CALC_TYPE'] = $value->CALC;
				$test_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$test_data['START_DATE'] = $value->START_DATE;
				$test_data['END_DATE'] = $value->END_DATE;
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				
				foreach ($data['tarif_amount_jakarta'] as $key1 => $value1) {
					
					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_20'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_40'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_4H'] = currency($value1->SELLING_OFFERING_RATE);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_45'] = currency($value1->SELLING_OFFERING_RATE);
					}
				}

				if (empty($test_data['TARIF_20'])) {
					$test_data['TARIF_20'] = currency(0);
				}

				if (empty($test_data['TARIF_40'])) {
					$test_data['TARIF_40'] = currency(0);
				}

				if (empty($test_data['TARIF_4H'])) {
					$test_data['TARIF_4H'] = currency(0);
				}

				if (empty($test_data['TARIF_45'])) {
					$test_data['TARIF_45'] = currency(0);
				}

				$hasil_jakarta[] = $test_data;
			}
		}

		// check data tariff_amount jakarta
		foreach ($hasil_jakarta as $key => $value) {
			if (!$this->M_quotation->check_data_container($quotation_number, '20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_quotation->check_data_container($quotation_number, '40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_quotation->check_data_container($quotation_number, '45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_jakarta[$key]['TARIF_20'])) {
					$hasil_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_40'])) {
					$hasil_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_4H'])) {
					$hasil_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_jakarta[$key]['TARIF_45'])) {
					$hasil_jakarta[$key]['TARIF_45'] = currency(0);
			}

		} //end of trucking service
		$data['hasil_jakarta'] = $hasil_jakarta;

		// weight
		$data['hasil_weight_jakarta'] = $this->M_quotation->get_data_weight_jakarta($quotation_number)->result();

		$data['customer_count'] = $this->M_quotation->get_quotation_param_full($quotation_number)->num_rows();
		$data['customer_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->result();
		$data['company_name'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_quotation->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_quotation->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_quotation->get_data_quote_customs($quotation_number)->num_rows();

		// $this->load->helper('comman_helper');
		// pr($data['count_customs']);

		$data['count_location'] = $this->M_quotation->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_quotation->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_quotation->get_data_quote_ocean_freight($quotation_number)->num_rows();

		// $this->load->view('quotations/v_detailquotation', $data);

		$html = $this->load->view('reports/r_agreementinggris', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		$pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		$pdf->SetFooter('&emsp;Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 &emsp;Jakarta Pusat 10160 Indonesia <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
		$pdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        0, // margin_left
        0, // margin right
       40, // margin top
       30, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('Agreement.pdf', 'I');
	}

	public function search_nik()
	{
		$kode = $this->input->get('term');
		$nik = $this->M_quotation->get_nik($kode)->result();

		foreach ($nik as $key => $value) {
			$temp_nik['value'] =  $value->Nm_lengkap;
			$temp_nik['pic_id'] = $value->Nik;
			$result_nik[] = $temp_nik;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_nik);
	}

}