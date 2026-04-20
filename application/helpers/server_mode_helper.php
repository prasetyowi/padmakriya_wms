<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function Get_Server_Mode($type, $message) 
{
	// Server Mode : 'INTERNAL', 'INSTANCE', 'ONLINE'
	/*
		INTERNAL	= Semua DB Hanya di 1 instance. 
			Contoh : select * from WMS.dbo.DO 
		
		INSTANCE	= Semua DB Bisa menyebar di berbagai Instance. 
		Instance WMS, FAS, Report bisa berbeda-beda. ( default MASTER DB ini sendiri )
			Contoh : select * from [Nama Linked Server].WMS.dbo.DO 
			
		ONLINE		= DB bisa menyebar di berbagai URL ( Disebabkan karena terletak di Kota/Pulau/Propinsi yang berbeda )
			Memakai API CURL 
	
	*/
	return 'INTERNAL';
}

function Get_Linked_Server_WMS()
{
	return 'PADMAKRIYA';
}

function Get_Linked_Server_FAS()
{
	return 'PADMAKRIYA';
}

function Get_Linked_Server_Report()
{
	return 'PADMAKRIYA';
}