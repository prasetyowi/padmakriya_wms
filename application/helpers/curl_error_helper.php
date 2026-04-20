<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function Get_CURL_Error( $ErrorNumber )
{
	if( $ErrorNumber == 'E0001' )	{	return 'Jumlah Data dari cabang lebih dari satu';	}
	if( $ErrorNumber == 'E0002' )	{	return 'Kode Menu tidak sama';						}
	if( $ErrorNumber == 'E0003' )	{	return 'Link Menu tidak sama';						}
	if( $ErrorNumber == 'E0004' )	{	return 'Nama Menu tidak sama';						}
	if( $ErrorNumber == 'E0005' )	{	return 'Aplikasi Menu tidak sama';					}
	if( $ErrorNumber == 'E0006' )	{	return 'Tipe tidak sama';							}
	if( $ErrorNumber == 'E0007' )	{	return 'Status Menu Delete tidak sama';				}
	if( $ErrorNumber == 'E0008' )	{	return 'Status Menu Update tidak sama';				}
	if( $ErrorNumber == 'E0009' )	{	return 'Status Menu Read tidak sama';				}
	if( $ErrorNumber == 'E0010' )	{	return 'Status Menu Create tidak sama';				}
	
	if( $ErrorNumber == 'E0011' )	{	return 'Menu Parent tidak sama';					}
	if( $ErrorNumber == 'E0012' )	{	return 'Kelas Menu tidak sama';						}
	if( $ErrorNumber == 'E0013' )	{	return 'Akses Create tidak sama';					}
	if( $ErrorNumber == 'E0014' )	{	return 'Akses Read tidak sama';						}
	if( $ErrorNumber == 'E0015' )	{	return 'Akses Update tidak sama';					}
	if( $ErrorNumber == 'E0016' )	{	return 'Akses Delete tidak sama';					}
	
}