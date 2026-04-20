<?php

class M_AnalisisPerformaBongkarMuat extends CI_Model
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

    public function Get_analisis_performa_bongkar_muat($tglawal, $tglakhir, $client_wms_id, $principle_id)
    {

        if ($principle_id == "") {
            $principle_id = "";
        } else {
            $principle_id = " AND hdr.principle_id = '$principle_id'";
        }

        $query_laporan =  $this->db->query("SELECT 
                                                dtl4.karyawan_id,
                                                ISNULL(k.karyawan_nama, '') AS karyawan_nama,
                                                hdr.penerimaan_pembelian_id,
                                                hdr.depo_id,
                                                hdr.depo_detail_id,
                                                hdr.client_wms_id,
                                                cw.client_wms_nama,
                                                hdr.penerimaan_surat_jalan_id,
                                                hdr.penerimaan_tipe_id,
                                                hdr.penerimaan_pembelian_kode,
                                                FORMAT(hdr.penerimaan_pembelian_tgl, 'yyyy-MM-dd') AS penerimaan_pembelian_tgl,
                                                hdr.penerimaan_pembelian_keterangan,
                                                hdr.penerimaan_pembelian_who_create,
                                                hdr.penerimaan_pembelian_tgl_create,
                                                hdr.penerimaan_pembelian_status,
                                                hdr.principle_id,
                                                p.principle_kode,
                                                hdr.penerimaan_pembelian_tgl_update,
                                                hdr.penerimaan_pembelian_who_update,
                                                SUM(CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END) AS sku_jumlah_barang_comp_ctn,
                                                SUM(CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn,
                                                DATEDIFF(HOUR, hdr.penerimaan_pembelian_tgl_create, hdr.penerimaan_pembelian_tgl_update) AS waktu_pengerjaan
                                            FROM penerimaan_pembelian hdr
                                            LEFT JOIN (SELECT 
                                                ppd.penerimaan_pembelian_detail_id,
                                                ppd.penerimaan_pembelian_id,
                                                ppd.sku_id,
                                                ppd.penerimaan_tipe_id,
                                                ppd.sku_jumlah_barang,
                                                ppd.sku_jumlah_terima,
                                                ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                                                ppd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                                                ppd.sku_jumlah_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_terima_comp,
                                                ppd.sku_exp_date,
                                                ppd.batch_no,
                                                ppd.karyawan_id,
                                                ppd.is_tambah_manual
                                            FROM penerimaan_pembelian_detail ppd
                                            LEFT JOIN sku
                                            ON sku.sku_id = ppd.sku_id) dtl
                                            ON dtl.penerimaan_pembelian_id = hdr.penerimaan_pembelian_id
                                            LEFT JOIN penerimaan_pembelian_detail4 dtl4
                                            ON dtl4.penerimaan_pembelian_id = hdr.penerimaan_pembelian_id
                                            LEFT JOIN karyawan k
                                            ON k.karyawan_id = dtl4.karyawan_id
                                            LEFT JOIN principle p
                                            ON p.principle_id = hdr.principle_id
                                            LEFT JOIN client_wms cw
                                            ON cw.client_wms_id = hdr.client_wms_id
                                            WHERE FORMAT(hdr.penerimaan_pembelian_tgl, 'yyyy-MM-dd') BETWEEN '$tglawal' AND '$tglakhir'
                                            AND hdr.depo_id = '" . $this->session->userdata('depo_id') . "'
                                            AND hdr.client_wms_id = '$client_wms_id'
                                            " . $principle_id . "
                                            GROUP BY dtl4.karyawan_id,
                                                ISNULL(k.karyawan_nama, ''),
                                                hdr.penerimaan_pembelian_id,
                                                hdr.depo_id,
                                                hdr.depo_detail_id,
                                                hdr.client_wms_id,
                                                cw.client_wms_nama,
                                                hdr.penerimaan_surat_jalan_id,
                                                hdr.penerimaan_tipe_id,
                                                hdr.penerimaan_pembelian_kode,
                                                FORMAT(hdr.penerimaan_pembelian_tgl, 'yyyy-MM-dd'),
                                                hdr.penerimaan_pembelian_keterangan,
                                                hdr.penerimaan_pembelian_who_create,
                                                hdr.penerimaan_pembelian_tgl_create,
                                                hdr.penerimaan_pembelian_status,
                                                hdr.principle_id,
                                                p.principle_kode,
                                                hdr.penerimaan_pembelian_tgl_update,
                                                hdr.penerimaan_pembelian_who_update
                                            ORDER BY ISNULL(k.karyawan_nama, ''), FORMAT(hdr.penerimaan_pembelian_tgl, 'yyyy-MM-dd'), hdr.penerimaan_pembelian_kode ASC");

        if ($query_laporan->num_rows() == 0) {
            $query_laporan = array();
        } else {
            $data = $query_laporan->result_array();
        }

        return $data;
    }

    public function Get_detail_analisis_performa_bongkar_muat_by_id($penerimaan_pembelian_id)
    {

        $query_laporan =  $this->db->query("SELECT 
                                                dtl4.karyawan_id,
                                                k.karyawan_nama,
                                                hdr.penerimaan_pembelian_id,
                                                hdr.depo_id,
                                                hdr.depo_detail_id,
                                                hdr.client_wms_id,
                                                cw.client_wms_nama,
                                                hdr.penerimaan_surat_jalan_id,
                                                hdr.penerimaan_tipe_id,
                                                hdr.penerimaan_pembelian_kode,
                                                FORMAT(hdr.penerimaan_pembelian_tgl, 'yyyy-MM-dd') AS penerimaan_pembelian_tgl,
                                                hdr.penerimaan_pembelian_keterangan,
                                                hdr.penerimaan_pembelian_who_create,
                                                hdr.penerimaan_pembelian_tgl_create,
                                                hdr.penerimaan_pembelian_status,
                                                hdr.principle_id,
                                                p.principle_kode,
                                                dtl.sku_id,
                                                sku.sku_konversi_group,
                                                sku.sku_nama_produk,
                                                SUM(dtl.sku_jumlah_barang_comp) AS sku_jumlah_barang_comp,
                                                SUM(dtl.sku_jumlah_terima_comp) AS sku_jumlah_terima_comp,
                                                dtl.sku_exp_date
                                            FROM penerimaan_pembelian hdr
                                            LEFT JOIN (SELECT 
                                                ppd.penerimaan_pembelian_detail_id,
                                                ppd.penerimaan_pembelian_id,
                                                ppd.sku_id,
                                                ppd.penerimaan_tipe_id,
                                                ppd.sku_jumlah_barang,
                                                ppd.sku_jumlah_terima,
                                                ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                                                ppd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                                                ppd.sku_jumlah_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_terima_comp,
                                                ppd.sku_exp_date,
                                                ppd.batch_no,
                                                ppd.karyawan_id,
                                                ppd.is_tambah_manual
                                            FROM penerimaan_pembelian_detail ppd
                                            LEFT JOIN sku
                                            ON sku.sku_id = ppd.sku_id) dtl
                                            ON dtl.penerimaan_pembelian_id = hdr.penerimaan_pembelian_id
                                            LEFT JOIN penerimaan_pembelian_detail4 dtl4
                                            ON dtl4.penerimaan_pembelian_id = hdr.penerimaan_pembelian_id
                                            LEFT JOIN sku
                                            ON sku.sku_id = dtl.sku_id
                                            LEFT JOIN karyawan k
                                            ON k.karyawan_id = dtl4.karyawan_id
                                            LEFT JOIN principle p
                                            ON p.principle_id = hdr.principle_id
                                            LEFT JOIN client_wms cw
                                            ON cw.client_wms_id = hdr.client_wms_id
                                            WHERE hdr.penerimaan_pembelian_id = '$penerimaan_pembelian_id'
                                            GROUP BY dtl4.karyawan_id,
                                                k.karyawan_nama,
                                                hdr.penerimaan_pembelian_id,
                                                hdr.depo_id,
                                                hdr.depo_detail_id,
                                                hdr.client_wms_id,
                                                cw.client_wms_nama,
                                                hdr.penerimaan_surat_jalan_id,
                                                hdr.penerimaan_tipe_id,
                                                hdr.penerimaan_pembelian_kode,
                                                FORMAT(hdr.penerimaan_pembelian_tgl, 'yyyy-MM-dd'),
                                                hdr.penerimaan_pembelian_keterangan,
                                                hdr.penerimaan_pembelian_who_create,
                                                hdr.penerimaan_pembelian_tgl_create,
                                                hdr.penerimaan_pembelian_status,
                                                hdr.principle_id,
                                                p.principle_kode,
                                                dtl.sku_id,
                                                sku.sku_konversi_group,
                                                sku.sku_nama_produk,
                                                dtl.sku_exp_date
                                            ORDER BY sku.sku_konversi_group ASC");

        if ($query_laporan->num_rows() == 0) {
            $query_laporan = array();
        } else {
            $data = $query_laporan->result();
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
