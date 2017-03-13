<?php

class M_Quotation extends CI_Model {

	private $db2;
	private $db3;

	public function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('crm', TRUE);
		$this->db3 = $this->load->database('pltapol', TRUE);
		// $dsn = 'mssql://userhsp:"hsp432@"@192.168.11.29/pltapol';
		// $this->db3 = $this->load->database($dsn);
	}

	// selling trucking bukan dari cost
	// function get_data_trucking()
	// {
	// 	$this->db->select("MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.START_DATE, MAIN.END_DATE, MAIN.TARIFF_AMOUNT, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, MAIN.TARIFF_CURRENCY, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY");
	// 	$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
	// 	$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
	// 	$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
	// 	$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
	// 	return $this->db->get();
	// }

	// selling trucking by jumlah cost
	function get_data_trucking($code_cmpy)
	{
		return $this->db->query("select distinct sel.COMPANY_ID, sel.START_DATE, sel.END_DATE, sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID, det.total as TOTAL, sel.FROM_QTY, sel.TO_QTY, sel.SELLING_SERVICE_ID, sel.TARIFF_AMOUNT, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, sel.TARIFF_CURRENCY, sel.CALC_TYPE, sel.INCREMENT_QTY from HSP..MSELLING_SERVICE_CONTAINER_ATTRIBUTE sel left join ( select sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID, COUNT(cost.FROM_LOCATION_ID) as total from HSP..MSELLING_SERVICE_CONTAINER_ATTRIBUTE sel left join HSP..MCOST_SERVICE_CONTAINER_ATTRIBUTE cost ON sel.FROM_LOCATION_ID = cost.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = cost.TO_LOCATION_ID AND sel.CONTAINER_SIZE_ID = cost.CONTAINER_SIZE_ID AND sel.CONTAINER_TYPE_ID = cost.CONTAINER_TYPE_ID AND sel.CONTAINER_CATEGORY_ID = cost.CONTAINER_CATEGORY_ID where GETDATE() between sel.START_DATE AND sel.END_DATE group by sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID,sel.FROM_QTY, sel.TO_QTY ) as det ON sel.FROM_LOCATION_ID = det.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = det.TO_LOCATION_ID AND sel.CONTAINER_SIZE_ID = det.CONTAINER_SIZE_ID AND sel.CONTAINER_TYPE_ID = det.CONTAINER_TYPE_ID AND sel.CONTAINER_CATEGORY_ID = det.CONTAINER_CATEGORY_ID left join MLOCATION FROM_LOC ON sel.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left join MLOCATION TO_LOC ON sel.TO_LOCATION_ID = TO_LOC.LOCATION_ID where (GETDATE() between sel.START_DATE AND sel.END_DATE) AND sel.COMPANY_ID = '$code_cmpy'");
	}
	
	public function get_template()
	{
		$this->db->where("TEMPLATE_ID = '1'");
		return $this->db->get("dbo.MTEMPLATE");
	}

	function get_data_customs($code_cmpy)
	{
		return $this->db->query("select distinct sel.COMPANY_ID,sel.START_DATE, sel.END_DATE, sel.CUSTOM_LOCATION_ID,sel.CUSTOM_KIND_ID, sel.CUSTOM_LINE_ID,sel.CONTAINER_SIZE_ID,sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID, det.total as TOTAL, sel.FROM_QTY, sel.TO_QTY, sel.SELLING_SERVICE_ID, sel.TARIFF_AMOUNT, sel.TARIFF_CURRENCY, sel.CALC_TYPE, sel.INCREMENT_QTY from HSP..MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE sel left join ( select sel.CUSTOM_LOCATION_ID,sel.CUSTOM_KIND_ID,sel.CUSTOM_LINE_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID, COUNT(cost.CUSTOM_LOCATION_ID) as total from HSP..MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE sel left join HSP..MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE cost ON sel.CUSTOM_LOCATION_ID = cost.CUSTOM_LOCATION_ID AND sel.CUSTOM_KIND_ID = cost.CUSTOM_KIND_ID AND sel.CUSTOM_LINE_ID = cost.CUSTOM_LINE_ID AND sel.CONTAINER_SIZE_ID = cost.CONTAINER_SIZE_ID AND sel.CONTAINER_TYPE_ID = cost.CONTAINER_TYPE_ID AND sel.CONTAINER_CATEGORY_ID = cost.CONTAINER_CATEGORY_ID where GETDATE() between sel.START_DATE AND sel.END_DATE group by sel.CUSTOM_LOCATION_ID,sel.CUSTOM_KIND_ID,sel.CUSTOM_LINE_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID,sel.FROM_QTY, sel.TO_QTY) as det ON sel.CUSTOM_LOCATION_ID = det.CUSTOM_LOCATION_ID AND sel.CUSTOM_KIND_ID = det.CUSTOM_KIND_ID AND sel.CUSTOM_LINE_ID = det.CUSTOM_LINE_ID AND sel.CONTAINER_SIZE_ID = det.CONTAINER_SIZE_ID AND sel.CONTAINER_TYPE_ID = det.CONTAINER_TYPE_ID AND sel.CONTAINER_CATEGORY_ID = det.CONTAINER_CATEGORY_ID where (GETDATE() between sel.START_DATE AND sel.END_DATE) AND sel.COMPANY_ID = '$code_cmpy'");
	}

	function get_data_location()
	{
		return $this->db->query("select distinct sel.COMPANY_SERVICE_ID, sel.START_DATE, sel.END_DATE, sel.FROM_LOCATION_ID, sel.TO_LOCATION_ID,sel.TRUCK_ID,sel.DISTANCE,sel.DISTANCE_PER_LITRE, det.total as TOTAL, sel.SELLING_SERVICE_ID, sel.TARIFF_AMOUNT, TRUCK.GENERAL_DESCRIPTION as TRUCK_NAME, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, sel.TARIFF_CURRENCY, sel.CALC_TYPE, sel.INCREMENT_QTY from HSP..MSELLING_SERVICE_LOCATION_ATTRIBUTE sel left join ( select sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID,sel.TRUCK_ID, COUNT(cost.FROM_LOCATION_ID) as total from HSP..MSELLING_SERVICE_LOCATION_ATTRIBUTE sel left join HSP..MCOST_SERVICE_LOCATION_ATTRIBUTE cost ON sel.FROM_LOCATION_ID = cost.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = cost.TO_LOCATION_ID AND sel.TRUCK_ID = cost.TRUCK_ID where GETDATE() between sel.START_DATE AND sel.END_DATE group by sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID,sel.TRUCK_ID ) as det ON sel.FROM_LOCATION_ID = det.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = det.TO_LOCATION_ID AND sel.TRUCK_ID = det.TRUCK_ID left join MLOCATION FROM_LOC ON sel.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left join MLOCATION TO_LOC ON sel.TO_LOCATION_ID = TO_LOC.LOCATION_ID left join MGENERAL_ID TRUCK ON sel.TRUCK_ID = TRUCK.GENERAL_ID where GETDATE() between sel.START_DATE AND sel.END_DATE");
	}

	function get_data_weight()
	{
		return $this->db->query("select distinct sel.COMPANY_SERVICE_ID, sel.START_DATE, sel.END_DATE, sel.FROM_LOCATION_ID, sel.TO_LOCATION_ID, det.total as TOTAL, sel.FROM_WEIGHT, sel.TO_WEIGHT, sel.SELLING_SERVICE_ID, sel.TARIFF_AMOUNT, sel.MEASUREMENT_UNIT,FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, sel.TARIFF_CURRENCY, sel.CALC_TYPE, sel.INCREMENT_QTY from HSP..MSELLING_SERVICE_WEIGHT_ATTRIBUTE sel left join ( select sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID, COUNT(cost.FROM_LOCATION_ID) as total from HSP..MSELLING_SERVICE_WEIGHT_ATTRIBUTE sel left join HSP..MCOST_SERVICE_WEIGHT_ATTRIBUTE cost ON sel.FROM_LOCATION_ID = cost.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = cost.TO_LOCATION_ID where GETDATE() between sel.START_DATE AND sel.END_DATE group by sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID ) as det ON sel.FROM_LOCATION_ID = det.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = det.TO_LOCATION_ID left join MLOCATION FROM_LOC ON sel.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left join MLOCATION TO_LOC ON sel.TO_LOCATION_ID = TO_LOC.LOCATION_ID where GETDATE() between sel.START_DATE AND sel.END_DATE");
	}

	function get_data_ocean()
	{
		return $this->db->query("select distinct sel.COMPANY_SERVICE_ID, sel.CHARGE_ID, sel.START_DATE, sel.END_DATE, sel.FROM_LOCATION_ID, sel.TO_LOCATION_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID, det.total as TOTAL, sel.FROM_QTY, sel.TO_QTY, sel.SELLING_SERVICE_ID, sel.TARIFF_AMOUNT, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME, sel.TARIFF_CURRENCY, sel.CALC_TYPE, sel.INCREMENT_QTY from HSP..MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE sel left join ( select sel.CHARGE_ID, sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID, sel.CONTAINER_CATEGORY_ID, COUNT(cost.FROM_LOCATION_ID) as total from HSP..MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE sel left join HSP..MCOST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE cost ON sel.FROM_LOCATION_ID = cost.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = cost.TO_LOCATION_ID AND sel.CONTAINER_SIZE_ID = cost.CONTAINER_SIZE_ID AND sel.CONTAINER_TYPE_ID = cost.CONTAINER_TYPE_ID AND sel.CONTAINER_CATEGORY_ID = cost.CONTAINER_CATEGORY_ID AND sel.CHARGE_ID = cost.CHARGE_ID where GETDATE() between sel.START_DATE AND sel.END_DATE group by sel.CHARGE_ID, sel.FROM_LOCATION_ID,sel.TO_LOCATION_ID,sel.CONTAINER_SIZE_ID, sel.CONTAINER_TYPE_ID,sel.CONTAINER_CATEGORY_ID,sel.FROM_QTY, sel.TO_QTY ) as det ON sel.FROM_LOCATION_ID = det.FROM_LOCATION_ID AND sel.TO_LOCATION_ID = det.TO_LOCATION_ID AND sel.CONTAINER_SIZE_ID = det.CONTAINER_SIZE_ID AND sel.CONTAINER_TYPE_ID = det.CONTAINER_TYPE_ID AND sel.CONTAINER_CATEGORY_ID = det.CONTAINER_CATEGORY_ID AND sel.CHARGE_ID = det.CHARGE_ID left join MLOCATION FROM_LOC ON sel.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left join MLOCATION TO_LOC ON sel.TO_LOCATION_ID = TO_LOC.LOCATION_ID where GETDATE() between sel.START_DATE AND sel.END_DATE");
	}

	function get_max_id()
	{
		$this->db->select("MAX(QUOTATION_NUMBER) as id");
		// $this->db->select("MAX(SUBSTRING(QUOTATION_NUMBER, 1, 2)) as id");
		$this->db->from("dbo.TRQUOTATION");
		return $this->db->get();
	}

	function get_document_number($document_name)
	{
		$this->db->select("MAIN.DOCUMENT_ID");
		$this->db->from("dbo.MDOCUMENT_ID MAIN");
		$this->db->where("DOCUMENT_NAME", $document_name);

		return $this->db->get();
	}

	function get_container_cost_detail($company_service_cost_trucking, $selling_service_cost_trucking, $type_cost_trucking, $from_location_cost_trucking, $to_location_cost_trucking)
	{
		$this->db->distinct();
		$this->db->select("MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COST_ID, MAIN.FROM_QTY, MAIN.TO_QTY, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE, MAIN.COST_GROUP_ID, MAIN.COST_TYPE_ID, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY, MAIN.COST_CURRENCY, MAIN.COST_AMOUNT");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.COMPANY_SERVICE_ID = ", $company_service_cost_trucking);
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service_cost_trucking);
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location_cost_trucking);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location_cost_trucking);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type_cost_trucking);

		return $this->db->get();
	}

	// check data container
	function check_data_cost_trucking($company_service, $selling_service, $container_type, $container_category, $from_location, $to_location)
	{
		$this->db->select("*");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->where("A.COMPANY_SERVICE_ID", $company_service);
		$this->db->where("A.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("A.FROM_LOCATION_ID", $from_location);
		$this->db->where("A.TO_LOCATION_ID", $to_location);
		$this->db->where("A.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $container_category);

		return $this->db->get();
	}

	function get_trucking_detail($selling_service, $type, $category, $from_location, $to_location, $size, $company_id)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.COMPANY_ID = ", $company_id);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");

		return $this->db->get();
	}

	function get_customs_detail($selling_service, $type, $category, $size, $customs_from, $line, $kind, $company_id)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.CUSTOM_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE, CUST_NAME.GENERAL_DESCRIPTION AS CUSTOMS_FROM, CUST_LINE.GENERAL_DESCRIPTION AS CUSTOMS_LINE, CUST_KIND.GENERAL_DESCRIPTION AS CUSTOMS_KIND");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID CUST_NAME", "MAIN.CUSTOM_LOCATION_ID = CUST_NAME.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID CUST_LINE", "MAIN.CUSTOM_LINE_ID = CUST_LINE.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID CUST_KIND", "MAIN.CUSTOM_KIND_ID = CUST_KIND.GENERAL_ID", "left");
		$this->db->where("MAIN.CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("MAIN.CUSTOM_LINE_ID = ", $line);
		$this->db->where("MAIN.CUSTOM_KIND_ID = ", $kind);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.COMPANY_ID = ", $company_id);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");

		return $this->db->get();
	}

	function get_location_detail($selling_service, $from, $to, $truck)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to);
		$this->db->where("MAIN.TRUCK_ID = ", $truck);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");

		return $this->db->get();
	}

	function get_weight_detail($from, $to)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "left");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");
		return $this->db->get();
	}

	function get_data_vendor($from_location, $to_location, $type, $size, $category)
	{
		// $this->db->select("VENDOR.VENDOR_NAME, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.BUYING_CURRENCY, MAIN.BUYING_RATE, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME");
		// $this->db->from("dbo.MVENDOR_SERVICE_CONTAINER_BUYING_RATE MAIN");
		// $this->db->join("dbo.MVENDOR_CONTRACT CON", "MAIN.CONTRACT_NO = CON.CONTRACT_NO", "left");
		// $this->db->join("dbo.MVENDOR VENDOR", "VENDOR.VENDOR_ID = CON.VENDOR_ID", "left");
		// $this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		// $this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		// $this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		// $this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		// $this->db->where("MAIN.CONTAINER_SIZE_ID", $size);
		// $this->db->where("MAIN.CONTAINER_TYPE_ID", $type);
		// $this->db->where("MAIN.CONTAINER_CATEGORY_ID", $category);

		// return $this->db->get();

		return $this->db->query("SELECT MAIN.COMPARE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.BUYING_CURRENCY, MAIN.BUYING_RATE, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, CON_TP.GENERAL_DESCRIPTION AS CON_TYPE, CON_CT.GENERAL_DESCRIPTION AS CON_CAT, COMPETITOR.COMPETITOR_NAME FROM HSP..MCOMPETITOR_SERVICE_CONTAINER_BUYING_RATE MAIN LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID LEFT JOIN HSP..MGENERAL_ID CON_TP ON MAIN.CONTAINER_TYPE_ID = CON_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_CT ON MAIN.CONTAINER_CATEGORY_ID = CON_CT.GENERAL_ID LEFT JOIN HSP..MCOMPETITOR_COMPARE COMPARE ON MAIN.COMPARE_ID = COMPARE.COMPARE_ID LEFT JOIN HSP..MCOMPETITOR COMPETITOR ON COMPARE.COMPETITOR_ID = COMPETITOR.COMPETITOR_ID WHERE CON_TP.CLASSIFICATION_ID = 'CONTAINER_TYPE' AND CON_CT.CLASSIFICATION_ID = 'CONTAINER_CATEGORY' AND MAIN.FROM_LOCATION_ID = '$from_location' AND MAIN.TO_LOCATION_ID = '$to_location' AND MAIN.CONTAINER_SIZE_ID = '$size' AND MAIN.CONTAINER_TYPE_ID = '$type' AND MAIN.CONTAINER_CATEGORY_ID = '$category'");
	}

	// search customer
	public function get_customer($customer)
	{
		$this->db2->db_select();
		$this->db2->distinct();
		$this->db2->select("MAIN.CUSTOMER_ID, MAIN.NAME AS CUSTOMER_NAME, MAIN.COMPANY_ID, COMPANY.NAME AS COMPANY_NAME, MAIN.POSITION_ID, POSITION.TYPE_DESCRIPTION");
		$this->db2->from("dbo.MCUSTOMER_CONTACT MAIN");
		$this->db2->join("dbo.MCOMPANY COMPANY", "MAIN.COMPANY_ID = COMPANY.COMPANY_ID", "join");
		$this->db2->join("dbo.MTYPE POSITION", "MAIN.POSITION_ID = POSITION.TYPE_ID");
		// $this->db2->where("MAIN.POSITION_ID = 'P20160531002'");
		$this->db2->like('COMPANY.NAME', $customer);
		$this->db2->limit(5);
		return $this->db2->get();
	}

	// public function cek()
	// {
	// 	return $this->db2->
	// }

	function get_trucking_cost($selling_service, $type, $category, $from_location, $to_location, $size, $company_id)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.COMPANY_ID = ", $company_id);
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
		// $this->db->where("MAIN.FROM_QTY", "1");

		return $this->db->get();
	}

	function get_ocean_cost($selling_service, $type, $category, $from_location, $to_location, $size, $charge)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MCOST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE MAIN");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.CHARGE_ID = ", $charge);
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");

		return $this->db->get();
	}

	function get_customs_cost($selling_service, $type, $category, $size, $customs_from, $line, $kind, $company_id)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->where("MAIN.CUSTOM_LOCATION_ID = ", $customs_from);
		$this->db->where("MAIN.CUSTOM_LINE_ID = ", $line);
		$this->db->where("MAIN.CUSTOM_KIND_ID = ", $kind);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $category);
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.COMPANY_ID = ", $company_id);
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
		// $this->db->where("MAIN.FROM_QTY", "1");

		return $this->db->get();
	}

	function get_location_cost($selling_service, $from_location, $to_location, $truck_id)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.TRUCK_ID = ", $truck_id);
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");

		return $this->db->get();
	}

	function get_weight_cost($selling_service, $from_location, $to_location)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->where("MAIN.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");

		return $this->db->get();
	}

	function check_data_cost($custom_location, $custom_kind, $custom_line, $size, $type, $category, $cost_id, $cost_type, $cost_group, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("CONTAINER_SIZE_ID", $size);
		$this->db->where("CONTAINER_TYPE_ID", $type);
		$this->db->where("CONTAINER_CATEGORY_ID", $category);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("COST_TYPE_ID", $cost_type);
		$this->db->where("COST_GROUP_ID", $cost_group);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		return $this->db->get("dbo.TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE");
	}

	function get_floor_price($from_location, $to_location, $type, $size, $category, $from_qty, $to_qty)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CONTAINER_SIZE_ID", $size);
		$this->db->where("CONTAINER_TYPE_ID", $type);
		$this->db->where("CONTAINER_CATEGORY_ID", $category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);

		return $this->db->get();

	}

	function get_price($custom_location, $custom_line, $custom_kind, $type, $size, $category, $from_qty, $to_qty)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE");
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CONTAINER_SIZE_ID", $size);
		$this->db->where("CONTAINER_TYPE_ID", $type);
		$this->db->where("CONTAINER_CATEGORY_ID", $category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);

		return $this->db->get();
	}

	function get_quotation()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');
		return $this->db->query("select distinct MAIN.QUOTATION_NUMBER, MAIN.QUOTATION_DOCUMENT_NUMBER, CONVERT(char(10), MAIN.QUOTATION_DATE,126) AS QUOTATION_DATE, MAIN.CUSTOMER_ID, MAIN.REVESION_NUMBER, MAIN.APPROVAL_STATUS, CUSTOMER.NAME AS COMPANY_NAME, MAIN.STATUS_AGREEMENT from HSP..TRQUOTATION MAIN left join [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID");
	}

	function get_quotation_param($quotation_number)
	{
		return $this->db->query("select distinct MAIN.QUOTATION_NUMBER, MAIN.QUOTATION_DOCUMENT_NUMBER,  CONVERT(char(10), MAIN.QUOTATION_DATE,126) AS QUOTATION_DATE, MAIN.CUSTOMER_ID, MAIN.REVESION_NUMBER, MAIN.APPROVAL_STATUS, CUSTOMER.NAME AS COMPANY_NAME from HSP..TRQUOTATION MAIN left join CRM..MCOMPANY CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.QUOTATION_NUMBER = $quotation_number");
	}

	function check_appr($number)
	{
		$this->db->where("TRANSACTION_NUMBER", $number);
		return $this->db->get("dbo.TRAPPROVAL_STATUS");
	}

	function update_apprquote($data, $quotation_number)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		$this->db->update("dbo.TRQUOTATION", $data);
	}

	function get_data_quote($quotation_number)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		return $this->db->get("dbo.TRQUOTATION");
	}

	function update_trappr($data_update_trappr, $quotation_number)
	{
		$this->db->where("TRANSACTION_NUMBER", $quotation_number);
		$this->db->update("dbo.TRAPPROVAL_STATUS", $data_update_trappr);
	}

	function get_data_quote_trucking($quotation_number)
	{
		$this->db->select("MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, CONVERT(char(11), MAIN.START_DATE,106) AS START_DATE, CONVERT(char(11), MAIN.END_DATE,106) AS END_DATE, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.SELLING_OFFERING_RATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME");
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

		return $this->db->query("SELECT MAIN.QUOTATION_NUMBER, MAIN.QUOTATION_DOCUMENT_NUMBER, CONVERT(char(11), MAIN.QUOTATION_PERIODE_START,106) AS QUOTATION_PERIODE_START, CONVERT(char(11), MAIN.QUOTATION_DATE,106) AS QUOTATION_DATE, CONVERT(char(11), MAIN.QUOTATION_PERIODE_END,106) AS QUOTATION_PERIODE_END, MAIN.CUSTOMER_ID, MAIN.REVESION_NUMBER, MAIN.APPROVAL_STATUS, COMPANY.NAME AS COMPANY_NAME, CUSTOMER.NAME, MAIN.APPROVAL_STATUS FROM HSP..TRQUOTATION MAIN LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] as COMPANY ON MAIN.CUSTOMER_ID =COMPANY.COMPANY_ID LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCUSTOMER_CONTACT] as  CUSTOMER ON COMPANY.COMPANY_ID = CUSTOMER.COMPANY_ID WHERE MAIN.QUOTATION_NUMBER = '$quotation_number'");
	}

	// function get_quotation_param_full($quotation_number)
	// {
	// 	// return $this->db->query("select MAIN.QUOTATION_NUMBER, MAIN.QUOTATION_DOCUMENT_NUMBER,  CONVERT(char(11), MAIN.QUOTATION_PERIODE_START,106) AS QUOTATION_PERIODE_START, CONVERT(char(11), MAIN.QUOTATION_DATE,106) AS QUOTATION_DATE, CONVERT(char(11), MAIN.QUOTATION_PERIODE_END,106) AS QUOTATION_PERIODE_END, MAIN.CUSTOMER_ID, MAIN.REVESION_NUMBER, MAIN.APPROVAL_STATUS, COMPANY.NAME AS COMPANY_NAME, CUSTOMER.NAME, MAIN.APPROVAL_STATUS from HSP..TRQUOTATION MAIN left join CRM..MCOMPANY COMPANY ON MAIN.CUSTOMER_ID = COMPANY.COMPANY_ID left join CRM..MCUSTOMER_CONTACT CUSTOMER ON COMPANY.COMPANY_ID = CUSTOMER.COMPANY_ID WHERE MAIN.QUOTATION_NUMBER = '$quotation_number'");

	// 	$this->db->select("MAIN.QUOTATION_NUMBER, MAIN.QUOTATION_DOCUMENT_NUMBER,  CONVERT(char(11), MAIN.QUOTATION_PERIODE_START,106) AS QUOTATION_PERIODE_START, CONVERT(char(11), MAIN.QUOTATION_DATE,106) AS QUOTATION_DATE, CONVERT(char(11), MAIN.QUOTATION_PERIODE_END,106) AS QUOTATION_PERIODE_END, MAIN.CUSTOMER_ID, MAIN.REVESION_NUMBER, MAIN.APPROVAL_STATUS, COMPANY.NAME AS COMPANY_NAME, CUSTOMER.NAME, MAIN.APPROVAL_STATUS");
	// 	$this->db->from("dbo.TRQUOTATION MAIN");
	// 	$this->db2->join('dbo.MCOMPANY COMPANY', "MAIN.CUSTOMER_ID = COMPANY.COMPANY_ID", "left");
	// 	$this->db2->join('dbo.MCUSTOMER_CONTACT CUSTOMER', "COMPANY.COMPANY_ID = CUSTOMER.COMPANY_ID", "left");
	// 	$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);
	// 	return $this->db->get();
	// }

	function get_quotation_param_full2($quotation_number)
	{
		return $this->db->query("select CUSTOMER.NAME from HSP..TRQUOTATION MAIN left join CRM..MCOMPANY COMPANY ON MAIN.CUSTOMER_ID = COMPANY.COMPANY_ID left join CRM..MCUSTOMER_CONTACT CUSTOMER ON COMPANY.COMPANY_ID = CUSTOMER.COMPANY_ID WHERE MAIN.QUOTATION_NUMBER = '$quotation_number'");
	}

	function check_approval($document_id)
	{
		$this->db->select("*");
		$this->db->from("dbo.MDOCUMENT_LEVEL_APPROVAL");
		$this->db->where("DOCUMENT_ID", $document_id);

		return $this->db->get();
	}

	function get_nik_appr($document_id, $approval_level)
	{
		$this->db->distinct();
		$this->db->select("APPROVAL_USER_ID");
		$this->db->from("dbo.MDOCUMENT_LEVEL_APPROVAL_PIC");
		$this->db->where("APPROVAL_LEVEL", $approval_level);

		return $this->db->get();
	}

	function get_email($nik)
	{
		return $this->db->query("select * from pltapol..u_nik where Nik = '$nik'");
	}

	function get_service_quotation($quotation_number)
	{
		$this->db->distinct();
		$this->db->select("SERVICE.SERVICE_NAME NAME");
		$this->db->from("dbo.TRQUOTATION_SERVICE MAIN");
		$this->db->join("dbo.MSELLING_SERVICE SERVICE", "MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID", "left");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	// GET ALL DATA CUSTOM JAKARTA
	public function get_all_data_custom_cost_jakarta($quotation_number)
	{
		$this->db->distinct();
		$this->db->select("A.SELLING_SERVICE_ID, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.CUSTOM_LOCATION_ID, B.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, A.CUSTOM_LINE_ID, E.GENERAL_DESCRIPTION AS CUSTOM_LINE, A.CUSTOM_KIND_ID, C.GENERAL_DESCRIPTION AS CUSTOM_KIND, A.CONTAINER_TYPE_ID, D.GENERAL_DESCRIPTION AS CONTAINER_TYPE, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, A.CALC_TYPE, A.INCREMENT_QTY, A.SELLING_CURRENCY, CALC.GENERAL_DESCRIPTION AS CALC_NAME");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MGENERAL_ID B", "A.CUSTOM_LOCATION_ID = B.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID C", "A.CUSTOM_KIND_ID = C.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID D", "A.CONTAINER_TYPE_ID = D.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID E", "A.CUSTOM_LINE_ID = E.GENERAL_ID", "left");
		$this->db->join("dbo.MGENERAL_ID CALC", "A.CALC_TYPE = CALC.GENERAL_ID", "left");
		$this->db->where("A.QUOTATION_NUMBER", $quotation_number);
		$this->db->where("A.CUSTOM_LOCATION_ID = 'TJP'");
		$this->db->where("A.FROM_QTY = '1'");
		$this->db->where("A.CALC_TYPE = 'UNT'");

		return $this->db->get();
	}

	// GET TARIF AMOUT CUSTOM JAKARTA
	public function get_tarif_amount_custom_jakarta($quotation_number)
	{
		$this->db->select("A.CUSTOM_LOCATION_ID, A.CONTAINER_TYPE_ID, A.CONTAINER_SIZE_ID, A.SELLING_CURRENCY, A.SELLING_OFFERING_RATE, A.SELLING_STANDART_RATE, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->where("A.QUOTATION_NUMBER", $quotation_number);
		$this->db->where("A.CUSTOM_LOCATION_ID = 'TJP'");
		$this->db->where("A.FROM_QTY = '1'");
		$this->db->where("A.CALC_TYPE = 'UNT'");

		return $this->db->get();
	}

	// check data container custom
	function check_data_custom($size, $custom_location, $custom_line, $custom_kind, $type_con, $con_cat, $from_qty, $to_qty, $start_date, $end_date, $quotation_number)
	{
		$this->db->select("A.CUSTOM_LOCATION_ID, A.CONTAINER_SIZE_ID, A.CUSTOM_LINE_ID, A.CONTAINER_TYPE_ID");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->where("A.CONTAINER_SIZE_ID", $size);
		$this->db->where("A.CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("A.CUSTOM_LINE_ID", $custom_line);
		$this->db->where("A.CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("A.CONTAINER_TYPE_ID", $type_con);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $con_cat);
		$this->db->where("A.FROM_QTY", $from_qty);
		$this->db->where("A.TO_QTY", $to_qty);
		$this->db->where("A.START_DATE", $start_date);
		$this->db->where("A.END_DATE", $end_date);
		$this->db->where("A.QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function get_template_quotation($quotation_number)
	{
		$this->db->select("TEMPLATE_TEXT1, TEMPLATE_TEXT2");
		$this->db->from("dbo.TRQUOTATION");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		
		return $this->db->get();
	}

	function get_data_location_jakarta($quotation_number)
	{
		$this->db->select("FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME, MAIN.SELLING_OFFERING_RATE");
		$this->db->from("dbo.TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->join("dbo.MGENERAL_ID TRUCK", "MAIN.TRUCK_KIND_ID = TRUCK.GENERAL_ID", "left");
		$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	public function get_all_data_container_cost_jakarta($quotation_number)
	{
		// GET ALL DATA CONTAINER COST
		$this->db->distinct();
		$this->db->select("CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.CONTAINER_TYPE_ID, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, B.LOCATION_NAME AS FROM_NAME, C.LOCATION_NAME AS TO_NAME, A.CONTAINER_TYPE_ID, A.FROM_QTY, A.TO_QTY, H.GENERAL_DESCRIPTION AS CALC, A.INCREMENT_QTY, A.CONTAINER_CATEGORY_ID");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->join("dbo.MLOCATION B", "A.FROM_LOCATION_ID = B.LOCATION_ID", "left");
		$this->db->join("dbo.MLOCATION C", "A.TO_LOCATION_ID = C.LOCATION_ID", "left");
		$this->db->join("dbo.MGENERAL_ID H", "A.CALC_TYPE = H.GENERAL_ID", "left");
		$this->db->where("A.QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	public function get_tarif_amount_jakarta($quotation_number)
	{
		// GET DATA TARIFF AMOUNT
		$this->db->select("CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.TO_LOCATION_ID, A.CONTAINER_SIZE_ID, A.SELLING_CURRENCY, A.SELLING_OFFERING_RATE, A.SELLING_STANDART_RATE, A.CONTAINER_TYPE_ID, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, convert(varchar(11), A.START_DATE, 106) AS START_DATE, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->where("A.QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	public function get_tarif_weight_jakarta($quotation_number)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get("dbo.TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE");
	}

	// check data container
	function check_data_container($quotation_number, $size, $from_location, $to_location, $type_con, $cat_con, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->select("A.CONTAINER_SIZE_ID, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, A.CONTAINER_TYPE_ID, A.CONTAINER_CATEGORY_ID");
		$this->db->from("dbo.TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->where("A.CONTAINER_SIZE_ID", $size);
		$this->db->where("A.FROM_LOCATION_ID", $from_location);
		$this->db->where("A.TO_LOCATION_ID", $to_location);
		$this->db->where("A.CONTAINER_TYPE_ID", $type_con);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $cat_con);
		$this->db->where("A.FROM_QTY", $from_qty);
		$this->db->where("A.TO_QTY", $to_qty);
		$this->db->where("A.START_DATE", $start_date);
		$this->db->where("A.END_DATE", $end_date);
		$this->db->where("A.QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function get_data_weight_jakarta($quotation_number)
	{
		$this->db->select("FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.SELLING_OFFERING_RATE, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, H.GENERAL_DESCRIPTION AS CALC, MAIN.INCREMENT_QTY, MAIN.CALC_TYPE, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT, MAIN.CALC_TYPE, MAIN.MEASUREMENT_UNIT");
		$this->db->from("TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "left");
		$this->db->join("MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "left");
		$this->db->join("MGENERAL_ID H", "MAIN.CALC_TYPE = H.GENERAL_ID", "left");
		$this->db->where("MAIN.QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function get_quotation_approved()
	{
		return $this->db->query("select distinct MAIN.QUOTATION_NUMBER,  MAIN.QUOTATION_DOCUMENT_NUMBER, CONVERT(char(10), MAIN.QUOTATION_DATE,126) AS QUOTATION_DATE, MAIN.CUSTOMER_ID, MAIN.REVESION_NUMBER, MAIN.APPROVAL_STATUS, CUSTOMER.NAME AS COMPANY_NAME from HSP..TRQUOTATION MAIN left join [192.168.11.28].[CRM].[dbo].[MCOMPANY] CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.APPROVAL_STATUS = 'A'");
	}

	function get_max_agreement()
	{
		return $this->db->query("select MAX(AGREEMENT_NUMBER) as id from HSP..TRAGREEMENT");
	}

	function get_cmpy_code($nik)
	{
		// $this->db3->db_select();
		// $this->db3->select("*");
		// $this->db3->from("u_nik_cmpy");
		// $this->db3->where("nik", $nik);

		// return $this->db3->get();

		return $this->db3->query("SELECT * FROM u_nik_cmpy WHERE Nik = '$nik'");
	}

	function get_code($cmpy)
	{
		$this->db->where("COMPANY_GLOBAL_ID", $cmpy);

		return $this->db->get("dbo.MCOMPANY");
	}

	function get_agreement()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("select distinct MAIN.AGREEMENT_NUMBER, MAIN.AGREEMENT_DOCUMENT_NUMBER, MAIN.QUOTATION_NUMBER, CONVERT(char(10), MAIN.AGREEMENT_DATE,126) AS AGREEMENT_DATE, MAIN.APPROVAL_STATUS, QUOTATION.QUOTATION_DOCUMENT_NUMBER, QUOTATION.CUSTOMER_ID, CUSTOMER.NAME AS COMPANY_NAME, MAIN.AMENDMENT_NUMBER from HSP..TRAGREEMENT MAIN left join HSP..TRQUOTATION QUOTATION on MAIN.QUOTATION_NUMBER = QUOTATION.QUOTATION_NUMBER left join [192.168.11.28].[CRM].[dbo].[MCOMPANY] as CUSTOMER on QUOTATION.CUSTOMER_ID = CUSTOMER.COMPANY_ID");
	}

	function get_agreement_param($agreement_number)
	{
		return $this->db->query("select distinct MAIN.AGREEMENT_NUMBER, MAIN.QUOTATION_NUMBER, CONVERT(char(10), MAIN.AGREEMENT_DATE,126) AS AGREEMENT_DATE, CONVERT(char(11), MAIN.AGREEMENT_PERIODE_START,106) AS AGREEMENT_PERIODE_START, CONVERT(char(11), MAIN.AGREEMENT_PERIODE_END,106) AS AGREEMENT_PERIODE_END, MAIN.AGREEMENT_DOCUMENT_NUMBER, MAIN.APPROVAL_STATUS, QUOTATION.CUSTOMER_ID, CUSTOMER.NAME AS COMPANY_NAME, MAIN.AMENDMENT_NUMBER from HSP..TRAGREEMENT MAIN left join HSP..TRQUOTATION QUOTATION on MAIN.QUOTATION_NUMBER = QUOTATION.QUOTATION_NUMBER left join CRM..MCOMPANY CUSTOMER on QUOTATION.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE MAIN.AGREEMENT_NUMBER = '$agreement_number'");
	}

	function get_detail_trucking_cost($quotation_number, $from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
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

	function get_detail_trucking_name($from_location, $to_location, $type, $size, $category)
	{
		$this->db->distinct();
		$this->db->select("A.COMPANY_NAME, B.SERVICE_NAME, MAIN.FROM_LOCATION_ID, E.LOCATION_NAME AS FROM_NAME, MAIN.TO_LOCATION_ID, F.LOCATION_NAME AS TO_NAME, H.GENERAL_DESCRIPTION AS CONTAINER_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOMPANY A", "MAIN.COMPANY_ID = A.COMPANY_ID", "left");
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
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
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
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
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
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
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
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
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
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
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
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
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
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
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
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
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
		$this->db->select("B.COMPANY_NAME, A.SELLING_SERVICE_ID, C.SERVICE_NAME, A.CUSTOM_LINE_ID, D.GENERAL_DESCRIPTION AS CUSTOM_LINE, A.CUSTOM_LOCATION_ID, E.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, A.CONTAINER_CATEGORY_ID, F.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, A.CONTAINER_TYPE_ID, G.GENERAL_DESCRIPTION AS CONTAINER_TYPE");
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

	function check_quotation($quotation_number)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get("dbo.TRAGREEMENT");
	}

	function check_quotation_insert($id)
	{
		$this->db->where("QUOTATION_NUMBER", $id);

		return $this->db->get("dbo.TRQUOTATION");
	}

	function check_combo($quotation_number, $selling_service)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);

		return $this->db->get("TRQUOTATION_SERVICE");
	}

	function check_data_revision($company_id)
	{
		// $this->db2->db_select();
		// $this->db2->select("COMPANY_ID, NAME");
		// $this->db2->from("MCOMPANY");
		// $this->db2->where("COMPANY_ID", $company_id);

		// return $this->db2->get();

		return $this->db2->query("SELECT COMPANY_ID, NAME FROM CRM..MCOMPANY WHERE COMPANY_ID = '$company_id'");
	}

	function get_data_quotation($quotation_number)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get("TRQUOTATION");
	}

	function get_date_revision($quotation_number)
	{
		$this->db->select("convert(varchar(10), QUOTATION_PERIODE_START, 126) AS START_DATE, convert(varchar(10), QUOTATION_PERIODE_END, 126) AS END_DATE");
		$this->db->from("dbo.TRQUOTATION");
		return $this->db->get();
	}

	function get_revision_trucking($quotation_number)
	{
		return $this->db->query("select MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.SELLING_CURRENCY, MAIN.SELLING_OFFERING_RATE, MAIN.SELLING_STANDART_RATE, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY, convert(varchar(10), MAIN.START_DATE, 126) AS START_DATE, convert(varchar(10), MAIN.END_DATE, 126) AS END_DATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME from TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE MAIN left join MLOCATION FROM_LOC on MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left join MLOCATION TO_LOC on MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID where MAIN.QUOTATION_NUMBER = '$quotation_number'");
	}

	function get_revision_customs($quotation_number)
	{
		return $this->db->query("select MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.CUSTOM_KIND_ID, MAIN.CUSTOM_LINE_ID, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.SELLING_OFFERING_RATE, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY, MAIN.START_DATE, MAIN.END_DATE, MAIN.CUSTOM_LOCATION_ID from TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN where MAIN.QUOTATION_NUMBER = '$quotation_number'");
	}

	function check_revision($quotation_number)
	{
		$this->db->select("REVESION_NUMBER");
		$this->db->from("dbo.TRQUOTATION");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function insert_revision_trucking_selling($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION_SERVICE_CONTAINER_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, FROM_LOCATION_ID, TO_LOCATION_ID, START_DATE, END_DATE, FROM_QTY, TO_QTY, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, CALC_TYPE, INCREMENT_QTY, USER_ID, USER_DATE) select QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, FROM_LOCATION_ID, TO_LOCATION_ID, START_DATE, END_DATE, FROM_QTY, TO_QTY, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, CALC_TYPE, INCREMENT_QTY, USER_ID, USER_DATE from HSP..TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_revision_trucking_cost($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, FROM_QTY, TO_QTY, FROM_LOCATION_ID, TO_LOCATION_ID, START_DATE, END_DATE, COST_ID, ACTIVE_STATUS, COST_TYPE_ID, COST_GROUP_ID, CALC_TYPE, APROVAL_STATUS, COST_CURRENCY, COST_AMOUNT, INCREMENT_QTY, USER_ID, USER_DATE) select QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, FROM_QTY, TO_QTY, FROM_LOCATION_ID, TO_LOCATION_ID, START_DATE, END_DATE, COST_ID, ACTIVE_STATUS, COST_TYPE_ID, COST_GROUP_ID, CALC_TYPE, APROVAL_STATUS, COST_CURRENCY, COST_AMOUNT, INCREMENT_QTY, USER_ID, USER_DATE from HSP..TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_trucking_selling($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_SERVICE_CONTAINER_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_trucking_cost($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_COST_SERVICE_CONTAINER_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function backup_quotation($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION (QUOTATION_NUMBER, QUOTATION_DOCUMENT_NUMBER, COMPANY_ID, REVESION_NUMBER, QUOTATION_DATE, QUOTATION_PERIODE_START, QUOTATION_PERIODE_END, AMENDMEND_DATE, AGREEMENT_NUMBER, AGREEMENT_DOCUMENT_NUMBER, AGREEMENT_DATE, AGREEMENT_PERIODE_START, AGREEMENT_PERIODE_END, CUSTOMER_ID, CREDIT_TERM, MARKETING_ID, CUSTOMER_PIC_ID, CUSTOMER_PIC_TITLE, APPROVAL_STATUS, AGREEMENT_CUSTOMER_PIC, AGREEMENT_CUSTOMER_TITLE, AGREEMENT_HSP_PIC, AGREEMENT_HSP_TITLE, REMARKS, TEMPLATE_TEXT1, TEMPLATE_TEXT2, USER_ID, USER_DATE) select QUOTATION_NUMBER, QUOTATION_DOCUMENT_NUMBER, COMPANY_ID, REVESION_NUMBER, QUOTATION_DATE, QUOTATION_PERIODE_START, QUOTATION_PERIODE_END, AMENDMEND_DATE, AGREEMENT_NUMBER, AGREEMENT_DOCUMENT_NUMBER, AGREEMENT_DATE, AGREEMENT_PERIODE_START, AGREEMENT_PERIODE_END, CUSTOMER_ID, CREDIT_TERM, MARKETING_ID, CUSTOMER_PIC_ID, CUSTOMER_PIC_TITLE, APPROVAL_STATUS, AGREEMENT_CUSTOMER_PIC, AGREEMENT_CUSTOMER_TITLE, AGREEMENT_HSP_PIC, AGREEMENT_HSP_TITLE, REMARKS, TEMPLATE_TEXT1, TEMPLATE_TEXT2, USER_ID, USER_DATE from HSP..TRQUOTATION where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function backup_quotation_services($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION_SERVICE (QUOTATION_NUMBER, REVESION_NUMBER, SELLING_SERVICE_ID, REMARKS) select QUOTATION_NUMBER, REVESION_NUMBER, SELLING_SERVICE_ID, REMARKS from TRQUOTATION_SERVICE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_quotation($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION where QUOTATION_NUMBER = '$quotation_number' and REVESION_NUMBER = (select MIN(REVESION_NUMBER) from HSP..TRQUOTATION where QUOTATION_NUMBER = '$quotation_number')")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_quotation_services($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_SERVICE where QUOTATION_NUMBER = '$quotation_number' and REVESION_NUMBER = (select MIN(REVESION_NUMBER) from HSP..TRQUOTATION_SERVICE where QUOTATION_NUMBER = '$quotation_number')")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_approval($quotation_number, $document_id, $table, $data_trappr)
	{
		$this->db->where("TRANSACTION_NUMBER", $quotation_number);
		$this->db->where("DOCUMENT_ID", $document_id);

		$this->db->update($table, $data_trappr);
	}

	function insert_revision_customs_selling($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, CUSTOM_LOCATION_ID, CUSTOM_KIND_ID, CUSTOM_LINE_ID, SELLING_SERVICE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, FROM_QTY, TO_QTY, START_DATE, END_DATE, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, CALC_TYPE, INCREMENT_QTY, USER_ID, USER_DATE) select QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, CUSTOM_LOCATION_ID, CUSTOM_KIND_ID, CUSTOM_LINE_ID, SELLING_SERVICE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, FROM_QTY, TO_QTY, START_DATE, END_DATE, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, CALC_TYPE, INCREMENT_QTY, USER_ID, USER_DATE from HSP..TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_revision_customs_cost($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE (QUOTATION_NUMBER, REVESION_NUMBER, SELLING_SERVICE_ID, CUSTOM_LOCATION_ID, COMPANY_ID, CUSTOM_KIND_ID, CUSTOM_LINE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, COST_ID, INCREMENT_QTY, START_DATE, END_DATE, FROM_QTY, TO_QTY, ACTIVE_STATUS, CALC_TYPE, COST_TYPE_ID, COST_GROUP_ID, COST_CURRENCY, COST_AMOUNT, APPROVAL_STATUS, USER_ID, USER_DATE) select QUOTATION_NUMBER, REVESION_NUMBER, SELLING_SERVICE_ID, CUSTOM_LOCATION_ID, COMPANY_ID, CUSTOM_KIND_ID, CUSTOM_LINE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, COST_ID, INCREMENT_QTY, START_DATE, END_DATE, FROM_QTY, TO_QTY, ACTIVE_STATUS, CALC_TYPE, COST_TYPE_ID, COST_GROUP_ID, COST_CURRENCY, COST_AMOUNT, APPROVAL_STATUS, USER_ID, USER_DATE from HSP..TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_customs_selling($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_customs_cost($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_COST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_revision_location($quotation_number)
	{
		return $this->db->query("select MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.TRUCK_KIND_ID, MAIN.DISTANCE, MAIN.DISTANCE_PER_LITRE, MAIN.SELLING_CURRENCY, MAIN.SELLING_STANDART_RATE, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY, CONVERT(char(10), MAIN.START_DATE, 126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE, 126) AS END_DATE, MAIN.SELLING_OFFERING_RATE, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME from HSP..TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE MAIN left JOIN MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left JOIN MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID left JOIN MGENERAL_ID TRUCK ON MAIN.TRUCK_KIND_ID = TRUCK.GENERAL_ID WHERE MAIN.QUOTATION_NUMBER = '$quotation_number'");
	}

	function insert_revision_location_selling($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION_SERVICE_LOCATION_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, TRUCK_KIND_ID, START_DATE, END_DATE, DISTANCE, DISTANCE_PER_LITRE, INCREMENT_QTY, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, CALC_TYPE, USER_ID, USER_DATE) select QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, TRUCK_KIND_ID, START_DATE, END_DATE, DISTANCE, DISTANCE_PER_LITRE, INCREMENT_QTY, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, CALC_TYPE, USER_ID, USER_DATE from HSP..TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_revision_location_cost($quotation_number)
	{
		if (!$this->db->query("insert into HSP..HIQUOTATION_COST_SERVICE_LOCATION_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_SERVICE_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, COST_ID, TRUCK_ID, START_DATE, END_DATE, INCREMENT_QTY, CALC_TYPE, COST_TYPE_ID, COST_GROUP_ID, COST_CURRENCY, COST_AMOUNT, USER_ID, USER_DATE) select QUOTATION_NUMBER, COMPANY_SERVICE_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, COST_ID, TRUCK_ID, START_DATE, END_DATE, INCREMENT_QTY, CALC_TYPE, COST_TYPE_ID, COST_GROUP_ID, COST_CURRENCY, COST_AMOUNT, USER_ID, USER_DATE from HSP..TRQUOTATION_COST_SERVICE_LOCATION_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_location_selling($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_SERVICE_LOCATION_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_location_cost($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_COST_SERVICE_LOCATION_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_revision_weight($quotation_number)
	{
		return $this->db->query("SELECT MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.SELLING_CURRENCY, MAIN.SELLING_OFFERING_RATE, MAIN.SELLING_STANDART_RATE, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT, MAIN.MEASUREMENT_UNIT, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY, CONVERT(char(10), MAIN.START_DATE, 126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE, 126) AS END_DATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME FROM HSP..TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE MAIN left JOIN MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left JOIN MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE MAIN.QUOTATION_NUMBER = '$quotation_number'");
	}

	function insert_revision_weight_selling($quotation_number)
	{
		if (!$this->db->query("INSERT INTO HSP..HIQUOTATION_SERVICE_WEIGHT_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, MEASUREMENT_UNIT, FROM_WEIGHT, TO_WEIGHT, START_DATE, END_DATE, INCREMENT_QTY, CALC_TYPE, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, USER_ID, USER_DATE) SELECT QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, MEASUREMENT_UNIT, FROM_WEIGHT, TO_WEIGHT, START_DATE, END_DATE, INCREMENT_QTY, CALC_TYPE, COMPANY_SHARE_ID, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, USER_ID, USER_DATE FROM HSP..TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE WHERE QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_revision_weight_cost($quotation_number)
	{
		if (!$this->db->query("INSERT INTO HSP..HIQUOTATION_COST_SERVICE_WEIGHT_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_SERVICE_ID, REVESION_NUMBER, SELLING_SERVICE_ID, COST_ID, FROM_LOCATION_ID, TO_LOCATION_ID, FROM_WEIGHT, TO_WEIGHT, START_DATE, END_DATE, INCREMENT_QTY, CALC_TYPE, COST_TYPE_ID, COST_GROUP_ID, COST_CURRENCY, COST_AMOUNT, USER_ID, USER_DATE) SELECT QUOTATION_NUMBER, COMPANY_SERVICE_ID, REVESION_NUMBER, SELLING_SERVICE_ID, COST_ID, FROM_LOCATION_ID, TO_LOCATION_ID, FROM_WEIGHT, TO_WEIGHT, START_DATE, END_DATE, INCREMENT_QTY, CALC_TYPE, COST_TYPE_ID, COST_GROUP_ID, COST_CURRENCY, COST_AMOUNT, USER_ID, USER_DATE FROM HSP..TRQUOTATION_COST_SERVICE_WEIGHT_ATTRIBUTE WHERE QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_weight_selling($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_SERVICE_WEIGHT_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_weight_cost($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_COST_SERVICE_WEIGHT_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_status_agreement($quotation_number, $table, $data)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);
		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_quotation_number($agreement_number)
	{
		$this->db->where("AGREEMENT_NUMBER", $agreement_number);
		return $this->db->get("dbo.TRAGREEMENT");
	}

	function get_data_agreement($agreement_number)
	{
		$this->db->select("AGREEMENT_DOCUMENT_NUMBER, CONVERT(char(11), AGREEMENT_DATE,106) AS AGREEMENT_DATE, CONVERT(char(10), AGREEMENT_PERIODE_START, 126) AS START_DATE, CONVERT(char(10), AGREEMENT_PERIODE_END, 126) AS END_DATE");
		$this->db->from("dbo.TRAGREEMENT");

		return $this->db->get();
	}

	function get_data_agreement2($agreement_number)
	{
		$this->db->select("AGREEMENT_DOCUMENT_NUMBER, CONVERT(char(11), AGREEMENT_DATE,106) AS AGREEMENT_DATE, CONVERT(char(10), AGREEMENT_PERIODE_START, 126) AS START_DATE, CONVERT(char(10), AGREEMENT_PERIODE_END, 126) AS END_DATE");
		$this->db->from("dbo.TRAGREEMENT");
		$this->db->where("AGREEMENT_NUMBER", $agreement_number);

		return $this->db->get();
	}

	function update_quotation($quotation_number, $table, $data_quotation)
	{
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		$this->db->update($table, $data_quotation);
	}

	function check_agreement($agreement_number)
	{
		$this->db->select("AMENDMENT_NUMBER");
		$this->db->from("dbo.TRAGREEMENT");
		$this->db->where("AGREEMENT_NUMBER", $agreement_number);
		return $this->db->get();
	}

	function update_agreement($agreement_number, $table, $update_agreement)
	{
		$this->db->where("AGREEMENT_NUMBER", $agreement_number);
		if (!$this->db->update($table, $update_agreement)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function backup_agreement($agreement_number)
	{
		if (!$this->db->query("insert into HSP..HIAGREEMENT (AGREEMENT_NUMBER, AMENDMENT_NUMBER, QUOTATION_NUMBER, AGREEMENT_DOCUMENT_NUMBER, AGREEMENT_DATE, AMENDMENT_DATE, AGREEMENT_PERIODE_START, AGREEMENT_PERIODE_END, CREDIT_TERM, APPROVAL_STATUS, AGREEMENT_CUSTOMER_PIC, AGREEMENT_CUSTOMER_TITLE, AGREEMENT_HSP_PIC, AGREEMENT_HSP_TITLE, REMARKS, USER_ID, USER_DATE) select AGREEMENT_NUMBER, AMENDMENT_NUMBER, QUOTATION_NUMBER, AGREEMENT_DOCUMENT_NUMBER, AGREEMENT_DATE, AMENDMENT_DATE, AGREEMENT_PERIODE_START, AGREEMENT_PERIODE_END, CREDIT_TERM, APPROVAL_STATUS, AGREEMENT_CUSTOMER_PIC, AGREEMENT_CUSTOMER_TITLE, AGREEMENT_HSP_PIC, AGREEMENT_HSP_TITLE, REMARKS, USER_ID, USER_DATE from HSP..TRAGREEMENT where AGREEMENT_NUMBER = '$agreement_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_agreement($agreement_number)
	{
		$this->db->query("delete from HSP..TRAGREEMENT where AGREEMENT_NUMBER = '$agreement_number' and AMENDMENT_NUMBER = (select MIN(AMENDMENT_NUMBER) from HSP..TRAGREEMENT where AGREEMENT_NUMBER = '$agreement_number')");
	}

	function get_data_pic($quotation_number)
	{
		$this->db->select("CUSTOMER_PIC_ID");
		$this->db->from("dbo.TRQUOTATION");
		$this->db->where("QUOTATION_NUMBER", $quotation_number);

		return $this->db->get();
	}

	function get_pic($customer_pic_id)
	{
		return $this->db2->query("select MAIN.SALUTATION_ID, MAIN.NAME, SALUT.TYPE_DESCRIPTION AS NAMDEP, JABAT.TYPE_DESCRIPTION AS JABATAN, MAIN.COMPANY_ID, COMPANY.NAME AS COMPANY_NAME from CRM..MCUSTOMER_CONTACT MAIN left join CRM..MTYPE SALUT on MAIN.SALUTATION_ID = SALUT.TYPE_ID left join CRM..MTYPE JABAT on MAIN.POSITION_ID = JABAT.TYPE_ID left join CRM..MCOMPANY COMPANY on MAIN.COMPANY_ID = COMPANY.COMPANY_ID where CUSTOMER_ID = '$customer_pic_id'");
	}

	function delete_transaction($quotation_number, $document_id)
	{
		if (!$this->db->query("delete from HSP..TRAPPROVAL_STATUS_TRANSACTION where TRANSACTION_NUMBER = '$quotation_number' and REVISION_NUMBER = (select MIN(REVISION_NUMBER) from HSP..TRAPPROVAL_STATUS_TRANSACTION where TRANSACTION_NUMBER = '$quotation_number' AND DOCUMENT_ID = '$document_id')")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_revision_ocean_freight($quotation_number)
	{
		return $this->db->query("select MAIN.SELLING_SERVICE_ID, MAIN.CHARGE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.SELLING_CURRENCY, MAIN.SELLING_OFFERING_RATE, MAIN.SELLING_STANDART_RATE, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY, convert(varchar(10), MAIN.START_DATE, 126) AS START_DATE, convert(varchar(10), MAIN.END_DATE, 126) AS END_DATE, FROM_LOC.LOCATION_NAME_SHORT AS FROM_NAME, TO_LOC.LOCATION_NAME_SHORT AS TO_NAME from TRQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE MAIN left join MLOCATION FROM_LOC on MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID left join MLOCATION TO_LOC on MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID where MAIN.QUOTATION_NUMBER = '$quotation_number'");
	}

	function insert_revision_ocean_selling($quotation_number)
	{
		if (!$this->db->query("INSERT INTO HSP..HIQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, CHARGE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, START_DATE, END_DATE, INCREMENT_QTY, FROM_QTY, TO_QTY, CALC_TYPE, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, USER_ID, USER_DATE) SELECT QUOTATION_NUMBER, COMPANY_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, CHARGE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, START_DATE, END_DATE, INCREMENT_QTY, FROM_QTY, TO_QTY, CALC_TYPE, SELLING_CURRENCY, SELLING_OFFERING_RATE, SELLING_STANDART_RATE, USER_ID, USER_DATE FROM HSP..TRQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE WHERE QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function insert_revision_ocean_cost($quotation_number)
	{
		if (!$this->db->query("INSERT INTO HSP..HIQUOTATION_COST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE (QUOTATION_NUMBER, COMPANY_SERVICE_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, CHARGE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, START_DATE, END_DATE, INCREMENT_QTY, FROM_QTY, TO_QTY, COST_ID, COST_TYPE_ID, COST_GROUP_ID, CALC_TYPE, COST_CURRENCY, COST_AMOUNT, APPROVAL_STATUS, USER_ID, USER_DATE, HISTORY_DATE) SELECT QUOTATION_NUMBER, COMPANY_SERVICE_ID, REVESION_NUMBER, SELLING_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, CHARGE_ID, CONTAINER_SIZE_ID, CONTAINER_TYPE_ID, CONTAINER_CATEGORY_ID, START_DATE, END_DATE, INCREMENT_QTY, FROM_QTY, TO_QTY, COST_ID, COST_TYPE_ID, COST_GROUP_ID, CALC_TYPE, COST_CURRENCY, COST_AMOUNT, APPROVAL_STATUS, USER_ID, USER_DATE, HISTORY_DATE FROM HSP..TRQUOTATION_COST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE WHERE QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_ocean_selling($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_SERVICE_OCEAN_FREIGHT_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_revision_ocean_cost($quotation_number)
	{
		if (!$this->db->query("delete from HSP..TRQUOTATION_COST_SERVICE_OCEAN_FREIGHT_ATTRIBUTE where QUOTATION_NUMBER = '$quotation_number'")) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_nik($kode)
	{
		// $this->db3->db_select();
		return $this->db->query("SELECT TOP(5) * FROM pltapol..u_nik WHERE NM_LENGKAP LIKE '%$kode%'");
	}

}