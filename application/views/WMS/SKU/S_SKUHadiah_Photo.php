<script type="text/javascript">	
	var SP_PhotoDir = '';
	var SP_PhotoIdx = '';
	var SP_SKUInduk_ID = '';
	var SP_PhotoID = '';
	
	function UploadPhotoMenu( SKUInduk_ID, SKUInduk_Nama )
	{
		SP_SKUInduk_ID = SKUInduk_ID;
		
		$("#hdupdatephoto_SKUInduk_ID").val( SKUInduk_ID );
		$("#hdupdatephoto_SKUInduk_Nama").val( SKUInduk_Nama );
		
		$("#txtupdatephoto_up_SKUInduk_Nama").val( SKUInduk_Nama );
		
		$.ajax(
		{
			type: 'POST',
			url: "<?= base_url('Photo/GetUploadedPhotoByID') ?>",
			data: {
					SKUInduk_ID : SKUInduk_ID
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
		
		if( Photo.PhotoMenu.length > 0)
		{
			for( i=0 ; i< Photo.PhotoMenu.length ; i++ )
			{
				var PhotoKeterangan = Photo.PhotoMenu[i].PhotoKeterangan;
				var PhotoID 		= Photo.PhotoMenu[i].PhotoID;
				var Photo1ID 		= Photo.PhotoMenu[i].Photo1ID;
				var PhotoDir 		= Photo.PhotoMenu[i].PhotoDir;
				var Photo1_urut 	= Photo.PhotoMenu[i].Photo1_urut;
				
				var strisu = '';
				var ID = 1;
				if( Photo1_urut == 0 )		{	strisu = '_Utama';	ID = 0;	}
				var str = '';
				
				SP_PhotoID = PhotoID;
				str = str + '<div class="ups'+ strisu +'" id="ups'+ strisu +'_'+ i +'">';
				str = str + '	<image class="up_imgs'+ strisu +'" src="<?= base_url() ?>'+ PhotoDir +'?'+ new Date().getTime() +'" width="200px" height="200px" />';
				
				if( ID == 1 )
				{
					str = str + '	<a href="#" class="ups_delimg'+ strisu +'" onclick="DeletePhoto('+ i +', \''+ PhotoDir +'\', 1, \''+ ID +'\')">';
					str = str + '		<i class="fa fa-times fa-3x" style="color: red;" ></i>';
					str = str +	'	</a>';
				}
				str = str + '	<input type="hidden" class="txtup_images_isfromdb'+ strisu +'" value="1" />';
				str = str + '	<input type="hidden" class="txtup_images_name'+ strisu +'" value="'+ PhotoDir +'" />';
				str = str + '	<input type="hidden" class="txtup_images_filetype'+ strisu +'" value="" />';
				
				str = str + '	<input type="hidden" class="txtup_images_photoid'+ strisu +'" value="'+ PhotoID +'" />';
				str = str + '	<input type="hidden" class="txtup_images_photo1id'+ strisu +'" value="'+ Photo1ID +'" />';
				str = str +	'</div>';
				
				if( ID == 1 )		{	$("#uploadedimages").append( str );	}
				else if( ID == 0 )	{	$("#uploadedimages"+ strisu ).html( str );	}
			}
			
			$("#MainMenu").attr('style','display: none;');
			$("#Foto").removeAttr('style');
			
		}
		else
		{
			$("#MainMenu").attr('style','display: none;');
			$("#Foto").removeAttr('style');
			
		}
	}
	
	function readURL(input, ID) 
	{
		var strisu = '';
		if( ID == 0 )	{ strisu = '_Utama';	}
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
					
					var Rand = Math.random() * 1000000000000000000;
					
					str = str + '<div class="ups'+ strisu +'" id="ups'+ strisu +'_'+ Rand +'">';
					str = str + '	<image class="up_imgs'+ strisu +'" src="'+ res +'" width="200px" height="200px" />';
					
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
						
					str = str + '	<input type="hidden" class="txtup_images_photoid'+ strisu +'" value="" />';
					str = str + '	<input type="hidden" class="txtup_images_photo1id'+ strisu +'" value="" />';
					str = str +	'</div>';
				
					if( ID == 1 )		{	$("#uploadedimages").append( str );	}
					else if( ID == 0 )	{	$("#uploadedimages"+ strisu ).html( str );	}
					
					$("#txtupdatephoto_UploadPhoto"+ strisu ).val('');
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
			$("#btnsaveuploadphoto").attr('disabled', true );
			
			var arrPhotoUtama = [];
			var arrPhotoDetail = [];
			
			var SKUInduk_ID 	= $("#hdupdatephoto_SKUInduk_ID").val();
			var SKUInduk_Nama 	= $("#hdupdatephoto_SKUInduk_Nama").val();
			
			var lphotou = $(".txtup_images_name_Utama").length;
	
			var IsLoad = $(".txtup_images_isfromdb_Utama").eq(0).val();
			
			var IsAdaUtama = 1;
			
			if( IsLoad == 0 )
			{
				IsAdaUtama = 0;
				
				var photo = $(".txtup_images_name_Utama").eq(0).val();
				
				var filetype = $(".txtup_images_filetype_Utama").eq(0).val();
				
				arrPhotoUtama.push( { IsLoad: IsLoad, PhotoString : photo, FileType : filetype, Urutan : 0 } );
			}
			
			/*if( arrPhotoUtama == '' )
			{
				$("#btnsaveuploadphoto").attr('disabled', false );
						
				var msg = 'Foto Utama wajib diisi.';
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
			else
			{*/
				var lphoto = $(".txtup_images_name").length;
		
				var Idx = 1;
				for( i=0 ; i<=lphoto ; i++ ) 
				{
					var IsLoad = $(".txtup_images_isfromdb").eq(i).val();
					
					//if( IsLoad == 0 )
					//{
					var photo = $(".txtup_images_name").eq(i).val();
					
					var filetype = $(".txtup_images_filetype").eq(i).val();
					
					if( typeof photo !== 'undefined' )
					{
						arrPhotoDetail.push( { IsLoad: IsLoad, PhotoString : photo, FileType : filetype, Urutan : Idx } );
					
					
						Idx++;
					}
					//}
				}
				
				$.ajax(
				{
					type: 'POST',
					url: "<?= base_url('Photo/UploadPhoto') ?>",
					data: {
							PhotoID : SP_PhotoID,
							SKUInduk_ID : SKUInduk_ID ,
							SKUInduk_Nama : SKUInduk_Nama,
							
							IsAdaUtama : IsAdaUtama,
							arrPhotoUtama : arrPhotoUtama,
							arrPhotoDetail : arrPhotoDetail
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
							
							SP_PhotoID = '';
							
							GetSKUIndukMenu();
							
							$("#MainMenu").removeAttr('style');
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
			//}
		}
	);
	
	function DeletePhoto( Idx, PhotoDir, Mode, ID )
	{	
		var strisu = '';
		if( ID == 0 )	{ strisu = '_Utama';	}
	
		$("#hdID").val('');
		if( Mode == 1 )
		{
			SP_PhotoIdx = Idx;
			SP_PhotoDir = PhotoDir;
			
			$("#hdID").val( ID );
			$("#previewdeletephoto").modal('show');
		}
		else
		{
			if( ID == 0 )
			{
				$("#ups_Utama_"+ SP_PhotoIdx ).html('');
				$("#ups_Utama_"+ PhotoDir ).remove();
			}
			else if( ID == 1 )
			{
				$("#ups_"+ SP_PhotoIdx ).html('');
				$("#ups_"+ PhotoDir ).remove();
			}
		}
	}
	
	$("#btnyesdeletephoto").click
	(
		function()
		{
			var ID = $("#hdID").val();
			$.ajax(
			{
				type: 'POST',
				url: "<?= base_url('Photo/DeletePhoto') ?>",
				data: {
						SKUInduk_ID : SP_SKUInduk_ID ,
						PhotoDir : SP_PhotoDir
						
						},
				success: function( response )
				{
					if( response == 1 )
					{
						if( ID == 0 )
						{
							$("#ups_Utama_"+ SP_PhotoIdx ).html('');
							$("#ups_Utama_"+ PhotoDir ).remove();
						}
						else if( ID == 1 )
						{
							$("#ups_"+ SP_PhotoIdx ).html('');
							$("#ups_"+ PhotoDir ).remove();
						}
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
			$("#Foto").attr('style','display: none;');
		}
	);
	
	function ResetPhoto()
	{
		$("#uploadedimages_Utama").html('');
		$("#uploadedimages").html('');
	}
</script>