// Styles for html rendering
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

<?php
	// init these values as needed
	echo("CUi.__cssHeadHeight=20;");
?>

CUi.doc = false;

CUi._mouseBut = 0;
CUi._mouseX = 0;
CUi._mouseY = 0;

CUi._isFF = false;
CUi._isIE = false;

CUi.init = function(doc,dbody)
{
	CUi.doc = doc;
	CUi.msg_doc=doc; //defaults to this value.

	CUi.doc.onkeydown = CUi.keyPress;
	
	CUi.doc.onmousemove = CUi.mouseMove;
	CUi.doc.onmousedown = CUi.mouseDown;
	CUi.doc.onmouseup = CUi.mouseUp;

	window.onresize = CUi.winResize;

	try {
		CUi._M_FB = CUtil.varok(console.debug);
	} catch(e) { CUi._M_FB = false; }
	
	//TODO: dbody resize? needed?
//	if(CUtil.varok(dbody))
//		dbody.onresize = CUi.winResize;
		
	if(CUtil.varok(CUi.doc.onselectstart))
		CUi.doc.onselectstart = CUi.onSelectStart;
	
	CUi._isCH = false; CUi._isIE = false; CUi._isFF = false;	
	if (navigator.userAgent.indexOf("Chrome")!=-1)
		CUi._isCH = true;
	else if (navigator.userAgent.indexOf("MSIE")!=-1)
		CUi._isIE = true;	
	else if ((navigator.userAgent.indexOf("Gecko")!=-1)||(navigator.userAgent.indexOf("Firefox")!=-1))
		CUi._isFF = true;	
	else
	{
		// TODO: Prevent web app from starting here as nessasery
		// alert("ERROR");
	}
	
	CUi._userEventOMM = function(e) {}
	CUi._userEventOMD = function(e) {}
	CUi._userEventOMU = function(e) {}
	CUi._userEventOSS = function() {}

	CUi._ifrmRegEventOMM = function(w,e) {}
	CUi._ifrmRegEventOMD = function(w,e) {}
	CUi._ifrmRegEventOMU = function(w,e) {}

}

CUi.logFB = function(msg)
{
	if(CUi._M_FB) { console.debug(CUtil.getNow() + ": " + msg); }
}

CUi.getAgentVal = function(ie,ff,ch)
{	
	return(((CUi._isCH)?(((CUtil.varok(ch))?(ch):(ff))):((CUi._isFF)?(ff):(ie))));
}

CUi.setUserKeyHandlers = function(ukd)
{
	for(var k in ukd)
		CUi._M_ACCEL_SHIFT_DEF[k] = (ukd[k]);
}

CUi.setUserEventHandlers = function(omm,omd,omu,oss)
{
	if(omm) CUi._userEventOMM = omm;
	if(omd) CUi._userEventOMD = omd;
	if(omu) CUi._userEventOMU = omu;
	if(oss) CUi._userEventOSS = oss;
}

CUi.setIFrameEventHandlers = function(omm,omd,omu)
{
	if(omm) CUi._ifrmRegEventOMM = omm;
	if(omd) CUi._ifrmRegEventOMD = omd;
	if(omu) CUi._ifrmRegEventOMU = omu;
}

CUi.setMsgDoc = function(doc)
{
	CUi.msg_doc=doc;
}

CUi.togDwSym = '[+]';
CUi.togUpSym = '[-]';

CUi.toggleDropDiv = function(anc,dn)
{
	var ddiv = CUtil.getNeighbourByName(anc,dn,'DIV',2,true);
	if(ddiv)
	{
		if(anc.innerHTML==CUi.togUpSym && ddiv.style.display=='block')
		{
			anc.innerHTML=CUi.togDwSym;
			ddiv.style.display='none';
		}
		else if(anc.innerHTML==CUi.togDwSym && ddiv.style.display=='none')
		{
			anc.innerHTML=CUi.togUpSym;
			ddiv.style.display='block';
		}
	}
}

