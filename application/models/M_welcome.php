<?php

class M_Welcome extends CI_Model {

	private $db3;

	public function __construct()
	{
		parent::__construct();
		$this->db3 = $this->load->database('pltapol', TRUE);
	}

	function test()
	{
		$user = 'V01610';
		$pw = 'V01610';
		$cmp = '83';
		$appl = 'HSPF';
		$flag_char = "";
		$msg_output = "";

		$var = "
		declare @nik nvarchar(10)
		declare @pwd nvarchar(20)
		declare @cmpy nvarchar(2)
		declare @appl nvarchar(20)
		declare @flag_char nvarchar(1) 
		declare @msg_output nvarchar(60)
		set @nik = '$user'
		set @pwd = '$pw'
		set @cmpy = '$cmp'
		set @appl = '$appl'
		exec sp_SingleLogin @nik, @pwd, @cmpy, @appl, @flag_char output, @msg_output output
		select @msg_output as informasi, @flag_char as hasil
		";

		// $this->db3->query("declare @flag_char nvarchar(1)");
		// $this->db3->query("declare @msg_output nvarchar(60)");
		
		// $query1 = "sp_SingleLogin {$user}, {$pw}, {$cmp}, {$appl}, '@flag_char output', '@msg_output output'";
		// $query2 = "select @msg_output, @flag_char";

		return $this->db3->query($var);
		
		// $this->db3->query("declare @flag_char nvarchar(1)");
		// $this->db3->query("declare @msg_output nvarchar(60)");
		// 

	}

	function check_login($user, $pw, $cmp, $appl)
	{
		$var = "
		declare @nik nvarchar(10)
		declare @pwd nvarchar(20)
		declare @cmpy nvarchar(2)
		declare @appl nvarchar(20)
		declare @flag_char nvarchar(1) 
		declare @msg_output nvarchar(60)
		set @nik = '$user'
		set @pwd = '$pw'
		set @cmpy = '$cmp'
		set @appl = '$appl'
		exec sp_SingleLogin @nik, @pwd, @cmpy, @appl, @flag_char output, @msg_output output
		select @msg_output as informasi, @flag_char as hasil
		";

		return $this->db3->query($var);
	}

	function check_nik($nik)
	{
		$this->db3->distinct();
		$this->db3->select("*");
		$this->db3->from("dbo.u_nik");
		$this->db3->where("Nik", $nik);

		return $this->db3->get();
	}

	function check_nik_appl($nik)
	{
		$this->db3->distinct();
		$this->db3->select("*");
		$this->db3->from("dbo.u_nik_appl");
		$this->db3->where("Nik", $nik);
		$this->db3->where("appl_code", "HSPF");

		return $this->db3->get();
	}

	function get_company_code($nik)
	{
		$this->db3->distinct();
		$this->db3->select("cmpy_code");
		$this->db3->from("dbo.u_nik_cmpy");
		$this->db3->where("Nik", $nik);

		return $this->db3->get();
	}
}