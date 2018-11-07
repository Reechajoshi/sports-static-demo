<?php

if(isset($_GET['pcode']))
{
	$pcode = base64_decode( $_GET[ 'pcode' ] );	
	
}

$t=$_GET['t'];
$frm_submit = $me."?b=prodedt&t=".$t."&pcode=".base64_encode( $pcode )."&ac=save";

if( isset( $_GET['ac']))
{	
	if ( $_GET['ac'] == "save" )
	{
		if ( isset( $_POST[ 'pcat' ] ) || ( isset( $_POST[ 'pbrand' ] )) || (isset( $_POST[ 'pname' ] )) || (isset( $_POST[ 'pdesc' ] )) || ( isset( $_POST[ 'pprice' ] )) )
		{
			$pcat = $_POST['pcat'] ;
			$pitem = trim( $_POST['pitem'] );
			$pbrand = trim( $_POST['pbrand'] );
			$pname = trim( $_POST['pname'] );
			$pdesc = trim( $_POST['pdesc'] );
			$pprice = trim( $_POST['pprice'] );
			$pimg = trim( $_POST['img'] );

			if( ( strlen($pname) !== 0 ) && ( strlen($pitem) !== 0 )  && ( strlen($pbrand) !== 0 ) && is_numeric( $pprice) )
			{
				if(!$_FILES['img']['name']=="")  //file input not blank 
				{
					$OldimgName = $_POST['OldimgName'];
					$imgDir = "data/$OldimgName";
					
					$d = date('Y-m-d G:i:s');
					foreach($_FILES as $f)
					{
						if($f['error']==0)
						{
							$iname = $f['name'];
							$s = @getimagesize($f['tmp_name']);
							if(isset($s['mime']))
							{
								$m = $s['mime'];
								
								// NOTE: Permit smaller images ; $highres = (($s[0] > 600) && ($s[1] > 600)) ? true : false;
								$highres = true; 
								if(($m=='image/jpeg')||($m=='image/gif')||($m=='image/png')||($m=='image/jpg')||($m=='image/tiff')||($m=='image/tif') )
								{//highres will be after this
									 if( $highres )
									 {
										$continue = true;	
										$iext = explode('/',$m);	
										$unqid = $hlp->getunqid( $pname );
										$imgname = $hlp->getunqid( $unqid );
										
										if(!is_Dir($imgDir))
										{
											exec("mkdir $imgDir",$opt,$ret); //mkdir
											
											if($ret !== 0)
											{
												$continue = false;	
											}
												
										}
										
										$tmpPath = "$imgDir/temp.".$iext[1];
										$currentImagePath = "$imgDir/original.jpeg";
										$thumbPath = "$imgDir/thumb.png";
										$thumbBigPath = "$imgDir/thumbBig.png";
										
										
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
												rmdir( "$imgDir" );// rmdir as command is failed
												$continue = false;
											}
											
											if( $continue )
											{
												$q = "update products set pname='$pname' , pbrand='$pbrand' , pcatgry='$pcat' , pdesc= '$pdesc' , pprice='$pprice' , pimgName = '$OldimgName' , pitem = '$pitem', pdate = now() where pcode='$pcode' ;";
												
												$ht = $s[1] > $s[0];
												if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
												{
													$hlp->echo_ok( "Product has been updated." );
																								
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
										}//move uploaded files					
										else
											$hlp->echo_err("Sorry, unable to upload image.");
									}
									else
										$hlp->echo_err("Please upload high resolution image,more than 600 x 600.");
								}//highres will end before this
								else
									$hlp->echo_err("Sorry, uploaded file type is not supported.");
							}
							else
								$hlp->echo_err("Sorry, uploaded file type is not supported.");
						}
						else
							$hlp->echo_err("Saving failed");
					}	//foreach ends
				}// Ends File Input not blank
				else
				{
					$q = "UPDATE products set pname='$pname' , pbrand = '$pbrand' , pcatgry = '$pcat' , pdesc = '$pdesc' , pprice = '$pprice' , pitem = '$pitem' , pdate = now() where pcode='$pcode' ;";
					
					$res = $hlp->_db->db_query( $q );
					if($res)
					$hlp->echo_ok( "Product has been upated" );
					else
					$hlp->echo_err("Product updation has been failed");
				}
				
			}
				else
					$hlp->echo_err("Please enter appropriate values in all the fields");
				
				
			} 	// end of if isset ac
		}		//end of ac=save
	}	


	
	
		$q = "select pcode , pname , pbrand , pcatgry , pdesc , pitem , pprice , pimgName from products where pcode='$pcode';"; 
		//	 pid  pbrand pcatgry pdesc pitem pprice pthumbId pimgName pbigthumbId pbigthumbId
		if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
		{
			$row = $hlp->_db->db_get( $res );	
			
			$pcode = $row[ 'pcode' ]; 
			$pname = $row[ 'pname' ];
			$pbrand = $row[ 'pbrand' ];
			$pcatgry = $row[ 'pcatgry' ];
			$pdesc = $row[ 'pdesc' ];
			$pitem = $row[ 'pitem' ];
			$pprice = $row[ 'pprice' ];
			
			
			if( strlen($pname) == 0 )
			echo("pname is null");
			//echo('code:'.$pcode.'pprod-'.$pproduct.'pname-'.$pname.' pbrand-'.$pbrand.'-pcat'.$pcatgry.'-pdesc-'.$pdesc.'-pprice-'.$pprice.'-pitem'.$pitem);
			$OldimgName = $row[ 'pimgName' ];  
			
			if( strlen($OldimgName) == 0 )
			{
				$OldimgName = $hlp->getunqid( $pname );
			}
			
			$Oldimg = "$OldimgName";
			
			
			
			$thumbPath = "data/$OldimgName/thumb.png";
			
			$tdstyle = "class=rviewcell";
			
			$style = "style='padding-top:7px;padding-bottom:7px;padding-right:10px;width:150px;color:#333;text-align:right;'";
			
			$valstyle = "style='padding-left:10px;padding-top:10px;'";
			
			$prod = $hlp->getproduct_cat($t,true);
			
			
			echo('<form name=frmWrt method="post" action="'.$frm_submit.'" enctype="multipart/form-data">
				<input type="hidden" name=OldimgName value="'.$Oldimg.'" />
			
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Category : </div>
					<div style="width:500px;">'.$hlp->getcatvalues($prod ,$pcatgry).'</div>	
				</div>
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Item : </div>
					<div><input type=text maxlength="200" name=pitem value ="'.$pitem.'"  id=pitem style="width:200px;" ></div>
				</div>
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Brand : </div>
					<div><input type=text maxlength="200" name=pbrand id=pbrand value="'.$pbrand.'" style="width:200px;" ></div>
				</div>
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Name : </div>
					<div><input type=text maxlength="200" name=pname id=pname value="'.$pname.'" style="width:200px;" ></div>
				</div> <!-- pname -->
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Description : </div>
					<div style="padding-top:10px;" ><textarea name="pdesc" rows="4" style="width:200px;">'.$pdesc.'</textarea></div> 
				</div>
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Price : </div>
					<div><input type=text maxlength="200" name=pprice id=pprice value='.$pprice.' style="width:200px;" ></div>
				</div>
				
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Image : </div>
					<div><img src="'.$thumbPath.'" width=150 height=150 /></div>
				</div>
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >&#160;</div><div><input type=file name=img style="width:300px;"></div>
				</div>
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >&#160;</div>
					<div style="color:#333;padding-left:10px;;"> Upload a new image to replace the existing</div>
				</div>
			</div>
			
				
			<div style="width:320px;text-align:center;padding-top:10px;" ><button type=submit class=roundbutton style="width:100px;" >Save</button></div>	
			</form>');
		}
		//echo( '<body>' );
	

?>