CUi.displayMsg = function(msg,errx)
{
        var updoc = CUi.msg_doc;

        if((!CUtil.varok(updoc)) || (!CUtil.varok(updoc.body)))
                return(false);

        var elm = updoc.getElementById("mainMsg");
        var atel = false;

        if(!CUtil.varok(errx)) errx = false;

        if(msg=="")
        {
                if(elm)
                	updoc.body.removeChild(elm);
        }
        else
        {
                if(!elm)
                {
                        elm = updoc.createElement('DIV');
                        elm.setAttribute("id","mainMsg");
                        atel = true;
                }
				
				elm.style.display="none";

                var html = "";

                if(errx)
                {
                        html = "<div class=msgcon><span style=\"background-color:#BE0000;\">" + msg;
                        html += "&#160;-&#160;<a class=awhite href=# onclick=\"document.body.removeChild(document.getElementById('mainMsg')); return false;\">Hide</a></span></div>";
                }
                else
                        html = "<div class=msgcon><span style=\"background-color:#00BE00;\">" + msg + "</span></div>";

                elm.innerHTML = html;
				
                if(atel)
                        updoc.body.appendChild(elm);

                elm.style.display="block";
        }
}
                                                                                                      
CUi.getPushUpHTML = function(ihtml)
{
	return("<span style=\"position:relative;top:-"+((CUi._isIE)?("2px"):("1px"))+";\">"+ihtml+"</span>");
}

CUi.getPushUpTextNode = function(txt) //todo: fix grid resizeing to be able to handle this.
{
	return("<div style=\"position:relative;top:-"+((CUi._isIE)?("2px"):("1px"))+";\">"+txt+"</div>");
}

CUi._M_TIP_PREFIX = "tip";
CUi.getTipHTML = function(tipID,tipTxt,bhide)
{
	return("<div class=tip id='" + CUi._M_TIP_PREFIX + tipID + "'" + ((bhide) ? (" style='display:none;'") : (" ")) + ">" + tipTxt + "</div>");
}

CUi.updateTipHTML = function(tipID,tipTxt)
{
	var el = CUi.doc.getElementById(CUi._M_TIP_PREFIX + tipID);
	if(el) { el.innerHTML = tipTxt; if(tipTxt=="") el.style.display='none'; else el.style.display='inline'; }
}

CUi.labelDataPairHTML = function(label,datID,datTxt,labW,datW)
{
	return("<div class=gensideblock><div class=gensideblock style='width:"+labW+";border-bottom: #9D9D9D 1px solid;background-color: #9D9D9D;'>" + CUi.getPushUpTextNode(label) + ":</div><div id='" + datID + "' class=gensideblock style='width:"+datW+";padding-left:2px;padding-right:2px;border-bottom: #9D9D9D 1px solid;'>" + ((CUtil.varok(datTxt)) ? (CUi.getPushUpTextNode(datTxt)) : ('')) + "</div></div>");
}

CUi._M_ACCEL_SHIFT_DEF = new Array();
	
CUi._M_KEYS_MAP = new Array();
CUi._M_KEY_ALPHA_CALLBACKS = new Array();
CUi._M_ACCEL_SHIFT_WITH_INPUT_FOCUS_DEF = new Array();

CUi.addShiftKeyHandler = function(kc, cb, bEvenWithInputFocus)
{
	CUi._M_ACCEL_SHIFT_DEF[kc.toUpperCase().charAt(0)] = cb;
	
	if(bEvenWithInputFocus)
	{
		CUi._M_ACCEL_SHIFT_WITH_INPUT_FOCUS_DEF[kc.toUpperCase().charAt(0)] = true;
	}
	
	CUi._bKeyAccelON = true;
}
 
