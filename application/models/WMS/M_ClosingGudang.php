<?php

class M_ClosingGudang extends CI_Model
{
    public function getDepoPrefix($depo_id)
    {
        $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
        return $listDoBatch->row();
    }

    public function GetStatusClosingGudang($tgl)
    {
        $this->db->select("*, format(tr_tutup_gudang_tgl_create, 'yyyy-MM-dd HH:mm:ss') as tgl_create")
            ->from("tr_tutup_gudang")
            ->where("tr_tutup_gudang_tgl_tbg", $tgl)
            ->where("depo_id", $this->session->userdata('depo_id'))
            ->where("tr_tutup_gudang_status", "Closed");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
    }

    public function GetClosingGudangDokumen($tgl)
    {
        $query = $this->db->query("exec proses_check_tutup_gudang '" . $this->session->userdata('depo_id') . "', '$tgl'");

        // $query = $this->db->query("SELECT
        //                             kode,
        //                             draft,
        //                             completed,
        //                             canceled,
        //                             total,
        //                             CASE
        //                                 WHEN completed + canceled = total AND total > 0 THEN '1'
        //                                 ELSE '0'
        //                             END status_closing
        //                             FROM (SELECT
        //                             'CAPTION-135003000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_batch_status NOT IN ('completed', 'canceled') THEN delivery_order_batch_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_batch_status IN ('completed') THEN delivery_order_batch_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_batch_status IN ('canceled') THEN delivery_order_batch_id
        //                                 ELSE NULL
        //                             END) canceled,
        //                             COUNT(DISTINCT delivery_order_batch_id) total
        //                             FROM delivery_order_batch
        //                             WHERE FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-135002000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_status NOT IN ('completed', 'canceled') THEN delivery_order_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_status IN ('completed') THEN delivery_order_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_status IN ('canceled') THEN delivery_order_id
        //                                 ELSE NULL
        //                             END) canceled,
        //                             COUNT(DISTINCT delivery_order_id) total
        //                             FROM delivery_order
        //                             WHERE FORMAT(delivery_order_tgl_surat_jalan, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-135006000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN picking_list_status NOT IN ('Close') THEN picking_list_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN picking_list_status IN ('Close') THEN picking_list_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             '0' canceled,
        //                             COUNT(DISTINCT picking_list_id) total
        //                             FROM picking_list
        //                             WHERE FORMAT(picking_list_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-135007000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN picking_order_status NOT IN ('Completed', 'Cancel') THEN picking_order_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN picking_order_status IN ('Completed') THEN picking_order_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN picking_order_status IN ('Cancel') THEN picking_order_id
        //                                 ELSE NULL
        //                             END) canceled,
        //                             COUNT(DISTINCT picking_order_id) total
        //                             FROM picking_order
        //                             WHERE FORMAT(picking_order_tanggal, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-135014000' kode,
        //                             0 draft,
        //                             COUNT(DISTINCT penerimaan_penjualan_id) completed,
        //                             0 canceled,
        //                             COUNT(DISTINCT penerimaan_penjualan_id) total
        //                             FROM penerimaan_penjualan
        //                             WHERE FORMAT(penerimaan_penjualan_tgl, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-126000000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN penerimaan_pembelian_status NOT IN ('Close') THEN penerimaan_pembelian_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN penerimaan_pembelian_status IN ('Close') THEN penerimaan_pembelian_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             '0' canceled,
        //                             COUNT(DISTINCT penerimaan_pembelian_id) total
        //                             FROM penerimaan_pembelian
        //                             WHERE FORMAT(penerimaan_pembelian_tgl, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-126002000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN penerimaan_surat_jalan_status NOT IN ('Close', 'Force Close','Partially Received') THEN penerimaan_surat_jalan_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN penerimaan_surat_jalan_status IN ('Close', 'Force Close','Partially Received') THEN penerimaan_surat_jalan_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             '0' canceled,
        //                             COUNT(DISTINCT penerimaan_surat_jalan_id) total
        //                             FROM penerimaan_surat_jalan
        //                             WHERE FORMAT(penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-120009000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN tr_koreksi_stok_status NOT IN ('Completed','Canceled') THEN tr_koreksi_stok_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN tr_koreksi_stok_status IN ('Completed') THEN tr_koreksi_stok_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN tr_koreksi_stok_status IN ('Canceled') THEN tr_koreksi_stok_id
        //                                 ELSE NULL
        //                             END) canceled,
        //                             COUNT(DISTINCT tr_koreksi_stok_id) total
        //                             FROM tr_koreksi_stok
        //                             WHERE FORMAT(tr_koreksi_stok_tanggal, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id_asal = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-120003000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN tr_mutasi_pallet_status NOT IN ('Completed', 'Canceled') THEN tr_mutasi_pallet_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN tr_mutasi_pallet_status IN ('Completed') THEN tr_mutasi_pallet_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN tr_mutasi_pallet_status IN ('Canceled') THEN tr_mutasi_pallet_id
        //                                 ELSE NULL
        //                             END) canceled,
        //                             COUNT(DISTINCT tr_mutasi_pallet_id) total
        //                             FROM tr_mutasi_pallet
        //                             WHERE FORMAT(tr_mutasi_pallet_tanggal, 'yyyy-MM-dd') = '$tgl'
        //                             AND depo_id_asal = '" . $this->session->userdata('depo_id') . "'
        //                             UNION ALL
        //                             SELECT
        //                             'CAPTION-109009000' kode,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_settlement_status NOT IN ('completed', 'canceled') THEN delivery_order_settlement_id
        //                                 ELSE NULL
        //                             END) draft,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_settlement_status IN ('completed') THEN delivery_order_settlement_id
        //                                 ELSE NULL
        //                             END) completed,
        //                             COUNT(DISTINCT CASE
        //                                 WHEN delivery_order_settlement_status IN ('canceled') THEN delivery_order_settlement_id
        //                                 ELSE NULL
        //                             END) canceled,
        //                             COUNT(DISTINCT delivery_order_settlement_id) total
        //                             FROM delivery_order_settlement a
        //                             INNER JOIN delivery_order b
        //                             ON b.delivery_order_id = a.delivery_order_id
        //                             WHERE FORMAT(delivery_order_settlement_tgl, 'yyyy-MM-dd') = '$tgl'
        //                             AND b.depo_id = '" . $this->session->userdata('depo_id') . "') dokumen");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
    }

