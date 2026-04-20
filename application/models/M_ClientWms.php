<?php

class M_ClientWms extends CI_Model
{
    private $table_name = "client_wms";

    private $primary_key = "client_wms_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function countAll()
    {
		$this->db->where('client_wms_is_deleted', '0');
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

    public function findAll()
    {
		$this->db->where('client_wms_is_deleted', '0');

        $objects = $this->db->get($this->table_name);
        return $objects->result();
    }

    public function findAll_array()
    {
		$this->db->where('client_wms_is_deleted', '0');

        $objects = $this->db->get($this->table_name);
        return $objects->result_array();
    }

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return $results[0];
	}
}
