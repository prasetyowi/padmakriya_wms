<?php

class M_KomparasiLokasiStockPalletGlobal2 extends CI_Model
{
    public function GetDepoDetail()
    {
        $this->db->select("*")
            ->from("depo_detail")
            ->where("depo_id", $this->session->userdata('depo_id'))
            ->where("depo_detail_flag_jual", 1)
            ->order_by("depo_detail_nama");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetPrinnciple()
    {
        $this->db->select("*")
            ->from("principle")
            ->where("principle_is_aktif", 1)
            ->order_by("principle_kode");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetStatusProgress()
    {
        $this->db->select("*")
            ->from("status_progress")
            ->where("status_progress_modul", "Delivery Order")
            ->order_by("status_progress_no");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetTipeDeliveryOrder()
    {
        $this->db->select("*")
            ->from("tipe_delivery_order")
            ->where("tipe_delivery_order_alias <>", "Mix")
            ->order_by("tipe_delivery_order_alias");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetTipeLayanan()
    {
        $this->db->select("*")
            ->from("tipe_layanan")
            ->where("tipe_layanan_is_aktif", "1")
            ->order_by("tipe_layanan_kode");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetPerusahaan()
    {
        $this->db->select("*")
            ->from("client_wms")
            ->order_by("client_wms_nama");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetPerusahaanById($id)
    {
        $this->db->select("*")
            ->from("client_wms")
            ->where("client_wms_id", $id)
            ->order_by("client_wms_nama");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_list_komparasi_lokasi_stock_pallet()
    {
        $query = $this->db->query("exec proses_maintenance_lokasi_stock_pallet_global '0', '" . $this->session->userdata('depo_id') . "'");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_komparasi_lokasi_stock_pallet_by_id($depo_detail_id)
    {

        $query = $this->db->query("SELECT
                                        a.depo_detail_id,
                                        c.pallet_id,
                                        d.depo_detail_nama,
                                        b.rak_lajur_detail_nama,
                                        c.pallet_kode
                                    FROM rak a
                                    INNER JOIN rak_lajur_detail b
                                        ON a.rak_id = b.rak_id
                                    INNER JOIN pallet c
                                        ON b.rak_lajur_detail_id = c.rak_lajur_detail_id
                                    INNER JOIN depo_detail d
                                        ON a.depo_detail_id = d.depo_detail_id
                                    WHERE a.depo_detail_id = '$depo_detail_id'");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_rak_lajur_detail_by_depo_detail_id($depo_detail_id)
    {

        $query = $this->db->query("SELECT
									a.rak_lajur_detail_id,
									a.rak_lajur_detail_nama
									FROM rak_lajur_detail a
									LEFT JOIN rak b
									ON b.rak_id = a.rak_id
									WHERE CONVERT(nvarchar(36), b.depo_detail_id) = '$depo_detail_id'
									ORDER BY a.rak_lajur_detail_nama ASC");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function update_pallet($pallet_id, $rak_lajur_detail_id)
    {
        $rak_lajur_detail_id = $rak_lajur_detail_id == "" ? null : $rak_lajur_detail_id;

        $this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);

        $this->db->where("pallet_id", $pallet_id);

        $queryinsert = $this->db->update("pallet");

        return $queryinsert;
        // return $this->db->last_query();
    }

    public function update_rak_lajur_detail_pallet($pallet_id, $rak_lajur_detail_id)
    {
        $rak_lajur_detail_id = $rak_lajur_detail_id == "" ? null : $rak_lajur_detail_id;

        $this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);

        $this->db->where("pallet_id", $pallet_id);

        $queryinsert = $this->db->update("rak_lajur_detail_pallet");

        return $queryinsert;
        // return $this->db->last_query();
    }
}