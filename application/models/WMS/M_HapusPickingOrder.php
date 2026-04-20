<?php

class M_HapusPickingOrder extends CI_Model
{
    public function execProsesMaintenanceDokumenBKB($mode, $depo_id)
    {
        return $this->db->query("exec proses_maintenance_dokumen_bkb  " . $mode . ",'" . $depo_id . "'")->result_array();
    }
}
