<?php

class M_PickOrder extends CI_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getAll()
    {
        $data = $this->db->select("po.*,gd.nama_gudang,pl.PickList_Kode")
            ->from('PickOrder as po')
            ->join('PickList as pl', 'po.PickList_ID = pl.PickList_ID', 'inner')
            ->join('Gudang as gd', 'pl.Gudang_ID = gd.gudang_id', 'inner')
            ->get();
        return $data->result_array();
    }

    public function getDetailByPickOrder($pickOrderId)
    {
        $data = $this->db->select("po.*,pod.*,gd.nama_gudang,pl.PickList_Kode,sku.SKU_NamaProduk")
            ->from('PickOrder as po')
            ->join('PickOrderD as pod', 'po.PickOrder_ID = pod.PickOrder_ID', 'inner')
            ->join('PickList as pl', 'po.PickList_ID = pl.PickList_ID', 'inner')
            ->join('Gudang as gd', 'pl.Gudang_ID = gd.gudang_id', 'inner')
            ->join('SKU_UNITMANDIRI as skuunit', 'pod.SKU_UnitMandiriID = skuunit.SKUUNITMANDIRI_ID', 'inner')
            ->join('SKU as sku', 'skuunit.SKU_ID = sku.SKU_ID', 'inner')
            ->where('po.PickOrder_ID', $pickOrderId)
            ->get();

        return $data->result_array();
    }

    public function filterPickOrder($dataPost)
    {
        $tglAwal = $dataPost['tglAwal'].' 00:00:00';
        $tglAkhir = $dataPost['tglAkhir'].' 23:59:59';
        $gudangId = $dataPost['gudangId'];
        $pickListId = $dataPost['pickListId'];
        $checkerName = $dataPost['checkerName'];
        $data = $this->db->select("po.*,gd.nama_gudang,pl.PickList_Kode")
        ->from('PickOrder as po')
        ->join('PickOrderD as pod', 'po.PickOrder_ID = pod.PickOrder_ID', 'inner')
        ->join('PickList as pl', 'po.PickList_ID = pl.PickList_ID', 'inner')
        ->join('Gudang as gd', 'pl.Gudang_ID = gd.gudang_id', 'inner')
        ->where('po.PickOrder_Date >= ', $tglAwal)
        ->where('po.PickOrder_Date <= ', $tglAkhir)
        ->where('po.PickOrder_CheckerName', $checkerName)
        ->where('pod.Gudang2_ID', $gudangId)
        ->where('po.PickList_ID', $pickListId)
        ->get();

        return $data->result_array();
    }

    public function getLastId(){
        $data = $this->db->select("*")
            ->from('PickOrder')
            ->order_by('PickOrder_DateCreate',"DESC")
            ->limit(1)
            ->get();

        $result = $data->result_array();
        return $result[0]['PickOrder_ID'];
    }

    public function savePickOrder($data)
    {
        $this->db->set("PickOrder_ID", "NEWID()", false);
        $this->db->set("PickList_ID", $data['PickList_ID']);
        $this->db->set("PickOrder_Kode", $data['PickOrder_Kode']);
        $this->db->set("PickOrder_Date", $data['PickOrder_Date']);
        $this->db->set("PickOrder_CheckerName", $data['PickOrder_CheckerName']);
        $this->db->set("PickOrder_Status", $data['PickOrder_Status']);
        $this->db->set("PickOrder_IsPrint", $data['PickOrder_IsPrint']);
        $this->db->set("PickOrder_PrintDate", null);
        $this->db->set("PickOrder_UserPrint", null);

        $this->db->set("PickOrder_UserCreate", $this->session->has_userdata('UserID'));
        $this->db->set("PickOrder_DateCreate", "GETDATE()", false);

        $this->db->insert('PickOrder');
    }

    public function savePickOrderDetail($data)
    {
        $this->db->set("PickOrderD_ID", "NEWID()", false);
        $this->db->set("PickOrder_ID", $data['PickOrder_ID']);
        $this->db->set("Gudang2_ID", $data['Gudang2_ID']);
        $this->db->set("SKU_UnitMandiriID", $data['SKU_UnitMandiriID']);
        $this->db->set("PickOrderD_ExpiredDate", $data['PickOrderD_ExpiredDate']);
        $this->db->set("PickOrderD_SatuanUnit", $data['PickOrderD_SatuanUnit']);
        $this->db->set("PickOrderD_QtyAssigned", $data['PickOrderD_QtyAssigned']);
        $this->db->set("PickOrderD_QtyActual", $data['PickOrderD_QtyActual']);

        $this->db->insert('PickOrderD');
    }
}
