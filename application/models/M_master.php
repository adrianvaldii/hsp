<?php

class M_Master extends CI_Model {
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
	
	public function get_generalid()
	{
		return $this->db->get('dbo.MGENERAL_ID');
	}

	public function get_classification()
	{
		$this->db->distinct();
		$this->db->select("CLASSIFICATION_ID");
		$this->db->from("dbo.MGENERAL_ID");
		return $this->db->get();
	}


	function add_generalid($data, $table)
	{
		$this->db->insert($table, $data);
	}

	function check_truck($truck_id)
	{
		return $this->db->query("SELECT * FROM HSP..MTRUCK WHERE TRUCK_ID = '$truck_id'");
	}

	function check_data_cost($cost_name, $cost_kind)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST WHERE COST_NAME = '$cost_name' AND COST_KIND = '$cost_kind'");
	}

	function get_data_truck()
	{
		return $this->db->query("SELECT TRUCK_ID, BPKB_NUMBER, KIR_NUMBER, SHARE_OPERATION_COST, FLAG, CONVERT(CHAR(11), STNK_EXPIRED, 106) AS STNK_EXPIRED, CONVERT(CHAR(11), KIR_EXPIRED, 106) AS KIR_EXPIRED, COMPANY.COMPANY_NAME FROM HSP..MTRUCK MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID");
	}

	function get_detail_mutation($transaction_id)
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT *, CONVERT(CHAR(10), MAIN.TRANSACTION_DATE, 126) AS TRANS_DATE, NIK.Nm_lengkap AS PIC_NAME FROM HSP..TRBANK_STATEMENT MAIN LEFT JOIN HSP..MBANK_PIC BANK_PIC ON MAIN.BANK_ID = BANK_PIC.BANK_ID LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK ON BANK_PIC.PIC_ID = NIK.Nik WHERE MAIN.TRANSACTION_ID = '$transaction_id'");
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
	
	function get_max_cost()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(COST_ID), 2, 3) as id FROM HSP..MCOST");
	}

	function get_all_cost_param($cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST WHERE COST_ID = '$cost_id'");
	}

	function check_coa_truck($truck_id, $cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..GLCHART_TRUCK WHERE TRUCK_ID = '$truck_id' AND COST_ID = '$cost_id'");
	}

	function update_cost_model($table, $data, $cost_id)
	{
		$this->db->where("COST_ID", $cost_id);
		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_data_mutation()
	{
		return $this->db->query("SELECT MAIN.TRANSACTION_ID, MAIN.BANK_ID, MAIN.WORK_ORDER_NUMBER, CONVERT(CHAR(10), MAIN.TRANSACTION_DATE, 126) AS TRANSACTION_DATE, MAIN.DESCRIPTION_1, MAIN.ORIGINAL_AMOUNT, MAIN.ORIGINAL_CURRENCY, MAIN.HOME_DEBIT, MAIN.HOME_CREDIT, MAIN.IS_DONE, MAIN.USER_ID, CONVERT(CHAR(10), MAIN.USER_DATE, 126) AS USER_DATE, NIK.Nm_lengkap AS NAME FROM HSP..TRBANK_STATEMENT MAIN LEFT JOIN pltapol..u_nik NIK ON MAIN.USER_ID = NIK.Nik");
	}

	function get_data_truck2($truck_id)
	{
		return $this->db->query("SELECT TRUCK_ID, COMPANY_ID, BPKB_NUMBER, KIR_NUMBER, SHARE_OPERATION_COST, FLAG, CONVERT(CHAR(11), STNK_EXPIRED, 106) AS STNK_EXPIRED, CONVERT(CHAR(11), KIR_EXPIRED, 106) AS KIR_EXPIRED FROM HSP..MTRUCK WHERE TRUCK_ID = '$truck_id'");
	}

	function update_mut($table, $data, $mutation_id)
	{
		$this->db->where("TRANSACTION_ID", $mutation_id);

		$this->db->update($table, $data);
	}

	function get_max_driver()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(DRIVER_ID), 3, 4) as id FROM HSP..MDRIVER");
	}

	function get_data_driver()
	{
		return $this->db->query("SELECT *, CONVERT(CHAR(10), BORN_OF_DATE, 126) AS BORN_DATE, CONVERT(CHAR(10), LICENSE_DRIVER_EXPIRED, 126) AS LICENSE_DATE FROM HSP..MDRIVER");
	}

	function get_data_driver_param($driver_id)
	{
		return $this->db->query("SELECT *, CONVERT(CHAR(10), BORN_OF_DATE, 126) AS BORN_DATE, CONVERT(CHAR(10), LICENSE_DRIVER_EXPIRED, 126) AS LICENSE_DATE FROM HSP..MDRIVER WHERE DRIVER_ID = '$driver_id'");	
	}

	function update_driver($table, $data_driver, $driver_id)
	{
		$this->db->where("DRIVER_ID", $driver_id);

		if (!$this->db->update($table, $data_driver)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_truck($table, $data_truck, $truck_id)
	{
		$this->db->where("TRUCK_ID", $truck_id);

		if (!$this->db->update($table, $data_truck)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_cost_type()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'COST_TYPE'");
	}

	function get_cost_group()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'COST_GROUP'");
	}

	function check_chassis($chassis_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCHASSIS WHERE CHASSIS_ID = '$chassis_id'");
	}

	function get_data_chassis()
	{
		return $this->db->query("SELECT CHASSIS_ID, KIR_NUMBER, SHARE_OPERATION_COST, FLAG, CONVERT(CHAR(11), KIR_EXPIRED, 106) AS KIR_EXPIRED, COMPANY.COMPANY_NAME FROM HSP..MCHASSIS MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID");
	}

	function get_data_chassis2($chassis_id)
	{
		return $this->db->query("SELECT MAIN.COMPANY_ID, MAIN.CHASSIS_ID, MAIN.KIR_NUMBER, MAIN.SHARE_OPERATION_COST, MAIN.FLAG, CONVERT(CHAR(11), MAIN.KIR_EXPIRED, 106) AS KIR_EXPIRED, COMPANY.COMPANY_NAME FROM HSP..MCHASSIS MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID WHERE CHASSIS_ID = '$chassis_id'");
	}

	function check_coa_chassis($chassis_id, $cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..GLCHART_CHASSIS WHERE CHASSIS_ID = '$chassis_id' AND COST_ID = '$cost_id'");
	}

	function get_all_cost()
	{
		return $this->db->query("SELECT *, COST_TP.GENERAL_DESCRIPTION AS COST_TPS, COST_GP.GENERAL_DESCRIPTION AS COST_GPS FROM HSP..MCOST MAIN LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE = COST_TP.GENERAL_ID AND COST_TP.CLASSIFICATION_ID = 'COST_TYPE' LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP = COST_GP.GENERAL_ID AND COST_GP.CLASSIFICATION_ID = 'COST_GROUP'");
	}

	function update_chassis($table, $data_chassis, $chassis_id)
	{
		$this->db->where("CHASSIS_ID", $chassis_id);

		if (!$this->db->update($table, $data_chassis)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_template($template_id)
	{
		return $this->db->query("SELECT * FROM HSP..MTEMPLATE WHERE TEMPLATE_ID = '$template_id'");
	}

	function update_template($table, $data_term, $template_id)
	{
		$this->db->where("TEMPLATE_ID", $template_id);

		$this->db->update($table, $data_term);
	}

	function get_cmpy_code($nik)
	{
		// $this->db3->db_select();
		// $this->db3->select("*");
		// $this->db3->from("dbo.u_nik_cmpy");
		// $this->db3->where("nik", $nik);

		// return $this->db3->get();

		return $this->db->query("SELECT * FROM pltapol..u_nik_cmpy where Nik = '$nik'");
	}

	function get_code($cmpy)
	{
		$this->db->where("COMPANY_GLOBAL_ID", $cmpy);

		return $this->db->get("dbo.MCOMPANY");
	}

	function get_trucking()
	{
		$this->db->distinct();
		$this->db->select("MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, FROM.LOCATION_NAME_SHORT AS FROM_NAME, TO.LOCATION_NAME_SHORT AS TO_NAME, MAIN.TARIFF_CURRENCY, MAIN.FLOOR_PRICE, MAIN.MARKET_PRICE, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_CONTAINER_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM", "MAIN.FROM_LOCATION_ID = FROM.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO", "MAIN.TO_LOCATION_ID = TO.LOCATION_ID", "inner");

		return $this->db->get();
	}

	function get_data_coa_truck()
	{
		return $this->db->query("SELECT *, COST.COST_NAME FROM HSP..GLCHART_TRUCK MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID");
	}

	function get_data_coa_chassis()
	{
		return $this->db->query("SELECT *, COST.COST_NAME FROM HSP..GLCHART_CHASSIS MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID");	
	}

	function get_data_coa_truck_param($truck_id, $cost_id)
	{
		return $this->db->query("SELECT *, COST.COST_NAME FROM HSP..GLCHART_TRUCK MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.TRUCK_ID = '$truck_id' AND MAIN.COST_ID = '$cost_id'");
	}

	function get_data_coa_chassis_param($chassis_id, $cost_id)
	{
		return $this->db->query("SELECT *, COST.COST_NAME FROM HSP..GLCHART_CHASSIS MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID WHERE MAIN.CHASSIS_ID = '$chassis_id' AND MAIN.COST_ID = '$cost_id'");
	}

	function update_coa_truck($table, $data_insert, $truck_id, $cost_id)
	{
		$this->db->where("TRUCK_ID", $truck_id);
		$this->db->where("COST_ID", $cost_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_coa_chassis($table, $data_insert, $chassis_id, $cost_id)
	{
		$this->db->where("CHASSIS_ID", $chassis_id);
		$this->db->where("COST_ID", $cost_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}	
	}

	function get_selling_trucking()
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_ATTRIBUTE");
		$this->db->where("FROM_QTY = 1");
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
		return $this->db->get();
	}

	function get_customs()
	{
		$this->db->distinct();
		$this->db->select("MAIN.COMPANY_ID, MAIN.SELLING_SERVICE_ID, MAIN.COMPANY_ID, MAIN.CUSTOM_LOCATION_ID, MAIN.CUSTOM_LINE_ID, MAIN.CUSTOM_KIND_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, FROM.GENERAL_DESCRIPTION AS FROM_NAME, MAIN.TARIFF_CURRENCY, MAIN.FLOOR_PRICE, MAIN.MARKET_PRICE, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_CONTAINER_CUSTOMS_ATTRIBUTE MAIN");
		$this->db->join("dbo.MGENERAL_ID FROM", "MAIN.CUSTOM_LOCATION_ID = FROM.GENERAL_ID", "inner");

		return $this->db->get();
	}

	function get_gl_account()
	{
		return $this->db->query("SELECT * FROM eHsj..glchart");
	}

	function get_selling_customs()
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_CONTAINER_CUSTOMS_ATTRIBUTE");
		$this->db->where("FROM_QTY = 1");
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
		return $this->db->get();
	}

	function get_location()
	{
		$this->db->distinct();
		$this->db->select("MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID,  FROM.LOCATION_NAME_SHORT AS FROM_NAME, TO.LOCATION_NAME_SHORT AS TO_NAME, MAIN.TARIFF_CURRENCY, MAIN.FLOOR_PRICE, MAIN.MARKET_PRICE, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, MAIN.TRUCK_ID, TRUCK.GENERAL_DESCRIPTION AS TRUCK_NAME");
		$this->db->from("dbo.MLIMITATIONS_PRICE_LOCATION_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM", "MAIN.FROM_LOCATION_ID = FROM.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO", "MAIN.TO_LOCATION_ID = TO.LOCATION_ID", "inner");
		$this->db->join("dbo.MGENERAL_ID TRUCK", "MAIN.TRUCK_ID = TRUCK.GENERAL_ID", "inner");

		return $this->db->get();
	}

	function get_selling_location()
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_LOCATION_ATTRIBUTE");
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
		return $this->db->get();
	}

	function get_weight()
	{
		$this->db->distinct();
		$this->db->select("MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID,  FROM.LOCATION_NAME_SHORT AS FROM_NAME, TO.LOCATION_NAME_SHORT AS TO_NAME, MAIN.TARIFF_CURRENCY, MAIN.FLOOR_PRICE, MAIN.MARKET_PRICE, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_WEIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM", "MAIN.FROM_LOCATION_ID = FROM.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO", "MAIN.TO_LOCATION_ID = TO.LOCATION_ID", "inner");

		return $this->db->get();
	}

	function get_selling_weight()
	{
		$this->db->distinct();
		$this->db->select("COMPANY_SERVICE_ID, FROM_LOCATION_ID, TO_LOCATION_ID, MAX(TARIFF_AMOUNT) AS TARIFF_AMOUNT");
		$this->db->from("dbo.MSELLING_SERVICE_WEIGHT_ATTRIBUTE");
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
		$this->db->group_by("COMPANY_SERVICE_ID");
		$this->db->group_by("FROM_LOCATION_ID");
		$this->db->group_by("TO_LOCATION_ID");
		return $this->db->get();
	}

	function get_ocean()
	{
		$this->db->distinct();
		$this->db->select("MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, FROM.LOCATION_NAME_SHORT AS FROM_NAME, TO.LOCATION_NAME_SHORT AS TO_NAME, MAIN.TARIFF_CURRENCY, MAIN.FLOOR_PRICE, MAIN.MARKET_PRICE, CONVERT(char(10), MAIN.START_DATE,126) AS START_DATE, MAIN.CHARGE_ID");
		$this->db->from("dbo.MLIMITATIONS_PRICE_OCEAN_FREIGHT_ATTRIBUTE MAIN");
		$this->db->join("dbo.MLOCATION FROM", "MAIN.FROM_LOCATION_ID = FROM.LOCATION_ID", "inner");
		$this->db->join("dbo.MLOCATION TO", "MAIN.TO_LOCATION_ID = TO.LOCATION_ID", "inner");

		return $this->db->get();
	}

	function get_selling_ocean()
	{
		$this->db->distinct();
		$this->db->select("*");
		$this->db->from("dbo.MSELLING_SERVICE_OCEAN_FREIGHT_ATTRIBUTE");
		$this->db->where("FROM_QTY = 1");
		$this->db->where("GETDATE() BETWEEN START_DATE AND END_DATE");
		return $this->db->get();
	}

	function update_selling($from_location, $to_location, $container_type, $from_qty, $to_qty, $container_size, $container_category, $table, $data)
	{
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		$this->db->update($table, $data);
	}

	function update_limit($company_service, $selling_service, $from_location, $to_location, $container_size, $container_type, $container_category,$table, $data_limitation)
	{
		$this->db->where("COMPANY_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);

		if (!$this->db->update($table, $data_limitation)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_limit_customs($company_service, $selling_service, $custom_location, $custom_line, $custom_kind, $container_size, $container_type, $container_category, $table, $data_limitation)
	{
		$this->db->where("COMPANY_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);

		if (!$this->db->update($table, $data_limitation)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_limit_location($company_service, $selling_service, $from_location, $to_location, $truck_id, $table, $data_limitation)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("TRUCK_ID", $truck_id);;

		if (!$this->db->update($table, $data_limitation)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_limit_weight($company_service, $selling_service, $from_location, $to_location, $table, $data_limitation)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		if (!$this->db->update($table, $data_limitation)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function update_limit_ocean($company_service, $selling_service, $from_location, $to_location, $container_size, $container_type, $container_category, $charge_id, $table, $data_limitation)
	{
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("SELLING_SERVICE_ID", $selling_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CHARGE_ID", $charge_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);

		if (!$this->db->update($table, $data_limitation)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_update_data($company_service, $from_location, $to_location, $container_size, $container_type, $container_category)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE, CONVERT(char(10), START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_CONTAINER_ATTRIBUTE");
		$this->db->where("COMPANY_ID", $company_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);

		return $this->db->get();
	}

	function check_update_data_customs($company_service, $custom_location, $custom_line, $custom_kind, $container_size, $container_type, $container_category)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE, CONVERT(char(10), START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_CONTAINER_CUSTOMS_ATTRIBUTE");
		$this->db->where("COMPANY_ID", $company_service);
		$this->db->where("CUSTOM_LOCATION_ID", $custom_location);
		$this->db->where("CUSTOM_LINE_ID", $custom_line);
		$this->db->where("CUSTOM_KIND_ID", $custom_kind);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);

		return $this->db->get();
	}

	function check_update_data_location($company_service, $from_location, $to_location, $truck_id)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE, CONVERT(char(10), START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_LOCATION_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("TRUCK_ID", $truck_id);;

		return $this->db->get();
	}

	function check_update_data_weight($company_service, $from_location, $to_location)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE, CONVERT(char(10), START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_WEIGHT_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);

		return $this->db->get();
	}

	function check_update_data_ocean($company_service, $from_location, $to_location, $container_size, $container_type, $container_category, $charge_id)
	{
		$this->db->select("TARIFF_CURRENCY, FLOOR_PRICE, MARKET_PRICE, CONVERT(char(10), START_DATE,126) AS START_DATE");
		$this->db->from("dbo.MLIMITATIONS_PRICE_OCEAN_FREIGHT_ATTRIBUTE");
		$this->db->where("COMPANY_SERVICE_ID", $company_service);
		$this->db->where("FROM_LOCATION_ID", $from_location);
		$this->db->where("TO_LOCATION_ID", $to_location);
		$this->db->where("CHARGE_ID", $charge_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size);
		$this->db->where("CONTAINER_TYPE_ID", $container_type);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category);

		return $this->db->get();
	}

	function test()
	{
		return $this->db->get("dbo.MTEMPLATE");
	}

	function get_data_hoarding()
	{
		return $this->db->query("SELECT * FROM HSP..MHOARDING");
	}

	function get_max_hoarding()
	{
		return $this->db->query("SELECT MAX(HOARDING_ID) as HOARDING_ID FROM HSP..MHOARDING MAIN");
	}

	function get_max_vendor()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(VENDOR_ID), 3, 5) as id FROM HSP..MVENDOR");
	}

	function get_data_vendor()
	{
		return $this->db->query("SELECT * FROM HSP..MVENDOR");
	}

	function get_data_vendor_param($vendor_id)
	{
		return $this->db->query("SELECT * FROM HSP..MVENDOR WHERE VENDOR_ID = '$vendor_id'");
	}

	function update_vendor($table, $data_insert, $vendor_id)
	{
		$this->db->where("VENDOR_ID", $vendor_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_max_vendor_contract()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(CONTRACT_NO), 3, 5) AS id FROM HSP..MVENDOR_CONTRACT");
	}

	function get_vendor_kind()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'VENDOR_KIND'");
	}

	function get_data_vendor_contract()
	{
		return $this->db->query("SELECT MAIN.CONTRACT_NO, COMPANY.COMPANY_NAME, VENDOR.VENDOR_NAME, VEN_KIND.GENERAL_DESCRIPTION AS VENDOR_KIND, MAIN.REFERENCE_NUMBER, MAIN.VENDOR_PIC, CONVERT(CHAR(10), MAIN.CONTRACT_DATE, 126) AS CONTRACT_DATE, CONVERT(CHAR(10), MAIN.VALID_FROM_DATE, 126) AS FROM_DATE, CONVERT(CHAR(10), MAIN.VALID_TO_DATE, 126) AS TO_DATE FROM HSP..MVENDOR_CONTRACT MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MVENDOR VENDOR ON MAIN.VENDOR_ID = VENDOR.VENDOR_ID LEFT JOIN HSP..MGENERAL_ID VEN_KIND ON MAIN.VENDOR_KIND = VEN_KIND.GENERAL_ID WHERE VEN_KIND.CLASSIFICATION_ID = 'VENDOR_KIND'");
	}

	function get_data_vendor_contract_param($contract_no)
	{
		return $this->db->query("SELECT MAIN.COMPANY_ID, MAIN.VENDOR_ID, MAIN.VENDOR_KIND, MAIN.CONTRACT_NO, COMPANY.COMPANY_NAME, VENDOR.VENDOR_NAME, VEN_KIND.GENERAL_DESCRIPTION AS VENDOR_KINDS, MAIN.REFERENCE_NUMBER, MAIN.VENDOR_PIC, CONVERT(CHAR(10), MAIN.CONTRACT_DATE, 126) AS CONTRACT_DATE, CONVERT(CHAR(10), MAIN.VALID_FROM_DATE, 126) AS FROM_DATE, CONVERT(CHAR(10), MAIN.VALID_TO_DATE, 126) AS TO_DATE FROM HSP..MVENDOR_CONTRACT MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MVENDOR VENDOR ON MAIN.VENDOR_ID = VENDOR.VENDOR_ID LEFT JOIN HSP..MGENERAL_ID VEN_KIND ON MAIN.VENDOR_KIND = VEN_KIND.GENERAL_ID WHERE VEN_KIND.CLASSIFICATION_ID = 'VENDOR_KIND' AND MAIN.CONTRACT_NO = '$contract_no'");	
	}

	function update_vendor_contract($table, $data_insert, $contract_no)
	{
		$this->db->where("CONTRACT_NO", $contract_no);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// get container size
	public function get_container_size()
	{
		$this->db->select("*");
		$this->db->from("dbo.MGENERAL_ID");
		$this->db->where("CLASSIFICATION_ID", "CONTAINER_SIZE");

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

	function get_location_master()
	{
		return $this->db->query("SELECT * FROM HSP..MLOCATION ORDER BY LOCATION_NAME ASC");
	}

	function get_data_vendor_truck()
	{
		return $this->db->query("SELECT MAIN.CONTRACT_NO, MAIN.COMPANY_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.BUYING_CURRENCY, MAIN.BUYING_RATE, VENDOR.VENDOR_NAME, COMPANY.COMPANY_NAME, CON_TYPE.GENERAL_DESCRIPTION AS CONTAINER_TYPE, CON_CAT.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME FROM HSP..MVENDOR_SERVICE_CONTAINER_BUYING_RATE MAIN LEFT JOIN HSP..MVENDOR_CONTRACT CONTRACT ON MAIN.CONTRACT_NO = CONTRACT.CONTRACT_NO LEFT JOIN HSP..MVENDOR VENDOR ON CONTRACT.VENDOR_ID = VENDOR.VENDOR_ID LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MGENERAL_ID CON_TYPE ON MAIN.CONTAINER_TYPE_ID = CON_TYPE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_CAT ON MAIN.CONTAINER_CATEGORY_ID = CON_CAT.GENERAL_ID LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE CON_TYPE.CLASSIFICATION_ID = 'CONTAINER_TYPE' AND CON_CAT.CLASSIFICATION_ID = 'CONTAINER_CATEGORY'");
	}

	function get_data_vendor_truck_param($contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)
	{
		return $this->db->query("SELECT MAIN.CONTRACT_NO, MAIN.COMPANY_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.BUYING_CURRENCY, MAIN.BUYING_RATE, VENDOR.VENDOR_NAME, COMPANY.COMPANY_NAME, CON_TYPE.GENERAL_DESCRIPTION AS CONTAINER_TYPE, CON_CAT.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME FROM HSP..MVENDOR_SERVICE_CONTAINER_BUYING_RATE MAIN LEFT JOIN HSP..MVENDOR_CONTRACT CONTRACT ON MAIN.CONTRACT_NO = CONTRACT.CONTRACT_NO LEFT JOIN HSP..MVENDOR VENDOR ON CONTRACT.VENDOR_ID = VENDOR.VENDOR_ID LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MGENERAL_ID CON_TYPE ON MAIN.CONTAINER_TYPE_ID = CON_TYPE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_CAT ON MAIN.CONTAINER_CATEGORY_ID = CON_CAT.GENERAL_ID LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE CON_TYPE.CLASSIFICATION_ID = 'CONTAINER_TYPE' AND CON_CAT.CLASSIFICATION_ID = 'CONTAINER_CATEGORY' AND MAIN.CONTRACT_NO = '$contract_no' AND MAIN.COMPANY_ID = '$company_id' AND MAIN.CONTAINER_SIZE_ID = '$container_size_id' AND MAIN.CONTAINER_TYPE_ID = '$container_type_id' AND MAIN.CONTAINER_CATEGORY_ID = '$container_category_id' AND MAIN.FROM_QTY = '$from_qty' AND MAIN.TO_QTY = '$to_qty' AND MAIN.FROM_LOCATION_ID = '$from_location_id' AND MAIN.TO_LOCATION_ID = '$to_location_id'");
	}

	function check_vendor_truck($contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id, $buying_currency, $buying_rate)
	{
		return $this->db->query("SELECT * FROM HSP..MVENDOR_SERVICE_CONTAINER_BUYING_RATE MAIN WHERE MAIN.CONTRACT_NO = '$contract_no' AND MAIN.COMPANY_ID = '$company_id' AND MAIN.CONTAINER_SIZE_ID = '$container_size_id' AND MAIN.CONTAINER_TYPE_ID = '$container_type_id' AND MAIN.CONTAINER_CATEGORY_ID = '$container_category_id' AND MAIN.FROM_QTY = '$from_qty' AND MAIN.TO_QTY = '$to_qty' AND MAIN.FROM_LOCATION_ID = '$from_location_id' AND MAIN.TO_LOCATION_ID = '$to_location_id' AND MAIN.BUYING_CURRENCY = '$buying_currency' AND MAIN.BUYING_RATE = '$buying_rate'");
	}

	function update_vendor_truck($table, $data_insert, $contract_no, $company_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)
	{
		$this->db->where("CONTRACT_NO", $contract_no);
		$this->db->where("COMPANY_ID", $company_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size_id);
		$this->db->where("CONTAINER_TYPE_ID", $container_type_id);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category_id);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location_id);
		$this->db->where("TO_LOCATION_ID", $to_location_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_service_adt()
	{
		return $this->db->query("SELECT * FROM HSP..MSELLING_SERVICE WHERE SERVICE_TYPE = 'ADT' AND FLAG = '1'");
	}

	function get_data_currency()
	{
		return $this->db->query("SELECT * FROM HSP..MCURRENCY");
	}

	function get_service_adt_selling_rate()
	{
		return $this->db->query("SELECT * FROM HSP..MSELLING_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MSELLING_SERVICE SERVICE ON MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID");
	}

	function get_service_adt_selling_rate_param($selling_service_id)
	{
		return $this->db->query("SELECT * FROM HSP..MSELLING_SERVICE_ADDITIONAL_CONTAINER_ATTRIBUTE MAIN LEFT JOIN HSP..MSELLING_SERVICE SERVICE ON MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID WHERE MAIN.SELLING_SERVICE_ID = '$selling_service_id'");	
	}

	function update_adt_service($table, $data_insert, $selling_service_id)
	{
		$this->db->where("SELLING_SERVICE_ID", $selling_service_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_max_competitor()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(COMPETITOR_ID), 3, 5) AS id FROM HSP..MCOMPETITOR");
	}

	function get_data_competitor()
	{
		return $this->db->query("SELECT * FROM HSP..MCOMPETITOR");
	}

	function get_data_competitor_param($competitor_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCOMPETITOR WHERE COMPETITOR_ID = '$competitor_id'");
	}

	function get_data_competitor_param2($competitor_name)
	{
		return $this->db->query("SELECT * FROM HSP..MCOMPETITOR WHERE COMPETITOR_NAME = '$competitor_name'");
	}

	function update_competitor($table, $data_insert, $competitor_id)
	{
		$this->db->where("COMPETITOR_ID", $competitor_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_max_competitor_compare()
	{
		return $this->db->query("SELECT SUBSTRING(MAX(COMPARE_ID), 3, 5) AS id FROM HSP..MCOMPETITOR_COMPARE");
	}

	function get_data_competitor_compare()
	{
		return $this->db->query("SELECT *, CONVERT(CHAR(10), MAIN.VALID_FROM_DATE, 126) AS FROM_DATE, CONVERT(CHAR(10), MAIN.VALID_TO_DATE, 126) AS TO_DATE FROM HSP..MCOMPETITOR_COMPARE MAIN LEFT JOIN HSP..MCOMPETITOR COMPETITOR ON MAIN.COMPETITOR_ID = COMPETITOR.COMPETITOR_ID");
	}

	function get_data_competitor_compare_param($compare_id)
	{
		return $this->db->query("SELECT *, CONVERT(CHAR(10), MAIN.VALID_FROM_DATE, 126) AS FROM_DATE, CONVERT(CHAR(10), MAIN.VALID_TO_DATE, 126) AS TO_DATE FROM HSP..MCOMPETITOR_COMPARE MAIN LEFT JOIN HSP..MCOMPETITOR COMPETITOR ON MAIN.COMPETITOR_ID = COMPETITOR.COMPETITOR_ID WHERE COMPARE_ID = '$compare_id'");
	}

	function update_competitor_compare($table, $data_insert, $compare_id)
	{
		$this->db->where("COMPARE_ID", $compare_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_competitor_rate()
	{
		return $this->db->query("SELECT MAIN.COMPARE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.BUYING_CURRENCY, MAIN.BUYING_RATE, CON_TYPE.GENERAL_DESCRIPTION AS CONTAINER_TYPE, CON_CAT.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, COMPETITOR.COMPETITOR_NAME FROM HSP..MCOMPETITOR_SERVICE_CONTAINER_BUYING_RATE MAIN LEFT JOIN HSP..MCOMPETITOR_COMPARE COMPARE ON MAIN.COMPARE_ID = COMPARE.COMPARE_ID LEFT JOIN HSP..MCOMPETITOR COMPETITOR ON COMPARE.COMPETITOR_ID = COMPETITOR.COMPETITOR_ID LEFT JOIN HSP..MGENERAL_ID CON_TYPE ON MAIN.CONTAINER_TYPE_ID = CON_TYPE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_CAT ON MAIN.CONTAINER_CATEGORY_ID = CON_CAT.GENERAL_ID LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE CON_TYPE.CLASSIFICATION_ID = 'CONTAINER_TYPE' AND CON_CAT.CLASSIFICATION_ID = 'CONTAINER_CATEGORY'");
	}

	function get_competitor_rate_param($compare_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)
	{
		return $this->db->query("SELECT MAIN.COMPARE_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, MAIN.FROM_LOCATION_ID, MAIN.TO_LOCATION_ID, MAIN.BUYING_CURRENCY, MAIN.BUYING_RATE, CON_TYPE.GENERAL_DESCRIPTION AS CONTAINER_TYPE, CON_CAT.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, FROM_LOC.LOCATION_NAME AS FROM_NAME, TO_LOC.LOCATION_NAME AS TO_NAME, COMPETITOR.COMPETITOR_NAME FROM HSP..MCOMPETITOR_SERVICE_CONTAINER_BUYING_RATE MAIN LEFT JOIN HSP..MCOMPETITOR_COMPARE COMPARE ON MAIN.COMPARE_ID = COMPARE.COMPARE_ID LEFT JOIN HSP..MCOMPETITOR COMPETITOR ON COMPARE.COMPETITOR_ID = COMPETITOR.COMPETITOR_ID LEFT JOIN HSP..MGENERAL_ID CON_TYPE ON MAIN.CONTAINER_TYPE_ID = CON_TYPE.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_CAT ON MAIN.CONTAINER_CATEGORY_ID = CON_CAT.GENERAL_ID LEFT JOIN HSP..MLOCATION FROM_LOC ON MAIN.FROM_LOCATION_ID = FROM_LOC.LOCATION_ID LEFT JOIN HSP..MLOCATION TO_LOC ON MAIN.TO_LOCATION_ID = TO_LOC.LOCATION_ID WHERE CON_TYPE.CLASSIFICATION_ID = 'CONTAINER_TYPE' AND CON_CAT.CLASSIFICATION_ID = 'CONTAINER_CATEGORY' AND MAIN.COMPARE_ID = '$compare_id' AND MAIN.CONTAINER_SIZE_ID = '$container_size_id' AND MAIN.CONTAINER_TYPE_ID = '$container_type_id' AND MAIN.CONTAINER_CATEGORY_ID = '$container_category_id' AND MAIN.FROM_QTY = '$from_qty' AND MAIN.TO_QTY = '$to_qty' AND MAIN.FROM_LOCATION_ID = '$from_location_id' AND MAIN.TO_LOCATION_ID = '$to_location_id'");
	}

	function update_competitor_rate($table, $data_insert, $compare_id, $container_size_id, $container_type_id, $container_category_id, $from_qty, $to_qty, $from_location_id, $to_location_id)
	{
		$this->db->where("COMPARE_ID", $compare_id);
		$this->db->where("CONTAINER_SIZE_ID", $container_size_id);
		$this->db->where("CONTAINER_TYPE_ID", $container_type_id);
		$this->db->where("CONTAINER_CATEGORY_ID", $container_category_id);
		$this->db->where("FROM_QTY", $from_qty);
		$this->db->where("TO_QTY", $to_qty);
		$this->db->where("FROM_LOCATION_ID", $from_location_id);
		$this->db->where("TO_LOCATION_ID", $to_location_id);

		if (!$this->db->update($table, $data_insert)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_data_cost_do()
	{
		return $this->db->query("SELECT *, COST_KD.GENERAL_DESCRIPTION AS COST_KINDS FROM HSP..MCOST MAIN LEFT JOIN HSP..MGENERAL_ID COST_KD ON MAIN.COST_KIND = COST_KD.GENERAL_ID WHERE COST_KD.CLASSIFICATION_ID = 'COST_KIND' AND COST_GROUP = 'DOR' AND COST_KIND = 'S'");
	}

	function get_data_calc()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'CALC_TYPE'");
	}

	function get_detail_cost($cost_id)
	{
		return $this->db->query("SELECT * FROM HSP..MCOST WHERE COST_ID = '$cost_id'");
	}

	function get_data_cost_shipping()
	{
		return $this->db->query("SELECT MAIN.COST_ID, MAIN.CONTAINER_SIZE_ID, MAIN.CONTAINER_TYPE_ID, MAIN.CONTAINER_CATEGORY_ID, MAIN.FROM_QTY, MAIN.TO_QTY, CONVERT(CHAR(10), MAIN.START_DATE, 126) AS START_DATE, CONVERT(CHAR(10), MAIN.END_DATE, 126) AS END_DATE, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.CALC_TYPE, MAIN.COST_CURRENCY, MAIN.COST_AMOUNT, MAIN.INCREMENT_QTY, COST.COST_NAME, CON_TP.GENERAL_DESCRIPTION AS CONTAINER_TYPE, CON_CAT.GENERAL_DESCRIPTION AS CONTAINER_CATEGORY, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, CALC.GENERAL_DESCRIPTION AS CALC_NAME FROM HSP..MCOST_SERVICE_CONTAINER_SHIPPING_ATTRIBUTE MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID CON_TP ON MAIN.CONTAINER_TYPE_ID = CON_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CON_CAT ON MAIN.CONTAINER_CATEGORY_ID = CON_CAT.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID CALC ON MAIN.CALC_TYPE = CALC.GENERAL_ID WHERE CON_TP.CLASSIFICATION_ID = 'CONTAINER_TYPE' AND CON_CAT.CLASSIFICATION_ID = 'CONTAINER_CATEGORY' AND COST_TP.CLASSIFICATION_ID = 'COST_TYPE' AND COST_GP.CLASSIFICATION_ID = 'COST_GROUP' AND CALC.CLASSIFICATION_ID = 'CALC_TYPE'");
	}

	function get_data_customer()
	{
		return $this->db2->query("SELECT MAIN.COMPANY_ID, MAIN.NAME AS COMPANY_NAME, CUSTOMER.NAME AS PIC_NAME, ADDRE.ADDRESS_1, ADDRE.ADDRESS_2 FROM MCOMPANY MAIN LEFT JOIN MCUSTOMER_CONTACT CUSTOMER ON MAIN.COMPANY_ID = CUSTOMER.COMPANY_ID LEFT JOIN MCOMPANY_ADDRESS ADDRE ON MAIN.COMPANY_ID = ADDRE.COMPANY_ID WHERE MAIN.COMPANY_ID IN  ( SELECT COMPANY_ID FROM MCUSTOMER_REFERER WHERE CUSTOMER_ID IN ( SELECT CUSTOMER_ID FROM MCUSTOMER_CONTACT where COMPANY_ID = 'COM170201001' ) )");
	}

	function get_view_location()
	{
		return $this->db->query("SELECT * FROM HSP..MLOCATION");
	}

	function get_pic_cost()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT DISTINCT MAIN.USER_ID_RECEIVED as PIC_ID, NIK.Nm_lengkap AS PIC_NAME FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK ON MAIN.USER_ID_RECEIVED = NIK.Nik WHERE MAIN.IS_TRANSFERED = 'Y'");
	}

	function get_data_cost_pic($pic_id, $work_order_number)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, MAIN.COST_ID, MAIN.CONTAINER_NUMBER, MAIN.COST_CURRENCY, MAIN.COST_TYPE_ID, MAIN.COST_GROUP_ID, MAIN.COST_RECEIVED_AMOUNT, CONVERT(CHAR(10), MAIN.REQUEST_DATE, 126) AS REQUEST_DATE, CONVERT(CHAR(10), MAIN.TRANSFER_DATE_ACTUAL, 126) AS TRANSFER_DATE, MAIN.IS_DONE, MAIN.USER_ID, CONVERT(CHAR(10), MAIN.USER_DATE, 126) AS CREATE_DATE, COST.COST_NAME, COST_TP.GENERAL_DESCRIPTION AS COST_TYPE, COST_GP.GENERAL_DESCRIPTION AS COST_GROUP, COST_KD.GENERAL_DESCRIPTION AS COST_KIND, NIK_RECEIVED.Nm_lengkap AS PIC_RECEIVED, NIK_CREATE.Nm_lengkap AS PIC_CREATE FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..MCOST COST ON MAIN.COST_ID = COST.COST_ID LEFT JOIN HSP..MGENERAL_ID COST_TP ON MAIN.COST_TYPE_ID = COST_TP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_GP ON MAIN.COST_GROUP_ID = COST_GP.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID COST_KD ON MAIN.COST_KIND = COST_KD.GENERAL_ID LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK_RECEIVED ON MAIN.USER_ID_RECEIVED = NIK_RECEIVED.Nik LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK_CREATE ON MAIN.USER_ID = NIK_CREATE.Nik WHERE MAIN.USER_ID_RECEIVED = '$pic_id' AND MAIN.IS_TRANSFERED = 'Y' AND MAIN.IS_DONE = 'N' AND COST_TP.CLASSIFICATION_ID = 'COST_TYPE' AND COST_GP.CLASSIFICATION_ID = 'COST_GROUP' AND COST_KD.CLASSIFICATION_ID = 'COST_KIND' AND MAIN.WORK_ORDER_NUMBER = '$work_order_number'");
	}

	function get_name_pic($pic_id)
	{
		$this->db3->db_select();
		return $this->db3->query("select * from pltapol..u_nik where Nik = '$pic_id'");
	}

	function get_wo_pic($pic_id)
	{
		return $this->db->query("SELECT MAIN.WORK_ORDER_NUMBER, CUSTOMER.NAME AS CUSTOMER_NAME, SUM(MAIN.COST_RECEIVED_AMOUNT) as TOTAL, WORK_ORDER.REFERENCE_NUMBER, NIK.Nm_lengkap AS PIC_CREATE FROM HSP..TRCASH_REQUEST MAIN LEFT JOIN HSP..TRWORKORDER WORK_ORDER ON MAIN.WORK_ORDER_NUMBER = WORK_ORDER.WORK_ORDER_NUMBER LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] CUSTOMER ON WORK_ORDER.CUSTOMER_ID = CUSTOMER.COMPANY_ID LEFT JOIN [192.168.11.181\DBTLI01].[pltapol].[dbo].[u_nik] NIK ON WORK_ORDER.USER_ID = NIK.Nik WHERE MAIN.USER_ID_RECEIVED = '$pic_id' AND MAIN.IS_TRANSFERED = 'Y' AND MAIN.IS_DONE = 'N' GROUP BY MAIN.WORK_ORDER_NUMBER, CUSTOMER.NAME, WORK_ORDER.REFERENCE_NUMBER, NIK.Nm_lengkap");
	}
}