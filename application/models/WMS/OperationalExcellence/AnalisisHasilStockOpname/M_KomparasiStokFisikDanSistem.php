<?php

class M_KomparasiStokFisikDanSistem extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Get_Principle()
    {
        $query = $this->db->query("SELECT
										*
									FROM principle
                                    WHERE ISNULL(principle_is_aktif, 0) = '1'
									ORDER BY principle_kode ASC");


        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetPrincipleByPerusahaan($client_wms_id)
    {
        $query = $this->db->query("select
                                    a.principle_id,
                                    b.principle_kode,
                                    b.principle_nama
                                from client_wms_principle a
                                left join principle b
                                on b.principle_id = a.principle_id
                                WHERE ISNULL(b.principle_is_aktif, 0) = '1'
                                AND convert(nvarchar(36), a.client_wms_id) = '$client_wms_id'
                                ORDER BY b.principle_kode ASC");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_perusahaan()
    {
        // $this->db->select("*")
        // 	->from("client_wms")
        // 	->order_by("client_wms_nama");
        // $query = $this->db->get();

        if ($this->session->userdata('client_wms_id') == "") {

            $query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0
                        ORDER BY b.client_wms_nama ASC");
        } else {


            $query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.client_wms_id = '" . $this->session->userdata('client_wms_id') . "'
						AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0
                        ORDER BY b.client_wms_nama ASC");
        }

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_komparasi_stok_fisik_dan_sistem($tglawal, $tglakhir, $client_wms_id, $principle_id)
    {

        if ($principle_id == "") {
            $principle_id = "";
        } else {
            $principle_id = " AND hdr.principle_id = '$principle_id'";
        }

        $query =  $this->db->query("SELECT hdr.client_wms_id,
                                                cw.client_wms_nama,
                                                hdr.principle_id,
                                                p.principle_kode,
                                                FORMAT(hdr.tr_opname_plan_tanggal, 'yyyy-MM-dd') AS tr_opname_plan_tanggal,
                                                SUM(ISNULL(dtl3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS sku_actual_qty_opname_comp,
                                                SUM(ISNULL(dtl3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS sku_qty_sistem_comp,
                                                CASE 
                                                    WHEN SUM(ISNULL(dtl3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0)) = SUM(ISNULL(dtl3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0)) THEN '0'
                                                    ELSE '1'
                                                END AS is_selisih
                                            FROM tr_opname_plan hdr
                                            LEFT JOIN (SELECT topd3.tr_opname_plan_detail3_id,
                                                    topd3.tr_opname_plan_detail2_id,
                                                    topd3.tr_opname_plan_detail_id,
                                                    topd3.tr_opname_plan_id,
                                                    topd3.sku_id,
                                                    topd3.sku_stock_id,
                                                    topd3.sku_expired_date,
                                                    topd3.sku_actual_qty_opname,
                                                    topd3.sku_qty_sistem,
                                                    topd3.is_new_sku,
                                                    topd3.sku_batch_no,
                                                    sku.sku_konversi_faktor,
                                                    topd3.sku_actual_qty_opname * ISNULL(sku.sku_konversi_faktor, 0) sku_actual_qty_opname_comp,
                                                    topd3.sku_qty_sistem * ISNULL(sku.sku_konversi_faktor, 0) sku_qty_sistem_comp
                                            FROM tr_opname_plan_detail3 topd3
                                            LEFT JOIN sku ON sku.sku_id = topd3.sku_id) dtl3 ON dtl3.tr_opname_plan_id = hdr.tr_opname_plan_id
                                            LEFT JOIN sku ON sku.sku_id = dtl3.sku_id
                                            LEFT JOIN principle p ON p.principle_id = hdr.principle_id
                                            LEFT JOIN client_wms cw ON cw.client_wms_id = hdr.client_wms_id
                                            WHERE FORMAT(hdr.tr_opname_plan_tanggal, 'yyyy-MM-dd') BETWEEN '$tglawal' AND '$tglakhir'
                                            AND hdr.depo_id = '" . $this->session->userdata('depo_id') . "'
                                            AND hdr.client_wms_id = '$client_wms_id'
                                            AND hdr.tr_opname_plan_status = 'Completed'
                                            " . $principle_id . "
                                            GROUP BY hdr.client_wms_id,
                                                    cw.client_wms_nama,
                                                    hdr.principle_id,
                                                    p.principle_kode,
                                                    FORMAT(hdr.tr_opname_plan_tanggal, 'yyyy-MM-dd')
                                            ORDER BY cw.client_wms_nama, p.principle_kode, FORMAT(hdr.tr_opname_plan_tanggal, 'yyyy-MM-dd') ASC");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_detail_komparasi_stok_fisik_dan_sistem($tglawal, $tglakhir, $client_wms_id, $principle_id)
    {

        $query =  $this->db->query("SELECT hdr.tr_opname_plan_id,
                                        hdr.tr_opname_plan_kode,
                                        hdr.client_wms_id,
                                        cw.client_wms_nama,
                                        hdr.principle_id,
                                        p.principle_kode,
                                        FORMAT(hdr.tr_opname_plan_tanggal, 'yyyy-MM-dd') AS tr_opname_plan_tanggal,
                                        hdr.karyawan_id_penanggungjawab,
                                        kry.karyawan_nama,
                                        hdr.tipe_stok,
                                        hdr.tipe_opname_id,
                                        tipe.tipe_opname_nama,
                                        hdr.tr_opname_plan_status,
                                        hdr.tr_opname_plan_tgl_start,
                                        hdr.tr_opname_plan_tgl_end,
                                        dtl3.sku_id,
                                        sku.sku_kode,
                                        sku.sku_nama_produk,
                                        sku.sku_konversi_group,
                                        sku.sku_konversi_faktor,
                                        sku.sku_satuan,
                                        sku.sku_kemasan,
                                        dtl3.sku_stock_id,
                                        dtl3.sku_expired_date,
                                        ISNULL(dtl3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0) AS sku_actual_qty_opname_comp,
                                        ISNULL(dtl3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0) AS sku_qty_sistem_comp,
                                        CASE 
                                            WHEN ISNULL(dtl3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0) = ISNULL(dtl3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0) THEN '0'
                                            ELSE '1'
                                        END AS is_selisih
                                    FROM tr_opname_plan hdr
                                    LEFT JOIN tr_opname_plan_detail3 dtl3 ON dtl3.tr_opname_plan_id = hdr.tr_opname_plan_id
                                    LEFT JOIN sku ON sku.sku_id = dtl3.sku_id
                                    LEFT JOIN principle p ON p.principle_id = hdr.principle_id
                                    LEFT JOIN karyawan kry ON kry.karyawan_id = hdr.karyawan_id_penanggungjawab
                                    LEFT JOIN client_wms cw ON cw.client_wms_id = hdr.client_wms_id
                                    LEFT JOIN tipe_opname tipe ON tipe.tipe_opname_id = hdr.tipe_opname_id
                                    WHERE FORMAT(hdr.tr_opname_plan_tanggal, 'yyyy-MM-dd') BETWEEN '$tglawal' AND '$tglakhir'
                                    AND hdr.depo_id = '" . $this->session->userdata('depo_id') . "'
                                    AND hdr.client_wms_id = '$client_wms_id'
                                    AND hdr.tr_opname_plan_status = 'Completed'
                                    AND hdr.principle_id = '$principle_id'
                                    ORDER BY hdr.tr_opname_plan_kode ASC");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $data = $query->result();
        }

        return $data;
    }


    public function proses_konversi_sku_composite_simple($sku_group)
    {

        $query =  $this->db->query("exec proses_konversi_sku_composite_simple '$sku_group'");

        if ($query->num_rows() == 0) {
            $query = "";
        } else {
            $query = $query->row(0)->sku_composite;
        }

        return $query;
    }

    public function proses_konversi_sku_qty_composite_simple($sku_group, $qty, $sku_qty_faktor)
    {

        $query =  $this->db->query("exec proses_konversi_sku_qty_composite_simple '$sku_group',$qty,$sku_qty_faktor");

        if ($query->num_rows() == 0) {
            $query = "0/0/0";
        } else {
            $query = $query->row(0)->sku_composite_qty;
        }

        return $query;
    }
}
