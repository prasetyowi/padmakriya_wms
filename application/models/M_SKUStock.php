<?php

class M_SKUStock extends CI_Model
{
    private $table_name = "sku_stock";

    private $primary_key = "sku_stock_id";

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function countAll()
    {
		$this->db->where('sku_stock_is_aktif', '1');
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

    public function findAll()
    {
		$this->db->where('sku_stock_is_aktif', '1');

        $objects = $this->db->get($this->table_name);
        return $objects->result();
    }

    public function findBySKUIdDepo($skuID,$depoID,$depoDID)
    {
		    $data = $this->db->select("*")
                ->from('sku_stock')
                ->where('sku_stock_is_aktif', '1')
                ->where('sku_stock.sku_id', $skuID)
                ->where('sku_stock.depo_id', $depoID)
                ->where('sku_stock.depo_detail_id', $depoDID)
                ->order_by('sku_stock_expired_date')
                ->get();
        return $data->result_array();
    }
}
