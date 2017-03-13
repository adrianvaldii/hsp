<?php

class M_Service extends CI_Model {

	// check available data
	function check_data_service($service_name)
	{
		$arr_data = array('SERVICE_NAME = ' => $service_name);
		$this->db->where($arr_data);
		$query = $this->db->get('dbo.MSELLING_SERVICE');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	// function input data to database
	function input_data($data, $table)
	{
		$this->db->insert($table, $data);
	}

	function get_max_serviceid()
	{
		$this->db->select_max("SELLING_SERVICE_ID");
		$this->db->from("dbo.MSELLING_SERVICE");

		return $this->db->get()->row();
	}

	function get_mgeneralid()
	{
		$this->db->where('CLASSIFICATION_ID', 'UNIT_CALCULATION');
		return $this->db->get('dbo.MGENERAL_ID');
	}

	function get_type_service()
	{
		return $this->db->query("SELECT * FROM HSP..MGENERAL_ID WHERE CLASSIFICATION_ID = 'SERVICE_TYPE'");
	}

	function get_service()
	{
		return $this->db->query("SELECT MAIN.SELLING_SERVICE_ID, MAIN.SERVICE_NAME, MAIN.SERVICE_DESCRIPTION, UOM.GENERAL_DESCRIPTION AS UOM_NAME, SERVICE_TP.GENERAL_DESCRIPTION AS SERVICE_TYPE FROM HSP..MSELLING_SERVICE MAIN LEFT JOIN HSP..MGENERAL_ID UOM ON MAIN.SELL_UOM_ID = UOM.GENERAL_ID LEFT JOIN HSP..MGENERAL_ID SERVICE_TP ON MAIN.SERVICE_TYPE = SERVICE_TP.GENERAL_ID WHERE MAIN.FLAG = '1' AND SERVICE_TP.CLASSIFICATION_ID = 'SERVICE_TYPE' AND UOM.CLASSIFICATION_ID = 'UNIT_CALCULATION'");
	}

	function get_service_param($service_id)
	{
		return $this->db->query("SELECT * FROM HSP..MSELLING_SERVICE WHERE SELLING_SERVICE_ID = '$service_id'");
	}

	function edit_data($where, $table)
	{
		return $this->db->get_where($table, $where);
	}

	function update_data($where, $data, $table)
	{
		$this->db->where($where);
		if (!$this->db->update($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function get_companyservice()
	{
		// $this->db->select('dbo.MSELLING_SERVICE_DETAIL.COMPANY_SERVICE_ID, dbo.MSELLING_SERVICE_DETAIL.SELLING_SERVICE_ID, dbo.MCOMPANY_SERVICES.COMPANY_ID, dbo.MSELLING_SERVICE.SERVICE_NAME, dbo.MCOMPANY.COMPANY_NAME');
		// $this->db->from('dbo.MSELLING_SERVICE_DETAIL');
		// $this->db->join('dbo.MCOMPANY_SERVICES', 'dbo.MSELLING_SERVICE_DETAIL.COMPANY_SERVICE_ID = dbo.MCOMPANY_SERVICES.COMPANY_SERVICE_ID', 'inner');
		// $this->db->join('dbo.MSELLING_SERVICE', 'dbo.MSELLING_SERVICE_DETAIL.SELLING_SERVICE_ID = dbo.MSELLING_SERVICE.SELLING_SERVICE_ID', 'inner');
		// $this->db->join('dbo.MCOMPANY', 'dbo.MCOMPANY_SERVICES.COMPANY_ID = dbo.MCOMPANY.COMPANY_ID', 'inner');
		// $q = $this->db->get();
		// return $q;

		return $this->db->query("SELECT MAIN.COMPANY_SERVICE_ID, MAIN.SELLING_SERVICE_ID, COMPANY.COMPANY_NAME, SERVICE.SERVICE_NAME FROM HSP..MSELLING_SERVICE_DETAIL MAIN LEFT JOIN HSP..MCOMPANY COMPANY ON MAIN.COMPANY_SERVICE_ID = COMPANY.COMPANY_ID LEFT JOIN HSP..MSELLING_SERVICE SERVICE ON MAIN.SELLING_SERVICE_ID = SERVICE.SELLING_SERVICE_ID");
	}

	function get_company_service_detail()
	{
		return $this->db->query("SELECT * FROM HSP..MCOMPANY");
	}

	// function get_company_service_detail()
	// {
	// 	$this->db->distinct();
	// 	$this->db->select('dbo.MCOMPANY_SERVICES.COMPANY_SERVICE_ID, dbo.MCOMPANY_SERVICES.COMPANY_ID, dbo.MCOMPANY.COMPANY_NAME');
	// 	$this->db->from('dbo.MCOMPANY_SERVICES');
	// 	$this->db->join('dbo.MCOMPANY', 'dbo.MCOMPANY_SERVICES.COMPANY_ID = dbo.MCOMPANY.COMPANY_ID');
	// 	$q = $this->db->get();
	// 	return $q;
	// }

	function get_service_detail()
	{
		return $this->db->get('dbo.MSELLING_SERVICE');
	}

	function input_service_detail($data, $table)
	{
		
		$this->db->insert($table, $data);
	}

	function check_data_companyservice($selling_service_id, $company_service_id)
	{
		$arr_data = array('SELLING_SERVICE_ID = ' => $selling_service_id, 'COMPANY_SERVICE_ID = ' => $company_service_id);
		$this->db->where($arr_data);
		$query = $this->db->get('dbo.MSELLING_SERVICE_DETAIL');
		if ($query->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}

}