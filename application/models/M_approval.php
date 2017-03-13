<?php

class M_Approval extends CI_Model {

	private $db2;
	private $db3;

	public function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('crm', TRUE);
		$this->db3 = $this->load->database('pltapol', TRUE);
		$this->db4 = $this->load->database('ehsj', TRUE);
		$this->db5 = $this->load->database('ehss', TRUE);
	}

	function get_cmpy_code($nik)
	{
		// $this->db3->db_select();
		// $this->db3->select("*");
		// $this->db3->from("dbo.u_nik_cmpy");
		// $this->db3->where("nik", $nik);

		// return $this->db3->get();
		return $this->db3->query("SELECT * FROM pltapol..u_nik_cmpy WHERE Nik = '$nik'");
	}

	function get_code($cmpy)
	{
		$this->db->where("COMPANY_GLOBAL_ID", $cmpy);

		return $this->db->get("dbo.MCOMPANY");
	}

	function get_max_cash($cost_additional, $container_numbers)
	{
		return $this->db->query("SELECT MAX(SEQUENCE_ID)+1 AS id FROM HSP..TRCASH_REQUEST WHERE COST_ID = '$cost_additional' AND CONTAINER_NUMBER = '$container_numbers'");
	}

	function get_data_operational($trx_operational)
	{
		return $this->db->query("SELECT DISTINCT MAIN.TRX_OPERATIONAL, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.SEQUENCE_ID, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.COST_ACTUAL_AMOUNT, MAIN.TRANSACTION_ID, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, COST.COST_NAME, BANK.DESCRIPTION_1 AS MUTATION_DESCRIPTION, MAIN.COST_AMOUNT, WO_SERVICE.CONTAINER_SIZE_ID, WO_SERVICE.CONTAINER_TYPE_ID, WO_SERVICE.CONTAINER_CATEGORY_ID, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME FROM HSP..TROPERATIONAL_DETAIL MAIN LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..TRBANK_STATEMENT BANK ON MAIN.TRANSACTION_ID = BANK.TRANSACTION_ID LEFT JOIN (SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE) AS WO_SERVICE ON MAIN.WORK_ORDER_NUMBER = WO_SERVICE.WORK_ORDER_NUMBER LEFT JOIN HSP..MLOCATION FROM_LOC ON WO_SERVICE.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON WO_SERVICE.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE MAIN.TRX_OPERATIONAL = '$trx_operational'");
	}

	function get_data_operational_param($transaction_number)
	{
		return $this->db->query("SELECT *, PIC.Nm_lengkap as PIC_NAME FROM HSP..TROPERATIONAL_HEADER MAIN LEFT JOIN pltapol..u_nik PIC ON MAIN.PIC_ID = PIC.Nik WHERE TRX_OPERATIONAL = '$transaction_number'");
	}

	function get_data_operational_approval($trx_operational)
	{
		return $this->db->query("SELECT *, CONVERT(CHAR(11),USER_DATE,106) as OPR_DATE, USER_DATE as OPR_DATE2 FROM HSP..TROPERATIONAL_HEADER WHERE TRX_OPERATIONAL = '$trx_operational'");	
	}

	function get_data_operational_detail($trx_operational)
	{
		return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.TRX_OPERATIONAL, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.SEQUENCE_ID, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.COST_ACTUAL_AMOUNT, MAIN.TRANSACTION_ID, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, COST.COST_NAME, BANK.DESCRIPTION_1 AS MUTATION_DESCRIPTION, MAIN.COST_AMOUNT, WO_SERVICE.CONTAINER_SIZE_ID, WO_SERVICE.CONTAINER_TYPE_ID, WO_SERVICE.CONTAINER_CATEGORY_ID, BANK.HOME_DEBIT FROM HSP..TROPERATIONAL_DETAIL MAIN LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..TRBANK_STATEMENT BANK ON MAIN.TRANSACTION_ID = BANK.TRANSACTION_ID LEFT JOIN HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WO_SERVICE ON MAIN.CONTAINER_NUMBER = WO_SERVICE.CONTAINER_NUMBER WHERE MAIN.TRX_OPERATIONAL = '$trx_operational'");
	}

	function get_data_description_vou($detail)
	{
		return $this->db->query("SELECT * FROM HSP..MDESCRIPTION_VOUCHER WHERE DETAIL = '$detail'");
	}

	function get_name_nik($nik)
	{
		return $this->db->query("SELECT Nik, Nm_lengkap FROM pltapol..u_nik WHERE Nik = '$nik'");
	}

	function get_user_nik($nik)
	{
		// $this->db3->distinct();
		// $this->db3->select("Nm_lengkap");
		// $this->db3->from("dbo.u_nik");
		// $this->db3->where("Nik", $nik);

		// return $this->db3->get();

		return $this->db3->query("SELECT DISTINCT Nm_lengkap FROM pltapol..u_nik WHERE Nik = '$nik'");
	}

	function get_odbc($company_code)
	{
		$this->db3->db_select();
		return $this->db3->query("select * from usettingBranch where CompFullName like '%hanoman%' and Company_code = '$company_code'");
	}

	function get_voucher_code($table_name, $company_code)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	return $this->db4->query("select * from $table_name..vutil_genr where genr_type = 'VC'");
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	return $this->db5->query("select * from $table_name..vutil_genr where genr_type = 'VC'");
		// }
		$this->db->db_select('eHsj');
		return $this->db->query("select * from $table_name..vutil_genr where genr_type = 'VC'");
	}

	function get_wo($work_order_number)
	{
		$this->db->db_select('HSP');
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.SHIPPER, MAIN.CONSIGNEE, MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(10), MAIN.WORK_ORDER_DATE, 126) AS WORK_ORDER_DATE, MAIN.VESSEL_ID, MAIN.POL_ID, MAIN.POD_ID, MAIN.CUSTOMER_ID, MAIN.REFERENCE_NUMBER, MAIN.TRADE_ID, VESSEL.VESSEL_NAME, PORT1.PORT_NAME AS POL_NAME, PORT2.PORT_NAME AS POD_NAME, CUSTOMER.NAME AS CUSTOMER_NAME, MAIN.VOYAGE_NUMBER, CONVERT(CHAR(10), MAIN.ETA, 126) AS ETA, CONVERT(CHAR(10), MAIN.ETD, 126) AS ETD FROM HSP..TRWORKORDER MAIN INNER JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID INNER JOIN HSP..MPORT PORT1 ON MAIN.POL_ID = PORT1.PORT_ID INNER JOIN HSP..MPORT PORT2 ON MAIN.POD_ID = PORT2.PORT_ID INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_data_wo2($work_order_number)
	{
		$this->db->db_select('HSP');
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(11), MAIN.WORK_ORDER_DATE, 106) AS WORK_ORDER_DATE, VESSEL.VESSEL_NAME, TRADE.GENERAL_DESCRIPTION AS TRADE_NAME, MAIN.TRADE_ID, MAIN.VOYAGE_NUMBER, CUSTOMER.NAME AS CUSTOMER_NAME FROM HSP..TRWORKORDER MAIN LEFT JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..MGENERAL_ID TRADE ON MAIN.TRADE_ID = TRADE.GENERAL_ID LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function insert_vou($table_name, $company_code, $table, $data)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	$this->db4->insert($table, $data);
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	$this->db5->insert($table, $data);
		// }
		$this->db->db_select('eHsj');
		
		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_vou_det($table_name, $company_code, $table, $data)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	$this->db4->insert($table, $data);
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	$this->db5->insert($table, $data);
		// }
		$this->db->db_select('eHsj');

		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_opr_vou($table, $data_update_opr, $operational_number)
	{
		$this->db->db_select('HSP');
		$this->db->where("TRX_OPERATIONAL", $operational_number);
		if (!$this->db->update($table, $data_update_opr)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_voucher($table_name, $company_code, $table, $data, $type)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	$this->db4->set($update_voucher);
		// 	$this->db4->where("genr_type", $type);
		// 	$this->db4->update($table, $update_voucher);
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	$this->db5->set($update_voucher);
		// 	$this->db5->where("genr_type", $type);
		// 	$this->db5->update($table, $update_voucher);
		// }
		$this->db->db_select('eHsj');
		$this->db->set($data);

		$this->db->where("genr_type", $type);
		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function reset_connect()
	{
		$this->db->db_select('HSP');
	}

	function check_cost_share($cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST WHERE COST_ID = '$cost_id'");
	}

	function get_detail_truck($work_order_number, $container_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_TRUCKING WHERE WORK_ORDER_NUMBER = '$work_order_number' AND CONTAINER_NUMBER = '$container_number'");
	}

	function get_percent_truck($truck_number)
	{
		return $this->db->query("SELECT * FROM HSP..MTRUCK WHERE TRUCK_ID = '$truck_number'");
	}

	function get_percent_chassis($chassis_number)
	{
		return $this->db->query("select * from HSP..MCHASSIS where CHASSIS_ID = '$chassis_number'");
	}

	function get_gl_account_truck($truck_number, $cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..GLCHART_TRUCK WHERE TRUCK_ID = '$truck_number' AND COST_ID = '$cost_id'");
	}

	function get_gl_account_chassis($chassis_number, $cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..GLCHART_CHASSIS WHERE CHASSIS_ID = '$chassis_number' AND COST_ID = '$cost_id'");
	}

	function update_operational_cost($transaction_number, $table, $update_operational)
	{
		$this->db->where("TRX_OPERATIONAL", $transaction_number);

		if (!$this->db->update($table, $update_operational)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_head_operational($operational_number)
	{
		return $this->db->query("SELECT * FROM HSP..TROPERATIONAL_HEADER WHERE TRX_OPERATIONAL = '$operational_number'");
	}



	public function get_approval($nik)
	{
		return $this->db->query("select distinct main.TRANSACTION_NUMBER, main.DOCUMENT_ID, document.DOCUMENT_NAME, main.APPROVAL_STATUS, CONVERT(char(11), main.REQUEST_APPROVAL_DATE,106) AS REQUEST_APPROVAL_DATE, main.REVISION_NUMBER from HSP..TRAPPROVAL_STATUS_TRANSACTION main left join HSP..MDOCUMENT_ID document on main.DOCUMENT_ID = document.DOCUMENT_ID left join HSP..MDOCUMENT_LEVEL_APPROVAL_PIC document_pic on document.DOCUMENT_ID = document_pic.DOCUMENT_ID where document_pic.APPROVAL_USER_ID = '$nik' AND (main.APPROVAL_STATUS = 'N' OR main.APPROVAL_STATUS = 'W') ORDER BY REQUEST_APPROVAL_DATE DESC");
	}

	public function update_status($transaction_number, $document_id, $table, $data_status_baru)
	{
		$this->db->where("TRANSACTION_NUMBER", $transaction_number);
		$this->db->where("DOCUMENT_ID", $document_id);

		$this->db->update($table, $data_status_baru);
	}

	public function check_document($document_id)
	{
		$this->db->select("*");
		$this->db->from("dbo.MDOCUMENT_LEVEL_APPROVAL");
		$this->db->where("DOCUMENT_ID", $document_id);

		return $this->db->get();
	}

	public function check_level($nik, $document_id)
	{
		$this->db->select("*");
		$this->db->from("dbo.MDOCUMENT_LEVEL_APPROVAL_PIC");
		$this->db->where("APPROVAL_USER_ID", $nik);
		$this->db->where("DOCUMENT_ID", $document_id);

		return $this->db->get();
	}

	public function check_pic_level($transaction_number, $document_id)
	{
		$this->db->select("LEVEL1_APPROVAL_STATUS, LEVEL2_APPROVAL_STATUS, LEVEL3_APPROVAL_STATUS");
		$this->db->from("dbo.TRAPPROVAL_STATUS_TRANSACTION");
		$this->db->where("TRANSACTION_NUMBER", $transaction_number);
		$this->db->where("DOCUMENT_ID", $document_id);

		return $this->db->get();
	}

	public function update_status_quotation($transaction_number, $table, $data_status_baru_quotation)
	{
		$this->db->where("QUOTATION_NUMBER", $transaction_number);

		$this->db->update($table, $data_status_baru_quotation);
	}

	function get_data_quote_trucking($quotation_number)
	{
		$this->db->select("MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, CONVERT(char(11), MAIN.START_DATE,106) AS START_DATE, CONVERT(char(11), MAIN.END_DATE,106) AS END_DATE, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.SELLING_OFFERING_RATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		return $this->db->get();
	} 

	function get_data_quote_customs($quotation_number)
	{
		$this->db->select("MAIN.CUSTOM_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, CONVERT(char(11), MAIN.START_DATE,106) AS START_DATE, CONVERT(char(11), MAIN.END_DATE,106) AS END_DATE, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.SELLING_OFFERING_RATE, MAIN.CUSTOM_KIND_ID, MAIN.CUSTOM_LINE_ID");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		return $this->db->get();
	}

	function get_data_quote_location($quotation_number)
	{
		$this->db->select("MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(11), MAIN.START_DATE,106) AS START_DATE, CONVERT(char(11), MAIN.END_DATE,106) AS END_DATE, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.SELLING_OFFERING_RATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, MAIN.TRUCK_KIND_ID, TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME");
		$this->db->from("dbo.TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MGENERAL_ID TRUCK", "MAIN.TRUCK_KIND_ID = TRUCK.GENERAL_ID", "left");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		return $this->db->get();
	}

	function get_data_quote_weight($quotation_number)
	{
		$this->db->select("MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(11), MAIN.START_DATE,106) AS START_DATE, CONVERT(char(11), MAIN.END_DATE,106) AS END_DATE, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.SELLING_OFFERING_RATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT, MAIN.MEASUREMENT_UNIT");
		$this->db->from("dbo.TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		return $this->db->get();
	}

	function get_data_quote_ocean_freight($quotation_number)
	{
		$this->db->select("MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, CONVERT(char(11), MAIN.START_DATE,106) AS START_DATE, CONVERT(char(11), MAIN.END_DATE,106) AS END_DATE, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.SELLING_OFFERING_RATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, MAIN.CHARGE_ID");
		$this->db->from("dbo.TRQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		return $this->db->get();
	}

	function get_quotation_param_full($quotation_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');
		
		return $this->db->query("select MAIN.QUOTATION_NUMBER, MAIN.QUOTATION_DOCUMENT_NUMBER,  CONVERT(char(11), MAIN.QUOTATION_PERIODE_START,106) AS QUOTATION_PERIODE_START, CONVERT(char(11), MAIN.QUOTATION_DATE,106) AS QUOTATION_DATE, CONVERT(char(11), MAIN.QUOTATION_PERIODE_END,106) AS QUOTATION_PERIODE_END, MAIN.CUSTOMER_ID, MAIN.REVESION_NUMBER, MAIN.APPROVAL_STATUS, COMPANY.NAME AS COMPANY_NAME, CUSTOMER.NAME, MAIN.APPROVAL_STATUS from HSP..TRQUOTATION MAIN left join [192.168.11.28].[CRM].[dbo].[MCOMPANY] as COMPANY ON MAIN.CUSTOMER_ID = COMPANY.COMPANY_ID left join CRM..MCUSTOMER_CONTACT CUSTOMER ON COMPANY.COMPANY_ID = CUSTOMER.COMPANY_ID WHERE MAIN.QUOTATION_NUMBER = $quotation_number");
	}

	function get_quotation_param_full2($quotation_number)
	{
		return $this->db->query("select CUSTOMER.NAME from HSP..TRQUOTATION MAIN left join CRM..MCOMPANY COMPANY ON MAIN.CUSTOMER_ID = COMPANY.COMPANY_ID left join CRM..MCUSTOMER_CONTACT CUSTOMER ON COMPANY.COMPANY_ID = CUSTOMER.COMPANY_ID WHERE MAIN.QUOTATION_NUMBER = $quotation_number");
	}

	function update_new($quotation_number, $table, $update_new)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		$this->db->update($table, $update_new);
	}

	function get_quotation_number($transaction_number)
	{
		$this->db->where("AGREEMENT_NUMBER", $transaction_number);
		return $this->db->get("dbo.TRAGREEMENT");
	}

	function get_agreement_param($agreement_number)
	{
		return $this->db->query("select distinct MAIN.AGREEMENT_NUMBER, MAIN.QUOTATION_NUMBER, CONVERT(char(10), MAIN.AGREEMENT_DATE,126) AS AGREEMENT_DATE, CONVERT(char(11), MAIN.AGREEMENT_PERIODE_START,106) AS AGREEMENT_PERIODE_START, CONVERT(char(11), MAIN.AGREEMENT_PERIODE_END,106) AS AGREEMENT_PERIODE_END, MAIN.AGREEMENT_DOCUMENT_NUMBER, MAIN.APPROVAL_STATUS, QUOTATION.CUSTOMER_ID, CUSTOMER.NAME AS COMPANY_NAME, MAIN.AMENDMENT_NUMBER from HSP..TRAGREEMENT MAIN left join HSP..TRQUOTATION QUOTATION on MAIN.QUOTATION_NUMBER = QUOTATION.QUOTATION_NUMBER left join CRM..MCOMPANY CUSTOMER on QUOTATION.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.AGREEMENT_NUMBER = '$agreement_number'");
	}

	function update_status_agreement($transaction_number, $table, $data_status_baru_agreement)
	{
		$this->db->where("AGREEMENT_NUMBER", $transaction_number);

		$this->db->update($table, $data_status_baru_agreement);
	}

	function update_new_agreement($agreement_number, $table, $update_new)
	{
		$this->db->where("AGREEMENT_NUMBER", $agreement_number);

		$this->db->update($table, $update_new);
	}

	function update_quotation($quotation_number, $table, $data)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		$this->db->update($table, $data);
	}

	function get_data_level($transaction_number, $document_id)
	{
		$this->db->where("TRANSACTION_NUMBER", $transaction_number);
		$this->db->where("DOCUMENT_ID", $document_id);

		return $this->db->get("dbo.TRAPPROVAL_STATUS_TRANSACTION");
	}

	public function get_approval_all()
	{
		return $this->db->query("select distinct main.TRANSACTION_NUMBER, main.DOCUMENT_ID, document.DOCUMENT_NAME, main.APPROVAL_STATUS, CONVERT(char(11), main.REQUEST_APPROVAL_DATE,106) AS REQUEST_APPROVAL_DATE, main.REVISION_NUMBER from HSP..TRAPPROVAL_STATUS_TRANSACTION main left join HSP..MDOCUMENT_ID document on main.DOCUMENT_ID = document.DOCUMENT_ID left join HSP..MDOCUMENT_LEVEL_APPROVAL_PIC document_pic on document.DOCUMENT_ID = document_pic.DOCUMENT_ID where main.APPROVAL_STATUS = 'A' OR main.APPROVAL_STATUS = 'R' ORDER BY REQUEST_APPROVAL_DATE DESC");
	}

	function get_detail_trucking_cost($quotation_number, $from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function get_data_additional_selling($additional_selling)
	{
		return $this->db->query("SELECT MAIN.REMARKS, MAIN.WORK_ORDER_NUMBER, MAIN.ADDITIONAL_SELLING, MAIN.CONTAINER_NUMBER, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.TARIFF_CURRENCY, MAIN.TARIFF_AMOUNT, SERVICE_NM.SERVICE_NAME, MAIN.STATUS FROM HSP..TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MSELLING_SERVICE SERVICE_NM ON MAIN.SELLING_SERVICE_ID = SERVICE_NM.SELLING_SERVICE_ID WHERE ADDITIONAL_SELLING = '$additional_selling'");
	}

	function get_detail_trucking_name($from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("A.COMPANY_NAME, B.SERVICE_NAME, MAIN.FROM_LOCATION_ID, E.LOCATION_NAME AS FROM_NAME, MAIN.TO_LOCATION_ID, F.LOCATION_NAME AS TO_NAME, H.GENERAL_DESCRIPTION AS CONTAINER_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOMPANY_SERVICES D", "MAIN.COMPANY_SERVICE_ID = D.COMPANY_SERVICE_ID", "left");
		$this->db->join("dbo.MCOMPANY A", "D.COMPANY_ID = A.COMPANY_ID", "left");
		$this->db->join("dbo.MSELLING_SERVICE B", "MAIN.SELLING_SERVICE_ID = B.SELLING_SERVICE_ID", "left");
		$this->db->join("dbo.MLOCATION E", "MAIN.FROM_LOCATION_ID = E.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION F", "MAIN.TO_LOCATION_ID = F.LOCATION_ID", "left");
		$this->db->join("dbo.MGENERAL_ID H", "MAIN.CONTAINER_TYPE_ID = H.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.FROM_QTY < 2 ");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 20
	public function get_container_cost_detail_20($quotation_number, $from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '20'");
		$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);
		$this->db->order_by("COST_NAME", "ASC");
		$this->db->order_by("MAIN.FROM_QTY", "ASC");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 40
	public function get_container_cost_detail_40($quotation_number, $from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '40'");
		$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);
		$this->db->order_by("COST_NAME", "ASC");
		$this->db->order_by("MAIN.FROM_QTY", "ASC");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 4h
	public function get_container_cost_detail_4h($quotation_number, $from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '4H'");
		$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);
		$this->db->order_by("COST_NAME", "ASC");
		$this->db->order_by("MAIN.FROM_QTY", "ASC");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 45
	public function get_container_cost_detail_45($quotation_number, $from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '45'");
		$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);
		$this->db->order_by("COST_NAME", "ASC");
		$this->db->order_by("MAIN.FROM_QTY", "ASC");

		return $this->db->get();
	}

	function get_floor_market_trucking($from_location, $to_location, $type, $size, $category)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("FROM_LOCATION_ID = ", $from_location);
		$this->db->where("TO_LOCATION_ID = ", $to_location);
		$this->db->where("CONTAINER_TYPE_ID = ", $type);
		$this->db->where("CONTAINER_SIZE_ID = ", $size);
		$this->db->where("CONTAINER_CATEGORY_ID = ", $category);

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 20
	public function get_container_custom_cost_detail_20($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_SERVICE_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "left");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("A.CUSTOM_LINE_ID = ", $customs_line);
		$this->db->where("A.CUSTOM_KIND_ID = ", $customs_kind);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("A.QUOTATION_NUMBER = ", $quotation_number);
		$this->db->where("A.CONTAINER_SIZE_ID = '20'");

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 40
	public function get_container_custom_cost_detail_40($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_SERVICE_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "left");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("A.CUSTOM_LINE_ID = ", $customs_line);
		$this->db->where("A.CUSTOM_KIND_ID = ", $customs_kind);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("A.QUOTATION_NUMBER = ", $quotation_number);
		$this->db->where("A.CONTAINER_SIZE_ID = '40'");

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 4h
	public function get_container_custom_cost_detail_4h($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_SERVICE_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "left");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("A.CUSTOM_LINE_ID = ", $customs_line);
		$this->db->where("A.CUSTOM_KIND_ID = ", $customs_kind);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("A.QUOTATION_NUMBER = ", $quotation_number);
		$this->db->where("A.CONTAINER_SIZE_ID = '4h'");

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 45
	public function get_container_custom_cost_detail_45($quotation_number, $customs_from, $customs_line, $customs_kind, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_SERVICE_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "left");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("A.CUSTOM_LINE_ID = ", $customs_line);
		$this->db->where("A.CUSTOM_KIND_ID = ", $customs_kind);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("A.QUOTATION_NUMBER = ", $quotation_number);
		$this->db->where("A.CONTAINER_SIZE_ID = '45'");

		return $this->db->get();
	}

	// get details of custom cost
	public function get_detail_of_custom_cost($customs_from, $customs_line, $customs_kind, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("A.COMPANY_SERVICE_ID, B.COMPANY_NAME, A.SELLING_SERVICE_ID, C.SERVICE_NAME, A.CUSTOM_LINE_ID, D.GENERAL_DESCRIPTION AS CUSTOM_LINE, A.CUSTOM_LOCATION_ID, E.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, A.CONTAINER_CATEGORY_ID, F.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, A.CONTAINER_TYPE_ID, G.GENERAL_DESCRIPTION AS CONTAINER_TYPE");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOMPANY B", "A.COMPANY_ID = B.COMPANY_ID", "left");
		$this->db->join("dbo.MSELLING_SERVICE C", "A.SELLING_SERVICE_ID = C.SELLING_SERVICE_ID", "left");
		$this->db->join("dbo.MGENERAL_ID D", "A.CUSTOM_LINE_ID = D.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID E", "A.CUSTOM_LOCATION_ID = E.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID F", "A.CONTAINER_CATEGORY_ID = F.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID G", "A.CONTAINER_TYPE_ID = G.GENERAL_ID", "left");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("A.CUSTOM_LINE_ID = ", $customs_line);
		$this->db->where("A.CUSTOM_KIND_ID = ", $customs_kind);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("A.CONTAINER_SIZE_ID", $size);

		return $this->db->get()->row();
	}

	function get_floor_market_customs($customs_from, $customs_line, $customs_kind, $type, $size, $category)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE");
		$this->db->where("CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("CUSTOM_LINE_ID = ", $customs_line);
		$this->db->where("CUSTOM_KIND_ID = ", $customs_kind);
		$this->db->where("CONTAINER_TYPE_ID = ", $type);
		$this->db->where("CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("CONTAINER_SIZE_ID", $size);

		return $this->db->get();
	}

	function get_detail_location($from_location, $to_location, $truck_id)
	{
		$this->db->distinct();
		$this->db->select("COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.TARIFF_AMOUNT, MAIN.DISTANCE, MAIN.DISTANCE_PER_LITRE, TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.TRUCK_ID");
		$this->db->from("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MGENERAL_ID TRUCK", "MAIN.TRUCK_ID = TRUCK.GENERAL_ID", "left");
		$this->db->join("dbo.MCOMPANY_SERVICES A", "MAIN.COMPANY_SERVICE_ID = A.COMPANY_SERVICE_ID", "left");
		$this->db->join("dbo.MCOMPANY COMPANY", "A.COMPANY_ID = COMPANY.COMPANY_ID", "left");
		$this->db->join("dbo.MSELLING_SERVICE SERVICE", "MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID", "left");
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get();
	}

	// GET LOCATION COST DETAIL
	public function get_location_cost_detail($quotation_number, $from_location, $to_location, $truck_id)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, convert(varchar(10), MAIN.START_DATE, 126) AS START_DATE, convert(varchar(10), MAIN.END_DATE, 126) AS END_DATE, MAIN.TRUCK_ID, MAIN.INCREMENT_QTY");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function get_floor_market_location($from_location, $to_location, $truck_id)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE");
		$this->db->from("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE");
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get();
	}

	function get_detail_weight2($from_location, $to_location)
	{
		$this->db->distinct();
		$this->db->select("COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT");
		$this->db->from("dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MCOMPANY_SERVICES A", "MAIN.COMPANY_SERVICE_ID = A.COMPANY_SERVICE_ID", "left");
		$this->db->join("dbo.MCOMPANY COMPANY", "A.COMPANY_ID = COMPANY.COMPANY_ID", "left");
		$this->db->join("dbo.MSELLING_SERVICE SERVICE", "MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID", "left");
		$this->db->where("FROM_WEIGHT <= 1");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get()->row();
	}

	function get_cost_transaction($transaction_number)
	{
		return $this->db->query("SELECT * FROM HSP..TROPERATIONAL_DETAIL WHERE TRX_OPERATIONAL = '$transaction_number'");
	}

	function update_cost_opr($table, $status_cost, $work_order_number, $container_number, $cost_id, $sequence_id)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("SEQUENCE_ID", $sequence_id);

		if (!$this->db->update($table, $status_cost)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_cost_weight($quotation_number, $from_location, $to_location)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, convert(varchar(10), MAIN.START_DATE, 126) AS START_DATE, convert(varchar(10), MAIN.END_DATE, 126) AS END_DATE, MAIN.INCREMENT_QTY, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT");
		$this->db->from("dbo.TRQUOTATION_COST_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function get_floor_market_weight($from_location, $to_location)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE");
		$this->db->from("dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get();
	}

	function get_data_wo($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(11), MAIN.WORK_ORDER_DATE, 106) AS WORK_ORDER_DATE, VESSEL.VESSEL_NAME, TRADE.GENERAL_DESCRIPTION AS TRADE_NAME, MAIN.VOYAGE_NUMBER, CUSTOMER.NAME AS CUSTOMER_NAME FROM HSP..TRWORKORDER MAIN LEFT JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..MGENERAL_ID TRADE ON MAIN.TRADE_ID = TRADE.GENERAL_ID LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_data_additional($transaction_number)
	{
		return $this->db->query("SELECT *, COST.COST_NAME FROM HSP..TRCASH_REQUEST_ADDITIONAL MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.ADDITIONAL_NUMBER = '$transaction_number'");
	}

	function update_additional($transaction_number, $table, $update_additional)
	{
		$this->db->where("ADDITIONAL_NUMBER", $transaction_number);
		$this->db->update($table, $update_additional);
	}

	function update_additional_selling($transaction_number, $table, $update_additional)
	{
		$this->db->where("ADDITIONAL_SELLING", $transaction_number);
		if (!$this->db->update($table, $update_additional)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_detail_mutation($trx_operational)
	{
		return $this->db->query("SELECT * FROM HSP..TROPERATIONAL_DETAIL MAIN LEFT JOIN HSP..TRBANK_STATEMENT MUT ON MAIN.TRANSACTION_ID = MUT.TRANSACTION_ID WHERE MAIN.TRX_OPERATIONAL = '$trx_operational'");
	}
}