<?php

class M_DeliveryOrderDetailDraft extends CI_Model
{
	private $table_name = "delivery_order_detail_draft";

    private $primary_key = "delivery_order_detail_draft_id";

	private $cols = [
		'delivery_order_draft_id' => [
			'name' => 'delivery_order_draft_id',
			'label' => 'DO Draft ID',
			'rules' => 'required',
		],
		'sku_keterangan' => [
            'name' => 'sku_keterangan',
			'label' => 'Keterangan',
        ],
        'sku_qty' => [
            'name' => 'sku_qty',
			'label' => 'Qty Req',
			'rules' => 'required',
        ],
	];

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	public function getDeliveryOrderDraftId() {return $this->delivery_order_draft_id;}
	public function setDeliveryOrderDraftId($str) {$this->delivery_order_draft_id = $str;}

    // public function Get_DeliveryOrderDetailDraft()
    // {
    //     $data = $this->db->select("do.*")
    //         ->from('DeliveryOrderDetailDraft as do')
    //         ->get();

    //     return $data->result_array();
    // }

    // public function save($DOD_ID, $SKU, $qty, $S_UserName)
    // {
    //     $this->db->set("DOD_Detail_ID", "NEWID()", false);
    //     $this->db->set("DOD_ID", $DOD_ID);
    //     $this->db->set("SKU_ID", $SKU["SKU_ID"]);
    //     $this->db->set("SKU_Kode", $SKU["SKU_Kode"]);
    //     $this->db->set("SKU_NamaProduk", $SKU["SKU_NamaProduk"]);
    //     $this->db->set("SKU_Weight", $SKU["SKU_Weight"]);
    //     $this->db->set("SKU_WeightUnit", $SKU["SKU_WeightUnit"]);
    //     $this->db->set("SKU_Length", $SKU["SKU_Length"]);
    //     $this->db->set("SKU_Width", $SKU["SKU_Width"]);
    //     $this->db->set("SKU_WidthUnit", $SKU["SKU_WidthUnit"]);
    //     $this->db->set("SKU_Height", $SKU["SKU_Height"]);
    //     $this->db->set("SKU_HeightUnit", $SKU["SKU_HeightUnit"]);
    //     $this->db->set("SKU_Volume", $SKU["SKU_Volume"]);
    //     $this->db->set("SKU_VolumeUnit", $SKU["SKU_VolumeUnit"]);
    //     $this->db->set("qty", $qty);

    //     $this->db->insert("DeliveryOrderDetailDraft");
    // }

    // public function Get_DeliveryOrderDetailDraft_By_DODID($DOD_ID)
    // {
    //     $data = $this->db->select("do.*")
    //         ->from('DeliveryOrderDetailDraft as do')
    //         ->where('DOD_ID', $DOD_ID)
    //         ->get();

    //     return $data->result_array();
    // }

	public function getAttributes()
	{
		return [
			'delivery_order_detail_draft_id' => $this->delivery_order_detail_draft_id,
			'delivery_order_draft_id' => $this->delivery_order_draft_id,
			'sku_id' => $this->sku_id,
			'gudang_id' => $this->gudang_id,
			'gudang_detail_id' => $this->gudang_detail_id,
			'sku_kode' => $this->sku_kode,
			'sku_nama_produk' => $this->sku_nama_produk,
			'sku_harga_satuan' => $this->sku_harga_satuan,
			'sku_disc_percent' => $this->sku_disc_percent,
			'sku_disc_rp' => $this->sku_disc_rp,
			'sku_harga_nett' => $this->sku_harga_nett,
			'sku_request_expdate' => $this->sku_request_expdate,
			'sku_filter_expdate' => $this->sku_filter_expdate,
			'sku_filter_expdatebulan' => $this->sku_filter_expdatebulan,
			'sku_filter_expdatetahun' => $this->sku_filter_expdatetahun,
			'sku_weight' => $this->sku_weight,
			'sku_weight_unit' => $this->sku_weight_unit,
			'sku_length' => $this->sku_length,
			'sku_length_unit' => $this->sku_length_unit,
			'sku_width' => $this->sku_width,
			'sku_width_unit' => $this->sku_width_unit,
			'sku_height' => $this->sku_height,
			'sku_height_unit' => $this->sku_height_unit,
			'sku_volume' => $this->sku_volume,
			'sku_volume_unit' => $this->sku_volume_unit,
			'sku_qty' => $this->sku_qty,
			'sku_keterangan' => $this->sku_keterangan,
		];
	}

