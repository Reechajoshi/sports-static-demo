<?php
	class cdb
	{
		var $dblnk = null;
	 	var $db_nospace = false;    
	 
		function cdb($dbn,$dbu,$dbp,$die_onerr = true)
		{
			$this->dblnk = @mysql_connect( 'localhost', $dbu, $dbp );
			
			if( $die_onerr && ( $this->dblnk === null || $this->dblnk === false ) )
				die ("ERROR: ".$this->db_error());
			else
			{
				if(!@mysql_select_db($dbn,$this->dblnk))
				{
					if( $die_onerr )
						die ("ERROR : ".$this->db_error());
					else
						$this->dblnk = null;
				}
			}
				
			//$this->db_query( "SET time_zone ='$__HACK_TZ'" ) or die ("ERROR : ".$this->db_error());
		}

		function db_begin() { return($this->db_query('BEGIN')); }
		function db_done() { return($this->db_query('COMMIT')); }
		function db_undo() { return($this->db_query('ROLLBACK')); }

		function db_permany()
		{
			$this->db_nospace=false;
		}
		
		function db_setspace($dnospace)
        {
			$this->db_nospace = $dnospace;
        }

		function db_query($q)
		{
			if($this->db_nospace && stripos($q,'insert')===0)
				return(false);
			else
			{
				return mysql_query($q, $this->dblnk); 
			}
		}

		function db_lastid()
		{
			return(mysql_insert_id($this->dblnk));
		}

		function db_affected()
		{
			return(mysql_affected_rows($this->dblnk));
		}

		function db_free($res)
		{
				return mysql_free_result($res);
		}
		
		function db_insert_id()
		{
			return (mysql_insert_id($this->dblnk)); 
		}

		function db_error()
		{
			return mysql_error();
		}

		function db_get($res)
		{
			return mysql_fetch_assoc($res);
		}

		function db_return($q, $ks)
		{
			$res = $this->db_query($q);
			if(($res)&&($row = $this->db_get($res)))
			{
				$ret = array();
				foreach( $ks as $k )
				{
					if( isset($row[$k]))
						$ret []= $row[$k];
					else
						$ret []= '';					
				}
				return($ret);
			}
			else
				return(false);
		}

		function db_num_rows($res)
		{
			return (mysql_num_rows($res)); 
		}
		
		function db_field_type($res,$pos)//position starts at 0
		{
			return( mysql_field_type( $res,$pos ) );
		}
		
		function escapeQuote($str)
		{
			//if((strpos($str,'"')!==false)&&(strpos($str,'\\"')===false))
			//{
				return(addslashes($str));
				//return(str_replace("\"","\\\"",$str));
			//}
			//else
				//return($str);
		}
	}
?>
