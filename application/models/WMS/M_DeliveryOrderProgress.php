<?php

class M_DeliveryOrderProgress extends CI_Model
{
	private $table_name = "delivery_order_progress";

    private $primary_key = "delivery_order_progress_id";

	private $cols = [
        'delivery_order_id' => [
            'name' => 'delivery_order_id',
			'label' => 'DO',
			'rules' => 'required',
        ],
		'status_progress_id' => [
            'name' => 'status_progress_id',
			'label' => 'Status Progress ID',
			'rules' => 'required',
        ],
		'status_progress_nama' => [
            'name' => 'status_progress_nama',
			'label' => 'Status Progress',
			'rules' => 'required',
        ],
    ];

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getPrimaryKey() {return $this->primary_key;}

	public function getTableName() {return $this->table_name;}

	public function getAttributes()
	{
		return [
			'delivery_order_progress_id' => $this->delivery_order_progress_id,
			'delivery_order_id' => $this->delivery_order_id,
			'status_progress_id' => $this->status_progress_id,
			'status_progress_nama' => $this->status_progress_nama,
			'delivery_order_progress_create_who' => $this->delivery_order_progress_create_who,
			'delivery_order_progress_create_tgl' => $this->delivery_order_progress_create_tgl,
		];
	}

	public function setAttributes($data)
	{
		$this->delivery_order_progress_id = isset($data['delivery_order_progress_id']) ? $data['delivery_order_progress_id'] : NULL;
		$this->delivery_order_id = isset($data['delivery_order_id']) ? $data['delivery_order_id'] : NULL;
		$this->status_progress_id = isset($data['status_progress_id']) ? $data['status_progress_id'] : NULL;
		$this->status_progress_nama = isset($data['status_progress_nama']) ? $data['status_progress_nama'] : NULL;
		$this->delivery_order_progress_create_who = isset($data['delivery_order_progress_create_who']) ? $data['delivery_order_progress_create_who'] : NULL;
		$this->delivery_order_progress_create_tgl = isset($data['delivery_order_progress_create_tgl']) ? date("Y-m-d", strtotime(str_replace("/", "-", $data['delivery_order_progress_create_tgl']))) : NULL;
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

	public function setValidation()
	{
		foreach($this->cols as $col) {
			if (isset($col['rules']) && $col['rules'] != "" && !isset($col['customRules'])) {
				$this->form_validation->set_rules($col['name'], $col['label'], $col['rules']);
			} else if (isset($col['customRules'])) {
				$customRules = [];
				$customRules[] = array($col['customRules']['callableName'], array($this->M_DeliveryOrderBatch, $col['customRules']['callableFunction']));
				if (isset($col['rules']) && $col['rules'] != "") {
					$rules = $col['rules'];
					if (strpos('|', $col['rules']) !== false) {
						$rules = explode('|', $col['rules']);
					}
					foreach($rules as $rule) {
						$customRules[] = $rule;
					}
				}
				$this->form_validation->set_rules($col['name'], $col['label'], $customRules);
				$this->form_validation->set_message($col['customRules']['callableName'], $col['customRules']['callableMessage']);
			}
		}
	}

	public function save()
	{
		$data = $this->getAttributes();

		// We have to add/override the values to the
		// $_POST vars so that form_validation library
		// can work with them.
		foreach($data as $key => $val) {
			$_POST[$key] = $val;
		}

		$this->setValidation();

		if ($this->form_validation->run() == true) {
			if (empty($data[$this->primary_key])) {
				$this->load->model("M_Function");
				$newId = $this->M_Function->Get_NewID();
				$data[$this->primary_key] = $newId[0]['kode'];

				foreach($data as $colName => $colValue) {
					if ($colValue != NULL && $colValue != '') {
						$this->db->set($colName, $colValue);
					}
				}

				$this->db->insert($this->table_name);

				if ($this->db->affected_rows() > 0) {
					return $newId[0]['kode'];
				} else {
					return false;
				}
			} else {
				foreach($data as $colName => $colValue) {
					if ($colValue != NULL && $colValue != '' && $colName != $this->primary_key) {
						$this->db->set($colName, $colValue);
					}
				}

				$this->db->where($this->primary_key, $data[$this->primary_key]);
				$this->db->update($this->table_name);

				if ($this->db->affected_rows() > 0) {
					return $data[$this->primary_key];
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
}