	public function setAttributes($data)
	{
		$this->delivery_order_detail_draft_id = isset($data['delivery_order_detail_draft_id']) ? $data['delivery_order_detail_draft_id'] : NULL;
		$this->delivery_order_draft_id = isset($data['delivery_order_draft_id']) ? $data['delivery_order_draft_id'] : NULL;
		$this->sku_id = isset($data['sku_id']) ? $data['sku_id'] : NULL;
		$this->gudang_id = isset($data['gudang_id']) ? $data['gudang_id'] : NULL;
		$this->gudang_detail_id = isset($data['gudang_detail_id']) ? $data['gudang_detail_id'] : NULL;
		$this->sku_kode = isset($data['sku_kode']) ? $data['sku_kode'] : NULL;
		$this->sku_nama_produk = isset($data['sku_nama_produk']) ? $data['sku_nama_produk'] : NULL;
		$this->sku_harga_satuan = isset($data['sku_harga_satuan']) ? $data['sku_harga_satuan'] : NULL;
		$this->sku_disc_percent = isset($data['sku_disc_percent']) ? $data['sku_disc_percent'] : NULL;
		$this->sku_disc_rp = isset($data['sku_disc_rp']) ? $data['sku_disc_rp'] : NULL;
		$this->sku_harga_nett = isset($data['sku_harga_nett']) ? $data['sku_harga_nett'] : NULL;
		$this->sku_request_expdate = isset($data['sku_request_expdate']) ? $data['sku_request_expdate'] : NULL;
		$this->sku_filter_expdate = isset($data['sku_filter_expdate']) ? $data['sku_filter_expdate'] : NULL;
		$this->sku_filter_expdatebulan = isset($data['sku_filter_expdatebulan']) ? $data['sku_filter_expdatebulan'] : NULL;
		$this->sku_filter_expdatetahun = isset($data['sku_filter_expdatetahun']) ? $data['sku_filter_expdatetahun'] : NULL;
		$this->sku_weight = isset($data['sku_weight']) ? $data['sku_weight'] : NULL;
		$this->sku_weight_unit = isset($data['sku_weight_unit']) ? $data['sku_weight_unit'] : NULL;
		$this->sku_length = isset($data['sku_length']) ? $data['sku_length'] : NULL;
		$this->sku_length_unit = isset($data['sku_length_unit']) ? $data['sku_length_unit'] : NULL;
		$this->sku_width = isset($data['sku_width']) ? $data['sku_width'] : NULL;
		$this->sku_width_unit = isset($data['sku_width_unit']) ? $data['sku_width_unit'] : NULL;
		$this->sku_height = isset($data['sku_height']) ? $data['sku_height'] : NULL;
		$this->sku_height_unit = isset($data['sku_height_unit']) ? $data['sku_height_unit'] : NULL;
		$this->sku_volume = isset($data['sku_volume']) ? $data['sku_volume'] : NULL;
		$this->sku_volume_unit = isset($data['sku_volume_unit']) ? $data['sku_volume_unit'] : NULL;
		$this->sku_qty = isset($data['sku_qty']) ? $data['sku_qty'] : NULL;
		$this->sku_keterangan = isset($data['sku_keterangan']) ? $data['sku_keterangan'] : NULL;
	}

	public function findManyByCriteria($criterias, $asObject = true)
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
		return $asObject ? $objects->result() : $objects->result_array();
	}

	public function deleteManyBy($conditions = [])
	{
		foreach($conditions as $key => $val) {
			$this->db->where($key, $val);
		}

		$this->db->delete($this->table_name);
	}

	public function setValidation()
	{
		foreach($this->cols as $col) {
			if (isset($col['rules']) && $col['rules'] != "") {
				$this->form_validation->set_rules($col['name'], $col['label'], $col['rules']);
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
			} else {
				// return $this->update($this->id, $data);
			}
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
}
