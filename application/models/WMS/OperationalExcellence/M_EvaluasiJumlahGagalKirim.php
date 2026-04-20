<?php

class M_EvaluasiJumlahGagalKirim extends CI_Model
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

    public function Get_Gudang()
    {
        $query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
                                    AND ISNULL(depo_detail_is_deleted, 0) = '0'
									ORDER BY depo_detail_nama ASC");


        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_kategori()
    {
        $query = $this->db->query("select kategori from reason where reason_modul_kode = 'SRE' and reason_modul = 'Delivery' and reason_is_aktif = '1' group by kategori order by kategori asc");


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
						AND b.client_wms_is_deleted = 0");
        } else {


            $query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.client_wms_id = '" . $this->session->userdata('client_wms_id') . "'
						AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");
        }

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_laporan_monitoring_delivery_order($tgl1, $tgl2, $client_wms_id, $principle_id, $kategori, $tipe)
    {
        set_time_limit(0);

        $data = array();
        $list_kolom = array();

        $query_laporan =  $this->db->query("exec report_monitoring_delivery_order_gagal_by_reason '$tgl1','$tgl2','$client_wms_id','" . $this->session->userdata('depo_id') . "','$principle_id','$kategori'");

        if ($query_laporan->num_rows() == 0) {
            $query_laporan = array();
        } else {
            $query_laporan = $query_laporan->result_array();

            $query_kolom =  $this->db->query("exec generate_kolom_stock_aging '$tgl1','$tgl2','$tipe'");

            foreach ($query_laporan as $key => $value) {

                $query_kolom =  $this->db->query("exec generate_kolom_stock_aging '$tgl1','$tgl2','$tipe'");

                array_push($data, array(
                    'reason_id' => $value['reason_id'],
                    'reason_keterangan' => $value['reason_keterangan'],
                    'kategori' => $value['kategori'],
                ));

                foreach ($query_kolom->result_array() as $key2 => $value2) {

                    $data[$key]['jml_' . $value2['kolom']] = $value['jml_' . $value2['kolom']];
                }
            }
        }

        return $data;
    }

    public function Generate_kolom_laporan_monitoring($tgl1, $tgl2, $tipe)
    {
        $list_kolom = array();

        $query_kolom =  $this->db->query("exec generate_kolom_stock_aging '$tgl1','$tgl2','$tipe'");

        if ($query_kolom->num_rows() == 0) {
            $query_kolom = array();
        } else {
            $query_kolom = $query_kolom->result_array();
        }

        return $query_kolom;
    }
}
