<?php
	require( 'conf/vars.php' );
	require( 'helper/class.helper.php' );
	
	$hlp = new chlp();
	
	if( isset( $_GET['fl'] ) )
	{
		$ret_html = $hlp->getMainContentHTML( $_GET['fl'] );
		echo($ret_html);
	}
	
	
	if( isset( $_GET['ac'] ))
	{
		if( $_GET[ 'ac' ] == 'enq' )
		{
			if(isset($_POST['email']))
			{
				$ename = $_POST[ 'ename' ];
				$email = $_POST[ 'email' ];
				$econt = $_POST[ 'econt' ];
				$enote = $_POST[ 'enote' ];
				$eid = $hlp->getunqid( $enote );
			
				if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$q="insert into enquiry (eid,ename,econt,enote,email,eack,edate) values ('$eid','$ename','$econt','$enote','$email',false,now());";
					$res = $hlp->_db->db_query( $q);
					if( $res !== false )
						echo("Ok");
					else
						echo("Nok");	
				}	
				else
				echo("wrongemail");
			}
			else
			{
				echo("Nok");
			}
		}
	}
	
	/*
	
	*/
?>