<?php

class M_StatusProgress extends CI_Model
{
	const MODUL_DO_DRAFT = 'Delivery Order Draft';
	
    private $table_name = "status_progress";

    private $primary_key = "status_progress_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function countAll()
    {
        $this->db->where('status_progress_is_deleted', '0');
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

    public function findAll()
    {
		$this->db->where('status_progress_is_deleted', '0');

		$this->db->order_by('status_progress_no', 'asc');

        $objects = $this->db->get($this->table_name);
        return $objects->result();
    }

	public function findAllByCriteria($criteria = [])
	{
		$this->db->where('status_progress_is_deleted', '0');

		$this->db->order_by('status_progress_no', 'asc');

		if (!empty($criteria)) {
			foreach($criteria as $colName => $colValue) {
				$this->db->where($colName, $colValue);
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
