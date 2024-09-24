<?php

class M_Pengguna_Depo extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Getpengguna_depo()
	{
		$this->db->select("pengguna_depo_id, pengguna_id, g.depo_id, g.depo_nama")
			->distinct()
			->from("pengguna_depo as pg")
			->join("depo as g", "g.depo_id = pg.depo_id", "inner")
			->order_by("g.depo_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Getpengguna_depo_by_pengguna_id($pengguna_id)
	{
		$query = $this->db->query(" select distinct pengguna_depo_id, pengguna_id, g.depo_id, g.depo_nama, g.unit_mandiri_id, g.depo_latitude, g.depo_longitude, g.depo_alamat,	
										case when pg.pengguna_depo_id is not null then 1 else 0 end as isaccessible
									FROM depo as g
									INNER JOIN pengguna_depo as pg ON g.depo_id = pg.depo_id and pengguna_id = '$pengguna_id'
									ORDER BY g.depo_nama");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Insertpengguna_depo($pengguna_id, $depo_id)
	{
		$this->db->set("pengguna_depo_id", "NEWID()", FALSE);
		$this->db->set("pengguna_id", $pengguna_id);
		$this->db->set("depo_id", $depo_id);

		$this->db->insert("pengguna_depo");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Deletepengguna_depo($pengguna_id)
	{
		$this->db->where("pengguna_id", $pengguna_id);

		$this->db->delete("pengguna_depo");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}
}
