<?php

class M_LaporanInformasiPallet extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function get_pallet_header_by_kode($kode)
    {
        $query = $this->db->query("SELECT
                                    pallet.pallet_id,
                                    pallet.pallet_kode,
                                    pallet.pallet_is_lock,
                                    pallet.pallet_is_lock_reason,
                                    depo.depo_id,
                                    depo.depo_nama,
                                    depo_detail.depo_detail_id,
                                    depo_detail.depo_detail_nama,
                                    rak.rak_id,
                                    rak.rak_kode,
                                    rak_lajur.rak_lajur_id,
                                    rak_lajur.rak_lajur_nama,
                                    rak_lajur_detail.rak_lajur_detail_id,
                                    rak_lajur_detail.rak_lajur_detail_nama
                                    FROM pallet
                                    LEFT JOIN rak_lajur_detail_pallet
                                    ON rak_lajur_detail_pallet.pallet_id = pallet.pallet_id
                                    LEFT JOIN rak_lajur_detail
                                    ON rak_lajur_detail.rak_lajur_detail_id = rak_lajur_detail_pallet.rak_lajur_detail_id
                                    LEFT JOIN rak_lajur
                                    ON rak_lajur.rak_lajur_id = rak_lajur_detail.rak_lajur_id
                                    LEFT JOIN rak
                                    ON rak.rak_id = rak_lajur.rak_id
                                    LEFT JOIN depo_detail
                                    ON depo_detail.depo_detail_id = rak.depo_detail_id
                                    LEFT JOIN depo
                                    ON depo.depo_id = depo_detail.depo_id
                                    WHERE pallet.pallet_kode = '$kode'");


        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
    }

    public function get_pallet_detail_by_kode($kode)
    {
        $query = $this->db->query("SELECT
                                    pallet_detail.pallet_detail_id,
                                    pallet.pallet_id,
                                    pallet.pallet_is_lock,
                                    pallet.pallet_is_lock_reason,
                                    pallet_detail.sku_id,
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    brand.principle_brand_nama AS brand,
                                    sku.sku_satuan,
                                    sku.sku_kemasan,
                                    pallet_detail.sku_stock_id,
                                    FORMAT(pallet_detail.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                                    pallet_detail.penerimaan_tipe_id,
                                    penerimaan_tipe.penerimaan_tipe_nama,
                                    (ISNULL(pallet_detail.sku_stock_qty,0) - ISNULL(pallet_detail.sku_stock_ambil,0) + ISNULL(pallet_detail.sku_stock_in,0) - ISNULL(pallet_detail.sku_stock_out,0) + ISNULL(pallet_detail.sku_stock_terima,0)) AS sku_stock_qty,
                                    pallet_detail.sku_stock_qty AS sku_stock_qty2,
                                    pallet_detail.sku_stock_ambil,
                                    pallet_detail.sku_stock_in,
                                    pallet_detail.sku_stock_out,
                                    pallet_detail.sku_stock_terima,
									isnull(pallet_detail.batch_no, '') as batch_no
                                    FROM pallet
                                    INNER JOIN pallet_detail
                                    ON pallet_detail.pallet_id = pallet.pallet_id
                                    INNER JOIN sku
                                    ON sku.sku_id = pallet_detail.sku_id
                                    INNER JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    INNER JOIN principle_brand brand
                                    ON brand.principle_brand_id = sku.principle_brand_id
                                    LEFT JOIN penerimaan_tipe
                                    ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail.penerimaan_tipe_id
                                    WHERE pallet.pallet_kode = '$kode'
                                    AND pallet_detail.pallet_detail_id IS NOT NULL
                                    ORDER BY sku.sku_kode ASC");


        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
    }

    public function deleteSelectedSKU($pallet_kode, $selectedSKU)
    {
        $palletIsLock = $this->db->select('pallet_is_lock')->from('pallet')->where('pallet_kode', $pallet_kode)->get()->row()->pallet_is_lock;

        if ($palletIsLock == 1) {
            $result = array(
                'status' => 'warning',
                'message' => 'Pallet dikunci, operasi penghapusan dibatalkan.'
            );

            return $result;
        }

        $this->db->trans_begin();

        $this->db->where_in('pallet_detail_id', $selectedSKU);
        $this->db->delete('pallet_detail');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = array(
                'status' => 'error',
                'message' => 'Gagal melakukan penghapusan SKU.'
            );
        } else {
            $this->db->trans_commit();
            $result = array(
                'status' => 'success',
                'message' => 'Berhasil melakukan penghapusan SKU.'
            );
        }

        return $result;
    }

    public function get_history_pallet_by_id($id)
    {
        $query = $this->db->query("SELECT
                                    his.rak_lajur_detail_pallet_id,
                                    his.pallet_id,
                                    pallet.pallet_kode,
                                    his.depo_id,
                                    depo.depo_nama,
                                    his.depo_detail_id,
                                    depo_detail.depo_detail_nama,
                                    rak.rak_id,
                                    rak.rak_kode,
                                    rak_lajur.rak_lajur_id,
                                    rak_lajur.rak_lajur_nama,
                                    rak_lajur_detail.rak_lajur_detail_id,
                                    rak_lajur_detail.rak_lajur_detail_nama,
                                    FORMAT(his.tanggal_create, 'dd-MM-yyyy') AS tanggal_create
                                    FROM rak_lajur_detail_pallet_his his
                                    LEFT JOIN pallet
                                    ON pallet.pallet_id = his.pallet_id
                                    LEFT JOIN rak_lajur_detail_pallet
                                    ON rak_lajur_detail_pallet.pallet_id = pallet.pallet_id
                                    LEFT JOIN rak_lajur_detail
                                    ON rak_lajur_detail.rak_lajur_detail_id = rak_lajur_detail_pallet.rak_lajur_detail_id
                                    LEFT JOIN rak_lajur
                                    ON rak_lajur.rak_lajur_id = rak_lajur_detail.rak_lajur_id
                                    LEFT JOIN rak
                                    ON rak.rak_id = rak_lajur.rak_id
                                    LEFT JOIN tipe_mutasi
                                    ON his.tipe_mutasi_id = tipe_mutasi.tipe_mutasi_id
                                    LEFT JOIN depo_detail
                                    ON depo_detail.depo_detail_id = his.depo_detail_id
                                    LEFT JOIN depo
                                    ON depo.depo_id = his.depo_id
                                    WHERE his.pallet_id = '$id'
                                    ORDER BY his.tanggal_create DESC");


        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
    }

    public function getDepoPrefix($depo_id)
    {
        $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
        return $listDoBatch->row();
    }

    public function getKodeAutoComplete($value)
    {
        return $this->db->select("SUBSTRING(pallet_kode, CHARINDEX('PAL/', pallet_kode), LEN(pallet_kode)) as kode")
            ->from("pallet")
            ->where("depo_id", $this->session->userdata('depo_id'))
            ->like("pallet_kode", $value)->get()->result();
    }

    public function getPalletByID($pallet_id)
    {
        $query = $this->db->query("SELECT *
            FROM pallet
            WHERE pallet_id = '$pallet_id'");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function insertPalletHis($dataPallet)
    {
        $this->db->set('pallet_id', $dataPallet['pallet_id']);
        $this->db->set('pallet_jenis_id', $dataPallet['pallet_jenis_id']);
        $this->db->set('depo_id', $dataPallet['depo_id']);
        $this->db->set('rak_lajur_detail_id', $dataPallet['rak_lajur_detail_id']);
        $this->db->set('pallet_kode', $dataPallet['pallet_kode']);
        $this->db->set('pallet_tanggal_create', 'GETDATE()', false);
        $this->db->set('pallet_who_create', $this->session->userdata('pengguna_username'));
        $this->db->set('pallet_is_aktif', $dataPallet['pallet_is_aktif']);
        $this->db->set('pallet_is_lock', $dataPallet['pallet_is_lock']);
        $this->db->set('pallet_is_lock_reason', $dataPallet['pallet_is_lock_reason']);
        $this->db->insert('pallet_his');


        $affectedrows = $this->db->affected_rows();

        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function getPalletDetailByID($pallet_id)
    {
        $query = $this->db->query("SELECT *
            FROM pallet_detail
            WHERE pallet_id = '$pallet_id'");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function insertPalletDetailHis($value)
    {
        $this->db->set('pallet_detail_id', $value['pallet_detail_id']);
        $this->db->set('pallet_id', $value['pallet_id']);
        $this->db->set('sku_id', $value['sku_id']);
        $this->db->set('sku_stock_id', $value['sku_stock_id']);
        $this->db->set('sku_stock_expired_date', $value['sku_stock_expired_date']);
        $this->db->set('sku_stock_qty', $value['sku_stock_qty']);
        $this->db->set('penerimaan_tipe_id', $value['penerimaan_tipe_id']);
        $this->db->set('sku_stock_ambil', $value['sku_stock_ambil']);
        $this->db->set('sku_stock_in', $value['sku_stock_in']);
        $this->db->set('sku_stock_out', $value['sku_stock_out']);
        $this->db->set('sku_stock_terima', $value['sku_stock_terima']);
        $this->db->set('sku_stock_expired_date_sj', $value['sku_stock_expired_date_sj']);
        $this->db->set('batch_no', $value['batch_no']);
        $this->db->insert('pallet_detail_his');

        $affectedrows = $this->db->affected_rows();

        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function updatePalletGenerateDetail2($value)
    {
        $this->db->set('pallet_generate_detail2_is_aktif', 0);
        $this->db->where('pallet_generate_detail2_kode', $value['pallet_kode']);
        $this->db->update('pallet_generate_detail2');

        $affectedrows = $this->db->affected_rows();

        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function deletePalletByID($pallet_id)
    {
        $this->db->where('pallet_id', $pallet_id);
        $this->db->delete('pallet');

        $affectedrows = $this->db->affected_rows();

        if ($affectedrows > 0) {
            $querydelete = 1;
        } else {
            $querydelete = 0;
        }

        return $querydelete;
    }

    public function deletePalletDetailByID($pallet_id)
    {
        $this->db->where('pallet_id', $pallet_id);
        $this->db->delete('pallet_detail');

        $affectedrows = $this->db->affected_rows();

        if ($affectedrows > 0) {
            $querydelete = 1;
        } else {
            $querydelete = 0;
        }

        return $querydelete;
    }

    public function deleteRakLajurDetailPalletByID($pallet_id)
    {
        $this->db->where('pallet_id', $pallet_id);
        $this->db->delete('rak_lajur_detail_pallet');

        $affectedrows = $this->db->affected_rows();

        if ($affectedrows > 0) {
            $querydelete = 1;
        } else {
            $querydelete = 0;
        }

        return $querydelete;
    }
}