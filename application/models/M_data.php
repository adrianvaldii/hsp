<?php

class M_data extends CI_Model {
	function tampil_data() {
		$this->db->where('CLASSIFICATION_ID', 'UNIT_CALCULATION');
		return $this->db->get('dbo.MGENERAL_ID');
	}
}