CUi.removeShiftKeyHandler = function(kc)
{
	CUi._M_ACCEL_SHIFT_DEF[kc.toUpperCase().charAt(0)] = null;
	if(CUtil.isdef(CUi._M_ACCEL_SHIFT_WITH_INPUT_FOCUS_DEF[kc.toUpperCase().charAt(0)]))
		CUi._M_ACCEL_SHIFT_WITH_INPUT_FOCUS_DEF[kc.toUpperCase().charAt(0)] = false;
}

CUi.pushAlphaKeyCB = function(cb) //HACKERS
{
	CUi._M_KEY_ALPHA_CALLBACKS.push( cb );
}

CUi.hookKeyCode = function(kcode, cid, cb)
{
	if(!CUi._M_KEYS_MAP[kcode])
		CUi._M_KEYS_MAP[kcode] = new Array();
		
	CUi._M_KEYS_MAP[kcode].push( { id: cid, cb: cb } );		
	
	CUi._bKeyAccelON = true;	
}

CUi.destroyKeyCode_Hook = function(kcode, cid)
{
	var newKeysMap = new Array();
	
	if(CUi._M_KEYS_MAP[kcode])
	{
		for(var i in CUi._M_KEYS_MAP[kcode])
		{
			if(CUi._M_KEYS_MAP[kcode][i].id != cid)
			{
				newKeysMap.push( CUi._M_KEYS_MAP[kcode][i] );
			}
		}
					
		CUi._M_KEYS_MAP[kcode] = newKeysMap;
	}
}

CUi.eventTarget = function(e)
{
	return(e.target || e.srcElement);
}

CUi.keyPress = function(e)
{
	e = CUtil.ensureRealEvent(e);
	CUi._inputLastTime = new Date();
	
	if(CUi._bKeyAccelON)
	{		
		var kcode = CUtil.getEventKeyCode(e);
		var kchar_code = CUtil.getEventCharCode(e);
		var kchar = String.fromCharCode(kchar_code);
		
		//alert(kcode + " - " + kchar_code + " - " + kchar);
			
		if(e.shiftKey)
		{
			//if(kcode > 31 && kcode < 250) //space plus printable keys approx
			{
				var eTarget = CUi.eventTarget(e);
				if( (CUi._M_ACCEL_SHIFT_WITH_INPUT_FOCUS_DEF[kchar]) || (eTarget && eTarget.tagName != "INPUT") )
				{					
					if(CUtil.isfunc(CUi._M_ACCEL_SHIFT_DEF[ kchar ] ))
					{ 
						try
						{
							var ret = ( CUi._M_ACCEL_SHIFT_DEF[kchar](e) );
							if(ret == false)
								return(false);
						}
						catch(exc) {}
					}
				}
			}
		}
		else if(!e.ctrlKey && !e.altKey) 
		{
			//TODO: special meaning of keys hardwired
		}
		
		if(CUi._M_KEYS_MAP[kcode])	
		{
			if(CUi._M_KEYS_MAP[kcode].length > 0 )
			{
				var ret = (CUi._M_KEYS_MAP[ kcode ][ CUi._M_KEYS_MAP[kcode].length - 1 ].cb( kcode, e ));
				if(CUtil.isdef(ret) && ret==false)
					return(ret); // DO NOT CONTIE PROCESSING
			}
		}
		else if(CUi._M_KEYS_MAP[kchar])
		{		
			if(CUi._M_KEYS_MAP[kchar].length > 0 )
			{
				var ret = (CUi._M_KEYS_MAP[ kchar ][ CUi._M_KEYS_MAP[kchar].length - 1 ].cb( kchar, e ));
				if(CUtil.isdef(ret) && ret==false)
					return(ret); // DO NOT CONTIE PROCESSING
			}
		}		
		
		if( CUi._M_KEY_ALPHA_CALLBACKS.length > 0 && CUtil.isCharAlphaNumeric(kchar_code) )
		{
			for(var iax in CUi._M_KEY_ALPHA_CALLBACKS)
				CUi._M_KEY_ALPHA_CALLBACKS[iax](kchar,e);
		}
	}
	
	return(true);
}

