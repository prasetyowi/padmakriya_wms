<?php

class M_PickList extends CI_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getPickList()
    {
        $data = $this->db->select("*")->from('PickList')->get();
        return $data->result_array();
    }

    public function getPickListByGudang($gudangId)
    {
        $data = $this->db->select("*")
            ->from('PickList')
            ->where('Gudang_ID',$gudangId)
            ->get();
        return $data->result_array();
    }

    public function getPickListById($pickListId)
    {
        $data = $this->db->select("*")
            ->from('PickList')
            ->where('PickList_ID',$pickListId)
            ->get();
        return $data->result_array();
    }

    public function getPickListDetail($pickListId)
    {
        $queryGetPickList = $this->db->select('pld.SKU_ID,sku.SKU_NamaProduk,pld2.PickListD2_QtyPlan,
                pld2.PickListD2_ExpiredDate,pld2.PickListD2_SatuanUnit,pld2.PickListD2_QtyPlan,
                pld2.PickListD2_QtyAssigned,pld2.PickListD2_QtySisa,pld2.SKU_UnitMandiriID')
                ->from('PickListD as pld')
                ->join("PickListD2 as pld2","pld.PickListD_ID = pld2.PickListD_ID","inner")
                ->join("SKU as sku","pld.SKU_ID = sku.SKU_ID","inner")
                ->where('pld.PickList_ID',$pickListId)
                ->get();

        return $queryGetPickList->result_array();
    }

    public function savePickOrder($data){
        $this->db->insert('PickOrder',$data);
    }

    public function savePickOrderDetail($data){
        $this->db->insert('PickOrderD',$data);
    }
}
