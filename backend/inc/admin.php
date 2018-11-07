<?php
	
	$uri = $me."?b=showadm&ac=save";
	$replace_uri = $me."?b=showadm&ac=rep";
	
	if(isset($_GET['ac'] ))
	{
		if($_GET['ac'] == 'save')
		{
			$row = 1;
			if( isset( $_FILES[ "upfile" ][ "tmp_name" ] ) )
			{
				$file = $_FILES["upfile"]["tmp_name"];
				if( strlen( $file ) > 0 )
				{
					$_skipFirstRow = ( isset( $_POST[ 'firstcol' ] ) && $_POST[ 'firstcol' ] == 'on' );
					
					if( ( $handle = fopen( $file, "r" ) ) !== FALSE ) 
					{
						$_err_count = 0;
						$_ok_count = 0;
						$_skip_count = 0;
						
						while ( ( $_line = fgetcsv( $handle) ) !== FALSE ) 
						{
							if( $_skipFirstRow !== false ) //skip first row....
							{
								$_skipFirstRow = false;
								continue;
							}	
							
							if( $_line !== false && is_array ( $_line ) )
							{
								$_code = addslashes( trim( $_line[ 0 ] ) );
								$_group = addslashes( $_line[ 1 ] );
								$_category = addslashes( trim( $_line[ 2 ] ) );
								$_item = addslashes( trim( $_line[ 3 ] ) );
								$_brand = addslashes( trim( $_line[ 4 ] ) );
								$_name = addslashes( trim( $_line[ 5 ] ) );
								$_description = addslashes( trim( $_line[ 6 ] ) );
								$_wsp = addslashes( trim( $_line[ 7 ] ) );
								
								if( $_code !== false && strlen( $_code ) > 0 )
								{
									if( $_group !== false && strlen( $_group ) > 0 )
									{
										if( $_category !== false && strlen( $_category ) > 0 )
										{
											if( $_item !== false && strlen( $_item ) > 0 )
											{
												$_q = "insert into products ( pcode, pproduct, pcatgry, pitem, pbrand , pname, pdesc, pprice , pdate, pimgName  ) values( '$_code', '$_group', '$_category', '$_item', '$_brand', '$_name', '$_description', '$_wsp', now(), '' ) on duplicate key update pcode = '$_code', pproduct = '$_group', pcatgry = '$_category', pitem = '$_item', pbrand = '$_brand', pname = '$_name', pdesc = '$_description', pprice = '$_wsp' , pdate = now() ;";
												
												if( $hlp->_db->db_query( $_q ) !== false )
													$_ok_count++;
												else
													$_err_count++;
											}
											else
												$_skip_count++;
										}
										else
											$_skip_count++;
									}
									else
										$_skip_count++;
								}
								else
									$_skip_count++;
							}
							else
								$_skip_count++;
						}
						fclose( $handle );
						
						$hlp->echo_ok( "Uploading finished, $_ok_count Row(s) has been processed.".( ( $_err_count > 0 )?( " unable to Process $_err_count Row(s)." ):( "" ) ).( ( $_skip_count > 0 )?( " $_skip_count Row(s) skipped." ):( "" ) ) );
					}
					else
						$hlp->echo_err( "Sorry, unable to process file." );
				}	
				else
					$hlp->echo_err( "Please specify file." );
			}
			else
				$hlp->echo_err( "Please specify file." );
		}// ac == save
		else if( $_GET[ 'ac' ] == 'rep' )
		{
			$insert_queries = array();
			$row = 1;
			if( isset( $_FILES[ "repfile" ][ "tmp_name" ] ) )
			{
				$file = $_FILES["repfile"]["tmp_name"];
				if( strlen( $file ) > 0 )
				{
					$_skipFirstRow = ( isset( $_POST[ 'rep_firstcol' ] ) && $_POST[ 'rep_firstcol' ] == 'on' );
					
					if( ( $handle = fopen( $file, "r" ) ) !== FALSE ) 
					{
						$_err_count = 0;
						$_ok_count = 0;
						$_skip_count = 0;
						$_del_query = "delete from products;";
						if( $hlp->_db->db_query( $_del_query ) !== false )
						{
							while ( ( $_line = fgetcsv( $handle) ) !== FALSE ) 
							{
								if( $_skipFirstRow !== false ) //skip first row....
								{
									$_skipFirstRow = false;
									continue;
								}	
								
								if( $_line !== false && is_array ( $_line ) )
								{
									$_code = addslashes( trim( $_line[ 0 ] ) );
									$_group = addslashes( trim( $_line[ 1 ] ) );
									$_category = addslashes( trim( $_line[ 2 ] ) );
									$_item = addslashes( trim( $_line[ 3 ] ) );
									$_brand = addslashes( trim( $_line[ 4 ] ) );
									$_name = addslashes( trim( $_line[ 5 ] ) );
									$_description = addslashes( trim( $_line[ 6 ] ) );
									$_wsp = addslashes( trim( $_line[ 7 ] ) );
									
									if( $_code !== false && strlen( $_code ) > 0 )
									{
										if( $_group !== false && strlen( $_group ) > 0 )
										{
											if( $_category !== false && strlen( $_category ) > 0 )
											{
												if( $_item !== false && strlen( $_item ) > 0 )
												{
													$_q = "insert into products ( pcode, pproduct, pcatgry, pitem, pbrand , pname, pdesc, pprice , pdate, pimgName  ) values( '$_code', '$_group', '$_category', '$_item', '$_brand', '$_name', '$_description', '$_wsp', now(), '' ) on duplicate key update pcode = '$_code', pproduct = '$_group', pcatgry = '$_category', pitem = '$_item', pbrand = '$_brand', pname = '$_name', pdesc = '$_description', pprice = '$_wsp' , pdate = now() ;";
													
													if( $hlp->_db->db_query( $_q ) !== false )
														$_ok_count++;
													else
														$_err_count++;
												}
												else
													$_skip_count++;
											}
											else
												$_skip_count++;
										}
										else
											$_skip_count++;
									}
									else
										$_skip_count++;
								}
								else
									$_skip_count++;
							}
						}
						else
							$hlp->echoerr( "Error occured while uploading Products." );
						fclose( $handle );
						
						$hlp->echo_ok( "Uploading finished, $_ok_count Row(s) has been processed.".( ( $_err_count > 0 )?( " unable to Process $_err_count Row(s)." ):( "" ) ).( ( $_skip_count > 0 )?( " $_skip_count Row(s) skipped." ):( "" ) ) );
					}
					else
						$hlp->echo_err( "Sorry, unable to process file." );
				}	
				else
					$hlp->echo_err( "Please specify file." );
			}
			else
				$hlp->echo_err( "Please specify file." );
		}
	}
	
	echo("<div style='padding-left:20px;padding-top:20px;'>");	
	echo("<button class=roundbutton style='width:130px;' onClick='top.location.replace( \"ad.php?b=showadm&c=dwn\");' >Download CSV File</button>");
	
	echo("<br/><br/>");
	echo("<form name=frmWrt method='post' action=$uri enctype='multipart/form-data'>
			<div>
				<span style='font-weight:bold;'>Merge Products: </span><input type=file name=upfile style='width:300px;'>
				
				<button type=submit class=roundbutton style='width:130px;' >Upload CSV File</button>
			</div>
			<div>
				<input type=checkbox name=firstcol id=firstcol checked /> <label for=firstcol style='position:relative;top: -2px;' > First Name includes column name.</label>
			</div>
		</form>" );
	echo( "<form name=frmWrt method='post' action=$replace_uri enctype='multipart/form-data'>
			<div>
				<span style='font-weight:bold;'>Replace Products: </span><input type=file name=repfile style='width:300px;'>
				
				<button type=submit class=roundbutton style='width:130px;' >Upload CSV File</button>
			</div>
			<div>
				<input type=checkbox name=rep_firstcol id=rep_firstcol checked /> <label for=rep_firstcol style='position:relative;top: -2px;' > First Name includes column name.</label>
			</div>
		</form>
	" );
	echo( "</div>" );
?>