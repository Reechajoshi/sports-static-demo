<?php

if(isset($_GET['t']))
{
	$t = $_GET['t']; 

	$pcatgry = false;
	$flag = 1;
	$highres = false;
	$uri = $me.'?b=prodnew&t='.$t;
	if ( isset( $_POST[ 'pcat' ] ) || ( isset( $_POST[ 'pbrand' ] )) || (isset( $_POST[ 'pname' ] )) || (isset( $_POST[ 'pdesc' ] )) || ( isset( $_POST[ 'pprice' ] )) )
	{
		$pcat = $_POST['pcat'] ;
		$pitem = trim( $_POST['pitem'] );
		$pbrand = trim( $_POST['pbrand'] );
		$pname = trim( $_POST['pname'] );
		$pdesc = trim( $_POST['pdesc'] );
		$pprice = trim( $_POST['pprice'] );
		$pimg = trim( $_POST['img'] );
		
		$fid = $hlp->getunqid( $pname );
		$d = date('Y-m-d G:i:s');
		
		
		if( ( strlen($pname) === 0 )|| ( strlen($pitem) === 0 )  || ( strlen($pbrand) === 0 ) || !is_numeric( $pprice))
		$flag = 0 ;
		
		if( $flag === 1)
		{
			if(!$_FILES['img']['name']=="")
			{
				foreach($_FILES as $f)
				{
					if($f['error']==0)
					{
						$iname = $f['name'];
						$s = @getimagesize($f['tmp_name']);
						if(isset($s['mime']))
						{
							$m = $s['mime'];
							//$highres = (($s[0] > 600) && ($s[1] > 600)) ? true : false;
							$highres = true; // (($s[0] > 600) && ($s[1] > 600)) ? true : false;
							
							if(($m=='image/jpeg')||($m=='image/gif')||($m=='image/png')||($m=='image/jpg')||($m=='image/tiff')||($m=='image/tif') )
							{// after this line chek the width and height
								 if( $highres )
								{		
									$iext = explode('/',$m);	
									$unqid = $hlp->getunqid( $pname );
									$imgname = $hlp->getunqid( $unqid );
									
									$imgDir = "data/$imgname" ;
									
									$cmd1="mkdir $imgDir";
									
									exec ( $cmd1, $outa, $ret );
									
									$tmpPath = "$imgDir/temp.".$iext[1];
									$currentImagePath = "$imgDir/original.jpeg";
									
									$continue = true;
									if( move_uploaded_file( $f['tmp_name'], $tmpPath ))
									{
										$size = filesize( $tmpPath );
										$cmd = "convert '$tmpPath' '$currentImagePath' ";
										
										exec ( $cmd, $outa, $ret );
										
										if( intval( $ret ) === 0 )
										{
											@unlink( $tmpPath );
												
											$size = filesize( $currentImagePath );
											$m = "image/jpeg";
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
											$q = "insert into products ( pcode , pname , pbrand , pcatgry , pdesc , pprice  , pitem , pimgName ,  pdate , pproduct ) values ( '$unqid' , '$pname' , '$pbrand' , '$pcat' , '$pdesc' ,  '$pprice' , '$pitem' ,'$imgname' , now() , '$t') ;";
											
											//echo("Query <br> $q");
											
											if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
											{
												$hlp->echo_ok( "Product has been stored." );
												
												$thumbPath = "$imgDir/thumb.png";
												$thumbBigPath = "$imgDir/thumbBig.png";
												
												$cmd = "convert $currentImagePath -geometry 200x $thumbPath";
						
												exec( $cmd, $op, $ret );
												if( intval( $ret ) === 0 && file_exists( $thumbPath ) )
												{	
													$s = @getimagesize( $thumbPath );
													$ht = $s[ 1 ];
													if($ht > 200)
													{
														$new_ht=$ht-200;
														$cmd = "convert ${thumbPath} -gravity South -chop x${new_ht}+0+0 ${thumbPath}";
													}
													else
													{
														$cmd = "convert ${currentImagePath}	-resize 200x -background transparent -compose Copy -gravity center -extent 200x200 ${thumbPath}";
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
																$cmd = "convert ${currentImagePath}	-resize 300x -background transparent -compose Copy -gravity center -extent 300x300 ${thumbBigPath}";	
																
															//	echo("cmd not greter $cmd ");
															}
															
															exec( $cmd, $op, $ret ); //associated thumbBig command
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
												}//return 0
												else
												{
													@unlink( $thumbPath );
													$hlp->echo_err( "Unable to create Thumbnail1." );
												}	
																		
											}	
											else
												$hlp->echo_err( "Sorry, unable to add record. contact support." );
										}
										else
											$hlp->echo_err( "Sorry, unable to process request. contact support." );	
									}					
									else
										$hlp->echo_err("Sorry, unable to upload image.");
								 }//highres
								 else
									$hlp->echo_err("Please upload high resolution image,more than 600 x 600.");
							}//before this line hv else for width and height
							else
								$hlp->echo_err("Sorry, uploaded file type is not supported.");
						}
						else
							$hlp->echo_err("Sorry, uploaded file type is not supported.");
					}
					else
						$hlp->echo_err("Saving failed");
				}
			}
			else
				$hlp->echo_err("Please upload the neccessary product image");	
				
		}
		else
			$hlp->echo_err("Please enter appropriate values in all fields");		
	}
	
	$pcatgry = false;
	
	$prod = $hlp->getproduct_cat( $t, true ); //original by jamie
	// print_r( $prod );
	//$prod = $t;
	
	echo('<form name=frmWrt method="post" action="'.$uri.'" enctype="multipart/form-data">
	
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Category :</div>
			<div style="width:500px;">'.$hlp->getcatvalues($prod ,$pcatgry).'</div>
		</div>
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Item : </div>
			<div><input type=text maxlength="200" name=pitem id=pitem value="" style="width:200px;" ></div>
		</div>
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Brand : </div>
			<div><input type=text maxlength="200" name=pbrand id=pbrand value="" style="width:200px;" ></div>
		</div>
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Name : </div>
			<div><input type=text maxlength="200" name=pname id=pname value="" style="width:200px;" ></div>
		</div>
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Description : </div>
			<div style="padding-top:10px;" ><textarea name="pdesc" rows="4" style="width:200px;"></textarea></div> 
		</div>
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Price : </div>
			<div><input type=text maxlength="200" name=pprice id=pprice value="" style="width:200px;" ></div>
		</div>
		<div style="padding-top:10px" >
			<div style="width:80px;float:left;padding-left:10px;" >Image : </div><div><input type=file name=img style="width:300px;"></div>
		</div>
		
		</div>
			
		<div style="width:320px;text-align:center;padding-top:10px;" ><button type=submit class=roundbutton style="width:100px;" >Save</button></div>	
		</form>');
		
	
	echo( '<body>' );
	
	
}//T isset
else
$hlp->echo_err("Problem has occurred");
	
	
	
?>

</div>
</body>
