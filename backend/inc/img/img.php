<?php
	$uri = "$me?b=showimg&s=1";
	if( isset( $_GET[ 's' ] ) )
	{
		if( $_GET[ 's' ] == 1 )
		{
			foreach($_FILES as $f)
			{
				if( !$f[ 'name' ] == '' )
				{
					if( $f[ 'error' ] == 0 )
					{
						$iname = $f[ 'name' ];
						$s = @getimagesize( $f[ 'tmp_name' ] );
						if( isset( $s[ 'mime' ] ) )
						{
							$mime = $s[ 'mime' ];
							$highres = true;
							if(($mime=='image/jpeg')||($mime=='image/gif')||($mime=='image/png')||($mime=='image/jpg')||($mime=='image/tiff')||($mime=='image/tif') )
							{
								if( $highres )
								{
									$iext = explode('/',$mime);	
									$imgnamefull = $f[ 'name' ];
									$imgname_arr = explode( '.',$imgnamefull );
									$imgname = $imgname_arr[0];
									$imgDir = "data/$imgname" ;
									if( is_dir( $imgDir ) ) // if directory is already present with product code.. remove old images
									{
										if( file_exists( $imgDir."/original.jpeg" ) ) { @unlink( $imgDir."/original.jpeg" ); } // remove old images of product code
										if( file_exists( $imgDir."/thumb.png" ) ) { @unlink( $imgDir."/thumb.png" ); }
										if( file_exists( $imgDir."/thumbBig.png" ) ) { @unlink( $imgDir."/thumbBig.png" ); }
										rmdir( $imgDir );
									}
									
									$cmd1="mkdir $imgDir";
									exec( $cmd1, $outa, $ret );
									$tmpPath = "$imgDir/temp.".$iext[1];
									$currentImagePath = "$imgDir/original.jpeg";
									$continue = true;
									if( move_uploaded_file( $f['tmp_name'], $tmpPath ) )
									{
										$size = filesize( $tmpPath );
										$cmd = "convert '$tmpPath' '$currentImagePath' ";
										exec ( $cmd, $outa, $ret );
										
										if( intval( $ret ) === 0 )
										{
											@unlink( $tmpPath );
												
											$size = filesize( $currentImagePath );
											$mime = "image/jpeg";
											$iext[1] = "jpg";
										}
										else
										{
											@unlink( $tmpPath );
											rmdir( "$imgDir" );// rmdir as command if failed
											$continue = false;
										}
										if( $continue )
										{
											$q = "update products set pimgName = '$imgname' where pcode='$imgname';";
											
											if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
											{
												$hlp->echo_ok( "Product has been stored." );	
												$thumbPath = "$imgDir/thumb.png";
												$thumbBigPath = "$imgDir/thumbBig.png";
												$cmd = "convert $currentImagePath -geometry 200x $thumbPath";
												exec( $cmd, $op, $ret );
												if( intval( $ret ) === 0 && file_exists( $thumbPath ) )
												{
													$s = @getimagesize( $thumbPath ); // image size
													$ht = $s[ 1 ];
													if($ht > 200)
													{
														$new_ht=$ht-200;
														$cmd = "convert ${thumbPath} -gravity South -chop x${new_ht}+0+0 ${thumbPath}";
													}
													else
													{
														$cmd = "convert $currentImagePath -resize 200x -background transparent -compose Copy -gravity center -extent 200x200 $thumbPath";
													}
													exec( $cmd, $op, $ret ); //associated thumb  command
													
													if($ret === 0)
													{
														$cmd = "convert $currentImagePath -geometry 300x $thumbBigPath";
														exec( $cmd, $op, $ret );
														
														if( $ret === 0 && file_exists( $thumbBigPath ))
														{
															$s = @getimagesize( $thumbBigPath );
															$ht = $s[ 1 ];
															if($ht > 300)
															{ 
																$new_ht=$ht-300;
																$cmd = "convert ${thumbBigPath} -gravity South -chop x${new_ht}+0+0 ${thumbBigPath}";
															
															}
															else
															{
																$cmd = "convert ${currentImagePath} -resize 300x -background transparent -compose Copy -gravity center -extent 300x300 ${thumbBigPath}";
															}
															exec( $cmd, $op, $ret );
															if( intval( $ret ) !== 0 )
															{
																@unlink( $thumbPath );
																@unlink( $thumbBigPath );
																$hlp->echo_err("unable to create thumbnail2.");
															}
														}
													}
													else	
													{
														@unlink( $thumbPath );
														@unlink( $thumbBigPath );
														$hlp->echo_err("unable to create thumbnail2.");
													}
												}
												else
												{
													@unlink( $thumbPath );
													$hlp->echo_err( "Unable to create Thumbnail1." );
												}
											}
											else
												$hlp->echo_err( "Sorry, unable to add record. contact support." );
										}
									}
									else
										$hlp->echo_err("Sorry, unable to upload image.");
								}
							}
							else
								$hlp->echo_err("Sorry, uploaded file type is not supported.");
						}
						else
							$hlp->echo_err("Please upload Image file.");
					}
					else
						$hlp->echo_err("Saving failed");
				}
				else
					$hlp->echo_err("Please upload the neccessary product image");	
			}
			
		}
	}
	
	echo( "
		<form action='$uri' method=post enctype='multipart/form-data'> 
			<div class=gencon style='padding-top:5px'>
				<a id=acur class=comp href=# onclick=\"CUtil.addFileUp('comp','Image:','prod_imgs','x','img');return(false);\">
					<div class=gensideblock>Add Images</div>
					<div class=gensideblock>
						<img border=0 src=images/ic/iaddimg.png />
					</div>
				</a>
				<span style='color:#555;' id=txt>(*.jpeg,*.jpg,*.bmp,*.png.)</span>
			</div>
			<div id=prod_imgs class=gencon style='padding-bottom:5px;width:500px'></div>
			<div class='gencon tviewcell' style='padding-top:5px;width:500px'>
				<center>
					<button id=acur class=roundbutton type=submit>Upload Images</button>
				</center>
			</div>
		</form>" );
	
?>