    public function GetDetailClosingGudangDokumen($tgl, $kode, $status_closing)
    {
        $query = $this->db->query("SELECT
                                    kode,
                                    dokumen_id,
                                    dokumen_kode,
                                    dokumen_status,
                                    status_closing
                                    FROM (SELECT
                                    'CAPTION-135003000' kode,
                                    delivery_order_batch_id AS dokumen_id,
                                    delivery_order_batch_kode AS dokumen_kode,
                                    delivery_order_batch_status AS dokumen_status,
                                    CASE WHEN delivery_order_batch_status IN ('completed', 'canceled') THEN '1' ELSE '0' END status_closing
                                    FROM delivery_order_batch
                                    WHERE FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-135002000' kode,
                                    delivery_order_id AS dokumen_id,
                                    delivery_order_kode AS dokumen_kode,
                                    delivery_order_status AS dokumen_status,
                                    CASE WHEN delivery_order_status IN ('completed', 'canceled') THEN '1' ELSE '0' END status_closing
                                    FROM delivery_order
                                    WHERE FORMAT(delivery_order_tgl_surat_jalan, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-135006000' kode,
                                        picking_list_id AS dokumen_id,
                                    picking_list_kode AS dokumen_kode,
                                    picking_list_status AS dokumen_status,
                                    CASE WHEN picking_list_status IN ('Close') THEN '1' ELSE '0' END status_closing
                                    FROM picking_list
                                    WHERE FORMAT(picking_list_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-135007000' kode,
                                    picking_order_id AS dokumen_id,
                                    picking_order_kode AS dokumen_kode,
                                    picking_order_status AS dokumen_status,
                                    CASE WHEN picking_order_status IN ('Close') THEN '1' ELSE '0' END status_closing
                                    FROM picking_order
                                    WHERE FORMAT(picking_order_tanggal, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-135014000' kode,
                                    penerimaan_penjualan_id AS dokumen_id,
                                    penerimaan_penjualan_kode AS dokumen_kode,
                                    'Retur' AS dokumen_status,
                                    '1' status_closing
                                    FROM penerimaan_penjualan
                                    WHERE FORMAT(penerimaan_penjualan_tgl, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-126000000' kode,
                                    penerimaan_pembelian_id AS dokumen_id,
                                    penerimaan_pembelian_kode AS dokumen_kode,
                                    penerimaan_pembelian_status AS dokumen_status,
                                    CASE WHEN penerimaan_pembelian_status IN ('Close') THEN '1' ELSE '0' END status_closing
                                    FROM penerimaan_pembelian
                                    WHERE FORMAT(penerimaan_pembelian_tgl, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-126002000' kode,
                                    penerimaan_surat_jalan_id AS dokumen_id,
                                    penerimaan_surat_jalan_kode AS dokumen_kode,
                                    penerimaan_surat_jalan_status AS dokumen_status,
                                    CASE WHEN penerimaan_surat_jalan_status IN ('Close', 'Force Close','Partially Received') THEN '1' ELSE '0' END status_closing
                                    FROM penerimaan_surat_jalan
                                    WHERE FORMAT(penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-120009000' kode,
                                    tr_koreksi_stok_id AS dokumen_id,
                                    tr_koreksi_stok_kode AS dokumen_kode,
                                    tr_koreksi_stok_status AS dokumen_status,
                                    CASE WHEN tr_koreksi_stok_status IN ('Completed', 'Canceled') THEN '1' ELSE '0' END status_closing
                                    FROM tr_koreksi_stok
                                    WHERE FORMAT(tr_koreksi_stok_tanggal, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id_asal = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-120003000' kode,
                                    tr_mutasi_pallet_id AS dokumen_id,
                                    tr_mutasi_pallet_kode AS dokumen_kode,
                                    tr_mutasi_pallet_status AS dokumen_status,
                                    CASE WHEN tr_mutasi_pallet_status IN ('Completed', 'Canceled') THEN '1' ELSE '0' END status_closing
                                    FROM tr_mutasi_pallet
                                    WHERE FORMAT(tr_mutasi_pallet_tanggal, 'yyyy-MM-dd') = '$tgl'
                                    AND depo_id_asal = '" . $this->session->userdata('depo_id') . "'
                                    UNION ALL
                                    SELECT
                                    'CAPTION-109009000' kode,
                                    delivery_order_settlement_id AS dokumen_id,
                                    delivery_order_settlement_kode AS dokumen_kode,
                                    delivery_order_settlement_status AS dokumen_status,
                                    CASE WHEN delivery_order_settlement_status IN ('Completed', 'Canceled') THEN '1' ELSE '0' END status_closing
                                    FROM delivery_order_settlement a
                                    INNER JOIN delivery_order b
                                    ON b.delivery_order_id = a.delivery_order_id
                                    WHERE FORMAT(delivery_order_settlement_tgl, 'yyyy-MM-dd') = '$tgl'
                                    AND b.depo_id = '" . $this->session->userdata('depo_id') . "') dokumen
                                    WHERE kode = '$kode'
                                    AND status_closing = '$status_closing'");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
    }

    public function check_tr_tutup_gudang($tr_tutup_gudang_tgl_tbg)
    {
        $query = $this->db->query("SELECT * FROM tr_tutup_gudang WHERE FORMAT(tr_tutup_gudang_tgl_tbg, 'yyyy-MM-dd') = '$tr_tutup_gudang_tgl_tbg' AND depo_id = '" . $this->session->userdata('depo_id') . "' ");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->num_rows();
        }
        return $query;
    }

    public function Insert_tr_tutup_gudang($tr_tutup_gudang_kode, $tr_tutup_gudang_tgl_tbg, $tr_tutup_gudang_tgl_next_tbg, $tr_tutup_gudang_status)
    {
        date_default_timezone_set('Asia/Jakarta');

        // $this->db->set("tr_tutup_gudang_id", "NEWID()", FALSE);
        // $this->db->set("depo_id", $this->session->userdata('depo_id'));
        // $this->db->set("tr_tutup_gudang_kode", $tr_tutup_gudang_kode);
        // $this->db->set("tr_tutup_gudang_periode_bulan", date('m', strtotime($tr_tutup_gudang_tgl_tbg)));
        // $this->db->set("tr_tutup_gudang_periode_tahun", date('Y', strtotime($tr_tutup_gudang_tgl_tbg)));
        // $this->db->set("tr_tutup_gudang_tgl_tbg", date('Y-m-d H:i:s', strtotime($tr_tutup_gudang_tgl_tbg . " " . date('H:i:s'))));
        // $this->db->set("tr_tutup_gudang_tgl_next_tbg", date('Y-m-d H:i:s', strtotime($tr_tutup_gudang_tgl_next_tbg . " " . date('H:i:s'))));
        // $this->db->set("tr_tutup_gudang_status", $tr_tutup_gudang_status);
        // $this->db->set("tr_tutup_gudang_tgl_create", "GETDATE()", FALSE);
        // $this->db->set("tr_tutup_gudang_who_create", $this->session->userdata('pengguna_username'));

        // $this->db->insert("tr_tutup_gudang");

        $query = $this->db->query("Exec proses_tutup_gudang '" . $this->session->userdata('depo_id') . "', '$tr_tutup_gudang_tgl_tbg', '" . $this->session->userdata('pengguna_username') . "'");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
        // return $this->db->last_query();
    }

    public function getDetailTutupGudang()
    {
        $query = $this->db->query("SELECT *, FORMAT(tr_tutup_gudang_tgl_create, 'yyyy-MM-dd') AS tr_tutup_gudang_tgl_create2 FROM tr_tutup_gudang WHERE depo_id = '" . $this->session->userdata('depo_id') . "'");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }
}
