<?php

class M_AnalisisJumlahPenerimaan extends CI_Model
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

    public function Exec_report_analisis_jumlah_penerimaan($tglawal, $tglakhir, $client_wms_id, $principle_id, $tipe)
    {
        set_time_limit(0);

        $data = array();
        $list_kolom = array();

        $query_laporan =  $this->db->query("exec report_analisis_jumlah_penerimaan '$tglawal','$tglakhir','$client_wms_id','" . $this->session->userdata('depo_id') . "','$principle_id','$tipe'");

        if ($query_laporan->num_rows() == 0) {
            $query_laporan = array();
        } else {
            $data = $query_laporan->result_array();
        }

        return $data;
    }

    public function Generate_kolom_report_analisis_jumlah_penerimaan($tglawal, $tglakhir, $tipe)
    {

        $query_kolom =  $this->db->query("exec generate_kolom_report_analisis_jumlah_penerimaan '$tglawal','$tglakhir','$tipe'");

        if ($query_kolom->num_rows() == 0) {
            $query_kolom = array();
        } else {
            $query_kolom = $query_kolom->result_array();
        }

        return $query_kolom;
    }

    public function Get_detail_analisis_jumlah_penerimaan($tgl, $bulan, $tahun, $client_wms_id, $principle_id, $nopol, $tipe)
    {
        $sql = "";

        if ($tipe == "tahun") {
            $sql = "SELECT YEAR(hdr.penerimaan_surat_jalan_tgl) AS tahun,
                    ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') AS penerimaan_surat_jalan_nopol,
                    hdr.client_wms_id,
                    cw.client_wms_nama,
                    hdr.principle_id,
                    p.principle_kode,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn
                FROM penerimaan_surat_jalan hdr
                LEFT JOIN (SELECT
                    psjd.penerimaan_surat_jalan_detail_id,
                    psjd.penerimaan_surat_jalan_id,
                    psjd.sku_id,
                    sku.sku_konversi_group,
                    sku.sku_konversi_level,
                    sku.sku_konversi_faktor,
                    ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                    psjd.penerimaan_tipe_id,
                    psjd.sku_jumlah_barang,
                    psjd.sku_harga,
                    psjd.sku_diskon_percent,
                    psjd.sku_diskon_rp,
                    psjd.sku_harga_total,
                    psjd.sku_exp_date,
                    psjd.sku_jumlah_barang_terima,
                    psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                    psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                    psjd.reason_surat_jalan_detail_sku,
                    psjd.batch_no,
                    psjd.client_pt_segmen_id
                FROM penerimaan_surat_jalan_detail psjd
                LEFT JOIN sku
                ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                LEFT JOIN principle p
                ON p.principle_id = hdr.principle_id
                LEFT JOIN client_wms cw
                ON cw.client_wms_id = hdr.client_wms_id
                LEFT JOIN reason_surat_jalan r
                ON r.reason_surat_jalan_id = hdr.reason_surat_jalan_id
                WHERE YEAR(hdr.penerimaan_surat_jalan_tgl) = '$tahun'
                AND ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') = '$nopol'
                AND hdr.principle_id = '$principle_id'
                AND hdr.client_wms_id = '$client_wms_id'
                GROUP BY YEAR(hdr.penerimaan_surat_jalan_tgl),
                        ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN'),
                        hdr.client_wms_id,
                        cw.client_wms_nama,
                        hdr.principle_id,
                        p.principle_kode
                ORDER BY YEAR(hdr.penerimaan_surat_jalan_tgl), cw.client_wms_nama, p.principle_kode ASC";
        } else if ($tipe == "bulan") {
            $sql = "SELECT YEAR(hdr.penerimaan_surat_jalan_tgl) AS tahun,
                    FORMAT(hdr.penerimaan_surat_jalan_tgl, 'MM') AS bulan,
                    ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') AS penerimaan_surat_jalan_nopol,
                    hdr.client_wms_id,
                    cw.client_wms_nama,
                    hdr.principle_id,
                    p.principle_kode,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn
                FROM penerimaan_surat_jalan hdr
                LEFT JOIN (SELECT
                    psjd.penerimaan_surat_jalan_detail_id,
                    psjd.penerimaan_surat_jalan_id,
                    psjd.sku_id,
                    sku.sku_konversi_group,
                    sku.sku_konversi_level,
                    sku.sku_konversi_faktor,
                    ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                    psjd.penerimaan_tipe_id,
                    psjd.sku_jumlah_barang,
                    psjd.sku_harga,
                    psjd.sku_diskon_percent,
                    psjd.sku_diskon_rp,
                    psjd.sku_harga_total,
                    psjd.sku_exp_date,
                    psjd.sku_jumlah_barang_terima,
                    psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                    psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                    psjd.reason_surat_jalan_detail_sku,
                    psjd.batch_no,
                    psjd.client_pt_segmen_id
                FROM penerimaan_surat_jalan_detail psjd
                LEFT JOIN sku
                ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                LEFT JOIN principle p
                ON p.principle_id = hdr.principle_id
                LEFT JOIN client_wms cw
                ON cw.client_wms_id = hdr.client_wms_id
                LEFT JOIN reason_surat_jalan r
                ON r.reason_surat_jalan_id = hdr.reason_surat_jalan_id
                WHERE YEAR(hdr.penerimaan_surat_jalan_tgl) = '$tahun'
                AND MONTH(hdr.penerimaan_surat_jalan_tgl) = '$bulan'
                AND ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') = '$nopol'
                AND hdr.principle_id = '$principle_id'
                AND hdr.client_wms_id = '$client_wms_id'
                GROUP BY YEAR(hdr.penerimaan_surat_jalan_tgl),
                        FORMAT(hdr.penerimaan_surat_jalan_tgl, 'MM'),
                        ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN'),
                        hdr.client_wms_id,
                        cw.client_wms_nama,
                        hdr.principle_id,
                        p.principle_kode
                ORDER BY YEAR(hdr.penerimaan_surat_jalan_tgl), FORMAT(hdr.penerimaan_surat_jalan_tgl, 'MM'), cw.client_wms_nama, p.principle_kode ASC";
        } else if ($tipe == "tanggal") {
            $sql = "SELECT FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') as tanggal,
                    ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') AS penerimaan_surat_jalan_nopol,
                    hdr.client_wms_id,
                    cw.client_wms_nama,
                    hdr.principle_id,
                    p.principle_kode,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Ditolak') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_ditolak,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Pecah') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_pecah,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Klaim') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_klaim,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') IN ('Retur') THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn_retur,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur') THEN ISNULL(dtl.sku_jumlah_barang_comp, 0) ELSE 0 END) AS sku_jumlah_barang_comp,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) ELSE 0 END) AS sku_jumlah_barang_terima_comp,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_comp_ctn,
                    SUM(CASE WHEN ISNULL(r.keterangan, '') NOT IN ('Ditolak','Klaim','Pecah','Retur')  THEN CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END ELSE 0 END) AS sku_jumlah_barang_terima_comp_ctn
                FROM penerimaan_surat_jalan hdr
                LEFT JOIN (SELECT
                    psjd.penerimaan_surat_jalan_detail_id,
                    psjd.penerimaan_surat_jalan_id,
                    psjd.sku_id,
                    sku.sku_konversi_group,
                    sku.sku_konversi_level,
                    sku.sku_konversi_faktor,
                    ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                    psjd.penerimaan_tipe_id,
                    psjd.sku_jumlah_barang,
                    psjd.sku_harga,
                    psjd.sku_diskon_percent,
                    psjd.sku_diskon_rp,
                    psjd.sku_harga_total,
                    psjd.sku_exp_date,
                    psjd.sku_jumlah_barang_terima,
                    psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                    psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                    psjd.reason_surat_jalan_detail_sku,
                    psjd.batch_no,
                    psjd.client_pt_segmen_id
                FROM penerimaan_surat_jalan_detail psjd
                LEFT JOIN sku
                ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                LEFT JOIN principle p
                ON p.principle_id = hdr.principle_id
                LEFT JOIN client_wms cw
                ON cw.client_wms_id = hdr.client_wms_id
                LEFT JOIN reason_surat_jalan r
                ON r.reason_surat_jalan_id = hdr.reason_surat_jalan_id
                WHERE FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') = '$tgl'
                AND ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') = '$nopol'
                AND hdr.principle_id = '$principle_id'
                AND hdr.client_wms_id = '$client_wms_id'
                GROUP BY FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd'),
                        ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN'),
                        hdr.client_wms_id,
                        cw.client_wms_nama,
                        hdr.principle_id,
                        p.principle_kode
                ORDER BY FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd'), cw.client_wms_nama, p.principle_kode ASC";
        }

        $query =  $this->db->query($sql);

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    private function _get_datatables_query($tglawal = null, $tglakhir = null, $filter_nopol = null, $filter_principle = null, $filter_perusahaan = null, $filter_reason = null)
    {
        // Query dasar dengan tambahan filter jika ada
        $sql = "SELECT hdr.penerimaan_surat_jalan_id,
                    hdr.penerimaan_surat_jalan_kode,
                    hdr.penerimaan_surat_jalan_no_sj,
                    FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') AS penerimaan_surat_jalan_tgl,
                    ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') AS penerimaan_surat_jalan_nopol,
                    hdr.client_wms_id,
                    cw.client_wms_nama,
                    hdr.principle_id,
                    p.principle_kode,
                    sku.sku_konversi_group,
                    sku.sku_nama_produk,
                    dtl.sku_exp_date,
                    SUM(ISNULL(dtl.sku_jumlah_barang_comp, 0)) AS sku_jumlah_barang_comp,
                    SUM(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0)) AS sku_jumlah_barang_terima_comp,
                    hdr.reason_surat_jalan_id,
                    ISNULL(r.keterangan, '') AS reason_surat_jalan_ket
                FROM penerimaan_surat_jalan hdr
                LEFT JOIN (SELECT
                    psjd.penerimaan_surat_jalan_detail_id,
                    psjd.penerimaan_surat_jalan_id,
                    psjd.sku_id,
                    sku.sku_konversi_group,
                    sku.sku_konversi_level,
                    sku.sku_konversi_faktor,
                    ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                    psjd.penerimaan_tipe_id,
                    psjd.sku_jumlah_barang,
                    psjd.sku_harga,
                    psjd.sku_diskon_percent,
                    psjd.sku_diskon_rp,
                    psjd.sku_harga_total,
                    psjd.sku_exp_date,
                    psjd.sku_jumlah_barang_terima,
                    psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                    psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                    psjd.reason_surat_jalan_detail_sku,
                    psjd.batch_no,
                    psjd.client_pt_segmen_id
                FROM penerimaan_surat_jalan_detail psjd
                LEFT JOIN sku
                ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                LEFT JOIN sku
                ON sku.sku_id = dtl.sku_id
                LEFT JOIN principle p
                ON p.principle_id = sku.principle_id
                LEFT JOIN client_wms cw
                ON cw.client_wms_id = hdr.client_wms_id
                LEFT JOIN reason_surat_jalan r
                ON r.reason_surat_jalan_id = hdr.reason_surat_jalan_id
                WHERE hdr.penerimaan_surat_jalan_id IS NOT NULL";

        // Tambahkan filter dari input
        $sql .= " AND FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') BETWEEN '" . $this->db->escape_like_str($tglawal) . "' AND '" . $this->db->escape_like_str($tglakhir) . "'";

        if ($filter_nopol) {
            $sql .= " AND ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') = '" . $this->db->escape_like_str($filter_nopol) . "'";
        }

        if ($filter_principle) {
            $sql .= " AND hdr.principle_id = '" . $this->db->escape_like_str($filter_principle) . "'";
        }

        if ($filter_perusahaan) {
            $sql .= " AND hdr.client_wms_id = '" . $this->db->escape_like_str($filter_perusahaan) . "'";
        }

        // Tambahkan kondisi filter untuk kolom `keterangan`
        if ($filter_reason == "lain_lain") {
            $sql .= " AND ISNULL(r.keterangan, '') NOT IN ('Ditolak', 'Klaim', 'Pecah', 'Retur')";
        } else if ($filter_reason == "ditolak") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Ditolak')";
        } else if ($filter_reason == "klaim") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Klaim')";
        } else if ($filter_reason == "pecah") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Pecah')";
        } else if ($filter_reason == "retur") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Retur')";
        }

        if ($_POST['search']['value']) {
            $search_value = $_POST['search']['value'];
            $sql .= " AND (hdr.penerimaan_surat_jalan_kode LIKE '%" . $search_value . "%' OR hdr.penerimaan_surat_jalan_no_sj LIKE '%" . $search_value . "%' OR FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') LIKE '%" . $search_value . "%' OR sku.sku_kode LIKE '%" . $search_value . "%' OR p.principle_kode LIKE '%" . $search_value . "%')";
        }

        $sql .= "GROUP BY hdr.penerimaan_surat_jalan_id,
                hdr.penerimaan_surat_jalan_kode,
                hdr.penerimaan_surat_jalan_no_sj,
                FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd'),
                ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN'),
                hdr.client_wms_id,
                cw.client_wms_nama,
                hdr.principle_id,
                p.principle_kode,
                sku.sku_konversi_group,
                sku.sku_nama_produk,
                dtl.sku_exp_date,
                hdr.reason_surat_jalan_id,
                ISNULL(r.keterangan, '')";

        // Tambahkan urutan
        $column_order = array(
            null,
            "cw.client_wms_nama",
            "hdr.penerimaan_surat_jalan_kode",
            "hdr.penerimaan_surat_jalan_no_sj",
            "FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd')",
            "ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN')",
            "p.principle_kode",
            "sku.sku_konversi_group",
            "sku.sku_nama_produk",
            null,
            "dtl.sku_exp_date",
            "SUM(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0))",
            "ISNULL(r.keterangan, '')"
        ); // Kolom yang bisa diurutkan

        if (isset($_POST['order'])) {
            $order_column = $column_order[$_POST['order']['0']['column']];
            $order_dir = $_POST['order']['0']['dir'];
            $sql .= " ORDER BY $order_column $order_dir";
        } else {
            $sql .= " ORDER BY hdr.penerimaan_surat_jalan_kode ASC"; // Default order
        }

        return $sql;
    }

    function get_datatables($tglawal = null, $tglakhir = null, $filter_nopol = null, $filter_principle = null, $filter_perusahaan = null, $filter_reason = null)
    {
        $sql = $this->_get_datatables_query($tglawal, $tglakhir, $filter_nopol, $filter_principle, $filter_perusahaan, $filter_reason);

        if ($_POST['length'] != -1) {
            $sql .= " OFFSET " . $_POST['start'] . " ROWS FETCH NEXT " . $_POST['length'] . " ROWS ONLY";
        }

        $query = $this->db->query($sql);
        return $query->result();
    }

    function count_filtered($tglawal = null, $tglakhir = null, $filter_nopol = null, $filter_principle = null, $filter_perusahaan = null, $filter_reason = null)
    {
        $sql = $this->_get_datatables_query($tglawal, $tglakhir, $filter_nopol, $filter_principle, $filter_perusahaan, $filter_reason);
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function count_all($tglawal = null, $tglakhir = null, $filter_nopol = null, $filter_principle = null, $filter_perusahaan = null, $filter_reason = null)
    {

        $sql = "SELECT hdr.penerimaan_surat_jalan_id,
                    hdr.penerimaan_surat_jalan_kode,
                    hdr.penerimaan_surat_jalan_no_sj,
                    FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') AS penerimaan_surat_jalan_tgl,
                    ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') AS penerimaan_surat_jalan_nopol,
                    hdr.client_wms_id,
                    cw.client_wms_nama,
                    hdr.principle_id,
                    p.principle_kode,
                    sku.sku_konversi_group,
                    sku.sku_nama_produk,
                    dtl.sku_exp_date,
                    SUM(ISNULL(dtl.sku_jumlah_barang_comp, 0)) AS sku_jumlah_barang_comp,
                    SUM(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0)) AS sku_jumlah_barang_terima_comp,
                    hdr.reason_surat_jalan_id,
                    ISNULL(r.keterangan, '') AS reason_surat_jalan_ket
                FROM penerimaan_surat_jalan hdr
                LEFT JOIN (SELECT
                    psjd.penerimaan_surat_jalan_detail_id,
                    psjd.penerimaan_surat_jalan_id,
                    psjd.sku_id,
                    sku.sku_konversi_group,
                    sku.sku_konversi_level,
                    sku.sku_konversi_faktor,
                    ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                    psjd.penerimaan_tipe_id,
                    psjd.sku_jumlah_barang,
                    psjd.sku_harga,
                    psjd.sku_diskon_percent,
                    psjd.sku_diskon_rp,
                    psjd.sku_harga_total,
                    psjd.sku_exp_date,
                    psjd.sku_jumlah_barang_terima,
                    psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                    psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                    psjd.reason_surat_jalan_detail_sku,
                    psjd.batch_no,
                    psjd.client_pt_segmen_id
                FROM penerimaan_surat_jalan_detail psjd
                LEFT JOIN sku
                ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                LEFT JOIN sku
                ON sku.sku_id = dtl.sku_id
                LEFT JOIN principle p
                ON p.principle_id = sku.principle_id
                LEFT JOIN client_wms cw
                ON cw.client_wms_id = hdr.client_wms_id
                LEFT JOIN reason_surat_jalan r
                ON r.reason_surat_jalan_id = hdr.reason_surat_jalan_id
                WHERE hdr.penerimaan_surat_jalan_id IS NOT NULL";

        // Tambahkan filter dari input
        $sql .= " AND FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') BETWEEN '" . $this->db->escape_like_str($tglawal) . "' AND '" . $this->db->escape_like_str($tglakhir) . "'";

        if ($filter_nopol) {
            $sql .= " AND ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN') = '" . $this->db->escape_like_str($filter_nopol) . "'";
        }

        if ($filter_principle) {
            $sql .= " AND hdr.principle_id = '" . $this->db->escape_like_str($filter_principle) . "'";
        }

        if ($filter_perusahaan) {
            $sql .= " AND hdr.client_wms_id = '" . $this->db->escape_like_str($filter_perusahaan) . "'";
        }

        // Tambahkan kondisi filter untuk kolom `keterangan`
        if ($filter_reason == "lain_lain") {
            $sql .= " AND ISNULL(r.keterangan, '') NOT IN ('Ditolak', 'Klaim', 'Pecah', 'Retur')";
        } else if ($filter_reason == "ditolak") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Ditolak')";
        } else if ($filter_reason == "klaim") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Klaim')";
        } else if ($filter_reason == "pecah") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Pecah')";
        } else if ($filter_reason == "retur") {
            $sql .= " AND ISNULL(r.keterangan, '') IN ('Retur')";
        }

        $sql .= "GROUP BY hdr.penerimaan_surat_jalan_id,
                hdr.penerimaan_surat_jalan_kode,
                hdr.penerimaan_surat_jalan_no_sj,
                FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd'),
                ISNULL(hdr.penerimaan_surat_jalan_nopol, 'UNKNOWN'),
                hdr.client_wms_id,
                cw.client_wms_nama,
                hdr.principle_id,
                p.principle_kode,
                sku.sku_konversi_group,
                sku.sku_nama_produk,
                dtl.sku_exp_date,
                hdr.reason_surat_jalan_id,
                ISNULL(r.keterangan, '')";

        $query = $this->db->query($sql);

        return $query->num_rows();
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

    public function Get_chart_analisis_jumlah_penerimaan($tglawal, $tglakhir, $client_wms_id, $principle_id, $tipe)
    {

        if ($principle_id == "") {
            $principle_id = "";
        } else {
            $principle_id = " AND hdr.principle_id = '$principle_id'";
        }

        if ($tipe == "tahun") {
            $query =  $this->db->query("SELECT YEAR(hdr.penerimaan_surat_jalan_tgl) AS tahun,
                                            ISNULL(p.principle_kode, 'UNKNOWN') AS principle,
                                            SUM(CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END) AS jumlah_penerimaan
                                        FROM penerimaan_surat_jalan hdr
                                        LEFT JOIN (SELECT
                                            psjd.penerimaan_surat_jalan_detail_id,
                                            psjd.penerimaan_surat_jalan_id,
                                            psjd.sku_id,
                                            sku.sku_konversi_group,
                                            sku.sku_konversi_level,
                                            sku.sku_konversi_faktor,
                                            ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                                            psjd.penerimaan_tipe_id,
                                            psjd.sku_jumlah_barang,
                                            psjd.sku_harga,
                                            psjd.sku_diskon_percent,
                                            psjd.sku_diskon_rp,
                                            psjd.sku_harga_total,
                                            psjd.sku_exp_date,
                                            psjd.sku_jumlah_barang_terima,
                                            psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                                            psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                                            psjd.reason_surat_jalan_detail_sku,
                                            psjd.batch_no,
                                            psjd.client_pt_segmen_id
                                        FROM penerimaan_surat_jalan_detail psjd
                                        LEFT JOIN sku
                                        ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                                        LEFT JOIN principle p
                                        ON p.principle_id = hdr.principle_id
                                        WHERE FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') BETWEEN '$tglawal' AND '$tglakhir'
                                        AND hdr.client_wms_id = '$client_wms_id'
                                        " . $principle_id . "
                                        GROUP BY YEAR(hdr.penerimaan_surat_jalan_tgl),
                                                 ISNULL(p.principle_kode, 'UNKNOWN')
                                        ORDER BY YEAR(hdr.penerimaan_surat_jalan_tgl) ASC");
        } else if ($tipe == "bulan") {
            $query =  $this->db->query("SELECT FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM') AS bulan,
                                            ISNULL(p.principle_kode, 'UNKNOWN') AS principle,
                                            SUM(CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END) AS jumlah_penerimaan
                                        FROM penerimaan_surat_jalan hdr
                                        LEFT JOIN (SELECT
                                            psjd.penerimaan_surat_jalan_detail_id,
                                            psjd.penerimaan_surat_jalan_id,
                                            psjd.sku_id,
                                            sku.sku_konversi_group,
                                            sku.sku_konversi_level,
                                            sku.sku_konversi_faktor,
                                            ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                                            psjd.penerimaan_tipe_id,
                                            psjd.sku_jumlah_barang,
                                            psjd.sku_harga,
                                            psjd.sku_diskon_percent,
                                            psjd.sku_diskon_rp,
                                            psjd.sku_harga_total,
                                            psjd.sku_exp_date,
                                            psjd.sku_jumlah_barang_terima,
                                            psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                                            psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                                            psjd.reason_surat_jalan_detail_sku,
                                            psjd.batch_no,
                                            psjd.client_pt_segmen_id
                                        FROM penerimaan_surat_jalan_detail psjd
                                        LEFT JOIN sku
                                        ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                                        LEFT JOIN principle p
                                        ON p.principle_id = hdr.principle_id
                                        WHERE FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') BETWEEN '$tglawal' AND '$tglakhir'
                                        AND hdr.client_wms_id = '$client_wms_id'
                                        " . $principle_id . "
                                        GROUP BY FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM'),
                                                 ISNULL(p.principle_kode, 'UNKNOWN')
                                        ORDER BY FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM') ASC");
        } else if ($tipe == "tanggal") {
            $query =  $this->db->query("SELECT FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') AS tanggal,
                                            ISNULL(p.principle_kode, 'UNKNOWN') AS principle,
                                            SUM(CASE WHEN sku_konversi_faktor_max > 0 THEN CAST(ROUND(ISNULL(dtl.sku_jumlah_barang_terima_comp, 0) / sku_konversi_faktor_max, 2) AS decimal(20, 2)) ELSE 0 END) AS jumlah_penerimaan
                                        FROM penerimaan_surat_jalan hdr
                                        LEFT JOIN (SELECT
                                            psjd.penerimaan_surat_jalan_detail_id,
                                            psjd.penerimaan_surat_jalan_id,
                                            psjd.sku_id,
                                            sku.sku_konversi_group,
                                            sku.sku_konversi_level,
                                            sku.sku_konversi_faktor,
                                            ISNULL((select top 1 s.sku_konversi_faktor from sku s where s.sku_konversi_group = sku.sku_konversi_group order by s.sku_konversi_level desc), 0) sku_konversi_faktor_max,
                                            psjd.penerimaan_tipe_id,
                                            psjd.sku_jumlah_barang,
                                            psjd.sku_harga,
                                            psjd.sku_diskon_percent,
                                            psjd.sku_diskon_rp,
                                            psjd.sku_harga_total,
                                            psjd.sku_exp_date,
                                            psjd.sku_jumlah_barang_terima,
                                            psjd.sku_jumlah_barang * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_comp,
                                            psjd.sku_jumlah_barang_terima * ISNULL(sku.sku_konversi_faktor, 0) sku_jumlah_barang_terima_comp,
                                            psjd.reason_surat_jalan_detail_sku,
                                            psjd.batch_no,
                                            psjd.client_pt_segmen_id
                                        FROM penerimaan_surat_jalan_detail psjd
                                        LEFT JOIN sku
                                        ON sku.sku_id = psjd.sku_id) dtl ON dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                                        LEFT JOIN principle p
                                        ON p.principle_id = hdr.principle_id
                                        WHERE FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') BETWEEN '$tglawal' AND '$tglakhir'
                                        AND hdr.client_wms_id = '$client_wms_id'
                                        " . $principle_id . "
                                        GROUP BY FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd'),
                                                 ISNULL(p.principle_kode, 'UNKNOWN')
                                        ORDER BY FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') ASC");
        }

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }
}