CUi.setMouseXY = function(e)
{
	try
	{
		if (CUi.doc.all)
		{
			CUi._mouseX = event.clientX;
			CUi._mouseY = event.clientY;
		}
		else
		{
			CUi._mouseX = e.pageX - CUi.doc.body.scrollLeft;
			CUi._mouseY = e.pageY - CUi.doc.body.scrollTop;
		}
	} catch(err) {}
	
	if (CUi._mouseX < 0) CUi._mouseX = 0;
	if (CUi._mouseY < 0) CUi._mouseY = 0;
}

CUi._M_WIN_RESIZE_ENLARGE_MAP = Array();
CUi._M_WIN_RESIZE_SHRINK_MAP = Array();

CUi.winResize = function(e)
{
	e = CUtil.ensureRealEvent(e);	
	
	if((CUtil.varok(CUi._M_WIN_LAST_HEIGHT))&&(CUtil.varok(CUi._M_WIN_LAST_WIDTH)))
	{
		try {
		var resizeFrom = ((CUtil.getDim(true) > CUi._M_WIN_LAST_WIDTH)?(CUi._M_WIN_RESIZE_ENLARGE_MAP):(CUi._M_WIN_RESIZE_SHRINK_MAP));
		} catch(e) { alert(e); }
		
		for(i in resizeFrom)
		{
			var info = resizeFrom[i];
			var elms = CUtil.getElementsById(info.id);

			for(ei in elms)
			{	
				try
				{
					if(info.doWidth)
						elms[ei].style.width = (CUtil.getDim(true) - info.widthOffset) + 'px';
				} catch(e) { }
			}
		}
		
		resizeFrom = ((CUtil.getDim(false) > CUi._M_WIN_LAST_HEIGHT)?(CUi._M_WIN_RESIZE_ENLARGE_MAP):(CUi._M_WIN_RESIZE_SHRINK_MAP));
		for(i in resizeFrom)
		{
			var info = resizeFrom[i];
			var elms = CUtil.getElementsById(info.id);

			for(ei in elms)
			{
				try {	
					if(info.doHeight)
					{
						elms[ei].style.height = (CUtil.getDim(false) - info.heightOffset) + 'px';
					}
				} catch(e) { }
			}
		}
	}
}

CUi.regWinResizeCB = function(cb)
{
	window.onresize = cb;
}

CUi._regWinResize = function(enlargeProir,shrinkPrior,info)
{
	var def = {
		doWidth: false, doHeight: false
	};
	
	if(!CUtil.varok(CUi._M_WIN_LAST_WIDTH))
		CUi._M_WIN_LAST_WIDTH=CUtil.getDim(true);

	if(!CUtil.varok(CUi._M_WIN_LAST_HEIGHT))
		CUi._M_WIN_LAST_HEIGHT=CUtil.getDim(false);
				
	for(var i in info)
		def[i] = info[i];
	
	CUi._M_WIN_RESIZE_SHRINK_MAP[shrinkPrior] = def;
	CUi._M_WIN_RESIZE_ENLARGE_MAP[enlargeProir] = def;
}

CUi.mouseMoveIFrm = function(e)
{
	var w = CUi.getRegIFrame();
	e = CUtil.ensureRealEvent(e,w);
	CUi._ifrmRegEventOMM(w,e)
}

CUi.mouseUpIFrm = function(e)
{
	var w = CUi.getRegIFrame();
	e = CUtil.ensureRealEvent(e,w);
	CUi._ifrmRegEventOMU(w,e)
}

CUi.mouseDownIFrm = function(e)
{
	var w = CUi.getRegIFrame();
	e = CUtil.ensureRealEvent(e,w);
	CUi._ifrmRegEventOMD(w,e)
}

CUi.getRegIFrame = function()
{
	var x = CUi.doc.getElementById('globIFRM_FI');
	if(x) return(x.contentWindow);
}

