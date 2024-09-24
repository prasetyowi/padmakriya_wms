<script type="text/javascript">	
	var SP_sku_photo_detail_dir = '';
	var SP_PhotoIdx = '';
	var SP_sku_induk_id = '';
	var SP_sku_photo_id = '';
	
	var FotoUtama_RandID = '';
	function compare( a, b ) 
	{
		if ( a.Urutan < b.Urutan ){
			return -1;
		}
		if ( a.Urutan > b.Urutan ){
			return 1;
		}
		return 0;
	}


	function UploadPhotoMenu( sku_induk_id, sku_induk_nama )
	{
		FotoUtama_RandID = '';
		
		SP_sku_induk_id = sku_induk_id;
		
		$("#hdupdatephoto_sku_induk_id").val( sku_induk_id );
		$("#hdupdatephoto_sku_induk_nama").val( sku_induk_nama );
		
		$("#txtupdatephoto_up_sku_induk_nama").val( sku_induk_nama );
		
		$.ajax(
		{
			type: 'POST',
			url: "<?= base_url('SKU_Photo/GetUploadedPhotoByID') ?>",
			data: {
					sku_induk_id : sku_induk_id
					},
			success: function( response )
			{
				ChUploadPhotoMenu( response )
			}
		});
	}
	
	
	function ChUploadPhotoMenu( JSONPhoto )
	{
		var Photo = JSON.parse( JSONPhoto );
		
		if( Photo.PhotoMenu != 0)
		{
			for( i=0 ; i< Photo.PhotoMenu.length ; i++ )
			{
				var sku_photo_keterangan 	= Photo.PhotoMenu[i].sku_photo_keterangan;
				var sku_photo_id 			= Photo.PhotoMenu[i].sku_photo_id;
				var sku_photo_detail_id 	= Photo.PhotoMenu[i].sku_photo_detail_id;
				var sku_photo_detail_dir 	= Photo.PhotoMenu[i].sku_photo_detail_dir;
				var sku_photo_detail_urut 	= Photo.PhotoMenu[i].sku_photo_detail_urut;
				
				var filetype = sku_photo_detail_dir.split('.');
				filetype = filetype[1];
				
				if( filetype == 'png' || filetype == 'jpg' || filetype == 'jpeg')
				{
					filetype = 'image/'+ filetype;
				}
				
				SP_sku_photo_id = sku_photo_id;
				if( sku_photo_detail_dir == "" ){	var Photos = '<?= base_url() ?>assets/images/img.png';	}
				else							{	var Photos = '<?= base_url() ?>'+ sku_photo_detail_dir;				}
			
				var Rand = Math.random() * 1000000000000000000;
				
				var str = '';
				str = str + '	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 nopadding" id="divphotoitem_'+ Rand +'">';
				str = str + '		<div class="panel panel-primary">';
				str = str + '			<div class="panel-body nopadding">';
				str = str + '				<div class="panelimg">';
				str = str + '					<image class="up_imgs" src="<?= base_url() ?>'+ sku_photo_detail_dir +'?'+ new Date().getTime() +'" />';
				//str = str + '					<img id="srcphoto_'+ i +'" src="'+ Photos +'?'+ new Date().getTime() +' class="img-responsive" style="width: 100%; height: 250px;">';
				//str = str + '					<header>';
				//str = str + '						<div class="bottomleft">';
				//str = str + '							<label>'+ sku_induk_nama +'</label>';
				//str = str + '						</div>';
				//str = str + '					</header>';
				str = str + '				</div>';
				str = str + '			</div>';
				str = str + '			<div class="panel-footer">';
				if( sku_photo_detail_urut != 0 )
				{
					str = str + '			<div class="form-group">';
					str = str + '				<button type="button" id="btnubahphoto_'+ Rand +'" class="form-control btn btn-success btnubahphoto" onclick="SetFotoUtama(1, \''+ Rand +'\')">Set Foto Utama</button>';
					str = str + '			</div>';
				}
				else 
				{
					FotoUtama_RandID = Rand;
					
					str = str + '			<div class="form-group">';
					str = str + '				<button type="button" id="btnubahphoto_'+ Rand +'" style="color: black;" class="form-control btn btn-success btnubahphoto" disabled="disabled" onclick="SetFotoUtama(1, \''+ Rand +'\')"><i class="fa fa-check"></i> <strong>Foto Utama</strong></button>';					
					str = str + '			</div>';
				}
				str = str + '				<div class="form-group">';
				str = str + '					<button type="button" class="form-control btn btn-danger btnhapusphoto" onclick="HapusFoto( 1, \''+ Rand +'\')"><i class="fa fa-trash-o"></i> Hapus Foto</button>';
				str = str + '				</div>';
				str = str + '			</div>';
				str = str + '		</div>';
				
				str = str + '		<input type="hidden" id="txtup_images_isfromdb_'+ Rand +'" 	class="txtup_images_isfromdb" value="1" />';
				str = str + '		<input type="hidden" id="txtup_images_name_'+ Rand +'" 		class="txtup_images_name" value="'+ sku_photo_detail_dir +'" />';
				str = str + '		<input type="hidden" id="txtup_images_filetype_'+ Rand +'" 	class="txtup_images_filetype" value="'+ filetype +'" />';
				
				str = str + '		<input type="hidden" id="txtup_images_sku_photo_id_'+ Rand +'" 	class="txtup_images_sku_photo_id" value="'+ sku_photo_id +'" />';
				str = str + '		<input type="hidden" id="txtup_images_sku_photo_detail_id_'+ Rand +'" 	class="txtup_images_sku_photo_detail_id" value="'+ sku_photo_detail_id +'" />';
				
				str = str + '		<input type="hidden" id="txtup_images_RandID_'+ Rand +'" 	class="txtup_images_RandID" value="'+ Rand +'" />';
				
				if( sku_photo_detail_urut == 0 )	{	var isutama = 1;	}
				else 					{	var isutama = 0;	}
				
				str = str + '		<input type="hidden" id="txtup_images_IsUtama_'+ Rand +'" 	class="txtup_images_IsUtama" value="'+ isutama +'" />';
				str = str + '	</div>';
				
				$("#uploadedimages").append( str );	
			}
		}
		
		$("#MainMenu").attr('style','display: none;');
		$("#anylist_letter").attr('style','display: none;');
		$("#Foto").removeAttr('style');
	}
	
	function readURL(input, ID) 
	{
		var strisu = '';
		if( ID == 0 )	{ strisu = '_Utama'; 	var strstyles = 'style="width: 300px; height: 300px;"';	}
		else			{ 						var strstyles = 'style="width: 200px; height: 200px;"';	}
		var filetype = input.files[0].type;

		if( filetype == 'image/png' || filetype == 'image/jpg' || filetype == 'image/jpeg' )
		{
			if (input.files && input.files[0]) 
			{
				var reader = new FileReader();
			
				reader.onload = function(e) 
				{
					var str = '';
					
					var res = e.target.result;
					
					var Rand = Math.random() * 10000000000000000000;
					
					str = str + '	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 col-xl-3" id="divphotoitem_'+ Rand +'">';
					str = str + '		<div class="panel panel-primary">';
					str = str + '			<div class="panel-body nopadding">';
					str = str + '				<div class="panelimg">';
					str = str + '					<image class="up_imgs" src="'+ res +'" />';
					//str = str + '					<img id="srcphoto_'+ i +'" src="'+ Photos +'?'+ new Date().getTime() +' class="img-responsive" style="width: 100%; height: 250px;">';
					//str = str + '					<header>';
					//str = str + '						<div class="bottomleft">';
					//str = str + '							<label>'+ sku_induk_nama +'</label>';
					//str = str + '						</div>';
					//str = str + '					</header>';
					str = str + '				</div>';
					str = str + '			</div>';
					str = str + '			<div class="panel-footer">';
					str = str + '				<div class="form-group">';
					str = str + '					<button type="button" id="btnubahphoto_'+ Rand +'" class="form-control btn btn-success btnubahphoto" onclick="SetFotoUtama( 0, \''+ Rand +'\')"> Set Foto Utama</button>';
					str = str + '				</div>';					
					str = str + '				<div class="form-group">';
					str = str + '					<button type="button" class="form-control btn btn-danger btnhapusphoto" onclick="HapusFoto( 0, \''+ Rand +'\')"><i class="fa fa-trash-o"></i> Hapus Foto</button>';
					str = str + '				</div>';
					str = str + '			</div>';
					str = str + '		</div>';

					if( filetype == 'image/jpg' || filetype == 'image/jpeg' ) 
					{
						res = res.split(',');
						res = res[1]; // ngambil yang tipe string nya saja.
					}
					else if( filetype == 'image/png' )
					{
						res = res.split(',');
						var ln = 1;
						
						if( res.length > 1 )
						{
							ln = res.length - 1;
						}
						
						res = res[ln];
					}
					
					str = str + '		<input type="hidden" id="txtup_images_isfromdb_'+ Rand +'" 	class="txtup_images_isfromdb" value="0" />';
					str = str + '		<input type="hidden" id="txtup_images_name_'+ Rand +'" 		class="txtup_images_name" value="'+ res +'" />';
					str = str + '		<input type="hidden" id="txtup_images_filetype_'+ Rand +'" 	class="txtup_images_filetype" value="'+ filetype +'" />';
						
					str = str + '		<input type="hidden" id="txtup_images_sku_photo_id_'+ Rand +'" 	class="txtup_images_sku_photo_id" value="" />';
					str = str + '		<input type="hidden" id="txtup_images_sku_photo_detail_id_'+ Rand +'" 	class="txtup_images_sku_photo_detail_id" value="" />';
					
					str = str + '		<input type="hidden" id="txtup_images_RandID_'+ Rand +'" 	class="txtup_images_RandID" value="'+ Rand +'" />';
					str = str + '		<input type="hidden" id="txtup_images_IsUtama_'+ Rand +'" 	class="txtup_images_IsUtama" value="0" />';
					str = str + '	</div>';
					
					
					$("#uploadedimages").append( str );	
					/*
					str = str + '<div class="ups'+ strisu +'" id="ups'+ strisu +'_'+ Rand +'">';
					str = str + '	<image class="up_imgs'+ strisu +'" src="'+ res +'" '+ strstyles +' />';
					
					if( ID == 1 )
					{
						str = str + '	<a href="#" class="ups_delimg'+ strisu +'" onclick="DeletePhoto('+ i +', \''+ Rand +'\', 0, \''+ ID +'\')">';
						str = str + '		<i class="fa fa-times fa-3x" style="color: red;" ></i>';
						str = str +	'	</a>';
					}
					//str = str + '<image class="up_images" src="'+ res +'" width="200px" height="200px" />';
					//str = str + '<input type="hidden" class="txtup_images_name" value="'+ e.target.result +'" />';
				
					if( filetype == 'image/jpg' || filetype == 'image/jpeg' ) 
					{
						res = res.split(',');
						res = res[1]; // ngambil yang tipe string nya saja.
					}

					str = str + '	<input type="hidden" class="txtup_images_isfromdb'+ strisu +'" value="0" />';
					str = str + '	<input type="hidden" class="txtup_images_name'+ strisu +'" value="'+ res +'" />';
					str = str + '	<input type="hidden" class="txtup_images_filetype'+ strisu +'" value="'+ filetype +'" />';
						
					str = str + '	<input type="hidden" class="txtup_images_sku_photo_id'+ strisu +'" value="" />';
					str = str + '	<input type="hidden" class="txtup_images_sku_photo_detail_id'+ strisu +'" value="" />';
					str = str +	'</div>';
				
					if( ID == 1 )		{	$("#uploadedimages").append( str );	}
					else if( ID == 0 )	{	$("#uploadedimages"+ strisu ).html( str );	}
					
					$("#txtupdatephoto_UploadPhoto"+ strisu ).val('');
					*/
				}
				
				reader.readAsDataURL(input.files[0]); // convert to base64 string
			}
		}	
		else
		{
			var msg = 'Format yang diperbolehkan hanya berupa gambar.';
			var msgtype = 'error';
				
			//if (!window.__cfRLUnblockHandlers) return false;
			new PNotify
			({
				title: 'Error',
				text: msg,
				type: msgtype,
				styling: 'bootstrap3',
				delay: 3000,
				stack: stack_center
			});
		}
	}
	
	function SetFotoUtama(IsNew, Rand )
	{
		FotoUtama_RandID = Rand;
		
		$(".btnubahphoto").html('');
		$(".btnubahphoto").html('Set Foto Utama'); 
		$(".btnubahphoto").prop('disabled', false );
		$(".btnubahphoto").removeAttr('style');
		
		$("#btnubahphoto_"+ Rand ).html('');
		$("#btnubahphoto_"+ Rand ).html('<i class="fa fa-check"></i> <strong>Foto Utama</strong>');
		$("#btnubahphoto_"+ Rand ).prop('disabled', true );
		$("#btnubahphoto_"+ Rand ).attr('style',"color: black;");
		
		$(".txtup_images_IsUtama").val( 0 );
		$("#txtup_images_IsUtama_"+ Rand ).val( 1 );
	}

	function HapusFoto(IsNew, Rand )
	{
		if( Rand == FotoUtama_RandID )
		{
			var msg = 'Tidak bisa menghapus foto utama.';
			var msgtype = 'error';
			
			new PNotify
			({
				title: 'Error',
				text: msg,
				type: msgtype,
				styling: 'bootstrap3',
				delay: 3000,
				stack: stack_center
			});
			
			return false
		}
		
		$("#divphotoitem_"+ Rand ).remove();
	}

	$("#btnuploadphotoback").click
	(
		function()
		{
			ResetPhoto();
			$("#previewuploadphoto").modal('hide');
		}
	);
		
	$("#txtupdatephoto_UploadPhoto").change(function() 
	{
		readURL(this, 1);
	});

	$("#txtupdatephoto_UploadPhoto_Utama").change(function() 
	{
		readURL(this, 0);
	});
	
	$("#btnsaveuploadphoto").click
	(
		function()
		{
			//var arrPhotoUtama = [];
			//var arrPhotoDetail = [];
			
			var arrPhoto 		= [];
			
			var sku_induk_id 	= $("#hdupdatephoto_sku_induk_id").val();
			var sku_induk_nama 	= $("#hdupdatephoto_sku_induk_nama").val();
			
			var lphoto = $(".txtup_images_name").length;
	
			var IsAdaUtama = 0;
			
			for( i=0 ; i<lphoto ; i++ )
			{
				var Rand = $(".txtup_images_RandID").eq(i).val();
				
				if( Rand == FotoUtama_RandID )
				{
					IsAdaUtama = 1;
				}
			}
			
			if( IsAdaUtama == 0 )
			{
				var msg = 'Tidak ada foto yang diset menjadi Foto Utama.';
				var msgtype = 'error';
				
				//if (!window.__cfRLUnblockHandlers) return false;
				new PNotify
				({
					title: 'Error',
					text: msg,
					type: msgtype,
					styling: 'bootstrap3',
					delay: 3000,
					stack: stack_center
				});
				
				return false;
			}
			
			var Idx = 1;
			
			for( i=0 ; i<lphoto ; i++ )
			{
				var Rand 		= $(".txtup_images_RandID").eq(i).val();
				
				var filetype 	= $(".txtup_images_filetype").eq(i).val();
				var photo 		= $(".txtup_images_name").eq(i).val();
				var IsFromDB 	= $(".txtup_images_isfromdb").eq(i).val();
				var IsUtama 	= $(".txtup_images_IsUtama").eq(i).val();
				
				if( IsUtama == 1 )
				{
					arrPhoto.push( { IsFromDB: IsFromDB, FileType : filetype, PhotoString : photo, Urutan : 0 } );
				}
				else if( IsUtama == 0 )
				{
					arrPhoto.push( { IsFromDB: IsFromDB, FileType : filetype, PhotoString : photo, Urutan : Idx } );
					
					Idx++;
				}
			}
			
			arrPhoto.sort( compare );

			$.ajax(
			{
				type: 'POST',
				url: "<?= base_url('SKU_Photo/UploadPhoto') ?>",
				data: {
						sku_photo_id : SP_sku_photo_id,
						sku_induk_id : sku_induk_id ,
						sku_induk_nama : sku_induk_nama,
						
						IsAdaUtama : IsAdaUtama,
						arrPhoto : arrPhoto//,
						
						//arrPhotoUtama : arrPhotoUtama,
						//arrPhotoDetail : arrPhotoDetail
						},
				success: function( response )
				{
					$("#btnsaveuploadphoto").attr('disabled', false );
					
					if( response == 1 ) 
					{	
						var msg = 'Foto berhasil ditambahkan.';
						var msgtype = 'success';
						
						//if (!window.__cfRLUnblockHandlers) return false;
						new PNotify
						({
							title: 'Info',
							text: msg,
							type: msgtype,
							styling: 'bootstrap3',
							delay: 3000,
							stack: stack_center
						});
						
						ResetPhoto();
						
						SP_sku_photo_id = '';
						
						GetSKUIndukMenu();
						
						$("#MainMenu").removeAttr('style');
						$("#anylist_letter").removeAttr('style');
						$("#Foto").attr('style','display: none;');
					}
					else
					{	
						var msg = 'Foto gagal ditambahkan.';
						var msgtype = 'error';
						
						new PNotify
						({
							title: 'Error',
							text: msg,
							type: msgtype,
							styling: 'bootstrap3',
							delay: 3000,
							stack: stack_center
						});
					}
				}
			});
		}
	);
	
	$("#btnphotoback").click
	(
		function()
		{
			$("#previewphotokembalikelistskuinduk").modal('show');
		}
	);
	
	$("#btnyesphotoback").click
	(
		function()
		{
			ResetPhoto();
			
			$("#MainMenu").removeAttr('style');
			$("#anylist_letter").removeAttr('style');
			$("#Foto").attr('style','display: none;');
		}
	);
	
	function ResetPhoto()
	{
		$("#uploadedimages_Utama").html('');
		$("#uploadedimages").html('');
	}
</script>