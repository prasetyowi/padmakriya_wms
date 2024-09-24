<?php

class M_TipeLayanan extends CI_Model
{
	private $table_name = "tipe_layanan";

    private $primary_key = "tipe_layanan_id";

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
		foreach($criterias as $criteria) {
			if ($criteria['type'] == 'specific') {
				$this->db->where($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'similar') {
				$this->db->like($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'in') {
				$this->db->where_in($criteria['colName'], $criteria['colValue']);
			}
		}

		$objects = $this->db->get($this->table_name);
		return $objects->result();
	}

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return isset($results[0]) ? $results[0] : NULL;
	}
}