CUi.regIFrame = function(sapp)
{	
	var ifrm = "<iframe src='"+sapp+"'id=globIFRM_FI width='100%' height='100%' frameborder=0 marginheight=0 marginwidth=0 scrolling=yes/>";
	
	CUtil.waitForIt( function() {
	
		var x = CUi.doc.getElementById('globIFRM_FI');
		if(x)
		{
			/*x.contentWindow.document.onmousemove = CUi.mouseMoveIFrm;
			x.contentWindow.document.onmouseup = CUi.mouseUpIFrm;
			x.contentWindow.document.onmousedown = CUi.mouseDownIFrm;*/
			
			return(true);
		}
		else
			return(false);
	} );
	
	return(ifrm);
}

CUi.mouseMove = function(e)
{
	e = CUtil.ensureRealEvent(e);
	CUi.setMouseXY(e);
	CUi._userEventOMM(e);
}

CUi.mouseUp = function(e)
{	
	e = CUtil.ensureRealEvent(e);
	CUi._userEventOMU(e);
}

CUi.mouseDown = function(e)
{
	e = CUtil.ensureRealEvent(e);
	
	try {
		if(CUi.doc.getElementById(CUtil.POP_IMG_ID))
		{
			var s = CUtil.getEventSrc(e)
			if(s && s.getAttribute('id')!=CUtil.POP_IMG_ID && !CUtil.getParentByName(s,CUtil.POP_IMG_ID))
			{
				var allIPops = CUtil.getElementsById(CUtil.POP_IMG_ID);
				for(var i in allIPops)
					allIPops[i].parentNode.removeChild(allIPops[i]);
			}
		}
	} catch(e) {}
	
	CUi._userEventOMD(e);	
}

CUi.onSelectStart = function()
{
	return(CUi._userEventOSS());
}

CUi.getShiftImgHTML = function(iname,src,itip,bl,bt)
{
	if(!CUtil.varok(bl) || !CUtil.varok(bt)) { bl = 0; bt = 0; }
	var sbl = bl-1; var sbt = bt-1;
	return("<div><img title='" + itip + "' alt='" + itip.slice(0,1) + "' class='"+iname+"' name='"+iname+"' src='"+src+"' border=0 style='position:relative;left:" + bl + ";top:" + bt + ";' onMouseOver='CUi.shiftImg(this," + sbl + "," + sbt + ");' onMouseOut='CUi.shiftImg(this," + bl +"," + bt + ");'/></div>");
}

CUi.shiftImg = function(ob,x,y)
{
	ob.style.left = x;
	ob.style.top = y;
}

CUi.getJSAnchorHTML = function(onc,inhtml)
{
	return("<a href=# onMouseDown='"+onc+"' onClick='return(false);'>" + inhtml + "</a>");
}

CUi.getSimpleAMenuHTML = function(mc,ms)
{
	var ix = 0;
	var html = "";
	for(var n in ms)
	{
		if(++ix<mc)
			html += "<span class=s1><a id=txt class=asmpl href=# onClick='"+ms[n]+";return false;'>" + n + "</a></span>";
		else
			html += "<span class=s1nb><a id=txt class=asmpl href=# onClick='"+ms[n]+";return false;'>" + n + "</a></span>";
	}
	return(html);
}

CUi._M_FILE_IN_FIX = "FINFIX";

