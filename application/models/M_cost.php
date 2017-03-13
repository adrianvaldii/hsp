<?php

class M_Cost extends CI_Model {
	private $db2;
	private $db3;

	public function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('crm', TRUE);
		$this->db3 = $this->load->database('pltapol', TRUE);
		$this->db4 = $this->load->database('ehsj', TRUE);
		$this->db5 = $this->load->database('ehss', TRUE);
		// $dsn = 'mssql://userhsp:"hsp432@"@192.168.11.29/pltapol';
		// $this->db3 = $this->load->database($dsn);
	}

	// public function get_all_data_container_cost_jakarta()
	// {
	// 	// GET ALL DATA CONTAINER COST
	// 	$this->db->distinct();
	// 	$this->db->select("CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.TARIFF_CURRENCY, A.COMPANY_SERVICE_ID, A.CONTAINER_TYPE_ID, D.COMPANY_NAME, E.SERVICE_NAME, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, A.SELLING_SERVICE_ID, B.LOCATION_NAME AS FROM_NAME, C.LOCATION_NAME AS TO_NAME, A.CONTAINER_TYPE_ID, A.FROM_QTY, A.TO_QTY, H.GENERAL_DESCRIPTION AS CALC, A.INCREMENT_QTY, A.CONTAINER_CATEGORY_ID");
	// 	$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE A");
	// 	$this->db->join("dbo.MLOCATION B", "A.FROM_LOCATION_ID = B.LOCATION_ID", "inner");
	// 	$this->db->join("dbo.MLOCATION C", "A.TO_LOCATION_ID = C.LOCATION_ID", "inner");
	// 	$this->db->join("dbo.MSELLING_SERVICE E", "A.SELLING_SERVICE_ID = E.SELLING_SERVICE_ID", "inner");
	// 	$this->db->join("dbo.MCOMPANY_SERVICES F", "A.COMPANY_SERVICE_ID = F.COMPANY_SERVICE_ID", "inner");
	// 	$this->db->join("dbo.MCOMPANY D", "F.COMPANY_ID = D.COMPANY_ID", "inner");
	// 	$this->db->join("dbo.MGENERAL_ID H", "A.CALC_TYPE = H.GENERAL_ID", "inner");
	// 	$this->db->where("A.COMPANY_SERVICE_ID = 'CS01'");

	// 	return $this->db->get();
	// }

	public function get_all_data_container_cost_jakarta($code_cmpy)
	{
		return $this->db->query("SELECT DISTINCT MAIN.COMPANY_ID, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.TARIFF_CURRENCY, MAIN.CONTAINER_TYPE_ID, COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.SELLING_SERVICE_ID, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.CONTAINER_TYPE_ID, MAIN.FROM_QTY, MAIN.TO_QTY, GEN_CALC.GENERAL_DESCRIPTION AS CALC, MAIN.INCREMENT_QTY, MAIN.CONTAINER_CATEGORY_ID FROM HSP..MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID LEFT JOIN HSP..MSELLING_SERVICE SERVICE ON MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MGENERAL_ID GEN_CALC ON MAIN.CALC_TYPE = GEN_CALC.GENERAL_ID WHERE MAIN.COMPANY_ID = '$code_cmpy'");
	}

	function get_cmpy_code($nik)
	{
		$this->db3->db_select();
		$this->db3->select("*");
		$this->db3->from("dbo.u_nik_cmpy");
		$this->db3->where("nik", $nik);

		return $this->db3->get();
	}

	function get_code($cmpy)
	{
		$this->db->where("COMPANY_GLOBAL_ID", $cmpy);

		return $this->db->get("dbo.MCOMPANY");
	}

	public function get_tarif_amount_jakarta($code_cmpy)
	{
		// GET DATA TARIFF AMOUNT
		$this->db->select("A.TO_LOCATION_ID, A.CONTAINER_SIZE_ID, A.TARIFF_AMOUNT, A.CONTAINER_TYPE_ID, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, convert(varchar(11), A.START_DATE, 106) AS START_DATE, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->where("A.COMPANY_ID = '$code_cmpy'");

		return $this->db->get();
	}

	public function get_all_data_container_cost_surabaya()
	{
		// GET ALL DATA CONTAINER COST
		$this->db->distinct();
		$this->db->select("convert(varchar(11), A.START_DATE, 106) AS START_DATE, A.TARIFF_CURRENCY, A.COMPANY_SERVICE_ID, A.CONTAINER_TYPE_ID, D.COMPANY_NAME, E.SERVICE_NAME, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, A.SELLING_SERVICE_ID, B.LOCATION_NAME AS FROM_NAME, C.LOCATION_NAME AS TO_NAME, A.CONTAINER_TYPE_ID, A.FROM_QTY, A.TO_QTY");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->join("dbo.MLOCATION B", "A.FROM_LOCATION_ID = B.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION C", "A.TO_LOCATION_ID = C.LOCATION_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE E", "A.SELLING_SERVICE_ID = E.SELLING_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY_SERVICES F", "A.COMPANY_SERVICE_ID = F.COMPANY_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY D", "F.COMPANY_ID = D.COMPANY_ID", "inner");
		$this->db->where("A.COMPANY_SERVICE_ID = 'CS02'");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 20
	public function get_container_cost_detail_20($from_location, $to_location, $company_id, $container_type)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "inner");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $container_type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '20'");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");
		$this->db->order_by("COST_NAME", "ASC");
		$this->db->order_by("MAIN.FROM_QTY", "ASC");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 40
	public function get_container_cost_detail_40($from_location, $to_location, $company_id, $container_type)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "inner");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $container_type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '40'");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 4h
	public function get_container_cost_detail_4h($from_location, $to_location, $company_id, $container_type)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "inner");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $container_type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '4H'");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");

		return $this->db->get();
	}

	// GET CONTAINER COST DETAIL SIZE 45
	public function get_container_cost_detail_45($from_location, $to_location, $company_id, $container_type)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "inner");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID = ", $container_type);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = '45'");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("GETDATE() BETWEEN MAIN.START_DATE AND MAIN.END_DATE");

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 20
	public function get_container_custom_cost_detail_20($company_id, $type, $custom_location, $custom_line, $container_category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "inner");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $custom_location);
		$this->db->where("A.CUSTOM_LINE_ID = ", $custom_line);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_SIZE_ID = '20'");
		$this->db->where("A.COMPANY_ID", $company_id);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 40
	public function get_container_custom_cost_detail_40($company_id, $type, $custom_location, $custom_line, $container_category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "inner");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $custom_location);
		$this->db->where("A.CUSTOM_LINE_ID = ", $custom_line);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_SIZE_ID = '40'");
		$this->db->where("A.COMPANY_ID", $company_id);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 4h
	public function get_container_custom_cost_detail_4h($company_id, $type, $custom_location, $custom_line, $container_category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "inner");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $custom_location);
		$this->db->where("A.CUSTOM_LINE_ID = ", $custom_line);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_SIZE_ID = '4h'");
		$this->db->where("A.COMPANY_ID", $company_id);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	// GET CONTAINER CUSTOM COST DETAIL SIZE 45
	public function get_container_custom_cost_detail_45($company_id, $type, $custom_location, $custom_line, $container_category)
	{
		$this->db->distinct();
		$this->db->select("A.CONTAINER_SIZE_ID, A.CONTAINER_TYPE_ID, A.CUSTOM_LOCATION_ID, A.COMPANY_ID, A.CUSTOM_LINE_ID, A.COST_ID, B.COST_NAME, A.COST_CURRENCY, A.COST_AMOUNT, A.COST_GROUP_ID, C.GENERAL_DESCRIPTION AS COST_GROUP, A.COST_TYPE_ID, D.GENERAL_DESCRIPTION AS COST_TYPE, A.FROM_QTY, A.TO_QTY, convert(varchar(10), A.START_DATE, 126) AS START_DATE, convert(varchar(10), A.END_DATE, 126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID, A.CONTAINER_CATEGORY_ID, A.SELLING_SERVICE_ID");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MCOST B", "A.COST_ID = B.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID C", "A.COST_GROUP_ID = C.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID D", "A.COST_TYPE_ID = D.GENERAL_ID", "inner");
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $custom_location);
		$this->db->where("A.CUSTOM_LINE_ID = ", $custom_line);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		$this->db->where("A.CONTAINER_SIZE_ID = '45'");
		$this->db->where("A.COMPANY_ID", $company_id);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	// get details of cost
	public function get_detail_of_cost($to_location, $from_location, $type, $company_id, $from_qty, $to_qty)
	{
		$this->db->distinct();
		$this->db->select("A.COMPANY_NAME, B.SERVICE_NAME, MAIN.FROM_LOCATION_ID, E.LOCATION_NAME AS FROM_NAME, MAIN.TO_LOCATION_ID, F.LOCATION_NAME AS TO_NAME, H.GENERAL_DESCRIPTION AS CONTAINER_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOMPANY A", "MAIN.COMPANY_ID = A.COMPANY_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE B", "MAIN.SELLING_SERVICE_ID = B.SELLING_SERVICE_ID", "inner");
		$this->db->join("dbo.MLOCATION E", "MAIN.FROM_LOCATION_ID = E.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION F", "MAIN.TO_LOCATION_ID = F.LOCATION_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID H", "MAIN.CONTAINER_TYPE_ID = H.GENERAL_ID", "inner");
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $type);
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.FROM_QTY = ", $from_qty);
		$this->db->where("MAIN.TO_QTY = ", $to_qty);

		return $this->db->get();
	}

	// get details add cost
	public function get_detail_add_cost($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category)
	{
		$this->db->distinct();
		$this->db->select("MAIN.FROM_LOCATION_ID, E.LOCATION_NAME AS FROM_NAME, MAIN.TO_LOCATION_ID, F.LOCATION_NAME AS TO_NAME, H.GENERAL_DESCRIPTION AS CONTAINER_TYPE, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.CONTAINER_SIZE_ID");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION E", "MAIN.FROM_LOCATION_ID = E.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION F", "MAIN.TO_LOCATION_ID = F.LOCATION_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID H", "MAIN.CONTAINER_TYPE_ID = H.GENERAL_ID", "inner");
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.FROM_QTY = ", $from_qty);
		$this->db->where("MAIN.TO_QTY = ", $to_qty);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $container_size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $container_category);
		$this->db->where("MAIN.COMPANY_ID", $company_id);

		return $this->db->get();
	}

	// get details add cost custom
	public function get_detail_add_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->distinct();
		$this->db->select("MAIN.CUSTOM_LOCATION_ID, CUST_LOCATION.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, MAIN.CUSTOM_LINE_ID, CUST_LINE.GENERAL_DESCRIPTION AS CUSTOM_LINE, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, CONT_TYPE.GENERAL_DESCRIPTION AS CONTAINER_TYPE");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->join("dbo.MGENERAL_ID CUST_LOCATION", "MAIN.CUSTOM_LOCATION_ID = CUST_LOCATION.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID CUST_LINE", "MAIN.CUSTOM_LINE_ID = CUST_LINE.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID CONT_TYPE", "MAIN.CONTAINER_TYPE_ID = CONT_TYPE.GENERAL_ID", "inner");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("MAIN.CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("MAIN.CUSTOM_LINE_ID", $custom_line);
		$this->db->where("MAIN.CONTAINER_SIZE_ID", $container_size);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("MAIN.FROM_QTY", $from_qty);
		$this->db->where("MAIN.TO_QTY", $to_qty);
		$this->db->where("MAIN.START_DATE = ", $start_date);
		$this->db->where("MAIN.END_DATE = ", $end_date);

		return $this->db->get();
	}

	// check details add cost2
	public function check_detail_add_cost($from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.FROM_QTY = ", $from_qty);
		$this->db->where("MAIN.TO_QTY = ", $to_qty);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $container_size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $container_category);
		$this->db->where("MAIN.START_DATE = ", $start_date);
		$this->db->where("MAIN.END_DATE = ", $end_date);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// check details add cost2
	public function check_detail_selling($from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.FROM_QTY = ", $from_qty);
		$this->db->where("MAIN.TO_QTY = ", $to_qty);
		$this->db->where("MAIN.CONTAINER_SIZE_ID = ", $container_size);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID = ", $container_category);
		$this->db->where("MAIN.START_DATE = ", $start_date);
		$this->db->where("MAIN.END_DATE = ", $end_date);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// check details add cost custom
	public function check_detail_add_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("MAIN.CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("MAIN.CUSTOM_LINE_ID", $custom_line);
		$this->db->where("MAIN.CONTAINER_SIZE_ID", $container_size);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("MAIN.FROM_QTY", $from_qty);
		$this->db->where("MAIN.TO_QTY", $to_qty);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// get details of custom cost
	public function get_detail_of_custom_cost($company_id, $type, $custom_location, $custom_line)
	{
		// $this->db->distinct();
		// $this->db->select("A.COMPANY_ID, B.COMPANY_NAME, A.SELLING_SERVICE_ID, C.SERVICE_NAME, A.CUSTOM_LINE_ID, D.GENERAL_DESCRIPTION AS CUSTOM_LINE, A.CUSTOM_LOCATION_ID, E.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, A.CONTAINER_CATEGORY_ID, F.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, A.CONTAINER_TYPE_ID, G.GENERAL_DESCRIPTION AS CONTAINER_TYPE");
		// $this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		// $this->db->join("dbo.MCOMPANY B", "A.COMPANY_ID = B.COMPANY_ID", "inner");
		// $this->db->join("dbo.MSELLING_SERVICE C", "A.SELLING_SERVICE_ID = C.SELLING_SERVICE_ID", "inner");
		// $this->db->join("dbo.MGENERAL_ID D", "A.CUSTOM_LINE_ID = D.GENERAL_ID", "inner");
		// $this->db->join("dbo.MGENERAL_ID E", "A.CUSTOM_LOCATION_ID = E.GENERAL_ID", "inner");
		// $this->db->join("dbo.MGENERAL_ID F", "A.CONTAINER_CATEGORY_ID = F.GENERAL_ID", "inner");
		// $this->db->join("dbo.MGENERAL_ID G", "A.CONTAINER_TYPE_ID = G.GENERAL_ID", "inner");
		// $this->db->where("A.CUSTOM_LOCATION_ID = ", $custom_location);
		// $this->db->where("A.CUSTOM_LINE_ID = ", $custom_line);
		// $this->db->where("A.CONTAINER_TYPE_ID = ", $type);
		// $this->db->where("A.COMPANY_ID = ", $company_id);

		// return $this->db->get();

		return $this->db->query("SELECT MAIN.COMPANY_ID, COMPANY.COMPANY_NAME, MAIN.SELLING_SERVICE_ID, SERVICE.SERVICE_NAME, MAIN.CUSTOM_LINE_ID, CUS_LINE.GENERAL_DESCRIPTION AS CUSTOM_LINE, MAIN.CUSTOM_LOCATION_ID, CUS_LOC.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, MAIN.CONTAINER_CATEGORY_ID, CON_CAT.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, MAIN.CONTAINER_TYPE_ID, CON_TYPE.GENERAL_DESCRIPTION AS CONTAINER_TYPE FROM HSP..MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MSELLING_SERVICE SERVICE ON MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID LEFT JOIN HSP..MGENERAL_ID CUS_LINE ON MAIN.CUSTOM_LINE_ID = CUS_LINE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CUS_LOC ON MAIN.CUSTOM_LOCATION_ID = CUS_LOC.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_CAT ON MAIN.CONTAINER_CATEGORY_ID = CON_CAT.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_TYPE ON MAIN.CONTAINER_TYPE_ID = CON_TYPE.GENERAL_ID WHERE MAIN.CUSTOM_LOCATION_ID = '$custom_location' AND MAIN.CUSTOM_LINE_ID = '$custom_line' AND MAIN.CONTAINER_TYPE_ID = '$type' AND MAIN.COMPANY_ID = '$company_id'");
	}

	// get all services
	public function get_services()
	{
		$this->db->where("FLAG", "1");
		return $this->db->get("dbo.MSELLING_SERVICE");
	}

	function get_services3($code_cmpy)
	{
		return $this->db->query("SELECT MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME FROM HSP..MSELLING_SERVICE_DETAIL MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_SERVICE_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MSELLING_SERVICE SERVICE ON MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID WHERE MAIN.COMPANY_SERVICE_ID = '$code_cmpy'");
	}

	// get all services spec
	public function get_services_spec()
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE");
		$this->db->where("SELLING_SERVICE_ID", "SS01");

		return $this->db->get()->row();
	}

	// get all services
	public function get_services_custom()
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE");
		$this->db->where("SELLING_SERVICE_ID", "SS02");

		return $this->db->get()->row();
	}

	// get all services location
	public function get_services_location()
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE");
		$this->db->where("SELLING_SERVICE_ID", "SS04");

		return $this->db->get()->row();
	}

	// get all services weight
	public function get_services_weight()
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE");
		$this->db->where("SELLING_SERVICE_ID", "SS05");

		return $this->db->get()->row();
	}

	// get all companies
	public function get_companies()
	{
		$this->db->distinct();
		$this->db->select("A.COMPANY_SERVICE_ID, B.COMPANY_NAME");
		$this->db->from("dbo.MCOMPANY_SERVICES A");
		$this->db->join("dbo.MCOMPANY B", "A.COMPANY_ID = B.COMPANY_ID", "inner");

		return $this->db->get();
	}

	// GET ALL DATA CUSTOM JAKARTA
	public function get_all_data_custom_cost_jakarta($code_cmpy)
	{
		$this->db->distinct();
		$this->db->select("A.COMPANY_ID, A.SELLING_SERVICE_ID, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.TARIFF_CURRENCY, A.CUSTOM_LOCATION_ID, B.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, A.CUSTOM_LINE_ID, E.GENERAL_DESCRIPTION AS CUSTOM_LINE, A.CUSTOM_KIND_ID, C.GENERAL_DESCRIPTION AS CUSTOM_KIND, A.CONTAINER_TYPE_ID, D.GENERAL_DESCRIPTION AS CONTAINER_TYPE, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, A.CALC_TYPE, A.INCREMENT_QTY");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MGENERAL_ID B", "A.CUSTOM_LOCATION_ID = B.GENERAL_ID");
		$this->db->join("dbo.MGENERAL_ID C", "A.CUSTOM_KIND_ID = C.GENERAL_ID");
		$this->db->join("dbo.MGENERAL_ID D", "A.CONTAINER_TYPE_ID = D.GENERAL_ID");
		$this->db->join("dbo.MGENERAL_ID E", "A.CUSTOM_LINE_ID = E.GENERAL_ID");
		$this->db->where("A.COMPANY_ID = '$code_cmpy'");

		return $this->db->get();
	}

	// GET TARIF AMOUT CUSTOM JAKARTA
	public function get_tarif_amount_custom_jakarta($code_cmpy)
	{
		$this->db->select("A.CUSTOM_LOCATION_ID, A.CONTAINER_TYPE_ID, A.CONTAINER_SIZE_ID, A.TARIFF_CURRENCY, A.TARIFF_AMOUNT, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->where("A.COMPANY_ID = '$code_cmpy'");

		return $this->db->get();
	}

	// get container size
	public function get_container_size()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CONTAINER_SIZE");

		return $this->db->get();
	}

	// get calculation type
	public function get_calculation_type()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CALC_TYPE");

		return $this->db->get();
	}

	public function get_measurement_unit()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "UNIT_WEIGHT");

		return $this->db->get();
	}

	// get container type
	public function get_container_type()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CONTAINER_TYPE");

		return $this->db->get();
	}

	// get container category
	public function get_container_category()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CONTAINER_CATEGORY");

		return $this->db->get();
	}

	// search from location
	public function get_location($location)
	{
		$this->db->like('LOCATION_NAME', $location);
		$this->db->limit(5);
		return $this->db->get('dbo.MLOCATION');
	}

	// check available data
	function check_data_selling($s_company_service_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id, $d_start_date, $d_end_date, $n_from_qtys, $n_to_qtys)
	{
		$this->db->where('COMPANY_ID = ', $s_company_service_id);
		$this->db->where('SELLING_SERVICE_ID = ', $s_selling_service_id);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $s_container_category_id);
		$this->db->where('FROM_LOCATION_ID = ', $s_from_location_id);
		$this->db->where('CONTAINER_TYPE_ID = ', $s_container_type_id);
		$this->db->where('TO_LOCATION_ID = ', $s_to_location_id);
		$this->db->where('CONTAINER_SIZE_ID = ', $n_container_size_id);
		$this->db->where('START_DATE = ', $d_start_date);
		$this->db->where('END_DATE = ', $d_end_date);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);

		$query = $this->db->get('dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// check available data cost
	function check_data_cost($company_id, $selling_service_id, $container_size, $container_type, $container_category, $from_location, $to_location, $start_date, $end_date, $n_from_qtys, $n_to_qtys, $cost_id)
	{
		$this->db->where('COMPANY_ID = ', $company_id);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service_id);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('START_DATE = ', $start_date);
		$this->db->where('END_DATE = ', $end_date);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);
		$this->db->where('COST_ID = ', $cost_id);

		$query = $this->db->get('dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// check available data cost custom
	function check_data_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $n_from_qtys, $n_to_qtys, $cost_id, $increment_qty, $start_date_post, $end_date_post)
	{
		$this->db->where('COMPANY_ID = ', $company_id);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('CUSTOM_LOCATION_ID = ', $custom_location);
		$this->db->where('CUSTOM_KIND_ID = ', $custom_kind);
		$this->db->where('CUSTOM_LINE_ID = ', $custom_line);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('START_DATE = ', $start_date_post);
		$this->db->where('END_DATE = ', $end_date_post);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);
		$this->db->where('COST_ID = ', $cost_id);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);

		$query = $this->db->get('dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}
	
	function check_data_custom_date($company_service, $selling_service, $size, $type_con, $cat_con, $custom_location, $custom_line, $custom_kind, $from_qty, $to_qty, $increment)
	{
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('COMPANY_ID = ', $company_service);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $cat_con);
		$this->db->where('CUSTOM_LOCATION_ID = ', $custom_location);
		$this->db->where('CUSTOM_KIND_ID = ', $custom_kind);
		$this->db->where('CUSTOM_LINE_ID = ', $custom_line);
		$this->db->where('CONTAINER_TYPE_ID = ', $type_con);
		$this->db->where('CONTAINER_SIZE_ID = ', $size);
		$this->db->where('FROM_QTY = ', $from_qty);
		$this->db->where('TO_QTY = ', $to_qty);
		$this->db->where('INCREMENT_QTY = ', $increment);

		return $this->db->get('dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE');
	}

	// check available data cost custom date
	function check_data_custom_date_cost($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $n_from_qtys, $n_to_qtys, $cost_id, $increment_qty)
	{
		$this->db->where('COMPANY_ID = ', $company_id);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('CUSTOM_LOCATION_ID = ', $custom_location);
		$this->db->where('CUSTOM_KIND_ID = ', $custom_kind);
		$this->db->where('CUSTOM_LINE_ID = ', $custom_line);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);
		$this->db->where('COST_ID = ', $cost_id);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);

		return $this->db->get('dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE');
	}

	// check available data cost date
	function check_data_cost_date($company_id, $selling_service_id, $container_size, $container_type, $container_category, $from_location, $to_location, $n_from_qtys, $n_to_qtys, $cost_id)
	{
		$this->db->where('COMPANY_ID = ', $company_id);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service_id);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);
		$this->db->where('COST_ID = ', $cost_id);

		return $this->db->get('dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE');
		
	}

	// check available data
	function check_data_selling2($s_company_service_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id, $n_from_qtys, $n_to_qtys)
	{
		$this->db->where('COMPANY_ID = ', $s_company_service_id);
		$this->db->where('SELLING_SERVICE_ID = ', $s_selling_service_id);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $s_container_category_id);
		$this->db->where('FROM_LOCATION_ID = ', $s_from_location_id);
		$this->db->where('CONTAINER_TYPE_ID = ', $s_container_type_id);
		$this->db->where('TO_LOCATION_ID = ', $s_to_location_id);
		$this->db->where('CONTAINER_SIZE_ID = ', $n_container_size_id);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);

		return $this->db->get('dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');
	}

	function valid_date($s_company_service_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id, $n_from_qtys, $n_to_qtys, $d_start_date, $d_end_date)
	{
		$this->db->where('COMPANY_ID = ', $s_company_service_id);
		$this->db->where('SELLING_SERVICE_ID = ', $s_selling_service_id);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $s_container_category_id);
		$this->db->where('FROM_LOCATION_ID = ', $s_from_location_id);
		$this->db->where('CONTAINER_TYPE_ID = ', $s_container_type_id);
		$this->db->where('TO_LOCATION_ID = ', $s_to_location_id);
		$this->db->where('CONTAINER_SIZE_ID = ', $n_container_size_id);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);
		$this->db->where("'$d_start_date' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$d_end_date' BETWEEN START_DATE AND END_DATE");

		return $this->db->get('dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE');
	}

	function valid_date_cost($company_id, $selling_service_id, $container_size, $container_type, $container_category, $from_location, $to_location, $n_from_qtys, $n_to_qtys, $cost_id, $start_date_post, $end_date_post)
	{
		$this->db->where('COMPANY_ID = ', $company_id);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service_id);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);
		$this->db->where('COST_ID = ', $cost_id);
		$this->db->where("'$start_date_post' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date_post' BETWEEN START_DATE AND END_DATE");

		return $this->db->get('dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE');
	}

	function valid_date_cost_custom($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $n_from_qtys, $n_to_qtys, $cost_id, $increment_qty, $start_date_post, $end_date_post)
	{
		$this->db->where('COMPANY_ID = ', $company_id);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('CUSTOM_LOCATION_ID = ', $custom_location);
		$this->db->where('CUSTOM_KIND_ID = ', $custom_kind);
		$this->db->where('CUSTOM_LINE_ID = ', $custom_line);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('FROM_QTY = ', $n_from_qtys);
		$this->db->where('TO_QTY = ', $n_to_qtys);
		$this->db->where('COST_ID = ', $cost_id);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);
		$this->db->where("'$start_date_post' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date_post' BETWEEN START_DATE AND END_DATE");

		return $this->db->get('dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE');
	}

	// get start and end date db
	function check_data_date($s_company_service_id, $s_selling_service_id, $n_container_size_id, $s_container_type_id, $s_container_category_id, $s_from_location_id, $s_to_location_id, $n_from_qtys, $n_to_qtys)
	{
		$this->db->select("CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->where('MAIN.COMPANY_SERVICE_ID = ', $s_company_service_id);
		$this->db->where('MAIN.SELLING_SERVICE_ID = ', $s_selling_service_id);
		$this->db->where('MAIN.CONTAINER_CATEGORY_ID = ', $s_container_category_id);
		$this->db->where('MAIN.FROM_LOCATION_ID = ', $s_from_location_id);
		$this->db->where('MAIN.CONTAINER_TYPE_ID = ', $s_container_type_id);
		$this->db->where('MAIN.TO_LOCATION_ID = ', $s_to_location_id);
		$this->db->where('MAIN.CONTAINER_SIZE_ID = ', $n_container_size_id);
		$this->db->where('MAIN.FROM_QTY = ', $n_from_qtys);
		$this->db->where('MAIN.TO_QTY = ', $n_to_qtys);

		return $this->db->get();
	}

	// get cost
	public function get_cost()
	{
		return $this->db->query("select * from HSP..MCOST where COST_KIND = 'S'");
	}

	// get cost
	public function get_cost_trc()
	{
		return $this->db->query("select * from HSP..MCOST where COST_KIND = 'S' AND COST_GROUP = 'TRC'");
	}

	// get cost
	public function get_cost_customs()
	{
		return $this->db->query("select * from HSP..MCOST where COST_KIND = 'S' AND COST_GROUP = 'CCL'");
	}

	// get cost type
	public function get_cost_type()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "COST_TYPE");

		return $this->db->get();
	}

	// get cost group
	public function get_cost_group()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "COST_GROUP");

		return $this->db->get();
	}

	// add container cost
	public function add_container_cost($data, $table)
	{
		$this->db->insert($table, $data);
	}

	// get company
	public function get_company()
	{
		return $this->db->get("dbo.MCOMPANY");
	}

	// get custom location
	public function get_custom_location()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CUSTOMS_LOCATION");

		return $this->db->get();
	}

	// get custom kind
	public function get_custom_kind()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CUSTOMS_KIND");

		return $this->db->get();
	}

	// get custom line
	public function get_custom_line()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CUSTOMS_LINE");

		return $this->db->get();
	}

	// check company
	public function check_company($company_service)
	{
		if (isset($company_service)) {
			$this->db->distinct();
			$this->db->select("COMPANY_ID");
			$this->db->from("dbo.MCOMPANY_SERVICES");
			$this->db->where("COMPANY_SERVICE_ID", $company_service);
			
			return $this->db->get()->row()->COMPANY_ID;
		}
	}

	// add container custom 
	public function add_container_custom($data, $table)
	{
		$this->db->insert($table, $data);
	}

	// check data container
	function check_data_container($size, $from_location, $to_location, $type_con, $cat_con, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->select("A.CONTAINER_SIZE_ID, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, A.CONTAINER_TYPE_ID, A.CONTAINER_CATEGORY_ID");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->where("A.CONTAINER_SIZE_ID", $size);
		$this->db->where("A.FROM_LOCATION_ID", $from_location);
		$this->db->where("A.TO_LOCATION_ID", $to_location);
		$this->db->where("A.CONTAINER_TYPE_ID", $type_con);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $cat_con);
		$this->db->where("A.FROM_QTY", $from_qty);
		$this->db->where("A.TO_QTY", $to_qty);
		$this->db->where("A.START_DATE", $start_date);
		$this->db->where("A.END_DATE", $end_date);

		return $this->db->get();
	}

	// check data container custom
	function check_data_custom($size, $custom_location, $custom_line, $custom_kind, $type_con, $con_cat, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->select("A.CUSTOM_LOCATION_ID, A.CONTAINER_SIZE_ID, A.CUSTOM_LINE_ID, A.CONTAINER_TYPE_ID");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
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

		return $this->db->get();
	}

	// check data add custom
	function check_data_add_custom($company_service, $selling_service, $size, $type_con, $cat_con, $custom_location, $custom_line, $custom_kind, $start_date, $end_date, $n_from_qtys, $n_to_qtys, $increment)
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->where("A.COMPANY_ID = ", $company_service);
		$this->db->where("A.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $custom_location);
		$this->db->where("A.CUSTOM_KIND_ID = ", $custom_kind);
		$this->db->where("A.CUSTOM_LINE_ID = ", $custom_line);
		$this->db->where("A.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type_con);
		$this->db->where("A.CONTAINER_CATEGORY_ID = ", $cat_con);
		$this->db->where("A.INCREMENT_QTY = ", $increment);
		$this->db->where("A.START_DATE = ", $start_date);
		$this->db->where("A.END_DATE = ", $end_date);
		$this->db->where("A.FROM_QTY = ", $n_from_qtys);
		$this->db->where("A.TO_QTY = ", $n_to_qtys);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	// check valid date data custom
	function valid_date_custom($company_service, $selling_service, $size, $type_con, $cat_con, $custom_location, $custom_line, $custom_kind, $n_from_qtys, $n_to_qtys, $increment, $start_date, $end_date)
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->where("A.SELLING_SERVICE_ID = ", $selling_service);
		$this->db->where("A.CUSTOM_LOCATION_ID = ", $custom_location);
		$this->db->where("A.COMPANY_ID = ", $company_service);
		$this->db->where("A.CUSTOM_KIND_ID = ", $custom_kind);
		$this->db->where("A.CUSTOM_LINE_ID = ", $custom_line);
		$this->db->where("A.CONTAINER_SIZE_ID = ", $size);
		$this->db->where("A.CONTAINER_TYPE_ID = ", $type_con);
		$this->db->where("A.CONTAINER_CATEGORY_ID = ", $cat_con);
		$this->db->where("A.INCREMENT_QTY = ", $increment);
		$this->db->where("A.FROM_QTY = ", $n_from_qtys);
		$this->db->where("A.TO_QTY = ", $n_to_qtys);
		$this->db->where("'$start_date' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date' BETWEEN START_DATE AND END_DATE");

		return $this->db->get();
	}

	// get detail container cost edit
	function get_detail_edit_container_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)
	{
		$this->db->distinct();
		$this->db->select("COST_FROM.LOCATION_NAME AS FROM_LOCATION, COST_TO.LOCATION_NAME AS TO_LOCATION, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.CONTAINER_SIZE_ID, COST_TYPE.GENERAL_DESCRIPTION AS TYPE_COST, COST.COST_NAME, MAIN.COST_AMOUNT, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION COST_FROM", "MAIN.FROM_LOCATION_ID = COST_FROM.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION COST_TO", "MAIN.TO_LOCATION_ID = COST_TO.LOCATION_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_TYPE", "MAIN.CONTAINER_TYPE_ID = COST_TYPE.GENERAL_ID", "inner");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.COST_ID", $cost_id);
		$this->db->where("MAIN.CONTAINER_SIZE_ID", $container_size);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("MAIN.FROM_QTY", $from_qty);
		$this->db->where("MAIN.TO_QTY", $to_qty);
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);

		return $this->db->get();
	}

	// get detail customs cost edit
	function get_detail_edit_customs_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)
	{
		$this->db->distinct();
		$this->db->select("COST_LOCATION.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, COST_KIND.GENERAL_DESCRIPTION AS CUSTOM_KIND, COST_LINE.GENERAL_DESCRIPTION AS CUSTOM_LINE, MAIN.CONTAINER_SIZE_ID, CON_TYPE.GENERAL_DESCRIPTION AS CONTAINER_TYPE, COST.COST_NAME, MAIN.COST_AMOUNT, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->join("MGENERAL_ID COST_LOCATION", "MAIN.CUSTOM_LOCATION_ID = COST_LOCATION.GENERAL_ID", "join");
		$this->db->join("MGENERAL_ID COST_KIND", "MAIN.CUSTOM_KIND_ID = COST_KIND.GENERAL_ID", "join");
		$this->db->join("MGENERAL_ID COST_LINE", "MAIN.CUSTOM_LINE_ID = COST_LINE.GENERAL_ID", "join");
		$this->db->join("MGENERAL_ID CON_TYPE", "MAIN.CONTAINER_TYPE_ID = CON_TYPE.GENERAL_ID", "join");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.COST_ID", $cost_id);
		$this->db->where("MAIN.CONTAINER_SIZE_ID", $container_size);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("MAIN.FROM_QTY", $from_qty);
		$this->db->where("MAIN.TO_QTY", $to_qty);
		$this->db->where("MAIN.CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("MAIN.CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("MAIN.CUSTOM_LINE_ID", $custom_line);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);

		return $this->db->get();
	}

	// get data cost edit
	function get_data_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.COST_ID", $cost_id);
		$this->db->where("MAIN.CONTAINER_SIZE_ID", $container_size);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("MAIN.FROM_QTY", $from_qty);
		$this->db->where("MAIN.TO_QTY", $to_qty);
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);

		return $this->db->get();
	}

	// update cost
	function update_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $table, $data)
	{
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// update cost
	function update_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date, $table, $data)
	{
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// check full data container cost
	function check_full_data($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)
	{
		$this->db->distinct();
		$this->db->select("CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE, COST_TYPE_ID, COST_GROUP_ID, CALC_TYPE, APROVAL_STATUS, INCREMENT_QTY, COST_CURRENCY");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		return $this->db->get();
	}

	// check full data container cost
	function check_full_data_cost_customs($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date)
	{
		$this->db->distinct();
		$this->db->select("COST_TYPE_ID, COST_GROUP_ID, CALC_TYPE, APPROVAL_STATUS, INCREMENT_QTY, COST_CURRENCY, COST_AMOUNT");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE");
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		return $this->db->get();
	}

	// check end date
	function check_end_date($company_service, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location)
	{
		$this->db->distinct();
		$this->db->select("CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get()->row()->END_DATE;
	}

	// check cost amount
	function check_cost_amount($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location)
	{
		$this->db->distinct();
		$this->db->select("COST_AMOUNT");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get()->row()->COST_AMOUNT;
	} 

	// check date
	function check_date($company_service, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $start_date, $end_date)
	{
		$this->db->distinct();
		$this->db->select("CONVERT(char(10), END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MCOST_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		// return $this->db->get()->row()->COMPANY_ID;

		// $end_datedb = $this->db->get()->row()->END_DATE;
		
		// if ($end_datedb == $end_date) {
		// 	return 1;
		// } else {
		// 	return 0;
		// }

		return $this->db->get()->row()->END_DATE;
	}

	// add first cost
	function add_first_cost($table, $first_record)
	{
		$this->db->insert($table, $first_record);
	}

	// add first custom
	function add_first_custom($table, $first_record)
	{
		$this->db->insert($table, $first_record);
	}

	// move record to history
	function move_record_cost($table, $move_record)
	{
		// move record to history
		$this->db->insert($table, $move_record);
	}

	// move record to history
	function move_record_custom($table, $move_record)
	{
		// move record to history
		$this->db->insert($table, $move_record);
	}

	function delete_last_record($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $cost_type, $cost_group, $calc_type, $increment_qty, $aproval_status, $cost_currency, $start_date, $end_date, $table)
	{
		// delete record before
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_last_record_selling($company_id, $selling_service, $container_size, $container_type, $container_category, $from_qty, $to_qty, $from_location, $to_location, $calc_type, $increment_qty, $approval_status, $tariff_currency, $start_date, $end_date, $table)
	{
		// delete record before
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_last_record_custom($company_id, $selling_service, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $calc_type, $increment_qty, $approval_status, $tariff_currency, $start_date, $end_date,$table)
	{
		// delete record before
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_last_record_customs_cost($company_id, $selling_service, $cost_id, $container_size, $container_type, $container_category, $from_qty, $to_qty, $custom_location, $custom_kind, $custom_line, $start_date, $end_date, $table)
	{
		// delete record before
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_data_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)
	{
		$this->db->select("CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE, TARIFF_AMOUNT, TARIFF_CURRENCY, CALC_TYPE, INCREMENT_QTY, APPROVAL_STATUS");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("COMPANY_ID", $company_id);

		return $this->db->get();
	}

	function get_data_selling2($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $start_date, $end_date)
	{
		$this->db->select("CONVERT(char(10), START_DATE,126) AS START_DATE, CONVERT(char(10), END_DATE,126) AS END_DATE, TARIFF_AMOUNT, TARIFF_CURRENCY, CALC_TYPE, INCREMENT_QTY, APPROVAL_STATUS");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("COMPANY_ID", $company_id);

		return $this->db->get();
	}

	function get_data_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->select("CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.TARIFF_AMOUNT, MAIN.TARIFF_CURRENCY, MAIN.CALC_TYPE, MAIN.INCREMENT_QTY, MAIN.APPROVAL_STATUS");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->where("MAIN.COMPANY_ID", $company_id);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("MAIN.CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("MAIN.CUSTOM_LINE_ID", $custom_line);
		$this->db->where("MAIN.CONTAINER_SIZE_ID", $container_size);
		$this->db->where("MAIN.CONTAINER_TYPE_ID", $container_type);
		$this->db->where("MAIN.CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("MAIN.FROM_QTY", $from_qty);
		$this->db->where("MAIN.TO_QTY", $to_qty);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);

		return $this->db->get();
	}

	function update_selling($company_id, $from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $table, $data)
	{
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("COMPANY_ID", $company_id);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_customs($company_id, $selling_service, $custom_location, $custom_kind, $custom_line, $container_size, $container_type, $container_category, $from_qty, $to_qty, $start_date, $table, $data)
	{
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("START_DATE", $start_date);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function get_all_data_container_print_jakarta()
	{
		// GET ALL DATA CONTAINER COST
		$this->db->distinct();
		$this->db->select("CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.TARIFF_CURRENCY, A.COMPANY_SERVICE_ID, A.CONTAINER_TYPE_ID, D.COMPANY_NAME, E.SERVICE_NAME, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, A.SELLING_SERVICE_ID, B.LOCATION_NAME AS FROM_NAME, C.LOCATION_NAME AS TO_NAME, A.CONTAINER_TYPE_ID, A.FROM_QTY, A.TO_QTY, H.GENERAL_DESCRIPTION AS CALC, A.INCREMENT_QTY, A.CONTAINER_CATEGORY_ID");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->join("dbo.MLOCATION B", "A.FROM_LOCATION_ID = B.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION C", "A.TO_LOCATION_ID = C.LOCATION_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE E", "A.SELLING_SERVICE_ID = E.SELLING_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY_SERVICES F", "A.COMPANY_SERVICE_ID = F.COMPANY_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY D", "F.COMPANY_ID = D.COMPANY_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID H", "A.CALC_TYPE = H.GENERAL_ID", "inner");
		$this->db->where("A.COMPANY_SERVICE_ID = 'CS01'");
		$this->db->where("A.FROM_QTY = '1'");
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	public function get_tarif_amount_print_jakarta()
	{
		// GET DATA TARIFF AMOUNT
		$this->db->select("A.TO_LOCATION_ID, A.CONTAINER_SIZE_ID, A.TARIFF_AMOUNT, A.CONTAINER_TYPE_ID, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, convert(varchar(11), A.START_DATE, 106) AS START_DATE, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE A");
		$this->db->where("A.COMPANY_SERVICE_ID = 'CS01'");
		$this->db->where("A.FROM_QTY = '1'");
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	// GET ALL DATA CUSTOM JAKARTA PRINT
	public function get_all_data_custom_print_jakarta()
	{
		$this->db->distinct();
		$this->db->select("A.COMPANY_SERVICE_ID, A.SELLING_SERVICE_ID, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.TARIFF_CURRENCY, A.CUSTOM_LOCATION_ID, B.GENERAL_DESCRIPTION AS CUSTOM_LOCATION, A.CUSTOM_LINE_ID, E.GENERAL_DESCRIPTION AS CUSTOM_LINE, A.CUSTOM_KIND_ID, C.GENERAL_DESCRIPTION AS CUSTOM_KIND, A.CONTAINER_TYPE_ID, D.GENERAL_DESCRIPTION AS CONTAINER_TYPE, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, A.CALC_TYPE, A.INCREMENT_QTY");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->join("dbo.MGENERAL_ID B", "A.CUSTOM_LOCATION_ID = B.GENERAL_ID");
		$this->db->join("dbo.MGENERAL_ID C", "A.CUSTOM_KIND_ID = C.GENERAL_ID");
		$this->db->join("dbo.MGENERAL_ID D", "A.CONTAINER_TYPE_ID = D.GENERAL_ID");
		$this->db->join("dbo.MGENERAL_ID E", "A.CUSTOM_LINE_ID = E.GENERAL_ID");
		$this->db->where("A.COMPANY_SERVICE_ID = 'CS01'");
		$this->db->where("A.FROM_QTY = '1'");
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	// GET TARIF AMOUT CUSTOM JAKARTA PRINT
	public function get_tarif_amount_custom_print_jakarta()
	{
		$this->db->select("A.CUSTOM_LOCATION_ID, A.CONTAINER_TYPE_ID, A.CONTAINER_SIZE_ID, A.TARIFF_CURRENCY, A.TARIFF_AMOUNT, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.CUSTOM_LOCATION_ID, A.CUSTOM_LINE_ID, A.CUSTOM_KIND_ID");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE A");
		$this->db->where("A.COMPANY_SERVICE_ID = 'CS01'");
		$this->db->where("A.FROM_QTY = '1'");
		$this->db->where("GETDATE() BETWEEN A.START_DATE AND A.END_DATE");

		return $this->db->get();
	}

	public function get_truck_id()
	{
		$this->db->select("GENERAL_ID, GENERAL_DESCRIPTION");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID = 'TRUCK_KIND'");

		return $this->db->get();
	}

	public function check_data_location($company_service, $selling_service, $distance, $distance_per_litre, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		return $this->db->get("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE");
	}

	public function check_data_date_location($company_service, $selling_service, $distance, $distance_per_litre, $truck_id, $from_location, $to_location, $increment_qty)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		return $this->db->get("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE");
	}

	function valid_date_selling_location($company_service, $selling_service, $distance, $distance_per_litre, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("'$start_date' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date' BETWEEN START_DATE AND END_DATE");

		return $this->db->get("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE");
	}

	function get_data_location_jakarta()
	{
		$this->db->select("FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID,
MAIN.TO_LOCATION_ID, MAIN.TARIFF_AMOUNT, MAIN.DISTANCE, MAIN.DISTANCE_PER_LITRE, 
TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, 
CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.TRUCK_ID, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, H.GENERAL_DESCRIPTION AS CALC, MAIN.INCREMENT_QTY, MAIN.CALC_TYPE");
		$this->db->from("MSELLING_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->join("MGENERAL_ID TRUCK", "MAIN.TRUCK_ID = TRUCK.GENERAL_ID", "inner");
		$this->db->join("MGENERAL_ID H", "MAIN.CALC_TYPE = H.GENERAL_ID", "inner");

		return $this->db->get();
	}

	function get_detail_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.TARIFF_AMOUNT, MAIN.DISTANCE, MAIN.DISTANCE_PER_LITRE, TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.TRUCK_ID");
		$this->db->from("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID TRUCK", "MAIN.TRUCK_ID = TRUCK.GENERAL_ID", "inner");
		$this->db->join("dbo.MCOMPANY_SERVICES A", "MAIN.COMPANY_SERVICE_ID = A.COMPANY_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY COMPANY", "A.COMPANY_ID = COMPANY.COMPANY_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE SERVICE", "MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID", "inner");
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		return $this->db->get();
	}

	function get_data_selling_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty)
	{
		$this->db->select("MAIN.TARIFF_AMOUNT, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.TARIFF_CURRENCY, MAIN.TARIFF_AMOUNT");
		$this->db->from("MSELLING_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		
		return $this->db->get();
	}

	function update_selling_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty, $table, $data)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_last_record_selling_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $start_date, $end_date, $increment_qty, $table)
	{
		// delete record before
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// check full data container cost
	function check_full_data_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $distance, $distance_per_litre, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("TARIFF_CURRENCY, TARIFF_AMOUNT");
		$this->db->from("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("DISTANCE", $distance);
		$this->db->where("DISTANCE_PER_LITRE", $distance_per_litre);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		return $this->db->get();
	}

	function get_cost_detail_location($cost_id)
	{
		$this->db->select("COST_TYPE, COST_GROUP");
		$this->db->from("dbo.MCOST");
		$this->db->where("COST_ID", $cost_id);

		return $this->db->get();
	}

	function check_data_cost_location($company_service, $selling_service, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty, $cost_id)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("COST_ID", $cost_id);

		return $this->db->get("dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE");
	}

	public function check_data_date_cost_location($company_service, $selling_service, $cost_id, $truck_id, $from_location, $to_location, $increment_qty)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		return $this->db->get("dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE");
	}

	function valid_date_cost_location($company_service, $selling_service, $cost_id, $truck_id, $from_location, $to_location, $start_date, $end_date, $increment_qty)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("'$start_date' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date' BETWEEN START_DATE AND END_DATE");

		return $this->db->get("dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE");
	}

	// GET LOCATION COST DETAIL
	public function get_location_cost_detail($from_location, $to_location, $truck_id, $start_date, $end_date, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, convert(varchar(10), MAIN.START_DATE, 126) AS START_DATE, convert(varchar(10), MAIN.END_DATE, 126) AS END_DATE, MAIN.TRUCK_ID, MAIN.INCREMENT_QTY");
		$this->db->from("dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "inner");
		$this->db->where("MAIN.FROM_LOCATION_ID = ", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID = ", $to_location);

		return $this->db->get();
	}

	function get_detail_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $start_date, $end_date, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.COST_AMOUNT, TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.TRUCK_ID, COST.COST_NAME");
		$this->db->from("dbo.MCOST_SERVICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID TRUCK", "MAIN.TRUCK_ID = TRUCK.GENERAL_ID", "inner");
		$this->db->join("dbo.MCOMPANY_SERVICES A", "MAIN.COMPANY_SERVICE_ID = A.COMPANY_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY COMPANY", "A.COMPANY_ID = COMPANY.COMPANY_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE SERVICE", "MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		return $this->db->get();
	}

	function update_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $start_date, $end_date, $increment_qty, $table, $data)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// check full data location cost
	function check_full_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MCOSt_SERVICE_LOCATION_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		return $this->db->get();
	}

	function delete_last_record_cost_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $cost_id, $start_date, $end_date, $increment_qty, $table)
	{
		// delete record before
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("COST_ID", $cost_id);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// check available data
	function check_data_weight($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight, $to_weight, $increment_qty)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('START_DATE = ', $start_date);
		$this->db->where('END_DATE = ', $end_date);
		$this->db->where('FROM_WEIGHT = ', $from_weight);
		$this->db->where('TO_WEIGHT = ', $to_weight);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);

		$query = $this->db->get('dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// check available data
	function check_data_weight2($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $increment_qty)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('FROM_WEIGHT = ', $from_weight);
		$this->db->where('TO_WEIGHT = ', $to_weight);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);

		return $this->db->get('dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE');
	}

	function valid_date_weight($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight, $to_weight, $increment_qty)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('FROM_WEIGHT = ', $from_weight);
		$this->db->where('TO_WEIGHT = ', $to_weight);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);
		$this->db->where("'$start_date' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date' BETWEEN START_DATE AND END_DATE");

		return $this->db->get('dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE');
	}

	function get_data_weight_jakarta()
	{
		$this->db->select("FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.TARIFF_AMOUNT, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, H.GENERAL_DESCRIPTION AS CALC, MAIN.INCREMENT_QTY, MAIN.CALC_TYPE, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT, MAIN.CALC_TYPE");
		$this->db->from("MSELLING_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->join("MGENERAL_ID H", "MAIN.CALC_TYPE = H.GENERAL_ID", "inner");

		return $this->db->get();
	}

	function get_detail_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT");
		$this->db->from("dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MCOMPANY_SERVICES A", "MAIN.COMPANY_SERVICE_ID = A.COMPANY_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY COMPANY", "A.COMPANY_ID = COMPANY.COMPANY_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE SERVICE", "MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID", "inner");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);

		return $this->db->get();
	}

	function get_detail_weight2($from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT");
		$this->db->from("dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MCOMPANY_SERVICES A", "MAIN.COMPANY_SERVICE_ID = A.COMPANY_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY COMPANY", "A.COMPANY_ID = COMPANY.COMPANY_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE SERVICE", "MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID", "inner");
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);

		return $this->db->get();
	}

	function get_data_selling_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)
	{
		$this->db->select("MAIN.TARIFF_AMOUNT, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE");
		$this->db->from("MSELLING_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);
		
		return $this->db->get();
	}

	function update_selling_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $table, $data)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}


	function check_detail_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("MAIN.TARIFF_CURRENCY, MAIN.TARIFF_AMOUNT");
		$this->db->from("dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);

		return $this->db->get();
	}

	function delete_last_record_weight_selling($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $table)
	{
		// delete record before
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// check available data
	function check_data_weight_cost($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight, $to_weight, $increment_qty, $cost_id)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('START_DATE = ', $start_date);
		$this->db->where('END_DATE = ', $end_date);
		$this->db->where('FROM_WEIGHT = ', $from_weight);
		$this->db->where('TO_WEIGHT = ', $to_weight);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);
		$this->db->where('COST_ID = ', $cost_id);

		$query = $this->db->get('dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// check available data
	function check_data_weight_cost2($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $increment_qty, $cost_id)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('TO_LOCATION_ID = ', $to_location);;
		$this->db->where('FROM_WEIGHT = ', $from_weight);
		$this->db->where('TO_WEIGHT = ', $to_weight);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);
		$this->db->where('COST_ID = ', $cost_id);

		return $this->db->get('dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE');
	}

	function check_cost($cost_id)
	{
		$this->db->select("COST_TYPE, COST_GROUP");
		$this->db->from("dbo.MCOST");
		$this->db->where("COST_ID", $cost_id);

		return $this->db->get();
	}

	function valid_date_weight_cost($company_service, $selling_service, $from_location, $to_location, $start_date, $end_date, $from_weight, $to_weight, $increment_qty, $cost_id)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('COSt_ID = ', $cost_id);
		$this->db->where('FROM_WEIGHT = ', $from_weight);
		$this->db->where('TO_WEIGHT = ', $to_weight);
		$this->db->where('INCREMENT_QTY = ', $increment_qty);
		$this->db->where("'$start_date' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date' BETWEEN START_DATE AND END_DATE");

		return $this->db->get('dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE');
	}

	function get_cost_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty)
	{
		$this->db->distinct();
		$this->db->select("COST.COST_NAME, MAIN.COST_AMOUNT, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GRP.GENERAL_DESCRIPTION AS COST_GROUP, MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.COST_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, convert(varchar(10), MAIN.START_DATE, 126) AS START_DATE, convert(varchar(10), MAIN.END_DATE, 126) AS END_DATE, MAIN.INCREMENT_QTY, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT");
		$this->db->from("dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_TP", "MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID COST_GRP", "MAIN.COST_GROUP_ID = COST_GRP.GENERAL_ID", "inner");
		$this->db->where("MAIN.COMPANY_SERVICE_ID", $company_service);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);
		$this->db->where("MAIN.INCREMENT_QTY", $increment_qty);
		$this->db->where("MAIN.FROM_WEIGHT", $from_weight);
		$this->db->where("MAIN.TO_WEIGHT", $to_weight);

		return $this->db->get();
	}

	function get_detail_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id)
	{
		$this->db->distinct();
		$this->db->select("FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, CONVERT(char(10), MAIN.END_DATE,126) AS END_DATE, MAIN.FROM_WEIGHT, MAIN.TO_WEIGHT, COST.COST_NAME, MAIN.COST_AMOUNT");
		$this->db->from("dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM_LOC", "MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO_LOC", "MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID", "inner");
		$this->db->join("dbo.MCOST COST", "MAIN.COST_ID = COST.COST_ID", "inner");
		$this->db->where("MAIN.COMPANY_SERVICE_ID", $company_service);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);
		$this->db->where("MAIN.INCREMENT_QTY", $increment_qty);
		$this->db->where("MAIN.FROM_WEIGHT", $from_weight);
		$this->db->where("MAIN.TO_WEIGHT", $to_weight);
		$this->db->where("MAIN.COST_ID", $cost_id);

		return $this->db->get();
	}

	function update_cost_weight($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id, $table, $data)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);
		$this->db->where("COST_ID", $cost_id);

		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_detail_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id)
	{
		$this->db->distinct();
		$this->db->select("MAIN.COST_CURRENCY, MAIN.COST_AMOUNT, MAIN.CALC_TYPE");
		$this->db->from("dbo.MCOST_SERVICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->where("MAIN.COMPANY_SERVICE_ID", $company_service);
		$this->db->where("MAIN.SELLING_SERVICE_ID", $selling_service);
		$this->db->where("MAIN.FROM_LOCATION_ID", $from_location);
		$this->db->where("MAIN.TO_LOCATION_ID", $to_location);
		$this->db->where("MAIN.START_DATE", $start_date);
		$this->db->where("MAIN.END_DATE", $end_date);
		$this->db->where("MAIN.INCREMENT_QTY", $increment_qty);
		$this->db->where("MAIN.FROM_WEIGHT", $from_weight);
		$this->db->where("MAIN.TO_WEIGHT", $to_weight);
		$this->db->where("MAIN.COST_ID", $cost_id);

		return $this->db->get();
	}

	function delete_last_record_weight_cost($company_service, $selling_service, $from_location, $to_location, $from_weight, $to_weight, $start_date, $end_date, $increment_qty, $cost_id, $table)
	{
		// delete record before
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("START_DATE", $start_date);
		$this->db->where("END_DATE", $end_date);
		$this->db->where("INCREMENT_QTY", $increment_qty);
		$this->db->where("FROM_WEIGHT", $from_weight);
		$this->db->where("TO_WEIGHT", $to_weight);
		$this->db->where("COST_ID", $cost_id);

		if (!$this->db->delete($table)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// get all services spec
	public function get_services_para($id)
	{
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE");
		$this->db->where("SELLING_SERVICE_ID", $id);

		return $this->db->get()->row();
	}

	// get calculation type
	public function get_general_para($id)
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", $id);

		return $this->db->get();
	}

	// check available data
	function check_data_selling_ocean($company_service, $selling_service, $container_size, $container_type, $container_category, $charge_id, $from_location, $to_location, $start_date, $end_date, $from_qty, $to_qty)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('START_DATE = ', $start_date);
		$this->db->where('END_DATE = ', $end_date);
		$this->db->where('FROM_QTY = ', $from_qty);
		$this->db->where('TO_QTY = ', $to_qty);
		$this->db->where('CHARGE_ID = ', $charge_id);

		$query = $this->db->get('dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// check available data
	function check_data_selling_ocean2($company_service, $selling_service, $container_size, $container_type, $container_category, $charge_id, $from_location, $to_location, $from_qty, $to_qty)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('FROM_QTY = ', $from_qty);
		$this->db->where('TO_QTY = ', $to_qty);
		$this->db->where('CHARGE_ID = ', $charge_id);

		return $this->db->get('dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE');
	}

	function valid_date_ocean($company_service, $selling_service, $container_size, $container_type, $container_category, $charge_id, $from_location, $to_location, $from_qty, $to_qty, $start_date, $end_date)
	{
		$this->db->where('COMPANY_SERVICE_ID = ', $company_service);
		$this->db->where('SELLING_SERVICE_ID = ', $selling_service);
		$this->db->where('CONTAINER_CATEGORY_ID = ', $container_category);
		$this->db->where('FROM_LOCATION_ID = ', $from_location);
		$this->db->where('CONTAINER_TYPE_ID = ', $container_type);
		$this->db->where('TO_LOCATION_ID = ', $to_location);
		$this->db->where('CONTAINER_SIZE_ID = ', $container_size);
		$this->db->where('FROM_QTY = ', $from_qty);
		$this->db->where('TO_QTY = ', $to_qty);
		$this->db->where('CHARGE_ID = ', $charge_id);
		$this->db->where("'$start_date' BETWEEN START_DATE AND END_DATE");
		$this->db->or_where("'$end_date' BETWEEN START_DATE AND END_DATE");

		return $this->db->get('dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE');
	}

	public function get_data_ocean_jakarta()
	{
		$this->db->distinct();
		$this->db->select("CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.TARIFF_CURRENCY, A.COMPANY_SERVICE_ID, D.COMPANY_NAME, E.SERVICE_NAME, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, A.SELLING_SERVICE_ID, B.LOCATION_NAME AS FROM_NAME, C.LOCATION_NAME AS TO_NAME, A.CONTAINER_TYPE_ID, A.FROM_QTY, A.TO_QTY, H.GENERAL_DESCRIPTION AS CALC, A.INCREMENT_QTY, A.CONTAINER_CATEGORY_ID, A.CHARGE_ID, CHARGE.GENERAL_DESCRIPTION AS CHARGE_NAME");
		$this->db->from("dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE A");
		$this->db->join("dbo.MLOCATION B", "A.FROM_LOCATION_ID = B.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION C", "A.TO_LOCATION_ID = C.LOCATION_ID", "inner");
		$this->db->join("dbo.MSELLING_SERVICE E", "A.SELLING_SERVICE_ID = E.SELLING_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY_SERVICES F", "A.COMPANY_SERVICE_ID = F.COMPANY_SERVICE_ID", "inner");
		$this->db->join("dbo.MCOMPANY D", "F.COMPANY_ID = D.COMPANY_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID H", "A.CALC_TYPE = H.GENERAL_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID CHARGE", "A.CHARGE_ID = CHARGE.GENERAL_ID", "inner");
		$this->db->where("A.COMPANY_SERVICE_ID = 'CS01'");

		return $this->db->get();
	}

	public function get_tarif_amount_ocean_jakarta()
	{
		// GET DATA TARIFF AMOUNT
		$this->db->select("A.TO_LOCATION_ID, A.CONTAINER_SIZE_ID, A.TARIFF_AMOUNT, A.CONTAINER_TYPE_ID, A.CONTAINER_CATEGORY_ID, A.FROM_QTY, A.TO_QTY, convert(varchar(11), A.START_DATE, 106) AS START_DATE, CONVERT(char(10), A.START_DATE,126) AS START_DATE, CONVERT(char(10), A.END_DATE,126) AS END_DATE, A.CHARGE_ID");
		$this->db->from("dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE A");
		$this->db->where("A.COMPANY_SERVICE_ID = 'CS01'");

		return $this->db->get();
	}

	// check data ocean
	function check_data_ocean($size, $from_location, $to_location, $type_con, $cat_con, $from_qty, $to_qty, $start_date, $end_date, $charge_id)
	{
		$this->db->select("A.CONTAINER_SIZE_ID, A.FROM_LOCATION_ID, A.TO_LOCATION_ID, A.CONTAINER_TYPE_ID, A.CONTAINER_CATEGORY_ID");
		$this->db->from("dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE A");
		$this->db->where("A.CONTAINER_SIZE_ID", $size);
		$this->db->where("A.FROM_LOCATION_ID", $from_location);
		$this->db->where("A.TO_LOCATION_ID", $to_location);
		$this->db->where("A.CONTAINER_TYPE_ID", $type_con);
		$this->db->where("A.CONTAINER_CATEGORY_ID", $cat_con);
		$this->db->where("A.FROM_QTY", $from_qty);
		$this->db->where("A.TO_QTY", $to_qty);
		$this->db->where("A.START_DATE", $start_date);
		$this->db->where("A.END_DATE", $end_date);
		$this->db->where("A.CHARGE_ID", $charge_id);

		return $this->db->get();
	}

	function check_limit_trucking($company_service_id, $selling_service_id, $container_size, $container_type, $container_category, $from_location, $to_location)
	{
		$this->db->select("*");
		$this->db->from("dbo.MLIMITATIONS_PRICE_CONTAINER_ATTRIBUTE");
		$this->db->where("COMPANY_ID", $company_service_id);
		$this->db->where("SELLING_SERVICE_ID", $selling_service_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get();
	}

	function check_limit_customs($company_service, $selling_service, $custom_location, $custom_kind, $custom_line, $size, $type_con, $cat_con)
	{
		$this->db->select("*");
		$this->db->from("dbo.MLIMITATIONS_PRICE_CONTAINER_CUSTOMS_ATTRIBUTE");
		$this->db->where("COMPANY_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("CONTAINER_SIZE_ID", $size);
		$this->db->where("CONTAINER_TYPE_ID", $type_con);
		$this->db->where("CONTAINER_CATEGORY_ID", $cat_con);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);

		return $this->db->get();
	}

	function check_limit_location($company_service, $selling_service, $from_location, $to_location, $truck_id)
	{
		$this->db->select("*");
		$this->db->from("dbo.MLIMITATIONS_PRICE_LOCATION_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("TRUCK_ID", $truck_id);

		return $this->db->get();
	}

	function check_limit_weight($company_service, $selling_service, $from_location, $to_location)
	{
		$this->db->select("*");
		$this->db->from("dbo.MLIMITATIONS_PRICE_WEIGHT_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get();
	}

	function check_limit_ocean($company_service, $selling_service, $container_size, $container_type, $container_category, $from_location, $to_location, $charge_id)
	{
		$this->db->select("*");
		$this->db->from("dbo.MLIMITATIONS_PRICE_OCEAN_FREIGHT_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CHARGE_ID", $charge_id);

		return $this->db->get();
	}

	function get_date_customs($company_id, $custom_location, $custom_kind, $custom_line, $container_type, $container_category, $from_qty, $to_qty, $start_date, $end_date)
	{
		return $this->db->query("SELECT CONVERT(CHAR(10), START_DATE, 126) AS START_DATE, CONVERT(CHAR(10), END_DATE, 126) AS END_DATE FROM MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE WHERE CUSTOM_LOCATION_ID = '$custom_location' AND CUSTOM_LINE_ID = '$custom_line' AND CUSTOM_KIND_ID = '$custom_kind' AND FROM_QTY = '$from_qty' AND TO_QTY = '$to_qty' AND START_DATE = '$start_date' AND END_DATE = '$end_date' AND CONTAINER_TYPE_ID = '$container_type' AND CONTAINER_CATEGORY_ID = '$container_category' AND COMPANY_ID = '$company_id'");
	}
}