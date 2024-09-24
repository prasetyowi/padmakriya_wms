<?php
date_default_timezone_set('Asia/Jakarta');

class M_PenerimaanMutasiAntarUnit extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function Get_NEWID()
    {
        $query = $this->db->query("SELECT NEWID() AS generate_kode");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->row(0)->generate_kode;
        }

        return $query;
    }

    public function getDepoPrefix($depo_id)
    {
        $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
        return $listDoBatch->row();
    }

    public function getDokumen()
    {
        $query = $this->db->query("SELECT tr_mutasi_depo_kode FROM tr_mutasi_depo");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getGudang()
    {
        $query = $this->db->query("SELECT depo_detail_id, depo_detail_nama FROM depo_detail WHERE depo_detail_is_gudang_penerima = 2 AND depo_id = '" . $this->session->userdata('depo_id') . "'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getPallet($id)
    {
        $query = $this->db->query("SELECT p.pallet_kode, 
                                        pj.pallet_jenis_nama
                                    FROM pallet AS p
                                    LEFT JOIN pallet_jenis AS pj ON p.pallet_jenis_id = pj.pallet_jenis_id
                                    LEFT JOIN tr_mutasi_depo_terima_detail AS mutasi_detail
                                    ON p.pallet_id = mutasi_detail.pallet_id
                                    LEFT JOIN tr_mutasi_depo_terima AS mutasi
                                    ON mutasi_detail.tr_mutasi_depo_terima_id = mutasi.tr_mutasi_depo_terima_id
                                    WHERE mutasi.tr_mutasi_depo_id  = '$id'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function getDataMutasi($tgl1, $tgl2, $filter_dokumen)
    {
        if ($filter_dokumen == "") {
            $filter_dokumen = "";
        } else {
            $filter_dokumen = "AND h.tr_mutasi_depo_kode = '$filter_dokumen'";
        }

        $query = $this->db->query("SELECT h.tr_mutasi_depo_id,
                                        h.tr_mutasi_depo_kode,
                                        h.tr_mutasi_depo_status,
                                        FORMAT(h.tr_mutasi_depo_tgl_create, 'dd-MM-yyyy') AS tr_mutasi_depo_tgl_create,
                                        asal.depo_nama AS depo_asal,
                                        tujuan.depo_nama AS depo_tujuan,
                                        d.depo_id_tujuan,
                                        h.tr_mutasi_depo_info,
                                        h2.tr_mutasi_depo_terima_id,
                                        CASE WHEN 
                                            dh2.tr_mutasi_stok_id IS NOT NULL THEN 1 ELSE 0
                                        END AS is_generate
                                    FROM tr_mutasi_depo AS h
                                    LEFT JOIN tr_mutasi_depo_detail AS d
                                        ON h.tr_mutasi_depo_id = d.tr_mutasi_depo_id
                                    LEFT JOIN depo AS asal
                                        ON d.depo_id_asal = asal.depo_id
                                    LEFT JOIN depo AS tujuan
                                        ON d.depo_id_tujuan = tujuan.depo_id
									LEFT JOIN tr_mutasi_depo_terima AS h2
									    ON h.tr_mutasi_depo_id = h2.tr_mutasi_depo_id
                                    LEFT JOIN tr_mutasi_depo_terima_detail2 AS dh2 
                                        ON h2.tr_mutasi_depo_terima_id = dh2.tr_mutasi_depo_terima_id
                                    WHERE FORMAT(h.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') >= '$tgl1'
                                    AND FORMAT(h.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') <= '$tgl2'
                                    $filter_dokumen
                                    GROUP BY  h.tr_mutasi_depo_id,
                                        h.tr_mutasi_depo_kode,
                                        h.tr_mutasi_depo_status,
                                        FORMAT(h.tr_mutasi_depo_tgl_create, 'dd-MM-yyyy'),
                                        asal.depo_nama,
                                        tujuan.depo_nama,
                                        d.depo_id_tujuan,
                                        h.tr_mutasi_depo_info,
                                        h2.tr_mutasi_depo_terima_id,
                                        CASE WHEN 
                                            dh2.tr_mutasi_stok_id IS NOT NULL THEN 1 ELSE 0
                                        END");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getDataMutasiHeader($id)
    {
        $query = $this->db->query("SELECT h.tr_mutasi_depo_id,
                                        h.tr_mutasi_depo_kode,
                                        h.tr_mutasi_depo_status,
                                        ISNULL(h.tr_mutasi_depo_keterangan, ' ') AS tr_mutasi_depo_keterangan,
                                        h.tr_mutasi_depo_info,
                                        FORMAT(h.tr_mutasi_depo_tgl_create, 'dd-MM-yyyy') AS tr_mutasi_depo_tgl_create,
                                        asal.depo_nama AS depo_asal,
                                        tujuan.depo_nama AS depo_tujuan,
                                        e.ekspedisi_nama,
                                        k.karyawan_nama,
										k2.kendaraan_nopol,
                                        h.tr_mutasi_depo_tgl_update
                                    FROM tr_mutasi_depo AS h
                                    LEFT JOIN tr_mutasi_depo_detail AS d
                                    ON h.tr_mutasi_depo_id = d.tr_mutasi_depo_id
                                    LEFT JOIN depo AS asal
                                    ON d.depo_id_asal = asal.depo_id
                                    LEFT JOIN depo AS tujuan
                                    ON d.depo_id_tujuan = tujuan.depo_id
									LEFT JOIN ekspedisi AS e
									ON h.ekspedisi_id = e.ekspedisi_id
									LEFT JOIN karyawan AS k
									ON h.karyawan_id = k.karyawan_id
									LEFT JOIN kendaraan AS k2
									ON h.kendaraan_id = k2.kendaraan_id
                                    WHERE h.tr_mutasi_depo_id = '$id'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function getDataMutasiDetail($id)
    {
        $query = $this->db->query("SELECT d2.tr_mutasi_depo_id,
                                        d2.sku_id,
                                        d2.sku_stock_id,
                                        ISNULL(d2.qty_plan, 0) AS qty_plan,
                                        ISNULL(d2.qty_ambil, 0) AS qty_ambil,
                                        s.sku_kode,
                                        s.sku_satuan,
                                        s.sku_kemasan,
                                        s.sku_nama_produk,
                                        p.principle_kode,
                                        FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                        dd.depo_detail_id,
                                        dd.depo_detail_nama
                                    FROM tr_mutasi_depo_detail_2 AS d2
                                    LEFT JOIN tr_mutasi_depo_detail AS d
                                    ON d2.tr_mutasi_depo_detail_id = d.tr_mutasi_depo_detail_id
                                    LEFT JOIN sku AS s
                                    ON d2.sku_id = s.sku_id
                                    LEFT JOIN principle AS p
                                    ON s.principle_id = p.principle_id
                                    LEFT JOIN sku_stock AS ss
                                    ON d2.sku_stock_id = ss.sku_stock_id
                                    LEFT JOIN depo_detail AS dd
                                    ON d.depo_detail_id_asal = dd.depo_detail_id
                                    WHERE d2.tr_mutasi_depo_id = '$id'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getKodeAutoComplete($value, $type)
    {
        if ($type == 'notone') {
            return $this->db->query("SELECT
                    SUBSTRING(pallet_kode, CHARINDEX('PAL/', pallet_kode), LEN(pallet_kode)) as kode,
                    pallet_is_aktif as is_aktif
                FROM pallet
                WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND pallet_kode LIKE '%" . $value . "%'
        
            UNION
        
                SELECT
                SUBSTRING(a.pallet_generate_detail2_kode, CHARINDEX('PAL/', a.pallet_generate_detail2_kode), LEN(a.pallet_generate_detail2_kode)) as kode,
                    a.pallet_generate_detail2_is_aktif AS is_aktif
                FROM
                    pallet_generate_detail2 a
                    LEFT JOIN pallet_generate b ON a.pallet_generate_id = b.pallet_generate_id
                WHERE
                    b.depo_id = '" . $this->session->userdata('depo_id') . "'
                    AND a.pallet_generate_detail2_is_aktif = 0
                    AND a.pallet_generate_detail2_kode LIKE '%" . $value . "%' ESCAPE '!'
        ")->result();
        }

        if ($type == 'one') {
            return $this->db->query("select rak_lajur_detail_nama as kode from rak_lajur_detail where rak_lajur_id in (select rak_lajur_id from rak_lajur inner join rak on rak.rak_id = rak_lajur.rak_id where depo_id = '" . $this->session->userdata('depo_id') . "') AND rak_lajur_detail_nama LIKE '%$value%' ORDER BY rak_lajur_detail_nama ASC")->result();
        }
    }

    public function check_kode_pallet($pallet_kode, $depo_detail_id)
    {
        $query = $this->db->query("SELECT *
									FROM pallet_generate_detail2
									WHERE pallet_generate_detail2_kode = '$pallet_kode'");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->row_array();
        }

        return $query;
    }

    public function insertPallet($data, $depo_detail_id)
    {
        //update
        $this->db->update("pallet_generate_detail2", ['pallet_generate_detail2_is_aktif' => 1], ['pallet_generate_detail2_id' =>  $data['pallet_generate_detail2_id']]);

        // insert
        $this->db->set("pallet_id", $data['pallet_generate_detail2_id']);
        $this->db->set("pallet_jenis_id", $data['pallet_jenis_id']);
        $this->db->set("depo_id",  $this->session->userdata('depo_id'));
        $this->db->set("rak_lajur_detail_id", null);
        $this->db->set("pallet_kode", $data['pallet_generate_detail2_kode']);
        $this->db->set("pallet_tanggal_create", date('Y-m-d H:i:s'));
        $this->db->set("pallet_who_create", $this->session->userdata('pengguna_username'));
        $this->db->set("pallet_is_aktif", 1);
        $this->db->set("pallet_is_lock", 0);

        $this->db->insert("pallet");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function delPalletDetailTemp($pallet_id)
    {
        $query = $this->db->query("DELETE FROM pallet_detail_temp WHERE pallet_id = '$pallet_id'");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $querydelete = 1;
        } else {
            $querydelete = 0;
        }

        return $querydelete;
    }

    public function insertIntoPalletDetailTemp($pallet_id, $mutasi_depo_id)
    {
        $query = $this->db->query("SELECT mutasi.*,
                                    sku_stock.sku_stock_expired_date
                                    FROM tr_mutasi_depo_detail_2 AS mutasi
                                    LEFT JOIN sku_stock ON mutasi.sku_stock_id = sku_stock.sku_stock_id
                                    WHERE mutasi.tr_mutasi_depo_id = '$mutasi_depo_id'");

        foreach ($query->result_array() as $data) {
            $this->db->set('pallet_detail_id', 'NEWID()', FALSE);
            $this->db->set('pallet_id', $pallet_id);
            $this->db->set('sku_id', $data['sku_id']);
            $this->db->set('sku_stock_id', null);
            $this->db->set('sku_stock_expired_date', $data['sku_stock_expired_date']);
            $this->db->set('sku_stock_qty', $data['qty_plan']);
            $this->db->set('penerimaan_tipe_id', null);
            $this->db->set('tanggal_create', date('Y-m-d H:i:s'));
            $this->db->set('jumlah_sisa', $data['qty_ambil']);
            $this->db->set('penerimaan_tipe_id', $mutasi_depo_id);

            $this->db->insert('pallet_detail_temp');
        }

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function getDataMutasiDetailTemp($pallet_id)
    {
        $query = $this->db->query("SELECT temp.*,
                                        s.sku_kode,
                                        s.sku_nama_produk,
                                        s.sku_kemasan,
                                        s.sku_satuan,
                                        s.sku_nama_produk,
                                        p.principle_kode
                                    FROM pallet_detail_temp AS temp
                                    LEFT JOIN sku AS s
                                    ON temp.sku_id = s.sku_id
                                    LEFT JOIN principle AS p
                                    ON s.principle_id = p.principle_id
                                    WHERE temp.pallet_id = '$pallet_id'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Delete_PalletDetailTemp($pallet_detail_id)
    {
        $this->db->where("pallet_detail_id", $pallet_detail_id);
        $this->db->delete("pallet_detail_temp");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $querydelete = 1;
        } else {
            $querydelete = 0;
        }

        return $querydelete;
    }

    public function insert_tr_mutasi_depo_terima($tr_mutasi_depo_terima_id, $tr_mutasi_depo_terima_kode, $tr_mutasi_depo_id)
    {
        $query = $this->db->get_where("tr_mutasi_depo", ['tr_mutasi_depo_id' => $tr_mutasi_depo_id])->row_array();

        $this->db->set("tr_mutasi_depo_terima_id", $tr_mutasi_depo_terima_id);
        $this->db->set("tr_mutasi_depo_id", $tr_mutasi_depo_id);
        $this->db->set("tr_mutasi_depo_terima_kode", $tr_mutasi_depo_terima_kode);
        $this->db->set("depo_id", $this->session->userdata('depo_id'));
        $this->db->set("tr_mutasi_depo_terima_status", "completed");
        $this->db->set("tr_mutasi_depo_terima_keterangan", $query['tr_mutasi_depo_keterangan']);
        $this->db->set("tr_mutasi_depo_terima_tgl_create", date('Y-m-d H:i:s'));
        $this->db->set("tr_mutasi_depo_terima_who_create", $this->session->userdata('pengguna_username'));
        $this->db->set("tr_mutasi_depo_terima_tgl_update", null);
        $this->db->set("tr_mutasi_depo_terima_who_update", null);

        $this->db->insert("tr_mutasi_depo_terima");

        $this->db->set("tr_mutasi_depo_info", "Diterima");
        $this->db->where("tr_mutasi_depo_id", $tr_mutasi_depo_id);

        $this->db->update("tr_mutasi_depo");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsertupdate = 1;
        } else {
            $queryinsertupdate = 0;
        }

        return $queryinsertupdate;
    }

    public function insert_tr_mutasi_depo_terima_detail($tr_mutasi_depo_terima_id, $pallet_id, $depo_detail_id, $sku_id, $jumlah_sisa, $jumlah_plan, $expired_date)
    {
        $this->db->set("tr_mutasi_depo_terima_detail_id", "NEWID()", FALSE);
        $this->db->set("tr_mutasi_depo_terima_id", $tr_mutasi_depo_terima_id);
        $this->db->set("sku_id", $sku_id);
        $this->db->set("qty_plan", $jumlah_plan);
        $this->db->set("qty_terima", $jumlah_sisa);
        $this->db->set("depo_detail_id", $depo_detail_id);
        $this->db->set("pallet_id", $pallet_id);
        $this->db->set("sku_expired_date", $expired_date);

        $this->db->insert("tr_mutasi_depo_terima_detail");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function insert_update_sku_stock($depo_detail_id, $tr_mutasi_depo_id, $pallet_id)
    {
        $sql_check = $this->db->query("SELECT d2.tr_mutasi_depo_id,
                                            d2.sku_id,
                                            d2.sku_stock_id,
                                            ISNULL(d2.qty_plan, 0) AS qty_plan,
                                            ISNULL(d2.qty_ambil, 0) AS qty_ambil,
                                            s.sku_kode,
                                            s.sku_satuan,
                                            s.sku_kemasan,
                                            s.sku_nama_produk,
                                            s.client_wms_id,
                                            s.sku_induk_id,
                                            p.principle_kode,
                                            FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                            dd.depo_detail_id,
                                            dd.depo_detail_nama
                                        FROM tr_mutasi_depo_detail_2 AS d2
                                        LEFT JOIN tr_mutasi_depo_detail AS d
                                        ON d2.tr_mutasi_depo_detail_id = d.tr_mutasi_depo_detail_id
                                        LEFT JOIN sku AS s
                                        ON d2.sku_id = s.sku_id
                                        LEFT JOIN principle AS p
                                        ON s.principle_id = p.principle_id
                                        LEFT JOIN sku_stock AS ss
                                        ON d2.sku_stock_id = ss.sku_stock_id
                                        LEFT JOIN depo_detail AS dd
                                        ON d.depo_detail_id_asal = dd.depo_detail_id
                                        WHERE d2.tr_mutasi_depo_id = '$tr_mutasi_depo_id'");

        if ($sql_check->num_rows() == 0) {
            $queryupdate = 0;
        } else {
            foreach ($sql_check->result() as $row) {
                $query_stock_masuk = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '$row->sku_id' AND sku_stock_expired_date = '$row->sku_stock_expired_date' AND depo_detail_id = '$depo_detail_id' ");

                if ($query_stock_masuk->num_rows() == 0) {
                    $query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");
                    $sku_stock_id       = $query_sku_stock_id->row(0)->generate_kode;

                    $this->db->set("sku_stock_id", $sku_stock_id);
                    $this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
                    $this->db->set("client_wms_id", $row->client_wms_id);
                    $this->db->set("depo_id", $this->session->userdata('depo_id'));
                    $this->db->set("depo_detail_id", $depo_detail_id);
                    $this->db->set("sku_induk_id", $row->sku_induk_id);
                    $this->db->set("sku_id", $row->sku_id);
                    $this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
                    $this->db->set("sku_stock_awal", "0");
                    $this->db->set("sku_stock_masuk", $row->qty_ambil);
                    $this->db->set("sku_stock_alokasi", "0");
                    $this->db->set("sku_stock_saldo_alokasi", "0");
                    $this->db->set("sku_stock_keluar", "0");
                    $this->db->set("sku_stock_akhir", "0");
                    $this->db->set("sku_stock_is_jual", "1");
                    $this->db->set("sku_stock_is_aktif", "1");
                    $this->db->set("sku_stock_is_deleted", "0");

                    $this->db->insert("sku_stock");

                    $this->db->set("sku_stock_id", $sku_stock_id);
                    $this->db->where("penerimaan_tipe_id", $tr_mutasi_depo_id);
                    $this->db->where("sku_id", $row->sku_id);
                    $this->db->where("sku_stock_expired_date", $row->sku_stock_expired_date);

                    $this->db->update("pallet_detail_temp");
                } else {
                    $sku_stock_masuk    = $query_stock_masuk->row(0)->sku_stock_masuk + $row->qty_ambil;
                    $sku_stock_id       = $query_stock_masuk->row(0)->sku_stock_id;
                    $tipe               = "masuk";
                    $waktu_delay        = "00:00:01";

                    $this->db->query("exec insertupdate_sku_stock '$tipe', '$sku_stock_id', NULL, '$row->qty_ambil'");

                    $this->db->set("sku_stock_id", $sku_stock_id);
                    $this->db->where("penerimaan_tipe_id", $tr_mutasi_depo_id);
                    $this->db->where("sku_id", $row->sku_id);
                    $this->db->where("sku_stock_expired_date", $row->sku_stock_expired_date);

                    $this->db->update("pallet_detail_temp");
                }
            }

            $queryupdate = 1;
        }

        return $queryupdate;
    }

    public function get_pallet_detail_temp($tr_mutasi_depo_id, $pallet_id)
    {
        $this->db->select("*")
            ->from("pallet_detail_temp")
            ->where("pallet_id", $pallet_id)
            ->where("penerimaan_tipe_id", $tr_mutasi_depo_id);

        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function get_pallet_detail($pallet_detail_id_temp)
    {
        $this->db->select("pallet_detail_id")
            ->from("pallet_detail")
            ->where("pallet_detail_id", $pallet_detail_id_temp);

        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->row(0)->pallet_detail_id;
        }

        return $query;
    }

    public function insert_pallet_detail($data)
    {
        $this->db->set("pallet_detail_id", $data['pallet_detail_id']);
        $this->db->set("pallet_id", $data['pallet_id']);
        $this->db->set("sku_id", $data['sku_id']);
        $this->db->set("sku_stock_id", $data['sku_stock_id']);
        $this->db->set("sku_stock_expired_date", $data['sku_stock_expired_date']);
        $this->db->set("sku_stock_qty", 0);
        $this->db->set("sku_stock_terima", $data['jumlah_sisa']);

        $this->db->insert("pallet_detail");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function update_pallet_detail($data)
    {
        $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_terima','" . $data['pallet_id'] . "', '" . $data['sku_stock_id'] . "', '" . $data['jumlah_sisa'] . "'");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryupdate = 1;
        } else {
            $queryupdate = 0;
        }

        return $queryupdate;
    }

    public function delete_pallet_detail_tmp($pallet_id, $tr_mutasi_depo_id)
    {
        $this->db->query("DELETE FROM pallet_detail_temp WHERE pallet_id = '$pallet_id' AND penerimaan_tipe_id = '$tr_mutasi_depo_id'");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $querydelete = 1;
        } else {
            $querydelete = 0;
        }

        return $querydelete;
    }

    public function getMutasiDepoTerimaByID($id)
    {
        $query = $this->db->query("SELECT s.client_wms_id,
                                        s.principle_id,
                                        h2.depo_id,
                                        h2.tr_mutasi_depo_terima_kode,
                                        GETDATE() AS tanggal,
                                        d.depo_detail_id_asal
                                    FROM tr_mutasi_depo_terima AS h2
                                    LEFT JOIN tr_mutasi_depo_terima_detail AS d2
                                    ON h2.tr_mutasi_depo_terima_id = d2.tr_mutasi_depo_terima_id
                                    LEFT JOIN sku AS s ON d2.sku_id = s.sku_id
                                    LEFT JOIN tr_mutasi_depo AS h
                                    ON h2.tr_mutasi_depo_id = h.tr_mutasi_depo_id
                                    LEFT JOIN tr_mutasi_depo_detail AS d
                                    ON h.tr_mutasi_depo_id = d.tr_mutasi_depo_id
                                    WHERE h2.tr_mutasi_depo_terima_id = '$id'
                                    GROUP BY s.client_wms_id, s.principle_id, h2.depo_id, h2.tr_mutasi_depo_terima_kode, d.depo_detail_id_asal
                                    ORDER BY s.principle_id ASC");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getMutasiDepoTerimaDetailAsal($tr_mutasi_depo_terima_id, $principle_id)
    {
        $query = $this->db->query("SELECT dh1.depo_detail_id,
                                        dh1.pallet_id,
                                        ssh1.sku_stock_id,
                                        dh1.sku_id,
                                        ssh1.sku_stock_expired_date,
                                        dh1.qty_terima
                                FROM tr_mutasi_depo_terima h1
                                LEFT JOIN tr_mutasi_depo_terima_detail dh1 ON h1.tr_mutasi_depo_terima_id = dh1.tr_mutasi_depo_terima_id
                                -- LEFT JOIN pallet p ON dh1.pallet_id = p.pallet_id 
                                LEFT JOIN pallet_detail pd ON dh1.pallet_id = pd.pallet_id and dh1.sku_id = pd.sku_id and dh1.sku_expired_date = pd.sku_stock_expired_date
                                LEFT JOIN sku_stock ssh1 ON pd.sku_stock_id = ssh1.sku_stock_id
                                LEFT JOIN sku AS s ON dh1.sku_id = s.sku_id
                                WHERE h1.tr_mutasi_depo_terima_id = '$tr_mutasi_depo_terima_id'
                                AND s.principle_id = '$principle_id'
                                GROUP BY dh1.depo_detail_id, dh1.pallet_id, ssh1.sku_stock_id, dh1.sku_id, ssh1.sku_stock_expired_date, dh1.qty_terima");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function insert_tr_mutasi_stok($tr_mutasi_stok_id, $tr_mutasi_stok_kode, $data)
    {
        $this->db->set("tr_mutasi_stok_id", $tr_mutasi_stok_id);
        $this->db->set("client_wms_id", $data['client_wms_id']);
        $this->db->set("principle_id", $data['principle_id']);
        $this->db->set("tr_mutasi_stok_kode", $tr_mutasi_stok_kode);
        $this->db->set("tr_mutasi_stok_tanggal", date('Y-m-d H:i:s'));
        $this->db->set("tr_mutasi_stok_keterangan", "Mutasi stok dari mutasi antar depo");
        $this->db->set("tr_mutasi_stok_status", "Draft");
        $this->db->set("depo_id_asal", $data['depo_id']);
        $this->db->set("tr_mutasi_stok_tgl_create", date('Y-m-d H:i:s'));
        $this->db->set("tr_mutasi_stok_who_create", $this->session->userdata('pengguna_username'));

        $this->db->insert("tr_mutasi_stok");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function insert_tr_mutasi_stok_detail($tr_mutasi_stok_id, $data)
    {
        $this->db->set("tr_mutasi_stok_detail_id", "NEWID()", FALSE);
        $this->db->set("tr_mutasi_stok_id", $tr_mutasi_stok_id);
        $this->db->set("depo_detail_asal", $data['depo_detail_id']);
        $this->db->set("pallet_id_asal", null);
        // $this->db->set("pallet_id_asal", $data['sku_stock_id']);
        $this->db->set("sku_stock_id", $data['sku_stock_id']);
        $this->db->set("sku_id", $data['sku_id']);
        $this->db->set("sku_stock_expired_date", $data['sku_stock_expired_date']);
        $this->db->set("qty", $data['qty_terima']);

        $this->db->insert("tr_mutasi_stok_detail");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function insert_tr_mutasi_depo_terima_detail2($tr_mutasi_depo_terima_id, $tr_mutasi_stok_id)
    {
        $this->db->set("tr_mutasi_depo_terima_detail2_id", "NEWID()", FALSE);
        $this->db->set("tr_mutasi_depo_terima_id", $tr_mutasi_depo_terima_id);
        $this->db->set("tr_mutasi_stok_id", $tr_mutasi_stok_id);

        $this->db->insert("tr_mutasi_depo_terima_detail2");

        $affectedrows = $this->db->affected_rows();
        if ($affectedrows > 0) {
            $queryinsert = 1;
        } else {
            $queryinsert = 0;
        }

        return $queryinsert;
    }

    public function showKodeMutasiStock($id)
    {
        $query = $this->db->query("SELECT tms.tr_mutasi_stok_id, tms.tr_mutasi_stok_kode 
                                    FROM tr_mutasi_stok AS tms
                                    LEFT JOIN tr_mutasi_depo_terima_detail2 AS mdtd2
                                        ON tms.tr_mutasi_stok_id = mdtd2.tr_mutasi_stok_id
                                    WHERE mdtd2.tr_mutasi_depo_terima_id = '$id'");

        if ($query->num_rows() == 0) {
            $query = [];
        } else {
            $query = $query->result_array();
        }

        return $query;
    }
}
