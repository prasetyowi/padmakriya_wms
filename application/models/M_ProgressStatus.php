<?php

class M_ProgressStatus extends CI_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function Get_ProgressStatus()
    {
        $data = $this->db->select("s.*")->from('ProgressStatus as s')
            ->get();

        return $data->result_array();
    }

    public function Get_ProgressStatus_By_SequenceNo($SequenceNo, $moduleName)
    {
        $query = $this->db->select("s.* ")->from('ProgressStatus as s')
            ->where('s.SequenceNo', $SequenceNo)
            ->where('s.Modul', $moduleName)
            ->get();

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->row_array();
        }
        return $query;
    }
}
