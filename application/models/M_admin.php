<?php

class M_Admin extends CI_Model {

	function get_menu()
	{
		return $this->db->query("SELECT * FROM HSP..MMENU");
	}

	function delete_menu($table, $nik, $data)
	{
		$this->db->where("NIK", $nik);

		if (!$this->db->delete($table, $data)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
}