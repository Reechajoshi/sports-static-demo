<?php
	require('../conf/vars.php');
	require('../helper/class.helper.php');
	$hlp = new chlp();

	$res = $hlp->_db->db_query('select iid, iname from images ;');
	$sz = $hlp->_db->db_num_rows($res);
	$cnt = 0;
	if($sz>0)
	{
		echo("[['','']");//important to keep one empty elment ..coz if 1st image is not present then image loader will cause probs and doesnt come up
		while(($row=$hlp->_db->db_get($res)))
		{
			echo(',["'.$row['iname'].'", "'.$DWN_FILE.'?iid='.base64_encode($row['iid']).'"]');
			$cnt++;
		}
		echo("]");
	}
	else
		echo("''");
?>