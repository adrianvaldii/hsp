<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

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
		$this->load->model('M_order');
		error_reporting(E_ALL & ~E_NOTICE);
		// if ($this->session->userdata('nik')=="") {
		// 	redirect('Welcome/index');
		// }
		$this->nik = $this->session->userdata('nik');
		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
		// $this->load->library('PHPExcel');
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	}

	function index()
	{
		$this->load->helper('comman_helper');
		$data['data_wo'] = $this->M_order->get_data_wo()->result();
		$data_wo = $this->M_order->get_data_wo()->result();
		$data_qty = $this->M_order->get_qty_container()->result();
		// $this->load->helper('comman_helper');
		// pr($data_qty);
		$result_wo = array();
		// $temp_serv = array();

		// declare baru
		foreach ($data_wo as $key => $value) {
			$temp_data['WORK_ORDER_NUMBER'] = $value->WORK_ORDER_NUMBER;
			$temp_data['AGREEMENT_NUMBER'] = $value->AGREEMENT_NUMBER;
			$temp_data['DATE_RECEIVED'] = $value->DATE_RECEIVED;
			$temp_data['NAME_RECEIVED'] = $value->NAME_RECEIVED;
			$temp_data['DATE_REQUEST'] = $value->DATE_REQUEST;
			$temp_data['NAME_REQUEST'] = $value->NAME_REQUEST;
			$temp_data['WORK_ORDER_NUMBER'] = $value->WORK_ORDER_NUMBER;
			$temp_data['CUSTOMER_NAME'] = $value->CUSTOMER_NAME;
			$temp_data['WORK_ORDER_DATE'] = $value->WORK_ORDER_DATE;
			$temp_data['TRADE'] = $value->TRADE_ID;
			$temp_data['VESSEL_NAME'] = $value->VESSEL_NAME;
			$temp_data['REGISTER_NUMBER_PIB_PEB'] = $value->REGISTER_NUMBER_PIB_PEB;
			$temp_data['REGISTER_NUMBER_SPPB_SPEB'] = $value->REGISTER_NUMBER_SPPB_SPEB;
			$temp_data['IS_CHARGED'] = $value->IS_CHARGED;
			$temp_data['INVOICE_NUMBER'] = $value->INVOICE_NUMBER;

			// sincronize with quantity
			foreach ($data_qty as $key1 => $value1) {
				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '20') {
					$temp_data['TOTAL_20'] = $value1->TOTAL;
					// $temp_data['TOTAL_20'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '40') {
					$temp_data['TOTAL_40'] = $value1->TOTAL;
					// $temp_data['TOTAL_40'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '4H') {
					$temp_data['TOTAL_4H'] = $value1->TOTAL;
					// $temp_data['TOTAL_4H'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '45') {
					$temp_data['TOTAL_45'] = $value1->TOTAL;
					// $temp_data['TOTAL_45'] = "ada";
				}
			}

			if (empty($temp_data['TOTAL_20'])) {
				$temp_data['TOTAL_20'] = 0;
				// $temp_data['TOTAL_20'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_40'])) {
				$temp_data['TOTAL_40'] = 0;
				// $temp_data['TOTAL_40'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_4H'])) {
				$temp_data['TOTAL_4H'] = 0;
				// $temp_data['TOTAL_4H'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_45'])) {
				$temp_data['TOTAL_45'] = 0;
				// $temp_data['TOTAL_45'] = "gak ada";
			}

			$data_service = $this->M_order->get_wo_service($value->WORK_ORDER_NUMBER)->result();
			$temp_serv = array();

			foreach ($data_service as $key2 => $value2) {
				array_push($temp_serv, $value2->SERVICE_ID);
			}

			$temp_data['SERVICES'] = implode(", ", $temp_serv);

			$result_wo[] = $temp_data;
			unset($temp_serv);
		}

		foreach ($result_wo as $key => $value) {
			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "20")->result()) {
				unset($result_wo[$key]['TOTAL_20']);
			}

			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "40")->result()) {
				unset($result_wo[$key]['TOTAL_40']);
			}

			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "4H")->result()) {
				unset($result_wo[$key]['TOTAL_4H']);
			}

			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "45")->result()) {
				unset($result_wo[$key]['TOTAL_45']);
			}

			// ---------------------------------------------------------------
			if (!isset($result_wo[$key]['TOTAL_20'])) {
				$result_wo[$key]['TOTAL_20'] = 0;
			}

			if (!isset($result_wo[$key]['TOTAL_40'])) {
				$result_wo[$key]['TOTAL_40'] = 0;
			}

			if (!isset($result_wo[$key]['TOTAL_4H'])) {
				$result_wo[$key]['TOTAL_4H'] = 0;
			}

			if (!isset($result_wo[$key]['TOTAL_45'])) {
				$result_wo[$key]['TOTAL_45'] = 0;
			}
		}

		// $this->load->helper('comman_helper');
		// pr($result_wo);
		$data['result_wo'] = $result_wo;

		$this->load->view('orders/v_index', $data);
	}

	function view_history_wo()
	{
		$this->load->helper('comman_helper');
		$data['data_wo'] = $this->M_order->get_wo_done()->result();
		$this->load->view('orders/v_indexhistorywo', $data);
	}

	function entry_wo()
	{
		$data['data_customer'] = $this->M_order->get_data_customer()->result();
		$this->load->view('orders/v_entryorder', $data);
	}

	function create_work_order()
	{
		$this->load->helper('comman_helper');
		$company_id = $this->uri->segment(3);
		$quotation_no = $this->uri->segment(4);
		$agreement_no = $this->uri->segment(5);
		$date = date('Y-m-d H:i:s');

		$data_trucking = array();
		$cost_trucking = array(); 

		$data['company_id'] = $company_id;
		// pr($company_id);
		$data['agreement_no'] = $agreement_no;
		$data['quotation_no'] = $quotation_no;
		$data['company_name'] = $this->M_order->get_company($company_id)->row()->NAME;
		$data['service_order'] = $this->M_order->get_general_service()->result();

		$total_agreement = $this->M_order->get_total_agreement($company_id);

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		// pr($code_cmpy);
		$temp_id = $this->M_order->get_max_id()->row()->id;
		$temp_year = $this->M_order->get_max_id()->row()->year_temp;
		$year_now = date('y');
		// $date = date('Y-m-d H:i:s');

		$potongan_tahun = substr($temp_year, 0,2);

		if ($potongan_tahun == $year_now) {
			// echo "sama";

			$temp_id_tem = $temp_id + 1;
			if ($temp_id_tem < 10) {
				$id = $year_now . "000" . $temp_id_tem;
			} elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
				$id = $year_now . "00" . $temp_id_tem;
			} elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
				$id = $year_now . "0" . $temp_id_tem;
			} else {
				$id = $year_now . $temp_id_tem;
			}
		} else {
			// echo "tidak";
			$id = $year_now . "0001";
		}
		
		$data['work_order_number'] = $id;

		// $this->load->helper('comman_helper');
		// pr($data['company_name']);

		// get data trucking from all agreement
		// for ($i=0; $i < count($total_agreement->num_rows()); $i++) { 
		// 	$temp_data = $this->M_order->get_data_selling($total_agreement->row()->QUOTATION_NUMBER)->result();
		// 	foreach ($temp_data as $key => $value) {
		// 		$test_data['FROM_NAME'] = $value->FROM_NAME;
		// 		$test_data['TO_NAME'] = $value->TO_NAME;
		// 		$test_data['FROM_NAME_SHORT'] = $value->FROM_NAME_SHORT;
		// 		$test_data['TO_NAME_SHORT'] = $value->TO_NAME_SHORT;
		// 		$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
		// 		$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
		// 		$test_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
		// 		$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
		// 		$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
		// 		$test_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
		// 		$test_data['SELLING_OFFERING_RATE'] = $value->SELLING_OFFERING_RATE;
		// 		$test_data['AGREEMENT_NUMBER'] = $value->AGREEMENT_NUMBER;
		// 		$test_data['QUOTATION_NUMBER'] = $value->QUOTATION_NUMBER;
		// 		$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;

		// 		$result_trucking[] = $test_data;
		// 	}
		// }
		$temp_data = $this->M_order->get_data_selling($quotation_no)->result();
		foreach ($temp_data as $key => $value) {
			$test_data['FROM_NAME'] = $value->FROM_NAME;
			$test_data['TO_NAME'] = $value->TO_NAME;
			$test_data['FROM_NAME_SHORT'] = $value->FROM_NAME_SHORT;
			$test_data['TO_NAME_SHORT'] = $value->TO_NAME_SHORT;
			$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
			$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
			$test_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
			$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
			$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
			$test_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
			$test_data['SELLING_OFFERING_RATE'] = $value->SELLING_OFFERING_RATE;
			$test_data['AGREEMENT_NUMBER'] = $value->AGREEMENT_NUMBER;
			$test_data['QUOTATION_NUMBER'] = $value->QUOTATION_NUMBER;
			$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;

			$result_trucking[] = $test_data;
		}
		// $this->load->helper('comman_helper');
		// pr($result_trucking);
		$data['data_trucking'] = $result_trucking;

		$this->form_validation->set_rules('work_order_number', 'Work Order Number', 'required');
		$this->form_validation->set_rules('work_order_date', 'Work Order Date', 'required');
		$this->form_validation->set_rules('vessel_name', 'Vessel Name', 'required');
		$this->form_validation->set_rules('vessel_id', 'Vessel ID', 'required');
		$this->form_validation->set_rules('voyage_number', 'Voyage Number', 'required');
		$this->form_validation->set_rules('reference_number', 'Reference Number', 'required');
		$this->form_validation->set_rules('trade_id', 'Trade', 'required');
		$this->form_validation->set_rules('pol_name', 'Port of Loading', 'required');
		$this->form_validation->set_rules('pol_id', 'Port of Loading ID', 'required');
		$this->form_validation->set_rules('pod_name', 'Port of Discharge', 'required');
		$this->form_validation->set_rules('pod_id', 'Port of Discharge ID', 'required');
		$this->form_validation->set_rules('pod_id', 'Port of Discharge ID', 'required');
		$this->form_validation->set_rules('eta', 'Estimated Time of Arrival', 'required');
		$this->form_validation->set_rules('etd', 'Estimated Time of Departure', 'required');
		$this->form_validation->set_rules('trucking[][]', 'Container', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if (isset($_POST)) {
			// declare variable
			$work_order_number = $this->input->post('work_order_number');
			$work_order_date = $this->input->post('work_order_date');
			$vessel_id = $this->input->post('vessel_id');
			$voyage_number = $this->input->post('voyage_number');
			$trade_id = $this->input->post('trade_id');
			$pol_id = $this->input->post('pol_id');
			$pod_id = $this->input->post('pod_id');
			$reference_number = $this->input->post('reference_number');
			$etd = $this->input->post('etd');
			$eta = $this->input->post('eta');

			$trucking = $this->input->post('trucking');

			// check work order number
			$check_wo_number = $this->M_order->check_wo_number($work_order_number)->num_rows();

			// check container valid
			$data_costtrucking = $trucking; // create copy to delete dups from
			$newtrucking = array();
			$trucking_check = "";

			for( $i=0; $i<count($trucking); $i++ ) {

			    if ( isset($trucking) && in_array( array( $trucking[$i]['container_no'] ), $newtrucking ) ) {
			    	unset($newtrucking[$i]);
			    	unset($data_costtrucking[$i]);
			    	$trucking_check = "error";
			    }
			    else {
			    	$newtrucking[$i][] = $trucking[$i]['container_no'];
			    }

			}

			if (empty($newtrucking[0][0])) {
				$cek = "yes";
			} else {
				$cek = "no";
			}

			// $this->load->helper('comman_helper');
			// pr($data_costtrucking);

			if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_createworkorder', $data);
			} elseif ($check_wo_number > 0) {
				$data['wo_exists'] = "exists";
				$this->load->view('orders/v_createworkorder', $data);
			} elseif ($trucking_check == "error") {
				$data['trucking_error'] = "error";
				$this->load->view('orders/v_createworkorder', $data);
			} else {
				// $this->load->helper('comman_helper');
				// pr($this->input->post('trucking'));
				// $this->db->trans_begin();
				// try {
				// 	// $this->load->helper('comman_helper');
				// 	// pr($this->input->post('trucking'));

				// 	// insert data work order
				// 	$data_work_order = array(
				// 		'work_order_number' => $work_order_number,
				// 		'work_order_date' => $work_order_date,
				// 		'company_id' => $code_cmpy,
				// 		'reference_number' => $reference_number,
				// 		'trade_id' => $trade_id,
				// 		'vessel_id' => $vessel_id,
				// 		'customer_id' => $company_id,
				// 		'pol_id' => $pol_id,
				// 		'pod_id' => $pod_id,
				// 		'voyage_number' => $voyage_number,
				// 		'user_id' => $this->nik,
				// 		'user_date' => $date
				// 	);
					

				// 	// $update_voyage = array(
				// 	// 	'vessel_id' => $vessel_id,
				// 	// 	'voyage_number' => $voyage_number
				// 	// );

				// 	// $this->M_order->update_vessel('dbo.MVESSEL_VOYAGE', $update_voyage, $vessel_id);

				// 	// insert data work order service
				// 	$data_wo_service = array(
				// 		'work_order_number' => $work_order_number,
				// 		'company_id' => $code_cmpy,
				// 		'service_id' => 'SS01',
				// 		'user_id' => $this->nik,
				// 		'user_date' => $date
				// 	);

				// 	// insert to data work order container trucking
				// 	foreach ($trucking as $value) {
				// 		// get total cost
				// 		$cost = $this->M_order->get_total_cost($value['quotation_number'])->row()->TOTAL_COST;
				// 		$data_trucking = array(
				// 			'work_order_number' => $work_order_number,
				// 			'company_id' => $code_cmpy,
				// 			'selling_service_id' => $value['selling_service_id'],
				// 			'container_number' => $value['container_no'],
				// 			'seal_number' => $value['seal_no'],
				// 			'container_size_id' => $value['container_size_id'],
				// 			'container_type_id' => $value['container_type_id'],
				// 			'container_category_id' => $value['container_category_id'],
				// 			'from_location_id' => $value['from_location_id'],
				// 			'to_location_id' => $value['to_location_id'],
				// 			'tariff_currency' => $value['currency'],
				// 			'tariff_amount' => $value['amount'],
				// 			'agreement_number' => $value['agreement_number'],
				// 			'bl_number' => $value['bl_no'],
				// 			'cost' => $cost,
				// 			'user_id' => $this->nik,
				// 			'user_date' => $date,
				// 			'flag' => '0'
				// 		);
				// 		$this->db->insert('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking);
				// 	}

				// 	$this->db->insert('dbo.TRWORKORDER', $data_work_order);
				// 	$this->db->insert('dbo.TRWORKORDER_SERVICE', $data_wo_service);

				// 	if ($this->db->trans_status() === FALSE)
				// 	{
				// 		$this->session->set_flashdata('failed', "Failed entry work order!");
				// 		throw new Exception("Error Processing Request", 1);
						
				//         // echo "gagal";
				//         // redirect(current_url());
				// 	}
				// 	else
				// 	{
				//         $this->db->trans_commit();
				//         $this->session->set_flashdata('success', "Successfully entry work order!");
				//         // redirect(current_url());
				//         // echo "berhasil";
				// 	}
				// 	redirect(current_url());
				// } catch (Exception $e) {
				// 	$this->session->set_flashdata('failed', "Failed entry work order adawd!");
				// 	$this->db->trans_rollback();
				// 	// echo $e->getMessage();
				// 	redirect(current_url());
				// }

				$this->db->trans_begin();
				try {
					// insert data work order
					$data_work_order = array(
						'work_order_number' => $work_order_number,
						'work_order_date' => $work_order_date,
						'company_id' => $code_cmpy,
						'reference_number' => $reference_number,
						'trade_id' => $trade_id,
						'vessel_id' => $vessel_id,
						'customer_id' => $company_id,
						'pol_id' => $pol_id,
						'pod_id' => $pod_id,
						'voyage_number' => $voyage_number,
						'etd' => $etd,
						'eta' => $eta,
						'agreement_number' => $agreement_no,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					// insert data work order service
					$data_wo_service = array(
						'work_order_number' => $work_order_number,
						'company_id' => $code_cmpy,
						'service_id' => 'SS01',
						'user_id' => $this->nik,
						'user_date' => $date
					);

					// $result_wo = $this->M_order->insert_wo('dbo.TRWORKORDER', $data_work_order);
					if (!$this->db->insert('dbo.TRWORKORDER', $data_work_order)) {
						throw new Exception("Failed entry work order, something wrong with work order data!", 1);
					} 
					// $result_wo_service = $this->M_order->insert_wo_services('dbo.TRWORKORDER_SERVICE', $data_wo_service);
					if (!$this->db->insert('dbo.TRWORKORDER_SERVICE', $data_wo_service)) {
						throw new Exception("Failed entry work order, something wrong with work order service data!", 1);
					}

					// die();

					// pr($result_wo_service);

					// insert to data work order container trucking
					foreach ($trucking as $value) {
						if ($value['container_no'] == "" || $value['container_no'] == NULL) {
							throw new Exception("Failed entry work order, Please set container number!", 1);
						} else {
							// get total cost
							$cost = $this->M_order->get_total_cost($value['quotation_number'], $value['container_size_id'], $value['container_type_id'], $value['container_category_id'])->row()->TOTAL_COST;
							$data_trucking = array(
								'work_order_number' => $work_order_number,
								'company_id' => $code_cmpy,
								'selling_service_id' => $value['selling_service_id'],
								'container_number' => $value['container_no'],
								'seal_number' => $value['seal_no'],
								'container_size_id' => $value['container_size_id'],
								'container_type_id' => $value['container_type_id'],
								'container_category_id' => $value['container_category_id'],
								'from_location_id' => $value['from_location_id'],
								'to_location_id' => $value['to_location_id'],
								'tariff_currency' => $value['currency'],
								'tariff_amount' => $value['amount'],
								'agreement_number' => $value['agreement_number'],
								'quotation_number' => $value['quotation_number'],
								'bl_number' => $value['bl_no'],
								'cost' => $cost,
								'user_id' => $this->nik,
								'user_date' => $date,
								'flag' => '0'
							);
							$result_container = $this->db->insert('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking);
							if (!$result_container) {
								throw new Exception("Failed entry container data!", 1);
							}
						}

						// // cost
						// // get data cost from quotation (agreement)
						// $get_cost = $this->M_order->get_cost($value['container_size_id'], $value['container_type_id'], $value['container_category_id'], $value['from_location_id'], $value['to_location_id'])->result();

						// foreach ($get_cost as $key1 => $value1) {
						// 	$cost_trucking[] = array(
						// 		'work_order_number' => $work_order_number,
						// 		'company_id' => $code_cmpy,
						// 		'container_number' => $value['container_no'],
						// 		'cost_id' => $value1->COST_ID,
						// 		'container_size_id' => $value['container_size_id'],
						// 		'container_type_id' => $value['container_type_id'],
						// 		'container_category_id' => $value['container_category_id'],
						// 		'from_location_id' => $value['from_location_id'],
						// 		'to_location_id' => $value['to_location_id'],
						// 		'from_qty' => $value1->FROM_QTY,
						// 		'to_qty' => $value1->TO_QTY,
						// 		'start_date' => $value1->START_DATE,
						// 		'end_date' => $value1->END_DATE,
						// 		'cost_type_id' => $value1->COST_TYPE_ID,
						// 		'cost_group_id' => $value1->COST_GROUP_ID,
						// 		'calc_type' => $value1->CALC_TYPE,
						// 		'increment_qty' => $value1->INCREMENT_QTY,
						// 		'cost_currency' => $value1->COST_CURRENCY,
						// 		'cost_amount' => $value1->COST_AMOUNT,
						// 		'user_id' => $this->nik,
						// 		'user_date' => $date
						// 	);
						// }
						
					}
					// // insert to table cost wo trucking
					// if (!$this->db->insert_batch('dbo.TRWORKORDER_COST_SERVICE_CONTAINER_ATTRIBUTE', $cost_trucking)) {
					// 	throw new Exception("Failed entry work order, something wrong with cost container!", 1);
					// }

					// $this->db->trans_complete();

					if ($this->db->trans_status() === FALSE)
					{
						throw new Exception("Failed entry work order, something wrong!", 1);
				        // // echo "gagal";
				        // redirect(current_url());
					}
					else
					{
				        $this->db->trans_commit();
				        $this->session->set_flashdata('success', "Successfully entry work order!");
				        redirect('Order/index');
				        // echo "berhasil";
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function create_wo()
	{
		$company_id = $this->uri->segment(3);
		$quotation_no = $this->uri->segment(4);
		$agreement_no = $this->uri->segment(5);
		$date = date('Y-m-d H:i:s');

		$data_trucking = array();
		$cost_trucking = array(); 

		$data['company_id'] = $company_id;
		$data['company_name'] = $this->M_order->get_company($company_id)->row()->NAME;
		$data['service_order'] = $this->M_order->get_general_service()->result();

		$total_agreement = $this->M_order->get_total_agreement($company_id);

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		$temp_id = $this->M_order->get_max_id()->row()->id;
		$temp_year = $this->M_order->get_max_id()->row()->year_temp;
		$year_now = date('y');
		// $date = date('Y-m-d H:i:s');

		$potongan_tahun = substr($temp_year, 0,2);

		if ($potongan_tahun == $year_now) {
			// echo "sama";

			$temp_id_tem = $temp_id + 1;
			if ($temp_id_tem < 10) {
				$id = $year_now . "000" . $temp_id_tem;
			} elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
				$id = $year_now . "00" . $temp_id_tem;
			} elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
				$id = $year_now . "0" . $temp_id_tem;
			} else {
				$id = $year_now . $temp_id_tem;
			}
		} else {
			// echo "tidak";
			$id = $year_now . "0001";
		}
		
		$data['work_order_number'] = $id;

		$works = $id;

		// $this->load->helper('comman_helper');
		// pr($data['company_name']);

		// get data trucking from all agreement
		$temp_data = $this->M_order->get_data_selling($quotation_no)->result();
		foreach ($temp_data as $key => $value) {
			$test_data['FROM_NAME'] = $value->FROM_NAME;
			$test_data['TO_NAME'] = $value->TO_NAME;
			$test_data['FROM_NAME_SHORT'] = $value->FROM_NAME_SHORT;
			$test_data['TO_NAME_SHORT'] = $value->TO_NAME_SHORT;
			$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
			$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
			$test_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
			$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
			$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
			$test_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
			$test_data['SELLING_OFFERING_RATE'] = $value->SELLING_OFFERING_RATE;
			$test_data['AGREEMENT_NUMBER'] = $value->AGREEMENT_NUMBER;
			$test_data['QUOTATION_NUMBER'] = $value->QUOTATION_NUMBER;
			$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;

			$result_trucking[] = $test_data;
		}
		$data['data_trucking'] = $result_trucking;

		// $this->form_validation->set_rules('work_order_number', 'Work Order Number', 'required');
		$this->form_validation->set_rules('work_order_date', 'Work Order Date', 'required');
		$this->form_validation->set_rules('vessel_name', 'Vessel Name', 'required');
		$this->form_validation->set_rules('shipper', 'Shipper', 'required');
		$this->form_validation->set_rules('consignee', 'Consignee', 'required');
		$this->form_validation->set_rules('vessel_id', 'Vessel ID', 'required');
		$this->form_validation->set_rules('voyage_number', 'Voyage Number', 'required');
		$this->form_validation->set_rules('reference_number', 'Reference Number', 'required');
		$this->form_validation->set_rules('trade_id', 'Trade', 'required');
		$this->form_validation->set_rules('pol_name', 'Port of Loading', 'required');
		$this->form_validation->set_rules('pol_id', 'Port of Loading ID', 'required');
		$this->form_validation->set_rules('pod_name', 'Port of Discharge', 'required');
		$this->form_validation->set_rules('pod_id', 'Port of Discharge ID', 'required');
		$this->form_validation->set_rules('pod_id', 'Port of Discharge ID', 'required');
		// $this->form_validation->set_rules('eta', 'Estimated Time of Arrival', 'required');
		// $this->form_validation->set_rules('etd', 'Estimated Time of Departure', 'required');
		$this->form_validation->set_rules('trucking[][]', 'Container', 'required');

		$this->load->helper('comman_helper');
		$truckss = $this->input->post('trucking');
		$trades = $this->input->post('trade_id');

		if ($trades == "IMP") {
			$this->form_validation->set_rules('eta', 'Estimated Time of Arrival', 'required');
		}

		if ($trades == "EXP") {
			$this->form_validation->set_rules('etd', 'Estimated Time of Departure', 'required');
		}

		// pr($truckss);
		if (!empty($truckss)) {
			// echo "tidak kosong";
			// // return true;
			foreach ($truckss as $key => $value) {
				$this->form_validation->set_rules('trucking['.$key.'][container_no]', 'Container Number', 'required');
				$this->form_validation->set_rules('trucking['.$key.'][seal_no]', 'Seal Number', 'required');
				$this->form_validation->set_rules('trucking['.$key.'][bl_no]', 'BL Number', 'required');
			}
		}

		// $this->form_validation->set_rules('container_no', 'Container Number', 'required');
		// $this->form_validation->set_rules('container_no', 'Container Number', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if (isset($_POST)) {
			// declare variable
			// $work_order_number = $this->input->post('work_order_number');
			$work_order_number = $works;
			$work_order_date = $this->input->post('work_order_date');
			$shipper = $this->input->post('shipper');
			$consignee = $this->input->post('consignee');
			$vessel_id = $this->input->post('vessel_id');
			$voyage_number = $this->input->post('voyage_number');
			$trade_id = $this->input->post('trade_id');
			$pol_id = $this->input->post('pol_id');
			$pod_id = $this->input->post('pod_id');
			$reference_number = $this->input->post('reference_number');
			$etd = $this->input->post('etd');
			$eta = $this->input->post('eta');
			$service = $this->input->post('service');

			$trucking = $this->input->post('trucking');

			// check work order number
			$check_wo_number = $this->M_order->check_wo_number($work_order_number)->num_rows();

			// check container valid
			$data_costtrucking = $trucking; // create copy to delete dups from
			$newtrucking = array();
			$trucking_check = "";

			for( $i=0; $i<count($trucking); $i++ ) {

			    if ( isset($trucking) && in_array( array( $trucking[$i]['container_no'] ), $newtrucking ) ) {
			    	unset($newtrucking[$i]);
			    	unset($data_costtrucking[$i]);
			    	$trucking_check = "error";
			    }
			    else {
			    	$newtrucking[$i][] = $trucking[$i]['container_no'];
			    }

			}

			if (empty($newtrucking[0][0])) {
				$cek = "yes";
			} else {
				$cek = "no";
			}

			// $this->load->helper('comman_helper');
			// pr($data_costtrucking);

			if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_createworkorder', $data);
			} elseif ($check_wo_number > 0) {
				$data['wo_exists'] = "exists";
				$this->load->view('orders/v_createworkorder', $data);
			} elseif ($trucking_check == "error") {
				$data['trucking_error'] = "error";
				$this->load->view('orders/v_createworkorder', $data);
			} else {
				// $this->db->trans_begin();
				// try {
				// 	// $this->load->helper('comman_helper');
				// 	// pr($this->input->post('trucking'));

				// 	// insert data work order
				// 	$data_work_order = array(
				// 		'work_order_number' => $work_order_number,
				// 		'work_order_date' => $work_order_date,
				// 		'company_id' => $code_cmpy,
				// 		'reference_number' => $reference_number,
				// 		'trade_id' => $trade_id,
				// 		'vessel_id' => $vessel_id,
				// 		'customer_id' => $company_id,
				// 		'pol_id' => $pol_id,
				// 		'pod_id' => $pod_id,
				// 		'voyage_number' => $voyage_number,
				// 		'user_id' => $this->nik,
				// 		'user_date' => $date
				// 	);
					

				// 	// $update_voyage = array(
				// 	// 	'vessel_id' => $vessel_id,
				// 	// 	'voyage_number' => $voyage_number
				// 	// );

				// 	// $this->M_order->update_vessel('dbo.MVESSEL_VOYAGE', $update_voyage, $vessel_id);

				// 	// insert data work order service
				// 	$data_wo_service = array(
				// 		'work_order_number' => $work_order_number,
				// 		'company_id' => $code_cmpy,
				// 		'service_id' => 'SS01',
				// 		'user_id' => $this->nik,
				// 		'user_date' => $date
				// 	);

				// 	// insert to data work order container trucking
				// 	foreach ($trucking as $value) {
				// 		// get total cost
				// 		$cost = $this->M_order->get_total_cost($value['quotation_number'])->row()->TOTAL_COST;
				// 		$data_trucking = array(
				// 			'work_order_number' => $work_order_number,
				// 			'company_id' => $code_cmpy,
				// 			'selling_service_id' => $value['selling_service_id'],
				// 			'container_number' => $value['container_no'],
				// 			'seal_number' => $value['seal_no'],
				// 			'container_size_id' => $value['container_size_id'],
				// 			'container_type_id' => $value['container_type_id'],
				// 			'container_category_id' => $value['container_category_id'],
				// 			'from_location_id' => $value['from_location_id'],
				// 			'to_location_id' => $value['to_location_id'],
				// 			'tariff_currency' => $value['currency'],
				// 			'tariff_amount' => $value['amount'],
				// 			'agreement_number' => $value['agreement_number'],
				// 			'bl_number' => $value['bl_no'],
				// 			'cost' => $cost,
				// 			'user_id' => $this->nik,
				// 			'user_date' => $date,
				// 			'flag' => '0'
				// 		);
				// 		$this->db->insert('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking);
				// 	}

				// 	$this->db->insert('dbo.TRWORKORDER', $data_work_order);
				// 	$this->db->insert('dbo.TRWORKORDER_SERVICE', $data_wo_service);

				// 	if ($this->db->trans_status() === FALSE)
				// 	{
				// 		$this->session->set_flashdata('failed', "Failed entry work order!");
				// 		throw new Exception("Error Processing Request", 1);
						
				//         // echo "gagal";
				//         // redirect(current_url());
				// 	}
				// 	else
				// 	{
				//         $this->db->trans_commit();
				//         $this->session->set_flashdata('success', "Successfully entry work order!");
				//         // redirect(current_url());
				//         // echo "berhasil";
				// 	}
				// 	redirect(current_url());
				// } catch (Exception $e) {
				// 	$this->session->set_flashdata('failed', "Failed entry work order adawd!");
				// 	$this->db->trans_rollback();
				// 	// echo $e->getMessage();
				// 	redirect(current_url());
				// }

				$this->db->trans_begin();
				try {
					// insert data work order
					$data_work_order = array(
						'work_order_number' => $work_order_number,
						'work_order_date' => $work_order_date,
						'company_id' => $code_cmpy,
						'reference_number' => $reference_number,
						'trade_id' => $trade_id,
						'vessel_id' => $vessel_id,
						'customer_id' => $company_id,
						'pol_id' => $pol_id,
						'pod_id' => $pod_id,
						'voyage_number' => $voyage_number,
						'etd' => $etd,
						'eta' => $eta,
						'agreement_number' => $agreement_no,
						'shipper' => $shipper,
						'consignee' => $consignee,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					// $result_wo = $this->M_order->insert_wo('dbo.TRWORKORDER', $data_work_order);
					if (!$this->db->insert('dbo.TRWORKORDER', $data_work_order)) {
						throw new Exception("Failed entry work order, something wrong with work order data!", 1);
					}

					foreach ($service as $value) {
						// insert data work order service
						$data_wo_service = array(
							'work_order_number' => $work_order_number,
							'company_id' => $code_cmpy,
							'service_id' => $value,
							'user_id' => $this->nik,
							'user_date' => $date
						);

						// $result_wo_service = $this->M_order->insert_wo_services('dbo.TRWORKORDER_SERVICE', $data_wo_service);
						if (!$this->db->insert('dbo.TRWORKORDER_SERVICE', $data_wo_service)) {
							throw new Exception("Failed entry work order, something wrong with work order service data!", 1);
						}
					}

					// die();

					// pr($result_wo_service);

					// insert to data work order container trucking
					foreach ($trucking as $value) {
						if ($value['container_no'] == "" || $value['container_no'] == NULL) {
							throw new Exception("Failed entry work order, Please set container number!", 1);
						} else {
							// get total cost
							$cost = $this->M_order->get_total_cost($value['quotation_number'], $value['container_size_id'], $value['container_type_id'], $value['container_category_id'])->row()->TOTAL_COST;
							
							// $do_amount = $this->input->post('amount');
							// $do_amount_fix = str_replace(',', '', $do_amount);

							// $thc_amount = $this->input->post('amount');
							// $thc_amount_fix = str_replace(',', '', $thc_amount);

							// $guarantee_amount = $this->input->post('amount');
							// $guarantee_amount_fix = str_replace(',', '', $guarantee_amount);

							// $data_trucking = array(
							// 	'work_order_number' => $work_order_number,
							// 	'company_id' => $code_cmpy,
							// 	'selling_service_id' => $value['selling_service_id'],
							// 	'container_number' => $value['container_no'],
							// 	'seal_number' => $value['seal_no'],
							// 	'container_size_id' => $value['container_size_id'],
							// 	'container_type_id' => $value['container_type_id'],
							// 	'container_category_id' => $value['container_category_id'],
							// 	'from_location_id' => $value['from_location_id'],
							// 	'to_location_id' => $value['to_location_id'],
							// 	'tariff_currency' => $value['currency'],
							// 	'tariff_amount' => $value['amount'],
							// 	'agreement_number' => $value['agreement_number'],
							// 	'quotation_number' => $value['quotation_number'],
							// 	'bl_number' => $value['bl_no'],
							// 	'cost' => $cost,
							// 	'user_id' => $this->nik,
							// 	'user_date' => $date,
							// 	'flag' => '0'
							// );

							$data_trucking = array(
								'work_order_number' => $work_order_number,
								'company_id' => $code_cmpy,
								'selling_service_id' => $value['selling_service_id'],
								'container_number' => $value['container_no'],
								'seal_number' => $value['seal_no'],
								'container_size_id' => $value['container_size_id'],
								'container_type_id' => $value['container_type_id'],
								'container_category_id' => $value['container_category_id'],
								'from_location_id' => $value['from_location_id'],
								'to_location_id' => $value['to_location_id'],
								'tariff_currency' => $value['currency'],
								'agreement_number' => $value['agreement_number'],
								'quotation_number' => $value['quotation_number'],
								'bl_number' => $value['bl_no'],
								'user_id' => $this->nik,
								'user_date' => $date,
								'flag' => '0'
							);

							// $data_cost_do = $this->M_order->get_data_cost_do($value['container_size_id'], $value['container_type_id'], $value['container_category_id'], $value['from_location_id'], $value['to_location_id']);
							$data_cost_do = $this->M_order->get_data_cost_do($value['container_size_id'], $value['container_type_id'], $value['container_category_id']);

							if ($data_cost_do->num_rows() > 0) {
								foreach ($data_cost_do->result() as $key1 => $value1) {
									$data_cash = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $value1->COST_ID,
										'container_number' => $value['container_no'],
										'cost_currency' => $value1->COST_CURRENCY,
										'cost_type_id' => $value1->COST_TYPE_ID,
										'cost_group_id' => $value1->COST_GROUP_ID,
										'cost_request_amount' => $value1->COST_AMOUNT,
										'request_date' => $date,
										'user_id_request' => $this->nik,
										'user_id' => $this->nik,
										'user_date' => $date,
										'status' => 'do',
										'cost_kind' => 'S',
										'sequence_id' => '0',
										'is_transfered' => 'N'
									);

									// insert into table trcash_request
									if (!$this->db->insert('dbo.TRCASH_REQUEST', $data_cash)) {
										throw new Exception("Failed inserted cash request customs, error(204)!", 1);
									}
								}
							}

							$result_container = $this->db->insert('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking);
							if (!$result_container) {
								throw new Exception("Failed entry container data!", 1);
							}
						}

						// // cost
						// // get data cost from quotation (agreement)
						// $get_cost = $this->M_order->get_cost($value['container_size_id'], $value['container_type_id'], $value['container_category_id'], $value['from_location_id'], $value['to_location_id'])->result();

						// foreach ($get_cost as $key1 => $value1) {
						// 	$cost_trucking[] = array(
						// 		'work_order_number' => $work_order_number,
						// 		'company_id' => $code_cmpy,
						// 		'container_number' => $value['container_no'],
						// 		'cost_id' => $value1->COST_ID,
						// 		'container_size_id' => $value['container_size_id'],
						// 		'container_type_id' => $value['container_type_id'],
						// 		'container_category_id' => $value['container_category_id'],
						// 		'from_location_id' => $value['from_location_id'],
						// 		'to_location_id' => $value['to_location_id'],
						// 		'from_qty' => $value1->FROM_QTY,
						// 		'to_qty' => $value1->TO_QTY,
						// 		'start_date' => $value1->START_DATE,
						// 		'end_date' => $value1->END_DATE,
						// 		'cost_type_id' => $value1->COST_TYPE_ID,
						// 		'cost_group_id' => $value1->COST_GROUP_ID,
						// 		'calc_type' => $value1->CALC_TYPE,
						// 		'increment_qty' => $value1->INCREMENT_QTY,
						// 		'cost_currency' => $value1->COST_CURRENCY,
						// 		'cost_amount' => $value1->COST_AMOUNT,
						// 		'user_id' => $this->nik,
						// 		'user_date' => $date
						// 	);
						// }
						
					}
					// // insert to table cost wo trucking
					// if (!$this->db->insert_batch('dbo.TRWORKORDER_COST_SERVICE_CONTAINER_ATTRIBUTE', $cost_trucking)) {
					// 	throw new Exception("Failed entry work order, something wrong with cost container!", 1);
					// }

					// $this->db->trans_complete();

					if ($this->db->trans_status() === FALSE)
					{
						throw new Exception("Failed entry work order, something wrong!", 1);
				        // // echo "gagal";
				        // redirect(current_url());
					}
					else
					{
				        $this->db->trans_commit();
				        $this->session->set_flashdata('success', "Successfully entry work order!");
				        redirect('Order/index');
				        // echo "berhasil";
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function edit_wo()
	{
		$this->load->helper('comman_helper');
		$work_order_number = $this->uri->segment(3);
		$agreement_no = $this->uri->segment(4);

		$date = date('Y-m-d H:i:s');

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		$data['wo_number'] = $work_order_number;
		$data['agreement_no'] = $agreement_no;
		$data_wo = $this->M_order->get_wo($work_order_number);
		$data['wo_date'] = $data_wo->row()->WORK_ORDER_DATE;
		$data['vessel_name'] = $data_wo->row()->TRADE_ID . " - " . $data_wo->row()->VESSEL_NAME;
		$data['vessel_id'] = $data_wo->row()->VESSEL_ID;
		$data['voyage_number'] = $data_wo->row()->VOYAGE_NUMBER;
		$data['eta'] = $data_wo->row()->ETA;
		$data['etd'] = $data_wo->row()->ETD;
		$data['pol_id'] = $data_wo->row()->POL_ID;
		$data['pod_id'] = $data_wo->row()->POD_ID;
		$data['pol_name'] = $data_wo->row()->POL_NAME;
		$data['pod_name'] = $data_wo->row()->POD_NAME;
		$data['customer_name'] = $data_wo->row()->CUSTOMER_NAME;
		$data['customer_id'] = $data_wo->row()->CUSTOMER_ID;
		$data['reference_number'] = $data_wo->row()->REFERENCE_NUMBER;
		$data['trade_id'] = $data_wo->row()->TRADE_ID;
		$data['shipper'] = $data_wo->row()->SHIPPER;
		$data['consignee'] = $data_wo->row()->CONSIGNEE;

		// $data['data_trucking_wo'] = $this->M_order->get_data_trucking_wo($work_order_number)->result();
		$temp_trucking_wo = $this->M_order->get_data_trucking_wo($work_order_number)->result();
		foreach ($temp_trucking_wo as $key => $value) {
			$temp_truck['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
			$temp_truck['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
			$temp_truck['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
			$temp_truck['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
			$temp_truck['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
			$temp_truck['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
			$temp_truck['TARIFF_AMOUNT'] = $value->TARIFF_AMOUNT;
			$temp_truck['AGREEMENT_NUMBER'] = $value->AGREEMENT_NUMBER;
			$temp_truck['QUOTATION_NUMBER'] = $value->QUOTATION_NUMBER;
			$temp_truck['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
			$temp_truck['FROM_NAME'] = $value->FROM_NAME;
			$temp_truck['TO_NAME'] = $value->TO_NAME;
			$temp_truck['CONTAINER_NUMBER'] = $value->CONTAINER_NUMBER;
			$temp_truck['SEAL_NUMBER'] = $value->SEAL_NUMBER;
			$temp_truck['BL_NUMBER'] = $value->BL_NUMBER;

			$result_pivot_trucking[] = $temp_truck;
		}
		$data['data_trucking_wo'] = $result_pivot_trucking;
		// pr($result_pivot_trucking);

		// $total_agreement = $this->M_order->get_total_agreement($data['customer_id']);
		$quotation_no = $this->M_order->get_quotation_number($agreement_no)->row()->QUOTATION_NUMBER;

		// get data trucking from all agreement
		$temp_data = $this->M_order->get_data_selling($quotation_no)->result();
		foreach ($temp_data as $key => $value) {
			$test_data['FROM_NAME'] = $value->FROM_NAME;
			$test_data['TO_NAME'] = $value->TO_NAME;
			$test_data['FROM_NAME_SHORT'] = $value->FROM_NAME_SHORT;
			$test_data['TO_NAME_SHORT'] = $value->TO_NAME_SHORT;
			$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
			$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
			$test_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
			$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
			$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
			$test_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
			$test_data['SELLING_OFFERING_RATE'] = $value->SELLING_OFFERING_RATE;
			$test_data['AGREEMENT_NUMBER'] = $value->AGREEMENT_NUMBER;
			$test_data['QUOTATION_NUMBER'] = $value->QUOTATION_NUMBER;
			$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;

			$result_trucking[] = $test_data;
		}
		$data['data_trucking'] = $result_trucking;
		// $this->load->helper('comman_helper');
		// pr($data['data_trucking_wo']);

		$this->form_validation->set_rules('work_order_number', 'Work Order Number', 'required');
		$this->form_validation->set_rules('work_order_date', 'Work Order Date', 'required');
		$this->form_validation->set_rules('vessel_name', 'Vessel Name', 'required');
		$this->form_validation->set_rules('shipper', 'Shipper', 'required');
		$this->form_validation->set_rules('consignee', 'Consignee', 'required');
		$this->form_validation->set_rules('vessel_id', 'Vessel ID', 'required');
		$this->form_validation->set_rules('voyage_number', 'Voyage Number', 'required');
		$this->form_validation->set_rules('reference_number', 'Reference Number', 'required');
		$this->form_validation->set_rules('trade_id', 'Trade', 'required');
		$this->form_validation->set_rules('pol_name', 'Port of Loading', 'required');
		$this->form_validation->set_rules('pol_id', 'Port of Loading ID', 'required');
		$this->form_validation->set_rules('pod_name', 'Port of Discharge', 'required');
		$this->form_validation->set_rules('pod_id', 'Port of Discharge ID', 'required');
		$this->form_validation->set_rules('pod_id', 'Port of Discharge ID', 'required');
		$this->form_validation->set_rules('eta', 'Estimated Time of Arrival', 'required');
		$this->form_validation->set_rules('etd', 'Estimated Time of Departure', 'required');
		$this->form_validation->set_rules('trucking[][]', 'Container', 'required');
		// $this->form_validation->set_rules('container_no', 'Container Number', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if (isset($_POST)) {
			// declare variable
			$work_order_number = $this->input->post('work_order_number');
			$work_order_date = $this->input->post('work_order_date');
			$shipper = $this->input->post('shipper');
			$consignee = $this->input->post('consignee');
			$vessel_id = $this->input->post('vessel_id');
			$voyage_number = $this->input->post('voyage_number');
			$trade_id = $this->input->post('trade_id');
			$pol_id = $this->input->post('pol_id');
			$pod_id = $this->input->post('pod_id');
			$reference_number = $this->input->post('reference_number');
			$etd = $this->input->post('etd');
			$eta = $this->input->post('eta');
			$customer_id = $this->input->post('customer_id');

			$trucking = $this->input->post('trucking');

			// check container valid
			$data_costtrucking = $trucking; // create copy to delete dups from
			$newtrucking = array();
			$trucking_check = "";

			for( $i=0; $i<count($trucking); $i++ ) {

			    if ( isset($trucking) && in_array( array( $trucking[$i]['container_no'] ), $newtrucking ) ) {
			    	unset($newtrucking[$i]);
			    	unset($data_costtrucking[$i]);
			    	$trucking_check = "error";
			    }
			    else {
			    	$newtrucking[$i][] = $trucking[$i]['container_no'];
			    }

			}

			if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_editwo', $data);
			} elseif ($trucking_check == "error") {
				$data['trucking_error'] = "error";
				$this->load->view('orders/v_editwo', $data);
			} else {
				// pr($check_wo_customs);
				$this->db->trans_begin();
				try {
					// insert data work order
					$data_work_order = array(
						'work_order_number' => $work_order_number,
						'work_order_date' => $work_order_date,
						'company_id' => $code_cmpy,
						'reference_number' => $reference_number,
						'trade_id' => $trade_id,
						'vessel_id' => $vessel_id,
						'shipper' => $shipper,
						'consignee' => $consignee,
						'customer_id' => $customer_id,
						'pol_id' => $pol_id,
						'pod_id' => $pod_id,
						'voyage_number' => $voyage_number,
						'etd' => $etd,
						'eta' => $eta,
						'user_id' => $this->nik,
						'user_date' => $date
					);
					$result_update_wo = $this->M_order->update_wo($work_order_number, 'dbo.TRWORKORDER', $data_work_order);
					if ($result_update_wo == FALSE) {
						throw new Exception("Error updating work order data, update error!", 1);
					}

					foreach ($trucking as $value) {
						// change to table TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE
						$data_trucking = array(
							'container_number' => $value['container_no'],
							'seal_number' => $value['seal_no'],
							'bl_number' => $value['bl_no']
						);
						// update data seal or BL number
						$update_container_attr = $this->M_order->update_container_attr('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking, $value['old_container_number'], $work_order_number);
						if ($update_container_attr == FALSE) {
							throw new Exception("Error Processing Request to Update Data BL or Seal Number", 1);
						}

						// change to table trwo cost additional
						$data_tr_cost_additional = array(
							'container_number' => $value['container_no']
						);
						$update_tr_cost_additional = $this->M_order->update_tr_cost_additional('dbo.TRWORKORDER_COST_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $data_tr_cost_additional, $value['old_container_number'], $work_order_number);
						if ($update_tr_cost_additional == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table Cost Additional WO", 1);
						}

						// change to table trwo cost container
						$data_tr_cost_container = array(
							'container_number' => $value['container_no']
						);
						$update_tr_cost_container = $this->M_order->update_tr_cost_container('dbo.TRWORKORDER_COST_SERVICE_CONTAINER_ATTRIBUTE', $data_tr_cost_container, $value['old_container_number'], $work_order_number);
						if ($update_tr_cost_container == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table Cost Container", 1);
						}

						// change to table trwo cost container customs
						$data_tr_cost_container_customs = array(
							'container_number' => $value['container_no']
						);
						$update_tr_cost_container_customs = $this->M_order->update_tr_cost_container_customs('dbo.TRWORKORDER_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_tr_cost_container_customs, $value['old_container_number'], $work_order_number);
						if ($update_tr_cost_container_customs == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table Cost Container Customs", 1);
						}

						// change to table trwo additional selling
						$data_tr_wo_additional = array(
							'container_number' => $value['container_no']
						);
						$update_tr_wo_additional_selling = $this->M_order->update_tr_wo_additional_selling('dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $data_tr_wo_additional, $value['old_container_number'], $work_order_number);
						if ($update_tr_wo_additional_selling == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table additional selling", 1);
						}

						// change to table trwo trucking
						$data_tr_wo_trucking = array(
							'container_number' => $value['container_no']
						);
						$update_tr_wo_trucking = $this->M_order->update_tr_wo_trucking('dbo.TRWORKORDER_TRUCKING', $data_tr_wo_trucking, $value['old_container_number'], $work_order_number);
						if ($update_tr_wo_trucking == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table Wo Trucking", 1);
						}

						// change to table trtransfer detail
						$data_tr_transfer_detail = array(
							'container_number' => $value['container_no']
						);
						$update_tr_transfer_detail = $this->M_order->update_tr_transfer_detail('dbo.TRTRANSFER_DETAIL', $data_tr_transfer_detail, $value['old_container_number'], $work_order_number);
						if ($update_tr_transfer_detail == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table Transfer Detail", 1);
						}

						// change to table troperational detail
						$data_tr_operational_detail = array(
							'container_number' => $value['container_no']
						);
						$update_tr_operational_detail = $this->M_order->update_tr_operational_detail('dbo.TROPERATIONAL_DETAIL', $data_tr_operational_detail, $value['old_container_number'], $work_order_number);
						if ($update_tr_operational_detail == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table operational detail", 1);
						}

						// change to table trinvoice detail
						$data_tr_invoice_detail = array(
							'container_number' => $value['container_no']
						);
						$update_tr_invoice_detail = $this->M_order->update_tr_invoice_detail('dbo.TRINVOICE_DETAIL', $data_tr_invoice_detail, $value['old_container_number'], $work_order_number);
						if ($update_tr_invoice_detail == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table invoice detail", 1);
						}

						// change to table trcash request
						$data_tr_cash_request = array(
							'container_number' => $value['container_no']
						);
						$update_tr_cash_request = $this->M_order->update_tr_cash_request('dbo.TRCASH_REQUEST', $data_tr_cash_request, $value['old_container_number'], $work_order_number);
						if ($update_tr_cash_request == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table Cash Request", 1);
						}

						// change to table trcash request additional
						$data_tr_cash_request_additional = array(
							'container_number' => $value['container_no']
						);
						$update_tr_cash_request_additional = $this->M_order->update_tr_cash_request_additional('dbo.TRCASH_REQUEST_ADDITIONAL', $data_tr_cash_request_additional, $value['old_container_number'], $work_order_number);
						if ($update_tr_cash_request_additional == FALSE) {
							throw new Exception("Error Processing Request to Update Data Table Cash Request Additional", 1);
						}

					}

					// $this->db->trans_complete();

					if ($this->db->trans_status() === FALSE)
					{
						throw new Exception("Failed update work order, something wrong!", 1);
					}
					else
					{
				        $this->db->trans_commit();
				        $this->session->set_flashdata('success', "Successfully update work order!");
				        redirect(current_url());
				        // echo "berhasil";
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	public function search_vessel()
	{
		$kode = $this->input->get('term');
		$vessel = $this->M_order->get_location($kode)->result();

		foreach ($vessel as $key => $value) {
			$temp_location['value'] =  $value->VESSEL_NAME . " - " . $value->VOYAGE_NUMBER;
			$temp_location['vessel_id'] = $value->VESSEL_ID;
			$temp_location['vessel_name'] = $value->VESSEL_NAME;
			$temp_location['trade'] = $value->TRADE;
			$temp_location['pol_id'] = $value->POL_ID;
			$temp_location['pod_id'] = $value->POD_ID;
			$temp_location['pol_name'] = $value->POL_NAME;
			$temp_location['pod_name'] = $value->POD_NAME;
			$temp_location['voyage_number'] = $value->VOYAGE_NUMBER;
			$temp_location['eta'] = $value->ETA;
			$temp_location['etd'] = $value->ETD;
			$result_location[] = $temp_location;
		}
		// $this->load->helper('comman_helper');
		// pr($result_location);
		echo json_encode($result_location);
	}

	public function search_vessel2()
	{
		$kode = $this->input->get('term');
		$vessel = $this->M_order->get_vessel2($kode)->result();

		foreach ($vessel as $key => $value) {
			$temp_location['value'] =  $value->VESSEL_NAME;
			$temp_location['vessel_id'] = $value->VESSEL_ID;
			$temp_location['vessel_name'] = $value->VESSEL_NAME;
			$result_location[] = $temp_location;
		}
		// $this->load->helper('comman_helper');
		// pr($result_location);
		echo json_encode($result_location);
	}

	public function search_port()
	{
		$kode = $this->input->get('term');
		$port = $this->M_order->get_port($kode)->result();

		foreach ($port as $key => $value) {
			$temp_location['value'] =  $value->PORT_NAME;
			$temp_location['port_id'] = $value->PORT_ID;
			$result_location[] = $temp_location;
		}
		// $this->load->helper('comman_helper');
		// pr($result_location);
		echo json_encode($result_location);
	}

	function entry_customs()
	{
		$this->load->helper('comman_helper');
		$work_order_number = $this->uri->segment(3);
		$date = date('Y-m-d H:i:s');

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		
		$data_wo = $this->M_order->get_wo_customs($work_order_number);
		$data['work_order_number'] = $data_wo->row()->WORK_ORDER_NUMBER;
		$data['company_id'] = $data_wo->row()->COMPANY_ID;
		$data['customer_name'] = $data_wo->row()->CUSTOMER_NAME;
		$data['work_order_date'] = $data_wo->row()->WORK_ORDER_DATE;
		$data['data_trucking'] = $this->M_order->get_data_trucking_wo_customs($work_order_number)->result();
		$data['data_measurement'] = $this->M_order->get_measurement()->result();
		// pr($data['data_trucking']);
		$data['data_lane'] = $this->M_order->get_lane()->result();
		$data['data_customs_location'] = $this->M_order->get_customs_location()->result();
		$data['company_hanoman'] = $this->M_order->get_company_hanoman()->result();
		$wo_customs = $this->M_order->get_data_customs($work_order_number);
		// $this->load->helper('comman_helper');
		// pr($wo_customs->num_rows());
		$data['hoarding_name'] = $wo_customs->row()->HOARDING_NAME;
		$data['hoarding_id'] = $wo_customs->row()->HOARDING_ID;
		$data['register_number'] = $wo_customs->row()->REGISTER_NUMBER_PIB_PEB;
		$data['register_date'] = $wo_customs->row()->REGISTER_DATE;
		$data['importir_id'] = $wo_customs->row()->IMPORTIR_ID;
		$data['importir_name'] = $wo_customs->row()->CUSTOMER_NAME;
		$data['ppjk_id'] = $wo_customs->row()->PPJK_ID;
		$data['data_hoarding'] = $this->M_order->get_hoarding2()->result();
		$data['data_commodity'] = $this->M_order->get_commodity2()->result();

		$customer_id = $data_wo->row()->CUSTOMER_ID;
		$total_agreement = $this->M_order->get_total_agreement($customer_id);
		$quotation_no = $this->M_order->get_quotation($customer_id)->row()->QUOTATION_NUMBER;
		// get data trucking from all agreement
		for ($i=0; $i < count($total_agreement->num_rows()); $i++) { 
			$temp_data = $this->M_order->get_data_selling($total_agreement->row()->QUOTATION_NUMBER)->result();
			foreach ($temp_data as $key => $value) {
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['FROM_NAME_SHORT'] = $value->FROM_NAME_SHORT;
				$test_data['TO_NAME_SHORT'] = $value->TO_NAME_SHORT;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				$test_data['CONTAINER_SIZE_ID'] = $value->CONTAINER_SIZE_ID;
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['SELLING_CURRENCY'] = $value->SELLING_CURRENCY;
				$test_data['SELLING_OFFERING_RATE'] = $value->SELLING_OFFERING_RATE;
				$test_data['AGREEMENT_NUMBER'] = $value->AGREEMENT_NUMBER;
				$test_data['QUOTATION_NUMBER'] = $value->QUOTATION_NUMBER;
				$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;

				$result_trucking[] = $test_data;
			}
		}
		// pr($data['data_commodity']);
		// $data['']

		// $this->form_validation->set_rules('hoarding_name', 'Unit Terminal Location', 'required');
		$this->form_validation->set_rules('hoarding_id', 'Unit Terminal Location', 'required');
		$this->form_validation->set_rules('register_number', 'Register Number', 'required');
		$this->form_validation->set_rules('register_date', 'Register Date', 'required');
		$this->form_validation->set_rules('importir_name', 'Importir Name', 'required');
		$this->form_validation->set_rules('importir_id', 'Importir ID', 'required');
		$this->form_validation->set_rules('ppjk_id', 'PPJK', 'required');

		// customs_location
		// customs_lane
		// commodity_id
		// gross_weight
		// gross_weight_measurement
		// net_weight
		// net_weight_measurement

		$truckss = $this->input->post('trucking');
		// pr($truckss);
		if (!empty($truckss)) {
			// echo "tidak kosong";
			// // return true;
			foreach ($truckss as $key => $value) {
				$this->form_validation->set_rules('trucking['.$key.'][customs_location]', 'Customs Location '.$key, 'required');
				$this->form_validation->set_rules('trucking['.$key.'][customs_lane]', 'Customs Lane '.$key, 'required');
				$this->form_validation->set_rules('trucking['.$key.'][commodity_id]', 'Commodity '.$key, 'required');
				$this->form_validation->set_rules('trucking['.$key.'][gross_weight]', 'Gross Weight '.$key, 'required');
				$this->form_validation->set_rules('trucking['.$key.'][gross_weight_measurement]', 'Gross Weight Measurement '.$key, 'required');
				$this->form_validation->set_rules('trucking['.$key.'][net_weight]', 'Net Weight '.$key, 'required');
				$this->form_validation->set_rules('trucking['.$key.'][net_weight_measurement]', 'Net Weight Measurement '.$key, 'required');
			}
		}

		if (isset($_POST)) {
			
			// hold error messages in div
	        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

	        $trucking = $this->input->post('trucking');

	        if (isset($_POST['trucking'])) {
	        	// pr($trucking);
	        	$check_gross_net = "";
	        	$check_gross_net_mea = "";
	        	// check gross and net weight
		        foreach ($trucking as $value) {
		        	if ($value['gross_weight'] < $value['net_weight']) {
		        		$check_gross_net = "error";
		        	}
		        }

		        foreach ($trucking as $value) {
		        	if ($value['gross_weight_measurement'] != $value['net_weight_measurement']) {
		        		$check_gross_net_mea = "error";
		        	}
		        }
	        }

			if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entrycustoms', $data);
			} elseif ($check_gross_net == "error") {
				$data['error_var_gro'] = "error";
				$data['error_var_gro_msg'] = "Gross Weight less than Net Weight!";
				$this->load->view('orders/v_entrycustoms', $data);
			} elseif ($check_gross_net_mea == "error") {
				$data['error_var_gro_mea'] = "error";
				$data['error_var_gro_mea_msg'] = "Gross Weight Measurement different with Net Weight Measurement!";
				$this->load->view('orders/v_entrycustoms', $data);
			} else {
				
				$wo_number = $this->input->post('work_order_number');
				$company_id = $this->input->post('company_id');
				$hoarding_id = $this->input->post('hoarding_id');
				$register_number = $this->input->post('register_number');
				$register_date = $this->input->post('register_date');
				$importir_name = $this->input->post('importir_name');
				$importir_id = $this->input->post('importir_id');
				$ppjk_id = $this->input->post('ppjk_id');

				$this->db->trans_begin();
				try {

					$check_customs_exists = $this->M_order->check_customs_exists($work_order_number)->num_rows();

					if ($check_customs_exists < 1) {
						$delete_wo_service = $this->M_order->delete_wo_service($work_order_number, 'SS02');
						if ($delete_wo_service == FALSE) {
							throw new Exception("Error Processing Request (209)", 1);
							
						}
						// // insert data work order service
						// $data_wo_service = array(
						// 	'work_order_number' => $work_order_number,
						// 	'company_id' => $code_cmpy,
						// 	'service_id' => 'SS02',
						// 	'user_id' => $this->nik,
						// 	'user_date' => $date
						// );

						// if (!$this->db->insert('dbo.TRWORKORDER_SERVICE', $data_wo_service)) {
						// 	throw new Exception("Error Processing Request Insert Data Work Order Service Customs Clearance", 1);
							
						// }

						// insert wo customs
						$data_customs = array(
							'work_order_number' => $wo_number,
							'company_id' => $company_id,
							'register_number_pib_peb' => $register_number,
							'register_date_pib_peb' => $register_date,
							'hoarding_id' => $hoarding_id,
							'importir_id' => $importir_id,
							'ppjk_id' => $ppjk_id
						);
						$check_customs = $this->M_order->check_customs($work_order_number)->num_rows();
						// $this->load->helper('comman_helper');
						// pr($check_customs);
						if ($check_customs > 0) {
							$update_customs_wo = $this->M_order->update_customs_wo('dbo.TRWORKORDER_CUSTOMS', $data_customs, $work_order_number);
							if ($update_customs_wo == FALSE) {
								throw new Exception("Failed updated PIB/PEB data, something wrong!", 1);
							}
						} else {
							if (!$this->db->insert('dbo.TRWORKORDER_CUSTOMS', $data_customs)) {
								throw new Exception("Failed inserted PIB/PEB data, something wrong!", 1);
							}
						}

						$delete_cost_customs = $this->M_order->delete_cost_customs($wo_number);
						if ($delete_cost_customs == FALSE) {
							throw new Exception("Failed updated PIB/PEB data, something wrong (Error 201)!", 1);
						}

						$delete_cash_request = $this->M_order->delete_cash_request($work_order_number, 'customs');
						if ($delete_cash_request == FALSE) {
							throw new Exception("Failed inserted cash request customs, error(203)!", 1);
						}

						// update container customs
						foreach ($trucking as $value) {
							// get selling offer customs
							$selling_temp = $this->M_order->get_selling_customs_wo($quotation_no, $value['customs_location'], $value['customs_lane'], $value['container_size_id'], $value['container_type_id'], $value['container_category_id']);
							if ($selling_temp->num_rows() < 1) {
								$selling_customs = 0;
							} else {
								$selling_customs = $selling_temp->row()->SELLING_OFFERING_RATE;
							}
							$data_trucking = array(
								'fcl_lcl' => 'FCL',
								'commodity_id' => $value['commodity_id'],
								'gross_weight' => $value['gross_weight'],
								'gross_weight_measurement' => $value['gross_weight_measurement'],
								'net_weight' => $value['net_weight'],
								'net_weight_measurement' => $value['net_weight_measurement'],
								'customs_lane' => $value['customs_lane'],
								'customs_location' => $value['customs_location'],
								'customs_lane_amount' => $selling_customs
							);

							// insert into cost customs
							$cost_customs = $this->M_order->get_cost_customs($value['customs_location'], $value['customs_lane'], $value['container_size_id'], $value['container_type_id'], $value['container_category_id'])->result();

							foreach ($cost_customs as $key1 => $value1) {
								$data_cost_customs = array(
									'work_order_number' => $wo_number,
									'company_id' => $code_cmpy,
									'container_number' => $value['container_number'],
									'cost_id' => $value1->COST_ID,
									'custom_location_id' => $value1->CUSTOM_LOCATION_ID,
									'custom_line_id' => $value1->CUSTOM_LINE_ID,
									'custom_kind_id' => $value1->CUSTOM_KIND_ID,
									'container_size_id' => $value1->CONTAINER_SIZE_ID,
									'container_type_id' => $value1->CONTAINER_TYPE_ID,
									'container_category_id' => $value1->CONTAINER_CATEGORY_ID,
									'start_date' => $value1->START_DATE,
									'end_date' => $value1->END_DATE,
									'from_qty' => $value1->FROM_QTY,
									'to_qty' => $value1->TO_QTY,
									'increment_qty' => $value1->INCREMENT_QTY,
									'calc_type' => $value1->CALC_TYPE,
									'cost_type_id' => $value1->COST_TYPE_ID,
									'cost_group_id' => $value1->COST_GROUP_ID,
									'cost_currency' => $value1->COST_CURRENCY,
									'cost_amount' => $value1->COST_AMOUNT,
									'user_id' => $this->nik,
									'user_date' => $date
								);

								// insert to table cost wo customs
								if (!$this->db->insert('dbo.TRWORKORDER_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_cost_customs)) {
									throw new Exception("Failed inserted cost container customs!", 1);
								}

								$data_cash = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $value1->COST_ID,
									'container_number' => $value['container_number'],
									'cost_currency' => $value1->COST_CURRENCY,
									'cost_type_id' => $value1->COST_TYPE_ID,
									'cost_group_id' => $value1->COST_GROUP_ID,
									'cost_request_amount' => $value1->COST_AMOUNT,
									'request_date' => $date,
									'user_id_request' => $this->nik,
									'user_id' => $this->nik,
									'user_date' => $date,
									'status' => 'customs',
									'cost_kind' => 'S',
									'sequence_id' => '0',
									'is_transfered' => 'N',
									'user_id' => $this->nik,
									'user_date' => $date
								);

								// insert into table trcash_request
								if (!$this->db->insert('dbo.TRCASH_REQUEST', $data_cash)) {
									throw new Exception("Failed inserted cash request customs, error(204)!", 1);
								}
							}

							$update_customs = $this->M_order->update_customs('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking, $value['container_number'], $wo_number);
							if ($update_customs == FALSE) {
								throw new Exception("Failed updated PIB/PEB data, updating container error!", 1);
							}
							unset($data_trucking);
						}
					} else {
						// all process should just updating data

						// update data customs wo
						$data_customs = array(
							'work_order_number' => $wo_number,
							'company_id' => $company_id,
							'register_number_pib_peb' => $register_number,
							'register_date_pib_peb' => $register_date,
							'hoarding_id' => $hoarding_id,
							'importir_id' => $importir_id,
							'ppjk_id' => $ppjk_id
						);
						$update_customs_wo = $this->M_order->update_customs_wo('dbo.TRWORKORDER_CUSTOMS', $data_customs, $work_order_number);
							
						if ($update_customs_wo == FALSE) {
							throw new Exception("Failed updated PIB/PEB data, something wrong!", 1);
						}

						// // delete data other customs
						// $delete_other_customs = $this->M_order->delete_other_customs($work_order_number);
						// if ($delete_other_customs == FALSE) {
						// 	throw new Exception("Error Processing Request to Delete Quarantine or BPOM", 1);
						// }

						// // insert other customs
						// foreach ($other_customs as $value) {
						// 	$data_other_customs = array(
						// 		'work_order_number' => $wo_number,
						// 		'selling_other_customs_id' => $value
						// 	);

						// 	if (!$this->db->insert('dbo.TRWORKORDER_OTHER_CUSTOMS', $data_other_customs)) {
						// 		throw new Exception("Error Processing Request to Entry Quarantine or BPOM", 1);
						// 	}
						// }

						// update container customs
						foreach ($trucking as $value) {
							
							$data_trucking = array(
								'fcl_lcl' => 'FCL',
								'commodity_id' => $value['commodity_id'],
								'gross_weight' => $value['gross_weight'],
								'gross_weight_measurement' => $value['gross_weight_measurement'],
								'net_weight' => $value['net_weight'],
								'net_weight_measurement' => $value['net_weight_measurement']
							);

							$update_customs = $this->M_order->update_customs('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_trucking, $value['container_number'], $wo_number);
							if ($update_customs == FALSE) {
								throw new Exception("Failed updated PIB/PEB data, updating container error!", 1);
							}
							unset($data_trucking);
						}
					}

					// $this->db->trans_complete();

					if ($this->db->trans_status() === FALSE)
					{
						throw new Exception("Failed updated/inserted PIB/PEB data, something wrong!", 1);
				        // // echo "gagal";
				        // redirect(current_url());
					}
					else
					{
				        // $this->db->trans_commit();
				        $this->session->set_flashdata('success', "Successfully insert data customs clearance!");
				        $this->db->trans_commit();
				        redirect('Order/index');
				        // echo "berhasil";
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	public function search_hoarding()
	{
		$kode = $this->input->get('term');
		$hoarding = $this->M_order->get_hoarding($kode)->result();

		foreach ($hoarding as $key => $value) {
			$temp_hoarding['value'] =  $value->HOARDING_NAME;
			$temp_hoarding['hoarding_id'] = $value->HOARDING_ID;
			$result_hoarding[] = $temp_hoarding;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_hoarding);
	}

	function all_customs()
	{
		$data['data_wo'] = $this->M_order->get_all_wo_customs()->result();

		$this->load->view('orders/v_allcustoms', $data);
	}

	public function search_company()
	{
		$kode = $this->input->get('term');
		$company = $this->M_order->get_company_crm($kode)->result();

		foreach ($company as $key => $value) {
			$temp_company['value'] =  $value->NAME;
			$temp_company['company_id'] = $value->COMPANY_ID;
			$result_company[] = $temp_company;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_company);
	}

	function cash_request()
	{
		$data['data_wo'] = $this->M_order->get_wo_cash()->result();

		$this->load->view('orders/v_cashrequest', $data);
	}

	function entry_cash_request()
	{
		$date = date('Y-m-d H:i:s');
		$work_order_number = $this->uri->segment(3);

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		$data_trucking = $this->M_order->get_cost_trucking_cash($work_order_number)->result();
		$data_customs = $this->M_order->get_cost_customs_cash($work_order_number)->result();

		// $data_mix = array();

		// mix data cost trucking and cost
		foreach ($data_trucking as $key => $value) {
			$temp_data['COST_ID'] = $value->COST_ID;
			$temp_data['CURRENCY'] = $value->COST_CURRENCY;
			$temp_data['COST_NAME'] = $value->COST_NAME;
			$temp_data['COST_AMOUNT'] = $value->COST_AMOUNT;
			$temp_data['COST_TYPE_ID'] = $value->COST_TYPE_ID;
			$temp_data['COST_GROUP_ID'] = $value->COST_GROUP_ID;

			$data_mix[] = $temp_data;
		}

		// mix data cost trucking and cost
		foreach ($data_customs as $key => $value) {
			$temp_data['COST_ID'] = $value->COST_ID;
			$temp_data['CURRENCY'] = $value->COST_CURRENCY;
			$temp_data['COST_NAME'] = $value->COST_NAME;
			$temp_data['COST_AMOUNT'] = $value->COST_AMOUNT;
			$temp_data['COST_TYPE_ID'] = $value->COST_TYPE_ID;
			$temp_data['COST_GROUP_ID'] = $value->COST_GROUP_ID;
			$temp_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;

			$data_mix[] = $temp_data;
		}

		$data['data_mix'] = $data_mix;
		$data['container_number'] = $this->M_order->get_container_number($work_order_number)->result();

		$nik = $this->M_order->get_name_nik($this->nik);
		$data['nik'] = $this->nik;
		$data['name'] = $nik->row()->Nm_lengkap;

		$data['work_order_date'] = $this->M_order->get_data_wo2($work_order_number)->row()->WORK_ORDER_DATE;
		$data['vessel_name'] = $this->M_order->get_data_wo2($work_order_number)->row()->VESSEL_NAME;
		$data['trade_name'] = $this->M_order->get_data_wo2($work_order_number)->row()->TRADE_NAME;
		$data['voyage_number'] = $this->M_order->get_data_wo2($work_order_number)->row()->VOYAGE_NUMBER;
		$data['customer_name'] = $this->M_order->get_data_wo2($work_order_number)->row()->CUSTOMER_NAME;

		$data['data_cash_request'] = $this->M_order->get_data_cash_request($work_order_number)->result();
		$data['data_additional'] = $this->M_order->get_data_additional($work_order_number)->result();
		// $this->load->helper('comman_helper');
		// pr($data['data_additional']);
		$data['data_add_param'] = $this->M_order->get_add_param($work_order_number)->result();
		$data['all_wo'] = $this->M_order->get_container_number($work_order_number)->result();
		$data['data_cost'] = $this->M_order->get_data_cost()->result();
		$data['data_cash_do'] = $this->M_order->get_cash_do2($work_order_number, $code_cmpy)->result();
		$data['data_selling_additional'] = $this->M_order->get_data_additional_selling($work_order_number, $code_cmpy)->result();
		$this->load->view('orders/v_entrycashrequest', $data);

		// $this->form_validation->set_rules('cost[][]', 'Container Number', 'required');
		// hold error messages in div
        // $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
	}

	function insert_cash_request()
	{
		$work_order_number = $this->uri->segment(3);
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');
		if (isset($_POST)) {
			// declare variable
			$cost = $this->input->post('cost');

			// if ($this->form_validation->run() == false) {
			// 	$this->load->view('orders/v_entrycashrequest', $data);
			// } else {

			// }
			// $data_add_arr = $this->M_order->get_temp_add($work_order_number)->result_array();
			// $result = array_diff_assoc($data_add_arr, $cost);

			// $this->load->helper('comman_helper');
			// pr(count($cost));
			$this->db->trans_begin();
			try {
				// // delete all additional data of work order number
				// $delete_additional = $this->M_order->delete_additional($work_order_number);
				// if ($delete_additional == FALSE) {
				// 	throw new Exception("Error Processing Request (201)", 1);
					
				// }
				// // insert additional cost into cash request
				// foreach ($cost as $value) {
				// 	$data_add[] = array(
				// 		'work_order_number' => $value['work_order_number'],
				// 		'cost_id' => $value['cost_id'],
				// 		'container_number' => $value['container_number'],
				// 		'cost_kind' => $value['cost_kind'],
				// 		'cost_currency' => $value['cost_currency'],
				// 		'cost_type_id' => $value['cost_type_id'],
				// 		'cost_group_id' => $value['cost_group_id'],
				// 		'cost_request_amount' => $value['cost_request_amount'],
				// 		'request_date' => $value['request_date'],
				// 		'user_id_request' => $value['user_id_request'],
				// 		'user_id' => $this->nik,
				// 		'user_date' => $date
				// 	);
				// }
				// if (!$this->db->insert_batch('dbo.TRCASH_REQUEST', $data_add)) {
				// 	throw new Exception("Error Processing Request Update/Delete Additional Cost Data", 1);
					
				// }

				// change status is deleted in trcash request additional
				// change all status is deleted to N
				if (count($cost) > 0) {
					$temp_change = array(
						'is_deleted' => 'Y',
						'need_approved' => 'N'
					);
					$change_delete_additional = $this->M_order->change_delete_additional($work_order_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $temp_change);
					if ($change_delete_additional == FALSE) {
						throw new Exception("Error Processing Request (202)", 1);
						
					}

					foreach ($cost as $value) {
						$change_delete = array(
							'is_deleted' => 'N',
							'need_approved' => $value['need_approve']
						);
						$update_deleted = $this->M_order->update_deleted($value['additional_number'], $value['work_order_number'], $value['container_number'], $value['cost_id'], $value['cost_request_amount'], 'dbo.TRCASH_REQUEST_ADDITIONAL', $change_delete);
						if ($update_deleted == FALSE) {
							throw new Exception("Error Processing Request Updated/Deleted Additional Cost Data (Change status if deleted)", 1);
							
						}
						// unset($update_deleted);

						// check if data need approval
						if ($value['need_approve'] == "Y") {
							$check_approval = $this->M_order->check_approval($value['additional_number']);
							if ($check_approval->num_rows() < 1) {
								$data_approval = array(
									'transaction_number' => $value['additional_number'],
									'document_id' => 'D1007',
									'revision_number' => '0',
									'company_id' => $code_cmpy,
									'request_approval_date' => $date,
									'approval_status' => 'N'
								);
								if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_approval)) {
									throw new Exception("Error Processing Request to Entry Approval Additional Cost", 1);
								}
							}
						}
						// unset($data_approval);
					}
				} else {
					$temp_change = array(
						'is_deleted' => 'Y',
						'need_approved' => 'N'
					);
					$change_delete_additional2 = $this->M_order->change_delete_additional2($work_order_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $temp_change);
					if ($change_delete_additional2 == FALSE) {
						throw new Exception("Error Processing Request Deleted All Additional Cost!", 1);
						
					}
				}

				// $this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request Deleted or Updated Additional Cost Data!", 1);
					
				} else {
					$this->session->set_flashdata('success_additional', "Successfully edited/deleted additional cost for this work order!");
					$this->db->trans_commit();
					redirect('Order/entry_cash_request/'.$work_order_number);
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed', $e->getMessage());
				$this->db->trans_rollback();
				redirect('Order/entry_cash_request/'.$work_order_number);
			}
		}
	}

	function edit_cash_do()
	{
		$work_order_number = $this->uri->segment(3);
		$this->db->trans_begin();
		try {
			$do = $this->input->post('do');

			foreach ($do as $value) {
				$amount = $value['amount'];
				$amount_fix = str_replace(',', '', $amount);
				$data_cash_do = array(
					'cost_request_amount' => $amount_fix,
					'cost_received_amount' => $amount_fix
				);

				$update_cash_do = $this->M_order->update_cash_do('dbo.TRCASH_REQUEST', $data_cash_do, $work_order_number, $value['container_number'], $value['cost_id'], $value['company_id'], $value['cost_type_id'], $value['cost_group_id'], $value['cost_kind']);
				if ($update_cash_do == FALSE) {
					throw new Exception("Error Processing Request to Update Cash DO", 1);
				}
			}

			if ($this->db->trans_status() === FALSE) {
				throw new Exception("Error Processing Request", 1);
			} else {
				$this->session->set_flashdata('success_cash_do', "Successfully updated Cash Request DO!");
				$this->db->trans_commit();
				redirect('Order/entry_cash_request/'.$work_order_number);
			}
		} catch (Exception $e) {
			$this->session->set_flashdata('failed_cash_do', $e->getMessage());
			$this->db->trans_rollback();
			redirect('Order/entry_cash_request/'.$work_order_number);
		}
	}

	function entry_cash_received()
	{
		$this->load->helper('comman_helper');
		$date = date('Y-m-d H:i:s');
		$work_order_number = $this->uri->segment(3);

		$data['data_cash_request'] = $this->M_order->get_data_cash_request2($work_order_number)->result();
		$data['data_nik'] = $this->M_order->get_nik_full()->result();

		// pr($data['data_nik']);

		$this->form_validation->set_rules('cash[][]', 'Cost Amount', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if (isset($_POST)) {
			// declare variable
			// $cash_received_date = $this->input->post('cash_received_date');
			// $pic_name = $this->input->post('pic_name');
			// $pic_id = $this->input->post('pic_id');
			// $cost_id = $this->input->post('cost_id');
			// $container_number = $this->input->post('container_number');
			// $cost_amount = $this->input->post('cost_amount');
			$cash = $this->input->post('cash');

			// $this->load->helper('comman_helper');
			// pr($pic_name);

			if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entrycashreceived', $data);
			} else {
				// pr($cash);
				$this->db->trans_begin();
				try {
					// UPDATE DATA WO
					$data_work_order = array(
						'cash_received_date' => $date
					);
					$update_wo_cash = $this->M_order->update_wo_cash($work_order_number, 'dbo.TRWORKORDER', $data_work_order);
					if ($update_wo_cash == FALSE) {
						throw new Exception("Error Processing Request update cash process (Error 201)", 1);
						
					}

					// for ($i=0; $i <= count($cash_received_date); $i++) { 
					// 	$data_cash = array(
					// 		'cost_received_amount' => $cost_amount[$i],
					// 		'transfer_date_actual' => $cash_received_date[$i],
					// 		'user_id_received' => $pic_id[$i]
					// 	);
					// 	$update_cash = $this->M_order->update_cash($work_order_number, $container_number[$i], $cost_id[$i], 'dbo.TRCASH_REQUEST', $data_cash);
					// 	if ($update_cash == FALSE) {
					// 		throw new Exception("Error Processing Request update cash process (Error 202)", 1);
							
					// 	}
					// 	unset($data_cash);
					// }

					foreach ($cash as $value) {
						$data_cash = array(
							'cost_received_amount' => $value['cost_amount'],
							'transfer_date_actual' => $value['cash_received_date'],
							'user_id_received' => $value['pic_id'],
						);
						$update_cash = $this->M_order->update_cash($work_order_number, $value['container_number'], $value['cost_id'], $value['sequence_id'], 'dbo.TRCASH_REQUEST', $data_cash);
						if ($update_cash == FALSE) {
							throw new Exception("Error Processing Request update cash process (Error 202)", 1);
							
						}
						unset($data_cash);
					}


					// $this->db->trans_complete();

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request Cash Process", 1);
						
					} else {
						$this->session->set_flashdata('success', "Successfully entry cash process!");
						$this->db->trans_commit();
						redirect('Order/index');
					}

					
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
				
			}
		}
	}

	public function search_nik()
	{
		$kode = $this->input->get('term');
		$nik = $this->M_order->get_nik($kode)->result();

		foreach ($nik as $key => $value) {
			$name_fix = rtrim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $value->Nm_lengkap));
			$temp_nik['value'] =  $name_fix;
			$temp_nik['pic_id'] = $value->Nik;
			$result_nik[] = $temp_nik;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_nik);
	}

	function entry_sppb()
	{
		$work_order_number = $this->uri->segment(3);
		$date = date('Y-m-d');
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$temp_data = $this->M_order->get_work_detail($work_order_number);

		$data['customer_name'] = $temp_data->row()->CUSTOMER_NAME;
		$data['vessel_name'] = $temp_data->row()->VESSEL_NAME;
		$data['voyage_number'] = $temp_data->row()->VOYAGE_NUMBER;
		$data['wo_date'] = $temp_data->row()->WORK_ORDER_DATE;
		$data['trade_name'] = $temp_data->row()->TRADE_NAME;
		$data['pol_name'] = $temp_data->row()->POL_NAME;
		$data['pod_name'] = $temp_data->row()->POD_NAME;
		$data['sppb_number'] = $this->M_order->get_data_customs($work_order_number)->row()->REGISTER_NUMBER_SPPB_SPEB;
		$data['sppb_date'] = $this->M_order->get_data_customs($work_order_number)->row()->REGISTER_SPPB_DATE;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		$this->form_validation->set_rules('sppb_number', 'SPPB Number', 'required');
		$this->form_validation->set_rules('sppb_date', 'SPPB Date', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $data['work_order_number'] = $work_order_number;

        if (isset($_POST)) {
        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entrysppb', $data);
			} else {
				// declare variable
				$sppb_number = $this->input->post('sppb_number');
				$sppb_date = $this->input->post('sppb_date');

				$this->db->trans_start();
				try {
					$data_sppb = array(
						'work_order_number' => $work_order_number,
						'company_id' => $code_cmpy,
						'REGISTER_NUMBER_SPPB_SPEB' => $sppb_number,
						'REGISTER_DATE_SPPB_SPEB' => $sppb_date
					);

					$check_customs = $this->M_order->check_customs($work_order_number)->num_rows();

					// $this->load->helper('comman_helper');
					// pr($data_sppb);

					if ($check_customs > 0) {
						$this->db->where('WORK_ORDER_NUMBER', $work_order_number);
						if (!$this->db->update('dbo.TRWORKORDER_CUSTOMS', $data_sppb)) {
							throw new Exception("Failed Update SPPB/NPE Number", 1);
							
						}
					} else {
						if (!$this->db->insert('dbo.TRWORKORDER_CUSTOMS', $data_sppb)) {
							throw new Exception("Failed Entry SPPB", 1);
							
						}
					}
					$this->db->trans_complete();

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully insert SPPB Number!");
						// $this->db->trans_commit();
						redirect('Order/index');
					}
					$this->db->trans_complete();
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
        }
	}

	function transfer_cost()
	{
		$this->load->view('orders/v_transfercash');
	}

	function transfer_do()
	{
		$data['data_wo'] = $this->M_order->get_wo_transfer()->result();
		$this->load->view('orders/v_transferdo', $data);
	}

	function search_commodity()
	{
		$kode = $this->input->get('term');
		$commodity = $this->M_order->get_commodity($kode)->result();

		foreach ($commodity as $key => $value) {
			$temp_commodity['value'] =  $value->COMMODITY_DESCRIPTION;
			$temp_commodity['commodity_id'] = $value->COMMODITY_ID;
			$result_commodity[] = $temp_commodity;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_commodity);
	}

	function view_trucking()
	{
		$data['data_trucking'] = $this->M_order->get_view_trucking()->result();
		$this->load->view('orders/v_viewtrucking', $data);
	}

	function entry_trucking_detail()
	{
		// $this->load->helper('comman_helper');
		$this->load->helper('comman_helper');
		$date = date('Y-m-d H:i:s');
		$work_order_number = $this->uri->segment(3);
		$agreement_number = $this->M_order->get_agreement_number($work_order_number)->row()->AGREEMENT_NUMBER;
		$quotation_number = $this->M_order->get_quotation_number($agreement_number)->row()->QUOTATION_NUMBER;
		// pr($quotation_number);
		$container_number = $this->uri->segment(4);

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		$data['work_order_number'] = $work_order_number;
		$data['container_number'] = $container_number;
		$data['customer_name'] = $this->M_order->get_name_customer($work_order_number)->row()->name;
		$data['data_driver'] = $this->M_order->get_driver2()->result();
		$temp_wo = $this->M_order->get_just_wo_data($work_order_number);
		$temp_address = $this->M_order->get_address($temp_wo->row()->CUSTOMER_ID)->result();
		$address = array();
		foreach ($temp_address as $key => $value) {
			if ($value->ADDRESS_1 != "" || $value->ADDRESS_1 != NULL) {
				array_push($address, $value->ADDRESS_1);
			}
			if ($value->ADDRESS_2 != "" || $value->ADDRESS_1 != NULL) {
				array_push($address, $value->ADDRESS_2);
			}
		}
		$filter_address = array_filter($address);
		// pr($filter_address);
		$data['data_address'] = $filter_address;

		$check_container = $this->M_order->check_container_trucking($container_number, $work_order_number);
		$data['check_container'] = $check_container->num_rows();

		// $this->load->helper('comman_helper');
		// pr($check_container);

		if ($check_container->num_rows() > 0) {
			$data['do_number'] = $check_container->row()->DELIVERY_ORDER_NUMBER;
			$data['do_date'] = $check_container->row()->DOCUMENT_DATE;
			$data['data_container'] = $this->M_order->get_container_param($work_order_number, $check_container->row()->DELIVERY_ORDER_NUMBER)->result();
		} else {
			$temp_id = $this->M_order->get_max_do()->row()->id;
			$temps = $temp_id + 1;

			if ($temp_id != NULL) {
				if ($temps < 10) {
					$temp_subs = substr($temp_id, 6, 1);
					$temp_subs++;
					$id = "000000" . $temps;
				} elseif ($temps < 100) {
					$temp_subs = substr($temp_id, 5, 2);
					$temp_subs++;
					$id = "00000" . $temps;
				} elseif ($temps < 1000) {
					$temp_subs = substr($temp_id, 4, 3);
					$temp_subs++;
					$id = "0000" . $temps;
				} elseif ($temps < 10000) {
					$temp_subs = substr($temp_id, 3, 4);
					$temp_subs++;
					$id = "000" . $temps;
				} elseif ($temps < 100000) {
					$temp_subs = substr($temp_id, 2, 5);
					$temp_subs++;
					$id = "00" . $temps;
				} elseif ($temps < 1000000) {
					$temp_subs = substr($temp_id, 1, 6);
					$temp_subs++;
					$id = "0" . $temps;
				} else {
					$id = $temp_id+1;
				}
			} else {
				// echo "kosong";
				$id = "0000001";
			}
			// $this->load->helper('comman_helper');
			// pr($id);
			$data['do_number'] = $id;
			$data['do_date'] = date('Y-m-d');
			// $data['data_container'] = $this->M_order->get_container($work_order_number, $container_number)->result();
			$data['data_container'] = $this->M_order->get_container2($work_order_number, $container_number)->result();
		}

		$data['data_own'] = $this->M_order->get_own_truck()->result();

		$this->form_validation->set_rules('do_number', 'Delivery Order Number', 'required');
		$this->form_validation->set_rules('document_date', 'Delivery Order Date', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	$do_number = $this->input->post('do_number');
			$do_date = $this->input->post('document_date');
			$container = $this->input->post('container');

			$check_sppb = "";
			$check_sppb_db = $this->M_order->check_sppb($work_order_number);
			if ($check_sppb_db->row()->REGISTER_NUMBER_SPPB_SPEB == NULL || $check_sppb_db->row()->REGISTER_NUMBER_SPPB_SPEB == "") {
				$check_sppb = "error";
			} else {
				$check_sppb = "no";
			}

			// declare for truck
			$stnk_status = "";
			// $kir_status = "";
			$date_now = date('Y-m-d');
			$stnk_data = "";
			$kir_data = "";

			// $this->load->helper('comman_helper');
			// pr(count($container));

        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entrytrucking', $data);
			} elseif($check_sppb == "error") {
				$data['sppb_error'] = "error";
				$this->load->view('orders/v_entrytrucking', $data);
			} elseif(count($container) > 1 || count($container) < 1) {
				$data['trucking_error'] = "error";
				$this->load->view('orders/v_entrytrucking', $data);
			} elseif($stnk_status == "error") {
				$data['stnk_error'] = "error";
				$data['stnk_message'] = "STNK or KIR are Expired!";
				$this->load->view('orders/v_entrytrucking', $data);
			} else {
				// pr($container);
				// check expired KIR and others of trucks
				$this->db->trans_begin();
				try {
					$check_container = $this->M_order->check_container_trucking($container_number, $work_order_number);

					if ($check_container->num_rows() > 0) {
						// entry to table trucking
						foreach ($container as $value) {
							$data_container = array(
								'container_number' => $value['container_number'],
								'document_date' => $do_date,
								'estimation_arrived' => $value['est_date'],
								'truck_id_number' => $value['truck_number'],
								'truck_owner_id' => $value['own_truck'],
								'chasis_id_number' => $value['chasis_number'],
								'driver_id' => $value['driver_id'],
								'final_location_detail' => $value['detail_to'],
								'remarks' => $value['remarks'],
								'user_id' => $this->nik,
								'user_date' => $date
							);

							// // get selling offering rate
							// // variable
							// $from_location = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->FROM_LOCATION_ID;
							// $to_location = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->TO_LOCATION_ID;
							// $container_size = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->CONTAINER_SIZE_ID;
							// $container_type = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->CONTAINER_TYPE_ID;
							// $container_category = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->CONTAINER_CATEGORY_ID;
							// $offering_rate = $this->M_order->get_data_selling2($quotation_number, $from_location, $to_location, $container_size, $container_type, $container_category)->row()->SELLING_OFFERING_RATE;

							// // update data
							// $data_update_container = array(
							// 	'tariff_amount' => $offering_rate
							// );

							// $update_container_amount = $this->M_order->update_container_amount('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_update_container, $work_order_number, $value['container_number']);
							// if ($update_container_amount == FALSE) {
							// 	throw new Exception("Error Processing Request to Update Selling Trucking of Container", 1);
							// }
							
							// change flag truck
							$flag_truck = array(
								'flag' => '1'
							);
							$change_flag_truck = $this->M_order->change_flag_truck($value['truck_number'], 'dbo.MTRUCK', $flag_truck);
							if ($change_flag_truck == FALSE) {
								throw new Exception("Error Processing Request to Change Flag Truck", 1);
							}

							// change flag chasis
							$flag_chasis = array(
								'flag' => '1'
							);
							$change_flag_chasis = $this->M_order->change_flag_chasis($value['chasis_number'], 'dbo.MCHASSIS', $flag_chasis);
							if ($change_flag_chasis == FALSE) {
								throw new Exception("Error Processing Request to Change Flag Chassis", 1);
								
							}

							// change flag driver
							$flag_driver = array(
								'flag' => '1'
							);
							$change_flag_driver = $this->M_order->change_flag_driver($value['driver_id'], 'dbo.MDRIVER', $flag_driver);
							if ($change_flag_driver == FALSE) {
								throw new Exception("Error Processing Request to Change Flag Driver", 1);
								
							}				

							$update_flags = array(
								'flag' => '1'
							);

							$change_flagsss = $this->M_order->change_flag($work_order_number, $value['container_number'], 'dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $update_flags);
							if ($change_flagsss == FALSE) {
								throw new Exception("Error Processing Request (203)", 1);
							}

							// update trucking detail data
							$update_wo_trucking = $this->M_order->update_wo_trucking('dbo.TRWORKORDER_TRUCKING', $data_container, $work_order_number, $container_number);
							if ($update_wo_trucking == FALSE) {
								throw new Exception("Error Processing Request to Update Data Trucking", 1);
							}
						}
					} else {
						$stnk_expired = "";
						$stnk_availables = "";
						foreach ($container as $value) {
							$stnk_data = $this->M_order->get_date_truck($value['truck_number'])->row()->STNK;
							$kir_data = $this->M_order->get_date_truck($value['truck_number'])->row()->KIR;

							if (strtotime($stnk_data) < strtotime($date_now) || strtotime($kir_data) < strtotime($date_now)) {
								$stnk_status = "error";
								$stnk_expired = $value['truck_number'];
							}
						}

						$available_truck = "";
						// check available truck
						foreach ($container as $value) {
							$check_truck = $this->M_order->check_available_truck($value['truck_number'])->row()->FLAG;
							if ($check_truck > 0) {
								$available_truck = "error";
								$stnk_availables = $value['truck_number'];
							}
						}

						// check driver expired and available
						$driver_status = "";
						$driver_available = "";
						$driver_expired = "";
						$driver_availables = "";
						foreach ($container as $key => $value) {
							$check_expired_driver = $this->M_order->check_expired_driver($value['driver_id']);
							if (strtotime($check_expired_driver->row()->LICENSE_DRIVER_EXPIRED) < strtotime($date_now)) {
								$driver_status = "error";
								$driver_expired = $check_expired_driver->row()->DRIVER_NAME;
							}
						}

						foreach ($container as $key => $value) {
							$check_available_driver = $this->M_order->check_expired_driver($value['driver_id'])->row()->FLAG;
							$temp_driver = $this->M_order->check_expired_driver($value['driver_id']);
							// $this->load->helper('comman_helper');
							// pr($check_available_driver);
							if ($check_available_driver > 0) {
								$driver_available = "error";
								$driver_availables = $temp_driver->row()->DRIVER_NAME;
							} else {
								$driver_available = "no";
							}
						}

						// check available chasis
						$chasis_status = "";
						$chasis_available = "";
						$chasis_expired = "";
						$chasis_availables = "";
						foreach ($container as $key => $value) {
							$check_expired_chasis = $this->M_order->check_expired_chasis($value['chasis_number']);
							if (strtotime($check_expired_chasis->row()->KIR_EXPIRED) < strtotime($date_now)) {
								$chasis_status = "error";
								$chasis_expired = $value['chasis_number'];
							}
						}

						foreach ($container as $key => $value) {
							$check_available_chasis = $this->M_order->check_expired_chasis($value['chasis_number'])->row()->FLAG;
							if ($check_available_chasis > 0) {
								$chasis_available = "error";
								$chasis_availables = $value['chasis_number'];
							}
						}

						if ($stnk_status == "error") {
							$data['stnk_error'] = "error";
							$data['stnk_message'] = "Your vehicle worthiness or vehicle registration certificate with number of truck '$stnk_expired' already expired!";
							$this->session->set_flashdata('stnk_error', "Your vehicle worthiness or vehicle registration certificate with number of truck '$stnk_expired' already expired!");
						} 

						if ($available_truck == "error") {
							$data['truck_error'] = "error";
							$data['truck_message'] = "Truck '$stnk_availables' is not available!";
							$this->session->set_flashdata('truck_error', "Truck '$stnk_availables' is not available!");
						} 

						if ($driver_status == "error") {
							$data['driver_error'] = "error";
							$data['driver_message'] = "Driver license of $driver_expired already expired!";
							$this->session->set_flashdata('driver_error', "Driver license of $driver_expired already expired!");
						} 

						if ($driver_available == "error") {
							$data['driver_available_error'] = "error";
							$data['driver_available_message'] = "Driver '$driver_availables' is not available!";
							$this->session->set_flashdata('driver_available_error', "Driver '$driver_availables' is not available!");
						} 

						if ($chasis_status == "error") {
							$data['chasis_error'] = "error";
							$data['chasis_message'] = "KIR of Chassis '$chasis_expired' already expired!";
							$this->session->set_flashdata('chasis_error', "KIR of Chassis '$chasis_expired' already expired!");
						} 

						if ($chasis_available == "error") {
							$data['chasis_available_error'] = "error";
							$data['chasis_available_message'] = "Chassis '$chasis_availables' is not available!";
							$this->session->set_flashdata('chasis_available_error', "Chassis '$chasis_availables' is not available!");
						}

						$check_do = $this->M_order->check_do($do_number)->result();

						foreach ($check_do as $key => $value) {
							$change_flag = array(
								'flag' => '0'
							);

							$change_flagss = $this->M_order->change_flag($work_order_number, $value->CONTAINER_NUMBER, 'dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $change_flag);
							if ($change_flagss == FALSE) {
								throw new Exception("Error Processing Request (201)", 1);
								
							}
						}

						$delete_container_trucking = $this->M_order->delete_container_trucking($work_order_number, $do_number);
						if ($delete_container_trucking == FALSE) {
							throw new Exception("Error Processing Request (202)", 1);
							
						}

						// entry to table trucking
						foreach ($container as $value) {
							$data_container[] = array(
								'work_order_number' => $work_order_number,
								'company_id' => $code_cmpy,
								'delivery_order_number' => $do_number,
								'container_number' => $value['container_number'],
								'document_date' => $do_date,
								'estimation_arrived' => $value['est_date'],
								'truck_id_number' => $value['truck_number'],
								'truck_owner_id' => $value['own_truck'],
								'chasis_id_number' => $value['chasis_number'],
								'driver_id' => $value['driver_id'],
								'final_location_detail' => $value['detail_to'],
								'remarks' => $value['remarks'],
								'user_id' => $this->nik,
								'user_date' => $date
							);

							// get selling offering rate
							// variable
							$from_location = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->FROM_LOCATION_ID;
							$to_location = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->TO_LOCATION_ID;
							$container_size = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->CONTAINER_SIZE_ID;
							$container_type = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->CONTAINER_TYPE_ID;
							$container_category = $this->M_order->get_detail_container2($value['container_number'], $work_order_number)->row()->CONTAINER_CATEGORY_ID;
							$offering_rate = $this->M_order->get_data_selling2($quotation_number, $from_location, $to_location, $container_size, $container_type, $container_category)->row()->SELLING_OFFERING_RATE;

							// update data
							$data_update_container = array(
								'tariff_amount' => $offering_rate
							);

							$update_container_amount = $this->M_order->update_container_amount('dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $data_update_container, $work_order_number, $value['container_number']);
							if ($update_container_amount == FALSE) {
								throw new Exception("Error Processing Request to Update Selling Trucking of Container", 1);
							}
							
							// change flag truck
							$flag_truck = array(
								'flag' => '1'
							);
							$change_flag_truck = $this->M_order->change_flag_truck($value['truck_number'], 'dbo.MTRUCK', $flag_truck);
							if ($change_flag_truck == FALSE) {
								throw new Exception("Error Processing Request to Change Flag Truck", 1);
							}

							// change flag chasis
							$flag_chasis = array(
								'flag' => '1'
							);
							$change_flag_chasis = $this->M_order->change_flag_chasis($value['chasis_number'], 'dbo.MCHASSIS', $flag_chasis);
							if ($change_flag_chasis == FALSE) {
								throw new Exception("Error Processing Request to Change Flag Chassis", 1);
								
							}

							// change flag driver
							$flag_driver = array(
								'flag' => '1'
							);
							$change_flag_driver = $this->M_order->change_flag_driver($value['driver_id'], 'dbo.MDRIVER', $flag_driver);
							if ($change_flag_driver == FALSE) {
								throw new Exception("Error Processing Request to Change Flag Driver", 1);
								
							}				

							$update_flags = array(
								'flag' => '1'
							);

							$change_flagsss = $this->M_order->change_flag($work_order_number, $value['container_number'], 'dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE', $update_flags);
							if ($change_flagsss == FALSE) {
								throw new Exception("Error Processing Request (203)", 1);
							}

							// unset($update_flag);

							// declare varible for cost
							$get_param_container = $this->M_order->get_detail_container_param($value['container_number'], $work_order_number);
							$container_size = $get_param_container->row()->CONTAINER_SIZE_ID;
							$container_type = $get_param_container->row()->CONTAINER_TYPE_ID;
							$container_category = $get_param_container->row()->CONTAINER_CATEGORY_ID;
							$from_location = $get_param_container->row()->FROM_LOCATION_ID;
							$to_location = $get_param_container->row()->TO_LOCATION_ID;

							// cost
							// get data cost from quotation (agreement)
							$get_cost = $this->M_order->get_cost($container_size, $container_type, $container_category, $from_location, $to_location)->result();
							if (empty($get_cost)) {
								throw new Exception("Error Processing Request to Get Cost Data", 1);
								
							}

							foreach ($get_cost as $key1 => $value1) {
								$cost_trucking[] = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'container_number' => $value['container_number'],
									'cost_id' => $value1->COST_ID,
									'container_size_id' => $container_size,
									'container_type_id' => $container_type,
									'container_category_id' => $container_category,
									'from_location_id' => $from_location,
									'to_location_id' => $to_location,
									'from_qty' => $value1->FROM_QTY,
									'to_qty' => $value1->TO_QTY,
									'start_date' => $value1->START_DATE,
									'end_date' => $value1->END_DATE,
									'cost_type_id' => $value1->COST_TYPE_ID,
									'cost_group_id' => $value1->COST_GROUP_ID,
									'calc_type' => $value1->CALC_TYPE,
									'increment_qty' => $value1->INCREMENT_QTY,
									'cost_currency' => $value1->COST_CURRENCY,
									'cost_amount' => $value1->COST_AMOUNT,
									'user_id' => $this->nik,
									'user_date' => $date
								);

								$temp_max_add = $this->M_order->get_max_cash($value1->COST_ID, $value['container_number'])->row()->id;
								$ids = ($temp_max_add == '')?0:$temp_max_add;

								$data_cash[] = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $value1->COST_ID,
									'sequence_id' => $ids,
									'container_number' => $value['container_number'],
									'cost_currency' => $value1->COST_CURRENCY,
									'cost_type_id' => $value1->COST_TYPE_ID,
									'cost_group_id' => $value1->COST_GROUP_ID,
									'cost_request_amount' => $value1->COST_AMOUNT,
									'request_date' => $date,
									'user_id_request' => $this->nik,
									'user_id' => $this->nik,
									'user_date' => $date,
									'status' => 'trucking',
									'cost_kind' => 'S',
									'is_transfered' => 'N'
								);
							}
						}

						// add to workorder trucking
						if (!$this->db->insert_batch('dbo.TRWORKORDER_TRUCKING', $data_container)) {
							throw new Exception("Error Processing Request Add Container Trucking Data", 1);
							
						}

						// insert into table trcash_request
						if (!$this->db->insert_batch('dbo.TRCASH_REQUEST', $data_cash)) {
							throw new Exception("Error Processing Request Add Cost to Cash Request", 1);
							
						}
						// insert to table cost wo trucking
						if (!$this->db->insert_batch('dbo.TRWORKORDER_COST_SERVICE_CONTAINER_ATTRIBUTE', $cost_trucking)) {
							throw new Exception("Error Processing Request Add Cost Container Trucking", 1);
							
						}
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request", 1);
						
					} else {
						$this->session->set_flashdata('success', "Successfully insert / updated data trucking!");
						$this->db->trans_commit();
						redirect('Order/view_trucking');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function search_truck()
	{
		$kode = $this->input->get('term');
		$truck = $this->M_order->get_truck($kode)->result();

		foreach ($truck as $key => $value) {
			$temp_truck['value'] =  $value->TRUCK_ID;
			$temp_truck['truck_id'] = $value->TRUCK_ID;
			$result_truck[] = $temp_truck;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_truck);
	}

	function search_chasis()
	{
		$kode = $this->input->get('term');
		$chasis = $this->M_order->get_chasis($kode)->result();

		foreach ($chasis as $key => $value) {
			$temp_chasis['value'] =  $value->CHASSIS_ID;
			$temp_chasis['chasis_id'] = $value->CHASSIS_ID;
			$result_chasis[] = $temp_chasis;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_chasis);
	}

	function search_driver()
	{
		$kode = $this->input->get('term');
		$driver = $this->M_order->get_driver($kode)->result();

		foreach ($driver as $key => $value) {
			$temp_driver['value'] =  $value->DRIVER_NAME;
			$temp_driver['driver_id'] = $value->DRIVER_ID;
			$result_driver[] = $temp_driver;
		}
		// $this->load->helper('comman_helper');
		// pr($hoarding);
		echo json_encode($result_driver);
	}

	function entry_transfer()
	{
		$this->load->helper('comman_helper');
		// generate auto number transaction
		$temp_id = $this->M_order->get_max_transaction()->row()->id;
		$year_now = date('y');
		$date = date('Y-m-d');

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		// $temp_subs = substr($temp_id, 9, 1);
		// TRX0000009
		// TRX0000001
		$temp2 = $temp_id + 1;

		if ($temp_id != NULL) {
			if ($temp2 < 10) {
				$temp_subs = substr($temp_id, 6, 1);
				$temp_subs++;
				$id = "TRX" . $year_now . "000000" . $temp2;
			} elseif ($temp2 == 10 || $temp2 < 100) {
				$temp_subs = substr($temp_id, 5, 2);
				$temp_subs++;
				$id = "TRX" . $year_now .  "00000" . $temp2;
			} elseif ($temp2 == 100 || $temp2 < 1000) {
				$temp_subs = substr($temp_id, 4, 3);
				$temp_subs++;
				$id = "TRX" . $year_now .  "0000" . $temp2;
			} elseif ($temp2 == 1000 || $temp2 < 10000) {
				$temp_subs = substr($temp_id, 3, 4);
				$temp_subs++;
				$id = "TRX" . $year_now .  "000" . $temp2;
			} elseif ($temp2 == 10000 || $temp2 < 100000) {
				$temp_subs = substr($temp_id, 2, 5);
				$temp_subs++;
				$id = "TRX" . $year_now .  "00" . $temp2;
			} elseif ($temp2 == 100000 || $temp2 < 1000000) {
				$temp_subs = substr($temp_id, 1, 6);
				$temp_subs++;
				$id = "TRX" . $year_now .  "0" . $temp2;
			} else {
				$id = "TRX" . $year_now .  ($temp_id+1);
			}
		} else {
			// echo "kosong";
			$id = "TRX" . $year_now . "0000001";
		}

		// $this->load->helper('comman_helper');
		// pr($id);

		$data['transaction_number'] = $id;

		// get data from post
		$pic_name = $this->input->post('pic_name');
		$pic_id = $this->input->post('pic_id');
		$data['pic_id'] = $pic_id;
		$data['pic_name'] = $pic_name;
		$date_trx = $this->input->post('date');

		$data['data_cost'] = $this->M_order->get_data_transfer($pic_name, $pic_id, $date_trx)->result();
		// pr(count($data['data_cost']));

		$this->form_validation->set_rules('transaction_number', 'Transaction', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entrycash', $data);
			} else {
				$this->db->trans_begin();
				try {
					$transaction_number = $this->input->post('transaction_number');
					$cash = $this->input->post('cash');
					// pr($cash);
					$receiver = $this->input->post('receiver');
					$receiver_name = $this->input->post('receiver_name');

					// sum total cost amount
					foreach ($cash as $value) {
						$sum += $value['cost_amount'];
					}

					// change status is_transfered cash request
					foreach ($cash as $value) {
						$change_stat = array(
							'is_transfered' => 'Y',
						);
						$update_stat = $this->M_order->update_stat($value['work_order_number'], $value['container_number'], $value['cost_id'], $value['sequence_id'], $value['cost_kind'], 'dbo.TRCASH_REQUEST', $change_stat);
						if ($update_stat == FALSE) {
							throw new Exception("Error Processing Request Update Status Transfered Cash Request", 1);
						}
					}

					// get company code
					$company_code = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
					// get odbc
					$odbc = $this->M_order->get_odbc($company_code)->row()->EpicorODBC;

					// get table from odbc
					$table_name = substr($odbc, 0, 4);

					$get_voucher = $this->M_order->get_voucher_code($table_name, $company_code);
					
					// combine voucher number
					$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

					// echo $voucher_number_out;

					// $this->load->helper('comman_helper');
					// pr($get_voucher->row()->seq_no + 1);

					// combine cost group
					$data_costgroup = $cash; // create copy to delete dups from
					$new_costgroup = array();

					for( $i=0; $i<count($cash); $i++ ) {

					    if ( in_array( array( $cash[$i]['cost_group'] ), $new_costgroup ) ) {
					    	unset($new_costgroup[$i]);
					    	unset($data_costgroup[$i]);
					    }
					    else {
					    	$new_costgroup[$i][] = $cash[$i]['cost_group'];
					    }

					}

					$cost_group = $data_costgroup[0]['cost_group'];

					// filter currency
					$data_currency = $cash; // create copy to delete dups from
					$new_currency = array();

					for( $i=0; $i<count($cash); $i++ ) {

					    if ( in_array( array( $cash[$i]['currency'] ), $new_currency ) ) {
					    	unset($new_currency[$i]);
					    	unset($data_currency[$i]);
					    }
					    else {
					    	$new_currency[$i][] = $cash[$i]['currency'];
					    }

					}

					$currency = $data_currency[0]['currency'];

					// filter work order number
					$data_work = $cash; // create copy to delete dups from
					$new_work = array();

					for( $i=0; $i<count($cash); $i++ ) {

					    if ( in_array( array( $cash[$i]['work_order_number'] ), $new_work ) ) {
					    	unset($new_work[$i]);
					    	unset($data_work[$i]);
					    }
					    else {
					    	$new_work[$i][] = $cash[$i]['work_order_number'];
					    }

					}

					// create header for keperluan voucher
					// // combine work
					$work_array = array();
					$customer_array = array();

					foreach ($cash as $key => $value) {
						array_push($work_array, $value['work_order_number']);

						// get customer wo
						$customer_from_wo = $this->M_order->get_just_wo_data($value['work_order_number'])->row()->CUSTOMER_ID;
						$customer_id = $this->M_order->get_customer_detail($customer_from_wo)->row()->SHORT_NAME;

						array_push($customer_array, $customer_id);
					}
					$wo_uniq = array_unique($work_array);
					$work = implode(', ', $wo_uniq);

					$customer_uniq = array_unique($customer_array);
					$customer_voucher = implode(", ", $customer_uniq);
					
					// // get name PIC receiver
					$pic_receiver = $this->M_order->get_name_nik($receiver)->row()->Nm_lengkap;
					// $this->load->helper('comman_helper');
					// pr($pic_receiver);

					$detail = "WO " . $work . " - " . $pic_receiver;
					$detail_for_vou = substr($detail, 0, 40)."...";

					$detail_utama = "WO " . $work . " - " . $pic_receiver;
					$detail_1 = "";
					$detail_2 = "";
					$detail_3 = "";
					$detail_4 = "";
					$len_detail = strlen($detail_utama);

					if ($len_detail > 34 && $len_detail <= 120) {
						$detail_1 = substr($detail_utama, 0, 34);
						$detail_2 = substr($detail_utama, 33, 39);
						$detail_3 = substr($detail_utama, 38, ($len_detail - (strlen($detail_1) + strlen($detail_2))));
					} elseif ($len_detail > 34 && $len_detail <= 80) {
						$detail_1 = substr($detail_utama, 0, 34);
						$detail_2 = substr($detail_utama, 33, ($len_detail - strlen($detail_1)));
						$detail_3 = "";
					} else {
						$detail_1 = $detail_utama;
						$detail_2 = "";
						$detail_3 = "";
					}

					// posting voucher
					// // voucher out
					$data_vou_out = array(
						'vou_no' => $voucher_number_out,
						'tipe_prof' => '0',
						'flow' => '0',
						'ref_no' => $transaction_number,
						'appl_date' => $date,
						'dept' => 'BZ1',
						'pembayaran' => 'TRANSFER',
						'vc_code' => 'U0055S',
						'kepada' => $receiver_name,
						'org_amt' => $sum,
						'curr' => $currency,
						'keperluan' => $detail_1,
						'keperluan2' => $detail_2,
						'keperluan3' => $detail_3,
						'vessel' => '999',
						'beban' => $customer_voucher,
						'entry_by' => $this->nik,
						'entry_date' => $date
					);

					$data_vou_det_out = array(
						'vou_no' => $voucher_number_out,
						'seq_no' => '1',
						'acc_code' => '131010100000000000',
						'curr' => $currency,
						'total' => $sum,
						'description' => $detail_for_vou,
						'entry_by' => $this->nik,
						'entry_date' => $date,
						'dept_bbn' => 'BZ1',
						'dept_kor' => 'ACC',
						'kode_ves' => '999'
					);

					$data_trx_vou_out = array(
						'voucher_number' => $voucher_number_out,
						'flow' => '0',
						'trx_number' => $transaction_number
					);

					// insert into table vou dan vou_det, trx_vou
					$insert_vou_out = $this->M_order->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);
					if ($insert_vou_out == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher", 1);
					}

					$insert_vou_det_out = $this->M_order->insert_vou_det($table_name, $company_code, 'vtrx_vou_det', $data_vou_det_out);
					if ($insert_vou_det_out == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher Detail", 1);
					}
					// $this->db4->insert('dbo.vtrx_vou', $data_vou_out);
					// $this->db4->insert('dbo.vtrx_vou_det', $data_vou_det_out);
					$insert_trtrx_vou_out = $this->M_order->insert_trtrx_vou('TRTRX_VOU', $data_trx_vou_out);

					if ($insert_trtrx_vou_out == FALSE) {
						throw new Exception("Error Processing Request to Entry TRTRX Vou (OUT)", 1);
					}

					$seq = $get_voucher->row()->seq_no + 1;

					// update voucher number
					$update_voucher = array(
						'seq_no' => $seq
					);
					$update_voucher_out = $this->M_order->update_voucher($table_name, $company_code, 'vutil_genr', $update_voucher, 'VC');
					if ($update_voucher_out == FALSE) {
						throw new Exception("Error Processing Request to Update Voucher Number (1)", 1);
					}

					unset($update_voucher);

					// get voucher number for in
					$get_voucher_in = $this->M_order->get_voucher_code($table_name, $company_code);
					// combine voucher number
					$voucher_number_in = $get_voucher_in->row()->first_code . $get_voucher_in->row()->seq_no;

					// echo $voucher_number_in;

					// $this->load->helper('comman_helper');
					// pr($voucher_number_in);

					// // voucher in
					$data_vou_in = array(
						'vou_no' => $voucher_number_in,
						'tipe_prof' => '0',
						'flow' => '1',
						'ref_no' => $transaction_number,
						'appl_date' => $date,
						'dept' => 'BZ1',
						'pembayaran' => 'TRANSFER',
						'vc_code' => 'U0055C',
						'kepada' => $pic_receiver,
						'org_amt' => $sum,
						'curr' => $currency,
						'keperluan' => $detail_1,
						'keperluan2' => $detail_2,
						'keperluan3' => $detail_3,
						'vessel' => '999',
						'beban' => $customer_voucher,
						'entry_by' => $this->nik,
						'entry_date' => $date
					);

					$data_vou_det_in = array(
						'vou_no' => $voucher_number_in,
						'seq_no' => '1',
						'acc_code' => '131010100000000000',
						'curr' => $currency,
						'total' => "-".$sum,
						'description' => $detail_for_vou,
						'entry_by' => $this->nik,
						'entry_date' => $date,
						'dept_bbn' => 'BZ1',
						'dept_kor' => 'ACC',
						'kode_ves' => '999'
					);

					$data_trx_vou_in = array(
						'voucher_number' => $voucher_number_in,
						'flow' => '1',
						'trx_number' => $transaction_number
					);

					// insert into table vou dan vou_det
					$insert_vou_in = $this->M_order->insert_vou($table_name, $company_code, 'vtrx_vou', $data_vou_in);
					if ($insert_vou_in == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher With In Flow", 1);
					}
					$insert_vou_det_in = $this->M_order->insert_vou_det($table_name, $company_code, 'vtrx_vou_det', $data_vou_det_in);
					if ($insert_vou_det_in == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher Detail With In Flow", 1);
					}

					$insert_trtrx_vou_in = $this->M_order->insert_trtrx_vou('TRTRX_VOU', $data_trx_vou_in);

					if ($insert_trtrx_vou_in == FALSE) {
						throw new Exception("Error Processing Request to Entry TRTRX Vou (IN)", 1);
					}

					// insert for transfer header
					$data_header = array(
						'trx_number' => $transaction_number,
						'trx_date' => $date,
						'cost_group_id' => $cost_group,
						'currency' => $currency,
						'total_amount' => $sum,
						'pic_receiver' => $receiver,
						'status' => 'N',
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$insert_transfer_header = $this->M_order->insert_transfer_header('TRTRANSFER_HEADER', $data_header);

					// $this->db->db_select();
					if ($insert_transfer_header == FALSE) {
						throw new Exception("Error Processing Request to Entry Transfer Header", 1);
					}

					// insert for transfer detail
					$get_sequence = $this->M_order->get_seq_detail($transaction_number)->row()->id;

					if ($get_sequence == NULL) {
						$sequnce = 1;
					} else {
						$sequnce = $get_sequence;
					}

					foreach ($cash as $value) {
						$data_detail = array(
							'trx_number' => $transaction_number,
							'sequence_id' => $sequnce,
							'work_order_number' => $value['work_order_number'],
							'container_number' => $value['container_number'],
							'cost_id' => $value['cost_id'],
							'cost_type_id' => $value['cost_type'],
							'cost_group_id' => $value['cost_group'],
							'cost_currency' => $value['currency'],
							'cost_amount' => $value['cost_amount'],
							'user_id' => $this->nik,
							'user_date' => $date
						);

						$insert_transfer_detail = $this->M_order->insert_transfer_detail('TRTRANSFER_DETAIL', $data_detail);

						if ($insert_transfer_detail == FALSE) {
							throw new Exception("Error Processing Request to Entry Transfer Detail", 1);
						}

						$sequnce++;
					}
					

					// update voucher number
					$update_voucher2 = array(
						'seq_no' => $seq+1
					);
					$update_voucher_in = $this->M_order->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher2, 'VC');
					if ($update_voucher_in == FALSE) {
						throw new Exception("Error Processing Request to Update Voucher Number (2)", 1);
					}

					unset($update_voucher);

					// $this->load->helper('comman_helper');
					// pr($sum);

					// // insert into approval
					// $check_approval = $this->M_order->check_approval_param($transaction_number, 'D1009');
					// if ($check_approval->num_rows() < 1) {
					// 	$data_approval = array(
					// 		'transaction_number' => $transaction_number,
					// 		'document_id' => 'D1009',
					// 		'revision_number' => '0',
					// 		'company_id' => $code_cmpy,
					// 		'request_approval_date' => $date,
					// 		'approval_status' => 'N'
					// 	);
					// 	if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_approval)) {
					// 		throw new Exception("Error Processing Request to Entry Approval Additional Cost", 1);
					// 	}
					// }

					$this->M_order->reset_connect();

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully transfer cost and posting eVoucher!");
						$this->db->trans_commit();
						// redirect(current_url());
						redirect('Order/view_all_transaction');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function entry_transfer_do()
	{
		$this->load->helper('comman_helper');
		// generate auto number transaction
		$temp_id = $this->M_order->get_max_transaction()->row()->id;
		$temp2 = $temp_id + 1;
		$year_now = date('y');
		$date = date('Y-m-d');

		// $temp_subs = substr($temp_id, 9, 1);
		// TRX0000009
		// TRX0000001

		if ($temp_id != NULL) {
			if ($temp2 < 10) {
				$temp_subs = substr($temp_id, 6, 1);
				$temp_subs++;
				$id = "TRX" . $year_now . "000000" . $temp2;
			} elseif ($temp2 == 10 || $temp2 < 100) {
				$temp_subs = substr($temp_id, 5, 2);
				$temp_subs++;
				$id = "TRX" . $year_now .  "00000" . $temp2;
			} elseif ($temp2 == 100 || $temp2 < 1000) {
				$temp_subs = substr($temp_id, 4, 3);
				$temp_subs++;
				$id = "TRX" . $year_now .  "0000" . $temp2;
			} elseif ($temp2 == 1000 || $temp2 < 10000) {
				$temp_subs = substr($temp_id, 3, 4);
				$temp_subs++;
				$id = "TRX" . $year_now .  "000" . $temp2;
			} elseif ($temp2 == 10000 || $temp2 < 100000) {
				$temp_subs = substr($temp_id, 2, 5);
				$temp_subs++;
				$id = "TRX" . $year_now .  "00" . $temp2;
			} elseif ($temp2 == 100000 || $temp2 < 1000000) {
				$temp_subs = substr($temp_id, 1, 6);
				$temp_subs++;
				$id = "TRX" . $year_now .  "0" . $temp2;
			} else {
				$id = "TRX" . $year_now .  ($temp_id+1);
			}
		} else {
			// echo "kosong";
			$id = "TRX" . $year_now . "0000001";
		}

		// $this->load->helper('comman_helper');
		// pr($id);

		$data['transaction_number'] = $id;

		// get data from post
		$pic_name = $this->input->post('pic_name');
		$pic_id = $this->input->post('pic_id');
		// $work_order_number = $this->input->post('work_order_number');
		$data['pic_id'] = $pic_id;
		$data['pic_name'] = $pic_name;
		$data['work_order_number'] = $work_order_number;
		$date_trx = $this->input->post('date');

		// $data['data_cost'] = $this->M_order->get_data_transfer2($pic_name, $pic_id, $date_trx)->result();
		$data['data_cost'] = $this->M_order->get_data_transfer2($pic_name, $pic_id, $date_trx)->result();
		// pr(count($data['data_cost']));

		$this->form_validation->set_rules('transaction_number', 'Transaction', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entrycashdo', $data);
			} else {
				$this->db->trans_begin();
				try {
					$transaction_number = $this->input->post('transaction_number');
					// $cash = $this->input->post('cash');
					// pr($cash);
					$receiver = $this->input->post('receiver');
					$receiver_name = $this->input->post('receiver_name');
					// $work_order = $this->input->post('work_order_number');

					$cash = $this->input->post('cash');
					$cash_share = $this->input->post('cash');
					$cash_jaminan = $this->input->post('cash');
					foreach ($cash_share as $key => $value) {
						if ($value['cost_id'] == 'C039') {
							unset($cash_share[$key]);
						}
					}

					foreach ($cash_jaminan as $key => $value) {
						if ($value['cost_id'] != 'C039') {
							unset($cash_jaminan[$key]);
						}
					}	

					// sum total cost amount
					foreach ($cash as $value) {
						$sum += $value['cost_amount'];
					}

					foreach ($cash_share as $key => $value) {
						$sum_share += $value['cost_amount'];
					}

					// // change status is_transfered cash request
					// foreach ($cash as $value) {
					// 	$change_stat = array(
					// 		'is_transfered' => 'Y',
					// 	);
					// 	$update_stat = $this->M_order->update_stat($value['work_order_number'], $value['container_number'], $value['cost_id'], $value['sequence_id'], $value['cost_kind'], 'dbo.TRCASH_REQUEST', $change_stat);
					// 	// if ($update_stat == FALSE) {
					// 	// 	throw new Exception("Error Processing Request Update Status Transfered Cash Request", 1);
					// 	// }
					// }

					// change status is_transfered cash request
					foreach ($cash as $value) {
						$change_stat = array(
							'is_transfered' => 'Y',
						);
						$update_stat = $this->M_order->update_stat($value['work_order_number'], $value['container_number'], $value['cost_id'], $value['sequence_id'], $value['cost_kind'], 'dbo.TRCASH_REQUEST', $change_stat);
						if ($update_stat == FALSE) {
							throw new Exception("Error Processing Request Update Status Transfered Cash Request", 1);
						}
					}

					// get company code
					$company_code = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
					// get odbc
					$odbc = $this->M_order->get_odbc($company_code)->row()->EpicorODBC;

					// get table from odbc
					$table_name = substr($odbc, 0, 4);

					$get_voucher = $this->M_order->get_voucher_code($table_name, $company_code);
					
					// combine voucher number
					$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

					// echo $voucher_number_out;

					// $this->load->helper('comman_helper');
					// pr($get_voucher->row()->seq_no + 1);

					// combine cost group
					$data_costgroup = $cash; // create copy to delete dups from
					$new_costgroup = array();

					for( $i=0; $i<count($cash); $i++ ) {

					    if ( in_array( array( $cash[$i]['cost_group'] ), $new_costgroup ) ) {
					    	unset($new_costgroup[$i]);
					    	unset($data_costgroup[$i]);
					    }
					    else {
					    	$new_costgroup[$i][] = $cash[$i]['cost_group'];
					    }

					}

					$cost_group = $data_costgroup[0]['cost_group'];

					// filter currency
					$data_currency = $cash; // create copy to delete dups from
					$new_currency = array();

					for( $i=0; $i<count($cash); $i++ ) {

					    if ( in_array( array( $cash[$i]['currency'] ), $new_currency ) ) {
					    	unset($new_currency[$i]);
					    	unset($data_currency[$i]);
					    }
					    else {
					    	$new_currency[$i][] = $cash[$i]['currency'];
					    }

					}

					$currency = $data_currency[0]['currency'];

					// filter work order number
					$data_work = $cash; // create copy to delete dups from
					$new_work = array();

					for( $i=0; $i<count($cash); $i++ ) {

					    if ( in_array( array( $cash[$i]['work_order_number'] ), $new_work ) ) {
					    	unset($new_work[$i]);
					    	unset($data_work[$i]);
					    }
					    else {
					    	$new_work[$i][] = $cash[$i]['work_order_number'];
					    }

					}

					// create header for keperluan voucher
					// // combine work
					$work_array = array();
					$customer_array = array();

					foreach ($cash as $key => $value) {
						array_push($work_array, $value['work_order_number']);

						// get customer wo
						$customer_from_wo = $this->M_order->get_just_wo_data($value['work_order_number'])->row()->CUSTOMER_ID;
						$customer_id = $this->M_order->get_customer_detail($customer_from_wo)->row()->SHORT_NAME;

						array_push($customer_array, $customer_id);
					}
					$wo_uniq = array_unique($work_array);
					$work = implode(', ', $wo_uniq);

					$customer_uniq = array_unique($customer_array);
					$customer_voucher = implode(", ", $customer_uniq);

					// // get name PIC receiver
					$pic_receiver = $this->M_order->get_name_nik($receiver)->row()->Nm_lengkap;
					// $this->load->helper('comman_helper');
					// pr($pic_receiver);

					// $customer_from_wo = $this->M_order->get_just_wo_data($work_order)->row()->CUSTOMER_ID;
					// $customer_do = $this->M_order->get_customer_detail($customer_from_wo)->row()->NAME;

					$detail = "WO " . $work . " "  .$receiver_name;
					$detail_do = "WO " . $work . " " . $receiver_name . " DO";

					// posting voucher
					// // voucher out
					$data_vou_out = array(
						'vou_no' => $voucher_number_out,
						'tipe_prof' => '0',
						'flow' => '0',
						'ref_no' => $transaction_number,
						'appl_date' => $date,
						'dept' => 'BZ1',
						'pembayaran' => 'TRANSFER',
						'vc_code' => 'U0055S',
						'kepada' => $receiver_name,
						'org_amt' => $sum,
						'curr' => $currency,
						'keperluan' => $detail,
						'vessel' => '999',
						'beban' => $customer_voucher,
						'head_approval' => '0',
						'entry_by' => $this->nik,
						'entry_date' => $date
					);

					$data_vou_det_out = array(
						'vou_no' => $voucher_number_out,
						'seq_no' => '1',
						'kode_ves' => '999',
						'acc_code' => '131010100000000000',
						'curr' => $currency,
						'total' => $sum,
						'description' => $detail,
						'entry_by' => $this->nik,
						'entry_date' => $date,
						'dept_bbn' => 'BZ1',
						'dept_kor' => 'ACC'
					);

					// // detail jaminan
					// $no_jaminan = 2;
					// foreach ($cash_jaminan as $key => $value) {
					// 	$detail_jaminan = "WO " . $value['work_order_number'] . " " . $customer_do . " JAMINAN" . " " . $value['container_number'];
					// 	$data_vou_det_out_jaminan = array(
					// 		'vou_no' => $voucher_number_out,
					// 		'seq_no' => $no_jaminan,
					// 		'kode_ves' => '999',
					// 		'acc_code' => '151020000000000000',
					// 		'curr' => $currency,
					// 		'total' => $value['cost_amount'],
					// 		'description' => $detail_jaminan,
					// 		'entry_by' => $this->nik,
					// 		'entry_date' => $date,
					// 		'dept_bbn' => '000',
					// 		'dept_kor' => 'ACC'
					// 	);

					// 	$insert_vou_det_out_jaminan = $this->M_order->insert_vou_det_jaminan($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out_jaminan);
					// 	if ($insert_vou_det_out_jaminan == FALSE) {
					// 		throw new Exception("Error Processing Request to Entry Voucher Detail Jaminan Data", 1);
					// 	}

					// 	$no_jaminan++;
					// }

					$data_trx_vou_out = array(
						'voucher_number' => $voucher_number_out,
						'flow' => '0',
						'trx_number' => $transaction_number
					);

					// insert into table vou dan vou_det, trx_vou
					$insert_vou_out = $this->M_order->insert_vou($table_name, $company_code, 'vtrx_vou', $data_vou_out);
					if ($insert_vou_out == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher Data", 1);
					}

					$insert_vou_det_out = $this->M_order->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);
					if ($insert_vou_det_out == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher Detail DO Data", 1);
					}

					// if (!$this->db->insert('TRTRX_VOU', $data_trx_vou_out)) {
					// 	throw new Exception("Error Processing Request to Entry Voucher Transaction", 1);
					// }

					$insert_trtrx_vou_out = $this->M_order->insert_trtrx_vou('TRTRX_VOU', $data_trx_vou_out);

					if ($insert_trtrx_vou_out == FALSE) {
						throw new Exception("Error Processing Request to Entry TRTRX Vou (IN)", 1);
					}

					$seq = $get_voucher->row()->seq_no + 1;

					// update voucher number
					$update_voucher = array(
						'seq_no' => $seq
					);
					$update_voucher_do = $this->M_order->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');
					if ($update_voucher_do == FALSE) {
						throw new Exception("Error Processing Request to Update Voucher Number", 1);
					}

					unset($update_voucher);

					// // insert for transfer header
					// $data_header = array(
					// 	'trx_number' => $transaction_number,
					// 	'trx_date' => $date,
					// 	'cost_group_id' => $cost_group,
					// 	'currency' => $currency,
					// 	'total_amount' => $sum,
					// 	'pic_receiver' => $receiver,
					// 	'user_id' => $this->nik,
					// 	'user_date' => $date
					// );
					// // if (!$this->db->insert("dbo.TRTRANSFER_HEADER", $data_header)) {
					// // 	throw new Exception("Error Processing Request to Entry Header Transfer", 1);
					// // }

					// $insert_transfer_header = $this->M_order->insert_transfer_header('TRTRANSFER_HEADER', $data_header);

					// // $this->db->db_select();
					// if ($insert_transfer_header == FALSE) {
					// 	throw new Exception("Error Processing Request to Entry Transfer Header", 1);
					// }

					// // insert for transfer detail
					// $get_sequence = $this->M_order->get_seq_detail($transaction_number)->row()->id;

					// if ($get_sequence == NULL) {
					// 	$sequnce = 1;
					// } else {
					// 	$sequnce = $get_sequence;
					// }

					// foreach ($cash as $value) {
					// 	$data_detail = array(
					// 		'trx_number' => $transaction_number,
					// 		'sequence_id' => $sequnce,
					// 		'work_order_number' => $value['work_order_number'],
					// 		'container_number' => $value['container_number'],
					// 		'cost_id' => $value['cost_id'],
					// 		'cost_type_id' => $value['cost_type'],
					// 		'cost_group_id' => $value['cost_group'],
					// 		'cost_currency' => $value['currency'],
					// 		'cost_amount' => $value['cost_amount'],
					// 		'user_id' => $this->nik,
					// 		'user_date' => $date
					// 	);

					// 	$insert_transfer_detail = $this->M_order->insert_transfer_detail('TRTRANSFER_DETAIL', $data_detail);

					// 	if ($insert_transfer_detail == FALSE) {
					// 		throw new Exception("Error Processing Request to Entry Transfer Detail", 1);
					// 	}

					// 	$sequnce++;
					// }
					// // if (!$this->db->insert_batch('dbo.TRTRANSFER_DETAIL', $data_detail)) {
					// // 	throw new Exception("Error Processing Request to Entry Detail Transfer", 1);
					// // }

					// $this->M_order->reset_connect();

					// get voucher number for in
					$get_voucher_in = $this->M_order->get_voucher_code($table_name, $company_code);
					// combine voucher number
					$voucher_number_in = $get_voucher_in->row()->first_code . $get_voucher_in->row()->seq_no;

					// echo $voucher_number_in;

					// $this->load->helper('comman_helper');
					// pr($voucher_number_in);

					// // voucher in
					$data_vou_in = array(
						'vou_no' => $voucher_number_in,
						'tipe_prof' => '0',
						'flow' => '1',
						'ref_no' => $transaction_number,
						'appl_date' => $date,
						'dept' => 'BZ1',
						'pembayaran' => 'TRANSFER',
						'vc_code' => 'U0055C',
						'kepada' => $receiver_name,
						'org_amt' => $sum,
						'curr' => $currency,
						'keperluan' => $detail,
						'vessel' => '999',
						'beban' => $customer_voucher,
						'entry_by' => $this->nik,
						'entry_date' => $date
					);

					$data_vou_det_in = array(
						'vou_no' => $voucher_number_in,
						'seq_no' => '1',
						'acc_code' => '131010100000000000',
						'curr' => $currency,
						'total' => "-".$sum,
						'description' => $detail,
						'entry_by' => $this->nik,
						'entry_date' => $date,
						'dept_bbn' => 'BZ1',
						'dept_kor' => 'ACC',
						'kode_ves' => '999'
					);

					$data_trx_vou_in = array(
						'voucher_number' => $voucher_number_in,
						'flow' => '1',
						'trx_number' => $transaction_number
					);

					// insert into table vou dan vou_det
					$insert_vou_in = $this->M_order->insert_vou($table_name, $company_code, 'vtrx_vou', $data_vou_in);
					if ($insert_vou_in == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher With In Flow", 1);
					}
					$insert_vou_det_in = $this->M_order->insert_vou_det($table_name, $company_code, 'vtrx_vou_det', $data_vou_det_in);
					if ($insert_vou_det_in == FALSE) {
						throw new Exception("Error Processing Request to Entry Voucher Detail With In Flow", 1);
					}

					$insert_trtrx_vou_in = $this->M_order->insert_trtrx_vou('TRTRX_VOU', $data_trx_vou_in);

					if ($insert_trtrx_vou_in == FALSE) {
						throw new Exception("Error Processing Request to Entry TRTRX Vou (IN)", 1);
					}

					// insert for transfer header
					$data_header = array(
						'trx_number' => $transaction_number,
						'trx_date' => $date,
						'cost_group_id' => $cost_group,
						'currency' => $currency,
						'total_amount' => $sum,
						'pic_receiver' => $receiver,
						'status' => 'N',
						'user_id' => $this->nik,
						'user_date' => $date
					);

					$insert_transfer_header = $this->M_order->insert_transfer_header('TRTRANSFER_HEADER', $data_header);

					// $this->db->db_select();
					if ($insert_transfer_header == FALSE) {
						throw new Exception("Error Processing Request to Entry Transfer Header", 1);
					}

					// insert for transfer detail
					$get_sequence = $this->M_order->get_seq_detail($transaction_number)->row()->id;

					if ($get_sequence == NULL) {
						$sequnce = 1;
					} else {
						$sequnce = $get_sequence;
					}

					foreach ($cash as $value) {
						$data_detail = array(
							'trx_number' => $transaction_number,
							'sequence_id' => $sequnce,
							'work_order_number' => $value['work_order_number'],
							'container_number' => $value['container_number'],
							'cost_id' => $value['cost_id'],
							'cost_type_id' => $value['cost_type'],
							'cost_group_id' => $value['cost_group'],
							'cost_currency' => $value['currency'],
							'cost_amount' => $value['cost_amount'],
							'user_id' => $this->nik,
							'user_date' => $date
						);

						$insert_transfer_detail = $this->M_order->insert_transfer_detail('TRTRANSFER_DETAIL', $data_detail);

						if ($insert_transfer_detail == FALSE) {
							throw new Exception("Error Processing Request to Entry Transfer Detail", 1);
						}

						$sequnce++;
					}
					

					// update voucher number
					$update_voucher2 = array(
						'seq_no' => $seq+1
					);
					$update_voucher_in = $this->M_order->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher2, 'VC');
					if ($update_voucher_in == FALSE) {
						throw new Exception("Error Processing Request to Update Voucher Number (2)", 1);
					}

					unset($update_voucher);

					$this->M_order->reset_connect();

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully transfer cost and posting eVoucher!");
						$this->db->trans_commit();
						// redirect(current_url());
						redirect('Order/view_all_transaction');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function print_do()
	{
		$work_order_number = $this->uri->segment(3);
		$container_number = $this->uri->segment(4);
		$data['container_number'] = $container_number;
		$data['work_order_number'] = $work_order_number;
		$data['customer_name'] = $this->M_order->get_name_customer($work_order_number)->row()->name;
		$data['do_number'] = $this->M_order->get_detail_container($work_order_number, $container_number)->row()->DELIVERY_ORDER_NUMBER;
		$data['driver_name'] = $this->M_order->get_detail_container($work_order_number, $container_number)->row()->DRIVER_NAME;
		$data['nopol'] = $this->M_order->get_detail_container($work_order_number, $container_number)->row()->TRUCK_ID_NUMBER;
		$data['data_do'] = $this->M_order->get_data_print_do($work_order_number, $container_number)->result();
		$data['seal_number'] = $this->M_order->get_data_trucking_param($work_order_number, $container_number)->row()->SEAL_NUMBER;

		$html = $this->load->view('reports/r_deliveryorder', $data, true);
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
		$pdf->Output('DeliveryOrder.pdf', 'I');
	}

	function view_all_transaction()
	{
		$this->load->helper('comman_helper');
		// $data['data_transaction'] = $this->M_order->get_data_transaction()->result();
		$data_all_transaction = $this->M_order->get_data_transaction2()->result();
		foreach ($data_all_transaction as $key => $value) {
			$temp_data['TRX_NUMBER'] = $value->TRX_NUMBER;
			$temp_data['TRX_DATE'] = $value->TRX_DATE;
			$temp_data['CUSTOMER_NAME'] = $value->CUSTOMER_NAME;
			$temp_data['COST_GROUP'] = $value->COST_GROUP;
			$temp_data['VOUCHER_NUMBER'] = $value->VOUCHER_NUMBER;
			$temp_data['PIC_NAME'] = $value->PIC_NAME;
			$temp_data['RECEIVER'] = $value->RECEIVER;
			$temp_data['CURRENCY'] = $value->CURRENCY;
			$temp_data['TOTAL_AMOUNT'] = $value->TOTAL_AMOUNT;

			$data_wo = $this->M_order->get_wo_transaction($value->TRX_NUMBER)->result();
			$temp_wo = array();
			foreach ($data_wo as $key1 => $value1) {
				array_push($temp_wo, $value1->WORK_ORDER_NUMBER);
			}

			$temp_data['WORK_ORDER_NUMBER'] = implode(", ", $temp_wo);

			$result_transaction[] = $temp_data;
			unset($temp_wo);
		}

		// pr($result_transaction);
		$data['data_transaction'] = $result_transaction;

		$this->load->view('orders/v_alltransaction', $data);
	}

	function detail_transaction()
	{
		$transaction_number = $this->uri->segment(3);
		$data['transaction_number'] = $transaction_number;
		$data['data_detail'] = $this->M_order->get_detail_transaction($transaction_number)->result();
		$data['voucher_out'] = $this->M_order->get_vou_out($transaction_number)->row()->VOUCHER_NUMBER;
		$data['voucher_in'] = $this->M_order->get_vou_in($transaction_number)->row()->VOUCHER_NUMBER;
		$data['transaction_date'] = $this->M_order->get_once_transaction($transaction_number)->row()->TRANSACTION_DATE;
		$data['pic_name'] = $this->M_order->get_once_transaction($transaction_number)->row()->PIC_NAME;
		$data['entry_by'] = $this->M_order->get_once_transaction($transaction_number)->row()->ENTRY_BY;

		$this->load->view('orders/v_detailtransaction', $data);
	}

	function entry_additional()
	{
		$this->load->helper('comman_helper');
		$date = date('Y-m-d');
		$work_order_number = $this->uri->segment(3);
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		// generate auto number transaction
		$temp_id = $this->M_order->get_max_additional()->row()->id;
		// pr($temp_id);
		$year_now = date('y');

		// $temp_subs = substr($temp_id, 9, 1);

		// if ($temp_id != NULL) {
		// 	// $temp_id++;
		// 	if ($temp_id <= 9) {
		// 		$temp_subs = substr($temp_id, 6, 1);
		// 		$temp_subs++;
		// 		$id = "AD" . "0000" . $temp_subs;
		// 	} elseif ($temp_id > 9 && $temp_id <= 100) {
		// 		$temp_subs = substr($temp_id, 5, 2);
		// 		$temp_subs++;
		// 		$id = "AD" . "000" . $temp_subs;
		// 	} elseif ($temp_id < 1000) {
		// 		$temp_subs = substr($temp_id, 4, 3);
		// 		$temp_subs++;
		// 		$id = "AD" . "00" . $temp_subs;
		// 	} elseif ($temp_id < 10000) {
		// 		$temp_subs = substr($temp_id, 3, 4);
		// 		$temp_subs++;
		// 		$id = "AD" . "0" . $temp_subs;
		// 	} else {
		// 		$id = "AD" . ($temp_id+1);
		// 	}
		// } else {
		// 	// echo "kosong";
		// 	$id = "AD" . "00001";
		// }

		if ($temp_id != NULL) {
			$temp_id++;
			$id = $temp_id;
		} else {
			$id = 160;
		}

		// $tamp = substr($temp_id, 2, (strlen($temp_id)-2));
		// pr($id);
		$data['id'] = $id;

		$data['all_wo'] = $this->M_order->get_container_number($work_order_number)->result();
		// $this->load->helper('comman_helper');
		// pr($data['all_wo']);
		$data['data_cost'] = $this->M_order->get_data_cost()->result();
		$data['data_currency'] = $this->M_order->get_data_currency()->result();

		$this->form_validation->set_rules('container_number', 'Container Number', 'required');
		$this->form_validation->set_rules('cost_id', 'Cost', 'required');
		$this->form_validation->set_rules('amount', 'Amount Cost', 'required');
		$this->form_validation->set_rules('currency', 'Currency', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entryadditional', $data);
			} else {
				$container_number = $this->input->post('container_number');
				$cost_id = $this->input->post('cost_id');
				// amount fix
				$amount = $this->input->post('amount');
				$amount_fix = str_replace(',', '', $amount);

				$currency = $this->input->post('currency');
				$additional_number = $this->input->post('additional_number');
				$remarks = $this->input->post('remarks');

				// $this->load->helper('comman_helper');
				// pr($amount_fix);

				// get type and group of cost id
				$cost_type = $this->M_order->get_cost_param($cost_id)->row()->COST_TYPE;
				$cost_group = $this->M_order->get_cost_param($cost_id)->row()->COST_GROUP;

				$this->db->trans_begin();
				try {
					$data_additional = array(
						'additional_number' => $additional_number,
						'company_id' => $code_cmpy,
						'work_order_number' => $work_order_number,
						'cost_id' => $cost_id,
						'container_number' => $container_number,
						'cost_currency' => $currency,
						'cost_type_id' => $cost_type,
						'cost_group_id' => $cost_group,
						'cost_request_amount' => $amount_fix,
						'request_date' => $date,
						'user_id_request' => $this->nik,
						'status' => 'N',
						'is_deleted' => 'N',
						'cost_kind' => 'A',
						'need_approved' => 'W',
						'remarks' => $remarks
					);


					if (!$this->db->insert('dbo.TRCASH_REQUEST_ADDITIONAL', $data_additional)) {
						throw new Exception("Error Processing Request entry cost additional.", 1);
						
					}

					$data_approval = array(
						'transaction_number' => $additional_number,
						'document_id' => 'D1007',
						'revision_number' => '0',
						'company_id' => $code_cmpy,
						'request_approval_date' => $date,
						'approval_status' => 'N',
						'remarks' => $remarks
					);
					if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_approval)) {
						throw new Exception("Error Processing Request to Entry Approval Additional Cost", 1);
					}

					// $this->db->trans_complete();

					if ($this->db->trans_status() === FALSE) {
						// $this->session->set_flashdata('failed', "Error Processing Request cost additonal!");
						throw new Exception("Error Processing Request", 1);
						
					} else {
						$this->session->set_flashdata('success_additional', "Successfully entry additional cost!");
						$this->db->trans_commit();
						redirect('Order/entry_cash_request/'.$work_order_number);
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function entry_selling_additional()
	{
		$this->load->helper('comman_helper');
		$work_order_number = $this->uri->segment(3);
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d');

		$data['selling_additional'] = $this->M_order->get_selling_additional($code_cmpy)->result();
		$data['all_wo'] = $this->M_order->get_container_number($work_order_number)->result();
		$data['data_currency'] = $this->M_order->get_data_currency()->result();

		$temp_id = $this->M_order->get_max_additional_selling()->row()->id;
		$year_now = date('y');
		$temp_id_tem = $temp_id + 1;
		// pr($temp_id_tem);
		// ADTS160001
		if ($temp_id != NULL) {
			if ($temp_id_tem < 10) {
				// $temp_subs = substr($temp_id, 6, 1);
				// $temp_subs++;
				$id = "ADTS" . $year_now . "000" . $temp_id_tem;
				// pr($id);
			} elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
				// $temp_subs = substr($temp_id, 5, 2);
				// $temp_subs++;
				// pr($temp_subs);
				$id = "ADTS" . $year_now .  "00" . $temp_id_tem;
				// pr($id);
			} elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
				// $temp_subs = substr($temp_id, 4, 3);
				// $temp_subs++;
				$id = "ADTS" . $year_now .  "0" . $temp_id_tem;
			} else {
				$id = "ADTS" . $year_now .  ($temp_id+1);
			}
		} else {
			// echo "kosong";
			$id = "ADTS" . $year_now . "0001";
		}

		$data['id'] = $id;

		$this->form_validation->set_rules('container_number', 'Container Number', 'required');
		$this->form_validation->set_rules('selling_service_id', 'Selling Service', 'required');
		$this->form_validation->set_rules('currency', 'Currency', 'required');
		$this->form_validation->set_rules('amount', 'Amount', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entryadditionalselling', $data);
			} else {
				$additional_selling = $this->input->post('additional_selling');
				$container_size = $this->input->post('container_size');
				$container_type = $this->input->post('container_type');
				$container_category = $this->input->post('container_category');
				$from_location = $this->input->post('from_location');
				$to_location = $this->input->post('to_location');
				$agreement_number = $this->input->post('agreement_number');
				$container_number = $this->input->post('container_number');
				$selling_service_id = $this->input->post('selling_service_id');
				$currency = $this->input->post('currency');
				$amount = $this->input->post('amount');
				$amount_fix = str_replace(',', '', $amount);
				$remarks = $this->input->post('remarks');
				$this->db->trans_begin();
				try {
					$data_selling_additional = array(
						'additional_selling' => $additional_selling,
						'work_order_number' => $work_order_number,
						'company_id' => $code_cmpy,
						'selling_service_id' => $selling_service_id,
						'container_number' => $container_number,
						'container_size_id' => $container_size,
						'container_type_id' => $container_type,
						'container_category_id' => $container_category,
						'from_location_id' => $from_location,
						'to_location_id' => $to_location,
						'agreement_number' => $agreement_number,
						'tariff_currency' => $currency,
						'tariff_amount' => $amount_fix,
						'flag' => '0',
						'status' => 'N',
						'user_id' => $this->nik,
						'user_date' => $date,
						'remarks' => $remarks
					);

					$check_approval = $this->M_order->check_approval_param($additional_selling, 'D1008');
					if ($check_approval->num_rows() < 1) {
						$data_approval = array(
							'transaction_number' => $additional_selling,
							'document_id' => 'D1008',
							'revision_number' => '0',
							'company_id' => $code_cmpy,
							'request_approval_date' => $date,
							'approval_status' => 'N',
							'remarks' => $remarks
						);
						if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_approval)) {
							throw new Exception("Error Processing Request to Entry Approval Additional Cost", 1);
						}
					}

					if (!$this->db->insert('dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $data_selling_additional)) {
						throw new Exception("Error Processing Request Entry Additional Selling Service", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry", 1);
					} else {
						$this->session->set_flashdata('success_additional_selling', 'Successfully Entry Additional Selling Service');
						$this->db->trans_commit();
						redirect('Order/entry_cash_request/'.$work_order_number);
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed_additional_selling', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
		
	}

	function edit_additional()
	{
		$this->load->helper('comman_helper');
		$work_order_number = $this->uri->segment(3);
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');
		if (isset($_POST)) {
			// declare variable
			$cost = $this->input->post('cost_appr');
			// pr($cost);
			$this->db->trans_begin();
			try {
				// change status is deleted in trcash request additional
				// change all status is deleted to N
				if (count($cost) > 0) {
					// delete all additional data of work order number
					$delete_additional = $this->M_order->delete_additional($work_order_number);
					if ($delete_additional == FALSE) {
						throw new Exception("Error Processing Request (201)", 1);
						
					}
					// insert additional cost into cash request
					foreach ($cost as $value) {
						$temp_max_add = $this->M_approval->get_max_cash($value['cost_id'], $value['container_number'])->row()->id;
						$ids = ($temp_max_add == '')?0:$temp_max_add;
						$data_add[] = array(
							'work_order_number' => $value['work_order_number'],
							'cost_id' => $value['cost_id'],
							'company_id' => $code_cmpy,
							'sequence_id' => $ids,
							'container_number' => $value['container_number'],
							'cost_kind' => $value['cost_kind'],
							'cost_currency' => $value['cost_currency'],
							'cost_type_id' => $value['cost_type_id'],
							'cost_group_id' => $value['cost_group_id'],
							'cost_request_amount' => $value['cost_request_amount'],
							'request_date' => $value['request_date'],
							'user_id_request' => $value['user_id_request'],
							'user_id' => $this->nik,
							'user_date' => $date
						);
					}
					if (!$this->db->insert_batch('dbo.TRCASH_REQUEST', $data_add)) {
						throw new Exception("Error Processing Request Updated/Deleted Additional Cost Data in Cash Request", 1);
						
					}

					$temp_change = array(
						'is_deleted' => 'Y'
					);
					$change_delete_additional3 = $this->M_order->change_delete_additional3($work_order_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $temp_change);
					if ($change_delete_additional3 == FALSE) {
						throw new Exception("Error Processing Request (202)", 1);
						
					}

					foreach ($cost as $value) {
						$change_delete = array(
							'is_deleted' => 'N'
						);
						$update_deleted2 = $this->M_order->update_deleted2($value['additional_number'], $value['work_order_number'], $value['container_number'], $value['cost_id'], $value['cost_request_amount'], 'dbo.TRCASH_REQUEST_ADDITIONAL', $change_delete);
						if ($update_deleted2 == FALSE) {
							throw new Exception("Error Processing Request Updated/Deleted Additional Cost Data (Change status if deleted)", 1);
						}
					}

					// update status approval data whom deleted to be waiting or new

				} else {
					$temp_change = array(
						'is_deleted' => 'Y'
					);
					$change_delete_additional3 = $this->M_order->change_delete_additional3($work_order_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $temp_change);
					if ($change_delete_additional3 == FALSE) {
						throw new Exception("Error Processing Request (202)", 1);
						
					}

					// delete all additional data of work order number
					$delete_additional = $this->M_order->delete_additional($work_order_number);
					if ($delete_additional == FALSE) {
						throw new Exception("Error Processing Request (204)", 1);
						
					}
				}

				// $this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request Delete or Update Additional Cost Data!", 1);
					
				} else {
					$this->session->set_flashdata('success', "Successfully edited/deleted additional cost for this work order!");
					$this->db->trans_commit();
					redirect('Order/entry_cash_request/'.$work_order_number);
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed', $e->getMessage());
				$this->db->trans_rollback();
				redirect('Order/entry_cash_request/'.$work_order_number);
			}
		}
	}

	function entry_operational_cost()
	{
		$data['data_wo'] = $this->M_order->get_wo_opr()->result();
		$data['pic_cash'] = $this->M_order->get_user_cash()->result();
		$data['pic_id'] = $this->nik;
		$data['pic_name'] = $this->M_order->get_name_pic($this->nik)->row()->Nm_lengkap;
		$this->load->view('orders/v_entryoperationalcost', $data);
	}

	function entry_opr_cost()
	{
		$this->load->helper('comman_helper');

		// get data from post
		// $pic_name = $this->input->post('pic_name');
		$pic_id = $this->input->post('pic_id');

		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');
		$work_order_number = $this->input->post('work_order_number');
		// pr($work_order_number);
		$data['data_cash_request'] = $this->M_order->get_data_cash_opr($pic_id, $work_order_number)->result();
		$rek_pic = $this->M_order->get_rek_pic($pic_id)->row()->BANK_ID;
		$data['data_mutation'] = $this->M_order->get_data_mutation2($rek_pic)->result();
		// pr($data['pic_cash']);
		$data['pic_name'] = $this->M_order->get_name_pic($pic_id)->row()->Nm_lengkap;
		$data['pic_id'] = $pic_id;
		$data['work_order_number'] = $work_order_number;
		$data['customer_name'] = $this->M_order->get_wo($work_order_number)->row()->CUSTOMER_NAME;
		$id = "";

		$this->form_validation->set_rules('work_order_number', 'Work Order Number', 'required');
		$this->form_validation->set_rules('opr[][]', 'Operational Cost', 'required');

		// $truckss = $this->input->post('trucking');
		// $oprs = $this->input->post('opr');
		// if (!empty($oprs)) {
		// 	// echo "tidak kosong";
		// 	// // return true;
		// 	foreach ($oprs as $key => $value) {
		// 		$this->form_validation->set_rules('opr['.$key.'][mutation_account]', 'Mutation Account ke-' . $key, 'required');
		// 		$this->form_validation->set_rules('opr['.$key.'][actual_amount]', 'Actual Amount ke-' . $key, 'required');
		// 	}
		// }

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_tableentryopr', $data);
			} else {
				$temp_id = $this->M_order->get_max_operational()->row()->id;
				$year_now = date('y');
				$temp_id_tem = $temp_id + 1;
				// pr($temp_id_tem);
				// TRXOPR16000001
				if ($temp_id != NULL) {
					if ($temp_id_tem < 10) {
						// $temp_subs = substr($temp_id, 6, 1);
						// $temp_subs++;
						$id = "TRXOPR" . $year_now . "00000" . $temp_id_tem;
						// pr($id);
					} elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
						// $temp_subs = substr($temp_id, 5, 2);
						// $temp_subs++;
						// pr($temp_subs);
						$id = "TRXOPR" . $year_now .  "0000" . $temp_id_tem;
						// pr($id);
					} elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
						// $temp_subs = substr($temp_id, 4, 3);
						// $temp_subs++;
						$id = "TRXOPR" . $year_now .  "000" . $temp_id_tem;
					} elseif ($temp_id_tem == 1000 || $temp_id_tem < 10000) {
						// $temp_subs = substr($temp_id, 4, 3);
						// $temp_subs++;
						$id = "TRXOPR" . $year_now .  "00" . $temp_id_tem;
					} elseif ($temp_id_tem == 10000 || $temp_id_tem < 100000) {
						// $temp_subs = substr($temp_id, 4, 3);
						// $temp_subs++;
						$id = "TRXOPR" . $year_now .  "0" . $temp_id_tem;
					} else {
						$id = "TRXOPR" . $year_now .  ($temp_id+1);
					}
				} else {
					// echo "kosong";
					$id = "TRXOPR" . $year_now . "000001";
				}

				// pr($id);

				$opr = $this->input->post('opr');
				// pr($opr[0]['cost_currency']);
				$work_order_number = $this->input->post('work_order_number');
				$pic_id = $this->input->post('pic_id');
				$this->db->trans_begin();
				try {
					// get total actual amount
					foreach ($opr as $value) {
						$amount_fix1 = str_replace(',', '', $value['cost_amount']);
						$amount_fix2 = str_replace(',', '', $value['actual_amount']);
						$total_actual_amount += $amount_fix2;
						$total_amount += $amount_fix1;
					}

					$currency_array = array();
					foreach ($opr as $value) {
						array_push($currency_array, $value['cost_currency']);
					}

					$currency_fix = array_unique($currency_array);

					// insert into operational header
					$data_operational_header = array(
						'TRX_OPERATIONAL' => $id,
						'TRX_OPERATIONAL_DATE' => $date,
						'WORK_ORDER_NUMBER' => $work_order_number,
						'PIC_ID' => $pic_id,
						'currency' => $currency_fix[0],
						'total_amount' => $total_amount,
						'total_actual_amount' => $total_actual_amount,
						'status' => 'N',
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.TROPERATIONAL_HEADER', $data_operational_header)) {
						throw new Exception("Error Processing Request to Insert Operational Header", 1);
					}

					// update data rincian / actual cost from cash request
					foreach ($opr as $value) {
						// amount fix
						// $amount = $this->input->post('amount');
						$amount_fix = str_replace(',', '', $value['actual_amount']);
						// $data_operational = array(
						// 	'cost_actual_amount' => $amount_fix,
						// 	'transaction_id' => $value['mutation_account'],
						// 	'is_done' => 'Y'
						// );
						// $update_opr = $this->M_order->update_opr($value['work_order_number'], $value['container_number'], $value['cost_id'], $value['cost_kind'], $value['sequence_id'], 'dbo.TRCASH_REQUEST', $data_operational);
						// if ($update_opr == FALSE) {
						// 	throw new Exception("Error Processing Request Update Actual Cost", 1);
						// }

						$data_operational_detail = array(
							'TRX_OPERATIONAL' => $id,
							'work_order_number' => $value['work_order_number'],
							'container_number' => $value['container_number'],
							'cost_id' => $value['cost_id'],
							'cost_type_id' => $value['cost_type_id'],
							'cost_group_id' => $value['cost_group_id'],
							'sequence_id' => $value['sequence_id'],
							'cost_currency' => $value['cost_currency'],
							'cost_amount' => $value['cost_amount'],
							'cost_actual_amount' => $amount_fix,
							'transaction_id' => $value['mutation_account'],
							'user_id' => $this->nik,
							'user_date' => $date
						);

						if (!$this->db->insert('dbo.TROPERATIONAL_DETAIL', $data_operational_detail)) {
							throw new Exception("Error Processing Request to Insert Operational Detail", 1);
						}

						$data_mut_flag = array(
							'is_done' => 'Y',
							'work_order_number' => $value['work_order_number']
						);
						$update_mut_flag = $this->M_order->update_mut_flag('dbo.TRBANK_STATEMENT', $data_mut_flag, $value['mutation_account']);
						if ($update_mut_flag == FALSE) {
							throw new Exception("Error Processing Request to Update Flag Mutation Data", 1);
						}

						// if ($value['home_debit'] == $amount_fix) {
						// 	$data_mut_flag = array(
						// 		'is_done' => 'Y',
						// 		'work_order_number' => $value['work_order_number']
						// 	);
						// 	$update_mut_flag = $this->M_order->update_mut_flag('dbo.TRBANK_STATEMENT', $data_mut_flag, $value['mutation_account']);
						// 	if ($update_mut_flag == FALSE) {
						// 		throw new Exception("Error Processing Request to Update Flag Mutation Data", 1);
						// 	}
						// } else {
						// 	$data_mut_flag = array(
						// 		'is_done' => 'N',
						// 		'work_order_number' => $value['work_order_number']
						// 	);
						// 	$update_mut_flag = $this->M_order->update_mut_flag('dbo.TRBANK_STATEMENT', $data_mut_flag, $value['mutation_account']);
						// 	if ($update_mut_flag == FALSE) {
						// 		throw new Exception("Error Processing Request to Update Flag Mutation Data", 1);
						// 	}
						// }

						$check_mut = $this->M_order->check_cash_mutation($value['mutation_account'], $value['work_order_number']);
						$check_max_sequence = $this->M_order->check_max_sequence($value['mutation_account'], $value['work_order_number'], $this->nik)->row()->total;
						$ids = ($check_max_sequence == '')?0:$check_max_sequence;
						// insert into cash mutation
						$data_mut = array(
							'transaction_id' => $value['mutation_account'],
							'work_order_number' => $value['work_order_number'],
							'pic_id' => $this->nik,
							'sequence_id' => $ids
						);

						if ($check_mut->num_rows() < 1) {
							if (!$this->db->insert('dbo.TRCASH_MUTATION', $data_mut)) {
								throw new Exception("Error Processing Request (Error 201)", 1);
							}
						}
					}

					// insert into approval operational cost
					$check_approval = $this->M_order->check_approval_param($id, 'D1009');
					if ($check_approval->num_rows() < 1) {
						$data_approval = array(
							'transaction_number' => $id,
							'document_id' => 'D1009',
							'revision_number' => '0',
							'company_id' => $code_cmpy,
							'request_approval_date' => $date,
							'approval_status' => 'N'
						);
						if (!$this->db->insert("dbo.TRAPPROVAL_STATUS_TRANSACTION", $data_approval)) {
							throw new Exception("Error Processing Request to Entry Approval Operational Cost", 1);
						}
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully inserted operational cost (Rincian Biaya)!");
						$this->db->trans_commit();
						redirect('Order/view_operational_cost');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('failed', $e->getMessage());
					$this->db->trans_rollback();
					redirect(current_url());
				}
			}
		}
	}

	function view_operational_cost()
	{
		// $this->load->helper('comman_helper');
		// $check_mut = $this->M_order->check_cash_mutation('MT0000002', '160001');
		// pr($check_mut->num_rows());
		$data['data_pic'] = $this->M_order->get_pic_opr()->result();
		$this->load->view('orders/v_indexoperationalcost', $data);
	}

	function detail_operational_cost()
	{
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		$trx_operational = $this->uri->segment(3);
		$work_order_number = $this->uri->segment(4);
		$pic_id = $this->uri->segment(5);

		$data['pic_name'] = $this->M_order->get_name_pic($pic_id)->row()->Nm_lengkap;
		$data['customer_name'] = $this->M_order->get_wo($work_order_number)->row()->CUSTOMER_NAME;
		$data['status'] = $this->M_order->get_data_operational($trx_operational)->row()->STATUS;
		$data['opr_date'] = $this->M_order->get_data_operational($trx_operational)->row()->OPR_DATE;
		$data['voucher_number'] = $this->M_order->get_data_operational($trx_operational)->row()->VOUCHER_NUMBER;
		$data['data_detail'] = $this->M_order->get_data_operational_detail($trx_operational)->result();
		$data['data_nonreim'] = $this->M_order->get_data_nonreim_opr($trx_operational)->result();

		$this->load->view('orders/v_detailoperational', $data);
	}

	function print_operational_cost()
	{
		$this->load->helper('comman_helper');
		$trx_operational = $this->uri->segment(3);

		$data['trx_operational'] = $trx_operational;
		$work_order_number = $this->M_order->get_data_operational($trx_operational)->row()->WORK_ORDER_NUMBER;
		$data['work_order_number'] = $work_order_number;
		$pic_id = $this->M_order->get_data_operational($trx_operational)->row()->PIC_ID;
		$data['opr_date'] = $this->M_order->get_data_operational($trx_operational)->row()->OPR_DATE;
		$data['opr_date2'] = $this->M_order->get_data_operational($trx_operational)->row()->OPR_DATE2;
		$data['pic_name'] = $this->M_order->get_name_pic($pic_id)->row()->Nm_lengkap;
		$data['customer_name'] = $this->M_order->get_wo($work_order_number)->row()->CUSTOMER_NAME;
		$data['data_reimbursement'] = $this->M_order->get_data_reim_opr($trx_operational)->result();
		$data['data_nonreim'] = $this->M_order->get_data_nonreim_opr($trx_operational)->result();
		// pr($data['data_nonreim']);
		$data['total_reim'] = $this->M_order->get_total_reim($trx_operational)->row()->total;
		// pr($data['total_reim']);
		$data['total_nonreim'] = $this->M_order->get_total_nonreim($trx_operational)->row()->total;

		$nik_level1 = $this->M_order->get_data_level($trx_operational, 'D1009')->row()->LEVEL1_APPROVAL_USER_ID;
		// $data['approval_name'] = $this->M_order->get_user_nik($nik_level1)->row()->Nm_lengkap;
		// $data['approval_date'] = $this->M_order->get_data_level($trx_operational, 'D1009')->row()->LEVEL1_APPROVAL_DATE;

		$html = $this->load->view('reports/r_operationalcost', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		// $pdf = new pdf();
		// $pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		$pdf->SetFooter('&emsp;&emsp;*) Printed by System ||');
		// $pdf->SetWatermarkText('DRAFT', 1, array(10,10), array(500,20));
		// $pdf->watermarkTextAlpha = 0.1;
		// $pdf->watermark_font = 'DejaVuSansCondensed';
		// $pdf->showWatermarkText = true;
		$pdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        0, // margin_left
        0, // margin right
       20, // margin top
       20, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('DeliveryOrder.pdf', 'I');
	}

	function edit_operational_cost()
	{
		$this->load->helper('comman_helper');
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;
		$trx_operational = $this->uri->segment(3);
		$work_order_number = $this->uri->segment(4);
		$pic_id = $this->uri->segment(5);

		$data['trx_operational'] = $trx_operational;
		$data['work_order_number'] = $work_order_number;
		$data['pic_id'] = $pic_id;
		$data['pic_name'] = $this->M_order->get_name_pic($pic_id)->row()->Nm_lengkap;
		$data['customer_name'] = $this->M_order->get_wo($work_order_number)->row()->CUSTOMER_NAME;
		// $data['data_detail'] = $this->M_order->get_detail_opr2($code_cmpy, $work_order_number, $pic_id)->result();
		$data['data_detail'] = $this->M_order->get_data_operational_detail($trx_operational)->result();
		// pr($data['data_detail']);
		$rek_pic = $this->M_order->get_rek_pic($pic_id)->row()->BANK_ID;
		$data['data_mutation'] = $this->M_order->get_data_mutation3($rek_pic)->result();

		$this->form_validation->set_rules('opr[][]', 'Operational Cost', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	$opr = $this->input->post('opr');
        	$temp = $this->input->post('temp');
			$trx_operational = $this->input->post('trx_operational');
			$work_order_number = $this->input->post('work_order_number');
			$status_pic = "";
			// check pic
			if ($this->nik != $pic_id) {
				$status_pic = "error";
			} else {
				$status_pic = "no error";
			}
        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_editoperational', $data);
			} elseif ($status_pic == "error") {
				$data['pic_error'] = "error";
				$this->load->view('orders/v_editoperational', $data);
			} else {
				$pic_id = $this->input->post('pic_id');
				// pr($opr);
				$this->db->trans_begin();
				try {
					// change all temporary bank mutation to status 'N'
					foreach ($temp as $key => $value) {
						$data_mut_flags = array(
								'is_done' => 'N',
								'work_order_number' => ''
						);
						$update_mut_flags = $this->M_order->update_mut_flag('dbo.TRBANK_STATEMENT', $data_mut_flags, $value['transaction_id']);
						if ($update_mut_flags == FALSE) {
							throw new Exception("Error Processing Request to Update Flag Mutation Data (201)", 1);
						}
					}

					// update data rincian / actual cost from cash request
					foreach ($opr as $value) {
						// amount fix
						$amount_fix = str_replace(',', '', $value['actual_amount']);

						// update data operational
						$update_opera = array(
							'cost_actual_amount' => $amount_fix,
							'transaction_id' => $value['mutation_account']
						);

						$edit_opera = $this->M_order->edit_opera('dbo.TROPERATIONAL_DETAIL', $update_opera, $value['trx_operational'], $value['work_order_number'], $value['container_number'], $value['cost_id'], $value['sequence_id']);
						if ($edit_opera == FALSE) {
							throw new Exception("Error Processing Request to Edit Operational Detail", 1);
						}

						$data_mut_flag = array(
							'is_done' => 'Y'
						);
						$update_mut_flag = $this->M_order->update_mut_flag('dbo.TRBANK_STATEMENT', $data_mut_flag, $value['mutation_account']);
						if ($update_mut_flag == FALSE) {
							throw new Exception("Error Processing Request to Update Flag Mutation Data", 1);
						}

						$check_mut = $this->M_order->check_cash_mutation($value['mutation_account'], $value['work_order_number']);
						// insert into cash mutation
						$data_mut = array(
							'transaction_id' => $value['mutation_account'],
							'work_order_number' => $value['work_order_number']
						);

						if ($check_mut->num_rows() < 1) {
							if (!$this->db->insert('dbo.TRCASH_MUTATION', $data_mut)) {
								throw new Exception("Error Processing Request (Error 201)", 1);
							}
						}
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request", 1);
					} else {
						$this->session->set_flashdata('success', "Successfully updated operational cost (Rincian Biaya)!");
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

	function posting_operational_cost()
	{
		$this->load->helper('comman_helper');
		$date = date('Y-m-d H:i:s');
		$operational_number = $this->uri->segment(3);
		$wo_number = $this->uri->segment(4);
		$niks = $this->uri->segment(5);

		$data_opr_header = $this->M_order->get_data_operational($operational_number);
		$data_operational = $this->M_order->get_data_operational_detail($operational_number)->result();
		$data_description_vou = $this->M_order->get_data_description_vou('OPERATIONAL_COST');
		// pr($data_description_vou->row());

		$pic_receiver = $this->M_order->get_name_nik($this->nik)->row()->Nm_lengkap;

		// get company code
		$company_code = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
		// pr($company_code);
		// get odbc
		$odbc = $this->M_order->get_odbc($company_code)->row()->EpicorODBC;

		// get table from odbc
		$table_name = substr($odbc, 0, 4);

		$get_voucher = $this->M_order->get_voucher_code($table_name, $company_code);
		
		// combine voucher number
		$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

		$arr_wo = array();

		foreach ($data_operational as $key => $value) {
			array_push($arr_wo, $value->WORK_ORDER_NUMBER);
		}

		$wo_unik = array_unique($arr_wo);

		$customer = $this->M_order->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

		$arr_imp = array();
		foreach ($wo_unik as $value) {
			$temp = $this->M_order->get_data_wo2($value)->row()->TRADE_ID;
			array_push($arr_imp, $temp);
		}

		$wo_fix = implode(", ", $wo_unik);

		$date_det = date('d.m.y');

		$detail = "WO " . $wo_fix . " " . $date_det . " " . $customer;

		// posting voucher
		// // voucher out
		$data_vou_out = array(
			'vou_no' => $voucher_number_out,
			'tipe_prof' => $data_description_vou->row()->TIPE_PROF,
			'flow' => $data_description_vou->row()->FLOW,
			'ref_no' => $operational_number,
			'appl_date' => $date,
			'dept' => $data_description_vou->row()->DEPT_BBN,
			'pembayaran' => $data_description_vou->row()->PEMBAYARAN,
			'vc_code_old' => $data_description_vou->row()->VC_CODE_OLD,
			'vc_code' =>$data_description_vou->row()->VC_CODE,
			'kepada' => $pic_receiver,
			'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
			'curr' => $data_opr_header->row()->CURRENCY,
			'keperluan' => $detail,
			'beban' => $customer,
			'entry_by' => $this->nik,
			'entry_date' => $date
		);

		$this->M_order->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

		// update voucher operational to table TROPERATIONAL HEADER
		$data_update_opr = array(
			'voucher_number' => $voucher_number_out
		);
		$this->M_order->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

		$seq = $get_voucher->row()->seq_no + 1;

		// update voucher number
		$update_voucher = array(
			'seq_no' => $seq
		);
		$this->M_order->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');
		$no_vou = 1;
		foreach ($data_operational as $key => $value) {
			
			$check_cost_share = $this->M_order->check_cost_share($value->COST_ID)->row()->COST_SHARE;
			if ($check_cost_share == 'N') {
				$gl_account = $this->M_order->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

				// detail vou for operational detail
				$detail_opr = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME;

				if ($gl_account != "" || $gl_account != NULL) {
					$data_vou_det_out = array(
						'vou_no' => $voucher_number_out,
						'seq_no' => $no_vou,
						'kode_ves' => $data_description_vou->row()->KODE_VES,
						'dept_bbn' => $data_description_vou->row()->DEPT_BBN,
						'dept_kor' => $data_description_vou->row()->DEPT_KOR,
						'acc_code' => $gl_account,
						'curr' => $value->COST_CURRENCY,
						'total' => $value->COST_ACTUAL_AMOUNT,
						'description' => $detail_opr,
						'entry_by' => $this->nik,
						'entry_date' => $date,
					);
					$this->M_order->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);
					
				}
			} elseif ($check_cost_share == 'Y') {
				// get truck and chassis number
				$truck_number = $this->M_order->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
				$chassis_number = $this->M_order->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

				// get percentase truck and chassis
				$percent_truck = $this->M_order->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
				$percent_chassis = $this->M_order->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

				// detail vou for operational detail
				$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
				$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

				// get gl account truck
				$gl_account_truck = $this->M_order->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
				$gl_account_chassis = $this->M_order->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

				// cost truck
				$cost_truck = $value->COST_ACTUAL_AMOUNT * ($percent_truck / 100);
				$cost_chassis = $value->COST_ACTUAL_AMOUNT * ($percent_chassis / 100);

				$data_vou_det_truck = array(
					'vou_no' => $voucher_number_out,
					'seq_no' => $no_vou,
					'kode_ves' => $data_description_vou->row()->KODE_VES,
					'dept_bbn' => $data_description_vou->row()->DEPT_BBN,
					'dept_kor' => $data_description_vou->row()->DEPT_KOR,
					'acc_code' => $gl_account_truck,
					'curr' => $value->COST_CURRENCY,
					'total' => $cost_truck,
					'description' => $detail_opr_truck,
					'entry_by' => $this->nik,
					'entry_date' => $date,
				);
				$this->M_order->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);
				$no_vou++;

				$data_vou_det_chassis = array(
					'vou_no' => $voucher_number_out,
					'seq_no' => $no_vou,
					'kode_ves' => $data_description_vou->row()->KODE_VES,
					'dept_bbn' => $data_description_vou->row()->DEPT_BBN,
					'dept_kor' => $data_description_vou->row()->DEPT_KOR,
					'acc_code' => $gl_account_chassis,
					'curr' => $value->COST_CURRENCY,
					'total' => $cost_chassis,
					'description' => $detail_opr_chassis,
					'entry_by' => $this->nik,
					'entry_date' => $date,
				);
				$this->M_order->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);
				

				// if ($gl_account_truck != "" || $gl_account_truck != NULL) {
				// 	$data_vou_det_truck = array(
				// 		'vou_no' => $voucher_number_out,
				// 		'seq_no' => $no_vou,
				// 		'kode_ves' => $data_description_vou->row()->KODE_VES,
				// 		'dept_bbn' => $data_description_vou->row()->DEPT_BBN,
				// 		'dept_kor' => $data_description_vou->row()->DEPT_KOR,
				// 		'acc_code' => $gl_account_truck,
				// 		'curr' => $value->COST_CURRENCY,
				// 		'total' => $cost_truck,
				// 		'description' => $detail_opr_truck,
				// 		'entry_by' => $this->nik,
				// 		'entry_date' => $date,
				// 	);
				// 	$this->M_order->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);
				// 	$no_vou++;
				// }

				// if ($gl_account_chassis != "" || $gl_account_chassis != NULL) {
				// 	$data_vou_det_chassis = array(
				// 		'vou_no' => $voucher_number_out,
				// 		'seq_no' => $no_vou,
				// 		'kode_ves' => $data_description_vou->row()->KODE_VES,
				// 		'dept_bbn' => $data_description_vou->row()->DEPT_BBN,
				// 		'dept_kor' => $data_description_vou->row()->DEPT_KOR,
				// 		'acc_code' => $gl_account_chassis,
				// 		'curr' => $value->COST_CURRENCY,
				// 		'total' => $cost_chassis,
				// 		'description' => $detail_opr_chassis,
				// 		'entry_by' => $this->nik,
				// 		'entry_date' => $date,
				// 	);
				// 	$this->M_order->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);
				// 	$no_vou++;
				// }
			}
			$no_vou++;
		}

		$this->session->set_flashdata('success_posting', 'Successfully Posting Operational Cost to E-Voucher');

		redirect('Order/detail_operational_cost/'.$operational_number.'/'.$wo_number.'/'.$niks);
		
	}

	function view_entry_invoice()
	{
		$data['data_wo'] = $this->M_order->get_data_wo()->result();
		$data_wo = $this->M_order->get_data_wo_inv()->result();
		$data_qty = $this->M_order->get_qty_container()->result();
		// $this->load->helper('comman_helper');
		// pr($data_qty);
		$result_wo = array();

		// declare baru
		foreach ($data_wo as $key => $value) {
			$temp_data['WORK_ORDER_NUMBER'] = $value->WORK_ORDER_NUMBER;
			$temp_data['DATE_RECEIVED'] = $value->DATE_RECEIVED;
			$temp_data['NAME_RECEIVED'] = $value->NAME_RECEIVED;
			$temp_data['DATE_REQUEST'] = $value->DATE_REQUEST;
			$temp_data['NAME_REQUEST'] = $value->NAME_REQUEST;
			$temp_data['WORK_ORDER_NUMBER'] = $value->WORK_ORDER_NUMBER;
			$temp_data['CUSTOMER_NAME'] = $value->CUSTOMER_NAME;
			$temp_data['WORK_ORDER_DATE'] = $value->WORK_ORDER_DATE;
			$temp_data['TRADE'] = $value->TRADE;
			$temp_data['VESSEL_NAME'] = $value->VESSEL_NAME;
			$temp_data['REGISTER_NUMBER_PIB_PEB'] = $value->REGISTER_NUMBER_PIB_PEB;
			$temp_data['REGISTER_NUMBER_SPPB_SPEB'] = $value->REGISTER_NUMBER_SPPB_SPEB;

			// sincronize with quantity
			foreach ($data_qty as $key1 => $value1) {
				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '20') {
					$temp_data['TOTAL_20'] = $value1->TOTAL;
					// $temp_data['TOTAL_20'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '40') {
					$temp_data['TOTAL_40'] = $value1->TOTAL;
					// $temp_data['TOTAL_40'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '4H') {
					$temp_data['TOTAL_4H'] = $value1->TOTAL;
					// $temp_data['TOTAL_4H'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '45') {
					$temp_data['TOTAL_45'] = $value1->TOTAL;
					// $temp_data['TOTAL_45'] = "ada";
				}
			}

			if (empty($temp_data['TOTAL_20'])) {
				$temp_data['TOTAL_20'] = 0;
				// $temp_data['TOTAL_20'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_40'])) {
				$temp_data['TOTAL_40'] = 0;
				// $temp_data['TOTAL_40'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_4H'])) {
				$temp_data['TOTAL_4H'] = 0;
				// $temp_data['TOTAL_4H'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_45'])) {
				$temp_data['TOTAL_45'] = 0;
				// $temp_data['TOTAL_45'] = "gak ada";
			}


			$result_wo[] = $temp_data;
		}

		foreach ($result_wo as $key => $value) {
			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "20")->result()) {
				unset($result_wo[$key]['TOTAL_20']);
			}

			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "40")->result()) {
				unset($result_wo[$key]['TOTAL_40']);
			}

			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "4H")->result()) {
				unset($result_wo[$key]['TOTAL_4H']);
			}

			if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "45")->result()) {
				unset($result_wo[$key]['TOTAL_45']);
			}

			// ---------------------------------------------------------------
			if (!isset($result_wo[$key]['TOTAL_20'])) {
				$result_wo[$key]['TOTAL_20'] = 0;
			}

			if (!isset($result_wo[$key]['TOTAL_40'])) {
				$result_wo[$key]['TOTAL_40'] = 0;
			}

			if (!isset($result_wo[$key]['TOTAL_4H'])) {
				$result_wo[$key]['TOTAL_4H'] = 0;
			}

			if (!isset($result_wo[$key]['TOTAL_45'])) {
				$result_wo[$key]['TOTAL_45'] = 0;
			}
		}

		// $this->load->helper('comman_helper');
		// pr($result_wo);
		$data['result_wo'] = $result_wo;
		$this->load->view('orders/v_viewentryinvoice', $data);
	}

	function entry_invoice()
	{
		$this->load->helper('comman_helper');
		$this->load->helper('duplicatearray_helper');
		$wo_check = $this->input->post('wo_check');
		// pr(min($wo_check));
		$wo_min = min($wo_check);

		$data['data_wo'] = $wo_check;
		$data['wo_date'] = $this->M_order->get_work_detail($wo_min)->row()->WORK_DATE;

		// pr($data['wo_date']);

		// generate invoice number
		$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		$temp_id = $this->M_order->get_max_invoice()->row()->id;
		$year_now = date('y');
		$date = date('Y-m-d H:i:s');
		$temp_id_tem = $temp_id + 1;

		// $temp_subs = substr($temp_id, 9, 1);
		// TRX0000009
		// TRX0000001

		if ($temp_id != NULL) {
			if ($temp_id_tem < 10) {
				$temp_subs = substr($temp_id, 6, 1);
				$temp_subs++;
				$id = "INV" . $year_now . "000000" . $temp_id_tem;
			} elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
				$temp_subs = substr($temp_id, 5, 2);
				$temp_subs++;
				$id = "INV" . $year_now .  "00000" . $temp_id_tem;
			} elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
				$temp_subs = substr($temp_id, 4, 3);
				$temp_subs++;
				$id = "INV" . $year_now .  "0000" . $temp_id_tem;
			} elseif ($temp_id_tem == 1000 || $temp_id_tem < 10000) {
				$temp_subs = substr($temp_id, 3, 4);
				$temp_subs++;
				$id = "INV" . $year_now .  "000" . $temp_id_tem;
			} elseif ($temp_id_tem == 10000 || $temp_id_tem < 100000) {
				$temp_subs = substr($temp_id, 2, 5);
				$temp_subs++;
				$id = "INV" . $year_now .  "00" . $temp_id_tem;
			} elseif ($temp_id_tem == 100000 || $temp_id_tem < 1000000) {
				$temp_subs = substr($temp_id, 1, 6);
				$temp_subs++;
				$id = "INV" . $year_now .  "0" . $temp_id_tem;
			} else {
				$id = "INV" . $year_now .  ($temp_id+1);
			}
		} else {
			// echo "kosong";
			$id = "INV" . $year_now . "0000001";
		}

		$data['invoice_number'] = $id;
		// pr($id);
		
		// declare variable with empty value
		$data['error_cash'] = "";
		$data['error_var'] = "";
		$data['error_customer'] = "";
		$data['error_customer_var'] = "";
		$data['customer_name'] = "";
		$data['error_vessel'] = "";
		$data['error_vessel_var'] = "";
		$data['vessel_name'] = "";
		$data['error_voyage'] = "";
		$data['error_voyage_var'] = "";
		$data['voyage_number'] = "";
		$data['eta'] = ""; 
		$data['etd'] = "";
		$data['pol_name'] = "";
		$data['pod_name'] = "";
		$status_customer = "";
		$status_vessel = "";
		$status_voyage = "";

		$arr_customer = array();
		$arr_vessel = array();
		$arr_voyage = array();
		$arr_shipper = array();
		$arr_consignee = array();

		foreach ($wo_check as $value) {
			// check if all cash request has been finished
			$check_cash = $this->M_order->check_cashs($value);
			if ($check_cash->num_rows() > 0 ) {
				$data['error_cash'] = "Invoice could not created, this Work Orders did not finished!";
				$data['error_var'] = "error";
			}

			// get data customer
			$customer_id = $this->M_order->get_just_wo_data($value)->row()->CUSTOMER_ID;
			array_push($arr_customer, $customer_id);

			// get data shipper and consignee
			$shipper_temp = $this->M_order->get_just_wo_data($value)->row()->SHIPPER;
			$consignee_temp = $this->M_order->get_just_wo_data($value)->row()->CONSIGNEE;
			array_push($arr_shipper, $shipper_temp);
			array_push($arr_consignee, $consignee_temp);


			// get data vessel
			$vessel_id = $this->M_order->get_just_wo_data($value)->row()->VESSEL_ID;
			array_push($arr_vessel, $vessel_id);

			// get data voyage
			$voyage = $this->M_order->get_just_wo_data($value)->row()->VOYAGE_NUMBER;
			array_push($arr_voyage, $voyage);
		}

		// check customer duplicate
		$customer_unik = array_unique($arr_customer);

		// check shipper and consignee duplicate
		$shipper_unik = array_unique($arr_shipper);
		$consignee_unik = array_unique($arr_consignee);

		$data['shipper'] = implode(", ", $shipper_unik);
		$data['consignee'] = implode(", ", $consignee_unik);

		// for customer alert
		if (count($customer_unik) > 1) {
			$data['error_customer'] = "Invoice could not created, Different Customers!";
			$data['error_customer_var'] = "error";
			$data['customer_name'] = "";
			$status_customer = "error";
		} else {
			$data['customer_name'] = $this->M_order->get_customer_detail($customer_unik[0])->row()->NAME;
			$status_customer = "no error";
		}

		// check vessel duplicate
		$vessel_unik = array_unique($arr_vessel);
		// for vessel alert
		if (count($vessel_unik) > 1) {
			$data['error_vessel'] = "Invoice could not created, Vessel did not same!";
			$data['error_vessel_var'] = "error";
			$data['vessel_name'] = "";
			$data['eta'] = ""; 
			$data['etd'] = "";
			$data['pol_name'] = "";
			$data['pod_name'] = "";
			$status_vessel = "error";
		} else {
			$data['vessel_name'] = $this->M_order->get_vessel_detail($vessel_unik[0])->row()->VESSEL_NAME;
			$data['eta'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->ETA_DATE;
			$data['etd'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->ETD_DATE;
			$data['pol_name'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->POL_NAME;
			$data['pod_name'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->POD_NAME;
			$status_vessel = "no error";
		}

		// check voyage duplicate
		$voyage_unik = array_unique($arr_voyage);

		if (count($voyage_unik) > 1) {
			$data['error_voyage'] = "Invoice could not created, Voyage Number did not same!";
			$data['error_voyage_var'] = "error";
			$data['voyage_number'] = "";
			$status_voyage = "error";
		} else {
			$data['voyage_number'] = $voyage_unik[0];
			$status_voyage = "no error";
		}

		$temp_wo_data = implode(", ", $wo_check);
		$data['work_order_number'] = $temp_wo_data;
		
		$data_selling = array();
		$data_main = array();
		$data_cost = array();
		
		if ($status_customer != "error" && $status_vessel != "error" && $status_voyage != "error") {
			foreach ($wo_check as $value) {
				// data selling and main (invoice selling and reimbursement cost) and additional cost
				$costs = $this->M_order->get_cost_invoice2($value)->result();
				$selling_inv = $this->M_order->get_selling_invoice($value)->result();
				$selling_inv_additional = $this->M_order->get_selling_additional_invoice($value)->result();

				foreach ($selling_inv as $key1 => $value1) {
					$temp_inv['WORK_ORDER_NUMBER'] = $value1->WORK_ORDER_NUMBER;
					$temp_inv['CONTAINER_NUMBER'] = $value1->CONTAINER_NUMBER;
					if ($value1->KIND == "TARIFF_AMOUNT") {
						$temp_inv['KIND'] = "TRUCKING";
						$temp_inv['DESCRIPTION_TYPE'] = "SS01";
					} elseif ($value1->KIND == "CUSTOMS_LANE_AMOUNT") {
						$temp_inv['KIND'] = "CUSTOMS CLEARANCE";
						$temp_inv['DESCRIPTION_TYPE'] = "SS02";
					}
					$temp_inv['CURRENCY'] = $value1->TARIFF_CURRENCY;
					$temp_inv['AMOUNT'] = $value1->AMOUNT;
					$temp_inv['TYPE'] = "HSP CHARGE";
					$temp_inv['DESCRIPTION'] = "SELLING";
					$temp_inv['CH_KIND'] = "SEL";
					$temp_inv['CH_TYPE'] = "CRG";

					$data_selling[] = $temp_inv;

					$data_main[] = $temp_inv;

					// echo $value1->CONTAINER_NUMBER;
				}

				foreach ($costs as $key2 => $value2) {
					$temp_co['WORK_ORDER_NUMBER'] = $value2->WORK_ORDER_NUMBER;
					$temp_co['CONTAINER_NUMBER'] = $value2->CONTAINER_NUMBER;
					$temp_co['DESCRIPTION'] = 'COST';
					$temp_co['KIND'] = $value2->COST_NAME;
					$temp_co['DESCRIPTION_TYPE'] = $value2->COST_ID;

					if ($value2->COST_TYPE_ID == 'NRM') {
						$temp_co['TYPE'] = "NON REIMBURSEMENT";
					} elseif ($value2->COST_TYPE_ID == 'REM') {
						$temp_co['TYPE'] = "REIMBURSEMENT";
					}
					$temp_co['TYPE_VAR'] = $value2->COST_TYPE_ID;
					$temp_co['CURRENCY'] = $value2->COST_CURRENCY;
					$temp_co['AMOUNT'] = $value2->COST_ACTUAL_AMOUNT;
					$temp_co['CH_KIND'] = "CST";
					$temp_co['CH_TYPE'] = "REM";

					$data_main[] = $temp_co;
				}

				// data additional selling
				foreach ($selling_inv_additional as $key4 => $value4) {
					$temp_inv_adt['WORK_ORDER_NUMBER'] = $value4->WORK_ORDER_NUMBER;
					$temp_inv_adt['CONTAINER_NUMBER'] = $value4->CONTAINER_NUMBER;
					$temp_inv_adt['KIND'] = $value4->SERVICE_NAME;
					$temp_inv_adt['DESCRIPTION_TYPE'] = $value4->SELLING_SERVICE_ID;
					$temp_inv_adt['CURRENCY'] = $value4->TARIFF_CURRENCY;
					$temp_inv_adt['AMOUNT'] = $value4->TARIFF_AMOUNT;
					$temp_inv_adt['TYPE'] = "HSP CHARGE";
					$temp_inv_adt['DESCRIPTION'] = "SELLING";
					$temp_inv_adt['CH_KIND'] = "SEL";
					$temp_inv_adt['CH_TYPE'] = "CRG";

					$data_selling[] = $temp_inv_adt;

					$data_main[] = $temp_inv_adt;
				}

				// data cost
				$temp_data_cost = $this->M_order->get_data_cash_request($value)->result();
				foreach ($temp_data_cost as $key3 => $value3) {
					$temp_cost_inv['WORK_ORDER_NUMBER'] = $value3->WORK_ORDER_NUMBER;
					$temp_cost_inv['CONTAINER_NUMBER'] = $value3->CONTAINER_NUMBER;
					$temp_cost_inv['COST_NAME'] = $value3->COST_NAME;
					$temp_cost_inv['COST_TYPE'] = $value3->COST_TYPE;
					$temp_cost_inv['COST_GROUP'] = $value3->COST_GROUP;
					$temp_cost_inv['COST_KIND'] = $value3->COST_KIND;
					$temp_cost_inv['COST_CURRENCY'] = $value3->COST_CURRENCY;
					$temp_cost_inv['COST_RECEIVED_AMOUNT'] = $value3->COST_RECEIVED_AMOUNT;
					$temp_cost_inv['COST_ACTUAL_AMOUNT'] = $value3->COST_ACTUAL_AMOUNT;
					$temp_cost_inv['COST_INVOICE_AMOUNT'] = $value3->COST_INVOICE_AMOUNT;
					$temp_cost_inv['IS_TRANSFERED'] = $value3->IS_TRANSFERED;
					$temp_cost_inv['IS_DONE'] = $value3->IS_DONE;

					$data_cost[] = $temp_cost_inv;
				}
			}
			// pr($data_main);
			$data['data_main'] = $data_main;
			$data['data_selling'] = $data_selling;
			$data['data_cost'] = $data_cost;
		} else {
			$data['data_main'] = "";
			$data['data_selling'] = "";
			$data['data_cost'] = "";
		}

		$this->load->view('orders/v_entryinvoice', $data);
	}

	function create_invoice()
	{
		$this->load->helper('comman_helper');
		$this->form_validation->set_rules('invoice_number', 'Invoice Number', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $invoice_number = $this->input->post('invoice_number');
        $invoice_date = $this->input->post('invoice_date');
    	$work_order_number = $this->input->post('work_order');
    	// $temp_invoice = $this->input->post('inv');
    	$invoi = $this->input->post('invoi');

    	$cmpy = $this->M_order->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_order->get_code($cmpy)->row()->COMPANY_ID;

		$date = date('Y-m-d H:i:s');

    	// pr($invoi);

		if ($this->form_validation->run() == false) {
			$this->load->view('orders/v_entryinvoice', $data);
		} 
		else {
			$this->db->trans_begin();
			try {
				foreach ($work_order_number as $value) {
					// cost reimbursement
					$cost_reim = $this->M_order->get_cash($value, 'REM')->result();
					foreach ($cost_reim as $key1 => $value1) {
						$sum_reim += $value1->COST_ACTUAL_AMOUNT;
					}

					// cost non reimbursement
					$cost_nonreim = $this->M_order->get_cash($value, 'NRM')->result();
					foreach ($cost_nonreim as $key2 => $value2) {
						$sum_nonreim += $value2->COST_ACTUAL_AMOUNT;
					}

					// update flag work order has been charged
					$data_inv_charged = array(
						'is_charged' => 'Y'
					);

					$update_charged_wo_inv = $this->M_order->update_charged_wo_inv($value, 'dbo.TRWORKORDER', $data_inv_charged);

					if ($update_charged_wo_inv == FALSE) {
						throw new Exception("Error Processing Request to Change Flag Charged Work Order", 1);
					}
				}

				foreach ($invoi as $value) {
					$sum_invoice += $value['amount'];
				}

				// entry invoice header
				$data_invoice = array(
					'invoice_number' => $invoice_number,
					'company_id' => $code_cmpy,
					'invoice_date' => $invoice_date,
					'total_non_reimbursement' => $sum_nonreim,
					'total_reimbursement' => $sum_reim,
					'total_invoice' => $sum_invoice,
					'user_id' => $this->nik,
					'user_date' => $date
				);

				if (!$this->db->insert('dbo.TRINVOICE', $data_invoice)) {
					throw new Exception("Error Processing Request to Entry Invoice Header", 1);
				}

				// entry invoice detail
				foreach ($invoi as $value) {
					$data_invoice_detail = array(
						'invoice_number' => $invoice_number,
						'company_id' => $code_cmpy,
						'work_order_number' => $value['work_order_number'],
						'container_number' => $value['container_number'],
						'charges_id' => $value['description_type'],
						'charges_type' => $value['ch_type'],
						'charges_group' => $value['ch_kind'],
						'charges_currency' => $value['currency'],
						'charges_amount' => $value['amount'],
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.TRINVOICE_DETAIL', $data_invoice_detail)) {
						throw new Exception("Error Processing Request to Entry Invoice Detail", 1);
					}
				}

				if ($this->db->trans_status === FALSE) {
					throw new Exception("Error Processing Request to Create Invoice", 1);
				} else {
					$this->session->set_flashdata('success', 'Successfully Create Invoice!');
					$this->db->trans_commit();
					redirect('Order/view_invoice');	
				}
			} catch (Exception $e) {
				$this->session->set_flashdata('failed', 'Failed to Create Invoice. Try Again!');
				$this->db->trans_rollback();
				redirect('Order/view_entry_invoice');
			}
		}
	}

	function view_invoice()
	{
		$this->load->helper('comman_helper');
		$data['data_invoice'] = $this->M_order->get_data_invoice()->result();
		$this->load->view('orders/v_viewinvoice', $data);
	}

	function detail_invoice()
	{
		$this->load->helper('comman_helper');
		$invoice_number = $this->uri->segment(3);
		$data['invoice_number'] = $invoice_number;

		$data_wo = $this->M_order->get_wo_inv($invoice_number)->result();

		// get customer name
		$arr_customer = array();
		foreach ($data_wo as $key => $value) {

			// get data customer
			$customer_id = $this->M_order->get_just_wo_data($value->WORK_ORDER_NUMBER)->row()->CUSTOMER_ID;
			array_push($arr_customer, $customer_id);
		}
		$customer_unique = array_unique($arr_customer);
		$data['customer_name'] = $this->M_order->get_customer_detail($customer_unique[0])->row()->NAME;

		// get company name
		$data['company_name'] = $this->M_order->get_data_invoice_for_det($invoice_number)->row()->COMPANY_NAME;

		// get data invoice detail
		$data_inv_det = $this->M_order->get_data_invoice_detail($invoice_number)->result();
		foreach ($data_inv_det as $key => $value) {
			$temp_detail['INVOICE_NUMBER'] = $value->INVOICE_NUMBER;
			$temp_detail['WORK_ORDER_NUMBER'] = $value->WORK_ORDER_NUMBER;
			$temp_detail['COMPANY_NAME'] = $value->COMPANY_NAME;
			$temp_detail['CONTAINER_NUMBER'] = $value->CONTAINER_NUMBER;
			$temp_detail['CHARGES_CURRENCY'] = $value->CHARGES_CURRENCY;
			$temp_detail['CHARGES_AMOUNT'] = $value->CHARGES_AMOUNT;
			$temp_detail['CHARGES_TYPE'] = $value->CHARGE_TYPE;
			$temp_detail['CHARGES_GROUP'] = $value->CHARGE_GROUP;

			// check charges id
			if ($value->CHARGES_GROUP == 'CST') {
				$temp_detail['CHARGES_NAME'] = $this->M_order->get_cost_inv($value->CHARGES_ID)->row()->COST_NAME;
			} elseif ($value->CHARGES_GROUP == 'SEL') {
				$temp_detail['CHARGES_NAME'] = $this->M_order->get_sell_inv($value->CHARGES_ID)->row()->SERVICE_NAME;
			}

			$result_inv_det[] = $temp_detail;
		}

		$data['data_det_inv'] = $result_inv_det;
		$this->load->view('orders/v_detailinvoice', $data);
	}

	function print_invoice()
	{
		$this->load->helper('comman_helper');
		$this->load->helper('spelling_helper');
		$invoice_number = $this->uri->segment(3);
		$data['invoice_number'] = $invoice_number;

		$data_wo = $this->M_order->get_wo_inv($invoice_number)->result();

		// $data['invoice_date'] = $this->M_order->get_invoice($invoice_number)->row()->INVOICE_DATE2;
		$inv_date = $this->M_order->get_invoice($invoice_number)->row()->INVOICE_DATE3;
		$fix_date_inv = date('F d, Y', strtotime($inv_date));
		$data['invoice_date'] = $fix_date_inv;

		$arr_wo = array();
		foreach ($data_wo as $key => $value) {
			array_push($arr_wo, $value->WORK_ORDER_NUMBER);
		}
		$data['wo_data'] = implode(", ", $arr_wo);
		$data_qty = $this->M_order->get_qty_container()->result();

		// get customer name
		$arr_customer = array();
		$arr_vessel = array();
		$arr_voyage = array();
		$arr_pib = array();
		$arr_bl = array();
		$arr_ref = array();
		$arr_shipper = array();
		$arr_consignee = array();

		foreach ($data_wo as $key => $value) {

			// get data customer
			$customer_id = $this->M_order->get_just_wo_data($value->WORK_ORDER_NUMBER)->row()->CUSTOMER_ID;
			array_push($arr_customer, $customer_id);

			// get data ref
			$ref = $this->M_order->get_just_wo_data($value->WORK_ORDER_NUMBER)->row()->REFERENCE_NUMBER;
			array_push($arr_ref, $ref);

			// get data vessel
			$vessel_id = $this->M_order->get_just_wo_data($value->WORK_ORDER_NUMBER)->row()->VESSEL_ID;
			array_push($arr_vessel, $vessel_id);

			// get data shipper and consignee
			$shipper_temp = $this->M_order->get_just_wo_data($value->WORK_ORDER_NUMBER)->row()->SHIPPER;
			$consignee_temp = $this->M_order->get_just_wo_data($value->WORK_ORDER_NUMBER)->row()->CONSIGNEE;
			array_push($arr_shipper, $shipper_temp);
			array_push($arr_consignee, $consignee_temp);

			// get data voyage
			$voyage = $this->M_order->get_just_wo_data($value->WORK_ORDER_NUMBER)->row()->VOYAGE_NUMBER;
			array_push($arr_voyage, $voyage);

			// get data pib/peb
			$pib_no = $this->M_order->get_custom_wo($value->WORK_ORDER_NUMBER)->row()->REGISTER_NUMBER_PIB_PEB;
			array_push($arr_pib, $pib_no);

			// get data BL
			$temp_bl = $this->M_order->get_container_wo($value->WORK_ORDER_NUMBER)->result();
			$temp_bl_all = $this->M_order->get_container_wo($value->WORK_ORDER_NUMBER);

			if ($temp_bl_all->num_rows() > 1) {
				foreach ($temp_bl as $keys => $values) {
					array_push($arr_bl, $values->BL_NUMBER);
				}
			} else {
				array_push($arr_bl, $temp_bl_all->row()->BL_NUMBER);
			}

			$temp_data['WORK_ORDER_NUMBER'] = $value->WORK_ORDER_NUMBER;

			// sincronize with quantity
			foreach ($data_qty as $key1 => $value1) {
				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '20') {
					$temp_data['TOTAL_20'] = $value1->TOTAL;
					// $temp_data['TOTAL_20'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '40') {
					$temp_data['TOTAL_40'] = $value1->TOTAL;
					// $temp_data['TOTAL_40'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '4H') {
					$temp_data['TOTAL_4H'] = $value1->TOTAL;
					// $temp_data['TOTAL_4H'] = "ada";
				}

				if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER && $value1->CONTAINER_SIZE_ID == '45') {
					$temp_data['TOTAL_45'] = $value1->TOTAL;
					// $temp_data['TOTAL_45'] = "ada";
				}
			}

			if (empty($temp_data['TOTAL_20'])) {
				$temp_data['TOTAL_20'] = 0;
				// $temp_data['TOTAL_20'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_40'])) {
				$temp_data['TOTAL_40'] = 0;
				// $temp_data['TOTAL_40'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_4H'])) {
				$temp_data['TOTAL_4H'] = 0;
				// $temp_data['TOTAL_4H'] = "gak ada";
			}

			if (empty($temp_data['TOTAL_45'])) {
				$temp_data['TOTAL_45'] = 0;
				// $temp_data['TOTAL_45'] = "gak ada";
			}


			$result_wo[] = $temp_data;

			foreach ($result_wo as $key => $value) {
				if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "20")->result()) {
					unset($result_wo[$key]['TOTAL_20']);
				}

				if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "40")->result()) {
					unset($result_wo[$key]['TOTAL_40']);
				}

				if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "4H")->result()) {
					unset($result_wo[$key]['TOTAL_4H']);
				}

				if (!$this->M_order->check_wo_container($value['WORK_ORDER_NUMBER'], "45")->result()) {
					unset($result_wo[$key]['TOTAL_45']);
				}

				// ---------------------------------------------------------------
				if (!isset($result_wo[$key]['TOTAL_20'])) {
					$result_wo[$key]['TOTAL_20'] = 0;
				}

				if (!isset($result_wo[$key]['TOTAL_40'])) {
					$result_wo[$key]['TOTAL_40'] = 0;
				}

				if (!isset($result_wo[$key]['TOTAL_4H'])) {
					$result_wo[$key]['TOTAL_4H'] = 0;
				}

				if (!isset($result_wo[$key]['TOTAL_45'])) {
					$result_wo[$key]['TOTAL_45'] = 0;
				}
			}
		}

		// check shipper and consignee duplicate
		$shipper_unik = array_unique($arr_shipper);
		$consignee_unik = array_unique($arr_consignee);
		$data['shipper'] = implode(", ", $shipper_unik);
		$data['consignee'] = implode(", ", $consignee_unik);

		$data['data_bl'] = implode(", ", $arr_bl);

		$count_20 = 0;
		$count_40 = 0;
		$count_4H = 0;
		$count_45 = 0;
		// count total container
		foreach ($result_wo as $key => $value) {
			if ($value['TOTAL_20'] > 0) {
				$count_20++;
			} elseif ($value['TOTAL_40'] > 0) {
				$count_40++;
			} elseif ($value['TOTAL_4H'] > 0) {
				$count_4H++;
			} elseif ($value['TOTAL_45'] > 0) {
				$count_45++;
			}
		}

		$data['pib_no'] = implode(", ", $arr_pib);

		$data['count_20'] = $count_20;
		$data['count_40'] = $count_40;
		$data['count_4H'] = $count_4H;
		$data['count_45'] = $count_45;

		$cmp_count20 = $count_20 . "x20";
		$cmp_count40 = $count_40 . "x40";
		$cmp_count4H = $count_4H . "x4H";
		$cmp_count45 = $count_45 . "x45";
		$arr_total_vol = array();

		if ($count_20 > 0) {
			array_push($arr_total_vol, $cmp_count20);
		}

		if ($count_40 > 0) {
			array_push($arr_total_vol, $cmp_count40);
		}

		if ($count_4H > 0) {
			array_push($arr_total_vol, $cmp_count4H);
		}

		if ($count_45 > 0) {
			array_push($arr_total_vol, $cmp_count45);
		}

		$result_vol = implode(", ", $arr_total_vol);
		$data['result_vol'] = $result_vol;

		$customer_unique = array_unique($arr_customer);
		$ref_unik = array_unique($arr_ref);
		$voyage_unik = array_unique($arr_voyage);
		$vessel_unik = array_unique($arr_vessel);
		$data['customer_name'] = $this->M_order->get_customer_detail($customer_unique[0])->row()->NAME;
		$address_1 = $this->M_order->get_customer_address($customer_unique[0])->row()->ADDRESS_1;
		$address_2 = $this->M_order->get_customer_address($customer_unique[0])->row()->ADDRESS_2;
		$data['customer_address'] = $address_1 . " " . $address_2;

		// get company name
		$data['company_name'] = $this->M_order->get_data_invoice_for_det($invoice_number)->row()->COMPANY_NAME;

		$data['ref_no'] = implode(", ", $ref_unik);
		
		$data['vessel_name'] = $this->M_order->get_vessel_detail($vessel_unik[0])->row()->VESSEL_NAME;
		$data['eta'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->ETA_DATE;
		$data['etd'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->ETD_DATE;
		$data['pol_name'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->POL_NAME;
		$data['pod_name'] = $this->M_order->get_data_wo_eta($vessel_unik[0])->row()->POD_NAME;
		$data['voyage_number'] = $voyage_unik[0];

		$data['data_rem'] = $this->M_order->get_data_rem_inv($invoice_number)->result();
		$data['total_rem'] = $this->M_order->sum_data_rem_inv($invoice_number)->row()->AMOUNT;
		$data['data_crg'] = $this->M_order->get_data_crg_inv($invoice_number)->result();
		$data['total_crg'] = $this->M_order->sum_data_crg_inv($invoice_number)->row()->AMOUNT;
		$data['total_inv'] = $this->M_order->get_data_invoice_for_det($invoice_number)->row()->TOTAL_INVOICE;
		$temp_total = $this->M_order->get_data_invoice_for_det($invoice_number)->row()->TOTAL_INVOICE;
		$spell_total = convertNumberToWord($temp_total);
		$data['spell_total'] = $spell_total;
		
		$html = $this->load->view('reports/r_invoice', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		
		// $pdf->AddPage('', // L - landscape, P - portrait 
  //       '', '', '', '',
  //       0, // margin_left
  //       0, // margin right
  //      45, // margin top
  //      40, // margin bottom
  //       0, // margin header
  //       0); // margin footer
		
		$pdf->WriteHTML($html);
		$pdf->Output('Invoice.pdf', 'I');
	}

	function upload_mutasi()
	{	
		$this->load->helper('comman_helper');
		// MT0000009
		// MT0000010
		$temp_id = $this->M_order->get_max_mutation()->row()->id;
		$temp_id_tem = $temp_id + 1;
		// pr($temp_id_tem);
		if ($temp_id != NULL) {
			if ($temp_id_tem < 10) {
				$temp_subs = substr($temp_id, 6, 1);
				$temp_subs++;
				$id = "MT" . "000000" . $temp_subs;
			} elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
				// $temp_subs = substr($temp_id, 5, 2);
				// $temp_subs++;
				// pr($temp_subs);
				$id = "MT" .  "00000" . $temp_id_tem;
			} elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
				$temp_subs = substr($temp_id, 4, 3);
				$temp_subs++;
				$id = "MT" .  "0000" . $temp_id_tem;
			} elseif ($temp_id_tem == 1000 || $temp_id_tem < 10000) {
				$temp_subs = substr($temp_id, 3, 4);
				$temp_subs++;
				$id = "MT" .  "000" . $temp_id_tem;
			} elseif ($temp_id_tem == 10000 || $temp_id_tem < 100000) {
				$temp_subs = substr($temp_id, 2, 5);
				$temp_subs++;
				$id = "MT" .  "00" . $temp_id_tem;
			} elseif ($temp_id_tem == 100000 || $temp_id_tem < 1000000) {
				$temp_subs = substr($temp_id, 1, 6);
				$temp_subs++;
				$id = "MT" .  "0" . $temp_id_tem;
			} else {
				$id = "MT" .  ($temp_id+1);
			}
		} else {
			// echo "kosong";
			$id = "MT" . "0000001";
		}

		// pr($id);

		$this->load->view('orders/v_uploadfile');
	}

	function upload_mutasi_in()
	{
		$this->load->helper('comman_helper');
		$date = date('Y-m-d H:i:s');
		$fileName = time() . $_FILES['file']['name'];

		  $config['upload_path'] = './uploaded/excel/'; 
		  $config['file_name'] = $fileName;
		  $config['allowed_types'] = 'xls|xlsx|csv|ods|ots';
		  $config['max_size'] = 10000;

		  $this->load->library('upload', $config);
		  $this->upload->initialize($config); 
		  
		  if (!$this->upload->do_upload('file')) {
		   $error = array('error' => $this->upload->display_errors());
		   $this->session->set_flashdata('failed','Something wrong in processing upload file excel'); 
		   redirect('Order/upload_mutasi'); 
		  } else {
		   $media = $this->upload->data();
		   $inputFileName = './uploaded/excel/'.$media['file_name'];
		   
		   try {
		    $inputFileType = IOFactory::identify($inputFileName);
			$objReader = IOFactory::createReader($inputFileType);
		    $objPHPExcel = $objReader->load($inputFileName);
		   } catch(Exception $e) {
		    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		   }

		   $this->db->trans_begin();
		   try {
		   		$sheet = $objPHPExcel->getSheet(0);
			   $highestRow = $sheet->getHighestRow();
			   $highestColumn = $sheet->getHighestColumn();
			   // $no = 1;
			   for ($row = 2; $row <= $highestRow; $row++){  
			   		// generate id
			   		// TRX0000001
			   		$temp_id = $this->M_order->get_max_mutation()->row()->id;
					$temp_id_tem = $temp_id + 1;
					// pr($temp_id_tem);
					if ($temp_id != NULL) {
						if ($temp_id_tem < 10) {
							$temp_subs = substr($temp_id, 6, 1);
							$temp_subs++;
							$id = "MT" . "000000" . $temp_subs;
						} elseif ($temp_id_tem == 10 || $temp_id_tem < 100) {
							// $temp_subs = substr($temp_id, 5, 2);
							// $temp_subs++;
							// pr($temp_subs);
							$id = "MT" .  "00000" . $temp_id_tem;
						} elseif ($temp_id_tem == 100 || $temp_id_tem < 1000) {
							$temp_subs = substr($temp_id, 4, 3);
							$temp_subs++;
							$id = "MT" .  "0000" . $temp_id_tem;
						} elseif ($temp_id_tem == 1000 || $temp_id_tem < 10000) {
							$temp_subs = substr($temp_id, 3, 4);
							$temp_subs++;
							$id = "MT" .  "000" . $temp_id_tem;
						} elseif ($temp_id_tem == 10000 || $temp_id_tem < 100000) {
							$temp_subs = substr($temp_id, 2, 5);
							$temp_subs++;
							$id = "MT" .  "00" . $temp_id_tem;
						} elseif ($temp_id_tem == 100000 || $temp_id_tem < 1000000) {
							$temp_subs = substr($temp_id, 1, 6);
							$temp_subs++;
							$id = "MT" .  "0" . $temp_id_tem;
						} else {
							$id = "MT" .  ($temp_id+1);
						}
					} else {
						// echo "kosong";
						$id = "MT" . "0000001";
					}

				    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

					if ($rowData[0][0] != '') {
						// $ori_amount_fix = number_format($ori_amount, 0, '', '');
						$home_debit_fix = str_replace(',', '', $rowData[0][7]);
						$home_credit_fix = str_replace(',', '', $rowData[0][8]);
						$ori_amount = $home_debit_fix + $home_credit_fix;
						$ori_amount_fix = number_format($ori_amount, 0, '', '');

						$date_re = str_replace('/', '-', $rowData[0][2]);
						$datess = new DateTime($date_re);
						$date_fix = $datess->format('Y-m-d');

						$date_re2 = str_replace('/', '-', $rowData[0][3]);
						$datess2 = new DateTime($date_re2);
						$date_fix2 = $datess2->format('Y-m-d');

						$description_fix = trim(preg_replace('/\s\s+/', ' ', $rowData[0][5]));
						$description_fix2 = str_replace('"', "", $description_fix);
						$description_fix3 = str_replace("'", "", $description_fix2);

					    $check_mutation = $this->M_order->check_data_mutation($rowData[0][0],  $date_fix, $date_fix2, $description_fix3, $ori_amount_fix, $home_debit_fix, $home_credit_fix);

					    if ($check_mutation->num_rows() < 1) {
						    $data = array(
						     	'transaction_id' => $id,
						     	'bank_id' => $rowData[0][0],
						     	'transaction_date' => $date_fix,
						     	'transaction_validate' => $date_fix2,
						     	'description_1' => $description_fix3,
						     	'original_currency' => 'IDR',
						     	'original_amount' => $ori_amount,
						     	'home_debit' => $home_debit_fix,
						     	'home_credit' => $home_credit_fix,
						     	'rate' => '1',
						     	'user_id' => $this->nik,
						     	'user_date' => $date
							);
						  	
						    if (!$this->db->insert('dbo.TRBANK_STATEMENT', $data)) {
						    	throw new Exception("Error Processing Request Insert Data Mutation", 1);
						    }
					    }
					}
			   }
			   if ($this->db->trans_status() === FALSE) {
			   		throw new Exception("Error Processing Request Upload Mutation Account", 1);	
			   } else {
			   		$this->session->set_flashdata('success', 'Successfully Upload Mutation Account');
			   		$this->db->trans_commit();
			   		redirect(redirect('Order/upload_mutasi'));
			   }
		   } catch (Exception $e) {
		   		$this->session->set_flashdata('failed', $e->getMessage());
		   		$this->db->trans_rollback();
		   		redirect(redirect('Order/upload_mutasi'));
		   }
		}
    }

    function change_container()
    {
    	$data['all_wo'] = $this->M_order->get_just_wo()->result();
    	$this->load->view('orders/v_changecontainer', $data);
    }

    function entry_container()
    {
    	$this->load->helper('comman_helper');
    	$work_order_number = $this->input->post('work_order_number');
    	$data['work_order_number'] = $work_order_number;
    	$data['all_wo'] = $this->M_order->get_container_number($work_order_number)->result();

    	$this->load->view('orders/v_entrycontainer', $data);
    }

    function view_detail_trucking()
    {
    	$this->load->helper('comman_helper');
    	$work_order_number = $this->uri->segment(3);
    	$data['work_order_number'] = $work_order_number;
    	$data['customer_name'] = $this->M_order->get_name_customer($work_order_number)->row()->name;
    	$data['data_container'] = $this->M_order->get_detail_trucking($work_order_number)->result();
    	$this->load->view('orders/v_viewdetailtrucking', $data);
    }

    function print_declaration()
    {
    	$this->load->helper('comman_helper');
    	$trx_operational = $this->uri->segment(3);
    	$work_order_number = $this->uri->segment(4);
    	$pic_id = $this->uri->segment(5);
    	$pic_name = $this->M_order->get_name_nik($pic_id)->row()->Nm_lengkap;

    	$check_nonreim = $this->input->post('check_nonreim');
    	// pr($check_nonreim);

    	$data['trx_operational'] = $trx_operational;
    	$data['work_order_number'] = $work_order_number;
    	$data['pic_id'] = $pic_id;
    	$data['pic_name'] = $pic_name;

    	$total = 0;

    	foreach ($check_nonreim as $value) {
    		$temp = $this->M_order->get_data_declare($trx_operational, $work_order_number, $pic_id, $value['cost_id'], $value['container_number']);
    		$temp_data['TRX_OPERATIONAL'] = $temp->row()->TRX_OPERATIONAL;
    		$temp_data['WORK_ORDER_NUMBER'] = $temp->row()->WORK_ORDER_NUMBER;
    		$temp_data['CONTAINER_NUMBER'] = $temp->row()->CONTAINER_NUMBER;
    		$temp_data['COST_NAME'] = $temp->row()->COST_NAME;
    		$temp_data['COST_CURRENCY'] = $temp->row()->COST_CURRENCY;
    		$temp_data['COST_ACTUAL_AMOUNT'] = $temp->row()->COST_ACTUAL_AMOUNT;

    		$total += $temp->row()->COST_ACTUAL_AMOUNT;
    		$result_declare[] = $temp_data;
    	}

    	$data['total'] = $total;
    	$data['data_nonreim'] = $result_declare;

    	$html = $this->load->view('reports/r_declaration', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		// $pdf = new pdf();
		// $pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		// $pdf->SetFooter('&emsp;&emsp;*) Printed by System ||');
		// $pdf->SetWatermarkText('DRAFT', 1, array(10,10), array(500,20));
		// $pdf->watermarkTextAlpha = 0.1;
		// $pdf->watermark_font = 'DejaVuSansCondensed';
		// $pdf->showWatermarkText = true;
		$pdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        0, // margin_left
        0, // margin right
       20, // margin top
       20, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('Declaration.pdf', 'I');
    }

    function entry_vessel_voyage()
    {
    	$date = date('Y-m-d H:i:s');
    	$this->load->helper('comman_helper');
    	$data['data_trade'] = $this->M_order->get_data_trade()->result();

    	$this->form_validation->set_rules('vessel_name', 'Vessel Name', 'required');
    	$this->form_validation->set_rules('vessel_id', 'Vessel ID', 'required');
    	$this->form_validation->set_rules('trade_id', 'Trade', 'required');
    	$this->form_validation->set_rules('eta', 'ETA', 'required');
    	$this->form_validation->set_rules('etd', 'ETD', 'required');
    	$this->form_validation->set_rules('pol_name', 'POL', 'required');
    	$this->form_validation->set_rules('pol_id', 'POL ID', 'required');
    	$this->form_validation->set_rules('pod_name', 'POD', 'required');
    	$this->form_validation->set_rules('pod_id', 'POD ID', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	// declare variable from post
        	$vessel_id = $this->input->post('vessel_id');
        	$voyage_number = $this->input->post('voyage_number');
        	$trade_id = $this->input->post('trade_id');
        	$eta = $this->input->post('eta');
        	$etd = $this->input->post('etd');
        	$pol_id = $this->input->post('pol_id');
        	$pod_id = $this->input->post('pod_id');

        	$check_data = $this->M_order->check_vessel_voyage($vessel_id, $voyage_number, $trade_id, $eta, $etd, $pol_id, $pod_id)->num_rows();

        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entryvesselvoyage', $data);
			} elseif($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Vessel voyage already exists! Please entry another data.";
				$this->load->view('orders/v_entryvesselvoyage', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_insert = array(
						'vessel_id' => $vessel_id,
						'voyage_number' => $voyage_number,
						'trade' => $trade_id,
						'eta_date' => $eta,
						'etd_date' => $etd,
						'pol_id' => $pol_id,
						'pod_id' => $pod_id,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MVESSEL_VOYAGE', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Vessel Voyage", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Vessel Voyage", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Vessel Voyage');
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

    function entry_vessel()
    {
    	$this->load->helper('comman_helper');
    	$date = date('Y-m-d H:i:s');

    	$this->form_validation->set_rules('vessel_name', 'Vessel Name', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	
        	$vessel_name = $this->input->post('vessel_name');

        	$check_data = $this->M_order->check_vessel($vessel_name)->num_rows();

        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entryvessel');
			} elseif($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Vessel already exists! Please entry another data.";
				$this->load->view('orders/v_entryvessel', $data);
			} else {
				// get vessel id
	        	$temp_id = $this->M_order->get_max_vessel()->row()->id;
		    	$temp_ids = $temp_id + 1;

		    	if ($temp_id != NULL || $temp_id != "") {
		    		if ($temp_ids < 10) {
		    			$id = "V" . "00" . $temp_ids;
		    		} elseif ($temp_ids == 10 || $temp_ids < 100) {
		    			$id = "V" . "0" . $temp_ids;
		    		} else {
		    			$id = "V" . $temp_ids;
		    		}
		    	} else {
		    		$id = "V001";
		    	}

	        	// declare variable from post
	        	$vessel_id = $id;

				$this->db->trans_begin();
				try {
					$data_insert = array(
						'vessel_id' => $vessel_id,
						'vessel_name' => $vessel_name,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MVESSEL', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Vessel Data", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Vessel Data", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Vessel Data');
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

    function entry_port()
    {
    	$this->load->helper('comman_helper');
    	$date = date('Y-m-d H:i:s');

    	$this->form_validation->set_rules('port_id', 'Port ID', 'required');
    	$this->form_validation->set_rules('port_name', 'Port Name', 'required');
    	$this->form_validation->set_rules('country', 'Country', 'required');
		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	
        	$port_id = $this->input->post('port_id');
        	$port_name = $this->input->post('port_name');
        	$country = $this->input->post('country');

        	$check_data = $this->M_order->check_port($port_id, $port_name, $country)->num_rows();

        	if ($this->form_validation->run() == false) {
				$this->load->view('orders/v_entryport');
			} elseif($check_data > 0) {
				$data['error_var'] = "error";
				$data['error_msg'] = "Port data already exists! Please entry another data.";
				$this->load->view('orders/v_entryport', $data);
			} else {
				$this->db->trans_begin();
				try {
					$data_insert = array(
						'port_id' => $port_id,
						'port_name' => $port_name,
						'country' => $country,
						'user_id' => $this->nik,
						'user_date' => $date
					);

					if (!$this->db->insert('dbo.MPORT', $data_insert)) {
						throw new Exception("Error Processing Request to Entry Port Data", 1);
					}

					if ($this->db->trans_status() === FALSE) {
						throw new Exception("Error Processing Request to Entry Port Data", 1);
					} else {
						$this->session->set_flashdata('success', 'Successfully Entry Port Data');
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

    function print_voucher_detail()
    {
    	$this->load->helper('comman_helper');
    	$trx_number = $this->uri->segment(3);
    	$data['trx_number'] = $trx_number;
    	$data['data_transaction'] = $this->M_order->get_data_detail_transaction($trx_number)->result();
    	$data['total_amount'] = $this->M_order->get_transfer_header($trx_number)->row()->TOTAL_AMOUNT;

    	$data_detail = $this->M_order->get_detail_data_transaction2($trx_number)->result();
    	

    	foreach ($data_detail as $key => $value) {
    		$data_bl2 = array();
    		$data_container = array();
    		$data_group = array();
    		$data_customs_location = array();

    		$temp_data['TRX_NUMBER'] = $value->TRX_NUMBER;
    		$temp_data['WORK_ORDER_NUMBER'] = $value->WORK_ORDER_NUMBER;
    		$temp_data['TRANS_DATE'] = $value->TRANS_DATE;
    		$temp_data['COST_CURRENCY'] = $value->COST_CURRENCY;
    		$temp_data['PIC_NAME'] = $value->PIC_NAME;
    		$temp_data['NIK_NAME'] = $value->FULL_NAME;
    		$temp_data['TRADE_ID'] = $value->TRADE_ID;
    		$temp_data['WO_DATE'] = $value->WO_DATE;
    		$temp_data['COMPANY_NAME'] = $value->COMPANY_NAME;
    		$temp_data['ETA_DATE'] = $value->ETA_DATE;
    		$temp_data['ETD_DATE'] = $value->ETD_DATE;
    		$temp_data['TOTAL_AMOUNT'] = $value->TOTAL_AMOUNT;
    		$temp_data['WO_DATE'] = $value->WO_DATE;

    		$data_bl = $this->M_order->get_detail_wo_transaction($value->TRX_NUMBER, $value->WORK_ORDER_NUMBER)->result();
    		foreach ($data_bl as $key1 => $value1) {
    			if ($value1->WORK_ORDER_NUMBER == $value->WORK_ORDER_NUMBER) {
    				array_push($data_bl2, $value1->BL_NUMBER);
    				array_push($data_container, $value1->CONTAINER_NUMBER);
    				array_push($data_group, $value1->COST_GROUP);
    				array_push($data_customs_location, $value1->CUSTOMS_LOCATION);
    			}
    		}

    		$fix_bl = array_unique($data_bl2);
    		$nomor_bl = implode(", ", $fix_bl);

    		$nomor_container = implode(", ", $data_container);

    		$fix_group = array_unique($data_group);
    		$cost_group = implode(", ", $fix_group);

			$fix_customs = array_unique($data_customs_location);
    		$customs_location = implode(", ", $fix_customs);    		

    		$temp_data['BL_NUMBER'] = $nomor_bl;
    		$temp_data['CONTAINER_NUMBER'] = $nomor_container;
    		$temp_data['COST_GROUP'] = $cost_group;
    		$temp_data['CUSTOMS_LOCATION'] = $customs_location;

    		unset($data_bl);
    		unset($data_bl2);
    		unset($data_container);
    		unset($data_group);
    		unset($data_customs_location);

    		$result_detail[] = $temp_data;
    	}

    	// pr($result_detail);

    	$data['result_detail'] = $result_detail;

    	$html = $this->load->view('reports/r_transaction', $data, true);
		$this->load->library('pdf');

		$pdf = $this->pdf->load();
		// $pdf = new pdf();
		// $pdf->SetHTMLHeader('<img src="' . base_url() . 'assets/images/header-quotation.jpg"/>');
		// $pdf->SetFooter('&emsp;Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 &emsp;Jakarta Pusat 10160 Indonesia <br>&emsp;Phone : +62-1 3505350, 3505355 <br>&emsp;Email : hanoman@hanomansp.com <br>&emsp;www.hanomansp.com||Page {PAGENO} of {nb}&emsp;');
		$pdf->AddPage('L', // L - landscape, P - portrait 
        '', '', '', '',
        -10, // margin_left
        -10, // margin right
       10, // margin top
       30, // margin bottom
        0, // margin header
        5); // margin footer
		// $pdf->defaultheaderfontstyle='I';
		// $pdf->defaultfooterfontstyle='I';
		// $pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
		
		$pdf->WriteHTML($html);
		$pdf->Output('Transaction.pdf', 'I');

    }
}
