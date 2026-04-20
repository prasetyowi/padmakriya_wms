<?php
class M_DepoDetail extends CI_Model
{
	private $table_name = "depo_detail";

	private $primary_key = "depo_detail_id";

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

	public function countByCriteria($criterias)
	{
		foreach ($criterias as $criteria) {
			if ($criteria['type'] == 'specific') {
				$this->db->where($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'similar') {
				$this->db->like($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'in') {
				$this->db->where_in($criteria['colName'], $criteria['colValue']);
			}
		}

		$this->db->from($this->table_name);
		return $this->db->count_all_results();
	}

	public function findAll()
	{
		$objects = $this->db->get($this->table_name);
		return $objects->result();
	}

	public function findAll_array()
	{
		$objects = $this->db->get($this->table_name);
		return $objects->result_array();
	}

	public function findByDepo_array()
	{
		$this->db->where("depo_id", $this->session->userdata('depo_id'));

		$objects = $this->db->get($this->table_name);
		return $objects->result_array();
	}

	public function findManyByCriteria($criterias)
	{
		foreach ($criterias as $criteria) {
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
		return $results[0];
	}
}
