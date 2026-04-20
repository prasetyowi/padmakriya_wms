<?php

class M_KaryawanPrinciple extends CI_Model
{
    private $table_name = "karyawan_principle";

    private $primary_key = "karyawan_principle_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function countAll()
    {
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

    public function findAll()
    {
        $objects = $this->db->get($this->table_name);
        return $objects->result();
    }

	public function findManyByCriteria($criterias)
	{
		foreach($criterias as $colName => $colValue) {
			$this->db->where($colName, $colValue);
		}

		$objects = $this->db->get($this->table_name);
        return $objects->result();
	}

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return $results[0];
	}
}
