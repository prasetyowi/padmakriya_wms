<?php

class M_StatusDO extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getSalesByDate($tgl, $tgl2)
    {
        $query = $this->db->query("SELECT DISTINCT
                                        a.karyawan_nama, b.sales_id
                                    FROM karyawan a
                                    INNER JOIN sales_order b
                                        ON a.karyawan_id = b.sales_id
                                    WHERE b.sales_order_id IN (SELECT
                                        do.sales_order_id
                                    FROM delivery_order do
                                    INNER JOIN sales_order so ON do.sales_order_id = so.sales_order_id
                                    WHERE dbo.floordate(so.sales_order_tgl) >= '$tgl'
                                    AND dbo.floordate(so.sales_order_tgl) <= '$tgl2')");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getDriverByDate($tgl, $tgl2)
    {
        $query = $this->db->query("SELECT DISTINCT k.karyawan_nama, dob.karyawan_id
                                    FROM delivery_order_batch dob
                                    LEFT JOIN karyawan k ON dob.karyawan_id = k.karyawan_id
                                    WHERE dbo.floordate(dob.delivery_order_batch_tanggal_kirim) >= '$tgl'
                                    AND dbo.floordate(dob.delivery_order_batch_tanggal_kirim) <= '$tgl2'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getNoSOBySales($sales, $tgl, $tgl2)
    {
        $query = $this->db->query("SELECT sales_order_id,
                                        sales_order_no_po
                                    FROM sales_order
                                    WHERE sales_id = '$sales'
                                    AND FORMAT(sales_order_tgl, 'yyyy-MM-dd') >= '$tgl'
			                        AND FORMAT(sales_order_tgl, 'yyyy-MM-dd') <= '$tgl2'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getNoFDJRByDriver($driver, $tgl, $tgl2)
    {
        $query = $this->db->query("SELECT delivery_order_batch_id,
                                        delivery_order_batch_kode
                                    FROM delivery_order_batch
                                    WHERE karyawan_id = '$driver'
                                    AND FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') >= '$tgl'
			                        AND FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') <= '$tgl2'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function count_all_data($noso, $nofdjr, $mode, $sales, $driver, $tgl1, $tgl2, $tgl3, $tgl4)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('delivery_order do');
        $this->db->join('delivery_order_batch dob', 'do.delivery_order_batch_id = dob.delivery_order_batch_id', 'left');
        $this->db->join('sales_order so', 'do.sales_order_id = so.sales_order_id', 'left');
        if ($mode == 0) {
            $this->db->where("FORMAT(so.sales_order_tgl, 'yyyy-MM-dd') >=", $tgl1);
            $this->db->where("FORMAT(so.sales_order_tgl, 'yyyy-MM-dd') <=", $tgl2);
            if ($noso != "") {
                $this->db->where('do.sales_order_id', $noso);
            }
            if ($sales != "") {
                $this->db->where('so.sales_id', $sales);
            }
        } else if ($mode == 1) {
            $this->db->where("FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') >=", $tgl3);
            $this->db->where("FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') <=", $tgl4);
            if ($nofdjr != "") {
                $this->db->where('do.delivery_order_batch_id', $nofdjr);
            }
            if ($driver != "") {
                $this->db->where('dob.karyawan_id', $driver);
            }
        }
        $query = $this->db->get()->row()->total;

        return intval($query);
    }

    public function count_filtered_data($search_value, $noso, $nofdjr, $mode, $sales, $driver, $tgl1, $tgl2, $tgl3, $tgl4)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('delivery_order do');
        $this->db->join('delivery_order_batch dob', 'do.delivery_order_batch_id = dob.delivery_order_batch_id', 'left');
        $this->db->join('sales_order so', 'do.sales_order_id = so.sales_order_id', 'left');
        if ($mode == 0) {
            $this->db->where("FORMAT(so.sales_order_tgl, 'yyyy-MM-dd') >=", $tgl1);
            $this->db->where("FORMAT(so.sales_order_tgl, 'yyyy-MM-dd') <=", $tgl2);
            if ($noso != "") {
                $this->db->where('do.sales_order_id', $noso);
            }
            if ($sales != "") {
                $this->db->where('so.sales_id', $sales);
            }
        } else if ($mode == 1) {
            $this->db->where("FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') >=", $tgl3);
            $this->db->where("FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') <=", $tgl4);
            if ($nofdjr != "") {
                $this->db->where('do.delivery_order_batch_id', $nofdjr);
            }
            if ($driver != "") {
                $this->db->where('dob.karyawan_id', $driver);
            }
        }

        // Tambahkan kondisi pencarian jika ada
        if (!empty($search_value)) {
            $this->db->where("(FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') LIKE '%" . $search_value . "%' ESCAPE '!' 
                            OR do.delivery_order_kode LIKE '%" . $search_value . "%' ESCAPE '!'
                            OR so.sales_order_kode LIKE '%" . $search_value . "%' ESCAPE '!'
                            OR so.sales_order_no_po LIKE '%" . $search_value . "%' ESCAPE '!'
                            OR do.delivery_order_kirim_nama LIKE '%" . $search_value . "%' ESCAPE '!'
                            OR do.delivery_order_kirim_alamat LIKE '%" . $search_value . "%' ESCAPE '!'
                            OR do.delivery_order_status LIKE '%" . $search_value . "%' ESCAPE '!')");
        }

        $query = $this->db->get();
        if ($query === FALSE) {
            // Handle error
            $error_message = $this->db->error();
            if (is_array($error_message) && isset($error_message['message'])) {
                echo $error_message['message'];
            } else {
                echo 'Database error occurred.';
            }
        } else {
            $row = $query->row();
            $total = isset($row->total) ? intval($row->total) : 0;
            return $total;
        }
    }

    public function get_filtered_data($start, $length, $search_value, $order_column, $order_dir, $noso, $nofdjr, $mode, $sales, $driver, $tgl1, $tgl2, $tgl3, $tgl4)
    {
        $search = '';
        if ($search_value != "") {
            $search .= "WHERE CONVERT(NVARCHAR, tempTable.delivery_order_tgl_rencana_kirim, 103) LIKE '%" . $search_value . "%' ESCAPE '!' 
                        OR tempTable.delivery_order_kode LIKE '%" . $search_value . "%' ESCAPE '!'
                        OR tempTable.sales_order_kode LIKE '%" . $search_value . "%' ESCAPE '!'
                        OR tempTable.sales_order_no_po LIKE '%" . $search_value . "%' ESCAPE '!'
                        OR tempTable.delivery_order_kirim_nama LIKE '%" . $search_value . "%' ESCAPE '!'
                        OR tempTable.delivery_order_kirim_alamat LIKE '%" . $search_value . "%' ESCAPE '!'
                        OR tempTable.delivery_order_status LIKE '%" . $search_value . "%' ESCAPE '!'";
        }

        $order_by = '';
        if ($order_column == 0) {
            $order_by = "ORDER BY tempTable.delivery_order_id ASC";
        } else if ($order_column == 1) {
            $order_by = "ORDER BY tempTable.delivery_order_tgl_rencana_kirim " . $order_dir . "";
        } else if ($order_column == 2) {
            $order_by = "ORDER BY tempTable.delivery_order_kode " . $order_dir . "";
        } else if ($order_column == 3) {
            $order_by = "ORDER BY tempTable.sales_order_kode " . $order_dir . "";
        } else if ($order_column == 4) {
            $order_by = "ORDER BY tempTable.sales_order_no_po " . $order_dir . "";
        } else if ($order_column == 5) {
            $order_by = "ORDER BY tempTable.delivery_order_kirim_nama " . $order_dir . "";
        } else if ($order_column == 6) {
            $order_by = "ORDER BY tempTable.delivery_order_kirim_alamat " . $order_dir . "";
        } else if ($order_column == 7) {
            $order_by = "ORDER BY tempTable.delivery_order_status " . $order_dir . "";
        }

        $where = "";
        if ($mode == 0) {
            $where .= "WHERE FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1'";
            $where .= "AND FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'";
            $where .= ($noso != "") ? "AND do.sales_order_id = '$noso'" : "";
            $where .= ($sales != "") ? "AND so.sales_id = '$sales'" : "";
        } elseif ($mode == 1) {
            $where .= "WHERE FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') >= '$tgl3'";
            $where .= "AND FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') <= '$tgl4'";
            $where .= ($nofdjr != "") ? "AND do.delivery_order_batch_id = '$nofdjr'" : "";
            $where .= ($driver != "") ? "AND dob.karyawan_id = '$driver'" : "";
        }

        $query = $this->db->query("SELECT * FROM (SELECT
                                                    do.delivery_order_id, 
                                                    do.delivery_order_kode, 
                                                    do.delivery_order_kirim_nama, 
                                                    do.delivery_order_kirim_alamat, 
                                                    FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') as delivery_order_tgl_rencana_kirim, 
                                                    FORMAT(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') as delivery_order_tgl_buat_do, 
                                                    FORMAT(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') as delivery_order_tgl_aktual_kirim, 
                                                    do.delivery_order_status,
                                                    so.sales_order_kode,
                                                    so.sales_order_no_po,
                                                    'DO' as flag
                                                FROM delivery_order do
                                                LEFT JOIN delivery_order_batch dob ON do.delivery_order_batch_id = dob.delivery_order_batch_id
                                                LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
                                                $where
                                                UNION
                                                SELECT
                                                    dod.delivery_order_draft_id, 
                                                    dod.delivery_order_draft_kode, 
                                                    dod.delivery_order_draft_kirim_nama, 
                                                    dod.delivery_order_draft_kirim_alamat, 
                                                    FORMAT(dod.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') as delivery_order_tgl_rencana_kirim, 
                                                    FORMAT(dod.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') as delivery_order_tgl_buat_do, 
                                                    FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'dd-MM-yyyy') as delivery_order_tgl_aktual_kirim, 
                                                    dod.delivery_order_draft_status,
                                                    so.sales_order_kode,
                                                    so.sales_order_no_po,
                                                    'DO Draft' as flag
                                                FROM delivery_order_draft dod
												LEFT JOIN delivery_order do ON dod.sales_order_id = do.sales_order_id
                                                LEFT JOIN sales_order so ON dod.sales_order_id = so.sales_order_id
												LEFT JOIN delivery_order_batch dob ON do.delivery_order_batch_id = dob.delivery_order_batch_id
                                                $where
                                                AND do.delivery_order_draft_id NOT IN (SELECT delivery_order_draft_id FROM delivery_order)
                                                ) AS tempTable             
                                    $search
                                    $order_by
                                    OFFSET " . intval($start) . " ROWS FETCH NEXT " . intval($length) . " ROWS ONLY");

        return $query->result_array();
    }

    public function GetDODetail($do_id)
    {
        return $this->db->distinct()
            ->select("status_progress_nama as status, delivery_order_progress_create_tgl as tgl")
            ->from("delivery_order_progress")
            ->where("delivery_order_id", "$do_id")
            ->order_by("tgl", 'ASC')
            ->get()
            ->result_array();
    }

    public function getDetailDO($noso, $nofdjr, $mode, $sales, $driver, $tgl, $tgl2)
    {
        $where = "";
        if ($mode == 0) {
            $where .= "WHERE FORMAT(so.sales_order_tgl, 'yyyy-MM-dd') >= '$tgl'";
            $where .= "AND FORMAT(so.sales_order_tgl, 'yyyy-MM-dd') <= '$tgl2'";
            $where .= ($noso != "") ? "AND do.sales_order_id = '$noso'" : "";
            $where .= ($sales != "") ? "AND so.sales_id = '$sales'" : "";
        } elseif ($mode == 1) {
            $where .= "WHERE FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') >= '$tgl'";
            $where .= "AND FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') <= '$tgl2'";
            $where .= ($nofdjr != "") ? "AND do.delivery_order_batch_id = '$nofdjr'" : "";
            $where .= ($driver != "") ? "AND dob.karyawan_id = '$driver'" : "";
        }

        $query = $this->db->query("SELECT
                                        do.delivery_order_id, 
                                        do.delivery_order_kode, 
                                        do.delivery_order_kirim_nama, 
                                        do.delivery_order_kirim_alamat, 
                                        FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') as delivery_order_tgl_rencana_kirim, 
                                        do.delivery_order_status,
                                        so.sales_order_kode,
                                        so.sales_order_no_po,
                                        'DO' as flag
                                    FROM delivery_order do
                                    LEFT JOIN delivery_order_batch dob ON do.delivery_order_batch_id = dob.delivery_order_batch_id
                                    LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
                                    $where
                                    UNION
                                    SELECT
                                        dod.delivery_order_draft_id, 
                                        dod.delivery_order_draft_kode, 
                                        dod.delivery_order_draft_kirim_nama, 
                                        dod.delivery_order_draft_kirim_alamat, 
                                        FORMAT(dod.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') as delivery_order_tgl_rencana_kirim, 
                                        dod.delivery_order_draft_status,
                                        so.sales_order_kode,
                                        so.sales_order_no_po,
                                        'DO Draft' as flag
                                    FROM delivery_order_draft dod
                                    LEFT JOIN delivery_order do ON dod.sales_order_id = do.sales_order_id
                                    LEFT JOIN sales_order so ON dod.sales_order_id = so.sales_order_id
                                    LEFT JOIN delivery_order_batch dob ON do.delivery_order_batch_id = dob.delivery_order_batch_id
                                    $where
                                    AND do.delivery_order_draft_id NOT IN (SELECT delivery_order_draft_id FROM delivery_order)");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getNoSO($noso)
    {
        $query = $this->db->query("SELECT sales_order_no_po FROM sales_order WHERE sales_order_id = '$noso'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function getNoFDJR($nofdjr)
    {
        $query = $this->db->query("SELECT delivery_order_batch_kode FROM delivery_order_batch WHERE delivery_order_batch_id = '$nofdjr'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function getSales($sales)
    {
        $query = $this->db->query("SELECT k.karyawan_nama
                                    FROM karyawan k
                                    LEFT JOIN sales_order so ON k.karyawan_id = so.sales_id
                                    WHERE so.sales_id = '$sales'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function getDriver($driver)
    {
        $query = $this->db->query("SELECT k.karyawan_nama
                                    FROM karyawan k
                                    LEFT JOIN delivery_order_batch dob ON k.karyawan_id = dob.karyawan_id
                                    WHERE dob.karyawan_id = '$driver'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function GetDeliveryOrderHeaderById($id)
    {
        $query = $this->db->query("SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									do.sales_order_id,
									FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(do.delivery_order_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_tgl_rencana_kirim,
									do.delivery_order_update_tgl,
									do.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									do.client_pt_id,
									ISNULL(do.delivery_order_kirim_nama,'') AS delivery_order_kirim_nama,
									ISNULL(do.delivery_order_kirim_alamat,'') AS delivery_order_kirim_alamat,
									ISNULL(do.delivery_order_kirim_telp,'') AS delivery_order_kirim_telp,
									ISNULL(do.delivery_order_kirim_provinsi,'') AS delivery_order_kirim_provinsi,
									ISNULL(do.delivery_order_kirim_kota,'') AS delivery_order_kirim_kota,
									ISNULL(do.delivery_order_kirim_kecamatan,'') AS delivery_order_kirim_kecamatan,
									ISNULL(do.delivery_order_kirim_kelurahan,'') AS delivery_order_kirim_kelurahan,
									ISNULL(do.delivery_order_kirim_kodepos,'') AS delivery_order_kirim_kodepos,
									ISNULL(do.delivery_order_kirim_area,'') AS delivery_order_kirim_area,
                                    '' as pabrik_id,
									ISNULL(do.delivery_order_ambil_nama,'') AS delivery_order_ambil_nama,
									ISNULL(do.delivery_order_ambil_alamat,'') AS delivery_order_ambil_alamat,
									ISNULL(do.delivery_order_ambil_telp,'') AS delivery_order_ambil_telp,
									ISNULL(do.delivery_order_ambil_provinsi,'') AS delivery_order_ambil_provinsi,
									ISNULL(do.delivery_order_ambil_kota,'') AS delivery_order_ambil_kota,
									ISNULL(do.delivery_order_ambil_kecamatan,'') AS delivery_order_ambil_kecamatan,
									ISNULL(do.delivery_order_ambil_kelurahan,'') AS delivery_order_ambil_kelurahan,
									ISNULL(do.delivery_order_ambil_kodepos,'') AS delivery_order_ambil_kodepos,
									ISNULL(do.delivery_order_ambil_area,'') AS delivery_order_ambil_area,
									do.delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.delivery_order_reff_id,
									do.delivery_order_reff_no,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_nama,
									ISNULL(do.delivery_order_keterangan,'') AS delivery_order_keterangan,
									do.delivery_order_status,
									delivery_order_nominal_tunai,
									ISNULL(do.delivery_order_attachment, '') AS delivery_order_attachment
									FROM delivery_order do
									LEFT JOIN client_wms ON do.client_wms_id = client_wms.client_wms_id
									LEFT JOIN tipe_delivery_order tipe ON tipe.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE do.delivery_order_id = '$id' ");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
        // return $this->db->last_query();
    }

    public function GetDeliveryOrderDetailById($id)
    {
        $query = $this->db->query("SELECT
									do.delivery_order_detail_id,
									do.delivery_order_id,
									do.sku_id,
									'' as gudang_id,
									'' as gudang_detail_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.sku_harga_satuan,
									sku.sku_satuan,
									sku.sku_kemasan,
									sku.sku_konversi_faktor,
									ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
									ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
									ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
									ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
									ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
									ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
									ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
									ISNULL(do.sku_weight, '0') AS sku_weight,
									ISNULL(do.sku_weight_unit, '') AS sku_weight_unit,
									ISNULL(do.sku_length, '0') AS sku_length,
									ISNULL(do.sku_length_unit, '') AS sku_length_unit,
									ISNULL(do.sku_width, '0') AS sku_width,
									ISNULL(do.sku_width_unit, '') AS sku_width_unit,
									ISNULL(do.sku_height, '0') AS sku_height,
									ISNULL(do.sku_height_unit, '') AS sku_height_unit,
									ISNULL(do.sku_volume, '0') AS sku_volume,
									ISNULL(do.sku_volume_unit, '') AS sku_volume_unit,
									ISNULL(do.sku_qty, '0') AS sku_qty,
									ISNULL(do.sku_keterangan, '') AS sku_keterangan,
									ISNULL(do.tipe_stock_nama, '') AS tipe_stock
									FROM delivery_order_detail do
									LEFT JOIN sku
									  ON sku.sku_id = do.sku_id
									WHERE delivery_order_id = '$id'
									ORDER BY sku_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
        // return $this->db->last_query();
    }
}
