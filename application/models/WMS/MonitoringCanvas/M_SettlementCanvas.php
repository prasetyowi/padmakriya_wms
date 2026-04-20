<?php

class M_SettlementCanvas extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getDepoPrefix($depo_id)
    {
        $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
        return $listDoBatch->row();
    }

    public function Get_Driver()
    {
        $this->db->select("*")
            ->from("karyawan")
            ->where("karyawan_level_id", "339d8ac2-c6ce-4b47-9bfc-e372592af521")
            ->where("depo_id", $this->session->userdata('depo_id'))
            ->order_by("karyawan_nama");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function cek_settlement_by_fdjr($delivery_order_batch_id)
    {
        $query = $this->db->query("select * from delivery_order_settlement where delivery_order_id in (select delivery_order_id from delivery_order_canvas where delivery_order_batch_id = '$delivery_order_batch_id')");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->num_rows();
        }

        return $query;
    }

    public function get_settlement_pengiriman_by_filter($Tgl_FDJR, $Tgl_FDJR2, $No_FDJR, $karyawan_id)
    {
        $status = "'Closing Delivery Confirm','completed'";

        // if ($No_FDJR == "") {
        // 	$No_FDJR = "";
        // } else {
        // 	$No_FDJR = " AND delivery_order_batch.delivery_order_batch_kode = '$No_FDJR' ";
        // }
        // if ($karyawan_id == "") {
        // 	$karyawan_id = "";
        // } else {
        // 	$karyawan_id = " AND delivery_order_batch.karyawan_id = '$karyawan_id' ";
        // }

        if ($No_FDJR == "") {
            $No_FDJR = "null";
        } else {
            $No_FDJR = "'$No_FDJR'";
        }

        if ($karyawan_id == "") {
            $karyawan_id = "null";
        } else {
            $karyawan_id = "'$karyawan_id'";
        }

        $query = $this->db->query("exec proses_settlement_view_canvas '" . $this->session->userdata('depo_id') . "', '$Tgl_FDJR', '$Tgl_FDJR2', " . $karyawan_id . ", " . $No_FDJR . " ");

        // $query = $this->db->query("SELECT
        // 								delivery_order_batch.delivery_order_batch_id,
        // 								delivery_order_batch.delivery_order_batch_kode,
        // 								picking_list.picking_list_id,
        // 								ISNULL(picking_list.picking_list_kode,'') AS picking_list_kode,
        // 								picking_order.picking_order_id,
        // 								ISNULL(picking_order.picking_order_kode,'') AS picking_order_kode,
        // 								FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
        // 								delivery_order_batch.delivery_order_batch_status,
        // 								delivery_order_batch.karyawan_id,
        // 								driver.karyawan_nama,
        // 								serah_terima_kirim.serah_terima_kirim_id,
        // 								ISNULL(serah_terima_kirim.serah_terima_kirim_kode,'') AS serah_terima_kirim_kode,
        // 								delivery_order_batch.tipe_delivery_order_id,
        // 								tipe_delivery_order.tipe_delivery_order_nama,
        // 								CASE WHEN delivery_order_settlement.delivery_order_id IS NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN delivery_order_settlement.delivery_order_id IS NOT NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END AS settlement_status
        // 							FROM delivery_order_batch
        // 							LEFT JOIN delivery_order
        // 							ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
        // 							LEFT JOIN picking_list
        // 							ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
        // 							LEFT JOIN picking_order
        // 							ON picking_list.picking_list_id = picking_order.picking_list_id
        // 							LEFT JOIN serah_terima_kirim
        // 							ON serah_terima_kirim.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
        // 							LEFT JOIN delivery_order_settlement
        // 							ON delivery_order_settlement.delivery_order_id = delivery_order.delivery_order_id
        // 							LEFT JOIN tipe_delivery_order
        // 							ON delivery_order_batch.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
        // 							LEFT JOIN karyawan driver
        // 							ON delivery_order_batch.karyawan_id = driver.karyawan_id
        // 							WHERE FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') BETWEEN '$Tgl_FDJR' 
        // 							AND '$Tgl_FDJR2' 
        // 							AND delivery_order_batch.depo_id = '" . $this->session->userdata('depo_id') . "' 
        // 							AND delivery_order_batch.delivery_order_batch_status IN (" . $status . ") 
        // 							" . $No_FDJR . " " . $karyawan_id . "
        // 							GROUP BY delivery_order_batch.delivery_order_batch_id,
        // 								delivery_order_batch.delivery_order_batch_kode,
        // 								picking_list.picking_list_id,
        // 								ISNULL(picking_list.picking_list_kode,''),
        // 								picking_order.picking_order_id,
        // 								ISNULL(picking_order.picking_order_kode,''),
        // 								FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'dd-MM-yyyy'),
        // 								delivery_order_batch.delivery_order_batch_status,
        // 								delivery_order_batch.karyawan_id,
        // 								driver.karyawan_nama,
        // 								serah_terima_kirim.serah_terima_kirim_id,
        // 								ISNULL(serah_terima_kirim.serah_terima_kirim_kode,''),
        // 								delivery_order_batch.tipe_delivery_order_id,
        // 								tipe_delivery_order.tipe_delivery_order_nama,
        // 								CASE WHEN delivery_order_settlement.delivery_order_id IS NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN delivery_order_settlement.delivery_order_id IS NOT NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END
        // 							ORDER BY delivery_order_batch.delivery_order_batch_kode ASC");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result();
        }

        return $query;
        // return $this->db->last_query();
    }

    public function Get_SettlementHeader($delivery_order_batch_id)
    {
        $query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										delivery_order_batch.delivery_order_batch_status,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										COUNT(DISTINCT delivery_order_settlement.delivery_order_id) AS jumlah
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_settlement
									ON delivery_order_settlement.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									GROUP BY delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										delivery_order_batch_status,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama ");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Get_PengirimanArea($delivery_order_batch_id)
    {
        $query = $this->db->query("SELECT
									area.*
									FROM delivery_order_area
									LEFT JOIN area
									ON area.area_id = delivery_order_area.area_id
									WHERE delivery_order_area.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY area.area_nama ASC");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Exec_SettlementPenerimaanBarang($delivery_order_batch_id)
    {
        $query = $this->db->query("exec proses_settlement_canvas '$delivery_order_batch_id'");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Exec_SettlementPenerimaanTunai($delivery_order_batch_id)
    {
        $query = $this->db->query("exec proses_settlement_tunai_canvas '$delivery_order_batch_id'");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Exec_SettlementPenerimaanBG($delivery_order_batch_id)
    {
        $query = $this->db->query("exec proses_settlement_bg '$delivery_order_batch_id'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Exec_SettlementKomparasiInvoiceTunaivsPenerimaanTunai($delivery_order_batch_id)
    {
        $query = $this->db->query("exec proses_settlement_invoicevstunai_canvas '$delivery_order_batch_id'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Insert_Settlement($delivery_order_batch_id, $statussettlement)
    {
        $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $this->session->userdata('depo_id'))->get();

        $Tanggal = date('Y-m-d h:i:s');
        $Prefix = "STL";
        $Unit = $listDoBatch->row()->depo_kode_preffix;

        $query = $this->db->query("SELECT * FROM delivery_order WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");
        foreach ($query->result() as $row) {

            $query_kode = $this->db->query("declare @res varchar(50)  
					exec GenCodeGeneral ?, ?, ?, @res OUTPUT
					select @res as resultMessage", array($Tanggal, $Prefix, $Unit));

            $res = $query_kode->result_array();

            $delivery_order_settlement_kode = $res[0]['resultMessage'];

            $this->db->set("delivery_order_settlement_id", "NewID()", FALSE);
            $this->db->set("delivery_order_id", $row->delivery_order_id);
            $this->db->set("delivery_order_settlement_kode", $delivery_order_settlement_kode);
            $this->db->set("delivery_order_settlement_tgl", "GETDATE()", FALSE);
            $this->db->set("delivery_order_settlement_who", $this->session->userdata('pengguna_username'));
            $this->db->set("delivery_order_settlement_status", $statussettlement);

            $this->db->insert("delivery_order_settlement");
        }

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
        // return $this->db->last_query();
    }

    public function Exec_ProsesSettlementDetail($delivery_order_batch_id, $sku_id)
    {
        $query = $this->db->query("exec proses_settlement_detail '$delivery_order_batch_id', '$sku_id' ");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Get_DetailBKB($picking_order_aktual_kode, $sku_id)
    {
        $query = $this->db->query("SELECT
									picking_order_aktual_h.picking_order_aktual_h_id,
									picking_order_aktual_h.picking_order_aktual_kode,
									picking_order_aktual_h.picking_order_aktual_tgl,
									picking_order_aktual_h.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_aktual_d.delivery_order_id,
									delivery_order.delivery_order_kode,
									picking_order_aktual_h.karyawan_id,
									picking_order_aktual_h.karyawan_nama,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_aktual_d.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_aktual_d.picking_order_plan_id,
									picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil_plan,
									picking_order_aktual_d.sku_stock_qty_ambil,
									picking_order_aktual_d.sku_stock_id,
									FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_plan,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
								FROM picking_order_aktual_h
								LEFT JOIN picking_order_aktual_d
									ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
								LEFT JOIN picking_order
									ON picking_order_aktual_h.picking_order_id = picking_order.picking_order_id
								LEFT JOIN picking_order_plan
									ON picking_order_plan.picking_order_plan_id = picking_order_aktual_d.picking_order_plan_id
									AND picking_order_plan.picking_order_id = picking_order.picking_order_id
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_aktual_d.delivery_order_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_order_aktual_d.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
								WHERE picking_order_aktual_h.picking_order_aktual_kode = '$picking_order_aktual_kode' AND picking_order_aktual_d.sku_id = '$sku_id'
								ORDER BY sku.sku_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Get_HeaderDO($delivery_order_kode)
    {
        $query = $this->db->query("SELECT
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias,
									delivery_order.delivery_order_status,
									FORMAT(delivery_order.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(delivery_order.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(delivery_order.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(delivery_order.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									delivery_order.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									delivery_order.delivery_order_keterangan,
									CASE WHEN delivery_order.delivery_order_tipe_pembayaran = '1' THEN 'KREDIT' ELSE 'TUNAI' END AS delivery_order_tipe_pembayaran,
									delivery_order.delivery_order_tipe_layanan,
									ISNULL(delivery_order.delivery_order_kirim_nama,'') AS delivery_order_kirim_nama,
									delivery_order.delivery_order_kirim_alamat,
									delivery_order.delivery_order_kirim_area,
									ISNULL(delivery_order.delivery_order_ambil_nama,'') AS delivery_order_ambil_nama,
									delivery_order.delivery_order_ambil_alamat,
									delivery_order.delivery_order_ambil_area
									FROM delivery_order
									LEFT JOIN client_wms
									ON client_wms.client_wms_id = delivery_order.client_wms_id
									LEFT JOIN tipe_delivery_order
									ON delivery_order.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
									WHERE delivery_order.delivery_order_kode = '$delivery_order_kode' ");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Get_DetailDO($delivery_order_kode, $sku_id)
    {
        $query = $this->db->query("SELECT
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order_detail.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_detail.sku_request_expdate,
									ISNULL(delivery_order_detail.sku_keterangan,'') sku_keterangan,
									delivery_order_detail.sku_qty,
									delivery_order_detail.sku_qty_kirim
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN sku
									ON sku.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_kode = '$delivery_order_kode'
									AND delivery_order_detail.sku_id = '$sku_id'
									ORDER BY sku.sku_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function GetHeaderDOById($delivery_order_kode)
    {
        $query = $this->db->query("SELECT
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias,
									delivery_order.delivery_order_status,
									FORMAT(delivery_order.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(delivery_order.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(delivery_order.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(delivery_order.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									delivery_order.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									delivery_order.delivery_order_keterangan,
									CASE WHEN delivery_order.delivery_order_tipe_pembayaran = '1' THEN 'KREDIT' ELSE 'TUNAI' END AS delivery_order_tipe_pembayaran,
									delivery_order.delivery_order_tipe_layanan,
									ISNULL(delivery_order.delivery_order_kirim_nama,'') AS delivery_order_kirim_nama,
									delivery_order.delivery_order_kirim_alamat,
									delivery_order.delivery_order_kirim_area,
									ISNULL(delivery_order.delivery_order_ambil_nama,'') AS delivery_order_ambil_nama,
									delivery_order.delivery_order_ambil_alamat,
									delivery_order.delivery_order_ambil_area
									FROM delivery_order
									LEFT JOIN client_wms
									ON client_wms.client_wms_id = delivery_order.client_wms_id
									LEFT JOIN tipe_delivery_order
									ON delivery_order.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
									WHERE delivery_order.delivery_order_kode = '$delivery_order_kode' ");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function GetDetailDOById($delivery_order_kode)
    {
        $query = $this->db->query("SELECT
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order_detail.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_detail.sku_request_expdate,
									ISNULL(delivery_order_detail.sku_keterangan,'') sku_keterangan,
									delivery_order_detail.sku_qty,
									delivery_order_detail.sku_qty_kirim
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN sku
									ON sku.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_kode = '$delivery_order_kode'
									ORDER BY sku.sku_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->db->last_query();
        return $query;
    }

    public function Update_DOSkuQty($delivery_order_id, $sku_id, $sku_qty)
    {
        //update delivery_order
        $this->db->set("sku_qty", $sku_qty);
        $this->db->where("delivery_order_id", $delivery_order_id);
        $this->db->where("sku_id", $sku_id);

        $this->db->update("delivery_order_detail");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryupdate = 1;
        } else {
            $queryupdate = 0;
        }

        return $queryupdate;
    }

    public function GetArea()
    {
        $query = $this->db->query("SELECT
										area.*
									FROM depo_area_header
									LEFT JOIN area_header
									ON depo_area_header.area_header_id = area_header.area_header_id
									LEFT JOIN area
									ON area_header.area_header_id = area.area_header_id
									WHERE depo_area_header.depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY area.area_nama ASC");

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

    public function GetPerusahaanByFDJR($delivery_order_batch_id)
    {
        $query = $this->db->query("SELECT DISTINCT
										client_wms.*
									FROM delivery_order
									LEFT JOIN client_wms
									ON client_wms.client_wms_id = delivery_order.client_wms_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY client_wms.client_wms_nama");

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
            ->where_in("tipe_delivery_order_id", array("F1E7E5C6-FB70-4E07-993C-3551E5B7DDC3", "23080729-40FB-42B2-B58E-588E0C3CCC3F"))
            ->order_by("tipe_delivery_order_alias");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetSKUDODetailBySettlement($delivery_order_batch_id)
    {
        $query = $this->db->query("SELECT DISTINCT
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									delivery_order_detail.sku_id,
									sku.sku_induk_id,
									sku_induk.sku_induk_nama AS sku_induk,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									ISNULL(sku.sku_harga_jual, '0') AS sku_harga_jual,
									sku.sku_satuan,
									sku.sku_kemasan,
									ISNULL(sku.sku_weight, '0') AS sku_weight,
									ISNULL(sku.sku_weight_unit, '') AS sku_weight_unit,
									ISNULL(sku.sku_length, '0') AS sku_length,
									ISNULL(sku.sku_length_unit, '') AS sku_length_unit,
									ISNULL(sku.sku_width, '0') AS sku_width,
									ISNULL(sku.sku_width_unit, '') AS sku_width_unit,
									ISNULL(sku.sku_height, '0') AS sku_height,
									ISNULL(sku.sku_height_unit, '') AS sku_height_unit,
									ISNULL(sku.sku_volume, '0') AS sku_volume,
									ISNULL(sku.sku_volume_unit, '') AS sku_volume_unit
								FROM delivery_order_batch
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
								LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
								LEFT JOIN (SELECT
									penerimaan_penjualan.delivery_order_batch_id,
									penerimaan_penjualan_detail.delivery_order_id,
									penerimaan_penjualan_detail.sku_id,
									SUM(penerimaan_penjualan_detail.sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(penerimaan_penjualan_detail.sku_jumlah_terima) AS sku_jumlah_terima
								FROM penerimaan_penjualan
								LEFT JOIN penerimaan_penjualan_detail
									ON penerimaan_penjualan_detail.penerimaan_penjualan_id = penerimaan_penjualan.penerimaan_penjualan_id
								GROUP BY penerimaan_penjualan.delivery_order_batch_id,
										penerimaan_penjualan_detail.delivery_order_id,
										penerimaan_penjualan_detail.sku_id) penerimaan
									ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									AND penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
								LEFT JOIN (SELECT
									delivery_order.delivery_order_batch_id,
									delivery_order_detail.sku_id,
									SUM(delivery_order_detail.sku_qty) AS sku_qty,
									SUM(delivery_order_detail.sku_qty_kirim) AS sku_qty_kirim
								FROM delivery_order
								LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
								WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
								AND delivery_order.tipe_delivery_order_id IN ('F1E7E5C6-FB70-4E07-993C-3551E5B7DDC3', '23080729-40FB-42B2-B58E-588E0C3CCC3F','C5BE83E2-01E8-4E24-B766-26BB4158F2CD')
								GROUP BY delivery_order.delivery_order_batch_id,
										delivery_order_detail.sku_id) do_sisipan
									ON do_sisipan.delivery_order_batch_id = delivery_order_detail.delivery_order_batch_id
									AND do_sisipan.sku_id = delivery_order_detail.sku_id
								LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
								LEFT JOIN sku_induk
									ON sku_induk.sku_induk_id = sku.sku_induk_id
								LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
								LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
								LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
								LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
								WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
								AND ((penerimaan.delivery_order_batch_id IS NOT NULL
								AND ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
								AND ISNULL(penerimaan.sku_jumlah_terima, 0) - ISNULL(do_sisipan.sku_qty, 0) > 0)
								OR penerimaan.delivery_order_batch_id IS NULL)
								ORDER BY sku.sku_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
        // return $this->db->last_query();
    }

    public function Insert_DeliveryOrderProgressStatus($delivery_order_id, $status_progress_id, $status_progress_nama)
    {
        //insert delivery_order_progress
        $this->db->set("delivery_order_progress_id", "NewID()", FALSE);
        $this->db->set("delivery_order_id", $delivery_order_id);
        $this->db->set("status_progress_id", $status_progress_id);
        $this->db->set("status_progress_nama", $status_progress_nama);
        $this->db->set("delivery_order_progress_create_who", $this->session->userdata('pengguna_username'));
        $this->db->set("delivery_order_progress_create_tgl", "GETDATE()", FALSE);

        $this->db->insert("delivery_order_progress");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function insert_delivery_order($delivery_order_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode)
    {
        // $delivery_order_id = $delivery_order_draft_id == '' ? null : $delivery_order_draft_id;
        $delivery_order_batch_id = $delivery_order_batch_id == '' ? null : $delivery_order_batch_id;
        $sales_order_id = $sales_order_id == '' ? null : $sales_order_id;
        // $delivery_order_kode = $delivery_order_draft_kode == '' ? null : $delivery_order_draft_kode;
        // $delivery_order_yourref = $delivery_order_draft_yourref == '' ? null : $delivery_order_draft_yourref;
        $client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
        $delivery_order_tgl_buat_do = $delivery_order_tgl_buat_do == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_buat_do)));
        $delivery_order_tgl_expired_do = $delivery_order_tgl_expired_do == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_expired_do)));
        $delivery_order_tgl_surat_jalan = $delivery_order_tgl_surat_jalan == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_surat_jalan)));
        $delivery_order_tgl_rencana_kirim = $delivery_order_tgl_rencana_kirim == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_rencana_kirim)));
        $delivery_order_tgl_aktual_kirim = $delivery_order_tgl_aktual_kirim == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_aktual_kirim)));
        $delivery_order_keterangan = $delivery_order_keterangan == '' ? null : $delivery_order_keterangan;
        // $delivery_order_status = $delivery_order_status == '' ? null : $delivery_order_status;
        $delivery_order_is_prioritas = $delivery_order_is_prioritas == '' ? null : $delivery_order_is_prioritas;
        $delivery_order_is_need_packing = $delivery_order_is_need_packing == '' ? null : $delivery_order_is_need_packing;
        $delivery_order_tipe_layanan = $delivery_order_tipe_layanan == '' ? null : $delivery_order_tipe_layanan;
        $delivery_order_tipe_pembayaran = $delivery_order_tipe_pembayaran == '' ? null : $delivery_order_tipe_pembayaran;
        $delivery_order_sesi_pengiriman = $delivery_order_sesi_pengiriman == '' ? null : $delivery_order_sesi_pengiriman;
        $delivery_order_request_tgl_kirim = $delivery_order_request_tgl_kirim == '' ? null : $delivery_order_request_tgl_kirim;
        $delivery_order_request_jam_kirim = $delivery_order_request_jam_kirim == '' ? null : $delivery_order_request_jam_kirim;
        $tipe_pengiriman_id = $tipe_pengiriman_id == '' ? null : $tipe_pengiriman_id;
        $nama_tipe = $nama_tipe == '' ? null : $nama_tipe;
        $confirm_rate = $confirm_rate == '' ? null : $confirm_rate;
        $delivery_order_reff_id = $delivery_order_reff_id == '' ? null : $delivery_order_reff_id;
        $delivery_order_reff_no = $delivery_order_reff_no == '' ? null : $delivery_order_reff_no;
        $delivery_order_total = $delivery_order_total == '' ? null : $delivery_order_total;
        $unit_mandiri_id = $unit_mandiri_id == '' ? null : $unit_mandiri_id;
        $depo_id = $depo_id == '' ? null : $depo_id;
        $client_pt_id = $client_pt_id == '' ? null : $client_pt_id;
        $delivery_order_kirim_nama = $delivery_order_kirim_nama == '' ? null : $delivery_order_kirim_nama;
        $delivery_order_kirim_alamat = $delivery_order_kirim_alamat == '' ? null : $delivery_order_kirim_alamat;
        $delivery_order_kirim_telp = $delivery_order_kirim_telp == '' ? null : $delivery_order_kirim_telp;
        $delivery_order_kirim_provinsi = $delivery_order_kirim_provinsi == '' ? null : $delivery_order_kirim_provinsi;
        $delivery_order_kirim_kota = $delivery_order_kirim_kota == '' ? null : $delivery_order_kirim_kota;
        $delivery_order_kirim_kecamatan = $delivery_order_kirim_kecamatan == '' ? null : $delivery_order_kirim_kecamatan;
        $delivery_order_kirim_kelurahan = $delivery_order_kirim_kelurahan == '' ? null : $delivery_order_kirim_kelurahan;
        // $delivery_order_kirim_latitude = $delivery_order_kirim_latitude == '' ? null : $delivery_order_kirim_latitude;
        // $delivery_order_kirim_longitude = $delivery_order_kirim_longitude == '' ? null : $delivery_order_kirim_longitude;
        $delivery_order_kirim_kodepos = $delivery_order_kirim_kodepos == '' ? null : $delivery_order_kirim_kodepos;
        $delivery_order_kirim_area = $delivery_order_kirim_area == '' ? null : $delivery_order_kirim_area;
        $delivery_order_kirim_invoice_pdf = $delivery_order_kirim_invoice_pdf == '' ? null : $delivery_order_kirim_invoice_pdf;
        $delivery_order_kirim_invoice_dir = $delivery_order_kirim_invoice_dir == '' ? null : $delivery_order_kirim_invoice_dir;
        $principle_id = $principle_id == '' ? null : $principle_id;
        $delivery_order_ambil_nama = $delivery_order_ambil_nama == '' ? null : $delivery_order_ambil_nama;
        $delivery_order_ambil_alamat = $delivery_order_ambil_alamat == '' ? null : $delivery_order_ambil_alamat;
        $delivery_order_ambil_telp = $delivery_order_ambil_telp == '' ? null : $delivery_order_ambil_telp;
        $delivery_order_ambil_provinsi = $delivery_order_ambil_provinsi == '' ? null : $delivery_order_ambil_provinsi;
        $delivery_order_ambil_kota = $delivery_order_ambil_kota == '' ? null : $delivery_order_ambil_kota;
        $delivery_order_ambil_kecamatan = $delivery_order_ambil_kecamatan == '' ? null : $delivery_order_ambil_kecamatan;
        $delivery_order_ambil_kelurahan = $delivery_order_ambil_kelurahan == '' ? null : $delivery_order_ambil_kelurahan;
        // $delivery_order_ambil_latitude = $delivery_order_ambil_latitude == '' ? null : $delivery_order_ambil_latitude;
        // $delivery_order_ambil_longitude = $delivery_order_ambil_longitude == '' ? null : $delivery_order_ambil_longitude;
        $delivery_order_ambil_kodepos = $delivery_order_ambil_kodepos == '' ? null : $delivery_order_ambil_kodepos;
        $delivery_order_ambil_area = $delivery_order_ambil_area == '' ? null : $delivery_order_ambil_area;
        // $delivery_order_update_who = $delivery_order_update_who == '' ? null : $delivery_order_update_who;
        // $delivery_order_update_tgl = $delivery_order_update_tgl == '' ? null : $delivery_order_update_tgl;
        // $delivery_order_approve_who = $delivery_order_approve_who == '' ? null : $delivery_order_approve_who;
        // $delivery_order_approve_tgl = $delivery_order_approve_tgl == '' ? null : $delivery_order_approve_tgl;
        // $delivery_order_reject_who = $delivery_order_reject_who == '' ? null : $delivery_order_reject_who;
        // $delivery_order_reject_tgl = $delivery_order_reject_tgl == '' ? null : $delivery_order_reject_tgl;
        // $delivery_order_reject_reason = $delivery_order_reject_reason == '' ? null : $delivery_order_reject_reason;
        // $delivery_order_no_urut_rute = $delivery_order_no_urut_rute == '' ? null : $delivery_order_no_urut_rute;
        // $delivery_order_prioritas_stock = $delivery_order_prioritas_stock == '' ? null : $delivery_order_prioritas_stock;
        $tipe_delivery_order_id = $tipe_delivery_order_id == '' ? null : $tipe_delivery_order_id;
        $delivery_order_draft_id = $delivery_order_draft_id == '' ? null : $delivery_order_draft_id;
        $delivery_order_draft_kode = $delivery_order_draft_kode == '' ? null : $delivery_order_draft_kode;

        $this->db->set("delivery_order_id", $delivery_order_id);
        $this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
        $this->db->set("sales_order_id", $sales_order_id);
        $this->db->set("delivery_order_kode", $delivery_order_kode);
        $this->db->set("delivery_order_yourref", NULL);
        $this->db->set("client_wms_id", $client_wms_id);
        $this->db->set("delivery_order_tgl_buat_do", $delivery_order_tgl_buat_do);
        $this->db->set("delivery_order_tgl_expired_do", $delivery_order_tgl_expired_do);
        $this->db->set("delivery_order_tgl_surat_jalan", $delivery_order_tgl_surat_jalan);
        $this->db->set("delivery_order_tgl_rencana_kirim", $delivery_order_tgl_rencana_kirim);
        $this->db->set("delivery_order_tgl_aktual_kirim", $delivery_order_tgl_aktual_kirim);
        $this->db->set("delivery_order_keterangan", $delivery_order_keterangan);
        $this->db->set("delivery_order_status", $delivery_order_status);
        $this->db->set("delivery_order_is_prioritas", $delivery_order_is_prioritas);
        $this->db->set("delivery_order_is_need_packing", $delivery_order_is_need_packing);
        $this->db->set("delivery_order_tipe_layanan", $delivery_order_tipe_layanan);
        $this->db->set("delivery_order_tipe_pembayaran", $delivery_order_tipe_pembayaran);
        $this->db->set("delivery_order_sesi_pengiriman", $delivery_order_sesi_pengiriman);
        $this->db->set("delivery_order_request_tgl_kirim", $delivery_order_request_tgl_kirim);
        $this->db->set("delivery_order_request_jam_kirim", $delivery_order_request_jam_kirim);
        $this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
        $this->db->set("nama_tipe", $nama_tipe);
        $this->db->set("confirm_rate", $confirm_rate);
        $this->db->set("delivery_order_reff_id", $delivery_order_reff_id);
        $this->db->set("delivery_order_reff_no", $delivery_order_reff_no);
        $this->db->set("delivery_order_total", $delivery_order_total);
        // $this->db->set("unit_mandiri_id", $unit_mandiri_id);
        $this->db->set("depo_id", $depo_id);
        $this->db->set("client_pt_id", $client_pt_id);
        $this->db->set("delivery_order_kirim_nama", $delivery_order_kirim_nama);
        $this->db->set("delivery_order_kirim_alamat", $delivery_order_kirim_alamat);
        $this->db->set("delivery_order_kirim_telp", $delivery_order_kirim_telp);
        $this->db->set("delivery_order_kirim_provinsi", $delivery_order_kirim_provinsi);
        $this->db->set("delivery_order_kirim_kota", $delivery_order_kirim_kota);
        $this->db->set("delivery_order_kirim_kecamatan", $delivery_order_kirim_kecamatan);
        $this->db->set("delivery_order_kirim_kelurahan", $delivery_order_kirim_kelurahan);
        // $this->db->set("delivery_order_kirim_latitude", $delivery_order_kirim_latitude);
        // $this->db->set("delivery_order_kirim_longitude", $delivery_order_kirim_longitude);
        $this->db->set("delivery_order_kirim_latitude", NULL);
        $this->db->set("delivery_order_kirim_longitude", NULL);
        $this->db->set("delivery_order_kirim_kodepos", $delivery_order_kirim_kodepos);
        $this->db->set("delivery_order_kirim_area", $delivery_order_kirim_area);
        $this->db->set("delivery_order_kirim_invoice_pdf", $delivery_order_kirim_invoice_pdf);
        $this->db->set("delivery_order_kirim_invoice_dir", $delivery_order_kirim_invoice_dir);
        $this->db->set("principle_id", $principle_id);
        $this->db->set("delivery_order_ambil_nama", $delivery_order_ambil_nama);
        $this->db->set("delivery_order_ambil_alamat", $delivery_order_ambil_alamat);
        $this->db->set("delivery_order_ambil_telp", $delivery_order_ambil_telp);
        $this->db->set("delivery_order_ambil_provinsi", $delivery_order_ambil_provinsi);
        $this->db->set("delivery_order_ambil_kota", $delivery_order_ambil_kota);
        $this->db->set("delivery_order_ambil_kecamatan", $delivery_order_ambil_kecamatan);
        $this->db->set("delivery_order_ambil_kelurahan", $delivery_order_ambil_kelurahan);
        // $this->db->set("delivery_order_ambil_latitude", $delivery_order_ambil_latitude);
        // $this->db->set("delivery_order_ambil_longitude", $delivery_order_ambil_longitude);
        $this->db->set("delivery_order_ambil_latitude", NULL);
        $this->db->set("delivery_order_ambil_longitude", NULL);
        $this->db->set("delivery_order_ambil_kodepos", $delivery_order_ambil_kodepos);
        $this->db->set("delivery_order_ambil_area", $delivery_order_ambil_area);
        $this->db->set("delivery_order_update_who", $this->session->userdata('pengguna_username'));
        $this->db->set("delivery_order_update_tgl", "GETDATE()", FALSE);
        $this->db->set("delivery_order_approve_who", NULL);
        $this->db->set("delivery_order_approve_tgl", NULL);
        $this->db->set("delivery_order_reject_who", NULL);
        $this->db->set("delivery_order_reject_tgl", NULL);
        $this->db->set("delivery_order_reject_reason", NULL);
        $this->db->set("delivery_order_no_urut_rute", $delivery_order_no_urut_rute);
        $this->db->set("delivery_order_prioritas_stock", NULL);
        $this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
        $this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
        $this->db->set("delivery_order_draft_kode", $delivery_order_draft_kode);

        $queryinsert = $this->db->insert("delivery_order");

        return $queryinsert;
        // return $this->db->last_query();
    }

    public function insert_delivery_order_detail($delivery_order_detail_id, $delivery_order_id, $delivery_order_batch_id, $data)
    {
        // $delivery_order_detail_id = $data['delivery_order_detail_id'] == '' ? null : $data['delivery_order_detail_id'];
        // $delivery_order_id = $data['delivery_order_id'] == '' ? null : $data['delivery_order_id'];
        // $delivery_order_batch_id = $data['delivery_order_batch_id'] == '' ? null : $data['delivery_order_batch_id'];
        $sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];
        $depo_id = $data['depo_id'] == '' ? null : $data['depo_id'];
        $depo_detail_id = $data['depo_detail_id'] == '' ? null : $data['depo_detail_id'];
        $sku_kode = $data["sku_kode"] == "" || $data["sku_kode"] == "null" ? null : $data["sku_kode"];
        $sku_nama_produk = $data["sku_nama_produk"] == "" || $data["sku_nama_produk"] == "null" ? null : $data["sku_nama_produk"];
        $sku_harga_satuan = $data["sku_harga_satuan"] == "" || $data["sku_harga_satuan"] == "null" ? null : $data["sku_harga_satuan"];
        $sku_disc_percent = $data["sku_disc_percent"] == "" || $data["sku_disc_percent"] == "null" ? null : $data["sku_disc_percent"];
        $sku_disc_rp = $data["sku_disc_rp"] == "" || $data["sku_disc_rp"] == "" ? null : $data["sku_disc_rp"];
        $sku_harga_nett = $data["sku_harga_nett"] == "" || $data["sku_harga_nett"] == "null" ? null : $data["sku_harga_nett"];
        $sku_request_expdate = $data["sku_request_expdate"] == "" || $data["sku_request_expdate"] == "null" ? null : $data["sku_request_expdate"];
        $sku_filter_expdate = $data["sku_request_expdate"] == "0" ? null : $data["sku_filter_expdate"];
        $sku_filter_expdatebulan = $data["sku_request_expdate"] == "0" ? null : $data["sku_filter_expdatebulan"];
        // $sku_filter_expdatetahun = $data["sku_filter_expdatetahun"] == "" ? null : $data["sku_filter_expdatetahun"];
        $sku_weight = $data["sku_weight"] == "" || $data["sku_weight"] == "null" ? null : $data["sku_weight"];
        $sku_weight_unit = $data["sku_weight_unit"] == "" || $data["sku_weight_unit"] == "null" ? null : $data["sku_weight_unit"];
        $sku_length = $data["sku_length"] == "" || $data["sku_length"] == "null" ? null : $data["sku_length"];
        $sku_length_unit = $data["sku_length_unit"] == "" || $data["sku_length_unit"] == "null" ? null : $data["sku_length_unit"];
        $sku_width = $data["sku_width"] == "" || $data["sku_width"] == "null" ? null : $data["sku_width"];
        $sku_width_unit = $data["sku_width_unit"] == "" || $data["sku_width_unit"] == "null" ? null : $data["sku_width_unit"];
        $sku_height = $data["sku_height"] == "" || $data["sku_height"] == "null" ? null : $data["sku_height"];
        $sku_height_unit = $data["sku_height_unit"] == "" || $data["sku_height_unit"] == "null" ? null : $data["sku_height_unit"];
        $sku_volume = $data["sku_volume"] == "" || $data["sku_volume"] == "null" ? null : $data["sku_volume"];
        $sku_volume_unit = $data["sku_volume_unit"] == "" || $data["sku_volume_unit"] == "null" ? null : $data["sku_volume_unit"];
        $sku_qty = $data["sku_qty"] == "" || $data["sku_qty"] == "null" ? null : $data["sku_qty"];
        $sku_keterangan = $data["sku_keterangan"] == "" || $data["sku_keterangan"] == "null" ? null : $data["sku_keterangan"];
        $tipe_stock_nama = $data['tipe_stock_nama'] == '' ? null : $data['tipe_stock_nama'];
        $sku_qty_kirim = $data['sku_qty_kirim'] == '' ? null : $data['sku_qty_kirim'];
        // $reason_id = $data['reason_id'] == '' ? null : $data['reason_id'];

        $this->db->set("delivery_order_detail_id", $delivery_order_detail_id);
        $this->db->set("delivery_order_id", $delivery_order_id);
        $this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
        $this->db->set("sku_id", $sku_id);
        $this->db->set("depo_id", $depo_id);
        $this->db->set("depo_detail_id", $depo_detail_id);
        $this->db->set("sku_kode", $sku_kode);
        $this->db->set("sku_nama_produk", $sku_nama_produk);
        $this->db->set("sku_harga_satuan", $sku_harga_satuan);
        $this->db->set("sku_disc_percent", $sku_disc_percent);
        $this->db->set("sku_disc_rp", $sku_disc_rp);
        $this->db->set("sku_harga_nett", $sku_harga_nett);
        $this->db->set("sku_request_expdate", $sku_request_expdate);
        $this->db->set("sku_filter_expdate", $sku_filter_expdate);
        $this->db->set("sku_filter_expdatebulan", $sku_filter_expdatebulan);
        // $this->db->set("sku_filter_expdatetahun", $sku_filter_expdatetahun);
        $this->db->set("sku_weight", $sku_weight);
        $this->db->set("sku_weight_unit", $sku_weight_unit);
        $this->db->set("sku_length", $sku_length);
        $this->db->set("sku_length_unit", $sku_length_unit);
        $this->db->set("sku_width", $sku_width);
        $this->db->set("sku_width_unit", $sku_width_unit);
        $this->db->set("sku_height", $sku_height);
        $this->db->set("sku_height_unit", $sku_height_unit);
        $this->db->set("sku_volume", $sku_volume);
        $this->db->set("sku_volume_unit", $sku_volume_unit);
        $this->db->set("sku_qty", $sku_qty);
        $this->db->set("sku_keterangan", $sku_keterangan);
        $this->db->set("sku_qty_kirim", $sku_qty_kirim);
        $this->db->set("reason_id", null);
        $this->db->set("tipe_stock_nama", $tipe_stock_nama);

        $queryinsert = $this->db->insert("delivery_order_detail");

        return $queryinsert;
        // return $this->db->last_query();
    }

    public function set_fdjr_status_completed($delivery_order_batch_id)
    {
        //update delivery_order
        $this->db->set("delivery_order_batch_status", "completed");
        $this->db->set("delivery_order_batch_update_tgl", 'GETDATE()', false);
        $this->db->where("delivery_order_batch_id", $delivery_order_batch_id);

        $this->db->update("delivery_order_batch");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryupdate = 1;
        } else {
            $queryupdate = 0;
        }

        return $queryupdate;
    }
}
