<title>Media Conversion Tool</title>

<script type="text/javascript" src="jsinc.x"></script>
<?php include("inc/js_helper.php"); ?>
<link rel="stylesheet" type="text/css" href="styles/ui.css.x">
<script type="text/javascript"> <!--
	var tabprops = new Array();	
	tabprops['TAB_ARTWORKS'] = { txtColor: '#28A9D3', txtSelectColor: '#28A9D3', tabLImage: 'images/tabs/tabl.png', tabTImage: 'images/tabs/tabt.png', tabRImage: 'images/tabs/tabr.png', extraBackdrop: '#28A9D3', borderSelect: '#fff 1px solid' };
	
	var addDynTab = function(parentTabID,newTabName,tabTxt,iuri,isNMailer)
	{		
		addDynTabEx(parentTabID,newTabName,"",tabTxt,iuri,isNMailer);				
	}

	var addDynTabEx = function(parentTabID,newTabID,newTabIDFix,tabTxt,iuri,isNMailer)
	{
		addDynTabMain(parentTabID,newTabID,newTabIDFix,tabTxt,iuri,isNMailer,false);
	}
	
	var addDynTabDirect = function(parentTabID,newTabID,newTabIDFix,tabTxt,iuri,isNMailer)
	{
		addDynTabMain(parentTabID,newTabID,newTabIDFix,tabTxt,iuri,isNMailer,true);
	}
	
	var addDynTabMain = function(parentTabID,newTabID,newTabIDFix,tabTxt,iuri,isNMailer,makeDirectReq)
	{
		var ewin = window, doc = document;
		var ptm = ewin.CTabs.getTabObject(parentTabID);
		while( !ptm )
		{
			if( CUtil.varok( ewin = ewin.parent.window ) )
			{
				ptm = ewin.CTabs.getTabObject(parentTabID);
			}
		}
		
		var tabc = ptm.getTabIndexFromID(newTabID + newTabIDFix);
		
		if(!tabc)
		{
			var p = false;
			if( makeDirectReq )
				p = { name: tabTxt, txtColor: "#28A9D3", iframeURL: iuri };
			else
				p = { name: tabTxt, txtColor: "#28A9D3", cbURL: iuri };
			
			if(CUtil.varok(tabprops[parentTabID]))
				{ for(var i in tabprops[parentTabID]) { p[i] = tabprops[parentTabID][i]; } }
			
			var tabc = false;
			if( makeDirectReq )
				tabc = ptm.addIFrameTab( (newTabID + newTabIDFix) , p);
		/*
			else
				tabc = ptm.addCallBackURLTab( (newTabID + newTabIDFix) , p);
		*/		
			ptm.displayNewTab(tabc);
		}
		else
			ptm.selectEx( tabc );
	}

	var indTabRef = function(tid,tp)
	{
		var doc = document;
		var ptm = CTabs.getTabObject(tid);
		ptm.indRefIFrame(0);
	}
	
	var getAllClient = function()
	{
		if( CUtil.varok( parent.parent._ALL_CLIENTS ) )
		{
			return( parent.parent._ALL_CLIENTS );
		}
		return(false);
	}
	
	CUi.init(document);
	CTabs.init();
	CUi.initMouse();
// --> </script>
