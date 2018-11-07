// JS Helper for mgtech
///////////////////////////////////////////////////////////////////////////////////////////////////

CHelp = {
	_M_LINK_COL_SPEED: 5,
	_M_LINK_RED_START: 255,
	_M_LINK_GREEN_START: 255,
	_M_LINK_BLUE_START: 255,
	
	_M_LINK_RED_END: 26,
	_M_LINK_GREEN_END: 0,
	_M_LINK_BLUE_END: 105,
	
	// LINK ANIMATION STATE ////////////////////////////////////////////////////////////////////////////////////////////////
	_mLinkLastClick: null, //USED IN OVER EVENTS TO NOT TRIGGER ON ONE WHICH HAS BEEN CLICKED
	_mLinkChangeHandle: new Array(),
	_mLinkRedMap: new Array(),
	_mLinkGreenMap: new Array(),
	_mLinkBlueMap: new Array(),
	
	// CONTENT STATE
	_mContentMap: new Array(),
	_lastHash: -1,
	
	init: function(doc)
	{
		CUi.init(document);
		CHelp.onResize();
		
		CHelp.monitorHashChange();
		
		window.onresize = CHelp.onResize;		
	},
	
	monitorHashChange: function()
	{
		CHelp._lastHash = location.hash.substr(1);
		CUi.logFB('start = ' + CHelp._lastHash + " - " + location.hash.substr(1));
		setInterval( function() {
			cur_lastHash = location.hash.substr(1);
			if( CHelp._lastHash != cur_lastHash )
			{
				CUi.logFB('onc = ' + CHelp._lastHash + " - " + cur_lastHash);
				CHelp.clickMe( cur_lastHash );
			}
		}, 100 );
	},
	
	onResize: function()
	{
		var mel = CUi.doc.getElementById('imenuproducts'),
			wx = CUtil.getDim(true), cx = 475,
			infobxs = CUtil.getElementsById( 'inforoundbox' );

		
		if( wx < 1200 )
		{			
			for(var x=0; x< infobxs.length;x++)
				{ infobxs[x].style.width = '90%'; }
			
			cx = 375
		}
		else
		{
			for(var x=0; x< infobxs.length;x++)
				{ infobxs[x].style.width = '600px'; }
		}
		
		CUtil.applyToMultiChildNodes( mel, new Array( 'TABLE', 'DIV' ), true, function(el) {
			var nm = el.getAttribute('name');
			if( nm && nm.substr(0,2) == 'mr' )
			{
				var _elwx = parseInt( nm.substr(2) );
				el.style.width = cx - _elwx;
			}
		} );		
		
		if( CUi._isIE )
		{
			var ieshadowdiv = CUtil.getElementsById( 'ieshadowdiv' );
			for(var x=0; x< ieshadowdiv.length;x++)
			{
				ieshadowdiv[ x ].style.filter = "filter: progid:DXImageTransform.Microsoft.Shadow(color='#969696', Direction=145, Strength=8);";
				ieshadowdiv[ x ].style.padding = "0px;";
				ieshadowdiv[ x ].style.width = "100%;";
				ieshadowdiv[ x ].style.height = "100%;";
			}
		}
	},
	
	setContent: function(cid,chtml)
	{
		/* ALL DONE ON SERVER SIDE NOW
		var prod_html = '';
		
		if(sProdList.length > 0)
		{		
			prod_html = '<div class=gc-wrap>';
			for(var ix in sProdList)
			{
				prod_html += '<div class=gsc><div class=prod-check>&#160;</div><div class="prod-side-bar"><a class="a-high gtxt" href=# >' + sProdList[ix] + '</a></div></div>';
			}
			
			prod_html += '</div>';
		}
		
		chtml = ( (ctitle == '') ? ('') : ("<div class='gc gtxt-box-title btxt gtxt'>" + ctitle + "</div>") ) + chtml + prod_html;		
		*/
		
		CHelp._mContentMap[cid] = chtml;
	},
	
	clickMe: function(lid)
	{
		try {
			/*
			var lprodSubItems = new Array();
						
			if(isProdLink)
			{
				var elMenuTop = CUtil.getParentByName(aob, "menut");					
				
				if(elMenuTop)
				{
					var elSub = CUtil.getChildByName(elMenuTop, 'submenux', 'DIV', false);
					if(elSub)
					{
						var elAs = elSub.getElementsByTagName('a');
						for( var ix in elAs)
						{
							if( elAs[ix] && elAs[ix].getAttribute && elAs[ix].getAttribute('name')!='' )
								lprodSubItems.push( elAs[ix].getAttribute('name') );
						}						
					}
				}
			}
			*/
			
			CUi.logFB('doing click for = ' + lid);
			var anclk = 'req.x?fl=' + lid;
			CHelp.displayContent(anclk, lid);			
		} catch(e) {  }
		return( false );
	},
	
	setContentTitle: function(lid)
	{
		//if(CUtil.varok(_M_TITLE_LIST[lid]))
		//	CUi.setDocumentTitle(_M_TITLE_LIST[lid]);
	},
	
	isContentOK: function(lid)
	{
		return( CUtil.varok(CHelp._mContentMap[lid]) );
	},
	
	displayContent: function(lk, lid)
	{		
		if(CHelp.isContentOK(lid))
		{
			var belm = CUi.doc.getElementById('maincon');
			if(belm)
			{
				belm.innerHTML = String(CHelp._mContentMap[lid]);
				CHelp.setContentTitle(lid);
				location.hash = "#" + lid;
				CHelp._lastHash = lid;
				return(true);				
			}
		}
		else
		{
			CUi.showWorking();
			
			CTalk.sendSimplePost(lk, function(txt) {
				try {
					CUi.hideWorking();
					if(txt)
					{	
						CHelp.setContent(lid,txt);
						CHelp.displayContent(lk, lid);
					}
					else
					{					
						alert('Sorry, we are unable to load the requested information.');
					}
				} catch(e) { }
			} );			
		}

		return(false);
	},
	
	highLink: function(lk,onover)
	{
		// COL MAX:  1A 0 69( 26 0 105)
		var pelm = lk.parentNode.parentNode, lid = pelm.getAttribute('name'), ired = 0, iblue = 0, igreen = 0, ireddiff = (CHelp._M_LINK_RED_START - CHelp._M_LINK_RED_END), 
		igreendiff = (CHelp._M_LINK_GREEN_START - CHelp._M_LINK_GREEN_END), ibluediff = (CHelp._M_LINK_BLUE_START - CHelp._M_LINK_BLUE_END);						
	
		if(CHelp._mLinkLastClick == lid)
			return ;
			
		//NOTE: ASSUMTION: since green is biggest diff, do it this way, need to change if start end change; this was not made auto blah!!!
		igreen = CHelp._M_LINK_COL_SPEED;
		ired = (ireddiff / igreendiff) * igreen;
		iblue = (ibluediff / igreendiff) * igreen;						
		
		if(!onover)
			{ ired *= -1; igreen *= -1; iblue *= -1; }
		
		var initMaps = function(beFirstTime)
		{
			if(beFirstTime) //IF THIS IS THE FIRST TIME, THEN INVERT THIS SENSE ...
				onover = !onover;
				
			CHelp._mLinkRedMap[lid] = ( (onover) ? (CHelp._M_LINK_RED_END) : (CHelp._M_LINK_RED_START) );
			CHelp._mLinkGreenMap[lid] = ( (onover) ? (CHelp._M_LINK_GREEN_END) : (CHelp._M_LINK_GREEN_START) );
			CHelp._mLinkBlueMap[lid] = ( (onover) ? (CHelp._M_LINK_BLUE_END) : (CHelp._M_LINK_BLUE_START) );
			
			pelm.style.borderBottomColor = CUtil.makeRealColourHex(CHelp._mLinkRedMap[lid],CHelp._mLinkGreenMap[lid],CHelp._mLinkBlueMap[lid]);
		}, 
		stopInterval = function()
		{
			initMaps();
			clearInterval(CHelp._mLinkChangeHandle[lid]);
		}
		
		if(!CUtil.varok(CHelp._mLinkRedMap[lid]))
			initMaps(true);
		
		if(CHelp._mLinkChangeHandle[lid])
			clearInterval(CHelp._mLinkChangeHandle[lid]);
		
		if(CHelp._mLinkGreenMap[lid] <= CHelp._M_LINK_GREEN_END)
			CHelp._mLinkGreenMap[lid] = CHelp._M_LINK_GREEN_END;
		else if(CHelp._mLinkGreenMap[lid] >= CHelp._M_LINK_GREEN_START)
			CHelp._mLinkGreenMap[lid] = CHelp._M_LINK_GREEN_START;
		
		CHelp._mLinkChangeHandle[lid] = setInterval( function() {					
			pelm.style.borderBottomColor = CUtil.makeRealColourHex(CHelp._mLinkRedMap[lid],CHelp._mLinkGreenMap[lid],CHelp._mLinkBlueMap[lid]);						
			
			CHelp._mLinkRedMap[lid] -= ired;
			CHelp._mLinkGreenMap[lid] -= igreen;
			CHelp._mLinkBlueMap[lid] -= iblue;
			
			//NOTE: ASSUMTION: This needs to move in accordance with the setting of igreen above, and changes based on largest diff.
			if(CHelp._mLinkGreenMap[lid] <= CHelp._M_LINK_GREEN_END)
				{ stopInterval(); }
			else if(CHelp._mLinkGreenMap[lid] >= CHelp._M_LINK_GREEN_START)
				{ stopInterval(); }			
		}, 20 );
	},
	
	setupResizeDIM: function(specElm)
	{	
		specElm.style.width = CHelp.getMainWidth() + 'px';
		specElm.style.paddingTop = "10px";
		specElm.style.zIndex=10;

		var belm = CUi.doc.getElementById('basecon');
		if(belm)
			belm.style.height = CHelp.getConHeight();
			
		CUi.hookResize( 'main', function(e) {
			specElm.style.width = CHelp.getMainWidth() + 'px';
			belm.style.height = CHelp.getConHeight() + 'px';
		} );
	},
	
	getMainWidth: function()
	{
		var clw = CUtil.getDim(true,50);		
		if(clw > 1000) return(1000); else return(clw);
		//else return( CUtil.max( 800, clw ) );
	},
	
	getConHeight: function()
	{
		return( CUtil.getDim(false,35) - _BODY_TOP_HEIGHT - _BODY_BASE_EXTRA_HEIGHT - (2 * _BODY_BAR_HEIGHT) );
	},
	
	_M_MAX_TEXT_SIZE: (_CONTENT_DEF_TEXT_SIZE + 5),
	_M_MIN_TEXT_SIZE: (_CONTENT_DEF_TEXT_SIZE - 2),
	
	_mCurTextSize: _CONTENT_DEF_TEXT_SIZE,
	
	initTextSize: function()
	{
		CHelp.setTextSize(_CONTENT_DEF_TEXT_SIZE);
	},
	
	decTextSize: function()
	{
		if(CHelp._mCurTextSize > CHelp._M_MIN_TEXT_SIZE)
		{
			CHelp.setTextSize( --CHelp._mCurTextSize );
		}
	},
	
	incTextSize: function()
	{
		if(CHelp._mCurTextSize < CHelp._M_MAX_TEXT_SIZE)
		{
			CHelp._mCurTextSize += 2;
			CHelp.setTextSize( CHelp._mCurTextSize);
		}
	},
	
	setCurrentTextSize: function()
	{
		CHelp.setTextSize( CHelp._mCurTextSize );
	},
	
	setTextSize: function(ts)
	{
		var elm = CUi.doc.getElementById('basecon');
		if(elm)
		{
			CHelp._mCurTextSize = ts;
			
			CUtil.applyToChildNodes(elm, 'DIV', true, function(ob) {
				ob.style.fontSize = CHelp._mCurTextSize + 'pt';
			} );
		}
	},
	
	ID_POP: '_id_pop_img',
	
	pop_img: function(ob,iw,ih)
	{
		try {
			var isrc = ob.href,
				itxt = CUtil.getOBText(ob),
				html = "<div style='height:12'><u>" + itxt + "</u></div><br/><img src=\"" + isrc + "\" border=0 width=" + iw + " />";
			
			new CWin.win(CHelp.ID_POP, {
				autoOpen: true,
				withFrills: false,
				width: iw,			
				height: ih,
				capHeight: 28,
				modal: true,
				positionMid: true,
				closeOnEscape: true,
				html: html,
				htmlCenter: true,
				fadeShade: false
			} );
			
			CUi.setOMD(CHelp.ID_POP, function() {
				var wx = CWin.getMe(CHelp.ID_POP);
				if(wx)
				{
					wx.destroy();
					CUi.clearOMD(CHelp.ID_POP);
				}
			} );
		} catch(e) {}
	},	
	
	productSubDisp: function(aob)
	{
		var elMenuTop = CUtil.getParentByName(aob,"menut");
		
		if(elMenuTop)
		{
			var elSub = CUtil.getChildByName(elMenuTop,'submenux','DIV',false),
				elIPMid = CUtil.getChildByName(aob,'ipmid','SPAN',false);
						
			if(elSub)
			{
				if( elSub.style.display == 'block' )
				{
					elSub.style.display = 'none';
					elIPMid.innerHTML = '+';
				}
				else
				{
					elSub.style.display = 'block';
					elIPMid.innerHTML = ' - ';
				}
			}
		}
	},
	submitEnqForm : function(obj){
		var parDiv = CUtil.getParentByName( obj, 'enqMainDiv' );
		if( parDiv )
		{
			var eleName = new Array();
			eleValue = new Array();
			CUtil.applyToMultiChildNodes( parDiv, new Array( 'INPUT', 'TEXTAREA'), true, function(ele){
				eleName.push( ele.name );
				eleValue.push( ele.value );
				
			} );
			CTalk.sendPost( "req.x?ac=enq", eleName, eleValue,function( resp ){ 
					
					if(resp=="Ok")
					{
						alert("Equiry is sent,we will contact you shortly");
						CUtil.applyToMultiChildNodes( parDiv, new Array( 'INPUT', 'TEXTAREA'), true, function(ele){
						eleName.push( ele.name );
						eleValue.push( ele.value="" );
						});
					}
					else if(resp=="wrongemail")
					{
						alert("Please specify the correct email id"); 
					}
					else if(resp=="Nok")
					{
						alert("Enquiry is not sent"); 
					}
				
			} );

		}
		
	}
};