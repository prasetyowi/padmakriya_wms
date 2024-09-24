<?php

class M_AutoGen extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	public function Exec_CodeGenGeneral( $Prefix, $Unit )
	{
		$query = $this->db->query("declare @res varchar(50)  
								   exec GenCodeGeneral GETDATE(), '$Prefix', '$Unit', @res OUTPUT
								   select @res as resultMessage");
        
		$res = $query->result_array();
		
		if(count($res) > 0){
			return $res[0]['resultMessage'];
		}
		else{
			
			return '';
		}
		
		
	}
	public function Exec_CodeGenGeneralTanggal( $Tanggal, $Prefix, $Unit )
	{
		
		$param = array();
		
		$query = $this->db->query("declare @res varchar(50)  
								   exec GenCodeGeneral ?, ?, ?, @res OUTPUT
								   select @res as resultMessage", array( $Tanggal, $Prefix, $Unit));
		
		$res = $query->result_array();
		
		if(count($res) > 0){
			
			return $res[0]['resultMessage'];
		}
		else{
			
			
			return '';
		}
	}

}