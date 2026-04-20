<?php

class M_Area extends CI_Model
{
    private $table_name = "area";

    private $primary_key = "area_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	public function Getarea()
	{
		$this->db	->select("area_id, area_wilayah, area_kode, area_nama, area_is_aktif")
					->from("area")
					->where("area_is_aktif", 1 )
					->order_by("area_wilayah","ASC")
					->order_by("area_kode","ASC");
		$query = $this->db->get();

		if( $query->num_rows() == 0 )	{	return 0; }
		else							{	return $query->result_array(); }
	}
	
    public function countAll()
    {
		$this->db->where('area_is_aktif', '1');
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

	public function countByCriteria($criterias)
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

		$this->db->from($this->table_name);
		return $this->db->count_all_results();
	}

    public function findAll()
    {
		$this->db->where('area_is_aktif', '1');

        $objects = $this->db->get($this->table_name);
        return $objects->result();
    }

	public function findManyByCriteria($criterias)
	{
		$this->db->order_by('area_nama', 'asc');

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
		return $results[0];
	}
}