CUi.postFileI = function(sapp,fid,cb)
{
	var finCreated = false;
	
	if(!document.getElementById(fid))
	{
		var ifrm = "<div style='width:100%;height:35px;'><iframe id=globIFRM_FI width='100%' height=25px frameborder=0 marginheight=0 marginwidth=0 scrolling=no/></div>";
		CUi.displayWinBlocker(CUi._M_FILE_IN_FIX,CUi._M_FILE_IN_FIX,"Please select a file to open",450,50,ifrm);
		
		CUtil.waitForIt( function() {
			var ifrmOB = document.getElementById("globIFRM_FI");
			if(ifrmOB)
			{
				ifrmOB.contentWindow.document.open();
				ifrmOB.contentWindow.document.write("<html><head></head><body><form name=formFI action='"+sapp+"' method=post enctype='multipart/form-data'><input type=file id=fin name='"+fid+"' style='width:100%' size=50/></body></html>");
				ifrmOB.contentWindow.document.close();
				
				var fin = false;
				CUtil.waitForIt( function() {
					if(!fin)						
						fin = ifrmOB.contentWindow.document.getElementById('fin');
					if(fin)
					{
						finCreated=true;
						if(CUtil.varok(fin.value)&&fin.value.length>1)
						{ 
							fin.parentNode.submit();
							cb();
							return(true);
						}
						return(false);
					}
					else if(!finCreated)
						return(false);
					
					return(true);  //input no longer exists stop waiting anymore
				} );
				
				return(true);
			}
		} );		
	}		
}

CUi.titleCenterOn=false;

CUi.setSoleModalBodyHTML = function(zi,inHtml,cid,w,h,caption)
{
	if(!CUtil.varok(cid))
		cid='blanketCover';

	//alert(CUi.doc.body.clientWidth + " " + CUi.doc.body.scrollWidth + " " +CUi.doc.body );
	//var w = Math.max(CUi.doc.body.clientWidth,CUi.doc.body.scrollWidth); var h = Math.max(CUi.doc.body.clientHeight,CUi.doc.body.scrollHeight);
	//var cw = CUi.doc.body.clientWidth; var ch = CUi.doc.body.clientHeight;
		
	var soleOB = CUi.doc.createElement('div');
	soleOB.setAttribute("name","soleChild");
	soleOB.setAttribute("id",cid);
	soleOB.style.width="100%";
	soleOB.style.height="100%";
	soleOB.style.backgroundColor="#ffffff";
	
	var innerW = '100%';
	var innerH = '100%';
	
	if(CUtil.varok(w)&&CUtil.varok(h))
	{
		if(String(w).indexOf('%')!=-1)
			innerW=String(parseInt(w)-5)+"%";
		else
			innerW=String(parseInt(w)-20);
		
		if(String(h).indexOf('%')!=-1)
			var innerH=String(parseInt(h)-5)+"%";
		else
			var innerH=String(parseInt(h)-20);
	}
	else
	{
		w = CUi.doc.body.clientWidth-50;
		h = CUi.doc.body.clientHeight-50;
		
		innerW = w - 10;
		innerH = h - 10;
	}
	
	var html = "<table border=0 width=100% height=100%><tr><td align=center valign=middle>";
	
	html += "<div name=blockerFullCon style=\"border: #9D9D9D 2px ridge;padding: 5px;background-color:#FFF;color:#000;text-align:left;position:relative;width:"+w+";height:"+h+"\">";
	
	if(CUtil.varok(caption))
	{
		html += "<div class=blockerHead>";
		html += "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr>"; 
		html += "<td valign=top><div style=\"position:relative;top:-"+((CUi._isIE)?("2px"):("1px"))+";\">"+caption+"</div></td>";
		html += "<td valign=top width=1px><a href=# onClick='var p=CUtil.getParentByName(this,\"soleChild\");p.parentNode.removeChild(p);return false;'>" + CUi.getShiftImgHTML('headclose','images/headclose.gif','close',1,1) + " </a></td>";
		html += "</tr></table></div>";
	}
	
	html += "<div class=gencon name=blockerInHtml style=\"text-align:left;background-color:#FFF;color:#000;position:relative;width:"+innerW+";height:"+innerH+"\">"+inHtml+"</div></div>";
	
	html += "</td></tr></table>";

	soleOB.innerHTML = html;
	CUi.doc.body.innerHTML="";
	CUi.doc.body.appendChild(soleOB);
	
	return(soleOB);
}
