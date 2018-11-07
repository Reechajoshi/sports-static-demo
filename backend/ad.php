<?php
	require( 'conf/vars.php' );
	require( 'helper/class.helper.php' );
	
	$me = $_SERVER[ "PHP_SELF" ];
	$hlp = new chlp();
	
	if( isset($_GET['c']) )
	{
		if($_GET['c'] == 'dwn')
		{
			$hlp->generateCSV();
		}
		if($_GET['c'] == 'up')
		{
			//$hlp->generateCSV();
		}
	}


?>