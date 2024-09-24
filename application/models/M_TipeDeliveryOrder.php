<?php

class M_TipeDeliveryOrder extends CI_Model
{
    private $table_name = "tipe_delivery_order";

    private $primary_key = "tipe_delivery_order_id";

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
		foreach($criterias as $criteria) {
			if ($criteria['type'] == 'specific') {
				$this->db->where($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'similar') {
				$this->db->like($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'in') {
				$this->db->where_in($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'notin') {
				$this->db->where_not_in($criteria['colName'], $criteria['colValue']);
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

	public function getExceptionId()
	{
		return [
			'BB403FA9-007F-42CD-87F0-92CEF7B40AAD'
		];
	}
}
