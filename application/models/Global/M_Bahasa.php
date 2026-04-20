<?php

class M_Bahasa extends CI_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	public function Getbahasa( $bahasa )
	{
		$this->db	->select("bahasa_id, bahasa_kode, bahasa_nama, bahasa_nama_". $bahasa ." as bahasa", FALSE)
					->from("bahasa");
		$query = $this->db->get();

		if( $query->num_rows() == 0 )	{	return 0; }
		else							{	return $query->result_array(); }
	}
	
	public function Getflag()
	{
		$query = $this->db->query("select flag_kode, flag_nama from flag order by flag_nama");

		if( $query->num_rows() == 0 )	{	return 0; }
		else							{	return $query->result_array(); }
	}
	
	public function Getdefault_language()
	{
		$query = $this->db->query("select vrbl_kode from vrbl where vrbl_param = 'DEFAULT_LANGUAGE'");

		if( $query->num_rows() == 0 )	{	return 0; }
		else							{	return $query->result_array(); }
	}
	
	public function Updatedefault_language()
	{
		$query = $this->db->query("select vrbl_kode from vrbl where vrbl_param = 'DEFAULT_LANGUAGE'");

		if( $query->num_rows() == 0 )	{	return 0; }
		else							{	return $query->result_array(); }
	}
}
