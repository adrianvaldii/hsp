<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set("Asia/Jakarta");

class Approval extends CI_Controller {

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
		$this->nik = $this->session->userdata('nik');
		$this->load->model('M_approval');

		// load library and helper
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('url','html','form'));
	}

	function index()
	{
		$data['user_nik'] = $this->M_approval->get_user_nik($this->nik)->row()->Nm_lengkap;
		$data['data_approval'] = $this->M_approval->get_approval($this->nik)->result();
		$this->load->view('approval/v_index', $data);
	}

	public function detail_approval_quotation()
	{
		$quotation_number = $this->uri->segment(3);

		$data['data_trucking'] = $this->M_approval->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_approval->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_approval->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_approval->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_approval->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['approval_status'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->APPROVAL_STATUS;
		$data['company_name'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_approval->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_approval->get_data_quote_customs($quotation_number)->num_rows();
		$data['count_location'] = $this->M_approval->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_approval->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_approval->get_data_quote_ocean_freight($quotation_number)->num_rows();
		$sel = $this->M_approval->get_data_level($quotation_number, 'D1002');
		$data['level1_approval_status'] = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL1_APPROVAL_STATUS;
		$data['level2_approval_status'] = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL2_APPROVAL_STATUS;
		$data['level3_approval_status'] = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL3_APPROVAL_STATUS;
		// level 1
		if ($sel->row()->LEVEL1_APPROVAL_STATUS != "" && $sel->row()->LEVEL1_APPROVAL_USER_ID != "") {
			$nik_level1 = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL1_APPROVAL_USER_ID;
			$data['level1_approval_name'] = $this->M_approval->get_user_nik($nik_level1)->row()->Nm_lengkap;
			$data['level1_approval_date'] = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL1_APPROVAL_DATE;
		} else {
			$data['level1_approval_name'] = "-";
			$data['level1_approval_date'] = "-";
		}
		// level 2
		if ($sel->row()->LEVEL2_APPROVAL_STATUS != "" && $sel->row()->LEVEL2_APPROVAL_USER_ID != "") {
			$nik_level2 = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level2_approval_name'] = $this->M_approval->get_user_nik($nik_level2)->row()->Nm_lengkap;
			$data['level2_approval_date'] = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL2_APPROVAL_DATE;
		} else {
			$data['level2_approval_name'] = "-";
			$data['level2_approval_date'] = "-";
		}
		// level 3
		if ($sel->row()->LEVEL3_APPROVAL_STATUS != "" && $sel->row()->LEVEL3_APPROVAL_USER_ID != "") {
			$nik_level3 = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level3_approval_name'] = $this->M_approval->get_user_nik($nik_level3)->row()->Nm_lengkap;
			$data['level3_approval_date'] = $this->M_approval->get_data_level($quotation_number, 'D1002')->row()->LEVEL3_APPROVAL_DATE;
		} else {
			$data['level3_approval_name'] = "-";
			$data['level3_approval_date'] = "-";
		}
		
		// update status approval
		$date = date('Y-m-d H:i:s');


		$this->form_validation->set_rules('status', 'Status Approval', 'required');
		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('approval/v_detailapprovalquotation', $data);
		} else {
			// declare variable
			$transaction_number = $this->uri->segment(3);
			$document_id = $this->uri->segment(4);
			$status = $this->input->post('status');

			// $this->load->helper('comman_helper');
			// pr($document_id);

			$level_satu = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL1;
			// $this->load->helper('comman_helper');
			// pr($level_satu);
			$level_dua = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL2;
			$level_tiga = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL3;
			$random_level = $this->M_approval->check_document($document_id)->row()->RANDOM_LEVEL;

			if ($random_level == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
				$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
				$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

				if ($check_pic_level_1 != 'Y' && $check_pic_level_2 != 'Y' && $check_pic_level_3 != 'Y') {
					// check level user
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
					} elseif ($status == 'R') {
						$status_level = 'R';
					} 
					// elseif ($status != 'A' && $status != 'A') {
					// 	$status_level = 'W';
					// } 

					if ($level == '1') {
						$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => $status,
							'level1_approval_status' => $status_level
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						// check if that data has been change to Y
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;

						if ($check_fix_level_1 == 'Y') {
							// update quotation
							$data_status_baru_quotation = array(
								'quotation_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
						}
					} elseif ($level == '2') {
						$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => $status,
							'level2_approval_status' => $status_level
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						// check if that data has been change to Y
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

						if ($check_fix_level_2 == 'Y') {
							// update quotation
							$data_status_baru_quotation = array(
								'quotation_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
						}
					} elseif ($level == '3') {
						$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => $status,
							'level3_approval_status' => $status_level
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						// check if that data has been change to Y
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_1 == 'Y') {
							// update quotation
							$data_status_baru_quotation = array(
								'quotation_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
						}
					}
				}

				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			} 

			if ($random_level == 'N' && $level_satu == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
				// $this->load->helper('comman_helper');
				// pr($check_pic_level_1);
				$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
				if ($status == 'A') {
					$status_level = 'Y';
					$update_new = array(
						'approval_status' => 'W'
					);
					$this->M_approval->update_new($quotation_number, 'dbo.TRQUOTATION', $update_new);
				} elseif ($status == 'R') {
					$status_level = 'R';

					// update status quotation to reject
					$update_new = array(
						'approval_status' => 'R'
					);
					$this->M_approval->update_new($quotation_number, 'dbo.TRQUOTATION', $update_new);

				} elseif ($status != 'A' && $status != 'R') {
					$status_level = 'W';
				} 

				if ($level == 1) {
					// check if user wanna rejected this quotation
					if ($status_level == "R") {
						$data_status_baru = array(
							'approval_status' => $status_level,
							'level1_approval_status' => $status_level,
							'LEVEL1_APPROVAL_USER_ID' => $this->nik,
							'LEVEL1_APPROVAL_DATE' => $date
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
					} else {
						if ($check_pic_level_1 != $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 } elseif ($check_pic_level_1 == $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'level1_approval_status' => $check_pic_level_1
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 }

						 // check all level available for data approval, then check if all access level has been 'Y'
						if ($random_level == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_quotation = array(
									'quotation_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
								// update quotation
								$data_status_baru_quotation = array(
									'quotation_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_quotation = array(
									'quotation_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_dua == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_quotation = array(
									'quotation_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == "Y") {
								// update quotation
								$data_status_baru_quotation = array(
									'quotation_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == "Y") {
								// update quotation
								$data_status_baru_quotation = array(
									'quotation_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_quotation = array(
									'quotation_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						}
					}

					
				}
				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			}

			if ($random_level == 'N' && $level_dua == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

				if ($check_pic_level_2 != 'Y') {
					// check level user
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
						$update_new = array(
							'approval_status' => 'W'
						);
						$this->M_approval->update_new($quotation_number, 'dbo.TRAGREEMENT', $update_new);
					} elseif ($status == 'R') {
						$status_level = 'R';

						// update status quotation to reject
						$update_new = array(
							'approval_status' => 'R'
						);
						$this->M_approval->update_new($quotation_number, 'dbo.TRQUOTATION', $update_new);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == '2') {
						// check if user wanna rejected this quotation
						if ($status_level == "R") {
							$data_status_baru = array(
								'approval_status' => $status_level,
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						} else {
							// check if data same
							if ($check_pic_level_2 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $status_level,
									'LEVEL2_APPROVAL_USER_ID' => $this->nik,
									'LEVEL2_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($check_pic_level_2 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $check_pic_level_2
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} 
						}
					} 
				}
				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			}

			if ($random_level == 'N' && $level_tiga == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

				if ($check_pic_level_3 != 'Y') {
					// check level user
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
						$update_new = array(
							'approval_status' => 'W'
						);
						$this->M_approval->update_new($quotation_number, 'dbo.TRAGREEMENT', $update_new);
					} elseif ($status == 'R') {
						$status_level = 'R';

						// update status quotation to reject
						$update_new = array(
							'approval_status' => 'R'
						);
						$this->M_approval->update_new($quotation_number, 'dbo.TRQUOTATION', $update_new);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == '3') {
						if ($status_level == "R") {
							$data_status_baru = array(
								'approval_status' => $status_level,
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						} else {
							// check if data same
							if ($check_pic_level_3 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $status_level,
									'LEVEL3_APPROVAL_USER_ID' => $this->nik,
									'LEVEL3_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($check_pic_level_3 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $check_pic_level_3
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update quotation
									$data_status_baru_quotation = array(
										'quotation_number' => $transaction_number,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status_quotation($transaction_number, 'dbo.TRQUOTATION', $data_status_baru_quotation);
									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							}
						} 
					} 
				}
				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			}

			// $this->load->helper('comman_helper');
			// pr($data_status_baru);

			$this->session->set_flashdata('success', "Successfully update Approval!");
			redirect(current_url());
		}
	}

	public function detail_approval_agreement()
	{
		$transaction_number = $this->uri->segment(3);
		$agreement_number = $this->uri->segment(3);
		$document_id = $this->uri->segment(4);
		$quotation_number = $this->M_approval->get_quotation_number($transaction_number)->row()->QUOTATION_NUMBER;

		// $this->load->helper('comman_helper');
		// pr($quotation_number);

		// agreement
		$data['agreement_number'] = $this->M_approval->get_agreement_param($transaction_number)->row()->AGREEMENT_NUMBER;
		// $this->load->helper("comman_helper");
		// pr($data['agreement_number']);
		$data['agreement_document_number'] = $this->M_approval->get_agreement_param($transaction_number)->row()->AGREEMENT_DOCUMENT_NUMBER;
		$data['periode_start'] = $this->M_approval->get_agreement_param($transaction_number)->row()->AGREEMENT_PERIODE_START;
		$data['periode_end'] = $this->M_approval->get_agreement_param($transaction_number)->row()->AGREEMENT_PERIODE_END;
		$data['amendment_number'] = $this->M_approval->get_agreement_param($transaction_number)->row()->AMENDMENT_NUMBER;
		$data['approval_status'] = $this->M_approval->get_agreement_param($transaction_number)->row()->APPROVAL_STATUS;

		// data quotation
		$data['data_trucking'] = $this->M_approval->get_data_quote_trucking($quotation_number)->result();
		$data['data_customs'] = $this->M_approval->get_data_quote_customs($quotation_number)->result();
		$data['data_location'] = $this->M_approval->get_data_quote_location($quotation_number)->result();
		$data['data_weight'] = $this->M_approval->get_data_quote_weight($quotation_number)->result();
		$data['data_ocean_freight'] = $this->M_approval->get_data_quote_ocean_freight($quotation_number)->result();
		$data['quotation_number'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_NUMBER;
		$data['quotation_document_number'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_DOCUMENT_NUMBER;
		$data['company_name'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->COMPANY_NAME;
		$data['start_date'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_START;
		$data['end_date'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->QUOTATION_PERIODE_END;
		$data['revision'] = $this->M_approval->get_quotation_param_full($quotation_number)->row()->REVESION_NUMBER;
		$data['count_trucking'] = $this->M_approval->get_data_quote_trucking($quotation_number)->num_rows();
		$data['count_customs'] = $this->M_approval->get_data_quote_customs($quotation_number)->num_rows();
		$data['count_location'] = $this->M_approval->get_data_quote_location($quotation_number)->num_rows();
		$data['count_weight'] = $this->M_approval->get_data_quote_weight($quotation_number)->num_rows();
		$data['count_ocean_freight'] = $this->M_approval->get_data_quote_ocean_freight($quotation_number)->num_rows();

		$sel = $this->M_approval->get_data_level($agreement_number, 'D1001');
		$data['level1_approval_status'] = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL1_APPROVAL_STATUS;
		$data['level2_approval_status'] = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL2_APPROVAL_STATUS;
		$data['level3_approval_status'] = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL3_APPROVAL_STATUS;
		// level 1
		if ($sel->row()->LEVEL1_APPROVAL_STATUS != "" && $sel->row()->LEVEL1_APPROVAL_USER_ID != "") {
			$nik_level1 = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL1_APPROVAL_USER_ID;
			$data['level1_approval_name'] = $this->M_approval->get_user_nik($nik_level1)->row()->Nm_lengkap;
			$data['level1_approval_date'] = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL1_APPROVAL_DATE;
		} else {
			$data['level1_approval_name'] = "-";
			$data['level1_approval_date'] = "-";
		}
		// level 2
		if ($sel->row()->LEVEL2_APPROVAL_STATUS != "" && $sel->row()->LEVEL2_APPROVAL_USER_ID != "") {
			$nik_level2 = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level2_approval_name'] = $this->M_approval->get_user_nik($nik_level2)->row()->Nm_lengkap;
			$data['level2_approval_date'] = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL2_APPROVAL_DATE;
		} else {
			$data['level2_approval_name'] = "-";
			$data['level2_approval_date'] = "-";
		}
		// level 3
		if ($sel->row()->LEVEL3_APPROVAL_STATUS != "" && $sel->row()->LEVEL3_APPROVAL_USER_ID != "") {
			$nik_level3 = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level3_approval_name'] = $this->M_approval->get_user_nik($nik_level3)->row()->Nm_lengkap;
			$data['level3_approval_date'] = $this->M_approval->get_data_level($agreement_number, 'D1001')->row()->LEVEL3_APPROVAL_DATE;
		} else {
			$data['level3_approval_name'] = "-";
			$data['level3_approval_date'] = "-";
		}


		// update status approval
		$date = date('Y-m-d H:i:s');


		$this->form_validation->set_rules('status', 'Status Approval', 'required');
		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('approval/v_detailapprovalagreement', $data);
		} else {
			// declare variable
			$status = $this->input->post('status');

			// $this->load->helper('comman_helper');
			// pr($document_id);

			$level_satu = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL1;
			// $this->load->helper('comman_helper');
			// pr($level_satu);
			$level_dua = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL2;
			$level_tiga = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL3;
			$random_level = $this->M_approval->check_document($document_id)->row()->RANDOM_LEVEL;

			if ($random_level == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
				$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
				$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

				if ($check_pic_level_1 != 'Y' && $check_pic_level_2 != 'Y' && $check_pic_level_3 != 'Y') {
					// check level user
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
					} elseif ($status == 'R') {
						$status_level = 'R';

						// enable quotation after status agreement rejected
						$update_quotation = array(
							'status_agreement' => 0
						);

						$this->M_approval->update_quotation($quotation_number, 'dbo.TRQUOTATION', $update_quotation);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == '1') {
						$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => $status,
							'level1_approval_status' => $status_level
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						// check if that data has been change to Y
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;

						if ($check_fix_level_1 == 'Y') {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
						}
					} elseif ($level == '2') {
						$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => $status,
							'level2_approval_status' => $status_level
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						// check if that data has been change to Y
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

						if ($check_fix_level_2 == 'Y') {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
						}
					} elseif ($level == '3') {
						$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => $status,
							'level3_approval_status' => $status_level
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						// check if that data has been change to Y
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_1 == 'Y') {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
						}
					}
				}

				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			} 

			if ($random_level == 'N' && $level_satu == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
				// $this->load->helper('comman_helper');
				// pr($check_pic_level_1);
				$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
				if ($status == 'A') {
					$status_level = 'Y';
					$update_new = array(
						'approval_status' => 'W'
					);
					$this->M_approval->update_new_agreement($agreement_number, 'dbo.TRAGREEMENT', $update_new);
				} elseif ($status == 'R') {
					$status_level = 'R';

					// enable quotation after status agreement rejected
					$update_quotation = array(
						'status_agreement' => 0
					);

					$this->M_approval->update_quotation($quotation_number, 'dbo.TRQUOTATION', $update_quotation);

					// update agreement status
					$update_new = array(
						'approval_status' => 'R'
					);
					$this->M_approval->update_new_agreement($agreement_number, 'dbo.TRAGREEMENT', $update_new);

				} elseif ($status != 'A' && $status != 'R') {
					$status_level = 'W';
				} 

				if ($level == 1) {
					if ($check_pic_level_1 != $status_level) {
					 	$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => $status_level,
							'level1_approval_status' => $status_level,
							'LEVEL1_APPROVAL_USER_ID' => $this->nik,
							'LEVEL1_APPROVAL_DATE' => $date
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
					 } elseif ($check_pic_level_1 == $status_level) {
					 	$data_status_baru = array(
							'transaction_number' => $transaction_number,
							'document_id' => $document_id,
							'approval_status' => 'W',
							'level1_approval_status' => $check_pic_level_1
						);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
					 }

					 // check all level available for data approval, then check if all access level has been 'Y'
					if ($random_level == "Y") {
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
							// update trapproval_status_transaction
							$data_status_baru_approval = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
						}
					} elseif ($level_satu == "Y" && $level_dua == "Y") {
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
							// update trapproval_status_transaction
							$data_status_baru_approval = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
						}
					} elseif ($level_satu == "Y" && $level_tiga == "Y") {
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
							// update trapproval_status_transaction
							$data_status_baru_approval = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
						}
					} elseif ($level_dua == "Y" && $level_tiga == "Y") {
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
							// update trapproval_status_transaction
							$data_status_baru_approval = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
						}
					} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_1 == "Y") {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
							// update trapproval_status_transaction
							$data_status_baru_approval = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
						}
					} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_2 == "Y") {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
							// update trapproval_status_transaction
							$data_status_baru_approval = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
						}
					} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
						$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
						$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
						$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

						if ($check_fix_level_3 == "Y") {
							// update quotation
							$data_status_baru_agreement = array(
								'agreement_number' => $transaction_number,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
							// update trapproval_status_transaction
							$data_status_baru_approval = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'A'
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
						}
					}
				}
				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			}

			if ($random_level == 'N' && $level_dua == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

				if ($check_pic_level_2 != 'Y') {
					// check level user
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
						$update_new = array(
							'approval_status' => 'W'
						);
						$this->M_approval->update_new_agreement($agreement_number, 'dbo.TRAGREEMENT', $update_new);
					} elseif ($status == 'R') {
						$status_level = 'R';

						// enable quotation after status agreement rejected
						$update_quotation = array(
							'status_agreement' => 0
						);

						$this->M_approval->update_quotation($quotation_number, 'dbo.TRQUOTATION', $update_quotation);

						// update agreement status
						$update_new = array(
							'approval_status' => 'R'
						);
						$this->M_approval->update_new_agreement($agreement_number, 'dbo.TRAGREEMENT', $update_new);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == '2') {
						// check if data same
						if ($check_pic_level_2 != $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status_level,
								'LEVEL2_APPROVAL_STATUS' => $status_level,
								'LEVEL2_APPROVAL_USER_ID' => $this->nik,
								'LEVEL2_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 } elseif ($check_pic_level_2 == $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status_level,
								'LEVEL2_APPROVAL_STATUS' => $check_pic_level_2
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 }

						// check all level available for data approval, then check if all access level has been 'Y'
						if ($random_level == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

							}
						} elseif ($level_satu == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

							}
						} elseif ($level_dua == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} 
					} 
				}
				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			}

			if ($random_level == 'N' && $level_tiga == "Y") {
				// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
				$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

				if ($check_pic_level_3 != 'Y') {
					// check level user
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
						$update_new = array(
							'approval_status' => 'W'
						);
						$this->M_approval->update_new_agreement($agreement_number, 'dbo.TRAGREEMENT', $update_new);
					} elseif ($status == 'R') {
						$status_level = 'R';

						// enable quotation after status agreement rejected
						$update_quotation = array(
							'status_agreement' => 0
						);

						$this->M_approval->update_quotation($quotation_number, 'dbo.TRQUOTATION', $update_quotation);

						// update agreement status
						$update_new = array(
							'approval_status' => 'R'
						);
						$this->M_approval->update_new_agreement($agreement_number, 'dbo.TRAGREEMENT', $update_new);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == '3') {
						// check if data same
						if ($check_pic_level_3 != $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status_level,
								'LEVEL3_APPROVAL_STATUS' => $status_level,
								'LEVEL3_APPROVAL_USER_ID' => $this->nik,
								'LEVEL3_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 } elseif ($check_pic_level_3 == $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status_level,
								'LEVEL3_APPROVAL_STATUS' => $check_pic_level_3
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 }

						// check all level available for data approval, then check if all access level has been 'Y'
						if ($random_level == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_dua == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_3 == "Y") {
								// update quotation
								$data_status_baru_agreement = array(
									'agreement_number' => $transaction_number,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status_agreement($transaction_number, 'dbo.TRAGREEMENT', $data_status_baru_agreement);
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} 
					} 
				}
				unset($check_pic_level_1);
				unset($check_pic_level_2);
				unset($check_pic_level_3);
			}

			// $this->load->helper('comman_helper');
			// pr($data_status_baru);

			$this->session->set_flashdata('success', "Successfully update Approval!");
			redirect(current_url());
		}
	}

	function history_approval()
	{
		$data['data_approval'] = $this->M_approval->get_approval_all()->result();
		$this->load->view('approval/v_historyapproval', $data);
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

	function detail_approval_additional()
	{
		$this->load->helper('comman_helper');
		$transaction_number = $this->uri->segment(3);
		$document_id = $this->uri->segment(4);
		$date = date('Y-m-d H:i:s');

		$cmpy = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_approval->get_code($cmpy)->row()->COMPANY_ID;

		$work_order_number = $this->M_approval->get_data_additional($transaction_number)->row()->WORK_ORDER_NUMBER;
		$cost_id = $this->M_approval->get_data_additional($transaction_number)->row()->COST_ID;
		$container_number = $this->M_approval->get_data_additional($transaction_number)->row()->CONTAINER_NUMBER;
		$cost_currency = $this->M_approval->get_data_additional($transaction_number)->row()->COST_CURRENCY;
		$cost_type_id = $this->M_approval->get_data_additional($transaction_number)->row()->COST_TYPE_ID;
		$cost_group_id = $this->M_approval->get_data_additional($transaction_number)->row()->COST_GROUP_ID;
		$cost_request_amount = $this->M_approval->get_data_additional($transaction_number)->row()->COST_REQUEST_AMOUNT;
		$cost_invoice_amount = $this->M_approval->get_data_additional($transaction_number)->row()->COST_INVOICE_AMOUNT;
		$request_date = $this->M_approval->get_data_additional($transaction_number)->row()->REQUEST_DATE;
		$user_id_request = $this->M_approval->get_data_additional($transaction_number)->row()->USER_ID_REQUEST;

		$data['data_additional'] = $this->M_approval->get_data_additional($transaction_number)->result();
		// $cost_additional = $this->M_approval->get_data_additional($transaction_number)->row()->COST_ID;
		// $container_numbers = $this->M_approval->get_data_additional($transaction_number)->row()->CONTAINER_NUMBER;
		// $temp_max_add = $this->M_approval->get_max_cash($cost_additional, $container_numbers)->row()->id;
		// $ids = ($temp_max_add == '')?0:$temp_max_add;
		// if ($temp_max_add != NULL || $temp_max_add != "" || $temp_max_add > 0) {
		// 	$ids = $temp_max_add+1;
		// 	// echo "ada sebelumnya";
		// } else {
		// 	$ids = 0;
		// 	// echo "ga ada sebelumnya";
		// }
		// echo $cost_additional;
		// pr($ids);
		$data['approval_status'] = $this->M_approval->get_data_additional($transaction_number)->row()->STATUS;
		$data['work_order_number'] = $this->M_approval->get_data_additional($transaction_number)->row()->WORK_ORDER_NUMBER;
		$data['work_order_date'] = $this->M_approval->get_data_wo($work_order_number)->row()->WORK_ORDER_DATE;
		$data['vessel_name'] = $this->M_approval->get_data_wo($work_order_number)->row()->VESSEL_NAME;
		$data['trade_name'] = $this->M_approval->get_data_wo($work_order_number)->row()->TRADE_NAME;
		$data['voyage_number'] = $this->M_approval->get_data_wo($work_order_number)->row()->VOYAGE_NUMBER;

		$sel = $this->M_approval->get_data_level($transaction_number, 'D1007');
		$data['level1_approval_status'] = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL1_APPROVAL_STATUS;
		$data['level2_approval_status'] = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL2_APPROVAL_STATUS;
		$data['level3_approval_status'] = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL3_APPROVAL_STATUS;
		// level 1
		if ($sel->row()->LEVEL1_APPROVAL_STATUS != "" && $sel->row()->LEVEL1_APPROVAL_USER_ID != "") {
			$nik_level1 = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL1_APPROVAL_USER_ID;
			$data['level1_approval_name'] = $this->M_approval->get_user_nik($nik_level1)->row()->Nm_lengkap;
			$data['level1_approval_date'] = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL1_APPROVAL_DATE;
		} else {
			$data['level1_approval_name'] = "-";
			$data['level1_approval_date'] = "-";
		}
		// level 2
		if ($sel->row()->LEVEL2_APPROVAL_STATUS != "" && $sel->row()->LEVEL2_APPROVAL_USER_ID != "") {
			$nik_level2 = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level2_approval_name'] = $this->M_approval->get_user_nik($nik_level2)->row()->Nm_lengkap;
			$data['level2_approval_date'] = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL2_APPROVAL_DATE;
		} else {
			$data['level2_approval_name'] = "-";
			$data['level2_approval_date'] = "-";
		}
		// level 3
		if ($sel->row()->LEVEL3_APPROVAL_STATUS != "" && $sel->row()->LEVEL3_APPROVAL_USER_ID != "") {
			$nik_level3 = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level3_approval_name'] = $this->M_approval->get_user_nik($nik_level3)->row()->Nm_lengkap;
			$data['level3_approval_date'] = $this->M_approval->get_data_level($transaction_number, 'D1007')->row()->LEVEL3_APPROVAL_DATE;
		} else {
			$data['level3_approval_name'] = "-";
			$data['level3_approval_date'] = "-";
		}

		$this->form_validation->set_rules('status', 'Status Approval', 'required');
		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('approval/v_detailapprovaladditional', $data);
		} else {
			$cost_additional = $this->M_approval->get_data_additional($transaction_number)->row()->COST_ID;
			$container_numbers = $this->M_approval->get_data_additional($transaction_number)->row()->CONTAINER_NUMBER;
			$temp_max_add = $this->M_approval->get_max_cash($cost_additional, $container_numbers)->row()->id;
			$ids = ($temp_max_add == '')?0:$temp_max_add;
			$this->db->trans_begin();
			try {
				// declare variable
				$status = $this->input->post('status');

				// $this->load->helper('comman_helper');
				// pr($document_id);

				$level_satu = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL1;
				// $this->load->helper('comman_helper');
				// pr($level_satu);
				$level_dua = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL2;
				$level_tiga = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL3;
				$random_level = $this->M_approval->check_document($document_id)->row()->RANDOM_LEVEL;

				if ($random_level == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
					$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
					$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

					if ($check_pic_level_1 != 'Y' && $check_pic_level_2 != 'Y' && $check_pic_level_3 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
						} elseif ($status == 'R') {
							$status_level = 'R';

							// enable quotation after status agreement rejected
							// $update_quotation = array(
							// 	'status_agreement' => 0
							// );

							// $this->M_approval->update_quotation($quotation_number, 'dbo.TRQUOTATION', $update_quotation);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '1') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level1_approval_status' => $status_level,
								'level1_approval_user_id' => $this->nik,
								'level1_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y') {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
							}
						} elseif ($level == '2') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level2_approval_status' => $status_level,
								'level2_approval_user_id' => $this->nik,
								'level2_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y') {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
							}
						} elseif ($level == '3') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level3_approval_status' => $status_level,
								'level3_approval_user_id' => $this->nik,
								'level3_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y') {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
							}
						}
					}

					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				} 

				if ($random_level == 'N' && $level_satu == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
					// $this->load->helper('comman_helper');
					// pr($check_pic_level_1);
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
						
						// update status additional cost
						$update_additional = array(
							'status' => 'W'
						);
						$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

						// // insert data to cash request
						// $insert_cash_request = array(
						// 	'work_order_number' => $work_order_number,
						// 	'cost_id' => $cost_id,
						// 	'container_number' => $container_number,
						// 	'cost_currency' => $cost_currency,
						// 	'cost_type_id' => $cost_type_id,
						// 	'cost_group_id' => $cost_group_id,
						// 	'cost_request_amount' => $cost_request_amount,
						// 	'request_date' => $request_date,
						// 	'user_id_request' => $user_id_request,
						// 	'cost_kind' => 'A',
						// 	'user_id' => $user_id_request,
						// 	'user_date' => $date
						// );
						// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

					} elseif ($status == 'R') {
						$status_level = 'R';

						// update status additional cost
						$update_additional = array(
							'status' => 'R',
							'IS_DELETED' => 'Y'
						);
						$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

						// update status approval
						$data_status_barus = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_barus);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == 1) {
						if ($check_pic_level_1 != $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 } elseif ($check_pic_level_1 == $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'approval_status' => $status_level,
								'level1_approval_status' => $check_pic_level_1
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 }

						// $data_status_baru = array(
						// 	'transaction_number' => $transaction_number,
						// 	'document_id' => $document_id,
						// 	'level1_approval_status' => $status_level,
						// 	'LEVEL1_APPROVAL_USER_ID' => $this->nik,
						// 	'LEVEL1_APPROVAL_DATE' => $date
						// );
						// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

						 // check all level available for data approval, then check if all access level has been 'Y'
						if ($random_level == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_dua == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == "Y") {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == "Y") {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_3 == "Y") {
								// update status additional cost
								$update_additional = array(
									'status' => 'A'
								);
								$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

								// insert data to cash request
								$insert_cash_request = array(
									'work_order_number' => $work_order_number,
									'company_id' => $code_cmpy,
									'cost_id' => $cost_id,
									'sequence_id' => $ids,
									'container_number' => $container_number,
									'cost_currency' => $cost_currency,
									'cost_type_id' => $cost_type_id,
									'cost_group_id' => $cost_group_id,
									'cost_request_amount' => $cost_request_amount,
									'cost_invoice_amount' => $cost_invoice_amount,
									'request_date' => $request_date,
									'user_id_request' => $user_id_request,
									'cost_kind' => 'A',
									'is_transfered' => 'N',
									'user_id' => $user_id_request,
									'user_date' => $date
								);
								$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						}
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				if ($random_level == 'N' && $level_dua == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

					if ($check_pic_level_2 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
							
							// update status additional cost
							$update_additional = array(
								'status' => 'W'
							);
							$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

							// // insert data to cash request
							// $insert_cash_request = array(
							// 	'work_order_number' => $work_order_number,
							// 	'cost_id' => $cost_id,
							// 	'container_number' => $container_number,
							// 	'cost_currency' => $cost_currency,
							// 	'cost_type_id' => $cost_type_id,
							// 	'cost_group_id' => $cost_group_id,
							// 	'cost_request_amount' => $cost_request_amount,
							// 	'request_date' => $request_date,
							// 	'user_id_request' => $user_id_request,
							// 	'cost_kind' => 'A',
							// 	'user_id' => $user_id_request,
							// 	'user_date' => $date
							// );
							// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status additional cost
							$update_additional = array(
								'status' => 'R',
								'IS_DELETED' => 'Y'
							);
							$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

							// update status approval
							$data_status_barus = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => $status,
									'level2_approval_status' => $status_level,
									'LEVEL2_APPROVAL_USER_ID' => $this->nik,
									'LEVEL2_APPROVAL_DATE' => $date
								);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_barus);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '2') {
							// check if data same
							if ($check_pic_level_2 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $status_level,
									'LEVEL2_APPROVAL_USER_ID' => $this->nik,
									'LEVEL2_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($check_pic_level_2 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $check_pic_level_2
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// $data_status_baru = array(
							// 	'transaction_number' => $transaction_number,
							// 	'document_id' => $document_id,
							// 	'level2_approval_status' => $status_level,
							// 	'LEVEL2_APPROVAL_USER_ID' => $this->nik,
							// 	'LEVEL2_APPROVAL_DATE' => $date
							// );
							// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} 
						} 
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				if ($random_level == 'N' && $level_tiga == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

					if ($check_pic_level_3 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
							
							// update status additional cost
							$update_additional = array(
								'status' => 'W'
							);
							$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

							// // insert data to cash request
							// $insert_cash_request = array(
							// 	'work_order_number' => $work_order_number,
							// 	'cost_id' => $cost_id,
							// 	'container_number' => $container_number,
							// 	'cost_currency' => $cost_currency,
							// 	'cost_type_id' => $cost_type_id,
							// 	'cost_group_id' => $cost_group_id,
							// 	'cost_request_amount' => $cost_request_amount,
							// 	'request_date' => $request_date,
							// 	'user_id_request' => $user_id_request,
							// 	'cost_kind' => 'A',
							// 	'user_id' => $user_id_request,
							// 	'user_date' => $date
							// );
							// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status additional cost
							$update_additional = array(
								'status' => 'R',
								'IS_DELETED' => 'Y'
							);
							$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

							// update status approval
							$data_status_barus = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level3_approval_status' => $status_level,
								'LEVEL3_APPROVAL_USER_ID' => $this->nik,
								'LEVEL3_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_barus);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '3') {
							// check if data same
							if ($check_pic_level_3 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $status_level,
									'LEVEL3_APPROVAL_USER_ID' => $this->nik,
									'LEVEL3_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($check_pic_level_3 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $check_pic_level_3
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// $data_status_baru = array(
							// 	'transaction_number' => $transaction_number,
							// 	'document_id' => $document_id,
							// 	'level3_approval_status' => $status_level,
							// 	'LEVEL3_APPROVAL_USER_ID' => $this->nik,
							// 	'LEVEL3_APPROVAL_DATE' => $date
							// );
							// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update status additional cost
									$update_additional = array(
										'status' => 'A'
									);
									$this->M_approval->update_additional($transaction_number, 'dbo.TRCASH_REQUEST_ADDITIONAL', $update_additional);

									// insert data to cash request
									$insert_cash_request = array(
										'work_order_number' => $work_order_number,
										'company_id' => $code_cmpy,
										'cost_id' => $cost_id,
										'sequence_id' => $ids,
										'container_number' => $container_number,
										'cost_currency' => $cost_currency,
										'cost_type_id' => $cost_type_id,
										'cost_group_id' => $cost_group_id,
										'cost_request_amount' => $cost_request_amount,
										'cost_invoice_amount' => $cost_invoice_amount,
										'request_date' => $request_date,
										'user_id_request' => $user_id_request,
										'cost_kind' => 'A',
										'is_transfered' => 'N',
										'user_id' => $user_id_request,
										'user_date' => $date
									);
									$this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} 
						} 
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				// $this->load->helper('comman_helper');
				// pr($data_status_baru);

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request", 1);
				} else {
					$this->session->set_flashdata('success', "Successfully update Approval!");
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

	function detail_approval_additional_selling()
	{
		$this->load->helper('comman_helper');
		$transaction_number = $this->uri->segment(3);
		$document_id = $this->uri->segment(4);
		$date = date('Y-m-d H:i:s');

		$cmpy = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_approval->get_code($cmpy)->row()->COMPANY_ID;

		$work_order_number = $this->M_approval->get_data_additional_selling($transaction_number)->row()->WORK_ORDER_NUMBER;
		$data['data_additional'] = $this->M_approval->get_data_additional_selling($transaction_number)->result();
		
		$data['approval_status'] = $this->M_approval->get_data_additional_selling($transaction_number)->row()->STATUS;
		$data['work_order_number'] = $this->M_approval->get_data_additional_selling($transaction_number)->row()->WORK_ORDER_NUMBER;
		$data['work_order_date'] = $this->M_approval->get_data_wo($work_order_number)->row()->WORK_ORDER_DATE;
		$data['vessel_name'] = $this->M_approval->get_data_wo($work_order_number)->row()->VESSEL_NAME;
		$data['trade_name'] = $this->M_approval->get_data_wo($work_order_number)->row()->TRADE_NAME;
		$data['voyage_number'] = $this->M_approval->get_data_wo($work_order_number)->row()->VOYAGE_NUMBER;

		$sel = $this->M_approval->get_data_level($transaction_number, $document_id);
		$data['level1_approval_status'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
		$data['level2_approval_status'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
		$data['level3_approval_status'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;
		// level 1
		if ($sel->row()->LEVEL1_APPROVAL_STATUS != "" && $sel->row()->LEVEL1_APPROVAL_USER_ID != "") {
			$nik_level1 = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_USER_ID;
			$data['level1_approval_name'] = $this->M_approval->get_user_nik($nik_level1)->row()->Nm_lengkap;
			$data['level1_approval_date'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_DATE;
		} else {
			$data['level1_approval_name'] = "-";
			$data['level1_approval_date'] = "-";
		}
		// level 2
		if ($sel->row()->LEVEL2_APPROVAL_STATUS != "" && $sel->row()->LEVEL2_APPROVAL_USER_ID != "") {
			$nik_level2 = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level2_approval_name'] = $this->M_approval->get_user_nik($nik_level2)->row()->Nm_lengkap;
			$data['level2_approval_date'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_DATE;
		} else {
			$data['level2_approval_name'] = "-";
			$data['level2_approval_date'] = "-";
		}
		// level 3
		if ($sel->row()->LEVEL3_APPROVAL_STATUS != "" && $sel->row()->LEVEL3_APPROVAL_USER_ID != "") {
			$nik_level3 = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level3_approval_name'] = $this->M_approval->get_user_nik($nik_level3)->row()->Nm_lengkap;
			$data['level3_approval_date'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_DATE;
		} else {
			$data['level3_approval_name'] = "-";
			$data['level3_approval_date'] = "-";
		}

		$this->form_validation->set_rules('status', 'Status Approval', 'required');
		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('approval/v_detailapprovaladditionalselling', $data);
		} else {
			
			$this->db->trans_begin();
			try {
				// declare variable
				$status = $this->input->post('status');

				// $this->load->helper('comman_helper');
				// pr($document_id);

				$level_satu = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL1;
				// $this->load->helper('comman_helper');
				// pr($level_satu);
				$level_dua = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL2;
				$level_tiga = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL3;
				$random_level = $this->M_approval->check_document($document_id)->row()->RANDOM_LEVEL;

				if ($random_level == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
					$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
					$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

					if ($check_pic_level_1 != 'Y' && $check_pic_level_2 != 'Y' && $check_pic_level_3 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status additional selling service
							$update_additional = array(
								'status' => 'R'
							);
							$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
							if ($update_additional_selling == FALSE) {
								throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
							}

							// update status approval
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'R',
								'level1_approval_status' => $status_level,
								'level1_approval_user_id' => $this->nik,
								'level1_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '1') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level1_approval_status' => $status_level,
								'level1_approval_user_id' => $this->nik,
								'level1_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y') {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
							}
						} elseif ($level == '2') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level2_approval_status' => $status_level,
								'level2_approval_user_id' => $this->nik,
								'level2_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y') {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
							}
						} elseif ($level == '3') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level3_approval_status' => $status_level,
								'level3_approval_user_id' => $this->nik,
								'level3_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y') {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
							}
						}
					}

					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				} 

				if ($random_level == 'N' && $level_satu == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
					// $this->load->helper('comman_helper');
					// pr($check_pic_level_1);
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
						
						// update status additional selling service
						$update_additional = array(
							'status' => 'W'
						);
						$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
						if ($update_additional_selling == FALSE) {
							throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
						}

						// // insert data to cash request
						// $insert_cash_request = array(
						// 	'work_order_number' => $work_order_number,
						// 	'cost_id' => $cost_id,
						// 	'container_number' => $container_number,
						// 	'cost_currency' => $cost_currency,
						// 	'cost_type_id' => $cost_type_id,
						// 	'cost_group_id' => $cost_group_id,
						// 	'cost_request_amount' => $cost_request_amount,
						// 	'request_date' => $request_date,
						// 	'user_id_request' => $user_id_request,
						// 	'cost_kind' => 'A',
						// 	'user_id' => $user_id_request,
						// 	'user_date' => $date
						// );
						// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

					} elseif ($status == 'R') {
						$status_level = 'R';

						// update status additional selling service
						$update_additional = array(
							'status' => 'R'
						);
						$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
						if ($update_additional_selling == FALSE) {
							throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
						}

						// update status approval
						$data_status_barus = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'R',
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_barus);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == 1) {
						if ($status_level != 'R' && $check_pic_level_1 != $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 } elseif ($status_level != 'R' && $check_pic_level_1 == $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'approval_status' => $status_level,
								'level1_approval_status' => $check_pic_level_1
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 }

						// $data_status_baru = array(
						// 	'transaction_number' => $transaction_number,
						// 	'document_id' => $document_id,
						// 	'level1_approval_status' => $status_level,
						// 	'LEVEL1_APPROVAL_USER_ID' => $this->nik,
						// 	'LEVEL1_APPROVAL_DATE' => $date
						// );
						// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

						 // check all level available for data approval, then check if all access level has been 'Y'
						if ($random_level == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_dua == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == "Y") {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == "Y") {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_3 == "Y") {
								// update status additional selling service
								$update_additional = array(
									'status' => 'A'
								);
								$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
								if ($update_additional_selling == FALSE) {
									throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
							}
						}
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				if ($random_level == 'N' && $level_dua == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

					if ($check_pic_level_2 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
							
							// update status additional selling service
							$update_additional = array(
								'status' => 'W'
							);
							$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
							if ($update_additional_selling == FALSE) {
								throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
							}

							// // insert data to cash request
							// $insert_cash_request = array(
							// 	'work_order_number' => $work_order_number,
							// 	'cost_id' => $cost_id,
							// 	'container_number' => $container_number,
							// 	'cost_currency' => $cost_currency,
							// 	'cost_type_id' => $cost_type_id,
							// 	'cost_group_id' => $cost_group_id,
							// 	'cost_request_amount' => $cost_request_amount,
							// 	'request_date' => $request_date,
							// 	'user_id_request' => $user_id_request,
							// 	'cost_kind' => 'A',
							// 	'user_id' => $user_id_request,
							// 	'user_date' => $date
							// );
							// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status additional selling service
							$update_additional = array(
								'status' => 'R'
							);
							$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
							if ($update_additional_selling == FALSE) {
								throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
							}

							// update status approval
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'R',
								'level1_approval_status' => $status_level,
								'level1_approval_user_id' => $this->nik,
								'level1_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '2') {
							// check if data same
							if ($status_level != 'R' && $check_pic_level_2 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $status_level,
									'LEVEL2_APPROVAL_USER_ID' => $this->nik,
									'LEVEL2_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($status_level != 'R' && $check_pic_level_2 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $check_pic_level_2
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// $data_status_baru = array(
							// 	'transaction_number' => $transaction_number,
							// 	'document_id' => $document_id,
							// 	'level2_approval_status' => $status_level,
							// 	'LEVEL2_APPROVAL_USER_ID' => $this->nik,
							// 	'LEVEL2_APPROVAL_DATE' => $date
							// );
							// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} 
						} 
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				if ($random_level == 'N' && $level_tiga == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

					if ($check_pic_level_3 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
							
							// update status additional selling service
							$update_additional = array(
								'status' => 'W'
							);
							$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
							if ($update_additional_selling == FALSE) {
								throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
							}

							// // insert data to cash request
							// $insert_cash_request = array(
							// 	'work_order_number' => $work_order_number,
							// 	'cost_id' => $cost_id,
							// 	'container_number' => $container_number,
							// 	'cost_currency' => $cost_currency,
							// 	'cost_type_id' => $cost_type_id,
							// 	'cost_group_id' => $cost_group_id,
							// 	'cost_request_amount' => $cost_request_amount,
							// 	'request_date' => $request_date,
							// 	'user_id_request' => $user_id_request,
							// 	'cost_kind' => 'A',
							// 	'user_id' => $user_id_request,
							// 	'user_date' => $date
							// );
							// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status additional selling service
							$update_additional = array(
								'status' => 'R'
							);
							$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
							if ($update_additional_selling == FALSE) {
								throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
							}

							// update status approval
							$data_status_barus = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level3_approval_status' => $status_level,
								'LEVEL3_APPROVAL_USER_ID' => $this->nik,
								'LEVEL3_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_barus);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '3') {
							// check if data same
							if ($status_level != 'R' && $check_pic_level_3 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $status_level,
									'LEVEL3_APPROVAL_USER_ID' => $this->nik,
									'LEVEL3_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($status_level != 'R' && $check_pic_level_3 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $check_pic_level_3
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// $data_status_baru = array(
							// 	'transaction_number' => $transaction_number,
							// 	'document_id' => $document_id,
							// 	'level3_approval_status' => $status_level,
							// 	'LEVEL3_APPROVAL_USER_ID' => $this->nik,
							// 	'LEVEL3_APPROVAL_DATE' => $date
							// );
							// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update status additional selling service
									$update_additional = array(
										'status' => 'A'
									);
									$update_additional_selling = $this->M_approval->update_additional_selling($transaction_number, 'dbo.TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE', $update_additional);
									if ($update_additional_selling == FALSE) {
										throw new Exception("Error Processing Request to Update Status Additional Selling Service", 1);
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
								}
							} 
						} 
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				// $this->load->helper('comman_helper');
				// pr($data_status_baru);

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request", 1);
				} else {
					$this->session->set_flashdata('success', "Successfully update Approval!");
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

	function detail_approval_operational_cost()
	{
		$this->load->helper('comman_helper');
		$transaction_number = $this->uri->segment(3);
		$document_id = $this->uri->segment(4);

		$data['data_operational'] = $this->M_approval->get_data_operational($transaction_number)->result();
		$date = date('Y-m-d H:i:s');

		$cmpy = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;

		$code_cmpy = $this->M_approval->get_code($cmpy)->row()->COMPANY_ID;

		$work_order_number = $this->M_approval->get_data_operational_param($transaction_number)->row()->WORK_ORDER_NUMBER;
		
		$data['approval_status'] = $this->M_approval->get_data_operational_param($transaction_number)->row()->STATUS;
		$data['work_order_number'] = $this->M_approval->get_data_operational_param($transaction_number)->row()->WORK_ORDER_NUMBER;
		$data['pic_name'] = $this->M_approval->get_data_operational_param($transaction_number)->row()->PIC_NAME;
		$data['work_order_date'] = $this->M_approval->get_data_wo($work_order_number)->row()->WORK_ORDER_DATE;
		$data['vessel_name'] = $this->M_approval->get_data_wo($work_order_number)->row()->VESSEL_NAME;
		$data['trade_name'] = $this->M_approval->get_data_wo($work_order_number)->row()->TRADE_NAME;
		$data['voyage_number'] = $this->M_approval->get_data_wo($work_order_number)->row()->VOYAGE_NUMBER;
		$data['customer_name'] = $this->M_approval->get_data_wo($work_order_number)->row()->CUSTOMER_NAME;

		$sel = $this->M_approval->get_data_level($transaction_number, $document_id);
		$data['level1_approval_status'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
		$data['level2_approval_status'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
		$data['level3_approval_status'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;
		// level 1
		if ($sel->row()->LEVEL1_APPROVAL_STATUS != "" && $sel->row()->LEVEL1_APPROVAL_USER_ID != "") {
			$nik_level1 = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_USER_ID;
			$data['level1_approval_name'] = $this->M_approval->get_user_nik($nik_level1)->row()->Nm_lengkap;
			$data['level1_approval_date'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_DATE;
		} else {
			$data['level1_approval_name'] = "-";
			$data['level1_approval_date'] = "-";
		}
		// level 2
		if ($sel->row()->LEVEL2_APPROVAL_STATUS != "" && $sel->row()->LEVEL2_APPROVAL_USER_ID != "") {
			$nik_level2 = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level2_approval_name'] = $this->M_approval->get_user_nik($nik_level2)->row()->Nm_lengkap;
			$data['level2_approval_date'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_DATE;
		} else {
			$data['level2_approval_name'] = "-";
			$data['level2_approval_date'] = "-";
		}
		// level 3
		if ($sel->row()->LEVEL3_APPROVAL_STATUS != "" && $sel->row()->LEVEL3_APPROVAL_USER_ID != "") {
			$nik_level3 = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_USER_ID;
			$data['level3_approval_name'] = $this->M_approval->get_user_nik($nik_level3)->row()->Nm_lengkap;
			$data['level3_approval_date'] = $this->M_approval->get_data_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_DATE;
		} else {
			$data['level3_approval_name'] = "-";
			$data['level3_approval_date'] = "-";
		}

		$this->form_validation->set_rules('status', 'Status Approval', 'required');
		// hold error messages in div
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
			$this->load->view('approval/v_detailapprovaloperationalcost', $data);
		} else {
			$this->load->helper('currency_helper');
			$mutasi_array = array();
			$data_mutasi = $this->M_approval->get_detail_mutation($transaction_number)->result();
			foreach ($data_mutasi as $key => $value) {
				if ($value->HOME_DEBIT != 0) {
					$amount_mutation = "Rp " . currency($value->HOME_DEBIT);
					array_push($mutasi_array, $amount_mutation);
				}
			}

			$mutasi_unik = array_unique($mutasi_array);
			$fix_mutation = implode(", ", $mutasi_unik);
			$detail_via = "B/S " . $fix_mutation;
			// pr($detail_via);

			$this->db->trans_begin();
			try {
				// declare variable
				$status = $this->input->post('status');
				$pelaksana = $this->input->post('pic_name');

				// $this->load->helper('comman_helper');
				// pr($document_id);

				$level_satu = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL1;
				// $this->load->helper('comman_helper');
				// pr($level_satu);
				$level_dua = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL2;
				$level_tiga = $this->M_approval->check_document($document_id)->row()->APPROVAL_LEVEL3;
				$random_level = $this->M_approval->check_document($document_id)->row()->RANDOM_LEVEL;

				if ($random_level == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
					$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
					$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

					if ($check_pic_level_1 != 'Y' && $check_pic_level_2 != 'Y' && $check_pic_level_3 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status operational cost
							$update_operational = array(
								'status' => 'R'
							);
							$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
							if ($update_operational_cost == FALSE) {
								throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
							}

							// update status approval
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'R',
								'level1_approval_status' => $status_level,
								'level1_approval_user_id' => $this->nik,
								'level1_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '1') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level1_approval_status' => $status_level,
								'level1_approval_user_id' => $this->nik,
								'level1_approval_date' => $date
							);

							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

							// check if that data has been change to Y
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y') {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($niks)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($niks)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}
								$this->M_approval->reset_connect();
							}
						} elseif ($level == '2') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level2_approval_status' => $status_level,
								'level2_approval_user_id' => $this->nik,
								'level2_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y') {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						} elseif ($level == '3') {
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level3_approval_status' => $status_level,
								'level3_approval_user_id' => $this->nik,
								'level3_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							// check if that data has been change to Y
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_3 == 'Y') {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						}
					}

					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				} 

				if ($random_level == 'N' && $level_satu == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
					// $this->load->helper('comman_helper');
					// pr($check_pic_level_1);
					$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
					if ($status == 'A') {
						$status_level = 'Y';
						
						// update status operational cost
						$update_operational = array(
							'status' => 'W'
						);
						$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
						if ($update_operational_cost == FALSE) {
							throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
						}

						// // insert data to cash request
						// $insert_cash_request = array(
						// 	'work_order_number' => $work_order_number,
						// 	'cost_id' => $cost_id,
						// 	'container_number' => $container_number,
						// 	'cost_currency' => $cost_currency,
						// 	'cost_type_id' => $cost_type_id,
						// 	'cost_group_id' => $cost_group_id,
						// 	'cost_request_amount' => $cost_request_amount,
						// 	'request_date' => $request_date,
						// 	'user_id_request' => $user_id_request,
						// 	'cost_kind' => 'A',
						// 	'user_id' => $user_id_request,
						// 	'user_date' => $date
						// );
						// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

					} elseif ($status == 'R') {
						$status_level = 'R';

						// update status operational cost
						$update_operational = array(
							'status' => 'R'
						);
						$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
						if ($update_operational_cost == FALSE) {
							throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
						}

						// update status approval
						$data_status_barus = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'R',
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
						$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_barus);

					} elseif ($status != 'A' && $status != 'R') {
						$status_level = 'W';
					} 

					if ($level == 1) {
						if ($status_level != 'R' && $check_pic_level_1 != $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'level1_approval_status' => $status_level,
								'LEVEL1_APPROVAL_USER_ID' => $this->nik,
								'LEVEL1_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 } elseif ($status_level != 'R' && $check_pic_level_1 == $status_level) {
						 	$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'W',
								'approval_status' => $status_level,
								'level1_approval_status' => $check_pic_level_1
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
						 }

						// $data_status_baru = array(
						// 	'transaction_number' => $transaction_number,
						// 	'document_id' => $document_id,
						// 	'level1_approval_status' => $status_level,
						// 	'LEVEL1_APPROVAL_USER_ID' => $this->nik,
						// 	'LEVEL1_APPROVAL_DATE' => $date
						// );
						// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

						 // check all level available for data approval, then check if all access level has been 'Y'
						if ($random_level == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						} elseif ($level_satu == "Y" && $level_dua == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}

								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						} elseif ($level_satu == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						} elseif ($level_dua == "Y" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_1 == "Y") {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_2 == "Y") {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
							$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
							$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
							$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

							if ($check_fix_level_3 == "Y") {
								// update status operational cost
								$update_operational = array(
									'status' => 'A'
								);
								$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
								if ($update_operational_cost == FALSE) {
									throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
								}

								// update is_done cash request
								// select data from transaction number
								$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
								foreach ($data_cost as $key => $value) {
									$status_cost = array(
										'is_done' => 'Y',
										'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
									);

									$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
									if ($update_cost_opr == FALSE) {
										throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
									}
								}
								
								// update trapproval_status_transaction
								$data_status_baru_approval = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'A'
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

								$date = date('Y-m-d');
								$operational_number = $transaction_number;
								$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
								$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

								$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
								$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
								$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
								// pr($data_description_vou->row());

								$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

								// get company code
								$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
								// pr($company_code);
								// get odbc
								$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

								// get table from odbc
								$table_name = substr($odbc, 0, 4);

								$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
								
								// combine voucher number
								$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

								$arr_wo = array();

								foreach ($data_operational as $key => $value) {
									array_push($arr_wo, $value->WORK_ORDER_NUMBER);
								}

								$wo_unik = array_unique($arr_wo);

								$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

								$arr_imp = array();
								foreach ($wo_unik as $value) {
									$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
									array_push($arr_imp, $temp);
								}

								$trade_fix = implode(", ", $arr_imp);

								$wo_fix = implode(", ", $wo_unik);

								$date_det = date('d.m.y');

								$detail = "WO " . $wo_fix . " " . $trade_fix . " " . $customer;

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
									'via' => $detail_via,
									'vc_code' =>$data_description_vou->row()->VC_CODE,
									'kepada' => $pelaksana,
									'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
									'curr' => $data_opr_header->row()->CURRENCY,
									'keperluan' => $detail,
									'beban' => $customer,
									'entry_by' => $niks,
									'entry_date' => $date
								);

								$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

								if ($insert_vou_head == FALSE) {
									throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
								}

								// update voucher operational to table TROPERATIONAL HEADER
								$data_update_opr = array(
									'voucher_number' => $voucher_number_out
								);

								$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

								if ($update_opr_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Operational", 1);
								}

								$seq = $get_voucher->row()->seq_no + 1;

								// update voucher number
								$update_voucher = array(
									'seq_no' => $seq
								);

								$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

								if ($update_voucher_vou == FALSE) {
									throw new Exception("Error Processing Request Update Voucher Number", 1);
								}

								$no_vou = 1;

								foreach ($data_operational as $key => $value) {
									
									$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
									if ($check_cost_share == 'N') {
										$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

											if ($insert_vou_det == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail", 1);
											}
											
										}
									} elseif ($check_cost_share == 'Y') {
										// get truck and chassis number
										$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
										$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

										// get percentase truck and chassis
										$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
										$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

										// detail vou for operational detail
										$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
										$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

										// get gl account truck
										$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
										$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

										if ($insert_vou_det_truck == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
										}

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
											'entry_by' => $niks,
											'entry_date' => $date,
										);

										$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

										if ($insert_vou_det_chassis == FALSE) {
											throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
										}
										
									}
									$no_vou++;
								}

								$this->M_approval->reset_connect();
							}
						}
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				if ($random_level == 'N' && $level_dua == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;

					if ($check_pic_level_2 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
							
							// update status operational cost
							$update_operational = array(
								'status' => 'W'
							);
							$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
							if ($update_operational_cost == FALSE) {
								throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
							}

							// // insert data to cash request
							// $insert_cash_request = array(
							// 	'work_order_number' => $work_order_number,
							// 	'cost_id' => $cost_id,
							// 	'container_number' => $container_number,
							// 	'cost_currency' => $cost_currency,
							// 	'cost_type_id' => $cost_type_id,
							// 	'cost_group_id' => $cost_group_id,
							// 	'cost_request_amount' => $cost_request_amount,
							// 	'request_date' => $request_date,
							// 	'user_id_request' => $user_id_request,
							// 	'cost_kind' => 'A',
							// 	'user_id' => $user_id_request,
							// 	'user_date' => $date
							// );
							// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status operational cost
							$update_operational = array(
								'status' => 'R'
							);
							$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
							if ($update_operational_cost == FALSE) {
								throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
							}

							// update status approval
							$data_status_baru = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => 'R',
								'level1_approval_status' => $status_level,
								'level1_approval_user_id' => $this->nik,
								'level1_approval_date' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '2') {
							// check if data same
							if ($status_level != 'R' && $check_pic_level_2 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $status_level,
									'LEVEL2_APPROVAL_USER_ID' => $this->nik,
									'LEVEL2_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($status_level != 'R' && $check_pic_level_2 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL2_APPROVAL_STATUS' => $check_pic_level_2
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// $data_status_baru = array(
							// 	'transaction_number' => $transaction_number,
							// 	'document_id' => $document_id,
							// 	'level2_approval_status' => $status_level,
							// 	'LEVEL2_APPROVAL_USER_ID' => $this->nik,
							// 	'LEVEL2_APPROVAL_DATE' => $date
							// );
							// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
									
									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}


									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} 
						} 
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				if ($random_level == 'N' && $level_tiga == "Y") {
					// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
					$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

					if ($check_pic_level_3 != 'Y') {
						// check level user
						$level = $this->M_approval->check_level($this->nik, $document_id)->row()->APPROVAL_LEVEL;
						if ($status == 'A') {
							$status_level = 'Y';
							
							// update status operational cost
							$update_operational = array(
								'status' => 'W'
							);
							$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
							if ($update_operational_cost == FALSE) {
								throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
							}

							// // insert data to cash request
							// $insert_cash_request = array(
							// 	'work_order_number' => $work_order_number,
							// 	'cost_id' => $cost_id,
							// 	'container_number' => $container_number,
							// 	'cost_currency' => $cost_currency,
							// 	'cost_type_id' => $cost_type_id,
							// 	'cost_group_id' => $cost_group_id,
							// 	'cost_request_amount' => $cost_request_amount,
							// 	'request_date' => $request_date,
							// 	'user_id_request' => $user_id_request,
							// 	'cost_kind' => 'A',
							// 	'user_id' => $user_id_request,
							// 	'user_date' => $date
							// );
							// $this->db->insert('dbo.TRCASH_REQUEST', $insert_cash_request);

						} elseif ($status == 'R') {
							$status_level = 'R';

							// update status operational cost
							$update_operational = array(
								'status' => 'R'
							);
							$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
							if ($update_operational_cost == FALSE) {
								throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
							}

							// update status approval
							$data_status_barus = array(
								'transaction_number' => $transaction_number,
								'document_id' => $document_id,
								'approval_status' => $status,
								'level3_approval_status' => $status_level,
								'LEVEL3_APPROVAL_USER_ID' => $this->nik,
								'LEVEL3_APPROVAL_DATE' => $date
							);
							$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_barus);

						} elseif ($status != 'A' && $status != 'R') {
							$status_level = 'W';
						} 

						if ($level == '3') {
							// check if data same
							if ($status_level != 'R' && $check_pic_level_3 != $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $status_level,
									'LEVEL3_APPROVAL_USER_ID' => $this->nik,
									'LEVEL3_APPROVAL_DATE' => $date
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 } elseif ($status_level != 'R' && $check_pic_level_3 == $status_level) {
							 	$data_status_baru = array(
									'transaction_number' => $transaction_number,
									'document_id' => $document_id,
									'approval_status' => 'W',
									'LEVEL3_APPROVAL_STATUS' => $check_pic_level_3
								);
								$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
							 }

							// $data_status_baru = array(
							// 	'transaction_number' => $transaction_number,
							// 	'document_id' => $document_id,
							// 	'level3_approval_status' => $status_level,
							// 	'LEVEL3_APPROVAL_USER_ID' => $this->nik,
							// 	'LEVEL3_APPROVAL_DATE' => $date
							// );
							// $this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);

							// check all level available for data approval, then check if all access level has been 'Y'
							if ($random_level == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "Y" && $level_dua == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_dua == "Y" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_1 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_2 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
								$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL1_APPROVAL_STATUS;
								$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL2_APPROVAL_STATUS;
								$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number, $document_id)->row()->LEVEL3_APPROVAL_STATUS;

								if ($check_fix_level_3 == "Y") {
									// update status operational cost
									$update_operational = array(
										'status' => 'A'
									);
									$update_operational_cost = $this->M_approval->update_operational_cost($transaction_number, 'dbo.TROPERATIONAL_HEADER', $update_operational);
									if ($update_operational_cost == FALSE) {
										throw new Exception("Error Processing Request to Update Status Operational Cost", 1);
									}

									// update is_done cash request
									// select data from transaction number
									$data_cost = $this->M_approval->get_cost_transaction($transaction_number)->result();
									foreach ($data_cost as $key => $value) {
										$status_cost = array(
											'is_done' => 'Y',
											'cost_actual_amount' => $value->COST_ACTUAL_AMOUNT
										);

										$update_cost_opr = $this->M_approval->update_cost_opr('dbo.TRCASH_REQUEST', $status_cost, $value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER, $value->COST_ID, $value->SEQUENCE_ID);
										if ($update_cost_opr == FALSE) {
											throw new Exception("Error Processing Request to Change Finished Status Cost Cash Request", 1);
										}
									}

									// update trapproval_status_transaction
									$data_status_baru_approval = array(
										'transaction_number' => $transaction_number,
										'document_id' => $document_id,
										'approval_status' => 'A'
									);
									$this->M_approval->update_status($transaction_number, $document_id, 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

									$date = date('Y-m-d');
									$operational_number = $transaction_number;
									$wo_number = $this->M_approval->get_head_operational($operational_number)->row()->WORK_ORDER_NUMBER;
									$niks = $this->M_approval->get_head_operational($operational_number)->row()->PIC_ID;

									$data_opr_header = $this->M_approval->get_data_operational_approval($operational_number);
									$data_operational = $this->M_approval->get_data_operational_detail($operational_number)->result();
									$data_description_vou = $this->M_approval->get_data_description_vou('OPERATIONAL_COST');
									// pr($data_description_vou->row());

									$pic_receiver = $this->M_approval->get_name_nik($this->nik)->row()->Nm_lengkap;

									// get company code
									$company_code = $this->M_approval->get_cmpy_code($this->nik)->row()->Cmpy_code;
									// pr($company_code);
									// get odbc
									$odbc = $this->M_approval->get_odbc($company_code)->row()->EpicorODBC;

									// get table from odbc
									$table_name = substr($odbc, 0, 4);

									$get_voucher = $this->M_approval->get_voucher_code($table_name, $company_code);
									
									// combine voucher number
									$voucher_number_out = $get_voucher->row()->first_code . $get_voucher->row()->seq_no;

									$arr_wo = array();

									foreach ($data_operational as $key => $value) {
										array_push($arr_wo, $value->WORK_ORDER_NUMBER);
									}

									$wo_unik = array_unique($arr_wo);

									$customer = $this->M_approval->get_wo($wo_unik[0])->row()->CUSTOMER_NAME;

									$arr_imp = array();
									foreach ($wo_unik as $value) {
										$temp = $this->M_approval->get_data_wo2($value)->row()->TRADE_ID;
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
										'via' => $detail_via,
										'vc_code' =>$data_description_vou->row()->VC_CODE,
										'kepada' => $pelaksana,
										'org_amt' => $data_opr_header->row()->TOTAL_ACTUAL_AMOUNT,
										'curr' => $data_opr_header->row()->CURRENCY,
										'keperluan' => $detail,
										'beban' => $customer,
										'entry_by' => $niks,
										'entry_date' => $date
									);

									$insert_vou_head = $this->M_approval->insert_vou($table_name, $company_code, 'dbo.vtrx_vou', $data_vou_out);

									if ($insert_vou_head == FALSE) {
										throw new Exception("Error Processing Request to Create Voucher Header Operational Cost", 1);
									}

									// update voucher operational to table TROPERATIONAL HEADER
									$data_update_opr = array(
										'voucher_number' => $voucher_number_out
									);

									$update_opr_vou = $this->M_approval->update_opr_vou('dbo.TROPERATIONAL_HEADER', $data_update_opr, $operational_number);

									if ($update_opr_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Operational", 1);
									}

									$seq = $get_voucher->row()->seq_no + 1;

									// update voucher number
									$update_voucher = array(
										'seq_no' => $seq
									);

									$update_voucher_vou = $this->M_approval->update_voucher($table_name, $company_code, 'dbo.vutil_genr', $update_voucher, 'VC');

									if ($update_voucher_vou == FALSE) {
										throw new Exception("Error Processing Request Update Voucher Number", 1);
									}

									$no_vou = 1;

									foreach ($data_operational as $key => $value) {
										
										$check_cost_share = $this->M_approval->check_cost_share($value->COST_ID)->row()->COST_SHARE;
										if ($check_cost_share == 'N') {
											$gl_account = $this->M_approval->check_cost_share($value->COST_ID)->row()->GL_ACCOUNT;

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
													'entry_by' => $niks,
													'entry_date' => $date,
												);

												$insert_vou_det = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_out);

												if ($insert_vou_det == FALSE) {
													throw new Exception("Error Processing Request Entry Voucher Detail", 1);
												}
												
											}
										} elseif ($check_cost_share == 'Y') {
											// get truck and chassis number
											$truck_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->TRUCK_ID_NUMBER;
											$chassis_number = $this->M_approval->get_detail_truck($value->WORK_ORDER_NUMBER, $value->CONTAINER_NUMBER)->row()->CHASIS_ID_NUMBER;

											// get percentase truck and chassis
											$percent_truck = $this->M_approval->get_percent_truck($truck_number)->row()->SHARE_OPERATION_COST;
											$percent_chassis = $this->M_approval->get_percent_chassis($chassis_number)->row()->SHARE_OPERATION_COST;

											// detail vou for operational detail
											$detail_opr_truck = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $truck_number;
											$detail_opr_chassis = "WO " . $wo_number . " " . $arr_imp[0] . " " . $date_det . " " . $customer . " " . $value->COST_NAME . " " . $chassis_number;

											// get gl account truck
											$gl_account_truck = $this->M_approval->get_gl_account_truck($truck_number, $value->COST_ID)->row()->ACCOUNT_CODE;
											$gl_account_chassis = $this->M_approval->get_gl_account_chassis($chassis_number, $value->COST_ID)->row()->ACCOUNT_CODE;

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_truck = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_truck);

											if ($insert_vou_det_truck == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Truck", 1);
											}

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
												'entry_by' => $niks,
												'entry_date' => $date,
											);

											$insert_vou_det_chassis = $this->M_approval->insert_vou_det($table_name, $company_code, 'dbo.vtrx_vou_det', $data_vou_det_chassis);

											if ($insert_vou_det_chassis == FALSE) {
												throw new Exception("Error Processing Request Entry Voucher Detail Chassis", 1);
											}
											
										}
										$no_vou++;
									}

									$this->M_approval->reset_connect();
								}
							} 
						} 
					}
					unset($check_pic_level_1);
					unset($check_pic_level_2);
					unset($check_pic_level_3);
				}

				// $this->load->helper('comman_helper');
				// pr($data_status_baru);

				if ($this->db->trans_status() === FALSE) {
					throw new Exception("Error Processing Request", 1);
				} else {
					$this->session->set_flashdata('success', "Successfully update Approval!");
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