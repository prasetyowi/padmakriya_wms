<?php

class M_ClientPt extends CI_Model
{
    private $table_name = "client_pt";

    private $primary_key = "client_pt_id";

	private $cols = [
        [
            'name' => 'client_pt_nama',
            'filterType' => 'similar',
        ],
        [
            'name' => 'client_pt_alamat',
            'filterType' => 'similar',
        ],
        [
            'name' => 'client_pt_telepon',
            'filterType' => 'similar',
        ],
        [
            'name' => 'area_id',
            'filterType' => 'specific',
        ],
    ];

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function countAll()
    {
		$this->db->where('client_pt_is_deleted', '0');
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

    public function findAll()
    {
		$this->db->where('client_pt_is_deleted', '0');

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

	/*
	 * search data to be used spesifically for datatable
	 * 
	 * Params:
	 * $criteria - array of criteria for filtering. It's key is the column name.
	 * $limit - int
	 * $offset - int
	 * $orders - array of orders, column key is the column name and dir key is the direction asc or desc
	 * $asLookup - false is for list page while true is for lookup modal
	 * 
	 * return array of result for datatable
	 */
	public function search($criteria = array(), $limit = '', $offset = '', $orders = array(), $asLookup = false)
    {
        $records = array();

		$this->db->join('area', 'area.area_id = '.$this->table_name.'.area_id');

        //limit and offset
		if ($limit != "" && $limit != "-1") {
			$this->db->limit($limit);
			if ($offset != "") {
				$this->db->limit($limit, $offset);
			}
		}

        //filters
        foreach($this->cols as $col) {
            if (isset($criteria[$col['name']])) {
                if ($col['filterType'] == 'similar') {
                    $this->db->like($col['name'], $criteria[$col['name']]);
                } else if ($col['filterType'] == 'specific' && $criteria[$col['name']] != "") {
                    $this->db->where($col['name'], $criteria[$col['name']]);
                } else if ($col['filterType'] == 'range' && $criteria[$col['name']][$col['rangeField'][0]] != "" && $criteria[$col['name']][$col['rangeField'][1]] != "") {
                    $this->db->where($col['name'].' BETWEEN \''.$criteria[$col['name']][$col['rangeField'][0]].'\' AND \''.$criteria[$col['name']][$col['rangeField'][1]].'\'');
                }
            }
        }

        //sorting
        if (!empty($orders)) {
            foreach($orders as $order) {
                $this->db->order_by($this->cols[$order['column']]['name'], $order['dir']);
            }
        }

		$this->db->where('client_pt_is_deleted', '0');

        $objects = $this->db->get($this->table_name);

        $records = [];
		if ($objects != false) {
			foreach($objects->result() as $object) {
				$row = [];
				$row[] = !empty($object->client_pt_nama) ? $object->client_pt_nama : '';
				$row[] = !empty($object->client_pt_alamat) ? strip_tags($object->client_pt_alamat) : '';
				$row[] = !empty($object->client_pt_telepon) ? $object->client_pt_telepon : '';
				$row[] = !empty($object->area_nama) ? $object->area_nama : '';
				if (!$asLookup) {
					$row[] = '<div style="white-space: nowrap">'
						.getViewButton('DeliveryOrderDraft', 'view', $object->client_pt_id)
						.getUpdateButton('DeliveryOrderDraft', 'update', $object->client_pt_id)
						.'</div>';
				} else {
					$row[] = getLookupPickButton('btn-choose-customer', ['id' => $object->client_pt_id]);
				}

				$records[] = $row;
			}
		}

        return $records;
    }

	public function getAttributeLabels($label='')
    {
        $labels = [
            'client_pt_nama' => 'Nama',
            'client_pt_alamat' => 'Alamat',
            'client_pt_telepon' => 'Telepon',
            'area_id' => 'Area'
        ];
        return $label === '' ? $labels : (isset($labels[$label]) ? $labels[$label] : '');
    }
}
