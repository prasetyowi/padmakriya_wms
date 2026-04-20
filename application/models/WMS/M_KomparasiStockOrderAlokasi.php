<?php

class M_KomparasiStockOrderAlokasi extends CI_Model
{
    public function execProsesMaintenanceOrderVSLokasi($mode, $depo_id)
    {
        return $this->db->query("exec proses_maintenance_order_vs_alokasi " . $mode . ",'" . $depo_id . "'")->result_array();
    }
}
