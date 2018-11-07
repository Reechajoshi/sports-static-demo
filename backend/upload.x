<?php
	
	require( "conf/vars.php" );
	require_once( 'helper/class.helper.php' );
	
	$me = "index.x";
	$hlp = new chlp();
	
	//set_time_limit(3600);

	//Set these amounts to whatever you need.
	//ini_set("post_max_size","8192M");
	//ini_set("upload_max_filesize","8192M");

	//Generally speaking, the memory_limit should be higher
	//than your post size.  So make sure that's right too.
	//ini_set("memory_limit","8200M");
	
	function uploadImage( $imagename )
	{
		GLOBAL $DATA_DESTINATION,$hlp;
		
		$currentImagePath = "Fileuploader/files/$imagename";
		
		$ext = false;
		$tmp = explode( '.',$imagename );
		
		if( sizeof( $tmp ) > 0 )
			$ext = $tmp[ sizeof( $tmp ) - 1 ];
			
		$d = date('Y-m-d G:i:s');
		$unqid = $hlp->getunqid($d).".$ext";
		$s = @getimagesize( $currentImagePath );
		
		if(isset($s['mime']))
		{
			$m=$s['mime'];

			if( ($m == 'image/jpeg' ) || ( $m == 'image/gif' ) || ( $m == 'image/png' ) || ( $m == 'image/jpg' ) || ( $m == 'image/bmp' ) )
			{
				$q = "insert into images( iid, idate, iname, isize, iwidth, iheight, imime ) values ( '$unqid', now(), '$imagename',".filesize( $currentImagePath )." ,".$s[0].",".$s[1].",'$m' );";
				
				$hlp->_db->db_begin();
				
				$res = $hlp->_db->db_query( $q );
				if( intval( $hlp->_db->db_affected( ) ) === 1 )
				{
					$newImagePath = $DATA_DESTINATION.$unqid;
					
					if( rename( $currentImagePath, $newImagePath ) )
					{
						$hlp->_db->db_done();
						return( true );
					}
					else
						$hlp->_db->db_undo();	
				}	
				else
					$hlp->_db->db_undo();
			}
			return( false );	
		}		
	
		@unlink( $currentImagePath );
		return( false );
	}
	
	require( "Fileuploader/upload.php" );
?>