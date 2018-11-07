<?php
	require('../../../../php/class.helper.php');
	$hlp = new chelp(false);

	$UNAME="";
	if(!$hlp->authSession())
		die('');

	require('../../../../conf/main.php');
	$hlp = new chelp();
	
	$res = $hlp->_db->db_query('select sname,shtml from templ');
	$sz = $hlp->_db->db_num_rows($res);
	$cnt = 0;
	if($sz>0)
	{
		while(($row=$hlp->_db->db_get($res)))
		{
			$sname = $row['sname'];
			$html = str_replace(array("\n","\r","'"),array(" "," ","\'"),$row['shtml']);
			$prev = $S_URI.$IMG_CGI."?tpi=".base64_encode($sname);
			
			
			echo(($cnt > 0)?(","):(""));
			
			echo("{title:'$sname',image:'$prev',html:'$html'}");
			$cnt++;
		}
	}
?>