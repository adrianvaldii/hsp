<?php

class M_Dashboard extends CI_Model {

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

	public function get_customer_month()
	{
		$this->db->query('SET ANSI_NULLS ON');
		$this->db->query('SET QUOTED_IDENTIFIER ON');
		$this->db->query('SET CONCAT_NULL_YIELDS_NULL ON'); 
		$this->db->query('SET ANSI_WARNINGS ON');
		$this->db->query('SET ANSI_PADDING ON');

		return $this->db->query("SELECT COUNT(MAIN.CUSTOMER_ID) AS TOTAL_CUSTOMER, CUSTOMER.NAME as CUSTOMER_NAME FROM HSP..TRWORKORDER MAIN LEFT JOIN [192.168.11.28].[CRM].[dbo].[MCOMPANY] CUSTOMER ON MAIN.CUSTOMER_ID = CUSTOMER.COMPANY_ID WHERE DATEPART(MM, MAIN.USER_DATE) = DATEPART(MM, GETDATE()) GROUP BY CUSTOMER.NAME");
	}

	public function get_wo_per_year()
	{
		return $this->db->query("SELECT COUNT(WORK_ORDER_NUMBER) AS TOTAL_WO, DATEPART(MM, WORK_ORDER_DATE) AS BULAN FROM HSP..TRWORKORDER WHERE DATEPART(YYYY, WORK_ORDER_DATE) = DATEPART(YYYY, GETDATE()) GROUP BY DATEPART(MM, WORK_ORDER_DATE)");
	}
}