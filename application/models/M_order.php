<?php

class M_Order extends CI_Model {

	private $db2;
	private $db3;
	private $db4;
	private $db5;

	public function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('crm', TRUE);
		$this->db3 = $this->load->database('pltapol', TRUE);
		$this->db4 = $this->load->database('ehsj', true);
		$this->db5 = $this->load->database('ehss', TRUE);
		// $dsn = 'mssql://userhsp:"hsp432@"@192.168.11.29/pltapol';
		// $this->db3 = $this->load->database($dsn);
	}

	// function get_data_customer()
	// {
	// 	return $this->db->query("SELECT MAIN.AGREEMENT_NUMBER, MAIN.AGREEMENT_DOCUMENT_NUMBER, MAIN.QUOTATION_NUMBER, CONVERT(CHAR(10), MAIN.AGREEMENT_PERIODE_START, 126) AS PERIODE_START, CONVERT(CHAR(10), MAIN.AGREEMENT_PERIODE_END, 126) AS PERIODE_END, QUOTATION.CUSTOMER_ID, CUSTOMER.NAME FROM HSP..TRAGREEMENT MAIN LEFT JOIN HSP..TRQUOTATION QUOTATION ON MAIN.QUOTATION_NUMBER = QUOTATION.QUOTATION_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON QUOTATION.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.APPROVAL_STATUS = 'A' AND GETDATE() BETWEEN MAIN.AGREEMENT_PERIODE_START AND MAIN.AGREEMENT_PERIODE_END");
	// }

	function get_data_customer()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.AGREEMENT_NUMBER, MAIN.AGREEMENT_DOCUMENT_NUMBER, MAIN.QUOTATION_NUMBER, CONVERT(CHAR(10), MAIN.AGREEMENT_PERIODE_START, 126) AS PERIODE_START, CONVERT(CHAR(10), MAIN.AGREEMENT_PERIODE_END, 126) AS PERIODE_END, QUOTATION.CUSTOMER_ID, CUSTOMER.NAME FROM HSP..TRAGREEMENT MAIN LEFT JOIN HSP..TRQUOTATION QUOTATION ON MAIN.QUOTATION_NUMBER = QUOTATION.QUOTATION_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON QUOTATION.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.APPROVAL_STATUS = 'A'");
	}

	function update_charged_wo_inv($work_order_number, $table, $data_inv_charged)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_inv_charged)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_data_invoice()
	{
		return $this->db->query("SELECT MAIN.INVOICE_NUMBER, MAIN.TOTAL_INVOICE, MAIN.TOTAL_NON_REIMBURSEMENT, MAIN.TOTAL_REIMBURSEMENT, CONVERT(CHAR(12), MAIN.INVOICE_DATE, 107) AS INVOICE_DATE, COMPANY.COMPANY_NAME FROM HSP..TRINVOICE MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID");
	}

	function get_general_service()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID like 'SERVICE_ORDER'");
	}

	function get_data_invoice_for_det($invoice_number)
	{
		return $this->db->query("SELECT MAIN.INVOICE_NUMBER, MAIN.TOTAL_INVOICE, MAIN.TOTAL_NON_REIMBURSEMENT, MAIN.TOTAL_REIMBURSEMENT, CONVERT(CHAR(12), MAIN.INVOICE_DATE, 107) AS INVOICE_DATE, COMPANY.COMPANY_NAME FROM HSP..TRINVOICE MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID WHERE MAIN.INVOICE_NUMBER = '$invoice_number'");
	}

	function get_detail_truck($work_order_number, $container_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_TRUCKING WHERE WORK_ORDER_NUMBER = '$work_order_number' AND CONTAINER_NUMBER = '$container_number'");
	}

	function get_detail_trucking($work_order_number)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.DELIVERY_ORDER_NUMBER, MAIN.TRUCK_ID_NUMBER, MAIN.CHASIS_ID_NUMBER, MAIN.FINAL_LOCATION_DETAIL, MAIN.REMARKS, CONVERT(CHAR(10), MAIN.DOCUMENT_DATE, 126) AS DOCUMENT_DATE, CONVERT(CHAR(10), MAIN.ESTIMATION_ARRIVED, 126) AS ESTIMATION_ARRIVED, TRUCK_OWNER.TRUCK_OWNER_NAME, DRIVER.DRIVER_NAME FROM HSP..TRWORKORDER_TRUCKING MAIN LEFT JOIN HSP..MTRUCK_OWNER TRUCK_OWNER ON MAIN.TRUCK_OWNER_ID = TRUCK_OWNER.TRUCK_OWNER_ID LEFT JOIN HSP..MDRIVER DRIVER ON MAIN.DRIVER_ID = DRIVER.DRIVER_ID WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_detail_chassis($work_order_number, $container_number)
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

	function update_opr_vou($table, $data_update_opr, $operational_number)
	{
		$this->db->where("TRX_OPERATIONAL", $operational_number);
		$this->db->update($table, $data_update_opr);
	}

	function get_data_invoice_detail($invoice_number)
	{
		return $this->db->query("SELECT MAIN.INVOICE_NUMBER, COMPANY.COMPANY_NAME, MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.CHARGES_CURRENCY, MAIN.CHARGES_AMOUNT, CH_TYPE.GENERAL_DESCRIPTION AS CHARGE_TYPE, CH_GROUP.GENERAL_DESCRIPTION AS CHARGE_GROUP, MAIN.CHARGES_ID, MAIN.CHARGES_TYPE, MAIN.CHARGES_GROUP, MAIN.COMPANY_ID FROM HSP..TRINVOICE_DETAIL MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MGENERAL_ID CH_TYPE ON MAIN.CHARGES_TYPE = CH_TYPE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CH_GROUP ON MAIN.CHARGES_GROUP = CH_GROUP.GENERAL_ID WHERE MAIN.INVOICE_NUMBER = '$invoice_number' AND CH_TYPE.CLASSIFICATION_ID = 'CHARGES_TYPE' AND CH_GROUP.CLASSIFICATION_ID = 'CHARGES_GROUP'");
	}

	function get_cost_inv($cost_id)
	{
		return $this->db->query("SELECT TOP 1 * FROM HSP..MCOST WHERE COST_ID = '$cost_id'");
	}

	function get_sell_inv($selling_id)
	{
		return $this->db->query("SELECT TOP 1 * FROM HSP..MSELLING_SERVICE WHERE SELLING_SERVICE_ID = '$selling_id'");
	}

	function get_wo_inv($invoice_number)
	{
		return $this->db->query("SELECT WORK_ORDER_NUMBER FROM HSP..TRINVOICE_DETAIL WHERE INVOICE_NUMBER = '$invoice_number' GROUP BY WORK_ORDER_NUMBER");
	}

	function get_wo_service($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE MAIN LEFT JOIN HSP..MGENERAL_ID SERVICE ON MAIN.SERVICE_ID = SERVICE.GENERAL_ID WHERE SERVICE.CLASSIFICATION_ID = 'SERVICE_ORDER' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number' ORDER BY MAIN.WORK_ORDER_NUMBER DESC");
	}

	function get_data_reim_opr($trx_operational)
	{
		return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.TRX_OPERATIONAL, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.SEQUENCE_ID, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.COST_ACTUAL_AMOUNT, MAIN.TRANSACTION_ID, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, COST.COST_NAME, BANK.DESCRIPTION_1 AS MUTATION_DESCRIPTION, MAIN.COST_AMOUNT, WO_SERVICE.CONTAINER_SIZE_ID, WO_SERVICE.CONTAINER_TYPE_ID, WO_SERVICE.CONTAINER_CATEGORY_ID FROM HSP..TROPERATIONAL_DETAIL MAIN LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..TRBANK_STATEMENT BANK ON MAIN.TRANSACTION_ID = BANK.TRANSACTION_ID LEFT JOIN HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WO_SERVICE ON MAIN.CONTAINER_NUMBER = WO_SERVICE.CONTAINER_NUMBER WHERE MAIN.TRX_OPERATIONAL = '$trx_operational' AND MAIN.COST_TYPE_ID = 'REM'");
	}

	function get_data_nonreim_opr($trx_operational)
	{
		return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.TRX_OPERATIONAL, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.SEQUENCE_ID, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.COST_ACTUAL_AMOUNT, MAIN.TRANSACTION_ID, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, COST.COST_NAME, BANK.DESCRIPTION_1 AS MUTATION_DESCRIPTION, MAIN.COST_AMOUNT, WO_SERVICE.CONTAINER_SIZE_ID, WO_SERVICE.CONTAINER_TYPE_ID, WO_SERVICE.CONTAINER_CATEGORY_ID FROM HSP..TROPERATIONAL_DETAIL MAIN LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..TRBANK_STATEMENT BANK ON MAIN.TRANSACTION_ID = BANK.TRANSACTION_ID LEFT JOIN HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WO_SERVICE ON MAIN.CONTAINER_NUMBER = WO_SERVICE.CONTAINER_NUMBER WHERE MAIN.TRX_OPERATIONAL = '$trx_operational' AND MAIN.COST_TYPE_ID = 'NRM'");
	}

	function get_total_reim($trx_operational)
	{
		return $this->db->query("SELECT SUM(COST_ACTUAL_AMOUNT) as total FROM HSP..TROPERATIONAL_DETAIL WHERE TRX_OPERATIONAL = '$trx_operational' AND COST_TYPE_ID = 'REM'");
	}

	function get_total_nonreim($trx_operational)
	{
		return $this->db->query("SELECT SUM(COST_ACTUAL_AMOUNT) as total FROM HSP..TROPERATIONAL_DETAIL WHERE TRX_OPERATIONAL = '$trx_operational' AND COST_TYPE_ID = 'NRM'");
	}

	function get_data_level($transaction_number, $document_id)
	{
		$this->db->where("TRANSACTION_NUMBER", $transaction_number);
		$this->db->where("DOCUMENT_ID", $document_id);

		return $this->db->get("dbo.TRAPPROVAL_STATUS_TRANSACTION");
	}

	function get_user_nik($nik)
	{
		// $this->db3->distinct();
		// $this->db3->select("Nm_lengkap");
		// $this->db3->from("dbo.u_nik");
		// $this->db3->where("Nik", $nik);

		// return $this->db3->get();
		return $this->db3->query("SELECT DISTINCT Nm_lengkap from u_nik where Nik = '$nik'");
	}

	function get_data_operational($trx_operational)
	{
		return $this->db->query("SELECT *, CONVERT(CHAR(11),USER_DATE,106) as OPR_DATE, USER_DATE as OPR_DATE2 FROM HSP..TROPERATIONAL_HEADER WHERE TRX_OPERATIONAL = '$trx_operational'");	
	}

	function check_cost_share($cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST WHERE COST_ID = '$cost_id'");
	}

	function get_data_operational_detail($trx_operational)
	{
		return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.TRX_OPERATIONAL, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.SEQUENCE_ID, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.COST_ACTUAL_AMOUNT, MAIN.TRANSACTION_ID, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, COST.COST_NAME, BANK.DESCRIPTION_1 AS MUTATION_DESCRIPTION, MAIN.COST_AMOUNT, WO_SERVICE.CONTAINER_SIZE_ID, WO_SERVICE.CONTAINER_TYPE_ID, WO_SERVICE.CONTAINER_CATEGORY_ID, BANK.HOME_DEBIT FROM HSP..TROPERATIONAL_DETAIL MAIN LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..TRBANK_STATEMENT BANK ON MAIN.TRANSACTION_ID = BANK.TRANSACTION_ID LEFT JOIN HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WO_SERVICE ON MAIN.CONTAINER_NUMBER = WO_SERVICE.CONTAINER_NUMBER WHERE MAIN.TRX_OPERATIONAL = '$trx_operational'");
	}

	function get_data_description_vou($detail)
	{
		return $this->db->query("SELECT * FROM HSP..MDESCRIPTION_VOUCHER WHERE DETAIL = '$detail'");
	}

	function update_mut_flag($table, $data_mut_flag, $mutation_account)
	{
		$this->db->where("TRANSACTION_ID", $mutation_account);

		if (!$this->db->update($table, $data_mut_flag)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function edit_opera($table, $update_opera, $trx_operational, $work_order_number, $container_number, $cost_id, $sequence_id)
	{
		$this->db->where("TRX_OPERATIONAL", $trx_operational);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("SEQUENCE_ID", $sequence_id);

		if (!$this->db->update($table, $update_opera)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// function get_data_customer()
	// {
	// 	return $this->db->query("SELECT MAIN.AGREEMENT_NUMBER, MAIN.QUOTATION_NUMBER, QUOTATION.CUSTOMER_ID, CUSTOMER.NAME FROM HSP..TRAGREEMENT MAIN LEFT JOIN HSP..TRQUOTATION QUOTATION ON MAIN.QUOTATION_NUMBER = QUOTATION.QUOTATION_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON QUOTATION.CUSTOMER_ID = CUSTOMER.COMPANY_ID");
	// }

	function get_total_agreement($company_id)
	{
		return $this->db->query("select MAIN.AGREEMENT_NUMBER, MAIN.QUOTATION_NUMBER, QUOTATION.CUSTOMER_ID from HSP..TRAGREEMENT MAIN inner join HSP..TRQUOTATION QUOTATION on MAIN.QUOTATION_NUMBER = QUOTATION.QUOTATION_NUMBER where QUOTATION.CUSTOMER_ID = '$company_id'");
	}

	function get_quotation($customer_id)
	{
		return $this->db->query("SELECT TOP 1 * FROM HSP..TRQUOTATION WHERE CUSTOMER_ID = '$customer_id'");
	}

	function get_selling_customs_wo($quotation_no, $customs_location, $customs_lane, $container_size_id, $container_type_id, $container_category_id)
	{
		return $this->db->query("select * from HSP..TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_no' and CUSTOM_LOCATION_ID = '$customs_location' AND CUSTOM_LINE_ID = '$customs_lane' AND CONTAINER_SIZE_ID = '$container_size_id' AND CONTAINER_TYPE_ID = '$container_type_id' AND CONTAINER_CATEGORY_ID = '$container_category_id'");
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

	function get_max_id()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(WORK_ORDER_NUMBER), 3, 4) as id, MAX(WORK_ORDER_NUMBER) as year_temp FROM HSP..TRWORKORDER");
	}

	function get_company($company_id)
	{
		$this->db2->db_select();
		return $this->db2->query("select COMPANY_ID, NAME FROM CRM..MCOMPANY WHERE COMPANY_ID = '$company_id'");
	}

	function get_data_selling($quotation_number)
	{
		return $this->db->query("SELECT AGREEMENT.AGREEMENT_NUMBER, MAIN.QUOTATION_NUMBER, MAIN.SELLING_SERVICE_ID, FROM_LOC.LOCATION_NAME AS FROM_NAME, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME_SHORT, TO_LOC.LOCATION_NAME AS TO_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME_SHORT, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.SELLING_CURRENCY, MAIN.SELLING_OFFERING_RATE from HSP..TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE MAIN INNER JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID INNER JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID INNER JOIN HSP..TRAGREEMENT AGREEMENT ON MAIN.QUOTATION_NUMBER = AGREEMENT.QUOTATION_NUMBER where MAIN.QUOTATION_NUMBER = '$quotation_number' and MAIN.FROM_QTY = 1");
	}

	function get_data_selling2($quotation_number, $from_location, $to_location, $container_size, $container_type, $container_category)
	{
		return $this->db->query("SELECT * FROM HSP..TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE WHERE QUOTATION_NUMBER = '$quotation_number' AND FROM_LOCATION_ID = '$from_location' AND TO_LOCATION_ID = '$to_location' AND CONTAINER_SIZE_ID = '$container_size' AND CONTAINER_TYPE_ID = '$container_type' AND CONTAINER_CATEGORY_ID = '$container_category' AND FROM_QTY = 1");
	}

	function get_cost_do($container_size, $container_type, $container_category, $from_location, $to_location, $cost_name)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST_SERVICE_CONTAINER_SHIPPING_ATTRIBUTE MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.CONTAINER_SIZE_ID = '$container_size' AND MAIN.CONTAINER_TYPE_ID = '$container_type' AND MAIN.CONTAINER_CATEGORY_ID = '$container_category' AND MAIN.FROM_LOCATION_ID = '$from_location' AND MAIN.TO_LOCATION_ID = '$to_location' AND COST.COST_NAME LIKE '%$cost_name%'");
	}

	function get_data_cost_do($container_size, $container_type, $container_category)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST_SERVICE_CONTAINER_SHIPPING_ATTRIBUTE MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.CONTAINER_SIZE_ID = '$container_size' AND MAIN.CONTAINER_TYPE_ID = '$container_type' AND MAIN.CONTAINER_CATEGORY_ID = '$container_category'");
	}

	function update_container_amount($table, $data_update_container, $work_order_number, $container_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);

		if (!$this->db->update($table, $data_update_container)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_detail_container2($container_number, $work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WHERE CONTAINER_NUMBER = '$container_number' AND WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_cash_do($work_order_number, $company_id)
	{
		return $this->db->query("SELECT *, COST.COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID WHERE COST_GROUP_ID = 'DOR' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.IS_TRANSFERED = 'N' AND MAIN.COMPANY_ID = '$company_id'");
	}

	function get_cash_do2($work_order_number, $company_id)
	{
		return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.COST_TYPE_ID, COST_GROUP_ID, MAIN.COMPANY_ID, MAIN.COST_KIND, COST.COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COST_CURRENCY, MAIN.COST_REQUEST_AMOUNT FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID WHERE COST_GROUP_ID = 'DOR' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.IS_TRANSFERED = 'N' AND MAIN.COMPANY_ID = '$company_id'");
	}

	function get_cash($work_order_number, $cost_type)
	{
		return $this->db->query("SELECT * FROM HSP..TRCASH_REQUEST WHERE WORK_ORDER_NUMBER = '$work_order_number' AND COST_TYPE_ID = '$cost_type'");
	}

	function get_max_additional_selling()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(ADDITIONAL_SELLING), 7, 4) as id FROM TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE");
	}

	function get_data_additional_selling($work_order_number, $company_id)
	{
		return $this->db->query("SELECT MAIN.ADDITIONAL_SELLING, MAIN.CONTAINER_NUMBER, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.TARIFF_CURRENCY, MAIN.TARIFF_AMOUNT, SERVICE_NM.SERVICE_NAME, MAIN.STATUS FROM HSP..TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MSELLING_SERVICE SERVICE_NM ON MAIN.SELLING_SERVICE_ID = SERVICE_NM.SELLING_SERVICE_ID WHERE WORK_ORDER_NUMBER = '$work_order_number' AND COMPANY_ID = '$company_id'");
	}

	function get_selling_additional($company_id)
	{
		return $this->db->query("SELECT MAIN.SELLING_SERVICE_ID, MAIN.TARIFF_CURRENCY, MAIN.TARIFF_AMOUNT, MAIN.TARIFF_AMOUNT, SERVICE_NM.SERVICE_NAME FROM HSP..MSELLING_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MSELLING_SERVICE SERVICE_NM ON MAIN.SELLING_SERVICE_ID = SERVICE_NM.SELLING_SERVICE_ID WHERE COMPANY_ID = '$company_id'");
	}

	function update_cash_do($table, $data_cash_do, $work_order_number, $container_number, $cost_id, $company_id, $cost_type_id, $cost_group_id, $cost_kind)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("COST_TYPE_ID", $cost_type_id);
		$this->db->where("COST_GROUP_ID", $cost_group_id);
		$this->db->where("COST_KIND", $cost_kind);

		if (!$this->db->update($table, $data_cash_do)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_total_cost($quotation_number, $container_size, $container_type, $container_category)
	{
		return $this->db->query("select SUM(MAIN.COST_AMOUNT) as TOTAL_COST from HSP..TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE MAIN WHERE QUOTATION_NUMBER = '$quotation_number' AND CONTAINER_SIZE_ID = '$container_size' AND CONTAINER_TYPE_ID = '$container_type' AND CONTAINER_CATEGORY_ID = '$container_category'");
	}

	function get_cost($container_size_id, $container_type_id, $container_category_id, $from_location_id, $to_location_id)
	{
		return $this->db->query("SELECT DISTINCT * FROM HSP..MCOST_SERVICE_CONTAINER_ATTRIBUTE WHERE CONTAINER_SIZE_ID = '$container_size_id' AND CONTAINER_TYPE_ID = '$container_type_id' AND CONTAINER_CATEGORY_ID = '$container_category_id' AND FROM_LOCATION_ID = '$from_location_id' AND TO_LOCATION_ID = '$to_location_id' AND (GETDATE() BETWEEN START_DATE AND END_DATE) AND FROM_QTY = '1'");
	}

	function check_wo_number($work_order_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		return $this->db->get("dbo.TRWORKORDER");
	}

	function get_quotation_number($agreement_no)
	{
		return $this->db->query("SELECT * FROM HSP..TRAGREEMENT WHERE AGREEMENT_NUMBER = '$agreement_no'");
	}

	function get_agreement_number($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_just_wo()
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER");
	}

	function get_customer_detail($customer_id)
	{
		$this->db2->db_select();
		return $this->db2->query("SELECT * FROM CRM..MCOMPANY WHERE COMPANY_ID = '$customer_id'");
	}

	// function get_data_wo()
	// {
	// 	return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.AGREEMENT_NUMBER, CONVERT(CHAR(11), MAIN.WORK_ORDER_DATE, 106) AS WORK_ORDER_DATE, TRADE.GENERAL_DESCRIPTION AS TRADE, VESSEL.VESSEL_NAME, CUSTOMS.REGISTER_NUMBER_PIB_PEB, CUSTOMS.REGISTER_NUMBER_SPPB_SPEB, CUSTOMER.NAME AS CUSTOMER_NAME, CONVERT(CHAR(10), CASH3.DATE_RECEIVED, 126) AS DATE_RECEIVED, CASH3.NAME_RECEIVED, CASH3.NAME_REQUEST, CONVERT(CHAR(10), CASH3.DATE_REQUEST, 126) AS DATE_REQUEST FROM HSP..TRWORKORDER MAIN INNER JOIN HSP..MGENERAL_ID TRADE ON MAIN.TRADE_ID = TRADE.GENERAL_ID INNER JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..TRWORKORDER_CUSTOMS CUSTOMS ON MAIN.WORK_ORDER_NUMBER = CUSTOMS.WORK_ORDER_NUMBER INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN (SELECT DISTINCT CASH.WORK_ORDER_NUMBER, MIN(CONVERT(CHAR(10), CASH.REQUEST_DATE, 126)) AS DATE_REQUEST,MIN( NIK2.Nm_lengkap) AS NAME_REQUEST, MIN(NIK.Nm_lengkap) AS NAME_RECEIVED, MIN(CONVERT(CHAR(10), CASH.TRANSFER_DATE_ACTUAL, 126)) AS DATE_RECEIVED FROM HSP..TRCASH_REQUEST CASH INNER JOIN pltapol..u_nik NIK ON CASH.USER_ID_RECEIVED = NIK.Nik INNER JOIN pltapol..u_nik NIK2 ON CASH.USER_ID_REQUEST = NIK2.Nik GROUP BY CASH.WORK_ORDER_NUMBER) AS CASH3 ON MAIN.WORK_ORDER_NUMBER = CASH3.WORK_ORDER_NUMBER");
	// }

	function get_data_wo()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT DISTINCT MAIN.TRADE_ID, MAIN.WORK_ORDER_NUMBER, MAIN.AGREEMENT_NUMBER, CONVERT(CHAR(10), MAIN.WORK_ORDER_DATE, 126) AS WORK_ORDER_DATE, TRADE.GENERAL_DESCRIPTION AS TRADE, VESSEL.VESSEL_NAME, CUSTOMS.REGISTER_NUMBER_PIB_PEB, CUSTOMS.REGISTER_NUMBER_SPPB_SPEB, CUSTOMER.NAME AS CUSTOMER_NAME, CONVERT(CHAR(10), CASH3.DATE_RECEIVED, 126) AS DATE_RECEIVED, CASH3.NAME_RECEIVED, CASH3.NAME_REQUEST, CONVERT(CHAR(10), CASH3.DATE_REQUEST, 126) AS DATE_REQUEST, INVOICE.INVOICE_NUMBER, MAIN.IS_CHARGED FROM HSP..TRWORKORDER MAIN LEFT JOIN HSP..MGENERAL_ID TRADE ON MAIN.TRADE_ID = TRADE.GENERAL_ID LEFT JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..TRWORKORDER_CUSTOMS CUSTOMS ON MAIN.WORK_ORDER_NUMBER = CUSTOMS.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN (SELECT DISTINCT CASH.WORK_ORDER_NUMBER, MIN(CONVERT(CHAR(10), CASH.REQUEST_DATE, 126)) AS DATE_REQUEST, MIN( NIK2.Nm_lengkap) AS NAME_REQUEST, MIN(NIK.Nm_lengkap) AS NAME_RECEIVED, MIN(CONVERT(CHAR(10), CASH.TRANSFER_DATE_ACTUAL, 126)) AS DATE_RECEIVED FROM HSP..TRCASH_REQUEST CASH LEFT JOIN pltapol..u_nik NIK ON CASH.USER_ID_RECEIVED = NIK.Nik LEFT JOIN pltapol..u_nik NIK2 ON CASH.USER_ID_REQUEST = NIK2.Nik GROUP BY CASH.WORK_ORDER_NUMBER) AS CASH3 ON MAIN.WORK_ORDER_NUMBER = CASH3.WORK_ORDER_NUMBER LEFT JOIN HSP..TRINVOICE_DETAIL INVOICE ON MAIN.WORK_ORDER_NUMBER = INVOICE.WORK_ORDER_NUMBER WHERE IS_CHARGED = 'N' ORDER BY MAIN.WORK_ORDER_NUMBER DESC");
	}

	function get_wo_done()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, CUSTOMER.NAME AS CUSTOMER_NAME, COMPANY.COMPANY_NAME, INVOICE.INVOICE_NUMBER FROM HSP..TRWORKORDER MAIN LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] AS CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..TRINVOICE_DETAIL INVOICE ON MAIN.WORK_ORDER_NUMBER = INVOICE.WORK_ORDER_NUMBER WHERE MAIN.IS_CHARGED = 'Y'");
	}

	function get_data_wo_inv()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.AGREEMENT_NUMBER, CONVERT(CHAR(11), MAIN.WORK_ORDER_DATE, 106) AS WORK_ORDER_DATE, TRADE.GENERAL_DESCRIPTION AS TRADE, VESSEL.VESSEL_NAME, CUSTOMS.REGISTER_NUMBER_PIB_PEB, CUSTOMS.REGISTER_NUMBER_SPPB_SPEB, CUSTOMER.NAME AS CUSTOMER_NAME, CONVERT(CHAR(10), CASH3.DATE_RECEIVED, 126) AS DATE_RECEIVED, CASH3.NAME_RECEIVED, CASH3.NAME_REQUEST, CONVERT(CHAR(10), CASH3.DATE_REQUEST, 126) AS DATE_REQUEST FROM HSP..TRWORKORDER MAIN INNER JOIN HSP..MGENERAL_ID TRADE ON MAIN.TRADE_ID = TRADE.GENERAL_ID INNER JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..TRWORKORDER_CUSTOMS CUSTOMS ON MAIN.WORK_ORDER_NUMBER = CUSTOMS.WORK_ORDER_NUMBER INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN (SELECT DISTINCT CASH.WORK_ORDER_NUMBER, MIN(CONVERT(CHAR(10), CASH.REQUEST_DATE, 126)) AS DATE_REQUEST,MIN( NIK2.Nm_lengkap) AS NAME_REQUEST, MIN(NIK.Nm_lengkap) AS NAME_RECEIVED, MIN(CONVERT(CHAR(10), CASH.TRANSFER_DATE_ACTUAL, 126)) AS DATE_RECEIVED FROM HSP..TRCASH_REQUEST CASH INNER JOIN pltapol..u_nik NIK ON CASH.USER_ID_RECEIVED = NIK.Nik INNER JOIN pltapol..u_nik NIK2 ON CASH.USER_ID_REQUEST = NIK2.Nik GROUP BY CASH.WORK_ORDER_NUMBER) AS CASH3 ON MAIN.WORK_ORDER_NUMBER = CASH3.WORK_ORDER_NUMBER WHERE IS_CHARGED = 'N' ORDER BY MAIN.WORK_ORDER_NUMBER DESC");
	}

	function get_data_wo2($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(11), MAIN.WORK_ORDER_DATE, 106) AS WORK_ORDER_DATE, VESSEL.VESSEL_NAME, TRADE.GENERAL_DESCRIPTION AS TRADE_NAME, MAIN.TRADE_ID, MAIN.VOYAGE_NUMBER, CUSTOMER.NAME AS CUSTOMER_NAME FROM HSP..TRWORKORDER MAIN LEFT JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..MGENERAL_ID TRADE ON MAIN.TRADE_ID = TRADE.GENERAL_ID LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_just_wo_data($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_custom_wo($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_CUSTOMS WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_container_wo($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_vessel_detail($vessel)
	{
		return $this->db->query("SELECT * FROM HSP..MVESSEL WHERE VESSEL_ID = '$vessel'");
	}

	function get_address($company_id)
	{
		$this->db2->db_select();
		return $this->db2->query("SELECT * FROM CRM..MCOMPANY_ADDRESS where COMPANY_ID = '$company_id'");
	}

	function get_data_wo_eta($vessel_id)
	{
		return $this->db->query("SELECT TOP 1 *, CONVERT(CHAR(11), MAIN.ETA, 106) AS ETA_DATE, CONVERT(CHAR(11), MAIN.ETD, 106) AS ETD_DATE, POLL.PORT_NAME AS POL_NAME, PODD.PORT_NAME AS POD_NAME FROM HSP..TRWORKORDER MAIN LEFT JOIN HSP..MPORT POLL ON MAIN.POL_ID = POLL.PORT_ID LEFT JOIN HSP..MPORT PODD ON MAIN.POD_ID = PODD.PORT_ID WHERE VESSEL_ID = '$vessel_id'");
	}

	function get_wo($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.SHIPPER, MAIN.CONSIGNEE, MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(10), MAIN.WORK_ORDER_DATE, 126) AS WORK_ORDER_DATE, MAIN.VESSEL_ID, MAIN.POL_ID, MAIN.POD_ID, MAIN.CUSTOMER_ID, MAIN.REFERENCE_NUMBER, MAIN.TRADE_ID, VESSEL.VESSEL_NAME, PORT1.PORT_NAME AS POL_NAME, PORT2.PORT_NAME AS POD_NAME, CUSTOMER.NAME AS CUSTOMER_NAME, MAIN.VOYAGE_NUMBER, CONVERT(CHAR(10), MAIN.ETA, 126) AS ETA, CONVERT(CHAR(10), MAIN.ETD, 126) AS ETD FROM HSP..TRWORKORDER MAIN INNER JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID INNER JOIN HSP..MPORT PORT1 ON MAIN.POL_ID = PORT1.PORT_ID INNER JOIN HSP..MPORT PORT2 ON MAIN.POD_ID = PORT2.PORT_ID INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_wo_opr()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(10), MAIN.WORK_ORDER_DATE, 126) AS WORK_ORDER_DATE, MAIN.CUSTOMER_ID, COMPANY.NAME AS COMPANY_NAME FROM HSP..TRWORKORDER MAIN LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] AS COMPANY ON MAIN.CUSTOMER_ID = COMPANY.COMPANY_ID ORDER BY MAIN.WORK_ORDER_NUMBER DESC");
	}

	// function get_data_rem_inv($invoice_number)
	// {
	// 	return $this->db->query("SELECT MAIN.INVOICE_NUMBER, MAIN.COMPANY_ID, MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.CHARGES_CURRENCY, MAIN.CHARGES_AMOUNT, COST.COST_NAME FROM HSP..TRINVOICE_DETAIL MAIN LEFT JOIN HSP..MCOST COST ON MAIN.CHARGES_ID = COST.COST_ID WHERE INVOICE_NUMBER = '$invoice_number' AND CHARGES_TYPE = 'REM'");
	// }

	function get_data_rem_inv($invoice_number)
	{
		return $this->db->query("SELECT MAIN.CHARGES_ID, MAIN.CHARGES_CURRENCY, SUM(MAIN.CHARGES_AMOUNT) AS AMOUNT, COST.COST_NAME FROM HSP..TRINVOICE_DETAIL MAIN LEFT JOIN HSP..MCOST COST ON MAIN.CHARGES_ID = COST.COST_ID WHERE MAIN.CHARGES_TYPE = 'REM' AND MAIN.INVOICE_NUMBER = '$invoice_number' GROUP BY MAIN.CHARGES_ID, COST.COST_NAME, MAIN.CHARGES_CURRENCY");
	}

	function sum_data_rem_inv($invoice_number)
	{
		return $this->db->query("SELECT SUM(MAIN.CHARGES_AMOUNT) AS AMOUNT FROM HSP..TRINVOICE_DETAIL MAIN WHERE MAIN.CHARGES_TYPE = 'REM' AND MAIN.INVOICE_NUMBER = '$invoice_number'");
	}

	// function get_data_crg_inv($invoice_number)
	// {
	// 	return $this->db->query("SELECT MAIN.INVOICE_NUMBER, MAIN.COMPANY_ID, MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.CHARGES_CURRENCY, MAIN.CHARGES_AMOUNT, SELLING.SERVICE_NAME FROM HSP..TRINVOICE_DETAIL MAIN LEFT JOIN HSP..MSELLING_SERVICE SELLING ON MAIN.CHARGES_ID = SELLING.SELLING_SERVICE_ID WHERE INVOICE_NUMBER = '$invoice_number' AND CHARGES_TYPE = 'CRG'");
	// }

	function get_data_crg_inv($invoice_number)
	{
		return $this->db->query("SELECT MAIN.CHARGES_ID, MAIN.CHARGES_CURRENCY, SUM(MAIN.CHARGES_AMOUNT) AS AMOUNT, SELLING.SERVICE_NAME FROM HSP..TRINVOICE_DETAIL MAIN LEFT JOIN HSP..MSELLING_SERVICE SELLING ON MAIN.CHARGES_ID = SELLING.SELLING_SERVICE_ID WHERE MAIN.CHARGES_TYPE = 'crg' AND MAIN.INVOICE_NUMBER = '$invoice_number' GROUP BY MAIN.CHARGES_ID, SELLING.SERVICE_NAME, MAIN.CHARGES_CURRENCY");
	}

	function sum_data_crg_inv($invoice_number)
	{
		return $this->db->query("SELECT SUM(MAIN.CHARGES_AMOUNT) AS AMOUNT FROM HSP..TRINVOICE_DETAIL MAIN WHERE MAIN.CHARGES_TYPE = 'CRG' AND MAIN.INVOICE_NUMBER = '$invoice_number'");
	}

	// search from location
	public function get_location($location)
	{
		// return $this->db->query("SELECT TOP(5) MAIN.VESSEL_ID, MAIN.VESSEL_NAME, MAIN.VOYAGE_NUMBER, MAIN.TRADE, MAIN.POL_ID, MAIN.POD_ID, PORT1.PORT_NAME AS POL_NAME, PORT2.PORT_NAME AS POD_NAME FROM HSP..MVESSEL_VOYAGE MAIN INNER JOIN HSP..MPORT PORT1 ON MAIN.POL_ID = PORT1.PORT_ID INNER JOIN HSP..MPORT PORT2 ON MAIN.POD_ID = PORT2.PORT_ID WHERE MAIN.VESSEL_NAME LIKE '%$location%'");
		return $this->db->query("SELECT MAIN.VESSEL_ID, VESSEL.VESSEL_NAME, MAIN.VOYAGE_NUMBER, MAIN.TRADE, MAIN.POL_ID, MAIN.POD_ID, PORT1.PORT_NAME AS POL_NAME, PORT2.PORT_NAME AS POD_NAME, CONVERT(CHAR(10), MAIN.ETA_DATE, 126) AS ETA, CONVERT(CHAR(10), MAIN.ETD_DATE, 126) AS ETD FROM HSP..MVESSEL_VOYAGE MAIN LEFT JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..MPORT PORT1 ON MAIN.POL_ID = PORT1.PORT_ID LEFT JOIN HSP..MPORT PORT2 ON MAIN.POD_ID = PORT2.PORT_ID WHERE VESSEL.VESSEL_NAME LIKE '%$location%'");
	}

	function get_vessel2($vessel)
	{
		return $this->db->query("SELECT TOP(5) * FROM HSP..MVESSEL WHERE VESSEL_NAME LIKE '%$vessel%'");
	}

	function get_port($kode)
	{
		return $this->db->query("SELECT TOP(5) * FROM HSP..MPORT WHERE PORT_NAME LIKE '%$kode%'");
	}

	function get_data_trucking_wo($work_order_number)
	{
		return $this->db->query("SELECT *, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN INNER JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID INNER JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function update_wo($work_order_number, $table, $data_work_order)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_work_order)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_wo_trucking($work_order_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->delete("dbo.TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_do_cost($work_order_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("COST_GROUP_ID", "DOR");
		if (!$this->db->delete("dbo.TRCASH_REQUEST")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_wo_cost_trucking($work_order_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->delete("dbo.TRWORKORDER_COST_SERVICE_CONTAINER_ATTRIBUTE")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_wo_truck($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_TRUCKING WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function check_wo_customs($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_CUSTOMS WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_wo_customs($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CUSTOMER_ID, CUSTOMER.NAME AS CUSTOMER_NAME, CONVERT(char(10), MAIN.WORK_ORDER_DATE, 126) AS WORK_ORDER_DATE, MAIN.COMPANY_ID FROM HSP..TRWORKORDER MAIN  INNER JOIN HSP..MVESSEL_VOYAGE VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_measurement()
	{
		return $this->db->query("SELECT GENERAL_ID FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'UNIT_WEIGHT'");
	}

	function get_company_hanoman()
	{
		return $this->db->query("SELECT * FROM MCOMPANY");
	}

	function get_hoarding($kode)
	{
		return $this->db->query("SELECT TOP(5) * FROM HSP..MHOARDING WHERE HOARDING_NAME LIKE '%$kode%'");
	}

	function get_hoarding2()
	{
		return $this->db->query("SELECT * FROM HSP..MHOARDING");
	}

	function update_customs($table, $data_trucking, $container_number, $wo_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $wo_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		if (!$this->db->update($table, $data_trucking)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_customs($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_CUSTOMS WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_all_wo_customs()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CUSTOMER_ID, CUSTOMER.NAME AS CUSTOMER_NAME, CUSTOMS.REGISTER_NUMBER_PIB_PEB FROM HSP..TRWORKORDER MAIN INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID INNER JOIN HSP..TRWORKORDER_CUSTOMS CUSTOMS ON MAIN.WORK_ORDER_NUMBER = CUSTOMS.WORK_ORDER_NUMBER");
	}

	function get_data_customs($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.COMPANY_ID, MAIN.REGISTER_NUMBER_PIB_PEB, CONVERT(CHAR(10), MAIN.REGISTER_DATE_PIB_PEB, 126) AS REGISTER_DATE, MAIN.HOARDING_ID, MAIN.IMPORTIR_ID, MAIN.PPJK_ID, HOARDING.HOARDING_NAME, CUSTOMER.NAME AS CUSTOMER_NAME, MAIN.REGISTER_NUMBER_SPPB_SPEB, CONVERT(CHAR(10), MAIN.REGISTER_DATE_SPPB_SPEB, 126) AS REGISTER_SPPB_DATE FROM HSP..TRWORKORDER_CUSTOMS MAIN INNER JOIN HSP..MHOARDING HOARDING ON MAIN.HOARDING_ID = HOARDING.HOARDING_ID INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.IMPORTIR_ID = CUSTOMER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_data_trucking_wo_customs($work_order_number)
	{
		return $this->db->query("SELECT MAIN.CUSTOMS_LOCATION, MAIN.CUSTOMS_LANE, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.CONTAINER_NUMBER, MAIN.BL_NUMBER, MAIN.FCL_LCL, MAIN.COMMODITY_ID, MAIN.GROSS_WEIGHT, MAIN.GROSS_WEIGHT_MEASUREMENT, MAIN.NET_WEIGHT, MAIN.NET_WEIGHT_MEASUREMENT, COMMODITY.COMMODITY_DESCRIPTION FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN INNER JOIN MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID INNER JOIN MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID LEFT JOIN HSP..MCOMMODITY COMMODITY ON MAIN.COMMODITY_ID = COMMODITY.COMMODITY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_company_crm($kode)
	{
		$this->db2->db_select();
		return $this->db2->query("SELECT TOP(5) * FROM CRM..MCOMPANY WHERE NAME LIKE '%$kode%'");
	}

	function check_container($container_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WHERE CONTAINER_NUMBER = '$container_number'");
	}

	function get_lane()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'CUSTOMS_LINE'");
	}

	function get_customs_location()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'CUSTOMS_LOCATION'");
	}

	function get_cost_customs($customs_location, $customs_lane, $container_size_id, $container_type_id, $container_category_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE WHERE CUSTOM_LOCATION_ID = '$customs_location' AND CUSTOM_LINE_ID = '$customs_lane' AND CONTAINER_SIZE_ID = '$container_size_id' AND CONTAINER_TYPE_ID = '$container_type_id' AND CONTAINER_CATEGORY_ID = '$container_category_id' AND (GETDATE() BETWEEN START_DATE AND END_DATE) AND FROM_QTY = '1'");
	}

	function check_cost_customs($wo_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE WHERE WORK_ORDER_NUMBER = '$wo_number'");
	}

	function delete_cost_customs($wo_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $wo_number);
		if (!$this->db->delete("dbo.TRWORKORDER_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_wo_cash()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CUSTOMER_ID, CUSTOMER.NAME AS CUSTOMER_NAME, CUSTOMS.REGISTER_NUMBER_PIB_PEB FROM HSP..TRWORKORDER MAIN INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID INNER JOIN HSP..TRWORKORDER_CUSTOMS CUSTOMS ON MAIN.WORK_ORDER_NUMBER = CUSTOMS.WORK_ORDER_NUMBER");
	}

	function get_cost_trucking_cash($work_order_number)
	{
		return $this->db->query("SELECT DISTINCT COST.COST_NAME, MAIN.WORK_ORDER_NUMBER, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.COST_AMOUNT, MAIN.COST_CURRENCY FROM HSP..TRWORKORDER_COST_SERVICE_CONTAINER_ATTRIBUTE MAIN INNER JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_cost_customs_cash($work_order_number)
	{
		return $this->db->query("SELECT DISTINCT COST.COST_NAME, MAIN.WORK_ORDER_NUMBER, MAIN.COST_ID, MAIN.CUSTOM_LOCATION_ID, MAIN.CUSTOM_KIND_ID, MAIN.CUSTOM_LINE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.COST_GROUP_ID, MAIN.COST_TYPE_ID, MAIN.COST_CURRENCY, MAIN.COST_AMOUNT FROM HSP..TRWORKORDER_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN INNER JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_container_number($work_order_number)
	{
		return $this->db->query("SELECT MAIN.CONTAINER_NUMBER, MAIN.AGREEMENT_NUMBER, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_name_nik($nik)
	{
		return $this->db->query("SELECT Nik, Nm_lengkap FROM pltapol..u_nik WHERE Nik = '$nik'");
	}

	function get_data_declare($trx_operational, $work_order_number, $pic_id, $cost_id, $container_number)
	{
		return $this->db->query("SELECT * FROM HSP..TROPERATIONAL_DETAIL MAIN LEFT JOIN HSP..TROPERATIONAL_HEADER OPR_HEADER ON MAIN.TRX_OPERATIONAL = OPR_HEADER.TRX_OPERATIONAL LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.TRX_OPERATIONAL = '$trx_operational' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND OPR_HEADER.PIC_ID = '$pic_id' AND MAIN.COST_ID = '$cost_id' AND MAIN.CONTAINER_NUMBER = '$container_number'");
	}

	function check_cash($work_order_number, $cost_id, $container_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRCASH_REQUEST WHERE WORK_ORDER_NUMBER = '$work_order_number' AND COST_ID = '$cost_id' AND CONTAINER_NUMBER = '$container_number'");
	}

	function get_data_cash_request($work_order_number)
	{
		return $this->db->query("SELECT DISTINCT MAIN.SEQUENCE_ID, MAIN.WORK_ORDER_NUMBER, MAIN.COST_KIND, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_REQUEST_AMOUNT, MAIN.COST_INVOICE_AMOUNT, MAIN.COST_ACTUAL_AMOUNT, MAIN.COST_RECEIVED_AMOUNT, CONVERT(char(10), MAIN.TRANSFER_DATE_ACTUAL, 126) as TRANSFER_DATE_ACTUAL, MAIN.COST_ID, COST.COST_NAME, TYPE.GENERAL_DESCRIPTION AS COST_TYPE, COS_GROUP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.USER_ID_RECEIVED, NAMA.NM_LENGKAP AS NAME, MAIN.IS_TRANSFERED, MAIN.IS_DONE FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID TYPE ON MAIN.COST_TYPE_ID = TYPE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COS_GROUP ON MAIN.COST_GROUP_ID = COS_GROUP.GENERAL_ID LEFT JOIN pltapol..u_nik NAMA ON MAIN.USER_ID_RECEIVED = NAMA.Nik WHERE WORK_ORDER_NUMBER = '$work_order_number' AND TYPE.CLASSIFICATION_ID = 'cost_type' AND COS_GROUP.CLASSIFICATION_ID = 'cost_group'");
	}

	function get_data_cash_request2($work_order_number)
	{
		return $this->db->query("SELECT DISTINCT MAIN.SEQUENCE_ID, MAIN.WORK_ORDER_NUMBER, MAIN.COST_KIND, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_REQUEST_AMOUNT, MAIN.COST_INVOICE_AMOUNT, MAIN.COST_ACTUAL_AMOUNT, MAIN.COST_RECEIVED_AMOUNT, CONVERT(char(10), MAIN.TRANSFER_DATE_ACTUAL, 126) as TRANSFER_DATE_ACTUAL, MAIN.COST_ID, COST.COST_NAME, TYPE.GENERAL_DESCRIPTION AS COST_TYPE, COS_GROUP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.USER_ID_RECEIVED, NAMA.NM_LENGKAP AS NAME, MAIN.IS_TRANSFERED, MAIN.IS_DONE, MAIN.COST_GROUP_ID FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID TYPE ON MAIN.COST_TYPE_ID = TYPE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COS_GROUP ON MAIN.COST_GROUP_ID = COS_GROUP.GENERAL_ID LEFT JOIN pltapol..u_nik NAMA ON MAIN.USER_ID_RECEIVED = NAMA.Nik WHERE WORK_ORDER_NUMBER = '$work_order_number' AND IS_TRANSFERED = 'N' ORDER BY MAIN.COST_GROUP_ID DESC");
	}

	function get_nik($kode)
	{
		$this->db3->db_select();
		return $this->db->query("SELECT TOP(5) MAIN.Nik, MAIN.Nm_lengkap, AKSES.cmpy_code FROM pltapol..u_nik MAIN LEFT JOIN pltapol..u_nik_cmpy_akses AKSES ON MAIN.Nik = AKSES.Nik WHERE (AKSES.cmpy_code = '83' OR AKSES.cmpy_code = '84' OR AKSES.cmpy_code = '85') AND MAIN.Nm_lengkap LIKE '%$kode%'");
	}

	function get_nik_full()
	{
		$this->db3->db_select();
		return $this->db->query("SELECT MAIN.Nik, MAIN.Nm_lengkap, AKSES.cmpy_code FROM pltapol..u_nik MAIN LEFT JOIN pltapol..u_nik_cmpy_akses AKSES ON MAIN.Nik = AKSES.Nik WHERE AKSES.cmpy_code = '83'");
	}

	function update_cash($work_order_number, $container_number, $cost_id, $sequence_id, $table, $data_cash)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("SEQUENCE_ID", $sequence_id);

		$this->db->set($data_cash);
		if (!$this->db->update($table, $data_cash)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_data_transfer($pic_name, $pic_id, $date)
	{
		return $this->db->query("SELECT DISTINCT MAIN.COST_KIND, MAIN.SEQUENCE_ID, MAIN.WORK_ORDER_NUMBER, MAIN.COST_ID, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_RECEIVED_AMOUNT AS COST_AMOUNT, CONVERT(CHAR(10), MAIN.TRANSFER_DATE_ACTUAL, 126) AS TRANSFER_DATE_ACTUAL, MAIN.USER_ID_RECEIVED, COST.COST_NAME AS COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, NIK.Nm_lengkap AS FULL_NAME, MAIN.COST_GROUP_ID FROM HSP..TRCASH_REQUEST MAIN INNER JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID INNER JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID INNER JOIN pltapol..u_nik NIK ON MAIN.USER_ID_RECEIVED = NIK.Nik WHERE (MAIN.USER_ID_RECEIVED LIKE '%$pic_id%' OR NIK.Nm_lengkap LIKE '%$pic_name%') AND MAIN.TRANSFER_DATE_ACTUAL = '$date' AND (MAIN.IS_TRANSFERED = 'N' OR MAIN.IS_TRANSFERED = NULL) AND MAIN.COST_GROUP_ID != 'DOR'");
	}

	// function get_data_transfer2($pic_name, $pic_id, $date, $work_order_number)
	// {
	// 	return $this->db->query("SELECT DISTINCT MAIN.COST_KIND, MAIN.SEQUENCE_ID, MAIN.WORK_ORDER_NUMBER, MAIN.COST_ID, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_RECEIVED_AMOUNT AS COST_AMOUNT, CONVERT(CHAR(10), MAIN.TRANSFER_DATE_ACTUAL, 126) AS TRANSFER_DATE_ACTUAL, MAIN.USER_ID_RECEIVED, COST.COST_NAME AS COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, NIK.Nm_lengkap AS FULL_NAME, MAIN.COST_GROUP_ID FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN pltapol..u_nik NIK ON MAIN.USER_ID_RECEIVED = NIK.Nik WHERE (MAIN.USER_ID_RECEIVED LIKE '%$pic_id%' OR NIK.Nm_lengkap LIKE '%$pic_name%') AND MAIN.TRANSFER_DATE_ACTUAL = '$date' AND (MAIN.IS_TRANSFERED = 'N' OR MAIN.IS_TRANSFERED = NULL) AND MAIN.COST_GROUP_ID = 'DOR' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	// }

	function get_data_transfer2($pic_name, $pic_id, $date)
	{
		return $this->db->query("SELECT DISTINCT MAIN.COST_KIND, MAIN.SEQUENCE_ID, MAIN.WORK_ORDER_NUMBER, MAIN.COST_ID, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_RECEIVED_AMOUNT AS COST_AMOUNT, CONVERT(CHAR(10), MAIN.TRANSFER_DATE_ACTUAL, 126) AS TRANSFER_DATE_ACTUAL, MAIN.USER_ID_RECEIVED, COST.COST_NAME AS COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, NIK.Nm_lengkap AS FULL_NAME, MAIN.COST_GROUP_ID FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN pltapol..u_nik NIK ON MAIN.USER_ID_RECEIVED = NIK.Nik WHERE (MAIN.USER_ID_RECEIVED LIKE '%$pic_id%' OR NIK.Nm_lengkap LIKE '%$pic_name%') AND MAIN.TRANSFER_DATE_ACTUAL = '$date' AND (MAIN.IS_TRANSFERED = 'N' OR MAIN.IS_TRANSFERED = NULL) AND MAIN.COST_GROUP_ID = 'DOR'");
	}

	function update_wo_cash($work_order_number, $table, $data_work_order)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_work_order)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_stat($work_order_number, $container_number, $cost_id, $sequence_id, $cost_kind, $table, $change_stat)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("SEQUENCE_ID", $sequence_id);
		$this->db->where("COST_KIND", $cost_kind);

		$this->db->set($change_stat);
		if (!$this->db->update($table, $change_stat)) {
			return FALSE;
		} else {
			return TRUE;
		}
		// return $this->db->query("update HSP..TRCASH_REQUEST set IS_TRANSFERED = 'Y' where WORK_ORDER_NUMBER = '$work_order_number' and COST_ID = '$cost_id' and CONTAINER_NUMBER = '$container_number' and COST_KIND = '$cost_kind' and SEQUENCE_ID = '$sequence_id'");
	}

	function get_work_detail($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(11), MAIN.WORK_ORDER_DATE, 106) AS WORK_ORDER_DATE, CONVERT(CHAR(10), MAIN.WORK_ORDER_DATE, 126) AS WORK_DATE, VESSEL.VESSEL_NAME AS VESSEL_NAME, TRADE.GENERAL_DESCRIPTION AS TRADE_NAME, CUSTOMER.NAME AS CUSTOMER_NAME, POL.PORT_NAME AS POL_NAME, POD.PORT_NAME AS POD_NAME, MAIN.VOYAGE_NUMBER FROM HSP..TRWORKORDER MAIN INNER JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID INNER JOIN HSP..MGENERAL_ID TRADE ON MAIN.TRADE_ID = TRADE.GENERAL_ID INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID INNER JOIN HSP..MPORT POL ON MAIN.POL_ID = POL.PORT_ID INNER JOIN HSP..MPORT POD ON MAIN.POD_ID = POD.PORT_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_commodity($kode)
	{
		return $this->db->query("SELECT TOP(5) * FROM HSP..MCOMMODITY WHERE COMMODITY_DESCRIPTION LIKE '%$kode%'");
	}

	function get_commodity2()
	{
		return $this->db->query("SELECT * FROM HSP..MCOMMODITY");
	}

	function delete_cash_request($work_order_number, $status)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("STATUS", $status);
		if (!$this->db->delete("dbo.TRCASH_REQUEST")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_view_trucking()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.COMMODITY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CUSTOMER.NAME AS CUSTOMER_NAME, COMMODITY.COMMODITY_DESCRIPTION, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, TRUCKING.CHASIS_ID_NUMBER, TRUCKING.TRUCK_ID_NUMBER, TRUCKING.TRUCK_OWNER_ID, TRUCKING.DRIVER_ID, CONVERT(CHAR(11), TRUCKING.ESTIMATION_ARRIVED, 106) AS EST_DATE, TRUCKING.DELIVERY_ORDER_NUMBER, TRUCK_OWN.TRUCK_OWNER_NAME, DRIVER.DRIVER_NAME, TRADE.GENERAL_DESCRIPTION AS TRADE_NAME, CONVERT(CHAR(11), TRUCKING.DOCUMENT_DATE, 106) AS DOCUMENT_DATE FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN INNER JOIN HSP..TRWORKORDER WORK ON MAIN.WORK_ORDER_NUMBER = WORK.WORK_ORDER_NUMBER INNER JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON WORK.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN HSP..MCOMMODITY COMMODITY ON MAIN.COMMODITY_ID = COMMODITY.COMMODITY_ID INNER JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID INNER JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID LEFT JOIN HSP..TRWORKORDER_TRUCKING TRUCKING ON MAIN.CONTAINER_NUMBER = TRUCKING.CONTAINER_NUMBER AND MAIN.WORK_ORDER_NUMBER = TRUCKING.WORK_ORDER_NUMBER LEFT JOIN HSP..MTRUCK_OWNER TRUCK_OWN ON TRUCKING.TRUCK_OWNER_ID = TRUCK_OWN.TRUCK_OWNER_ID LEFT JOIN HSP..MDRIVER DRIVER ON TRUCKING.DRIVER_ID = DRIVER.DRIVER_ID INNER JOIN HSP..MGENERAL_ID TRADE ON WORK.TRADE_ID = TRADE.GENERAL_ID");
	}

	// function get_container($work_order_number)
	// {
	// 	return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, COMMODITY.COMMODITY_DESCRIPTION FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN INNER JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID INNER JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID LEFT JOIN HSP..MCOMMODITY COMMODITY ON MAIN.COMMODITY_ID = COMMODITY.COMMODITY_ID WHERE (MAIN.FLAG = '0' OR MAIN.FLAG = NULL) AND MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	// }

	function get_container2($work_order_number, $container_number)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, COMMODITY.COMMODITY_DESCRIPTION FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID LEFT JOIN HSP..MCOMMODITY COMMODITY ON MAIN.COMMODITY_ID = COMMODITY.COMMODITY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.CONTAINER_NUMBER = '$container_number'");
	}

	function get_own_truck()
	{
		return $this->db->query("SELECT * FROM HSP..MTRUCK_OWNER");
	}

	function get_truck($kode)
	{
		return $this->db->query("SELECT TOP(5) * FROM HSP..MTRUCK WHERE TRUCK_ID LIKE '%$kode%'");
	}

	function get_chasis($kode)
	{
		return $this->db->query("SELECT TOP(5) * FROM HSP..MCHASSIS WHERE CHASSIS_ID LIKE '%$kode%'");	
	}

	function get_driver($kode)
	{
		return $this->db->query("SELECT TOP(5) * FROM HSP..MDRIVER WHERE DRIVER_NAME LIKE '%$kode%'");		
	}

	function get_driver2()
	{
		return $this->db->query("SELECT * FROM HSP..MDRIVER");		
	}

	function get_max_do()
	{
		return $this->db->query("select MAX(DELIVERY_ORDER_NUMBER) AS id FROM HSP..TRWORKORDER_TRUCKING");
	}

	function check_container_trucking($container_number, $work_order_number)
	{
		return $this->db->query("SELECT DELIVERY_ORDER_NUMBER, CONVERT(CHAR(10), DOCUMENT_DATE, 126) AS DOCUMENT_DATE FROM HSP..TRWORKORDER_TRUCKING WHERE CONTAINER_NUMBER = '$container_number' AND WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_container_param($work_order_number, $do_number)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, COMMODITY.COMMODITY_DESCRIPTION, TRUCKING.TRUCK_ID_NUMBER, TRUCKING.TRUCK_OWNER_ID, TRUCKING.CHASIS_ID_NUMBER, TRUCKING.DRIVER_ID, TRUCKING.FINAL_LOCATION_DETAIL, OWNER.TRUCK_OWNER_NAME, DRIVER.DRIVER_NAME, CONVERT(CHAR(10), TRUCKING.DOCUMENT_DATE, 126) AS DOCUMENT_DATE, CONVERT(CHAR(10), TRUCKING.ESTIMATION_ARRIVED, 126) AS EST_DATE FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID LEFT JOIN HSP..MCOMMODITY COMMODITY ON MAIN.COMMODITY_ID = COMMODITY.COMMODITY_ID LEFT JOIN HSP..TRWORKORDER_TRUCKING TRUCKING ON MAIN.WORK_ORDER_NUMBER = TRUCKING.WORK_ORDER_NUMBER AND MAIN.CONTAINER_NUMBER = TRUCKING.CONTAINER_NUMBER LEFT JOIN HSP..MTRUCK_OWNER OWNER ON TRUCKING.TRUCK_OWNER_ID = OWNER.TRUCK_OWNER_ID LEFT JOIN HSP..MDRIVER DRIVER ON TRUCKING.DRIVER_ID = DRIVER.DRIVER_ID WHERE DELIVERY_ORDER_NUMBER = '$do_number' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}



	function check_do($do_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_TRUCKING WHERE DELIVERY_ORDER_NUMBER = '$do_number'");
	}

	function update_flag($work_order_number, $container_number, $table, $update_flag)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $work_order_number);
		$this->db->update($table, $update_flag);
	}

	function change_flag($work_order_number, $container_number, $table, $change_flag)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		if (!$this->db->update($table, $change_flag)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_container_trucking($work_order_number, $do_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("DELIVERY_ORDER_NUMBER", $do_number);
		if (!$this->db->delete("dbo.TRWORKORDER_TRUCKING")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_wo_service($work_order_number, $selling_service)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("SERVICE_ID", $selling_service);
		if (!$this->db->delete('dbo.TRWORKORDER_SERVICE')) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_max_transaction()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(TRX_NUMBER),6,8) AS id FROM HSP..TRTRANSFER_HEADER");
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

		// $this->db4->db_select();
		$this->db->db_select('eHsj');
		return $this->db->query("select * from $table_name..vutil_genr where genr_type = 'VC'");
	}

	function insert_vou($table_name, $company_code, $table, $data)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	// $tables = "eHsj..".$table;
		// 	if (!$this->db4->insert($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	if (!$this->db5->insert($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// }
		$this->db->db_select('eHsj');
		// $this->db4->query("use eHsj");
		// $tables = "eHsj..".$table;
		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}

		// $this->db->close();
	}

	function insert_vou_det($table_name, $company_code, $table, $data)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	// $tables = "eHsj..".$table;
		// 	if (!$this->db4->insert($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	if (!$this->db5->insert($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// }

		// $this->db4->db_select();
		$this->db->db_select('eHsj');
		// $this->db4->query("use eHsj");
		// $tables = "eHsj..".$table;
		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}

		// $this->db->close();
	}

	function insert_vou_det_jaminan($table_name, $company_code, $table, $data)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	$this->db4->set($data);
		// 	if (!$this->db4->insert($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	$this->db5->set($data);
		// 	if (!$this->db5->insert($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// }

		// $this->db4->db_select();
		$this->db->db_select('eHsj');
		$this->db->set($data);
		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}

		// $this->db->close();
	}

	function update_voucher($table_name, $company_code, $table, $update_voucher, $type)
	{
		// if ($company_code == '83') {
		// 	$this->db4->db_select();
		// 	$this->db4->set($update_voucher);
		// 	$this->db4->where("genr_type", $type);
		// 	if (!$this->db4->update($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// } elseif ($company_code == '84') {
		// 	$this->db5->db_select();
		// 	$this->db5->set($update_voucher);
		// 	$this->db5->where("genr_type", $type);
		// 	if (!$this->db5->update($table, $data)) {
		// 		return FALSE;
		// 	} else {
		// 		return TRUE;
		// 	}
		// }

		// $this->db4->db_select();

		$this->db->db_select('eHsj');
		$this->db->set($update_voucher);

		$this->db->where("genr_type", $type);
		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_trtrx_vou($table, $data)
	{
		$this->db->db_select('HSP');
		// $this->db4->query("use eHsj");
		// $tables = "eHsj..".$table;
		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_transfer_header($table, $data)
	{
		$this->db->db_select('HSP');
		// $this->db4->query("use eHsj");
		// $tables = "eHsj..".$table;
		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_transfer_detail($table, $data)
	{
		$this->db->db_select('HSP');
		// $this->db4->query("use eHsj");
		// $tables = "eHsj..".$table;
		if (!$this->db->insert($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function reset_connect()
	{
		$this->db->db_select('HSP');
	}

	// function get_voucher_code($table_name, $company_code)
	// {
	// 	// if ($company_code == '83') {
	// 	// 	$this->db4->db_select();
	// 	// 	return $this->db4->query("select * from $table_name..vutil_genr where genr_type = 'VC'");
	// 	// } elseif ($company_code == '84') {
	// 	// 	$this->db5->db_select();
	// 	// 	return $this->db5->query("select * from $table_name..vutil_genr where genr_type = 'VC'");
	// 	// }

	// 	$this->db4->db_select();
	// 	return $this->db4->query("select * from $table_name..vutil_genr where genr_type = 'VC'");
	// }

	// function insert_vou($table_name, $company_code, $table, $data)
	// {
	// 	// if ($company_code == '83') {
	// 	// 	$this->db4->db_select();
	// 	// 	// $tables = "eHsj..".$table;
	// 	// 	if (!$this->db4->insert($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// } elseif ($company_code == '84') {
	// 	// 	$this->db5->db_select();
	// 	// 	if (!$this->db5->insert($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// }
	// 	$this->db4->db_select();
	// 	// $this->db4->query("use eHsj");
	// 	// $tables = "eHsj..".$table;
	// 	if (!$this->db4->insert($table, $data)) {
	// 		return FALSE;
	// 	} else {
	// 		return TRUE;
	// 	}
	// }

	// function insert_vou_det($table_name, $company_code, $table, $data)
	// {
	// 	// if ($company_code == '83') {
	// 	// 	$this->db4->db_select();
	// 	// 	// $tables = "eHsj..".$table;
	// 	// 	if (!$this->db4->insert($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// } elseif ($company_code == '84') {
	// 	// 	$this->db5->db_select();
	// 	// 	if (!$this->db5->insert($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// }

	// 	$this->db4->db_select();
	// 	// $tables = "eHsj..".$table;
	// 	if (!$this->db4->insert($table, $data)) {
	// 		return FALSE;
	// 	} else {
	// 		return TRUE;
	// 	}
	// }

	// function insert_vou_det_jaminan($table_name, $company_code, $table, $data)
	// {
	// 	// if ($company_code == '83') {
	// 	// 	$this->db4->db_select();
	// 	// 	$this->db4->set($data);
	// 	// 	if (!$this->db4->insert($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// } elseif ($company_code == '84') {
	// 	// 	$this->db5->db_select();
	// 	// 	$this->db5->set($data);
	// 	// 	if (!$this->db5->insert($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// }

	// 	$this->db4->db_select();
	// 	$this->db4->set($data);
	// 	if (!$this->db4->insert($table, $data)) {
	// 		return FALSE;
	// 	} else {
	// 		return TRUE;
	// 	}
	// }

	// function update_voucher($table_name, $company_code, $table, $update_voucher, $type)
	// {
	// 	// if ($company_code == '83') {
	// 	// 	$this->db4->db_select();
	// 	// 	$this->db4->set($update_voucher);
	// 	// 	$this->db4->where("genr_type", $type);
	// 	// 	if (!$this->db4->update($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// } elseif ($company_code == '84') {
	// 	// 	$this->db5->db_select();
	// 	// 	$this->db5->set($update_voucher);
	// 	// 	$this->db5->where("genr_type", $type);
	// 	// 	if (!$this->db5->update($table, $data)) {
	// 	// 		return FALSE;
	// 	// 	} else {
	// 	// 		return TRUE;
	// 	// 	}
	// 	// }

	// 	$this->db4->db_select();
	// 	$this->db4->set($update_voucher);
	// 	$this->db4->where("genr_type", $type);
	// 	if (!$this->db4->update($table, $data)) {
	// 		return FALSE;
	// 	} else {
	// 		return TRUE;
	// 	}
	// }

	function get_seq_detail($transaction_number)
	{
		$this->db->db_select('HSP');
		return $this->db->query("SELECT MAX(SEQUENCE_ID) AS id FROM HSP..TRTRANSFER_DETAIL WHERE TRX_NUMBER = '$transaction_number'");
	}

	function get_data_transaction()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		// return $this->db->query("SELECT MAIN.TRX_NUMBER AS TRANSACTION_NUMBER, CONVERT(CHAR(11), MAIN.TRX_DATE, 106) AS TRANSACTION_DATE, MAIN.CURRENCY, MAIN.TOTAL_AMOUNT, MAIN.PIC_RECEIVER, PIC.Nm_lengkap AS RECEIVER, NIK.Nm_lengkap AS PIC_NAME FROM HSP..TRTRANSFER_HEADER MAIN LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] PIC ON MAIN.PIC_RECEIVER = PIC.Nik LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] NIK ON MAIN.USER_ID = NIK.Nik");

		return $this->db->query("SELECT DISTINCT MAIN.TRX_NUMBER, CONVERT(CHAR(10), MAIN.TRX_DATE, 126) AS TRX_DATE, MAIN.CURRENCY, MAIN.TOTAL_AMOUNT, MAIN.PIC_RECEIVER, MAIN.USER_ID, DETAIL.WORK_ORDER_NUMBER, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, VOU.VOUCHER_NUMBER, PIC.Nm_lengkap AS RECEIVER, NIK.Nm_lengkap AS PIC_NAME, CUSTOMER.NAME AS CUSTOMER_NAME FROM HSP..TRTRANSFER_HEADER MAIN LEFT JOIN HSP..TRTRANSFER_DETAIL DETAIL ON MAIN.TRX_NUMBER = DETAIL.TRX_NUMBER LEFT JOIN HSP..MGENERAL_ID COST_GP ON DETAIL.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..TRTRX_VOU VOU ON MAIN.TRX_NUMBER = VOU.TRX_NUMBER LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] PIC ON MAIN.PIC_RECEIVER = PIC.Nik LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] NIK ON MAIN.USER_ID = NIK.Nik LEFT JOIN HSP..TRWORKORDER ORDERS ON DETAIL.WORK_ORDER_NUMBER = ORDERS.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] CUSTOMER ON ORDERS.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE COST_GP.CLASSIFICATION_ID = 'COST_GROUP' AND VOU.FLOW = '0'");
	}

	function get_data_transaction2()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		// return $this->db->query("SELECT MAIN.TRX_NUMBER AS TRANSACTION_NUMBER, CONVERT(CHAR(11), MAIN.TRX_DATE, 106) AS TRANSACTION_DATE, MAIN.CURRENCY, MAIN.TOTAL_AMOUNT, MAIN.PIC_RECEIVER, PIC.Nm_lengkap AS RECEIVER, NIK.Nm_lengkap AS PIC_NAME FROM HSP..TRTRANSFER_HEADER MAIN LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] PIC ON MAIN.PIC_RECEIVER = PIC.Nik LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] NIK ON MAIN.USER_ID = NIK.Nik");

		return $this->db->query("SELECT MAIN.TRX_NUMBER, CONVERT(CHAR(10), MAIN.TRX_DATE, 126) AS TRX_DATE, MAIN.CURRENCY, MAIN.TOTAL_AMOUNT, MAIN.PIC_RECEIVER, MAIN.USER_ID, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, VOU.VOUCHER_NUMBER, PIC.Nm_lengkap AS RECEIVER, NIK.Nm_lengkap AS PIC_NAME, CUSTOMER.NAME AS CUSTOMER_NAME FROM HSP..TRTRANSFER_HEADER MAIN LEFT JOIN HSP..TRTRANSFER_DETAIL DETAIL ON MAIN.TRX_NUMBER = DETAIL.TRX_NUMBER LEFT JOIN HSP..MGENERAL_ID COST_GP ON DETAIL.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..TRTRX_VOU VOU ON MAIN.TRX_NUMBER = VOU.TRX_NUMBER LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] PIC ON MAIN.PIC_RECEIVER = PIC.Nik LEFT JOIN [192.168.11.181\dbtli01].[pltapol].[dbo].[u_nik] NIK ON MAIN.USER_ID = NIK.Nik LEFT JOIN HSP..TRWORKORDER ORDERS ON DETAIL.WORK_ORDER_NUMBER = ORDERS.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] CUSTOMER ON ORDERS.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE COST_GP.CLASSIFICATION_ID = 'COST_GROUP' AND VOU.FLOW = '0' GROUP BY MAIN.TRX_NUMBER, TRX_DATE, MAIN.CURRENCY, MAIN.TOTAL_AMOUNT, MAIN.PIC_RECEIVER, MAIN.USER_ID, COST_GP.GENERAL_DESCRIPTION, VOU.VOUCHER_NUMBER, PIC.Nm_lengkap, NIK.Nm_lengkap, CUSTOMER.NAME ORDER BY MAIN.TRX_NUMBER DESC");
	}

	function get_wo_transaction($trx_number)
	{
		return $this->db->query("SELECT DISTINCT TRX_NUMBER, WORK_ORDER_NUMBER FROM HSP..TRTRANSFER_DETAIL WHERE TRX_NUMBER = '$trx_number'");
	}

	function get_detail_transaction($transaction_number)
	{
		return $this->db->query("SELECT DISTINCT MAIN.TRX_NUMBER, MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.COST_GROUP_ID, MAIN.COST_TYPE_ID, MAIN.COST_CURRENCY, MAIN.COST_AMOUNT, COST.COST_NAME AS COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP FROM HSP..TRTRANSFER_DETAIL MAIN INNER JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID INNER JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID WHERE TRX_NUMBER = '$transaction_number'");
	}

	function get_vou_out($transaction_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRTRX_VOU WHERE TRX_NUMBER = '$transaction_number' AND FLOW = '0'");
	}

	function get_vou_in($transaction_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRTRX_VOU WHERE TRX_NUMBER = '$transaction_number' AND FLOW = '1'");
	}

	function get_once_transaction($transaction_number)
	{
		return $this->db->query("SELECT CONVERT(CHAR(11), MAIN.TRX_DATE, 106) AS TRANSACTION_DATE, PIC.Nm_lengkap AS PIC_NAME, NIK.Nm_lengkap AS ENTRY_BY FROM HSP..TRTRANSFER_HEADER MAIN LEFT JOIN pltapol..u_nik PIC ON MAIN.PIC_RECEIVER = PIC.Nik LEFT JOIN pltapol..u_nik NIK ON MAIN.USER_ID = NIK.Nik WHERE TRX_NUMBER = '$transaction_number'");
	}

	function get_name_customer($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("select main.WORK_ORDER_NUMBER, customer.name from HSP..TRWORKORDER main left join [192.168.11.28].[CRM].[dbo].[MCOMPANY] as customer on main.CUSTOMER_ID = customer.COMPANY_ID where main.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_detail_container($work_order_number, $container_number)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.DELIVERY_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.TRUCK_ID_NUMBER, TRUCK_OWN.TRUCK_OWNER_NAME, DRIVER.DRIVER_NAME FROM HSP..TRWORKORDER_TRUCKING MAIN LEFT JOIN HSP..MTRUCK_OWNER TRUCK_OWN ON MAIN.TRUCK_OWNER_ID = TRUCK_OWN.TRUCK_OWNER_ID LEFT JOIN HSP..MDRIVER DRIVER ON MAIN.DRIVER_ID = DRIVER.DRIVER_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.CONTAINER_NUMBER = '$container_number'");
	}

	function get_data_print_do($work_order_number, $container_number)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, COMMOD.COMMODITY_DESCRIPTION, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MCOMMODITY COMMOD ON MAIN.COMMODITY_ID = COMMOD.COMMODITY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.CONTAINER_NUMBER = '$container_number'");
	}

	function get_data_trucking_param($work_order_number, $container_number)
	{
		return $this->db->query("SELECT *, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE MAIN INNER JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID INNER JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.CONTAINER_NUMBER = '$container_number'");
	}

	function check_sppb($work_order_number)
	{
		return $this->db->query("select REGISTER_NUMBER_SPPB_SPEB from TRWORKORDER_CUSTOMS where WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_qty_container()
	{
		return $this->db->query("SELECT WORK_ORDER_NUMBER, CONTAINER_SIZE_ID, COUNT(CONTAINER_SIZE_ID) AS TOTAL FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE GROUP BY WORK_ORDER_NUMBER, CONTAINER_SIZE_ID ORDER BY WORK_ORDER_NUMBER DESC");
	}

	function get_qty_container_inv()
	{
		return $this->db->query("SELECT WORK_ORDER_NUMBER, CONTAINER_SIZE_ID, COUNT(CONTAINER_SIZE_ID) AS TOTAL FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE GROUP BY WORK_ORDER_NUMBER, CONTAINER_SIZE_ID ORDER BY WORK_ORDER_NUMBER ASC");
	}

	function check_wo_container($work_order_number, $container_size_id)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WHERE WORK_ORDER_NUMBER = '$work_order_number' AND CONTAINER_SIZE_ID = '$container_size_id'");
	}

	function update_vessel($table, $update_voyage, $vessel_id)
	{
		$this->db->where("VESSEL_ID", $vessel_id);
		$this->db->update($table, $update_voyage);
	}

	function get_date_truck($truck_id)
	{
		return $this->db->query("select CONVERT(CHAR(10), STNK_EXPIRED, 126) AS STNK, CONVERT(CHAR(10), KIR_EXPIRED, 126) AS KIR from HSP..MTRUCK where TRUCK_ID = '$truck_id'");
	}

	function change_flag_truck($truck_number, $table, $flag_truck)
	{
		$this->db->where("TRUCK_ID", $truck_number);
		if (!$this->db->update($table, $flag_truck)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_available_truck($truck_number)
	{
		return $this->db->query("SELECT * FROM HSP..MTRUCK WHERE TRUCK_ID = '$truck_number'");
	}

	function get_detail_container_param($container_number, $work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WHERE CONTAINER_NUMBER = '$container_number' AND WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function check_expired_driver($driver_id)
	{
		return $this->db->query("SELECT CONVERT(CHAR(10), LICENSE_DRIVER_EXPIRED, 126) AS LICENSE_DRIVER_EXPIRED, FLAG, DRIVER_NAME FROM HSP..MDRIVER WHERE DRIVER_ID = '$driver_id'");
	}

	function check_expired_chasis($chasis_number)
	{
		return $this->db->query("SELECT CONVERT(CHAR(10), KIR_EXPIRED, 126) AS KIR_EXPIRED, FLAG FROM HSP..MCHASSIS WHERE CHASSIS_ID = '$chasis_number'");
	}

	function change_flag_chasis($chasis_number, $table, $flag_chasis)
	{
		$this->db->where("CHASSIS_ID", $chasis_number);
		if (!$this->db->update($table, $flag_chasis)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function change_flag_driver($driver_id, $table, $flag_driver)
	{
		$this->db->where("DRIVER_ID", $driver_id);
		if (!$this->db->update($table, $flag_driver)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_all_wo($code_cmpy)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, CUSTOMER.NAME AS CUSTOMER_NAME from HSP..TRWORKORDER MAIN LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID where MAIN.COMPANY_ID = '$code_cmpy'");
	}

	function get_data_cost()
	{
		return $this->db->query("SELECT DISTINCT MAIN.COST_ID, MAIN.COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COST_TYPE AS COST_TYPE_ID, MAIN.COST_GROUP AS COST_GROUP_ID FROM MCOST MAIN LEFT JOIN MGENERAL_ID COST_TP ON MAIN.COST_TYPE = COST_TP.GENERAL_ID LEFT JOIN MGENERAL_ID COST_GP ON MAIN.COST_GROUP = COST_GP.GENERAL_ID WHERE MAIN.COST_KIND = 'A' ORDER BY COST_ID DESC");
	}

	function get_cost_param($cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST WHERE COST_ID = '$cost_id'");
	}

	function get_data_currency()
	{
		return $this->db->query("SELECT * FROM HSP..MCURRENCY");
	}

	function get_max_additional()
	{
		return $this->db->query("SELECT MAX(ADDITIONAL_NUMBER) AS id FROM HSP..TRCASH_REQUEST_ADDITIONAL");
	}

	function get_data_additional($work_order_number)
	{
		return $this->db->query("SELECT DISTINCT MAIN.ADDITIONAL_NUMBER, MAIN.WORK_ORDER_NUMBER, MAIN.STATUS, MAIN.COST_ID, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_KIND, MAIN.COST_REQUEST_AMOUNT, MAIN.COST_INVOICE_AMOUNT, CONVERT(CHAR(10), MAIN.REQUEST_DATE, 126) AS REQUEST_DATE, COST.COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.USER_ID_REQUEST, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID FROM HSP..TRCASH_REQUEST_ADDITIONAL MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.STATUS = 'A' AND MAIN.IS_DELETED = 'N'");
	}

	function delete_additional($work_order_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("COST_KIND", "A");
		if (!$this->db->delete("HSP..TRCASH_REQUEST")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_max_cash($cost_additional, $container_numbers)
	{
		return $this->db->query("SELECT MAX(SEQUENCE_ID)+1 AS id FROM HSP..TRCASH_REQUEST WHERE COST_ID = '$cost_additional' AND CONTAINER_NUMBER = '$container_numbers'");
	}

	function get_temp_add($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRCASH_REQUEST_ADDITIONAL WHERE WORK_ORDER_NUMBER = '$work_order_number' AND STATUS = 'A' AND IS_DELETED = 'N'");
	}

	function change_delete_additional($work_order_number, $table, $temp_change)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("STATUS = 'W' OR STATUS = 'N'");

		$this->db->set($temp_change);
		if (!$this->db->update($table, $temp_change)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function change_delete_additional3($work_order_number, $table, $temp_change)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("STATUS = 'A'");

		$this->db->set($temp_change);
		if (!$this->db->update($table, $temp_change)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function change_delete_additional2($work_order_number, $table, $temp_change)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("STATUS = 'N' OR STATUS = 'W'");

		$this->db->set($temp_change);
		if (!$this->db->update($table, $temp_change)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_deleted($additonal_number, $work_order_number, $container_number, $cost_id, $cost_request_amount, $table, $change_delete)
	{
		$this->db->where("ADDITIONAL_NUMBER", $additonal_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("COST_REQUEST_AMOUNT", $cost_request_amount);

		$this->db->set($change_delete);
		if (!$this->db->update($table, $change_delete)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_deleted2($additonal_number, $work_order_number, $container_number, $cost_id, $cost_request_amount, $table, $change_delete)
	{
		$this->db->where("ADDITIONAL_NUMBER", $additonal_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("COST_REQUEST_AMOUNT", $cost_request_amount);
		$this->db->where("STATUS = 'A'");

		$this->db->set($change_delete);
		if (!$this->db->update($table, $change_delete)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_approval($transaction_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRAPPROVAL_STATUS_TRANSACTION WHERE TRANSACTION_NUMBER = '$transaction_number'");
	}

	function check_approval_param($transaction_number, $document_id)
	{
		return $this->db->query("SELECT * FROM HSP..TRAPPROVAL_STATUS_TRANSACTION WHERE TRANSACTION_NUMBER = '$transaction_number' AND DOCUMENT_ID = '$document_id'");
	}

	function get_add_param($work_order_number)
	{
		return $this->db->query("SELECT DISTINCT MAIN.NEED_APPROVED, MAIN.ADDITIONAL_NUMBER, MAIN.WORK_ORDER_NUMBER, MAIN.STATUS, MAIN.COST_ID, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_KIND, MAIN.COST_REQUEST_AMOUNT, MAIN.COST_INVOICE_AMOUNT, CONVERT(CHAR(10), MAIN.REQUEST_DATE, 126) AS REQUEST_DATE, COST.COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.USER_ID_REQUEST, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID FROM HSP..TRCASH_REQUEST_ADDITIONAL MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND (MAIN.STATUS = 'W' OR MAIN.STATUS = 'N') AND MAIN.IS_DELETED = 'N' AND (MAIN.NEED_APPROVED = 'W' OR MAIN.NEED_APPROVED = 'Y')");
	}

	function insert_wo($table, $data_work_order)
	{
		$this->db->insert($table, $data_work_order);
		return ($this->db->affected_rows() != 1) ? false : true;
	}

	function insert_wo_services($table, $data_wo_service)
	{
		$this->db->insert($table, $data_wo_service);
		return $this->db->affected_rows() > 0;
	}

	function update_customs_wo($table, $data_customs, $work_order_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_customs)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_data_cash_opr($pic_id, $work_order_number)
	{
		return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.COMPANY_ID, MAIN.COST_ID, MAIN.CONTAINER_NUMBER, MAIN.COST_KIND, MAIN.SEQUENCE_ID, MAIN.COST_CURRENCY, MAIN.COST_GROUP_ID, MAIN.COST_TYPE_ID, MAIN.COST_RECEIVED_AMOUNT, MAIN.COST_ACTUAL_AMOUNT, MAIN.COST_ACTUAL_DOCUMENT_NUMBER, CONVERT(CHAR(10), MAIN.COST_ACTUAL_DATE, 126) AS COST_ACTUAL_DATE, COST.COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID where MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.USER_ID_RECEIVED = '$pic_id' AND IS_TRANSFERED = 'Y' AND IS_DONE = 'N'");
	}

	function get_max_operational()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(TRX_OPERATIONAL), 9, 6) as id FROM HSP..TROPERATIONAL_HEADER");
	}

	function get_user_cash()
	{
		return $this->db->query("select DISTINCT MAIN.USER_ID_RECEIVED, PIC_NAME.Nm_lengkap as NAME_PIC from HSP..TRCASH_REQUEST MAIN inner join pltapol..u_nik PIC_NAME on MAIN.USER_ID_RECEIVED = PIC_NAME.Nik");
	}

	function get_name_pic($pic_id)
	{
		$this->db3->db_select();
		return $this->db3->query("select * from pltapol..u_nik where Nik = '$pic_id'");
	}

	function update_opr($work_order_number, $container_number, $cost_id, $cost_kind, $sequence_id, $table, $data_operational)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("COST_KIND", $cost_kind);
		$this->db->where("SEQUENCE_ID", $sequence_id);

		$this->db->set($data_operational);
		if (!$this->db->update($table, $data_operational)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// function get_pic_opr()
	// {
	// 	return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.USER_ID_RECEIVED, PIC_NICK.Nm_lengkap AS PIC_NAME FROM HSP..TRCASH_REQUEST MAIN INNER JOIN pltapol..u_nik PIC_NICK ON MAIN.USER_ID_RECEIVED = PIC_NICK.Nik ORDER BY MAIN.WORK_ORDER_NUMBER ASC");
	// }

	// function get_pic_opr()
	// {
	// 	return $this->db->query("SELECT DISTINCT MAIN.WORK_ORDER_NUMBER, MAIN.USER_ID_RECEIVED, PIC_NICK.Nm_lengkap AS PIC_NAME, CUSTOMER.NAME, AMO.COST_AMOUNT, ACT.COST_ACTUAL FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN pltapol..u_nik PIC_NICK ON MAIN.USER_ID_RECEIVED = PIC_NICK.Nik LEFT JOIN HSP..TRWORKORDER WORK_ORDER ON MAIN.WORK_ORDER_NUMBER = WORK_ORDER.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON WORK_ORDER.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN (SELECT AMOUNT.WORK_ORDER_NUMBER, SUM(AMOUNT.COST_RECEIVED_AMOUNT) AS COST_AMOUNT, AMOUNT.USER_ID_RECEIVED FROM HSP..TRCASH_REQUEST AMOUNT WHERE AMOUNT.IS_TRANSFERED = 'Y' GROUP BY AMOUNT.WORK_ORDER_NUMBER, AMOUNT.USER_ID_RECEIVED) AS AMO ON MAIN.WORK_ORDER_NUMBER = AMO.WORK_ORDER_NUMBER AND MAIN.USER_ID_RECEIVED = AMO.USER_ID_RECEIVED LEFT JOIN (SELECT ACTUAL.WORK_ORDER_NUMBER, SUM(ACTUAL.COST_ACTUAL_AMOUNT) AS COST_ACTUAL, ACTUAL.USER_ID_RECEIVED FROM HSP..TRCASH_REQUEST ACTUAL WHERE ACTUAL.IS_TRANSFERED = 'Y' GROUP BY ACTUAL.WORK_ORDER_NUMBER, ACTUAL.USER_ID_RECEIVED) AS ACT ON MAIN.WORK_ORDER_NUMBER = ACT.WORK_ORDER_NUMBER AND MAIN.USER_ID_RECEIVED = ACT.USER_ID_RECEIVED WHERE IS_TRANSFERED = 'Y' ORDER BY MAIN.WORK_ORDER_NUMBER ASC");
	// }

	function get_pic_opr()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.TRX_OPERATIONAL, CONVERT(CHAR(10), MAIN.TRX_OPERATIONAL_DATE, 126) AS OPERATIONAL_DATE, MAIN.WORK_ORDER_NUMBER, MAIN.PIC_ID, MAIN.CURRENCY, MAIN.TOTAL_AMOUNT, MAIN.STATUS, WORK_ORDER.CUSTOMER_ID, NIK.Nm_lengkap AS PIC_NAME, COMPANY.NAME, opr.TOTAL_AMOUNTS FROM HSP..TROPERATIONAL_HEADER MAIN LEFT JOIN HSP..TRWORKORDER WORK_ORDER ON MAIN.WORK_ORDER_NUMBER = WORK_ORDER.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as COMPANY ON WORK_ORDER.CUSTOMER_ID = COMPANY.COMPANY_ID LEFT JOIN pltapol..u_nik NIK ON MAIN.PIC_ID = NIK.Nik LEFT JOIN ( SELECT TRX_OPERATIONAL, SUM(COST_ACTUAL_AMOUNT) AS TOTAL_AMOUNTS FROM HSP..TROPERATIONAL_DETAIL GROUP BY TRX_OPERATIONAL ) AS opr ON MAIN.TRX_OPERATIONAL = opr.TRX_OPERATIONAL ORDER BY MAIN.TRX_OPERATIONAL DESC");
	}

	function get_detail_opr($code_cmpy, $work_order_number, $pic_id)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.COST_RECEIVED_AMOUNT, MAIN.COST_ACTUAL_AMOUNT, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, COST.COST_NAME, BANK.DESCRIPTION_1 AS DESCRIPTION_MUTATION, CONVERT(CHAR(10), BANK.TRANSACTION_DATE, 126) AS MUTATION_DATE FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..TRBANK_STATEMENT BANK ON MAIN.TRANSACTION_ID = BANK.TRANSACTION_ID WHERE IS_TRANSFERED = 'Y' AND MAIN.COMPANY_ID = '$code_cmpy' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND USER_ID_RECEIVED = '$pic_id'");
	}

	function check_max_sequence($mutation_account, $work_order_number, $pic_id)
	{
		return $this->db->query("SELECT MAX(SEQUENCE_ID)+1 AS total FROM HSP..TRCASH_MUTATION WHERE TRANSACTION_ID = '$mutation_account' AND WORK_ORDER_NUMBER = '$work_order_number' AND PIC_ID = '$pic_id'");
	}

	function get_detail_opr2($code_cmpy, $work_order_number, $pic_id)
	{
		return $this->db->query("SELECT MAIN.TRANSACTION_ID, MAIN.COST_KIND, MAIN.SEQUENCE_ID, MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.COST_ID, MAIN.COST_RECEIVED_AMOUNT, MAIN.COST_ACTUAL_AMOUNT, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, COST.COST_NAME, MAIN.COST_ACTUAL_DOCUMENT_NUMBER, CONVERT(CHAR(10), MAIN.COST_ACTUAL_DATE, 126) AS ACTUAL_DATE FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.COMPANY_ID = '$code_cmpy' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND USER_ID_RECEIVED = '$pic_id'");
	}

	function get_max_invoice()
	{
		return $this->db->query("select SUBSTRING(MAX(INVOICE_NUMBER),6,8) AS id from HSP..TRINVOICE");
	}

	function get_invoice($invoice_number)
	{
		return $this->db->query("SELECT *, CONVERT(CHAR(12), INVOICE_DATE, 107) AS INVOICE_DATE2, CONVERT(CHAR(10), INVOICE_DATE, 105) AS INVOICE_DATE3 FROM HSP..TRINVOICE");
	}

	function get_customer_address($customer)
	{
		$this->db2->db_select();
		return $this->db2->query("SELECT * FROM CRM..MCOMPANY_ADDRESS WHERE COMPANY_ID = '$customer'");
	}

	function get_header_invoice($work_order_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.VESSEL_ID, MAIN.VOYAGE_NUMBER, CONVERT(CHAR(11), MAIN.ETA, 106) AS ETA, CONVERT(CHAR(11), MAIN.ETD, 106) AS ETD, MAIN.POL_ID, MAIN.POD_ID, MAIN.CUSTOMER_ID, VESSEL.VESSEL_NAME, POD.PORT_NAME AS POD_NAME, POL.PORT_NAME AS POL_NAME, CUSTOMER.NAME AS CUSTOMER_NAME, SHIPPER.NAME AS SHIPPER_NAME FROM HSP..TRWORKORDER MAIN LEFT JOIN HSP..MVESSEL VESSEL ON MAIN.VESSEL_ID = VESSEL.VESSEL_ID LEFT JOIN HSP..MPORT POD ON MAIN.POD_ID = POD.PORT_ID LEFT JOIN HSP..MPORT POL ON MAIN.POL_ID = POL.PORT_ID LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN HSP..TRWORKORDER_CUSTOMS CUSTOMS ON MAIN.WORK_ORDER_NUMBER = CUSTOMS.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as SHIPPER ON CUSTOMS.IMPORTIR_ID = SHIPPER.COMPANY_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_cost_invoice($work_order_number)
	{
		return $this->db->query("select *, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP from HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID where WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_cost_invoice2($work_order_number)
	{
		return $this->db->query("select *, COST.COST_NAME from HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID where WORK_ORDER_NUMBER = '$work_order_number' and IS_DONE = 'Y' AND COST_TYPE_ID = 'REM'");
	}

	function get_selling_invoice($work_order_number)
	{
		return $this->db->query("SELECT WORK_ORDER_NUMBER, CONTAINER_NUMBER, TARIFF_CURRENCY, KIND, AMOUNT FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE UNPIVOT (AMOUNT FOR KIND IN (TARIFF_AMOUNT, CUSTOMS_LANE_AMOUNT)) AS unpvt WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_selling_additional_invoice($work_order_number)
	{
		return $this->db->query("SELECT MAIN.ADDITIONAL_SELLING, MAIN.WORK_ORDER_NUMBER, MAIN.SELLING_SERVICE_ID, MAIN.CONTAINER_NUMBER, MAIN.TARIFF_CURRENCY, MAIN.TARIFF_AMOUNT, SELLING.SERVICE_NAME FROM HSP..TRWORKORDER_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MSELLING_SERVICE SELLING ON MAIN.SELLING_SERVICE_ID = SELLING.SELLING_SERVICE_ID WHERE MAIN.WORK_ORDER_NUMBER = '$work_order_number' AND MAIN.STATUS = 'A'");
	}

	function get_pivot_transfer($container_number)
	{
		return $this->db->query("SELECT * FROM ( SELECT CONTAINER_NUMBER, COST_ID, IS_TRANSFERED, COST_GROUP_ID FROM TRCASH_REQUEST) AS src PIVOT( MAX(IS_TRANSFERED) FOR COST_ID IN ([C005], [C006], [C031]) ) AS pvt WHERE COST_GROUP_ID = 'DOR' AND CONTAINER_NUMBER = '$container_number'");
	}

	function check_transfered_cost($container_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRCASH_REQUEST WHERE CONTAINER_NUMBER = '$container_number' AND IS_TRANSFERED = 'Y'");
	}

	function check_amount_container($container_number, $work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE WHERE CONTAINER_NUMBER = '$container_number' AND WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_max_mutation()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(TRANSACTION_ID),3,8) AS id FROM HSP..TRBANK_STATEMENT");
	}

	function check_data_mutation($bank_id, $transaction_date, $transaction_validate, $description_1, $ori_amount, $home_debit, $home_credit)
	{
		return $this->db->query("SELECT * FROM HSP..TRBANK_STATEMENT WHERE BANK_ID = '$bank_id' AND TRANSACTION_DATE = '$transaction_date' AND TRANSACTION_VALIDATE = '$transaction_validate' AND DESCRIPTION_1 = '$description_1' AND ORIGINAL_AMOUNT = '$ori_amount' AND HOME_DEBIT = '$home_debit' AND HOME_CREDIT = '$home_credit'");
	}

	function get_data_mutation()
	{
		return $this->db->query("SELECT TRANSACTION_ID, CONVERT(CHAR(10), TRANSACTION_DATE, 126) AS TRANSACTION_DATE, DESCRIPTION_1, HOME_DEBIT FROM HSP..TRBANK_STATEMENT WHERE HOME_DEBIT != 0 AND IS_DONE != 'Y'");
	}

	function get_data_mutation2($rek_pic)
	{
		return $this->db->query("SELECT TRANSACTION_ID, CONVERT(CHAR(10), TRANSACTION_DATE, 126) AS TRANSACTION_DATE, DESCRIPTION_1, HOME_DEBIT FROM HSP..TRBANK_STATEMENT WHERE HOME_DEBIT != 0 AND IS_DONE != 'Y' AND BANK_ID = '$rek_pic'");
	}

	function get_data_mutation3($rek_pic)
	{
		return $this->db->query("SELECT TRANSACTION_ID, CONVERT(CHAR(10), TRANSACTION_DATE, 126) AS TRANSACTION_DATE, DESCRIPTION_1, HOME_DEBIT FROM HSP..TRBANK_STATEMENT WHERE HOME_DEBIT != 0 AND BANK_ID = '$rek_pic'");
	}

	function check_cash_mutation($mutation_account, $work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRCASH_MUTATION WHERE TRANSACTION_ID = '$mutation_account' AND WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function check_cashs($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRCASH_REQUEST WHERE WORK_ORDER_NUMBER = '$work_order_number' AND IS_DONE = 'N'");
	}

	function update_container_attr($table, $data_trucking, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_trucking)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_cost_additional($table, $data_tr_cost_additional, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_cost_additional)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_cost_container($table, $data_tr_cost_container, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_cost_container)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_cost_container_customs($table, $data_tr_cost_container_customs, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_cost_container_customs)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_wo_additional_selling($table, $data_tr_wo_additional, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_wo_additional)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_wo_trucking($table, $data_tr_wo_trucking, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_wo_trucking)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_transfer_detail($table, $data_tr_transfer_detail, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_transfer_detail)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_operational_detail($table, $data_tr_operational_detail, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_operational_detail)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_invoice_detail($table, $data_tr_invoice_detail, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_invoice_detail)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_cash_request($table, $data_tr_cash_request, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_cash_request)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_tr_cash_request_additional($table, $data_tr_cash_request_additional, $container_number, $work_order_number)
	{
		$this->db->where("CONTAINER_NUMBER", $container_number);
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		if (!$this->db->update($table, $data_tr_cash_request_additional)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_data_trade()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'TRADE_ID'");
	}

	function check_vessel_voyage($vessel_id, $voyage_number, $trade_id, $eta, $etd, $pol_id, $pod_id)
	{
		return $this->db->query("SELECT * FROM HSP..MVESSEL_VOYAGE WHERE VESSEL_ID = '$vessel_id' AND VOYAGE_NUMBER = '$voyage_number' AND TRADE = '$trade_id' AND ETA_DATE = '$eta' AND ETD_DATE = '$etd' AND POL_ID = '$pol_id' AND POD_ID = '$pod_id'");
	}

	function check_vessel($vessel_name)
	{
		return $this->db->query("SELECT * FROM HSP..MVESSEL WHERE VESSEL_NAME = '$vessel_name'");
	}

	function get_max_vessel()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(VESSEL_ID), 2, 3) as id FROM HSP..MVESSEL");
	}

	function check_port($port_id, $port_name, $country)
	{
		return $this->db->query("SELECT * FROM HSP..MPORT WHERE PORT_ID = '$port_id' AND PORT_NAME = '$port_name' AND COUNTRY = '$country'");
	}

	function get_data_detail_transaction($trx_number)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT MAIN.TRX_NUMBER, CONVERT(CHAR(11), MAIN.USER_DATE, 106) AS TRANS_DATE, MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_AMOUNT, PIC.Nm_lengkap AS PIC_NAME, NIK.Nm_lengkap AS NIK_NAME, WORK_ORDER.TRADE_ID, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, CONVERT(CHAR(11), WORK_ORDER.WORK_ORDER_DATE, 106) AS WO_DATE, COMPANY.NAME AS COMPANY_NAME, CONTAIN.CONTAINER_SIZE_ID, CONTAIN.CONTAINER_TYPE_ID, CONTAIN.BL_NUMBER, CONVERT(CHAR(11), WORK_ORDER.ETA, 106) AS ETA_DATE, CONVERT(CHAR(11), WORK_ORDER.ETD, 106) AS ETD_DATE, COST.COST_NAME, CONTAIN.CUSTOMS_LOCATION FROM HSP..TRTRANSFER_DETAIL MAIN LEFT JOIN HSP..TRTRANSFER_HEADER TR_HEAD ON MAIN.TRX_NUMBER = TR_HEAD.TRX_NUMBER LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] PIC ON TR_HEAD.PIC_RECEIVER = PIC.Nik LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK ON TR_HEAD.USER_ID = NIK.Nik LEFT JOIN HSP..TRWORKORDER WORK_ORDER ON MAIN.WORK_ORDER_NUMBER = WORK_ORDER.WORK_ORDER_NUMBER LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] COMPANY ON WORK_ORDER.CUSTOMER_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE CONTAIN ON WORK_ORDER.WORK_ORDER_NUMBER = CONTAIN.WORK_ORDER_NUMBER AND MAIN.CONTAINER_NUMBER = CONTAIN.CONTAINER_NUMBER LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.TRX_NUMBER = '$trx_number' AND COST_GP.CLASSIFICATION_ID = 'COST_GROUP'");
	}

	function get_transfer_header($trx_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRTRANSFER_HEADER WHERE TRX_NUMBER = '$trx_number'");
	}

	function get_wo_transfer()
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER ORDER BY WORK_ORDER_NUMBER DESC");
	}

	function get_rek_pic($pic_id)
	{
		return $this->db->query("SELECT * FROM HSP..MBANK_PIC WHERE PIC_ID = '$pic_id'");
	}

	function update_wo_trucking($table, $data_container, $work_order_number, $container_number)
	{
		$this->db->where("WORK_ORDER_NUMBER", $work_order_number);
		$this->db->where("CONTAINER_NUMBER", $container_number);

		if (!$this->db->update($table, $data_container)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_customs_exists($work_order_number)
	{
		return $this->db->query("SELECT * FROM HSP..TRWORKORDER_CUSTOMS WHERE WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_detail_data_transaction2($trx_number)
	{
		// return $this->db->query("SELECT MAIN.TRX_NUMBER, MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(11), MAIN.USER_DATE, 106) AS TRANS_DATE, MAIN.COST_CURRENCY, PIC.Nm_lengkap AS PIC_NAME, NIK.Nm_lengkap AS NIK_NAME, WORK_ORDER.TRADE_ID, CONVERT(CHAR(11), WORK_ORDER.WORK_ORDER_DATE, 106) AS WO_DATE, COMPANY.NAME AS COMPANY_NAME, CONVERT(CHAR(11), WORK_ORDER.ETA, 106) AS ETA_DATE, CONVERT(CHAR(11), WORK_ORDER.ETD, 106) AS ETD_DATE, SUM(MAIN.COST_AMOUNT) AS TOTAL_AMOUNT FROM HSP..TRTRANSFER_DETAIL MAIN LEFT JOIN HSP..TRTRANSFER_HEADER TR_HEAD ON MAIN.TRX_NUMBER = TR_HEAD.TRX_NUMBER LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] PIC ON TR_HEAD.PIC_RECEIVER = PIC.Nik LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK ON TR_HEAD.USER_ID = NIK.Nik LEFT JOIN HSP..TRWORKORDER WORK_ORDER ON MAIN.WORK_ORDER_NUMBER = WORK_ORDER.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] COMPANY ON WORK_ORDER.CUSTOMER_ID = COMPANY.COMPANY_ID WHERE MAIN.TRX_NUMBER = '$trx_number' GROUP BY MAIN.WORK_ORDER_NUMBER, MAIN.USER_DATE, MAIN.COST_CURRENCY, PIC.Nm_lengkap, NIK.Nm_lengkap, WORK_ORDER.TRADE_ID, WORK_ORDER.ETA, WORK_ORDER.ETD, COMPANY.NAME, WORK_ORDER.WORK_ORDER_DATE, MAIN.TRX_NUMBER");

		return $this->db->query("SELECT MAIN.TRX_NUMBER, MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(11), MAIN.USER_DATE, 106) AS TRANS_DATE, MAIN.COST_CURRENCY, PIC.Nm_lengkap AS PIC_NAME, NIKS.FULL_NAME, WORK_ORDER.TRADE_ID, CONVERT(CHAR(11), WORK_ORDER.WORK_ORDER_DATE, 106) AS WO_DATE, COMPANY.NAME AS COMPANY_NAME, CONVERT(CHAR(11), WORK_ORDER.ETA, 106) AS ETA_DATE, CONVERT(CHAR(11), WORK_ORDER.ETD, 106) AS ETD_DATE, SUM(MAIN.COST_AMOUNT) AS TOTAL_AMOUNT FROM HSP..TRTRANSFER_DETAIL MAIN LEFT JOIN HSP..TRTRANSFER_HEADER TR_HEAD ON MAIN.TRX_NUMBER = TR_HEAD.TRX_NUMBER LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] PIC ON TR_HEAD.PIC_RECEIVER = PIC.Nik LEFT JOIN (SELECT MAIN_WO.WORK_ORDER_NUMBER, MAIN_WO.USER_ID, NIK_WO.Nm_lengkap AS FULL_NAME FROM HSP..TRWORKORDER MAIN_WO LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK_WO ON MAIN_WO.USER_ID = NIK_WO.Nik) NIKS ON MAIN.WORK_ORDER_NUMBER = NIKS.WORK_ORDER_NUMBER LEFT JOIN HSP..TRWORKORDER WORK_ORDER ON MAIN.WORK_ORDER_NUMBER = WORK_ORDER.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] COMPANY ON WORK_ORDER.CUSTOMER_ID = COMPANY.COMPANY_ID WHERE MAIN.TRX_NUMBER = '$trx_number' GROUP BY MAIN.WORK_ORDER_NUMBER, MAIN.USER_DATE, MAIN.COST_CURRENCY, PIC.Nm_lengkap, NIKS.FULL_NAME, WORK_ORDER.TRADE_ID, WORK_ORDER.ETA, WORK_ORDER.ETD, COMPANY.NAME, WORK_ORDER.WORK_ORDER_DATE, MAIN.TRX_NUMBER");
	}

	function get_detail_wo_transaction($trx_number, $work_order_number)
	{
		return $this->db->query("SELECT DISTINCT CONTAIN.BL_NUMBER, MAIN.TRX_NUMBER, MAIN.WORK_ORDER_NUMBER, MAIN.CONTAINER_NUMBER, CONTAIN.CUSTOMS_LOCATION, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, CONTAIN.CUSTOMS_LOCATION FROM HSP..TRTRANSFER_DETAIL MAIN LEFT JOIN HSP..TRWORKORDER WORK_ORDER ON MAIN.WORK_ORDER_NUMBER = WORK_ORDER.WORK_ORDER_NUMBER LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..TRWORKORDER_SERVICE_CONTAINER_ATTRIBUTE CONTAIN ON WORK_ORDER.WORK_ORDER_NUMBER = CONTAIN.WORK_ORDER_NUMBER AND MAIN.CONTAINER_NUMBER = CONTAIN.CONTAINER_NUMBER WHERE MAIN.TRX_NUMBER = '$trx_number' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}
}