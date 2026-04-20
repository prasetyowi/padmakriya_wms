<?php

class M_RekalkulasiStock extends CI_Model
{
    //tes user baru 5
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
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

    public function GetDepoDetail()
    {
        $this->db->select("*")
            ->from("depo_detail")
            ->where("depo_id", $this->session->userdata('depo_id'))
            ->order_by("depo_detail_nama");
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetPrinciple()
    {
        $query = $this->db->query("SELECT
                                    principle.principle_id,
                                    principle.principle_kode
                                    FROM sku_stock
                                    LEFT JOIN sku
                                    ON sku.sku_id = sku_stock.sku_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    WHERE sku_stock.depo_id = '" . $this->session->userdata('depo_id') . "'
                                    GROUP BY principle.principle_id,
                                            principle.principle_kode
                                    ORDER BY principle.principle_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function GetTotalRekalkulasiByFilter($tgl1, $tgl2, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status)
    {

        $query = $this->db->query("Exec proses_rekalkulasi_stock '" . $this->session->userdata('depo_id') . "', '$tgl1', '$tgl2', '" . $this->session->userdata('pengguna_username') . "', 'view_all', '','','$perusahaan','$gudang','$principle','$sku_kode','$sku_nama_produk','$status' ");

        if (
            $query->num_rows() == 0
        ) {
            $query = 0;
        } else {
            $query = $query->num_rows();
        }

        return $query;
        // return $this->db->last_query();
    }

    public function GetRekalkulasiByFilter($tgl1, $tgl2, $start, $end, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status)
    {
        $query = $this->db->query("Exec proses_rekalkulasi_stock '" . $this->session->userdata('depo_id') . "', '$tgl1', '$tgl2', '" . $this->session->userdata('pengguna_username') . "', 'view_filtered', '$start', '$end','$perusahaan','$gudang','$principle','$sku_kode','$sku_nama_produk','$status' ");

        if (
            $query->num_rows() == 0
        ) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
        // return $this->db->last_query();
    }

    public function GetProsesRekalkulasiByFilter($tgl1, $tgl2, $start, $end, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status)
    {
        $query = $this->db->query("Exec proses_rekalkulasi_stock '" . $this->session->userdata('depo_id') . "', '$tgl1', '$tgl2', '" . $this->session->userdata('pengguna_username') . "', '', '$start', '$end','$perusahaan','$gudang','$principle','$sku_kode','$sku_nama_produk','$status' ");

        if (
            $query->num_rows() == 0
        ) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
        // return $this->db->last_query();
    }

    public function SimpanRekalkulasi($tgl1, $tgl2, $start, $end, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status)
    {
        $query = $this->db->query("Exec proses_rekalkulasi_stock '" . $this->session->userdata('depo_id') . "', '$tgl1', '$tgl2', '" . $this->session->userdata('pengguna_username') . "', 'simpan', '$start', '$end','$perusahaan','$gudang','$principle','$sku_kode','$sku_nama_produk','$status' ");

        if (
            $query->num_rows() == 0
        ) {
            $query = 0;
        } else {
            $query = $query->result();
        }

        return $query;
        // return $this->db->last_query();
    }
}
