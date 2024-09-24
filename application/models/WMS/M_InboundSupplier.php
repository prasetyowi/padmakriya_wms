<?php

date_default_timezone_set('Asia/Jakarta');

class M_InboundSupplier extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('M_Vrbl');
  }

  public function filteredData($dataPost)
  {
    $date = explode(" - ", $dataPost->tglMulai);

    $this->db->select("sl.security_logbook_id as id,
                       sl.security_logbook_kode as kode,
                       ISNULL(FORMAT(sl.security_logbook_tgl_masuk, 'yyyy-MM-dd HH:mm'), '-') as tglMasuk,
                       ISNULL(FORMAT(sl.security_logbook_tgl_keluar, 'yyyy-MM-dd HH:mm'), '-') as tglKeluar,
                       p.principle_nama as principle")
      ->from("security_logbook sl")
      ->join("principle p", "sl.principle_id = p.principle_id", "left")
      ->where("FORMAT(sl.security_logbook_tgl_masuk, 'yyyy-MM-dd') >=", date('Y-m-d', strtotime(str_replace("/", "-", $date[0]))))
      ->where("FORMAT(sl.security_logbook_tgl_masuk, 'yyyy-MM-dd') <=", date('Y-m-d', strtotime(str_replace("/", "-", $date[1]))));
    if ($dataPost->kode != "") {
      $this->db->where("sl.security_logbook_kode", $dataPost->kode);
    }
    $this->db->where("sl.depo_id", $this->session->userdata('depo_id'));
    $this->db->where("sl.flag", 'Inbound Supplier');

    $this->db->order_by("sl.security_logbook_kode", "ASC");

    return $this->db->get()->result();
  }

  public function getDataPrinciple()
  {
    return $this->db->select('principle_id, principle_kode, principle_nama')->from('principle')
      ->order_by('principle_nama')
      ->get()->result();
  }

  public function getDataEkspedisi()
  {
    return $this->db->select('ekspedisi_id, ekspedisi_kode, ekspedisi_nama')->from('ekspedisi')
      ->order_by('ekspedisi_nama')
      ->get()->result();
  }

  public function getDataSuratJalan($id)
  {
    // return $this->db->select('penerimaan_surat_jalan_id, penerimaan_surat_jalan_kode')->from('penerimaan_surat_jalan')->where('penerimaan_surat_jalan_status', 'Open')->where('depo_id', $this->session->userdata('depo_id'))
    //   ->order_by('penerimaan_surat_jalan_kode')
    //   ->get()->result();

    if ($id != null) {
      $union = "UNION
      SELECT
      p.penerimaan_surat_jalan_id,
      p.penerimaan_surat_jalan_kode,
      cw.client_wms_nama,
      principle.principle_kode
    FROM security_logbook sl
    LEFT JOIN penerimaan_surat_jalan p
      ON sl.penerimaan_surat_jalan_id = p.penerimaan_surat_jalan_id
    LEFT JOIN client_wms cw
      ON p.client_wms_id = cw.client_wms_id
    LEFT JOIN principle
      ON p.principle_id = principle.principle_id
    WHERE sl.security_logbook_id = '$id'";
    } else {
      $union = "";
    }

    $query = $this->db->query("SELECT
                p.penerimaan_surat_jalan_id, p.penerimaan_surat_jalan_kode, cw.client_wms_nama, principle.principle_kode
              FROM penerimaan_surat_jalan p
              LEFT JOIN client_wms cw ON p.client_wms_id = cw.client_wms_id
              LEFT JOIN principle ON p.principle_id = principle.principle_id 
              WHERE p.penerimaan_surat_jalan_status = 'Open'
              AND p.depo_id = '" . $this->session->userdata('depo_id') . "'
              AND NOT EXISTS (SELECT
              sl.penerimaan_surat_jalan_id
              FROM security_logbook sl
              WHERE p.penerimaan_surat_jalan_id = sl.penerimaan_surat_jalan_id)
              $union
              ORDER BY p.penerimaan_surat_jalan_kode ASC
              ");

    return $query->result();
  }

  public function getDepoPrefix($depo_id)
  {
    $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
    return $listDoBatch->row();
  }

  public function getSuratJalanKodeByID($noSuratJalan)
  {
    return $this->db->select('penerimaan_surat_jalan_kode')->from('penerimaan_surat_jalan')->where('penerimaan_surat_jalan_id', $noSuratJalan)->where('depo_id', $this->session->userdata('depo_id'))
      ->get()->row()->penerimaan_surat_jalan_kode;
  }

  public function saveData($logSecId, $noSuratJalan, $principle, $ekspedisi, $generate_kode, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, $name_file, $mode, $suratJalanKode)
  {

    if ($mode == 'add') {
      //save to header
      $this->db->set("security_logbook_id", $logSecId);
      $this->db->set("principle_id", $principle);
      $this->db->set("ekspedisi_id", $ekspedisi);
      $this->db->set("penerimaan_surat_jalan_id", $noSuratJalan);
      $this->db->set("penerimaan_surat_jalan_kode", $suratJalanKode);
      $this->db->set("security_logbook_kode", $generate_kode);
      $this->db->set("security_logbook_nama_driver", $namaDriver);
      $this->db->set("security_logbook_no_hp", $noHandphone);
      $this->db->set("security_logbook_nopol", $nopol);
      $this->db->set("security_logbook_total_ctn", $totalCatatanSJ);
      $this->db->set("flag", 'Inbound Supplier');
      // $this->db->set("security_logbook_tgl_masuk", date("Y-m-d H:i:s", strtotime($jamTrukMasuk)));
      $this->db->set("security_logbook_tgl_masuk", "GETDATE()", false);
      $this->db->set("security_logbook_tgl_create", "GETDATE()", false);
      $this->db->set("security_logbook_who_create", $this->session->userdata('pengguna_username'));
      $this->db->set("depo_id", $this->session->userdata('depo_id'));
      $this->db->insert('security_logbook');

      //insert to detail
      if ($name_file !== NULL) {
        $insertBatch = [];
        foreach (json_decode($name_file) as $key => $value) {
          $logSecDetailId = $this->M_Vrbl->Get_NewID();
          $logSecDetailId = $logSecDetailId[0]['NEW_ID'];
          // $insertBatch[$key] = [
          //   'security_logbook_detail_id' => $logSecDetailId,
          //   'security_logbook_id' => $logSecId,
          //   'security_logbook_detail_attachment' => $value
          // ];
          $this->db->set("security_logbook_detail_id", $logSecDetailId);
          $this->db->set("security_logbook_id", $logSecId);
          $this->db->set("security_logbook_detail_attachment", $value);
          $this->db->insert('security_logbook_detail');
        }
        // $this->db->insert_batch('security_logbook_detail', $insertBatch);
      }
    }

    if ($mode == 'edit') {
      $this->db->set("principle_id", $principle);
      $this->db->set("ekspedisi_id", $ekspedisi);
      $this->db->set("penerimaan_surat_jalan_id", $noSuratJalan);
      $this->db->set("penerimaan_surat_jalan_kode", $suratJalanKode);
      $this->db->set("security_logbook_nama_driver", $namaDriver);
      $this->db->set("security_logbook_no_hp", $noHandphone);
      $this->db->set("security_logbook_nopol", $nopol);
      $this->db->set("security_logbook_total_ctn", $totalCatatanSJ);
      $this->db->where("security_logbook_id", $this->input->post('inboundSupplierId'));
      $this->db->update('security_logbook');


      if ($name_file !== NULL) {
        $insertBatch = [];
        foreach (json_decode($name_file) as $key => $value) {
          $logSecDetailId = $this->M_Vrbl->Get_NewID();
          $logSecDetailId = $logSecDetailId[0]['NEW_ID'];
          // $insertBatch[$key] = [
          //   'security_logbook_detail_id' => $logSecDetailId,
          //   'security_logbook_id' => $logSecId,
          //   'security_logbook_detail_attachment' => $value
          // ];
          $this->db->set("security_logbook_detail_id", $logSecDetailId);
          $this->db->set("security_logbook_id", $this->input->post('inboundSupplierId'));
          $this->db->set("security_logbook_detail_attachment", $value);
          $this->db->insert('security_logbook_detail');
        }
        // $this->db->insert_batch('security_logbook_detail', $insertBatch);
      }
    }
  }

  public function konfirmasiData($dataPost)
  {
    $this->db->set("security_logbook_tgl_keluar", "GETDATE()", false);
    $this->db->where("security_logbook_id", $dataPost->inboundSupplierId);
    $result =  $this->db->update('security_logbook');
    if ($result) {
      $response = [
        'status' => true,
        'message' => "Data berhasil dikonfirmasi"
      ];
    } else {
      $response = [
        'status' => false,
        'message' => "Data gagal dikonfirmasi"
      ];
    }

    return $response;
  }

  public function deleteData($dataPost)
  {
    $this->db->trans_begin();

    $this->db->where("security_logbook_id", $dataPost->inboundSupplierId);
    $this->db->delete('security_logbook');

    $getFileDetail = $this->db->select('security_logbook_detail_attachment as file')->from('security_logbook_detail')->where('security_logbook_id', $dataPost->inboundSupplierId)->get()->result();

    foreach ($getFileDetail as $key => $value) {
      if ($value->file != NULL) {
        unlink('assets/images/uploads/LogSecurity/' . $value->file);
      }
    }

    $this->db->where("security_logbook_id", $dataPost->inboundSupplierId);
    $this->db->delete('security_logbook_detail');

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return false;
    } else {
      $this->db->trans_commit();
      return true;
    }
  }

  public function getDataInboundSupplier($inboundSupplierId)
  {

    $header = $this->db->select("sl.*")
      ->from('security_logbook sl')
      ->where('sl.security_logbook_id', $inboundSupplierId)->get()->row();

    $detailImg = $this->db->select("security_logbook_detail_attachment as file")
      ->from('security_logbook_detail')
      ->where('security_logbook_id', $inboundSupplierId)->get()->result();

    $response = [
      'header' => $header,
      'detail' => $detailImg
    ];
    return $response;

    // return $this->db->select("sl.*, sld.security_logbook_detail_attachment as file")
    //   ->from('security_logbook sl')
    //   ->join('security_logbook_detail sld', 'sl.security_logbook_id = sld.security_logbook_id', 'left')
    //   ->where('sl.security_logbook_id', $inboundSupplierId)->get()->result();
  }
}
