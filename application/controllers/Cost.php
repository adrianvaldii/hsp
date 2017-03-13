<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set("Asia/Jakarta");

class Cost extends CI_Controller {

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
		$this->load->model('M_cost');
		$this->nik = $this->session->userdata('nik');

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
	}

	public function index()
	{
		$this->load->helper('comman_helper');
		$this->load->helper('currency_helper');
		$cmpy = $this->M_cost->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_cost->get_code($cmpy)->row()->COMPANY_ID;

		// container service jakarta
		$data['cost_container_jakarta'] = $this->M_cost->get_all_data_container_cost_jakarta($code_cmpy)->result();
		// pr($data['cost_container_jakarta']);
		$data['tarif_amount_jakarta'] = $this->M_cost->get_tarif_amount_jakarta($code_cmpy)->result();

		$result['cost'] = $this->M_cost->get_cost()->result();
		$result['cost_type'] = $this->M_cost->get_cost_type()->result();
		$result['cost_group'] = $this->M_cost->get_cost_group()->result();

		// container cost jakarta
		if (!$data['cost_container_jakarta']) {
			$hasil_jakarta = array();
		} else {
			// select cost continer jakarta
			foreach ($data['cost_container_jakarta'] as $key => $value) {
				$test_data['COMPANY_ID'] = $value->COMPANY_ID;
				$test_data['COMPANY_NAME'] = $value->COMPANY_NAME;
				$test_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['SERVICE_NAME'] = $value->SERVICE_NAME;
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
						$test_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
						$test_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
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
			if (!$this->M_cost->check_data_container('20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_cost->check_data_container('40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_cost->check_data_container('4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_cost->check_data_container('45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
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

		}

		// custom service jakarta
		$data['cost_custom_jakarta'] = $this->M_cost->get_all_data_custom_cost_jakarta($code_cmpy)->result();
		$data['tarif_amount_custom_jakarta'] = $this->M_cost->get_tarif_amount_custom_jakarta($code_cmpy)->result();

		$result['services'] = $this->M_cost->get_services3($code_cmpy)->result();
		$result['companies'] = $this->M_cost->get_companies()->result();

		// $this->load->helper('comman_helper');
		// pr($data['cost_custom_jakarta']);
		
		// container custom jakarta		
		if (!$data['cost_custom_jakarta']) {
			$hasil_custom_jakarta = array();
		} else {
			// select data cost container custom jakarta
			foreach ($data['cost_custom_jakarta'] as $key => $value) {
				$print_data['COMPANY_ID'] = $value->COMPANY_ID;
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['FROM_QTY'] = $value->FROM_QTY;
				$print_data['TO_QTY'] = $value->TO_QTY;
				$print_data['CALC_TYPE'] = $value->CALC_TYPE;
				$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['END_DATE'] = $value->END_DATE;

				foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
						$print_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
					}
				}

				if (empty($print_data['TARIF_20'])) {
					$print_data['TARIF_20'] = currency(0);
				}

				if (empty($print_data['TARIF_40'])) {
					$print_data['TARIF_40'] = currency(0);
				}

				if (empty($print_data['TARIF_4H'])) {
					$print_data['TARIF_4H'] = currency(0);
				}

				if (empty($print_data['TARIF_45'])) {
					$print_data['TARIF_45'] = currency(0);
				}

				$hasil_custom_jakarta[] = $print_data;
			}
		}

		// check data tariff_amount custom jakarta
		foreach ($hasil_custom_jakarta as $key => $value) {
			if (!$this->M_cost->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_cost->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_cost->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_cost->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
				unset($hasil_custom_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_custom_jakarta[$key]['TARIF_20'])) {
					$hasil_custom_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_40'])) {
					$hasil_custom_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_4H'])) {
					$hasil_custom_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_custom_jakarta[$key]['TARIF_45'])) {
					$hasil_custom_jakarta[$key]['TARIF_45'] = currency(0);
			}

		}

		$result['hasil_location_jakarta'] = $this->M_cost->get_data_location_jakarta()->result();

		$result['hasil_weight_jakarta'] = $this->M_cost->get_data_weight_jakarta()->result();
		
		// ocean freight
		// OF jakarta
		$data['cost_ocean_jakarta'] = $this->M_cost->get_data_ocean_jakarta()->result();
		$data['tarif_amount_ocean_jakarta'] = $this->M_cost->get_tarif_amount_ocean_jakarta()->result();

		// ocean cost jakarta
		if (!$data['cost_ocean_jakarta']) {
			$hasil_ocean_jakarta = array();
		} else {
			// select cost ocean jakarta
			foreach ($data['cost_ocean_jakarta'] as $key => $value) {
				$test_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
				$test_data['COMPANY_NAME'] = $value->COMPANY_NAME;
				$test_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
				$test_data['CHARGE_ID'] = $value->CHARGE_ID;
				$test_data['CHARGE_NAME'] = $value->CHARGE_NAME;
				$test_data['SERVICE_NAME'] = $value->SERVICE_NAME;
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
				
				foreach ($data['tarif_amount_ocean_jakarta'] as $key1 => $value1) {
					
					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CHARGE_ID == $value->CHARGE_ID) {
						$test_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CHARGE_ID == $value->CHARGE_ID) {
						$test_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CHARGE_ID == $value->CHARGE_ID) {
						$test_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
					}

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CHARGE_ID == $value->CHARGE_ID) {
						$test_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
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

				$hasil_ocean_jakarta[] = $test_data;
			}
		}

		// check data tariff_amount jakarta
		foreach ($hasil_ocean_jakarta as $key => $value) {
			if (!$this->M_cost->check_data_ocean('20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $value['CHARGE_ID'])->result()) {
				unset($hasil_ocean_jakarta[$key]['TARIF_20']);
			} 

			if (!$this->M_cost->check_data_ocean('40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $value['CHARGE_ID'])->result()) {
				unset($hasil_ocean_jakarta[$key]['TARIF_40']);
			}

			if (!$this->M_cost->check_data_ocean('4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $value['CHARGE_ID'])->result()) {
				unset($hasil_ocean_jakarta[$key]['TARIF_4H']);
			}

			if (!$this->M_cost->check_data_ocean('45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'], $value['CHARGE_ID'])->result()) {
				unset($hasil_ocean_jakarta[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (!isset($hasil_ocean_jakarta[$key]['TARIF_20'])) {
					$hasil_ocean_jakarta[$key]['TARIF_20'] = currency(0);
			}

			if (!isset($hasil_ocean_jakarta[$key]['TARIF_40'])) {
					$hasil_ocean_jakarta[$key]['TARIF_40'] = currency(0);
			}

			if (!isset($hasil_ocean_jakarta[$key]['TARIF_4H'])) {
					$hasil_ocean_jakarta[$key]['TARIF_4H'] = currency(0);
			}

			if (!isset($hasil_ocean_jakarta[$key]['TARIF_45'])) {
					$hasil_ocean_jakarta[$key]['TARIF_45'] = currency(0);
			}

		}

		$result['hasil_ocean_jakarta'] = $hasil_ocean_jakarta;

		// result data container trucking service
		$result['hasil_jakarta'] = $hasil_jakarta;

		// result data container custom service
		$result['hasil_custom_jakarta'] = $hasil_custom_jakarta;

		$this->load->view('costs/v_containerservice', $result);	
	}

	public function container_detail()
	{
		$to_location = $this->uri->segment(3);
		$container_type = $this->uri->segment(4);
		$from_location = $this->uri->segment(5);
		$company_id = $this->uri->segment(6);
		$from_qty = $this->uri->segment(7);
		$to_qty = $this->uri->segment(8);
		// get all data cost
		$data['cost_detail_20'] = $this->M_cost->get_container_cost_detail_20($from_location, $to_location, $company_id, $container_type, $from_qty, $to_qty)->result();
		$data['cost_detail_40'] = $this->M_cost->get_container_cost_detail_40($from_location, $to_location, $company_id, $container_type, $from_qty, $to_qty)->result();
		$data['cost_detail_4h'] = $this->M_cost->get_container_cost_detail_4h($from_location, $to_location, $company_id, $container_type, $from_qty, $to_qty)->result();
		$data['cost_detail_45'] = $this->M_cost->get_container_cost_detail_45($from_location, $to_location, $company_id, $container_type, $from_qty, $to_qty)->result();

		// get data to detail
		$data['details'] = $this->M_cost->get_detail_of_cost($to_location, $from_location, $container_type, $company_id, $from_qty, $to_qty)->result();

		// $this->load->helper('comman_helper');
		// pr($data['details']);

		$this->load->view('costs/v_detailcontainercost', $data);
	}

	public function container_custom_detail()
	{
		$this->load->helper('comman_helper');
		$company_id = $this->uri->segment(3);
		$s_custom_location = $this->uri->segment(4);
		$c_container_type = $this->uri->segment(5);
		$s_custom_line = $this->uri->segment(6);
		$container_category = $this->uri->segment(7);
		// get all data cost
		$data['custom_cost_detail_20'] = $this->M_cost->get_container_custom_cost_detail_20($company_id, $c_container_type, $s_custom_location, $s_custom_line, $container_category)->result();
		$data['custom_cost_detail_40'] = $this->M_cost->get_container_custom_cost_detail_40($company_id, $c_container_type, $s_custom_location, $s_custom_line, $container_category)->result();
		$data['custom_cost_detail_4h'] = $this->M_cost->get_container_custom_cost_detail_4h($company_id, $c_container_type, $s_custom_location, $s_custom_line, $container_category)->result();
		$data['custom_cost_detail_45'] = $this->M_cost->get_container_custom_cost_detail_45($company_id, $c_container_type, $s_custom_location, $s_custom_line, $container_category)->result();

		// get data to detail
		$data['company_name'] = $this->M_cost->get_detail_of_custom_cost($company_id, $c_container_type, $s_custom_location, $s_custom_line)->row()->COMPANY_NAME;
		// pr($data['company_name']);
		$data['service_name'] = $this->M_cost->get_detail_of_custom_cost($company_id, $c_container_type, $s_custom_location, $s_custom_line)->row()->SERVICE_NAME;
		$data['custom_location'] = $this->M_cost->get_detail_of_custom_cost($company_id, $c_container_type, $s_custom_location, $s_custom_line)->row()->CUSTOM_LOCATION;
		$data['custom_line'] = $this->M_cost->get_detail_of_custom_cost($company_id, $c_container_type, $s_custom_location, $s_custom_line)->row()->CUSTOM_LINE;
		$data['container_type'] = $this->M_cost->get_detail_of_custom_cost($company_id, $c_container_type, $s_custom_location, $s_custom_line)->row()->CONTAINER_TYPE;
		$data['container_category'] = $this->M_cost->get_detail_of_custom_cost($company_id, $c_container_type, $s_custom_location, $s_custom_line)->row()->CONTAINER_CATEGORY;

		// $this->load->helper('comman_helper');
		// pr($data['details']);

		$this->load->view('costs/v_detailcontainercustomcost', $data);
	}

	public function add_container()
	{
		$cmpy = $this->M_cost->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_cost->get_code($cmpy)->row()->COMPANY_ID;
		$data['code_cmpy'] = $code_cmpy;

		$data['company_service'] = $this->M_cost->get_companies()->result();
		$data['selling_service'] = $this->M_cost->get_services_spec();
		$data['container_size'] = $this->M_cost->get_container_size()->result();
		$data['container_type'] = $this->M_cost->get_container_type()->result();
		$data['container_category'] = $this->M_cost->get_container_category()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();

		// set validation rules
		 $this->form_validation->set_rules('company_id', 'Company Service', 'required');
		 $this->form_validation->set_rules('selling_service_id', 'Selling Service', 'required');
		 $this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		 $this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		 $this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		 $this->form_validation->set_rules('from_location_id', 'From / To Location', 'required');
		 $this->form_validation->set_rules('to_location_id', 'Destination', 'required');
		 $this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		 $this->form_validation->set_rules('increment_qty', 'Increment Quantity', 'required');
		 $this->form_validation->set_rules('tariff_currency[]', 'Currency', 'required');
		 $this->form_validation->set_rules('tariff_amount[]', 'Cost Amount', 'required');
		 $this->form_validation->set_rules('start_date', 'Start Date', 'required');
		 $this->form_validation->set_rules('end_date', 'End Date', 'required');
		 $this->form_validation->set_rules('from_qty[]', 'From Qty', 'required');
		 $this->form_validation->set_rules('to_qty[]', 'To Qty', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	// declare variable
	        $company_id = $this->input->post('company_id');
			$s_selling_service_id = $this->input->post('selling_service_id');
			$n_container_size_id = $this->input->post('container_size_id');
			$s_container_type_id =  $this->input->post('container_type_id');
			$s_container_category_id = $this->input->post('container_category_id');

			// $this->load->helper('comman_helper');
			// pr($floor_price);

			$s_calc_type = $this->input->post('calc_type');
			$n_increment_qty = $this->input->post('increment_qty');
			$s_from_location_id = $this->input->post('from_location_id');
			$s_to_location_id =  $this->input->post('to_location_id');
			$d_start_date = $this->input->post('start_date');
			$d_end_date = $this->input->post('end_date');

	         $n_from_qtys = $this->input->post('from_qty');
	         $n_to_qtys = $this->input->post('to_qty');
	         $n_tariff_currencys = $this->input->post('tariff_currency');
	         $n_tariff_amounts = $this->input->post('tariff_amount');
	         $n_count_selling = count($this->input->post('to_qty'));
	         $check_valid_qty = "";
	         
	         // validation for qty
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	if (isset($n_from_qtys[$i+1]) && $n_from_qtys[$i+1] <= $n_to_qtys[$i]) {
	         		$check_valid_qty = "error";
	         	} elseif (isset($n_from_qtys[$i+1]) && ($n_from_qtys[$i+1] - 1) != $n_to_qtys[$i]) {
	         		$check_valid_qty = "error";
	         	}
	         }
	         // validation for data exists
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	// check data to database
	         	$check_data_selling = $this->M_cost->check_data_selling($company_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id, $d_start_date, $d_end_date, $n_from_qtys[$i], $n_to_qtys[$i]);

	         	if ($check_data_selling > 0) {
	         		$result_check = 1;
	         	} else {
	         		$result_check = 0;
	         	}
	         }
	         // validation for overlap start date
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	$check_data_selling2 = $this->M_cost->check_data_selling2($company_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id, $n_from_qtys[$i], $n_to_qtys[$i]);

	         	if ($check_data_selling2->num_rows() > 0) {
	         		$check_date = $this->M_cost->valid_date($company_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id, $n_from_qtys[$i], $n_to_qtys[$i], $d_start_date, $d_end_date);

         			if ($check_date->num_rows() > 0) {
         				$result_date = 1;
         			} else {
         				$result_date = 0;
         			}
	         	} else {
	         		$result_date = 0;
	         	}

	         }
	         
	         // check for validation
	        if ($this->form_validation->run() == FALSE) {
	            $this->load->view('costs/v_addcontainer', $data);
	        } elseif ($check_valid_qty == "error") {
	        	 $data['qty_error'] = "error";
        		 $this->load->view('costs/v_addcontainer', $data);
	        } elseif ($result_check > 0) {
	        	 $data['data_exist'] = "exist";
	        	 $this->load->view('costs/v_addcontainer', $data);
	        } elseif ($result_date > 0) {
	        	 $data['date_error'] = "error";
	        	 $this->load->view('costs/v_addcontainer', $data);
	        } else{
	        	$this->db->trans_begin();
	        	try {
	        		$data_entry = array();
		        	for ($i=0; $i < $n_count_selling; $i++) { 
		        		$data_entry[] = array(
			        		'company_id' => $company_id,
			        		'selling_service_id' => $s_selling_service_id,
			        		'container_size_id' => $n_container_size_id,
			        		'container_type_id' => $s_container_type_id,
			        		'container_category_id' => $s_container_category_id,
			        		'from_location_id' => $s_from_location_id,
			        		'to_location_id' => $s_to_location_id,
			        		'start_date' => $d_start_date,
			        		'end_date' => $d_end_date,
			        		'calc_type' => $s_calc_type,
			        		'increment_qty' => $n_increment_qty,
			        		'from_qty' => $n_from_qtys[$i],
			        		'to_qty' => $n_to_qtys[$i],
			        		'tariff_currency' => $n_tariff_currencys[$i],
			        		'tariff_amount' => $n_tariff_amounts[$i],
			        		'approval_status' => 'N'
			        	);
		        	}

		        	// insert limitation from header
		        	$check_limit = $this->M_cost->check_limit_trucking($company_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id)->num_rows();

		        	if ($check_limit < 1) {
		        		$data_limit = array(
		        			'company_id' => $company_id,
		        			'selling_service_id' => $s_selling_service_id,
		        			'container_size_id' => $n_container_size_id,
		        			'container_type_id' => $s_container_type_id,
		        			'container_category_id' => $s_container_category_id,
		        			'from_location_id' => $s_from_location_id,
		        			'to_location_id' => $s_to_location_id
		        		);

		        		if (!$this->db->insert('dbo.MLIMITATIONS_PRICE_CONTAINER_ATTRIBUTE', $data_limit)) {
		        			throw new Exception("Error Processing Request to Entry Floor Price Container Trucking Selling", 1);
		        		}
		        	}
		        	
		        	// $this->M_cost->add_container_selling($data, 'dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');

		        	if (!$this->db->insert_batch('dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE', $data_entry)) {
		        		throw new Exception("Error Processing Request to Entry Container Trucking Selling", 1);
		        	}

		            if ($this->db->trans_status() === FALSE) {
		            	throw new Exception("Error Processing Request to Entry Trucking Service Selling", 1);
		            } else {
		            	$this->session->set_flashdata('success_container_trucking', 'Selling Rate Submitted Successfully');
		            	$this->db->trans_commit();
		            	redirect('Cost/add_container');
		            }
	        	} catch (Exception $e) {
	        		$this->session->set_flashdata('failed_container_trucking', $e->getMessage());
	            	$this->db->trans_rollback();
	            	redirect('Cost/add_container');
	        	}
	        }
        }
	}

	public function add_container_custom()
	{
		$cmpy = $this->M_cost->get_cmpy_code($this->nik)->row()->Cmpy_code;
		$code_cmpy = $this->M_cost->get_code($cmpy)->row()->COMPANY_ID;
		$data['code_cmpy'] = $code_cmpy;
		$data['company_service'] = $this->M_cost->get_companies()->result();
		$data['company'] = $this->M_cost->get_company()->result();
		$data['selling_service'] = $this->M_cost->get_services_custom();
		$data['container_size'] = $this->M_cost->get_container_size()->result();
		$data['container_type'] = $this->M_cost->get_container_type()->result();
		$data['container_category'] = $this->M_cost->get_container_category()->result();
		$data['custom_location'] = $this->M_cost->get_custom_location()->result();
		$data['custom_kind'] = $this->M_cost->get_custom_kind()->result();
		$data['custom_line'] = $this->M_cost->get_custom_line()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();

		// set validation rules
		 $this->form_validation->set_rules('company_id', 'Company ID', 'required');
		 $this->form_validation->set_rules('selling_service_id', 'Selling Service', 'required');
		 $this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		 $this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		 $this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		 $this->form_validation->set_rules('custom_location_id', 'Custom Location', 'required');
		 $this->form_validation->set_rules('custom_kind_id', 'Custom Kind', 'required');
		 $this->form_validation->set_rules('custom_line_id', 'Custom Line', 'required');
		 $this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		 $this->form_validation->set_rules('increment_qty', 'Increment Qty', 'required');
		 $this->form_validation->set_rules('tariff_currency[]', 'Currency', 'required');
		 $this->form_validation->set_rules('tariff_amount[]', 'Cost Amount', 'required|numeric');
		 $this->form_validation->set_rules('start_date', 'Start Date', 'required');
		 $this->form_validation->set_rules('end_date', 'End Date', 'required');
		 $this->form_validation->set_rules('from_qty[]', 'From Qty', 'required');
		 $this->form_validation->set_rules('to_qty[]', 'To Qty', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         if (isset($_POST)) {
         	// check available data
	         // declare variable
	         $company_id = $this->input->post('company_id');
	         $selling_service = $this->input->post('selling_service_id');
	         $custom_location = $this->input->post('custom_location_id');
	         $custom_kind = $this->input->post('custom_kind_id');
	         $custom_line = $this->input->post('custom_line_id');
	         $size = $this->input->post('container_size_id');
	         $type_con = $this->input->post('container_type_id');
	         $cat_con = $this->input->post('container_category_id');
	         $increment = $this->input->post('increment_qty');
	         $start_date = $this->input->post('start_date');
	         $end_date = $this->input->post('end_date');

	         $n_from_qtys = $this->input->post('from_qty');
	         $n_to_qtys = $this->input->post('to_qty');
	         $n_tariff_currencys = $this->input->post('tariff_currency');
	         $n_tariff_amounts = $this->input->post('tariff_amount');
	         $n_count_selling = count($this->input->post('to_qty'));
	         $check_valid_qty = "";
	         
	         // validation for qty
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	if (isset($n_from_qtys[$i+1]) && $n_from_qtys[$i+1] <= $n_to_qtys[$i]) {
	         		$check_valid_qty = "error";
	         	} elseif (isset($n_from_qtys[$i+1]) && ($n_from_qtys[$i+1] - 1) != $n_to_qtys[$i]) {
	         		$check_valid_qty = "error";
	         	}
	         }

	         // $check_data = $this->M_cost->check_data_add_custom($company_service, $selling_service, $custom_location, $company, $custom_kind, $custom_line, $size, $type_con, $cat_con, $increment);

	         // validation for data exists
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	// check data to database
	         	$check_data_custom = $this->M_cost->check_data_add_custom($company_id, $selling_service, $size, $type_con, $cat_con, $custom_location, $custom_line, $custom_kind, $start_date, $end_date, $n_from_qtys[$i], $n_to_qtys[$i], $increment);

	         	if ($check_data_custom > 0) {
	         		$result_check = 1;
	         	} else {
	         		$result_check = 0;
	         	}
	         }

	         // validation for overlap start date
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	$check_data_custom_date = $this->M_cost->check_data_custom_date($company_id, $selling_service, $size, $type_con, $cat_con, $custom_location, $custom_line, $custom_kind, $n_from_qtys[$i], $n_to_qtys[$i], $increment);

	         	if ($check_data_custom_date->num_rows() > 0) {
	         		$check_date = $this->M_cost->valid_date_custom($company_id, $selling_service, $size, $type_con, $cat_con, $custom_location, $custom_line, $custom_kind, $n_from_qtys[$i], $n_to_qtys[$i], $increment, $start_date, $end_date);

         			if ($check_date->num_rows() > 0) {
         				$result_date = 1;
         			} else {
         				$result_date = 0;
         			}
	         	} else {
	         		$result_date = 0;
	         	}

	         }

	         // $this->load->helper('comman_helper');
	         // pr($check_data);

	         // check for validation
	        if ($this->form_validation->run() == FALSE) {
	            $this->load->view('costs/v_addcontainercustom', $data);
	        } elseif ($check_valid_qty == "error") {
	        	 $data['qty_error'] = "error";
        		 $this->load->view('costs/v_addcontainercustom', $data);
	        } elseif ($result_check > 0) {
	        	$data['data_exist'] = "exist";
	        	$this->load->view('costs/v_addcontainercustom', $data);
	        } elseif ($result_date > 0) {
	        	$data['date_error'] = "error";
	        	$this->load->view('costs/v_addcontainercustom', $data);
	        } else{
	        	$this->db->trans_begin();
	        	try {
	        		$data_entry = array();
		        	for ($i=0; $i < $n_count_selling; $i++) { 
		        		$data_entry[] = array(
			        		'selling_service_id' => $this->input->post('selling_service_id'),
			        		'custom_location_id' => $this->input->post('custom_location_id'),
			        		'company_id' => $company_id,
			        		'custom_kind_id' => $this->input->post('custom_kind_id'),
			        		'container_size_id' => $this->input->post('container_size_id'),
			        		'container_type_id' => $this->input->post('container_type_id'),
			        		'container_category_id' => $this->input->post('container_category_id'),
			        		'custom_line_id' => $this->input->post('custom_line_id'),
			        		'start_date' => $this->input->post('start_date'),
			        		'end_date' => $this->input->post('end_date'),
			        		'calc_type' => $this->input->post('calc_type'),
			        		'increment_qty' => $this->input->post('increment_qty'),
			        		'from_qty' => $n_from_qtys[$i],
			        		'to_qty' => $n_to_qtys[$i],
			        		'tariff_currency' => $n_tariff_currencys[$i],
			        		'tariff_amount' => $n_tariff_amounts[$i],
			        		'approval_status' => 'N'
			        	);
		        	}

		        	// insert limitation from header
		        	$check_limit = $this->M_cost->check_limit_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $size, $type_con, $cat_con)->num_rows();

		        	if ($check_limit < 1) {
		        		$data_limit = array(
		        			'company_id' => $company_id,
		        			'selling_service_id' => $selling_service,
		        			'container_size_id' => $size,
		        			'container_type_id' => $type_con,
		        			'container_category_id' => $cat_con,
		        			'custom_location_id' => $custom_location,
		        			'custom_line_id' => $custom_line,
		        			'custom_kind_id' => $custom_kind
		        		);

		        		if (!$this->db->insert('dbo.MLIMITATIONS_PRICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_limit)) {
		        			throw new Exception("Error Processing Request to Entry Floor Price Customs Clearance Selling", 1);
		        		}
		        	}

		        	if (!$this->db->insert_batch('dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_entry)) {
		        		throw new Exception("Error Processing Request to Entry Customs Clearance Selling", 1);
		        	}

		            if ($this->db->trans_status() === FALSE) {
		            	throw new Exception("Error Processing Request to Entry Customs Clearance Selling", 1);
		            } else {
		            	$this->session->set_flashdata('success_customs_selling', 'Selling Rate Custom Submitted Successfully');
		            	$this->db->trans_commit();
		            	redirect(current_url());
		            }
	        	} catch (Exception $e) {
	        		$this->session->set_flashdata('failed_customs_selling', $e->getMessage());
	            	$this->db->trans_rollback();
	            	redirect(current_url());
	        	}
	        }
         }
	}

	public function search_from_location()
	{
		$kode = $this->input->get('term');
		$data['location'] = $this->M_cost->get_location($kode)->result();

		foreach ($data['location'] as $key => $value) {
			$temp_location['value'] = $value->LOCATION_NAME;
			$temp_location['location_id'] = $value->LOCATION_ID;
			$result_location[] = $temp_location;
		}
		// $this->load->helper('comman_helper');
		// pr($result_location);
		echo json_encode($result_location);
	}

	public function add_container_cost()
	{
		$cmpy = $this->M_cost->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_cost->get_code($cmpy)->row()->COMPANY_ID;
		$date = date('Y-m-d H:i:s');
		$data['company_service'] = $this->M_cost->get_companies()->result();
		$data['selling_service'] = $this->M_cost->get_services_spec();
		$data['container_size'] = $this->M_cost->get_container_size()->result();
		$data['container_type'] = $this->M_cost->get_container_type()->result();
		$data['container_category'] = $this->M_cost->get_container_category()->result();
		$data['cost'] = $this->M_cost->get_cost_trc()->result();
		$data['cost_type'] = $this->M_cost->get_cost_type()->result();
		$data['cost_group'] = $this->M_cost->get_cost_group()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();

		$from_location = $this->uri->segment(3);
		$to_location = $this->uri->segment(4);
		$container_type = $this->uri->segment(5);
		$from_qty = $this->uri->segment(6);
		$to_qty = $this->uri->segment(7);
		$container_size = $this->uri->segment(8);
		$container_category = $this->uri->segment(9);
		$company_id = $this->uri->segment(10);
		$selling_service_id = $this->uri->segment(11);
		$start_date = $this->uri->segment(12);
		$end_date = $this->uri->segment(13);

		$data['details'] = $this->M_cost->get_detail_add_cost($code_cmpy, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category)->result();

		$data['check_detail'] = $this->M_cost->check_detail_add_cost($from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date);


		// set validation rules
		 $this->form_validation->set_rules('cost_currency[]', 'Currency', 'required');
		 $this->form_validation->set_rules('cost_amount[]', 'Cost Amount', 'required|numeric');
		 $this->form_validation->set_rules('from_qty[]', 'From Qty', 'required');
		 $this->form_validation->set_rules('to_qty[]', 'To Qty', 'required');
		 $this->form_validation->set_rules('cost_id', 'Cost', 'required');
		 $this->form_validation->set_rules('start_date', 'Start Date', 'required');
		 $this->form_validation->set_rules('end_date', 'End Date', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // declare variable
         $n_from_qtys = $this->input->post('from_qty');
         $n_to_qtys = $this->input->post('to_qty');
         $n_cost_currencys = $this->input->post('cost_currency');
         $n_cost_amounts = $this->input->post('cost_amount');
         $n_count_selling = count($this->input->post('to_qty'));
         
         // variable from POST
         $start_date_post = $this->input->post('start_date');
     	 $end_date_post = $this->input->post('end_date');
     	 $cost_id = $this->input->post('cost_id');
     	 $calc_type = $this->input->post('calc_type');
     	 $increment_qty = $this->input->post('increment_qty');

         $check_valid_qty = "";
         
         // validation for qty
         for ($i=0; $i < $n_count_selling; $i++) { 
         	if (isset($n_from_qtys[$i+1]) && $n_from_qtys[$i+1] <= $n_to_qtys[$i]) {
         		$check_valid_qty = "error";
         	} elseif (isset($n_from_qtys[$i+1]) && ($n_from_qtys[$i+1] - 1) != $n_to_qtys[$i]) {
         		$check_valid_qty = "error";
         	}
         }
         
         // validation for data exists
         for ($i=0; $i < $n_count_selling; $i++) { 
         	// check data to database
         	$check_data_cost = $this->M_cost->check_data_cost($company_id, $selling_service_id, $container_size, $container_type, $container_category, $from_location, $to_location, $start_date_post, $end_date_post, $n_from_qtys[$i], $n_to_qtys[$i], $cost_id);

         	if ($check_data_cost > 0) {
         		$result_check = 1;
         	} else {
         		$result_check = 0;
         	}
         }

         // validation for overlap start date
         for ($i=0; $i < $n_count_selling; $i++) { 
         	$check_data_date = $this->M_cost->check_data_cost_date($company_id, $selling_service_id, $container_size, $container_type, $container_category, $from_location, $to_location, $n_from_qtys[$i], $n_to_qtys[$i], $cost_id);

         	if ($check_data_date->num_rows() > 0) {
         		$check_date = $this->M_cost->valid_date_cost($company_id, $selling_service_id, $container_size, $container_type, $container_category, $from_location, $to_location, $n_from_qtys[$i], $n_to_qtys[$i], $cost_id, $start_date_post, $end_date_post);

     			if ($check_date->num_rows() > 0) {
     				$result_date = 1;
     			} else {
     				$result_date = 0;
     			}
         	} else {
         		$result_date = 0;
         	}
         }

         // $this->load->helper('comman_helper');
         // pr($result_date);

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_addcontainercost', $data);
        } elseif ($check_valid_qty == "error") {
        	$data['qty_error'] = "error";
        	$this->load->view('costs/v_addcontainercost', $data);
        } elseif ($result_check > 0) {
        	$data['data_exist'] = "exist";
        	$this->load->view('costs/v_addcontainercost', $data);
        } elseif ($result_date > 0) {
        	$data['date_error'] = "error";
        	$this->load->view('costs/v_addcontainercost', $data);
        } else{
        	$this->db->trans_begin();
        	try {
        		$cost_type = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_TYPE;
	        	$cost_group = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_GROUP;
	        	$data = array();
	        	for ($i=0; $i < $n_count_selling; $i++) { 
	        		$data[] = array(
		        		'company_id' => $company_id,
		        		'selling_service_id' => $selling_service_id,
		        		'container_size_id' => $container_size,
		        		'container_type_id' => $container_type,
		        		'container_category_id' => $container_category,
		        		'from_location_id' => $from_location,
		        		'to_location_id' => $to_location,
		        		'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
		        		'from_qty' => $n_from_qtys[$i],
		        		'to_qty' => $n_to_qtys[$i],
		        		'cost_currency' => $n_cost_currencys[$i],
		        		'cost_amount' => $n_cost_amounts[$i],
		        		'cost_id' => $this->input->post('cost_id'),
		        		'cost_type_id' => $cost_type,
		        		'cost_group_id' => $cost_group,
		        		'calc_type' => $this->input->post('calc_type'),
		        		'increment_qty' => $this->input->post('increment_qty'),
		        		'aproval_status' => 'N',
		        		'user_id' => $this->nik,
		        		'user_date' => $date
		        	);
	        	}
	        	// $this->M_cost->add_container_cost($data, 'dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE');
	        	if (!$this->db->insert_batch('dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE', $data)) {
	        		throw new Exception("Error Processing Request to Entry Container Trucking Cost", 1);
	        	}
	            
	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Entry Container Trucking Cost", 1);
	        	} else {
	        		$this->session->set_flashdata('success_trucking_cost', 'Cost Rate Submitted Successfully');
	        		$this->db->trans_commit();
	            	redirect(current_url());
	        	}

        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_trucking_cost', $e->getMessage());
        		$this->db->trans_rollback();
            	redirect(current_url());
        	}
        }
	}

	public function add_container_custom_cost()
	{
		$data['company_service'] = $this->M_cost->get_companies()->result();
		$data['company'] = $this->M_cost->get_company()->result();
		$data['selling_service'] = $this->M_cost->get_services_custom();
		$data['container_size'] = $this->M_cost->get_container_size()->result();
		$data['container_type'] = $this->M_cost->get_container_type()->result();
		$data['container_category'] = $this->M_cost->get_container_category()->result();
		$data['custom_location'] = $this->M_cost->get_custom_location()->result();
		$data['custom_kind'] = $this->M_cost->get_custom_kind()->result();
		$data['custom_line'] = $this->M_cost->get_custom_line()->result();
		$data['cost'] = $this->M_cost->get_cost_customs()->result();
		$data['cost_type'] = $this->M_cost->get_cost_type()->result();
		$data['cost_group'] = $this->M_cost->get_cost_group()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();

		$company_id = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$custom_location = $this->uri->segment(5);
		$custom_kind = $this->uri->segment(6);
		$custom_line = $this->uri->segment(7);
		$container_size = $this->uri->segment(8);
		$container_type = $this->uri->segment(9);
		$container_category = $this->uri->segment(10);
		$from_qty = $this->uri->segment(11);
		$to_qty = $this->uri->segment(12);
		$start_date = $this->uri->segment(13);
		$end_date = $this->uri->segment(14);
		

		$data['details'] = $this->M_cost->get_detail_add_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->result();

		$data['check_detail'] = $this->M_cost->check_detail_add_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date);

		// $this->load->helper('comman_helper');
		// pr($data['details']);

		// set validation rules
		 $this->form_validation->set_rules('cost_currency[]', 'Currency', 'required');
		 $this->form_validation->set_rules('cost_amount[]', 'Cost Amount', 'required|numeric');
		 $this->form_validation->set_rules('from_qty[]', 'From Qty', 'required');
		 $this->form_validation->set_rules('to_qty[]', 'To Qty', 'required');
		 $this->form_validation->set_rules('cost_id', 'Cost', 'required');
		 $this->form_validation->set_rules('start_date', 'Start Date', 'required');
		 $this->form_validation->set_rules('end_date', 'End Date', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // declare variable
         $n_from_qtys = $this->input->post('from_qty');
         $n_to_qtys = $this->input->post('to_qty');
         $n_cost_currencys = $this->input->post('cost_currency');
         $n_cost_amounts = $this->input->post('cost_amount');
         $n_count_selling = count($this->input->post('to_qty'));
         $start_date_post = $this->input->post('start_date');
	     $end_date_post = $this->input->post('end_date');
	     $cost_id = $this->input->post('cost_id');
		 $increment_qty = $this->input->post('increment_qty');

         $check_valid_qty = "";
         
         // validation for qty
         for ($i=0; $i < $n_count_selling; $i++) { 
         	if (isset($n_from_qtys[$i+1]) && $n_from_qtys[$i+1] < $n_to_qtys[$i]) {
         		$check_valid_qty = "error";
         	} elseif (isset($n_from_qtys[$i+1]) && ($n_from_qtys[$i+1] - 1) != $n_to_qtys[$i]) {
         		$check_valid_qty = "error";
         	}
         }

         // validation for data exists
         for ($i=0; $i < $n_count_selling; $i++) { 
         	// check data to database
         	$check_data_cost_custom = $this->M_cost->check_data_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $n_from_qtys[$i], $n_to_qtys[$i], $cost_id, $increment_qty, $start_date_post, $end_date_post);

         	if ($check_data_cost_custom > 0) {
         		$result_check = 1;
         	} else {
         		$result_check = 0;
         	}
         }

         // validation for overlap start date
         for ($i=0; $i < $n_count_selling; $i++) { 
         	$check_data_date = $this->M_cost->check_data_custom_date_cost($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $n_from_qtys[$i], $n_to_qtys[$i], $cost_id, $increment_qty);

         	if ($check_data_date->num_rows() > 0) {
         		$check_date = $this->M_cost->valid_date_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $n_from_qtys[$i], $n_to_qtys[$i], $cost_id, $increment_qty, $start_date_post, $end_date_post);

     			if ($check_date->num_rows() > 0) {
     				$result_date = 1;
     			} else {
     				$result_date = 0;
     			}
         	} else {
         		$result_date = 0;
         	}

         }

        // $this->load->helper('comman_helper');
        // pr($result_date);

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_addcontainercustomcost', $data);
        } elseif ($check_valid_qty == "error") {
        	$data['qty_error'] = "error";
        	$this->load->view('costs/v_addcontainercustomcost', $data);
        } elseif ($result_check > 0) {
        	$data['data_exist'] = "exist";
        	$this->load->view('costs/v_addcontainercustomcost', $data);
        } elseif ($result_date > 0) {
        	$data['date_error'] = "error";
        	$this->load->view('costs/v_addcontainercustomcost', $data);
        } else{
        	$this->db->trans_begin();
        	try {
        		$cost_type = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_TYPE;
	        	$cost_group = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_GROUP;
	        	$data_entry = array();
	        	for ($i=0; $i < $n_count_selling; $i++) { 
	        		$data_entry[] = array(
		        		'company_id' => $company_id,
		        		'selling_service_id' => $selling_service,
		        		'custom_location_id' => $custom_location,
		        		'custom_kind_id' => $custom_kind,
		        		'custom_line_id' => $custom_line,
		        		'container_size_id' => $container_size,
		        		'container_type_id' => $container_type,
		        		'container_category_id' => $container_category,
		        		'cost_id' => $this->input->post('cost_id'),
		        		'increment_qty' => $this->input->post('increment_qty'),
		        		'cost_type_id' => $cost_type,
		        		'cost_group_id' => $cost_group,
		        		'from_qty' => $n_from_qtys[$i],
		        		'to_qty' => $n_to_qtys[$i],
		        		'cost_currency' => $n_cost_currencys[$i],
		        		'cost_amount' => $n_cost_amounts[$i],
		        		'approval_status' => 'N',
		        		'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
		        		'user_id' => 'adm001'
		        	);
	        	}
	        	
	        	if (!$this->db->insert_batch('dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data_entry)) {
	        		throw new Exception("Error Processing Request to Entry Customs Clearance Cost", 1);
	        	}

	            if ($this->db->trans_status() === FALSE) {
	            	throw new Exception("Error Processing Request to Entry Customs Clearance Cost", 1);
	            } else {
	            	$this->session->set_flashdata('success_customs_cost', 'Cost Custom Rate Submitted Successfully');
	            	$this->db->trans_commit();
	            	redirect(current_url());
	            }
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_customs_cost', $e->getMessage());
            	$this->db->trans_rollback();
            	redirect(current_url());
        	}
        }
	}

	public function search_cost_group()
	{
		$kode = $this->input->get('term');
		$data['cost_group'] = $this->M_cost->get_cost_group($kode)->result();

		foreach ($data['cost_group'] as $key => $value) {
			$temp_generalid['value'] = $value->GENERAL_DESCRIPTION;
			$temp_generalid['general_id'] = $value->GENERAL_ID;
			$result_generalid[] = $temp_generalid;
		}
		// $this->load->helper('comman_helper');
		// pr($result_location);
		echo json_encode($result_generalid);
	}

	public function print_container_jakarta()
	{
		if (isset($_POST['submit'])) {

			if (!empty($_POST['check']) && in_array('qty', $_POST['check']) && in_array('date', $_POST['check'])) {
				// load currency
				$this->load->helper('currency_helper');
				// container service jakarta
				$data['cost_container_jakarta'] = $this->M_cost->get_all_data_container_print_jakarta()->result();
				$data['tarif_amount_jakarta'] = $this->M_cost->get_tarif_amount_print_jakarta()->result();

				// container cost jakarta
				if (!$data['cost_container_jakarta']) {
					$hasil_jakarta = array();
				} else {
					// select cost continer jakarta
					foreach ($data['cost_container_jakarta'] as $key => $value) {
						$test_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$test_data['COMPANY_NAME'] = $value->COMPANY_NAME;
						$test_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$test_data['SERVICE_NAME'] = $value->SERVICE_NAME;
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
								$test_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
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
					if (!$this->M_cost->check_data_container('20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_container('40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_container('4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_container('45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
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

				}

				$result['hasil_jakarta'] = $hasil_jakarta;
				$html = $this->load->view('reports/r_pricecontainerjakarta4', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Jakarta.pdf', 'I');
			}

			if (empty($_POST['check'])) {
				// load currency
				$this->load->helper('currency_helper');
				// container service jakarta
				$data['cost_container_jakarta'] = $this->M_cost->get_all_data_container_print_jakarta()->result();
				$data['tarif_amount_jakarta'] = $this->M_cost->get_tarif_amount_print_jakarta()->result();

				// container cost jakarta
				if (!$data['cost_container_jakarta']) {
					$hasil_jakarta = array();
				} else {
					// select cost continer jakarta
					foreach ($data['cost_container_jakarta'] as $key => $value) {
						$test_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$test_data['COMPANY_NAME'] = $value->COMPANY_NAME;
						$test_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$test_data['SERVICE_NAME'] = $value->SERVICE_NAME;
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
								$test_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
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
					if (!$this->M_cost->check_data_container('20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_container('40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_container('4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_container('45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
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

				}

				$result['hasil_jakarta'] = $hasil_jakarta;
				$html = $this->load->view('reports/r_pricecontainerjakarta1', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Jakarta.pdf', 'I');
			}

			if (in_array('qty', $_POST['check'])) {
				// load currency
				$this->load->helper('currency_helper');
				// container service jakarta
				$data['cost_container_jakarta'] = $this->M_cost->get_all_data_container_print_jakarta()->result();
				$data['tarif_amount_jakarta'] = $this->M_cost->get_tarif_amount_print_jakarta()->result();

				// container cost jakarta
				if (!$data['cost_container_jakarta']) {
					$hasil_jakarta = array();
				} else {
					// select cost continer jakarta
					foreach ($data['cost_container_jakarta'] as $key => $value) {
						$test_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$test_data['COMPANY_NAME'] = $value->COMPANY_NAME;
						$test_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$test_data['SERVICE_NAME'] = $value->SERVICE_NAME;
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
								$test_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
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
					if (!$this->M_cost->check_data_container('20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_container('40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_container('4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_container('45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
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

				}

				$result['hasil_jakarta'] = $hasil_jakarta;
				$html = $this->load->view('reports/r_pricecontainerjakarta2', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Jakarta.pdf', 'I');
			}

			if (in_array('date', $_POST['check'])) {
				// load currency
				$this->load->helper('currency_helper');
				// container service jakarta
				$data['cost_container_jakarta'] = $this->M_cost->get_all_data_container_print_jakarta()->result();
				$data['tarif_amount_jakarta'] = $this->M_cost->get_tarif_amount_print_jakarta()->result();

				// container cost jakarta
				if (!$data['cost_container_jakarta']) {
					$hasil_jakarta = array();
				} else {
					// select cost continer jakarta
					foreach ($data['cost_container_jakarta'] as $key => $value) {
						$test_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$test_data['COMPANY_NAME'] = $value->COMPANY_NAME;
						$test_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$test_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$test_data['SERVICE_NAME'] = $value->SERVICE_NAME;
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
								$test_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							}

							if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE) {
								$test_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
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
					if (!$this->M_cost->check_data_container('20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_container('40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_container('4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_container('45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
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

				}

				$result['hasil_jakarta'] = $hasil_jakarta;
				$html = $this->load->view('reports/r_pricecontainerjakarta3', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Jakarta.pdf', 'I');
			}
		}
	}

	public function print_container_custom_jakarta()
	{
		if (isset($_POST['submit'])) {
			if (!empty($_POST['check']) && in_array('qty', $_POST['check']) && in_array('date', $_POST['check'])) {
				// load helper currency
				$this->load->helper('currency_helper');
				// custom service jakarta
				$data['cost_custom_jakarta'] = $this->M_cost->get_all_data_custom_print_jakarta()->result();
				$data['tarif_amount_custom_jakarta'] = $this->M_cost->get_tarif_amount_custom_print_jakarta()->result();

				$result['services'] = $this->M_cost->get_services()->result();
				$result['companies'] = $this->M_cost->get_companies()->result();

				// $this->load->helper('comman_helper');
				// pr($data['cost_custom_jakarta']);
				
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
						$print_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$print_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
						$print_data['FROM_QTY'] = $value->FROM_QTY;
						$print_data['TO_QTY'] = $value->TO_QTY;
						$print_data['CALC_TYPE'] = $value->CALC_TYPE;
						$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
						$print_data['START_DATE'] = $value->START_DATE;
						$print_data['END_DATE'] = $value->END_DATE;

						foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
							if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
							}
						}

						if (empty($print_data['TARIF_20'])) {
							$print_data['TARIF_20'] = currency(0);
						}

						if (empty($print_data['TARIF_40'])) {
							$print_data['TARIF_40'] = currency(0);
						}

						if (empty($print_data['TARIF_4H'])) {
							$print_data['TARIF_4H'] = currency(0);
						}

						if (empty($print_data['TARIF_45'])) {
							$print_data['TARIF_45'] = currency(0);
						}

						$hasil_custom_jakarta[] = $print_data;
					}
				}

				// check data tariff_amount custom jakarta
				foreach ($hasil_custom_jakarta as $key => $value) {
					if (!$this->M_cost->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_45']);
					}
					// ------------------------------------------------------------------------
					if (!isset($hasil_custom_jakarta[$key]['TARIF_20'])) {
							$hasil_custom_jakarta[$key]['TARIF_20'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_40'])) {
							$hasil_custom_jakarta[$key]['TARIF_40'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_4H'])) {
							$hasil_custom_jakarta[$key]['TARIF_4H'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_45'])) {
							$hasil_custom_jakarta[$key]['TARIF_45'] = currency(0);
					}

				}

				$result['hasil_custom_jakarta'] = $hasil_custom_jakarta;

				$html = $this->load->view('reports/r_pricecustomjakarta4', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Customs Jakarta.pdf', 'I');
			}

			if (empty($_POST['check'])) {
				// load helper currency
				$this->load->helper('currency_helper');
				// custom service jakarta
				$data['cost_custom_jakarta'] = $this->M_cost->get_all_data_custom_print_jakarta()->result();
				$data['tarif_amount_custom_jakarta'] = $this->M_cost->get_tarif_amount_custom_print_jakarta()->result();

				$result['services'] = $this->M_cost->get_services()->result();
				$result['companies'] = $this->M_cost->get_companies()->result();

				// $this->load->helper('comman_helper');
				// pr($data['cost_custom_jakarta']);
				
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
						$print_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$print_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
						$print_data['FROM_QTY'] = $value->FROM_QTY;
						$print_data['TO_QTY'] = $value->TO_QTY;
						$print_data['CALC_TYPE'] = $value->CALC_TYPE;
						$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
						$print_data['START_DATE'] = $value->START_DATE;
						$print_data['END_DATE'] = $value->END_DATE;

						foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
							if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
							}
						}

						if (empty($print_data['TARIF_20'])) {
							$print_data['TARIF_20'] = currency(0);
						}

						if (empty($print_data['TARIF_40'])) {
							$print_data['TARIF_40'] = currency(0);
						}

						if (empty($print_data['TARIF_4H'])) {
							$print_data['TARIF_4H'] = currency(0);
						}

						if (empty($print_data['TARIF_45'])) {
							$print_data['TARIF_45'] = currency(0);
						}

						$hasil_custom_jakarta[] = $print_data;
					}
				}

				// check data tariff_amount custom jakarta
				foreach ($hasil_custom_jakarta as $key => $value) {
					if (!$this->M_cost->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_45']);
					}
					// ------------------------------------------------------------------------
					if (!isset($hasil_custom_jakarta[$key]['TARIF_20'])) {
							$hasil_custom_jakarta[$key]['TARIF_20'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_40'])) {
							$hasil_custom_jakarta[$key]['TARIF_40'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_4H'])) {
							$hasil_custom_jakarta[$key]['TARIF_4H'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_45'])) {
							$hasil_custom_jakarta[$key]['TARIF_45'] = currency(0);
					}

				}

				$result['hasil_custom_jakarta'] = $hasil_custom_jakarta;

				$html = $this->load->view('reports/r_pricecustomjakarta1', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Customs Jakarta.pdf', 'I');
			}

			if (in_array('qty', $_POST['check'])) {
				#// load helper currency
				$this->load->helper('currency_helper');
				// custom service jakarta
				$data['cost_custom_jakarta'] = $this->M_cost->get_all_data_custom_print_jakarta()->result();
				$data['tarif_amount_custom_jakarta'] = $this->M_cost->get_tarif_amount_custom_print_jakarta()->result();

				$result['services'] = $this->M_cost->get_services()->result();
				$result['companies'] = $this->M_cost->get_companies()->result();

				// $this->load->helper('comman_helper');
				// pr($data['cost_custom_jakarta']);
				
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
						$print_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$print_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
						$print_data['FROM_QTY'] = $value->FROM_QTY;
						$print_data['TO_QTY'] = $value->TO_QTY;
						$print_data['CALC_TYPE'] = $value->CALC_TYPE;
						$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
						$print_data['START_DATE'] = $value->START_DATE;
						$print_data['END_DATE'] = $value->END_DATE;

						foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
							if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
							}
						}

						if (empty($print_data['TARIF_20'])) {
							$print_data['TARIF_20'] = currency(0);
						}

						if (empty($print_data['TARIF_40'])) {
							$print_data['TARIF_40'] = currency(0);
						}

						if (empty($print_data['TARIF_4H'])) {
							$print_data['TARIF_4H'] = currency(0);
						}

						if (empty($print_data['TARIF_45'])) {
							$print_data['TARIF_45'] = currency(0);
						}

						$hasil_custom_jakarta[] = $print_data;
					}
				}

				// check data tariff_amount custom jakarta
				foreach ($hasil_custom_jakarta as $key => $value) {
					if (!$this->M_cost->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_45']);
					}
					// ------------------------------------------------------------------------
					if (!isset($hasil_custom_jakarta[$key]['TARIF_20'])) {
							$hasil_custom_jakarta[$key]['TARIF_20'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_40'])) {
							$hasil_custom_jakarta[$key]['TARIF_40'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_4H'])) {
							$hasil_custom_jakarta[$key]['TARIF_4H'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_45'])) {
							$hasil_custom_jakarta[$key]['TARIF_45'] = currency(0);
					}

				}

				$result['hasil_custom_jakarta'] = $hasil_custom_jakarta;

				$html = $this->load->view('reports/r_pricecustomjakarta2', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Customs Jakarta.pdf', 'I');
			}

			if (in_array('date', $_POST['check'])) {
				// load helper currency
				$this->load->helper('currency_helper');
				// custom service jakarta
				$data['cost_custom_jakarta'] = $this->M_cost->get_all_data_custom_print_jakarta()->result();
				$data['tarif_amount_custom_jakarta'] = $this->M_cost->get_tarif_amount_custom_print_jakarta()->result();

				$result['services'] = $this->M_cost->get_services()->result();
				$result['companies'] = $this->M_cost->get_companies()->result();

				// $this->load->helper('comman_helper');
				// pr($data['cost_custom_jakarta']);
				
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
						$print_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
						$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
						$print_data['CONTAINER_CATEGORY_ID'] = $value->CONTAINER_CATEGORY_ID;
						$print_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
						$print_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
						$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
						$print_data['FROM_QTY'] = $value->FROM_QTY;
						$print_data['TO_QTY'] = $value->TO_QTY;
						$print_data['CALC_TYPE'] = $value->CALC_TYPE;
						$print_data['INCREMENT_QTY'] = $value->INCREMENT_QTY;
						$print_data['START_DATE'] = $value->START_DATE;
						$print_data['END_DATE'] = $value->END_DATE;

						foreach ($data['tarif_amount_custom_jakarta'] as $key1 => $value1) {
							if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '20' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '40' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
							} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '45' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID && $value1->CONTAINER_CATEGORY_ID == $value->CONTAINER_CATEGORY_ID && $value1->FROM_QTY == $value->FROM_QTY && $value1->TO_QTY == $value->TO_QTY && $value1->START_DATE == $value->START_DATE && $value1->END_DATE == $value->END_DATE && $value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CUSTOM_KIND_ID == $value->CUSTOM_KIND_ID && $value1->CUSTOM_LINE_ID == $value->CUSTOM_LINE_ID) {
								$print_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
							}
						}

						if (empty($print_data['TARIF_20'])) {
							$print_data['TARIF_20'] = currency(0);
						}

						if (empty($print_data['TARIF_40'])) {
							$print_data['TARIF_40'] = currency(0);
						}

						if (empty($print_data['TARIF_4H'])) {
							$print_data['TARIF_4H'] = currency(0);
						}

						if (empty($print_data['TARIF_45'])) {
							$print_data['TARIF_45'] = currency(0);
						}

						$hasil_custom_jakarta[] = $print_data;
					}
				}

				// check data tariff_amount custom jakarta
				foreach ($hasil_custom_jakarta as $key => $value) {
					if (!$this->M_cost->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_20']);
					} 

					if (!$this->M_cost->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_40']);
					}

					if (!$this->M_cost->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_4H']);
					}

					if (!$this->M_cost->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CUSTOM_KIND_ID'], $value['CONTAINER_TYPE_ID'], $value['CONTAINER_CATEGORY_ID'], $value['FROM_QTY'], $value['TO_QTY'], $value['START_DATE'], $value['END_DATE'])->result()) {
						unset($hasil_custom_jakarta[$key]['TARIF_45']);
					}
					// ------------------------------------------------------------------------
					if (!isset($hasil_custom_jakarta[$key]['TARIF_20'])) {
							$hasil_custom_jakarta[$key]['TARIF_20'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_40'])) {
							$hasil_custom_jakarta[$key]['TARIF_40'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_4H'])) {
							$hasil_custom_jakarta[$key]['TARIF_4H'] = currency(0);
					}

					if (!isset($hasil_custom_jakarta[$key]['TARIF_45'])) {
							$hasil_custom_jakarta[$key]['TARIF_45'] = currency(0);
					}

				}

				$result['hasil_custom_jakarta'] = $hasil_custom_jakarta;

				$html = $this->load->view('reports/r_pricecustomjakarta3', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price List Customs Jakarta.pdf', 'I');
			}
		}
	}

	public function print_container_surabaya()
	{
		// load helper currency
		$this->load->helper('currency_helper');
		// container service surabaya
		$data['cost_container_surabaya'] = $this->M_cost->get_all_data_container_cost_surabaya()->result();
		$data['tarif_amount_surabaya'] = $this->M_cost->get_tarif_amount_surabaya()->result();

		// container cost surabaya
		if (!$data['cost_container_surabaya']) {
			$hasil_surabaya = array();
		} else {
			// select cost continer surabaya
			foreach ($data['cost_container_surabaya'] as $key => $value) {
				$test_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;
				$test_data['COMPANY_NAME'] = $value->COMPANY_NAME;
				$test_data['START_DATE'] = $value->START_DATE;
				$test_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$test_data['SELLING_SERVICE_ID'] = $value->SELLING_SERVICE_ID;
				$test_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$test_data['SERVICE_NAME'] = $value->SERVICE_NAME;
				$test_data['FROM_QTY'] = $value->FROM_QTY;
				$test_data['TO_QTY'] = $value->TO_QTY;
				$test_data['FROM_NAME'] = $value->FROM_NAME;
				$test_data['FROM_LOCATION_ID'] = $value->FROM_LOCATION_ID;
				$test_data['TO_NAME'] = $value->TO_NAME;
				$test_data['TO_LOCATION_ID'] = $value->TO_LOCATION_ID;
				
				foreach ($data['tarif_amount_surabaya'] as $key1 => $value1) {

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == 20 && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$test_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
					} 

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == 40 && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$test_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
					} 

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$test_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
					} 

					if ($value1->TO_LOCATION_ID == $value->TO_LOCATION_ID && $value1->CONTAINER_SIZE_ID == 45 && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$test_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
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

				$hasil_surabaya[] = $test_data;
			}
		}

		// check data tariff_amount surabaya
		foreach ($hasil_surabaya as $key => $value) {
			if (!$this->M_cost->check_data_container('20', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_surabaya[$key]['TARIF_20']);
			} 

			if (!$this->M_cost->check_data_container('40', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_surabaya[$key]['TARIF_40']);
			}

			if (!$this->M_cost->check_data_container('4H', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_surabaya[$key]['TARIF_4H']);
			}

			if (!$this->M_cost->check_data_container('45', $value['FROM_LOCATION_ID'], $value['TO_LOCATION_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_surabaya[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (empty($hasil_surabaya[$key]['TARIF_20'])) {
					$hasil_surabaya[$key]['TARIF_20'] = currency(0);
			}

			if (empty($hasil_surabaya[$key]['TARIF_40'])) {
					$hasil_surabaya[$key]['TARIF_40'] = currency(0);
			}

			if (empty($hasil_surabaya[$key]['TARIF_4H'])) {
					$hasil_surabaya[$key]['TARIF_4H'] = currency(0);
			}

			if (empty($hasil_surabaya[$key]['TARIF_45'])) {
					$hasil_surabaya[$key]['TARIF_45'] = currency(0);
			}

		}

		$result['hasil_surabaya'] = $hasil_surabaya;
		$this->load->library('pdf');

		$this->pdf->load_view('reports/r_pricecontainersurabaya', $result);
		$this->pdf->setFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
		$this->pdf->Output('PriceListSurabaya.pdf', 'I');
	}

	public function print_container_custom_surabaya()
	{
		// load helper currency
		$this->load->helper('currency_helper');
		// custom service surabaya
		$data['cost_custom_surabaya'] = $this->M_cost->get_all_data_custom_cost_surabaya()->result();
		$data['tarif_amount_custom_surabaya'] = $this->M_cost->get_tarif_amount_custom_surabaya()->result();

		// container custom surabaya
		if (!$data['cost_custom_surabaya']) {
			$hasil_custom_surabaya = array();
		} else {
			// select data cost container custom surabaya
			foreach ($data['cost_custom_surabaya'] as $key => $value) {
				$print_data['CUSTOM_LOCATION'] = $value->CUSTOM_LOCATION;
				$print_data['START_DATE'] = $value->START_DATE;
				$print_data['CUSTOM_LOCATION_ID'] = $value->CUSTOM_LOCATION_ID;
				$print_data['CUSTOM_KIND'] = $value->CUSTOM_KIND;
				$print_data['CUSTOM_KIND_ID'] = $value->CUSTOM_KIND_ID;
				$print_data['CUSTOM_LINE'] = $value->CUSTOM_LINE;
				$print_data['CUSTOM_LINE_ID'] = $value->CUSTOM_LINE_ID;
				$print_data['TARIFF_CURRENCY'] = $value->TARIFF_CURRENCY;
				$print_data['CONTAINER_TYPE_ID'] = $value->CONTAINER_TYPE_ID;
				$print_data['COMPANY_SERVICE_ID'] = $value->COMPANY_SERVICE_ID;

				foreach ($data['tarif_amount_custom_surabaya'] as $key1 => $value1) {
					if ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == 20 && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$print_data['TARIF_20'] = currency($value1->TARIFF_AMOUNT);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == 40 && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$print_data['TARIF_40'] = currency($value1->TARIFF_AMOUNT);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == '4H' && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$print_data['TARIF_4H'] = currency($value1->TARIFF_AMOUNT);
					} elseif ($value1->CUSTOM_LOCATION_ID == $value->CUSTOM_LOCATION_ID && $value1->CONTAINER_SIZE_ID == 45 && $value1->CONTAINER_TYPE_ID == $value->CONTAINER_TYPE_ID) {
						$print_data['TARIF_45'] = currency($value1->TARIFF_AMOUNT);
					}
				}

				// if data per container size empty
				if (empty($print_data['TARIF_20'])) {
					$print_data['TARIF_20'] = currency(0);
				}

				if (empty($print_data['TARIF_40'])) {
					$print_data['TARIF_40'] = currency(0);
				}

				if (empty($print_data['TARIF_4H'])) {
					$print_data['TARIF_4H'] = currency(0);
				}

				if (empty($print_data['TARIF_45'])) {
					$print_data['TARIF_45'] = currency(0);
				}

				$hasil_custom_surabaya[] = $print_data;
			}
		}

		// check data tariff_amount custom surabaya
		foreach ($hasil_custom_surabaya as $key => $value) {
			if (!$this->M_cost->check_data_custom('20', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_custom_surabaya[$key]['TARIF_20']);
			} 

			if (!$this->M_cost->check_data_custom('40', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_custom_surabaya[$key]['TARIF_40']);
			}

			if (!$this->M_cost->check_data_custom('4H', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_custom_surabaya[$key]['TARIF_4H']);
			}

			if (!$this->M_cost->check_data_custom('45', $value['CUSTOM_LOCATION_ID'], $value['CUSTOM_LINE_ID'], $value['CONTAINER_TYPE_ID'])->result()) {
				unset($hasil_custom_surabaya[$key]['TARIF_45']);
			}
			// ------------------------------------------------------------------------
			if (empty($hasil_custom_surabaya[$key]['TARIF_20'])) {
					$hasil_custom_surabaya[$key]['TARIF_20'] = currency(0);
			}

			if (empty($hasil_custom_surabaya[$key]['TARIF_40'])) {
					$hasil_custom_surabaya[$key]['TARIF_40'] = currency(0);
			}

			if (empty($hasil_custom_surabaya[$key]['TARIF_4H'])) {
					$hasil_custom_surabaya[$key]['TARIF_4H'] = currency(0);
			}

			if (empty($hasil_custom_surabaya[$key]['TARIF_45'])) {
					$hasil_custom_surabaya[$key]['TARIF_45'] = currency(0);
			}

		}

		$result['hasil_custom_surabaya'] = $hasil_custom_surabaya;

		// $this->load->helper('comman_helper');
		// pr($result['hasil_custom_surabaya']);
		$this->load->library('pdf');

		$this->pdf->load_view('reports/r_pricecustomsurabaya', $result);
		$this->pdf->setFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
		$this->pdf->Output('PriceListCustomSurabaya.pdf', 'I');
	}

	public function edit_container_cost()
	{
		$company_id = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$cost_id = $this->uri->segment(5);
		$container_size = $this->uri->segment(6);
		$container_type = $this->uri->segment(7);
		$container_category = $this->uri->segment(8);
		$from_qty = $this->uri->segment(9);
		$to_qty = $this->uri->segment(10);
		$from_location = $this->uri->segment(11);
		$to_location = $this->uri->segment(12);
		$start_date = $this->uri->segment(13);
		$end_date = $this->uri->segment(14);

		$data['details'] = $this->M_cost->get_detail_edit_container_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->result();

		$data['data_cost'] = $this->M_cost->get_data_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->result();

		// set validation rules
		$this->form_validation->set_rules('cost_amount', 'Cost Amount', 'required|numeric');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');        

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editcontainercost', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare 
		        $cost_type = $this->M_cost->check_full_data($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->row()->COST_TYPE_ID;

		        $cost_group = $this->M_cost->check_full_data($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->row()->COST_GROUP_ID;

		        $calc_type = $this->M_cost->check_full_data($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->row()->CALC_TYPE;

		        $aproval_status = $this->M_cost->check_full_data($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->row()->APROVAL_STATUS;

		        $increment_qty = $this->M_cost->check_full_data($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->row()->INCREMENT_QTY;

		        $cost_currency = $this->M_cost->check_full_data($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)->row()->COST_CURRENCY;

				$cost_amount = $this->M_cost->check_cost_amount($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date);

	        	// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$data = array(
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'cost_amount' => $this->input->post('cost_amount')
			        );

		        	$update_cost = $this->M_cost->update_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, 'dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE', $data);
		        	if ($update_cost == FALSE) {
		        		throw new Exception("Error Processing Request to Update Container Trucking Cost", 1);
		        	}
	        	} else {
	        		$first_record = array(
	        			'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'cost_id' => $cost_id,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'cost_type_id' => $cost_type,
	        			'cost_group_id' => $cost_group,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'aproval_status' => $aproval_status,
	        			'cost_currency' => $cost_currency,
		        		'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
		        		'cost_amount' => $this->input->post('cost_amount')
			        );

			        $move_record = array(
			        	'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'cost_id' => $cost_id,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'cost_type_id' => $cost_type,
	        			'cost_group_id' => $cost_group,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'aproval_status' => $aproval_status,
	        			'cost_currency' => $cost_currency,
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'cost_amount' => $cost_amount
			        );

			        // input new first cost
			        if (!$this->db->insert('dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request to Entry Container Trucking Cost", 1);
			        }
			        // move last record to history and delete that record
			        // $this->M_cost->move_record_cost('dbo.HICOST_SERVICE_CONTAINER_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HICOST_SERVICE_CONTAINER_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request to Moving Container Trucking Cost History", 1);
			        }
			        // delete last record
			        $delete_last_record = $this->M_cost->delete_last_record($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $cost_type, $cost_group, $calc_type, $increment_qty, $aproval_status, $cost_currency, $start_date, $end_date,'dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE');
			        if ($delete_last_record == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Last Record Container Trucking Cost", 1);
			        }
	        	}

	        	if ($this->db->trans_status() === FALSE) {
	            	throw new Exception("Error Processing Request to Edit Container Trucking Cost", 1);
	            } else {
	            	$this->session->set_flashdata('success_edit_container_cost', 'Cost Rate updated successfully!');
	            	$this->db->trans_commit();
	            	redirect(current_url());
	            }
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_container_cost', $e->getMessage());
            	$this->db->trans_rollback();
            	redirect(current_url());
        	}
        }
	}

	function edit_container()
	{
		$from_location = $this->uri->segment(3);
		$to_location = $this->uri->segment(4);
		$container_type = $this->uri->segment(5);
		$from_qty = $this->uri->segment(6);
		$to_qty = $this->uri->segment(7);
		$container_size = $this->uri->segment(8);
		$container_category = $this->uri->segment(9);
		$company_id = $this->uri->segment(10);
		$selling_service = $this->uri->segment(11);
		$start_date = $this->uri->segment(12);
		$end_date = $this->uri->segment(13);

		$data['details'] = $this->M_cost->get_detail_add_cost($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category)->result();

		$checks = $this->M_cost->get_data_selling2($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)->num_rows();

		if ($checks <= 0) {
			$data['check_selling'] = 0;
		} else {
			$calc_type = $this->M_cost->get_data_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)->row()->CALC_TYPE;

			$increment_qty = $this->M_cost->get_data_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)->row()->INCREMENT_QTY;

			$tariff_currency = $this->M_cost->get_data_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)->row()->TARIFF_CURRENCY;

			$approval_status = $this->M_cost->get_data_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)->row()->APPROVAL_STATUS;

			$tariff_amount = $this->M_cost->get_data_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)->row()->TARIFF_AMOUNT;

			$data['check_selling'] = 1;
		}

		$data['data_selling'] = $this->M_cost->get_data_selling2($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)->result();

		// $this->load->helper('comman_helper');
		// pr($data['data_selling']);

		// set validation rules
		$this->form_validation->set_rules('tariff_amount', 'Tariff Amount', 'required|numeric');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editcontainer', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$data = array(
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'tariff_amount' => $this->input->post('tariff_amount')
			        );

		        	$update_selling = $this->M_cost->update_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, 'dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE', $data);
		            if ($update_selling == FALSE) {
		            	throw new Exception("Error Processing Request to Update Container Trucking Selling", 1);
		            }
	        	} else {
	        		$first_record = array(
	        			'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $this->input->post('tariff_amount')
			        );

			        $move_record = array(
			        	'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'start_date' => $start_date,
		        		'end_date' => $end_date,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $tariff_amount
			        );

			        // input new first cost
			        // $this->M_cost->add_first_cost('dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE', $first_record);
			        if (!$this->db->insert('dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request Entry Container Trucking Selling", 1);
			        }
			        // move last record to history and delete that record
			        // $this->M_cost->move_record_cost('dbo.HISELLING_SERVICE_CONTAINER_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HISELLING_SERVICE_CONTAINER_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request Move Container Trucking Selling to History", 1);
			        }
			        // delete last record
			        $delete_last_record_selling = $this->M_cost->delete_last_record_selling($company_id, $selling_service, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $calc_type, $increment_qty, $approval_status, $tariff_currency, $start_date, $end_date,'dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');
			        if ($delete_last_record_selling == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Last Record Container Trucking Selling", 1);
			        }
	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Edit Container Trucking Selling", 1);
	        	} else {
	        		$this->session->set_flashdata('success_edit_container_trucking', 'Selling Rate updated successfully!');
	        		$this->db->trans_commit();
		            redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_container_trucking', $e->getMessage());
        		$this->db->trans_rollback();
	            redirect(current_url());
        	}
        }
	}

	function edit_container_custom()
	{
		$company_id = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$custom_location = $this->uri->segment(5);
		$custom_kind = $this->uri->segment(6);
		$custom_line = $this->uri->segment(7);
		$container_size = $this->uri->segment(8);
		$container_type = $this->uri->segment(9);
		$container_category = $this->uri->segment(10);
		$from_qty = $this->uri->segment(11);
		$to_qty = $this->uri->segment(12);
		$start_date = $this->uri->segment(13);
		$end_date = $this->uri->segment(14);
		$increment_param = $this->uri->segment(15);
		$calc_param = $this->uri->segment(16);
		$currency_param = $this->uri->segment(17);
		// // check company
  //       $company = $this->M_cost->check_company($company_service);

		$data['details'] = $this->M_cost->get_detail_add_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->result();

		// $data['data_customs'] = $this->M_cost->get_data_customs($company_service, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->result();
		$temp_customs = $this->M_cost->get_data_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date);
		// get date with param
		$temp_date = $this->M_cost->get_date_customs($company_id, $custom_location, $custom_kind, $custom_line, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date);

		// $this->load->helper('comman_helper');
		// pr($temp_customs);

		if ($temp_customs->num_rows() < 1) {
			$data['TARIFF_AMOUNT'] = "";
			$data['START_DATE'] = $temp_date->row()->START_DATE;
			$data['END_DATE'] = $temp_date->row()->END_DATE;	
		} else {
			$data['TARIFF_AMOUNT'] = $temp_customs->row()->TARIFF_AMOUNT;
			$data['START_DATE'] = $temp_customs->row()->START_DATE;
			$data['END_DATE'] = $temp_customs->row()->END_DATE;
		}

		// set validation rules
		$this->form_validation->set_rules('tariff_amount', 'Tariff Amount', 'required|numeric');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editcustoms', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$company_id = $this->uri->segment(3);
					$selling_service = $this->uri->segment(4);
					$custom_location = $this->uri->segment(5);
					$custom_kind = $this->uri->segment(6);
					$custom_line = $this->uri->segment(7);
					$container_size = $this->uri->segment(8);
					$container_type = $this->uri->segment(9);
					$container_category = $this->uri->segment(10);
					$from_qty = $this->uri->segment(11);
					$to_qty = $this->uri->segment(12);
					$start_date = $this->uri->segment(13);
					$end_date = $this->uri->segment(14);
					$increment_param = $this->uri->segment(15);
					$calc_param = $this->uri->segment(16);
					$currency_param = $this->uri->segment(17);
	        		if ($temp_customs->num_rows() < 1) {
	        			$data = array(
	        				'company_id' => $company_id,
		        			'selling_service_id' => $selling_service,
		        			'custom_location_id' => $custom_location,
		        			'custom_kind_id' => $custom_kind,
		        			'custom_line_id' => $custom_line,
		        			'container_size_id' => $container_size,
		        			'container_type_id' => $container_type,
		        			'container_category_id' => $container_category,
		        			'from_qty' => $from_qty,
		        			'to_qty' => $to_qty,
		        			'start_date' => $start_date,
			        		'end_date' => $end_date,
		        			'calc_type' => $calc_param,
		        			'increment_qty' => $increment_param,
		        			'tariff_currency' => $currency_param,
			        		'tariff_amount' => $this->input->post('tariff_amount')
	        			);
	        			if (!$this->db->insert('dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data)) {
	        				throw new Exception("Error Processing Request to Update Customs Clearance Selling", 1);
	        			}
	        			// unset($data);
	        			// $this->session->set_flashdata('success', 'Customs Rate updated successfully!.');
			         //    redirect(current_url());
	        		} else {
	        			$data = array(
			        		'start_date' => $start_date,
			        		'end_date' => $end_date,
			        		'tariff_amount' => $this->input->post('tariff_amount')
				        );

			        	$update_customs = $this->M_cost->update_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, 'dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data);
			        	if ($update_customs == FALSE) {
			        		throw new Exception("Error Processing Request to Update Customs Selling Rate", 1);
			        	}
			            // $this->session->set_flashdata('success', 'Customs Rate updated successfully!.');
			            // redirect(current_url());	
	        		}
	        		
	        	} else {
	        		$calc_type = $this->M_cost->get_data_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->row()->CALC_TYPE;

					$increment_qty = $this->M_cost->get_data_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->row()->INCREMENT_QTY;

					$tariff_currency = $this->M_cost->get_data_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->row()->TARIFF_CURRENCY;

					$approval_status = $this->M_cost->get_data_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->row()->APPROVAL_STATUS;

					$tariff_amount = $this->M_cost->get_data_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)->row()->TARIFF_AMOUNT;

	        		$first_record = array(
	        			'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'custom_location_id' => $custom_location,
	        			'custom_kind_id' => $custom_kind,
	        			'custom_line_id' => $custom_line,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $this->input->post('tariff_amount')
			        );

			        $move_record = array(
			        	'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'custom_location_id' => $custom_location,
	        			'custom_kind_id' => $custom_kind,
	        			'custom_line_id' => $custom_line,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'start_date' => $start_date,
		        		'end_date' => $end_date,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $tariff_amount
			        );

			        // input new first cost
			        // $this->M_cost->add_first_custom('dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $first_record);
			        if (!$this->db->insert('dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request to Entry Customs Clearance Selling", 1);
			        }
			        // move last record to history and delete that record
			        // $this->M_cost->move_record_custom('dbo.HISELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HISELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request to Move History Customs Clearance Selling", 1);
			        }
			        // delete last record
			        $delete_last_record_custom = $this->M_cost->delete_last_record_custom($company_id, $selling_service, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $calc_type, $increment_qty, $approval_status, $tariff_currency, $start_date, $end_date,'dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE');
			        if ($delete_last_record_custom == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Customs Clearance Selling That Have Been Changed", 1);
			        }

					// unset data
					unset($calc_type);
					unset($increment_qty);
					unset($tariff_currency);
					unset($tariff_amount);
					unset($approval_status);
	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Edit Customs Clearance Selling", 1);
	        	} else {
	        		$this->session->set_flashdata('success_edit_customs_selling', 'Customs Rate updated successfully!');
	        		$this->db->trans_commit();
		            redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_customs_selling', $e->getMessage());
        		$this->db->trans_rollback();
	            redirect(current_url());
        	}
        }
	}

	public function edit_customs_cost()
	{
		$company_id = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$custom_location = $this->uri->segment(11);
		// $company = $this->M_cost->check_company($company_service);
		$custom_line = $this->uri->segment(12);
		$custom_kind = $this->uri->segment(13);
		$container_size = $this->uri->segment(6);
		$container_type = $this->uri->segment(7);
		$container_category = $this->uri->segment(8);
		$cost_id = $this->uri->segment(5);
		$start_date = $this->uri->segment(14);
		$end_date = $this->uri->segment(15);
		$from_qty = $this->uri->segment(9);
		$to_qty = $this->uri->segment(10);

		$data['details'] = $this->M_cost->get_detail_edit_customs_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->result();

		// $data['data_cost'] = $this->M_cost->get_data_cost($company_service, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location)->result();

		// set validation rules
		$this->form_validation->set_rules('cost_amount', 'Cost Amount', 'required|numeric');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        // declare 
        $checks = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->num_rows();

  //       $this->load->helper('comman_helper');
		// pr($checks);

		if ($checks <= 0) {
			$data['check_selling'] = 0;
		} else {
			$cost_type = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->row()->COST_TYPE_ID;

			$cost_group = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->row()->COST_GROUP_ID;

	        $calc_type = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->row()->CALC_TYPE;

	        $approval_status = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->row()->APPROVAL_STATUS;

	        $increment_qty = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->row()->INCREMENT_QTY;

	        $cost_currency = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->row()->COST_CURRENCY;

			$cost_amount = $this->M_cost->check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)->row()->COST_AMOUNT;     

			$data['check_selling'] = 1;
		}   

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editcustomscost', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$data = array(
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'cost_amount' => $this->input->post('cost_amount')
			        );

		        	$update_cost_customs = $this->M_cost->update_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date, 'dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $data);
		        	if ($update_cost_customs == FALSE) {
		        		throw new Exception("Error Processing Request to Update Customs Clearance Cost", 1);
		        	}
		            // $this->session->set_flashdata('success', 'Cost Rate updated successfully!');
		            // redirect(current_url());
	        	} else {
	        		// asli
	        		$first_record = array(
	        			'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'custom_location_id' => $custom_location,
	        			'custom_kind_id' => $custom_kind,
	        			'custom_line_id' => $custom_line,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'cost_id' => $cost_id,
	        			'cost_type_id' => $cost_type,
	        			'cost_group_id' => $cost_group,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'cost_currency' => $cost_currency,
		        		'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
		        		'cost_amount' => $this->input->post('cost_amount')
			        );

			        $move_record = array(
			        	'company_id' => $company_id,
	        			'selling_service_id' => $selling_service,
	        			'custom_location_id' => $custom_location,
	        			'custom_kind_id' => $custom_kind,
	        			'custom_line_id' => $custom_line,
	        			'container_size_id' => $container_size,
	        			'container_type_id' => $container_type,
	        			'container_category_id' => $container_category,
	        			'from_qty' => $from_qty,
	        			'to_qty' => $to_qty,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'cost_id' => $cost_id,
	        			'cost_type_id' => $cost_type,
	        			'cost_group_id' => $cost_group,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'approval_status' => $approval_status,
	        			'cost_currency' => $cost_currency,
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'cost_amount' => $cost_amount
			        );

			        // input new first cost
			        // $this->M_cost->add_first_cost('dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $first_record);
			        if (!$this->db->insert('dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request to Entry Customs Clearance Cost", 1);
			        }
			        // move last record to history and delete that record
			        // $this->M_cost->move_record_cost('dbo.HICOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HICOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request to Move Customs Clearance Cost History", 1);
			        }
			        // delete last record
			        $delete_last_record_customs_cost = $this->M_cost->delete_last_record_customs_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date,'dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE');
			        if ($delete_last_record_customs_cost == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Last Record Customs Clearance Cost", 1);
			        }
	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Update Customs Clearance Cost", 1);
	        	} else {
	        		$this->session->set_flashdata('success_edit_customs_cost', 'Cost Rate updated successfully!');
	        		$this->db->trans_commit();
		            redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_customs_cost', $e->getMessage);
        		$this->db->trans_rollback();
	            redirect(current_url());
        	}
        }
	}

	public function add_location_selling()
	{
		$data['company_service'] = $this->M_cost->get_companies()->result();
		$data['selling_service'] = $this->M_cost->get_services_location();
		$data['truck_id'] = $this->M_cost->get_truck_id()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();

		// set validation
		$this->form_validation->set_rules('company_service_id', 'Company Service', 'required');
		$this->form_validation->set_rules('distance', 'Distance', 'required|numeric');
		$this->form_validation->set_rules('distance_per_litre', 'Distance per Litre', 'required|numeric');
		$this->form_validation->set_rules('truck_id', 'Kind of Truck', 'required');
		$this->form_validation->set_rules('temp_from_location', 'From / to Location', 'required');
		$this->form_validation->set_rules('from_location_id', 'Code From / To Location', 'required');
		$this->form_validation->set_rules('temp_to_location', 'Destination', 'required');
		$this->form_validation->set_rules('to_location_id', 'Code Destination', 'required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('increment_qty', 'Increment Qty', 'required');
		$this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		$this->form_validation->set_rules('tariff_currency', 'Currency', 'required');
		$this->form_validation->set_rules('tariff_amount', 'Tariff Amount', 'required|numeric');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// set varible from post
		$company_service = $this->input->post('company_service_id');
		$selling_service = $this->input->post('selling_service_id');
		$distance = $this->input->post('distance');
		$distance_per_litre = $this->input->post('distance_per_litre');
		$truck_id = $this->input->post('truck_id');
		$from_location = $this->input->post('from_location_id');
		$to_location = $this->input->post('to_location_id');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$increment_qty = $this->input->post('increment_qty');
		$calc_type = $this->input->post('calc_type');
		$currency = $this->input->post('tariff_currency');
		$amount = $this->input->post('tariff_amount');

		// validation data exist
		$check_data_location = $this->M_cost->check_data_location($company_service, $selling_service, $distance, $distance_per_litre, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty);

		if ($check_data_location->num_rows() > 0) {
			$result_check = 1;
		} else {
			$result_check = 0;
		}

		// validation for overlap start date
		$check_data_date_location = $this->M_cost->check_data_date_location($company_service, $selling_service, $distance, $distance_per_litre, $truck_id, $from_location, $to_location, $increment_qty);

     	if ($check_data_date_location->num_rows() > 0) {
     		$check_date = $this->M_cost->valid_date_selling_location($company_service, $selling_service, $distance, $distance_per_litre, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty);

 			if ($check_date->num_rows() > 0) {
 				$result_date = 1;
 			} else {
 				$result_date = 0;
 			}
     	} else {
     		$result_date = 0;
     	}

		// input and check validation
		 // check for validation
        if ($this->form_validation->run() == FALSE) {
           $this->load->view('costs/v_addlocation', $data);
        } elseif ($result_check > 0) {
        	$data['data_exist'] = "exist";
        	$this->load->view('costs/v_addlocation', $data);
        } elseif ($result_date > 0) {
        	$data['date_error'] = "error";
        	$this->load->view('costs/v_addlocation', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		$data = array(
	        		'company_service_id' => $company_service,
	        		'selling_service_id' => $selling_service,
	        		'distance' => $distance,
	        		'distance_per_litre' => $distance_per_litre,
	        		'truck_id' => $truck_id,
	        		'from_location_id' => $from_location,
	        		'to_location_id' => $to_location,
	        		'start_date' => $start_date,
	        		'end_date' => $end_date,
	        		'increment_qty' => $increment_qty,
	        		'calc_type' => $calc_type,
	        		'tariff_currency' => $currency,
	        		'tariff_amount' => $amount
	        	);

	        	// insert limitation from header
	        	$check_limit = $this->M_cost->check_limit_location($company_service, $selling_service, $from_location, $to_location, $truck_id)->num_rows();

	        	if ($check_limit < 1) {
	        		$data_limit = array(
	        			'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'truck_id' => $truck_id
	        		);

	        		if (!$this->db->insert('dbo.MLIMITATIONS_PRICE_LOCATION_ATTRIBUTE', $data_limit)) {
	        			throw new Exception("Error Processing Request to Entry Floor Price Location Selling", 1);
	        		}
	        	}

	        	if (!$this->db->insert('dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE', $data)) {
	        		throw new Exception("Error Processing Request to Entry Location Rate Selling", 1);
	        	}
	        	
	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Entry Location Rate Selling", 1);
	        	} else {
	        		$this->session->set_flashdata('success_entry_location_selling', 'Selling Location Rate submited successfully!');
	        		$this->db->trans_commit();
		        	redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_entry_location_selling', $e->getMessage());
        		$this->db->trans_rollback();
	        	redirect(current_url());
        	}
        }
	}

	function add_location_cost()
	{
		// get data from segment url
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$from_location = $this->uri->segment(5);
		$to_location = $this->uri->segment(6);
		$truck_id = $this->uri->segment(7);
		$distance = $this->uri->segment(8);
		$distance_per_litre = $this->uri->segment(9);
		$start_date = $this->uri->segment(10);
		$end_date = $this->uri->segment(11);
		$increment_qty = $this->uri->segment(12);
		$calc_type = $this->uri->segment(13);

		$data['details'] = $this->M_cost->get_detail_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty)->result();

		$data['cost'] = $this->M_cost->get_cost()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();

		// set rules
		$this->form_validation->set_rules('cost_id', 'Cost', 'required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('increment_qty', 'Increment Qty', 'required');
		$this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		$this->form_validation->set_rules('cost_currency', 'Cost Currency', 'required');
		$this->form_validation->set_rules('cost_amount', 'Cost Amount', 'required|numeric');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        // set varible from post
        $cost_id = $this->input->post('cost_id');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$increment_qty = $this->input->post('increment_qty');
		$calc_type = $this->input->post('calc_type');
		$currency = $this->input->post('cost_currency');
		$amount = $this->input->post('cost_amount');

        // validation data exist
		$check_data_cost_location = $this->M_cost->check_data_cost_location($company_service, $selling_service, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty, $cost_id);

		if ($check_data_cost_location->num_rows() > 0) {
			$result_check = 1;
		} else {
			$result_check = 0;
		}

		// validation for overlap start date
		$check_data_date_cost_location = $this->M_cost->check_data_date_cost_location($company_service, $selling_service, $cost_id, $truck_id, $from_location, $to_location, $increment_qty);

     	if ($check_data_date_cost_location->num_rows() > 0) {
     		$check_date = $this->M_cost->valid_date_cost_location($company_service, $selling_service, $cost_id, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty);

 			if ($check_date->num_rows() > 0) {
 				$result_date = 1;
 			} else {
 				$result_date = 0;
 			}
     	} else {
     		$result_date = 0;
     	}

		// input and check validation
		 // check for validation
        if ($this->form_validation->run() == FALSE) {
           $this->load->view('costs/v_addlocationcost', $data);
        } elseif ($result_check > 0) {
        	$data['data_exist'] = "exist";
        	$this->load->view('costs/v_addlocationcost', $data);
        } elseif ($result_date > 0) {
        	$data['date_error'] = "error";
        	$this->load->view('costs/v_addlocationcost', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		$cost_type = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_TYPE;
	        	$cost_group = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_GROUP;
	        	$data = array(
	        		'company_service_id' => $company_service,
	        		'selling_service_id' => $selling_service,
	        		'cost_id' => $cost_id,
	        		'cost_type_id' => $cost_type,
	        		'cost_group_id' => $cost_group,
	        		'truck_id' => $truck_id,
	        		'from_location_id' => $from_location,
	        		'to_location_id' => $to_location,
	        		'start_date' => $start_date,
	        		'end_date' => $end_date,
	        		'increment_qty' => $increment_qty,
	        		'calc_type' => $calc_type,
	        		'cost_currency' => $currency,
	        		'cost_amount' => $amount
	        	);

	        	if (!$this->db->insert('dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE', $data)) {
	        		throw new Exception("Error Processing Request to Location Cost Rate", 1);
	        	}
	        	
	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Entry Location Cost Rate", 1);
	        	} else {
	        		$this->session->set_flashdata('success_entry_location_cost', 'Cost Location Rate submited successfully!');
	        		$this->db->trans_commit();
		        	redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_entry_location_cost', $e->getMessage());
        		$this->db->trans_rollback();
	        	redirect(current_url());
        	}
        }	
	}

	public function edit_location()
	{
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$from_location = $this->uri->segment(5);
		$to_location = $this->uri->segment(6);
		$truck_id = $this->uri->segment(7);
		$distance = $this->uri->segment(8);
		$distance_per_litre = $this->uri->segment(9);
		$start_date = $this->uri->segment(10);
		$end_date = $this->uri->segment(11);
		$increment_qty = $this->uri->segment(12);
		$calc_type = $this->uri->segment(13);

		$data['details'] = $this->M_cost->get_detail_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty)->result();
		$data['data_selling'] = $this->M_cost->get_data_selling_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty)->result();

		$tariff_currency = $this->M_cost->check_full_data_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $increment_qty)->row()->TARIFF_CURRENCY;

		// $this->load->helper('comman_helper');
		// pr($tariff_currency);

		$tariff_amount = $this->M_cost->check_full_data_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $increment_qty)->row()->TARIFF_AMOUNT;

		// set rules
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('tariff_amount', 'Tariff Amount', 'required|numeric');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editlocation', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$data = array(
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'tariff_amount' => $this->input->post('tariff_amount')
			        );

		        	$update_selling_location = $this->M_cost->update_selling_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty, 'dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE', $data);
		        	if ($update_selling_location == FALSE) {
		        		throw new Exception("Error Processing Request to Update Location Selling", 1);
		        	}
		            // $this->session->set_flashdata('success', 'Selling Rate updated successfully!');
		            // redirect(current_url());
	        	} else {
	        		$first_record = array(
	        			'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'truck_id' => $truck_id,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $this->input->post('tariff_amount'),
		        		'distance' => $distance,
		        		'distance_per_litre' => $distance_per_litre

			        );

			        $move_record = array(
			        	'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'truck_id' => $truck_id,
	        			'start_date' => $start_date,
		        		'end_date' => $end_date,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $tariff_amount,
		        		'distance' => $distance,
		        		'distance_per_litre' => $distance_per_litre
			        );

			        // input new first cost
			        // $this->M_cost->add_first_cost('dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE', $first_record);
			        if (!$this->db->insert('dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request to Entry Location Selling", 1);
			        }
			        // move last record to history and delete that record
			        // $this->M_cost->move_record_cost('dbo.HISELLING_SERVICE_LOCATION_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HISELLING_SERVICE_LOCATION_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request to Moving History Location Selling", 1);
			        }
			        // delete last record
			        $delete_last_record_selling_location = $this->M_cost->delete_last_record_selling_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty,'dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE');
			        if ($delete_last_record_selling_location == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Last Record Location Selling", 1);
			        }
	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Update Location Selling", 1);
	        	} else {
	        		$this->session->set_flashdata('success_edit_location_selling', 'Selling Rate updated successfully!');
	        		$this->db->trans_commit();
		            redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_location_selling', $e->getMessage());
        		$this->db->trans_rollback();
	            redirect(current_url());
        	}
        }
	}

	function location_detail()
	{
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$from_location = $this->uri->segment(5);
		$to_location = $this->uri->segment(6);
		$truck_id = $this->uri->segment(7);
		$distance = $this->uri->segment(8);
		$distance_per_litre = $this->uri->segment(9);
		$start_date = $this->uri->segment(10);
		$end_date = $this->uri->segment(11);
		$increment_qty = $this->uri->segment(12);
		$calc_type = $this->uri->segment(13);

		$data['details'] = $this->M_cost->get_detail_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty)->result();

		$data['cost_detail'] = $this->M_cost->get_location_cost_detail($from_location, $to_location, $truck_id, $start_date, $end_date, $increment_qty)->result();

		$this->load->view('costs/v_detaillocationcost', $data);
	}

	function edit_location_cost()
	{
		// get data from segment url
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$cost_id = $this->uri->segment(5);
		$from_location = $this->uri->segment(6);
		$to_location = $this->uri->segment(7);
		$truck_id = $this->uri->segment(8);
		$start_date = $this->uri->segment(9);
		$end_date = $this->uri->segment(10);
		$increment_qty = $this->uri->segment(11);

		$data['details'] = $this->M_cost->get_detail_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $start_date, $end_date, $increment_qty)->result();

		$cost_currency = $this->M_cost->check_full_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $increment_qty)->row()->COST_CURRENCY;

		$cost_amount = $this->M_cost->check_full_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $increment_qty)->row()->COST_AMOUNT;

		$cost_type = $this->M_cost->check_full_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $increment_qty)->row()->COST_TYPE_ID;

		$cost_group = $this->M_cost->check_full_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $increment_qty)->row()->COST_GROUP_ID;

		$calc_type = $this->M_cost->check_full_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id,  $increment_qty)->row()->CALC_TYPE;

		// $this->load->helper('comman_helper');
		// pr($tariff_currency);

		// set rules
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('cost_amount', 'Cost Amount', 'required|numeric');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editlocationcost', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$data = array(
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'cost_amount' => $this->input->post('cost_amount')
			        );

		        	$update_cost_location = $this->M_cost->update_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $start_date, $end_date, $increment_qty, 'dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE', $data);
		        	if ($update_cost_location == FALSE) {
		        		throw new Exception("Error Processing Request to Update Location Cost", 1);
		        	}
		            // $this->session->set_flashdata('success', 'Cost rate updated successfully!');
		            // redirect(current_url());
	        	} else {
	        		$first_record = array(
	        			'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'truck_id' => $truck_id,
	        			'cost_id' => $cost_id,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'cost_currency' => $cost_currency,
		        		'cost_amount' => $this->input->post('cost_amount'),
		        		'cost_type_id' => $cost_type,
		        		'cost_group_id' => $cost_group

			        );

			        $move_record = array(
			        	'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'truck_id' => $truck_id,
	        			'cost_id' => $cost_id,
	        			'start_date' => $start_date,
		        		'end_date' => $end_date,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'cost_currency' => $cost_currency,
		        		'cost_amount' => $cost_amount,
		        		'cost_type_id' => $cost_type,
		        		'cost_group_id' => $cost_group
			        );

			        // input new first cost
			        // $this->M_cost->add_first_cost('dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE', $first_record);
			        if (!$this->db->insert('dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request to Entry Location Cost", 1);
			        }
			        // move last record to history and delete that record
			        // $this->M_cost->move_record_cost('dbo.HICOST_SERVICE_LOCATION_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HICOST_SERVICE_LOCATION_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request to Moving History Location Cost", 1);
			        }
			        // delete last record
			        $delete_last_record_cost_location = $this->M_cost->delete_last_record_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $start_date, $end_date, $increment_qty,'dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE');
			        if ($delete_last_record_cost_location == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Last Record Location Cost", 1);
			        }
	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Update Location Cost", 1);
	        	} else {
	        		$this->session->set_flashdata('success_edit_location_cost', 'Cost rate updated successfully!');
	        		$this->db->trans_commit();
		            redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_location_cost', $e->getMessage());
        		$this->db->trans_rollback();
	            redirect(current_url());
        	}
        }		
	}

	public function print_location_jakarta()
	{
		if (isset($_POST['submit'])) {
			if (empty($_POST['check'])) {
				$result['hasil_location_jakarta'] = $this->M_cost->get_data_location_jakarta()->result();

				$html = $this->load->view('reports/r_pricelocationjakarta1', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price Location Jakarta.pdf', 'I');
			}

			if (in_array('date', $_POST['check'])) {
				$result['hasil_location_jakarta'] = $this->M_cost->get_data_location_jakarta()->result();

				$html = $this->load->view('reports/r_pricelocationjakarta2', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price Location Jakarta.pdf', 'I');
			}
		}
	}

	function add_weight_selling()
	{
		$data['company_service'] = $this->M_cost->get_companies()->result();
		$data['selling_service'] = $this->M_cost->get_services_weight();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();
		$data['measurement_unit'] = $this->M_cost->get_measurement_unit()->result();

		// set validation rules
		 $this->form_validation->set_rules('company_service_id', 'Company Service', 'required');
		 $this->form_validation->set_rules('selling_service_id', 'Selling Service', 'required');
		 $this->form_validation->set_rules('from_location_id', 'From / To Location', 'required');
		 $this->form_validation->set_rules('to_location_id', 'Destination', 'required');
		 $this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		 $this->form_validation->set_rules('increment_qty', 'Increment Quantity', 'required');
		 $this->form_validation->set_rules('tariff_currency[]', 'Currency', 'required');
		 $this->form_validation->set_rules('tariff_amount[]', 'Cost Amount', 'required|numeric');
		 $this->form_validation->set_rules('start_date', 'Start Date', 'required');
		 $this->form_validation->set_rules('end_date', 'End Date', 'required');
		 $this->form_validation->set_rules('from_weight[]', 'From Weight', 'required');
		 $this->form_validation->set_rules('to_weight[]', 'To Weight', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	// declare variable
	        $company_service = $this->input->post('company_service_id');
			$selling_service = $this->input->post('selling_service_id');
			$calc_type = $this->input->post('calc_type');
			$increment_qty = $this->input->post('increment_qty');
			$from_location = $this->input->post('from_location_id');
			$to_location =  $this->input->post('to_location_id');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$measurement_unit = $this->input->post('measurement_unit');

	         $from_weight = $this->input->post('from_weight');
	         $to_weight = $this->input->post('to_weight');
	         $n_tariff_currencys = $this->input->post('tariff_currency');
	         $n_tariff_amounts = $this->input->post('tariff_amount');
	         $n_count_selling = count($this->input->post('to_weight'));
	         $check_valid_qty = "";
	         
	         // validation for qty
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	if (isset($from_weight[$i+1]) && $from_weight[$i+1] <= $to_weight[$i]) {
	         		$check_valid_qty = "error";
	         	} elseif (isset($from_weight[$i+1]) && ($from_weight[$i+1] - 1) != $to_weight[$i]) {
	         		$check_valid_qty = "error";
	         	}
	         }
	         // validation for data exists
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	// check data to database
	         	$check_data_selling = $this->M_cost->check_data_weight($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight[$i], $to_weight[$i], $increment_qty);

	         	if ($check_data_selling > 0) {
	         		$result_check = 1;
	         	} else {
	         		$result_check = 0;
	         	}
	         }
	         // validation for overlap start date
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	$check_data_selling2 = $this->M_cost->check_data_weight2($company_service, $selling_service, $from_location, $to_location, $from_weight[$i], $to_weight[$i], $increment_qty);

	         	if ($check_data_selling2->num_rows() > 0) {
	         		$check_date = $this->M_cost->valid_date_weight($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight[$i], $to_weight[$i], $increment_qty);

         			if ($check_date->num_rows() > 0) {
         				$result_date = 1;
         			} else {
         				$result_date = 0;
         			}
	         	} else {
	         		$result_date = 0;
	         	}

	         }
	         
	         // check for validation
	        if ($this->form_validation->run() == FALSE) {
	            $this->load->view('costs/v_addweight', $data);
	        } elseif ($check_valid_qty == "error") {
	        	 $data['qty_error'] = "error";
        		 $this->load->view('costs/v_addweight', $data);
	        } elseif ($result_check > 0) {
	        	 $data['data_exist'] = "exist";
        		 $this->load->view('costs/v_addweight', $data);
	        } elseif ($result_date > 0) {
	        	 $data['date_error'] = "error";
        		 $this->load->view('costs/v_addweight', $data);
	        } else{
	        	$this->db->trans_begin();
	        	try {
	        		$data_entry = array();
		        	for ($i=0; $i < $n_count_selling; $i++) { 
		        		$data_entry[] = array(
			        		'company_service_id' => $company_service,
			        		'selling_service_id' => $selling_service,
			        		'from_location_id' => $from_location,
			        		'to_location_id' => $to_location,
			        		'start_date' => $start_date,
			        		'end_date' => $end_date,
			        		'calc_type' => $calc_type,
			        		'increment_qty' => $increment_qty,
			        		'measurement_unit' => $measurement_unit,
			        		'from_weight' => $from_weight[$i],
			        		'to_weight' => $to_weight[$i],
			        		'tariff_currency' => $n_tariff_currencys[$i],
			        		'tariff_amount' => $n_tariff_amounts[$i]
			        	);
		        	}

		        	// insert limitation from header
		        	$check_limit = $this->M_cost->check_limit_weight($company_service, $selling_service, $from_location, $to_location)->num_rows();

		        	if ($check_limit < 1) {
		        		$data_limit = array(
		        			'company_service_id' => $company_service,
		        			'selling_service_id' => $selling_service,
		        			'from_location_id' => $from_location,
		        			'to_location_id' => $to_location
		        		);

		        		if (!$this->db->insert('dbo.MLIMITATIONS_PRICE_WEIGHT_ATTRIBUTE', $data_limit)) {
		        			throw new Exception("Error Processing Request to Entry Floor Price Weight Selling", 1);
		        		}
		        	}
		        	
		        	// $this->M_cost->add_container_selling($data, 'dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');

		        	if (!$this->db->insert_batch('dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE', $data_entry)) {
		        		throw new Exception("Error Processing Request to Entry Weight Selling", 1);
		        	}

		            if ($this->db->trans_status() === FALSE) {
		            	throw new Exception("Error Processing Request to Entry Weight Selling", 1);
		            } else {
		            	$this->session->set_flashdata('success_entry_weight_selling', 'Selling Rate Submitted Successfully');
		            	$this->db->trans_commit();
		            	redirect(current_url());
		            }
	        	} catch (Exception $e) {
	        		$this->session->set_flashdata('failed_entry_weight_selling', $e->getMessage());
	            	$this->db->trans_rollback();
	            	redirect(current_url());
	        	}
	        }
        }
	}

	function edit_weight()
	{
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$from_location = $this->uri->segment(5);
		$to_location = $this->uri->segment(6);
		$from_weight = $this->uri->segment(7);
		$to_weight = $this->uri->segment(8);
		$calc_type = $this->uri->segment(9);
		$increment_qty = $this->uri->segment(10);
		$start_date = $this->uri->segment(11);
		$end_date = $this->uri->segment(12);

		$data['details'] = $this->M_cost->get_detail_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)->result();

		$data['data_selling'] = $this->M_cost->get_data_selling_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)->result();

		// set validation rules
		$this->form_validation->set_rules('tariff_amount', 'Tariff Amount', 'required|numeric');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editweight', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$data = array(
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'tariff_amount' => $this->input->post('tariff_amount')
			        );

		        	$update_selling_weight = $this->M_cost->update_selling_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, 'dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE', $data);
		        	if ($update_selling_weight == FALSE) {
		        		throw new Exception("Error Processing Request to Update Weight Selling", 1);
		        	}
		            // $this->session->set_flashdata('success', 'Selling Rate updated successfully!');
		            // redirect(current_url());
	        	} else {
	        		$tariff_currency = $this->M_cost->check_detail_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)->row()->TARIFF_CURRENCY;

					$tariff_amount = $this->M_cost->check_detail_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)->row()->TARIFF_AMOUNT;

	        		$first_record = array(
	        			'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'from_weight' => $from_weight,
	        			'to_weight' => $to_weight,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $this->input->post('tariff_amount')
			        );

			        $move_record = array(
			        	'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'from_weight' => $from_weight,
	        			'to_weight' => $to_weight,
	        			'start_date' => $start_date,
		        		'end_date' => $end_date,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'tariff_currency' => $tariff_currency,
		        		'tariff_amount' => $tariff_amount
			        );

			        // move last record to history and delete that record
			        // $this->M_cost->move_record_cost('dbo.HISELLING_SERVICE_WEIGHT_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HISELLING_SERVICE_WEIGHT_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request to Moving History Weight Selling", 1);
			        }
			        // delete last record
			        $delete_last_record_weight_selling = $this->M_cost->delete_last_record_weight_selling($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty,'dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE');
			        if ($delete_last_record_weight_selling == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Last Record Weight Selling", 1);
			        }
			        // input new first cost
			        // $this->M_cost->add_first_cost('dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE', $first_record);
			        if (!$this->db->insert('dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request to Entry Weight Selling", 1);
			        }

			        unset($tariff_currency);
			        unset($tariff_amount);

	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Update Weight Selling", 1);
	        	} else {
	        		$this->session->set_flashdata('success_edit_weight_selling', 'Selling Rate updated successfully!');
	        		$this->db->trans_commit();
		            redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_weight_selling', $e->getMessage());
        		$this->db->trans_rollback();
	            redirect(current_url());
        	}
        }
	}

	function add_weight_cost()
	{
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$from_location = $this->uri->segment(5);
		$to_location = $this->uri->segment(6);
		$from_weight = $this->uri->segment(7);
		$to_weight = $this->uri->segment(8);
		$calc_type = $this->uri->segment(9);
		$increment_qty = $this->uri->segment(10);
		$start_date = $this->uri->segment(11);
		$end_date = $this->uri->segment(12);

		$data['details'] = $this->M_cost->get_detail_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)->result();

		$data['cost'] = $this->M_cost->get_cost()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();

		// set validation rules
		 $this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		 $this->form_validation->set_rules('increment_qty', 'Increment Quantity', 'required');
		 $this->form_validation->set_rules('cost_currency[]', 'Currency', 'required');
		 $this->form_validation->set_rules('cost_amount[]', 'Cost Amount', 'required|numeric');
		 $this->form_validation->set_rules('start_date', 'Start Date', 'required');
		 $this->form_validation->set_rules('end_date', 'End Date', 'required');
		 $this->form_validation->set_rules('from_weight[]', 'From Weight', 'required');
		 $this->form_validation->set_rules('to_weight[]', 'To Weight', 'required');
		 $this->form_validation->set_rules('cost_id', 'Cost', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	// declare variable
			$calc_type = $this->input->post('calc_type');
			$increment_qty = $this->input->post('increment_qty');
			$cost_id = $this->input->post('cost_id');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');

	         $from_weight = $this->input->post('from_weight');
	         $to_weight = $this->input->post('to_weight');
	         $cost_currency = $this->input->post('cost_currency');
	         $cost_amount = $this->input->post('cost_amount');
	         $n_count_selling = count($this->input->post('to_weight'));
	         $check_valid_qty = "";
	         
	         // validation for qty
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	if (isset($from_weight[$i+1]) && $from_weight[$i+1] <= $to_weight[$i]) {
	         		$check_valid_qty = "error";
	         	} elseif (isset($from_weight[$i+1]) && ($from_weight[$i+1] - 1) != $to_weight[$i]) {
	         		$check_valid_qty = "error";
	         	}
	         }
	         // validation for data exists
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	// check data to database
	         	$check_data_selling = $this->M_cost->check_data_weight_cost($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight[$i], $to_weight[$i], $increment_qty, $cost_id);

	         	if ($check_data_selling > 0) {
	         		$result_check = 1;
	         	} else {
	         		$result_check = 0;
	         	}
	         }
	         // validation for overlap start date
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	$check_data_selling2 = $this->M_cost->check_data_weight_cost2($company_service, $selling_service, $from_location, $to_location, $from_weight[$i], $to_weight[$i], $increment_qty, $cost_id);

	         	if ($check_data_selling2->num_rows() > 0) {
	         		$check_date = $this->M_cost->valid_date_weight_cost($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight[$i], $to_weight[$i], $increment_qty, $cost_id);

         			if ($check_date->num_rows() > 0) {
         				$result_date = 1;
         			} else {
         				$result_date = 0;
         			}
	         	} else {
	         		$result_date = 0;
	         	}

	         }
	         
	         // check for validation
	        if ($this->form_validation->run() == FALSE) {
	            $this->load->view('costs/v_addweightcost', $data);
	        } elseif ($check_valid_qty == "error") {
	        	 $data['qty_error'] = "error";
        		 $this->load->view('costs/v_addweightcost', $data);
	        } elseif ($result_check > 0) {
	        	 $data['data_exist'] = "exist";
        		 $this->load->view('costs/v_addweightcost', $data);
	        } elseif ($result_date > 0) {
	        	 $data['date_error'] = "error";
        		 $this->load->view('costs/v_addweightcost', $data);
	        } else{
	        	$this->db->trans_begin();
	        	try {
	        		$cost_type = $this->M_cost->check_cost($cost_id)->row()->COST_TYPE;
					$cost_group = $this->M_cost->check_cost($cost_id)->row()->COST_GROUP;

		        	$data_entry = array();
		        	for ($i=0; $i < $n_count_selling; $i++) { 
		        		$data_entry[] = array(
			        		'company_service_id' => $company_service,
			        		'selling_service_id' => $selling_service,
			        		'from_location_id' => $from_location,
			        		'to_location_id' => $to_location,
			        		'cost_id' => $cost_id,
			        		'cost_type_id' => $cost_type,
			        		'cost_group_id' => $cost_group,
			        		'start_date' => $start_date,
			        		'start_date' => $start_date,
			        		'end_date' => $end_date,
			        		'calc_type' => $calc_type,
			        		'increment_qty' => $increment_qty,
			        		'from_weight' => $from_weight[$i],
			        		'to_weight' => $to_weight[$i],
			        		'cost_currency' => $cost_currency[$i],
			        		'cost_amount' => $cost_amount[$i]
			        	);
		        	}
		        	
		        	// $this->M_cost->add_container_selling($data, 'dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');

		        	if (!$this->db->insert_batch('dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE', $data_entry)) {
		        		throw new Exception("Error Processing Request to Entry Weight Cost", 1);
		        	}

		        	// $this->load->helper('comman_helper');
		        	// pr($data_entry);

		        	unset($cost_type);
		        	unset($cost_group);
		            
		            if ($this->db->trans_status() === FALSE) {
		            	throw new Exception("Error Processing Request to Entry Weight Cost", 1);
		            } else {
		            	$this->session->set_flashdata('success_entry_weight_cost', 'Cost Rate Submitted Successfully');
		            	$this->db->trans_commit();
		            	redirect(current_url());
		            }
	        	} catch (Exception $e) {
	        		$this->session->set_flashdata('failed_entry_weight_cost', 'Cost Rate Submitted Successfully');
	            	$this->db->trans_rollback();
	            	redirect(current_url());
	        	}
	        }
        }
	}

	function weight_detail()
	{
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$from_location = $this->uri->segment(5);
		$to_location = $this->uri->segment(6);
		$from_weight = $this->uri->segment(7);
		$to_weight = $this->uri->segment(8);
		$calc_type = $this->uri->segment(9);
		$increment_qty = $this->uri->segment(10);
		$start_date = $this->uri->segment(11);
		$end_date = $this->uri->segment(12);

		$data['details'] = $this->M_cost->get_detail_weight2($from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)->result();

		$data['cost_detail'] = $this->M_cost->get_cost_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)->result();

		$this->load->view('costs/v_detailweightcost', $data);
	}

	function edit_weight_cost()
	{
		$company_service = $this->uri->segment(3);
		$selling_service = $this->uri->segment(4);
		$cost_id = $this->uri->segment(5);
		$from_location = $this->uri->segment(6);
		$to_location = $this->uri->segment(7);
		$from_weight = $this->uri->segment(8);
		$to_weight = $this->uri->segment(9);
		$start_date = $this->uri->segment(10);
		$end_date = $this->uri->segment(11);
		$increment_qty = $this->uri->segment(12);
		$cost_type = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_TYPE;
        $cost_group = $this->M_cost->get_cost_detail_location($cost_id)->row()->COST_GROUP;

		$data['details'] = $this->M_cost->get_detail_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id)->result();

		// set validation rules
		$this->form_validation->set_rules('cost_amount', 'Cost Amount', 'required|numeric');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// hold error messages in div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

         // check for validation
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('costs/v_editweightcost', $data);
        } else {
        	$this->db->trans_begin();
        	try {
        		// declare variable change date
	        	$start_date_change = $this->input->post('start_date');
	        	$end_date_change = $this->input->post('end_date');
	        	// check if current end_date == end_date_change
	        	if ($end_date == $end_date_change) {
	        		$data = array(
		        		'start_date' => $start_date,
		        		'end_date' => $end_date,
		        		'cost_amount' => $this->input->post('cost_amount')
			        );

		        	$update_cost_weight = $this->M_cost->update_cost_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id, 'dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE', $data);
		        	if ($update_cost_weight == FALSE) {
		        		throw new Exception("Error Processing Request to Update Weight Cost", 1);
		        	}
		            // $this->session->set_flashdata('success', 'Cost Rate updated successfully!');
		            // redirect(current_url());
	        	} else {
	        		$cost_currency = $this->M_cost->check_detail_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id)->row()->COST_CURRENCY;

					$cost_amount = $this->M_cost->check_detail_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id)->row()->COST_AMOUNT;

					$calc_type = $this->M_cost->check_detail_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id)->row()->CALC_TYPE;

	        		$first_record = array(
	        			'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'cost_id' => $cost_id,
	        			'cost_type_id' => $cost_type,
	        			'cost_group_id' => $cost_group,
	        			'from_weight' => $from_weight,
	        			'to_weight' => $to_weight,
	        			'start_date' => $this->input->post('start_date'),
		        		'end_date' => $this->input->post('end_date'),
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'cost_currency' => $cost_currency,
		        		'cost_amount' => $this->input->post('cost_amount')
			        );

			        $move_record = array(
			        	'company_service_id' => $company_service,
	        			'selling_service_id' => $selling_service,
	        			'from_location_id' => $from_location,
	        			'to_location_id' => $to_location,
	        			'cost_id' => $cost_id,
	        			'cost_type_id' => $cost_type,
	        			'cost_group_id' => $cost_group,
	        			'from_weight' => $from_weight,
	        			'to_weight' => $to_weight,
	        			'start_date' => $start_date,
		        		'end_date' => $end_date,
	        			'calc_type' => $calc_type,
	        			'increment_qty' => $increment_qty,
	        			'cost_currency' => $cost_currency,
		        		'cost_amount' => $cost_amount
			        );

			        // move last record to history and delete that record
			        // $this->M_cost->move_record_cost('dbo.HICOST_SERVICE_WEIGHT_ATTRIBUTE', $move_record);
			        if (!$this->db->insert('dbo.HICOST_SERVICE_WEIGHT_ATTRIBUTE', $move_record)) {
			        	throw new Exception("Error Processing Request to Moving History Weight Cost", 1);
			        }
			        // delete last record
			        $delete_last_record_weight_cost = $this->M_cost->delete_last_record_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id,'dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE');
			        if ($delete_last_record_weight_cost == FALSE) {
			        	throw new Exception("Error Processing Request to Delete Last Record Weight Cost", 1);
			        }
			        // input new first cost
			        if (!$this->db->insert('dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE', $first_record)) {
			        	throw new Exception("Error Processing Request to Entry Weight Cost", 1);
			        }

			        unset($cost_currency);
			        unset($cost_amount);
			        unset($calc_type);

	        	}

	        	if ($this->db->trans_status() === FALSE) {
	        		throw new Exception("Error Processing Request to Entry Weight Cost", 1);
	        	} else {
	        		$this->session->set_flashdata('success_edit_weight_cost', 'Cost Rate updated successfully!');
	        		$this->db->trans_commit();
		            redirect(current_url());
	        	}
        	} catch (Exception $e) {
        		$this->session->set_flashdata('failed_edit_weight_cost', $e->getMessage());
        		$this->db->trans_rollback();
	            redirect(current_url());
        	}
        }
	}

	function print_weight_jakarta()
	{
		if (isset($_POST['submit'])) {
			if (!empty($_POST['check']) && in_array('date', $_POST['check']) && in_array('weight', $_POST['check'])) {
				$result['hasil_weight_jakarta'] = $this->M_cost->get_data_weight_jakarta()->result();

				$html = $this->load->view('reports/r_priceweightjakarta4', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price Service Bulk Jakarta.pdf', 'I');
			}
			
			if (empty($_POST['check'])) {
				$result['hasil_weight_jakarta'] = $this->M_cost->get_data_weight_jakarta()->result();

				$html = $this->load->view('reports/r_priceweightjakarta1', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price Service Bulk Jakarta.pdf', 'I');
			}

			if (in_array('date', $_POST['check'])) {
				$result['hasil_weight_jakarta'] = $this->M_cost->get_data_weight_jakarta()->result();

				$html = $this->load->view('reports/r_priceweightjakarta2', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price Service Bulk Jakarta.pdf', 'I');
			}

			if (in_array('weight', $_POST['check'])) {
				$result['hasil_weight_jakarta'] = $this->M_cost->get_data_weight_jakarta()->result();

				$html = $this->load->view('reports/r_priceweightjakarta3', $result, true);
				$this->load->library('pdf');

				$pdf = $this->pdf->load();
				$pdf->defaultheaderfontstyle='I';
				$pdf->defaultfooterfontstyle='I';
				$pdf->SetHeader('Generated on {DATE M, d Y H:i:s}');
				$pdf->SetFooter('Gedung 50 Abdul Muis, Jl. Abdul Muis No. 50 Jakarta Pusat 10160 Indonesia||Page {PAGENO} of {nb}');
				$pdf->WriteHTML($html);

				$pdf->Output('Price Service Bulk Jakarta.pdf', 'I');
			}
		}
	}

	function add_ocean_freight_selling()
	{
		$data['company_service'] = $this->M_cost->get_companies()->result();
		$data['selling_service'] = $this->M_cost->get_services_para('SS03');
		$data['container_size'] = $this->M_cost->get_container_size()->result();
		$data['container_type'] = $this->M_cost->get_container_type()->result();
		$data['container_category'] = $this->M_cost->get_container_category()->result();
		$data['calc_type'] = $this->M_cost->get_calculation_type()->result();
		$data['charges'] = $this->M_cost->get_general_para('CHARGES_KIND')->result();

		// set validation rules
		 $this->form_validation->set_rules('company_service_id', 'Company Service', 'required');
		 $this->form_validation->set_rules('selling_service_id', 'Selling Service', 'required');
		 $this->form_validation->set_rules('container_size_id', 'Container Size', 'required');
		 $this->form_validation->set_rules('container_type_id', 'Container Type', 'required');
		 $this->form_validation->set_rules('container_category_id', 'Container Category', 'required');
		 $this->form_validation->set_rules('charge_id', 'Charge Kind', 'required');
		 $this->form_validation->set_rules('from_location_id', 'From / To Location', 'required');
		 $this->form_validation->set_rules('to_location_id', 'Destination', 'required');
		 $this->form_validation->set_rules('calc_type', 'Calculation Type', 'required');
		 $this->form_validation->set_rules('increment_qty', 'Increment Quantity', 'required');
		 $this->form_validation->set_rules('tariff_currency[]', 'Currency', 'required');
		 $this->form_validation->set_rules('tariff_amount[]', 'Cost Amount', 'required|numeric');
		 $this->form_validation->set_rules('start_date', 'Start Date', 'required');
		 $this->form_validation->set_rules('end_date', 'End Date', 'required');
		 $this->form_validation->set_rules('from_qty[]', 'From Qty', 'required');
		 $this->form_validation->set_rules('to_qty[]', 'To Qty', 'required');

		 // hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if (isset($_POST)) {
        	// declare variable
	        $company_service = $this->input->post('company_service_id');
			$selling_service = $this->input->post('selling_service_id');
			$container_size = $this->input->post('container_size_id');
			$container_type =  $this->input->post('container_type_id');
			$container_category = $this->input->post('container_category_id');
			$charge_id = $this->input->post('charge_id');
			$calc_type = $this->input->post('calc_type');
			$increment_qty = $this->input->post('increment_qty');
			$from_location = $this->input->post('from_location_id');
			$to_location =  $this->input->post('to_location_id');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');

	         $from_qty = $this->input->post('from_qty');
	         $to_qty = $this->input->post('to_qty');
	         $tariff_currency = $this->input->post('tariff_currency');
	         $tariff_amount = $this->input->post('tariff_amount');
	         $n_count_selling = count($this->input->post('to_qty'));
	         $check_valid_qty = "";
	         
	         // validation for qty
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	if (isset($from_qty[$i+1]) && $from_qty[$i+1] <= $to_qty[$i]) {
	         		$check_valid_qty = "error";
	         	} elseif (isset($from_qty[$i+1]) && ($from_qty[$i+1] - 1) != $to_qty[$i]) {
	         		$check_valid_qty = "error";
	         	}
	         }
	         // validation for data exists
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	// check data to database
	         	$check_data_selling = $this->M_cost->check_data_selling_ocean($company_service, $selling_service, $container_size, $container_type, $container_category, $charge_id, $from_location, $to_location, $start_date, $end_date, $from_qty[$i], $to_qty[$i]);

	         	if ($check_data_selling > 0) {
	         		$result_check = 1;
	         	} else {
	         		$result_check = 0;
	         	}
	         }
	         // validation for overlap start date
	         for ($i=0; $i < $n_count_selling; $i++) { 
	         	$check_data_selling2 = $this->M_cost->check_data_selling_ocean2($company_service, $selling_service, $container_size, $container_type, $container_category, $charge_id, $from_location, $to_location, $from_qty[$i], $to_qty[$i]);

	         	if ($check_data_selling2->num_rows() > 0) {
	         		$check_date = $this->M_cost->valid_date_ocean($company_service, $selling_service, $container_size, $container_type, $container_category, $charge_id, $from_location, $to_location, $from_qty[$i], $to_qty[$i], $start_date, $end_date);

         			if ($check_date->num_rows() > 0) {
         				$result_date = 1;
         			} else {
         				$result_date = 0;
         			}
	         	} else {
	         		$result_date = 0;
	         	}

	         }
	         
	         // check for validation
	        if ($this->form_validation->run() == FALSE) {
	            $this->load->view('costs/v_addoceanfreight', $data);
	        } elseif ($check_valid_qty == "error") {
	        	$data['qty_error'] = "error";
        		$this->load->view('costs/v_addoceanfreight', $data);
	        } elseif ($result_check > 0) {
	        	$data['data_exist'] = "exist";
	        	$this->load->view('costs/v_addoceanfreight', $data);
	        } elseif ($result_date > 0) {
	        	$data['date_error'] = "error";
	        	$this->load->view('costs/v_addoceanfreight', $data);
	        } else{
	        	$this->db->trans_begin();
	        	try {
	        		$data_entry = array();
		        	for ($i=0; $i < $n_count_selling; $i++) { 
		        		$data_entry[] = array(
			        		'company_service_id' => $company_service,
			        		'selling_service_id' => $selling_service,
			        		'container_size_id' => $container_size,
			        		'container_type_id' => $container_type,
			        		'container_category_id' => $container_category,
			        		'charge_id' => $charge_id,
			        		'from_location_id' => $from_location,
			        		'to_location_id' => $to_location,
			        		'start_date' => $start_date,
			        		'end_date' => $end_date,
			        		'calc_type' => $calc_type,
			        		'increment_qty' => $increment_qty,
			        		'from_qty' => $from_qty[$i],
			        		'to_qty' => $to_qty[$i],
			        		'tariff_currency' => $tariff_currency[$i],
			        		'tariff_amount' => $tariff_amount[$i],
			        		'approval_status' => 'N'
			        	);
		        	}

		        	// insert limitation from header
		        	$check_limit = $this->M_cost->check_limit_ocean($company_service, $selling_service, $container_size, $container_type, $container_category, $from_location, $to_location, $charge_id)->num_rows();

		        	if ($check_limit < 1) {
		        		$data_limit = array(
		        			'company_service_id' => $company_service,
		        			'selling_service_id' => $selling_service,
		        			'container_size_id' => $container_size,
		        			'container_type_id' => $container_type,
		        			'container_category_id' => $container_category,
		        			'from_location_id' => $from_location,
		        			'to_location_id' => $to_location,
		        			'charge_id' => $charge_id
		        		);

		        		if (!$this->db->insert('dbo.MLIMITATIONS_PRICE_OCEAN_FREIGHT_ATTRIBUTE', $data_limit)) {
		        			throw new Exception("Error Processing Request to Entry Floor Price Ocean Freight", 1);
		        		}
		        	}
		        	
		        	// $this->M_cost->add_container_selling($data, 'dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');

		        	if (!$this->db->insert_batch('dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE', $data_entry)) {
		        		throw new Exception("Error Processing Request to Entry Ocean Freight", 1);
		        	}

		        	// $this->load->helper('comman_helper');
		        	// pr($data_entry);
		            if ($this->db->trans_status() === FALSE) {
		            	throw new Exception("Error Processing Request to Entry Ocean Freight", 1);
		            } else {
		            	$this->session->set_flashdata('success_entry_ocean_selling', 'Selling Rate Submitted Successfully');
		            	$this->db->trans_commit();
		            	redirect(current_url());
		            }
	        	} catch (Exception $e) {
	        		$this->session->set_flashdata('failed_entry_ocean_selling', 'Selling Rate Submitted Successfully');
	            	$this->db->trans_rollback();
	            	redirect(current_url());
	        	}
	        }
        }
	}

	function add_ocean_freight_cost()
	{

	}

	function edit_ocean_freight_selling()
	{

	}

	function edit_ocean_freight_cost()
	{

	}

}