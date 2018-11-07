<?php
	$gid = false;
	if(isset($_GET['gid']))
	{
		$gid=base64_decode($_GET['gid']);
		echo("gid is :'$gid'");
	}
	if(isset($_GET['gname']))
	{
		$gname=base64_decode($_GET['gname']);
	}

	$uri = $me.'?b=artnew&gid='.base64_encode($gid);
	
	if(isset( $_POST[ 'htmlsrc' ] ) )
	{
		$Aname = $_POST['aname'];
		$Adesc=$_POST['htmlsrc'];
		$Aid = $hlp->getunqid( $Aname );
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
					if(($m=='image/jpeg')||($m=='image/gif')||($m=='image/png')||($m=='image/jpg'))
					{
						$iext = explode('/',$m);
						$unqid = $hlp->getunqid( $Aname );
						$imgn = $unqid.".".$iext[1];
						$currentImagePath = "data/$imgn";
						
						$ThumbId='';
								
						if( move_uploaded_file( $f['tmp_name'],$currentImagePath ))
						{
							$size=filesize( $currentImagePath );
							$q="insert into artworks( Aid , gid , Aname , Adate , Adesc ,  ThumbId  , AimgSize , AimgWidth , AimgHeight , AimgMime ) values ( '$imgn', '$gid' , '$Aname' , now() , '$Adesc' , '$ThumbId' , '$size' , ".$s[0].",".$s[1].", '$m' );";
							echo("Query is $q");
							if( $hlp->_db->db_query( $q ) )
							{
								
								$thumbId = $hlp->getunqid($d).".".$iext[1];
								$thumbPath = "data/$thumbId";
								
								$cmd = "convert $currentImagePath -geometry 150x $thumbPath";
								exec( $cmd, $op, $ret );
									
								if( intval( $ret ) === 0 && file_exists( $thumbPath ) )
								{								
									$hlp->_db->db_query( "UPDATE artworks set ThumbId = '$thumbId' where Aid= '$imgn';" );
									$hlp->echo_ok( "Thumbnail is saved" );
								}
								else
								   $hlp->echo_err( "Thumbnail saving failed" );
								$hlp->echo_ok( "Saved successfully" );								
							}	
							else
								$hlp->echo_err( "Saving Failed" );
						}					
					}
					else
					{
						$hlp->echo_err("Saving Failed");
					}
						
				}
			}
			else
			{
				echo_err("Saving failed");
			}
		}
	}
	
	echo('<form name=frmWrt method="post" action="'.$uri.'" enctype="multipart/form-data">
		<div style="padding-top:10px" >
			<div style="width:150px;float:left;padding-left:10px;" >Name : </div>
			<div><input type=text name=aname id=aname value="" style="width:300px;" ></div>
		</div>
		
		<div style="padding-top:10px" >
			<div style="width:150px;float:left;padding-left:10px;" >Image : </div><div><input type=file name=img style="width:300px;"></div></div>
			<div style="padding-top:10px;" ><textarea id="htmlsrc" name="htmlsrc" rows="20" cols="80" style="width: 95%"></textarea></div> 
		</form>');
		
	
	echo( '<body>' );
	
	echo( $szo );
	
	
	echo( '<div class=gencon style="padding-top:3px;">' );
	
	
	$toolbar_type = "BasicToolbar";
	require("ckeditor/init.php");
	$CKEditor = new CKEditor();
	$CKEditor->returnOutput = true;
	
	echo($CKEditor->replace("htmlsrc",array("toolbar"=>$toolbar_type)));
?>

</div>
</body>
