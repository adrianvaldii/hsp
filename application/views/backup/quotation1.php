// $this->load->helper('comman_helper');
			// pr($document_id);

			// for ($i=0; $i < count($transaction_number); $i++) {
			// 	// count total approval
			// 	$level_satu = $this->M_approval->check_document($document_id[$i])->row()->APPROVAL_LEVEL1;
			// 	// $this->load->helper('comman_helper');
			// 	// pr($level_satu);
			// 	$level_dua = $this->M_approval->check_document($document_id[$i])->row()->APPROVAL_LEVEL2;
			// 	$level_tiga = $this->M_approval->check_document($document_id[$i])->row()->APPROVAL_LEVEL3;
			// 	$random_level = $this->M_approval->check_document($document_id[$i])->row()->RANDOM_LEVEL;

			// 	if ($random_level == "Y") {
			// 		// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
			// 		$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 		$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 		$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 		if ($check_pic_level_1 != 'Y' && $check_pic_level_2 != 'Y' && $check_pic_level_3 != 'Y') {
			// 			// check level user
			// 			$level = $this->M_approval->check_level($this->nik, $document_id[$i])->row()->APPROVAL_LEVEL;
			// 			if ($status[$i] == 'A') {
			// 				$status_level = 'Y';
			// 			} elseif ($status[$i] != 'A') {
			// 				$status_level = 'W';
			// 			} 

			// 			if ($level == '1') {
			// 				$data_status_baru = array(
			// 					'transaction_number' => $transaction_number[$i],
			// 					'document_id' => $document_id[$i],
			// 					'approval_status' => $status[$i],
			// 					'level1_approval_status' => $status_level
			// 				);
			// 				$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 				// check if that data has been change to Y
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;

			// 				if ($check_fix_level_1 == 'Y') {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 				}
			// 			} elseif ($level == '2') {
			// 				$data_status_baru = array(
			// 					'transaction_number' => $transaction_number[$i],
			// 					'document_id' => $document_id[$i],
			// 					'approval_status' => $status[$i],
			// 					'level2_approval_status' => $status_level
			// 				);
			// 				$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 				// check if that data has been change to Y
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;

			// 				if ($check_fix_level_2 == 'Y') {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 				}
			// 			} elseif ($level == '3') {
			// 				$data_status_baru = array(
			// 					'transaction_number' => $transaction_number[$i],
			// 					'document_id' => $document_id[$i],
			// 					'approval_status' => $status[$i],
			// 					'level3_approval_status' => $status_level
			// 				);
			// 				$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 				// check if that data has been change to Y
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_1 == 'Y') {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 				}
			// 			}
			// 		}

			// 		unset($check_pic_level_1);
			// 		unset($check_pic_level_2);
			// 		unset($check_pic_level_3);
			// 	} 

			// 	if ($random_level == 'N' && $level_satu == "Y") {
			// 		// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
			// 		$check_pic_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 		// $this->load->helper('comman_helper');
			// 		// pr($check_pic_level_1);
			// 		$level = $this->M_approval->check_level($this->nik, $document_id[$i])->row()->APPROVAL_LEVEL;
			// 		if ($status[$i] == 'A') {
			// 			$status_level = 'Y';
			// 		} elseif ($status[$i] != 'A') {
			// 			$status_level = 'W';
			// 		} 

			// 		if ($level == 1) {
			// 			if ($check_pic_level_1 != $status_level) {
			// 			 	$data_status_baru = array(
			// 					'transaction_number' => $transaction_number[$i],
			// 					'document_id' => $document_id[$i],
			// 					'approval_status' => 'W',
			// 					'level1_approval_status' => $status_level,
			// 					'LEVEL1_APPROVAL_USER_ID' => $this->nik,
			// 					'LEVEL1_APPROVAL_DATE' => $date
			// 				);
			// 				$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 			 } elseif ($check_pic_level_1 == $status_level) {
			// 			 	$data_status_baru = array(
			// 					'transaction_number' => $transaction_number[$i],
			// 					'document_id' => $document_id[$i],
			// 					'approval_status' => 'W',
			// 					'level1_approval_status' => $check_pic_level_1
			// 				);
			// 				$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 			 }

			// 			 // check all level available for data approval, then check if all access level has been 'Y'
			// 			if ($random_level == "Y") {
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 					// update trapproval_status_transaction
			// 					$data_status_baru_approval = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 				}
			// 			} elseif ($level_satu == "Y" && $level_dua == "Y") {
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 					// update trapproval_status_transaction
			// 					$data_status_baru_approval = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 				}
			// 			} elseif ($level_satu == "Y" && $level_tiga == "Y") {
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 					// update trapproval_status_transaction
			// 					$data_status_baru_approval = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 				}
			// 			} elseif ($level_dua == "Y" && $level_tiga == "Y") {
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 					// update trapproval_status_transaction
			// 					$data_status_baru_approval = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 				}
			// 			} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_1 == "Y") {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 					// update trapproval_status_transaction
			// 					$data_status_baru_approval = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 				}
			// 			} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_2 == "Y") {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 					// update trapproval_status_transaction
			// 					$data_status_baru_approval = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 				}
			// 			} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
			// 				$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 				$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 				$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 				if ($check_fix_level_3 == "Y") {
			// 					// update quotation
			// 					$data_status_baru_quotation = array(
			// 						'quotation_number' => $transaction_number[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 					// update trapproval_status_transaction
			// 					$data_status_baru_approval = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'A'
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 				}
			// 			}
			// 		}
			// 		unset($check_pic_level_1);
			// 		unset($check_pic_level_2);
			// 		unset($check_pic_level_3);
			// 	}

			// 	if ($random_level == 'N' && $level_dua == "Y") {
			// 		// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
			// 		$check_pic_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;

			// 		if ($check_pic_level_2 != 'Y') {
			// 			// check level user
			// 			$level = $this->M_approval->check_level($this->nik, $document_id[$i])->row()->APPROVAL_LEVEL;
			// 			if ($status[$i] == 'A') {
			// 				$status_level = 'Y';
			// 			} elseif ($status[$i] != 'A') {
			// 				$status_level = 'W';
			// 			} 

			// 			if ($level == '2') {
			// 				// check if data same
			// 				if ($check_pic_level_2 != $status_level) {
			// 				 	$data_status_baru = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'W',
			// 						'LEVEL2_APPROVAL_STATUS' => $status_level,
			// 						'LEVEL2_APPROVAL_USER_ID' => $this->nik,
			// 						'LEVEL2_APPROVAL_DATE' => $date
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 				 } elseif ($check_pic_level_2 == $status_level) {
			// 				 	$data_status_baru = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'W',
			// 						'LEVEL2_APPROVAL_STATUS' => $check_pic_level_2
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 				 }

			// 				// check all level available for data approval, then check if all access level has been 'Y'
			// 				if ($random_level == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "Y" && $level_dua == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

			// 					}
			// 				} elseif ($level_satu == "Y" && $level_tiga == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);

			// 					}
			// 				} elseif ($level_dua == "Y" && $level_tiga == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);

			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);

			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_2 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);

			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);

			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} 
			// 			} 
			// 		}
			// 		unset($check_pic_level_1);
			// 		unset($check_pic_level_2);
			// 		unset($check_pic_level_3);
			// 	}

			// 	if ($random_level == 'N' && $level_tiga == "Y") {
			// 		// CHECK DATA TRAPPROVAL_STATUS... ALL LEVEL
			// 		$check_pic_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 		if ($check_pic_level_3 != 'Y') {
			// 			// check level user
			// 			$level = $this->M_approval->check_level($this->nik, $document_id[$i])->row()->APPROVAL_LEVEL;
			// 			if ($status[$i] == 'A') {
			// 				$status_level = 'Y';
			// 			} elseif ($status[$i] != 'A') {
			// 				$status_level = 'W';
			// 			} 

			// 			if ($level == '3') {
			// 				// check if data same
			// 				if ($check_pic_level_3 != $status_level) {
			// 				 	$data_status_baru = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'W',
			// 						'LEVEL3_APPROVAL_STATUS' => $status_level,
			// 						'LEVEL3_APPROVAL_USER_ID' => $this->nik,
			// 						'LEVEL3_APPROVAL_DATE' => $date
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 				 } elseif ($check_pic_level_3 == $status_level) {
			// 				 	$data_status_baru = array(
			// 						'transaction_number' => $transaction_number[$i],
			// 						'document_id' => $document_id[$i],
			// 						'approval_status' => 'W',
			// 						'LEVEL3_APPROVAL_STATUS' => $check_pic_level_3
			// 					);
			// 					$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru);
			// 				 }

			// 				// check all level available for data approval, then check if all access level has been 'Y'
			// 				if ($random_level == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == 'Y' || $check_fix_level_2 == "Y" || $check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "Y" && $level_dua == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == 'Y' && $check_fix_level_2 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "Y" && $level_tiga == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == 'Y' && $check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_dua == "Y" && $level_tiga == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_2 == 'Y' && $check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "Y" && $level_dua == "N" && $level_tiga == "N") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_1 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "N" && $level_dua == "Y" && $level_tiga == "N") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_2 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} elseif ($level_satu == "N" && $level_dua == "N" && $level_tiga == "Y") {
			// 					$check_fix_level_1 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL1_APPROVAL_STATUS;
			// 					$check_fix_level_2 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL2_APPROVAL_STATUS;
			// 					$check_fix_level_3 = $this->M_approval->check_pic_level($transaction_number[$i])->row()->LEVEL3_APPROVAL_STATUS;

			// 					if ($check_fix_level_3 == "Y") {
			// 						// update quotation
			// 						$data_status_baru_quotation = array(
			// 							'quotation_number' => $transaction_number[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status_quotation($transaction_number[$i], 'dbo.TRQUOTATION', $data_status_baru_quotation);
			// 						// update trapproval_status_transaction
			// 						$data_status_baru_approval = array(
			// 							'transaction_number' => $transaction_number[$i],
			// 							'document_id' => $document_id[$i],
			// 							'approval_status' => 'A'
			// 						);
			// 						$this->M_approval->update_status($transaction_number[$i], $document_id[$i], 'dbo.TRAPPROVAL_STATUS_TRANSACTION', $data_status_baru_approval);
			// 					}
			// 				} 
			// 			} 
			// 		}
			// 		unset($check_pic_level_1);
			// 		unset($check_pic_level_2);
			// 		unset($check_pic_level_3);
			// 	}
			// }

			// $this->load->helper('comman_helper');
			// pr($data_status_baru);