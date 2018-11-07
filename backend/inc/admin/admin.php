<?php

	
	if( isset($_GET['c']) )
	{
		if($_GET['c'] == 'dwn')
		{
			$hlp->generateCSV();
		}
	}
	
	echo("<button style='background-color:brown;' onClick='top.location.replace( \"".$me."?b=showadm&c=dwn\");' >Download Csv file</button>");
	
	
	//echo("<button style='background-color:brown;color:white;' onclick='$me?a=adm&b=showadm&c=dwn' >Download Csv file</button>");

	//
?>