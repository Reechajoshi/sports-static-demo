
CUi.initMouse = function()
{
	CUi.setUserEventHandlers(CUi.sooMM ,CUi.sooMD,CUi.sooMU,CUi.ieSelectStart);
	//CUi.setIFrameEventHandlers(CUi.sooiFrmMM,CUi.sooiFrmMD,CUi.sooiFrmMU);
}

CUi.htmlMenuRow = function(ms,mw,mc)
{
	var html = "<div class=menurow >";

	for(var n in ms)
	{
		//html += "<a class="+mc+" style=\"width: " + mw + ";\" href=# onBlur='CUi.showContextMenu(0,this,top.dw.document);' onClick=\"" + ms[n] + "; return(false);\" ><div>" + n + "</div></a>";
		html += "<a class="+mc+" style=\"width: " + mw + ";\" href=# onMouseDown=\"" + ms[n] + ";return(false);\" onclick=\"return(false);\"><div>" + n + "</div></a>";
	}
	
	html += "</div>";
	return(html);
}

CUi.lastMsgErrX = false;

CUi.displayMsg = function(msg,errx)
{	
	CUtil.log("MSG: " + msg);
	
	var upwin = top.up;  //TODO: hacked it  badly ;(
	var updoc = top.up.document;
	
	if((!CUtil.varok(updoc)) || (!CUtil.varok(updoc.body)))
		return(false);
	
	var elm = updoc.getElementById("mainMsg");
	var atel = false;

	if(!CUtil.varok(errx)) errx = false;
	
	if(msg=="")
	{
		if(elm && !CUi.lastMsgErrX)
			updoc.body.removeChild(elm);
	}
	else
	{
		if(!elm)
		{
			CUi.lastMsgErrX = false;
			elm = updoc.createElement('DIV');
			elm.setAttribute("id","mainMsg");
			atel = true;
		}
		//else if(elm.style.display=="block") 
		//{
			//if(!errx && CUi.lastMsgErrX)
				//return(false);		
		//}
				
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
		CUi.lastMsgErrX = errx;
	}
}

CUi.showContextMenu = function(mid,src,pdoc)
{	
	if(CUtil.varok(mid))
	{
		if(!CUtil.varok(pdoc)) pdoc = CUi.doc;
		
		__funcRmvIfExists = function() { 
			var cm = pdoc.getElementById("popup_contextmenu"); 			
			if(cm) { cm.parentNode.removeChild(cm);	} 
		}
		
		if(mid==0)
		{			
			setTimeout(__funcRmvIfExists,50);
		}
		else
		{
			var hasMenu = false;
			
			var __funcHtmlLink = function(txt,onc,wparam,wl)
			{
				hasMenu = true;
				var wlhtml = ""; if(wl) wlhtml = " style=\"border-bottom:1px solid #ccc;\" "; else wlhtml = "";
				//ASSUMTION: must always use safe run on context drop menus
				var oc = 'CUi.safeRun("' + onc + '"' + ((CUtil.varok(wparam))?(',"'+wparam+'");return false;'):(');return false;'));
				
				if(CUi._isFF)
					return("<div class=acontextmenu style='padding: 2px;" + ((wl) ? ("border-bottom:1px solid #999;") : ("")) + "' ><a class=acontextmenu href=# onmousedown='"+oc+"' onclick='return(false);'>"+txt+"</a></div>");
				else
					return("<div class=acontextmenu " + ((wl) ? ("style=\"border-bottom:1px solid #999;\"") : ("")) + " ><a class=acontextmenu style='padding: 2px;' href=# onmousedown='"+oc+"' onclick='return(false);'>"+txt+"</a></div>");
			}
			
			var menuwidth = 0;		
			var left = 0, top = 0;
			
			menuwidth=150;
			left = src.offsetLeft + pdoc.body.scrollLeft;
			top = pdoc.body.scrollTop;
			
			var html = "<div class=contextmenu onMouseOut='CUi.contextMenuOut(event);' style='width:" + menuwidth + ";left:" + left + "px;top:" + top + ";z-index:" + CUi._zIndexHighest + "'>"
			
			if(mid=="CATELOG")
			{
				html += __funcHtmlLink("Active","CCat.viewCat",'(1)');
				html += __funcHtmlLink("Pending","CCat.viewCat",'(0)');
				html += __funcHtmlLink("Import To","CCat.importTo");
			}
			else if(mid=="CLIENTS")
			{
				html += __funcHtmlLink("New","CGroup.displayMarketWatches");
				html += __funcHtmlLink("Edit","CGroup.displayMarketWatches");
				html += __funcHtmlLink("Delete","CGroup.displayMarketWatches");
			}
			else if(mid=="ORDERS")
			{
			}
			
			html += "</div>";
			
			__funcRmvIfExists();
			
			if(hasMenu)
			{
				cm = pdoc.createElement('div');			
				cm.setAttribute('id','popup_contextmenu');
				cm.innerHTML = html;
				pdoc.body.appendChild(cm);
			}
		}
	}
}

CUi.contextMenuOut = function(e)
{
	var toel = CUtil.getEventDestSrc(e);

	if(CUtil.varok(toel))
	{
		if(CUtil.getClassName(toel)!='acontextmenu')
		{
			//alert(toel.parentNode.innerHTML);
			CUi.showContextMenu(0);
		}
	}
}

CUi.safeRun = function(cmd,param)
{	
	if(CUtil.varok(param))
	{
		param = param.replace(/[(]/,"('").replace(/[)]/,"')").replace(/[,]/,"','");
		cmd = "try { " + cmd + param + "; } catch(e) {alert(e.message);}";
		//alert(cmd);
	}
	else
	{
		cmd = "try { " + cmd + "(); } catch(e) {alert(e.message);}";
	}
	
	eval(cmd);
	CUi.showContextMenu(0,this,top.dw.document);
}

CUi.sooMU = function(e)
{
	try
	{
		var s = CUtil.getEventSrc(e);	
		if(s)
		{
			var c = CUtil.getClassName(s)
			if((c!="contextmenu")&&(c!="contextlink"))
				{ CUi.showContextMenu(0); }
		}
		
		CGrid.handleMouse(e);
	}
	catch(err) { CUtil.log(err.message); }
}

CUi.sooMM = function(e)
{
	try
	{
		CGrid.handleMouse(e);		
	}
	catch(err) { CUtil.log(err.message); }
}


CUi.sooMD = function(e)
{
	try
	{
		CGrid.handleMouse(e);	
	}
	catch(err) { CUtil.log(err.message); }
}

CUi.ieSelectStart = function()
{
	return(true);
}

CUi.retrIFrameCon = function(sapp,fid)
{
	var ifrm = CUi.regIFrame(sapp);
	var soleOB=	CUi.setSoleModalBodyHTML(20,ifrm,fid);
	
	return("globIFRM_FI");
}

CUi._M_FILE_IN_FIX = "FINFIX";

CUi.postFileI = function(cap,sapp,fid,cb)
{
	var finCreated = false;
	
	if(!document.getElementById(fid))
	{
		var ifrm = "<iframe id=globIFRM_FI width=450px height='45px' frameborder=0 marginheight=0 marginwidth=0 scrolling=no/>";
		//CUi.displayWinBlocker(CUi._M_FILE_IN_FIX,CUi._M_FILE_IN_FIX,"Please select a file to open",450,50,ifrm);
		
		var soleOB=	CUi.setSoleModalBodyHTML(20,ifrm,fid,460,50,cap);
		
		CUtil.waitForIt( function() {
			var ifrmOB = document.getElementById("globIFRM_FI");
			if(ifrmOB)
			{
				ifrmOB.contentWindow.document.open();
				ifrmOB.contentWindow.document.write("<html><head></head><body><form name=filepost action='"+sapp+"' method=post enctype='multipart/form-data'><input type=file id=fin name='"+fid+"' size=50/></body></html>");
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
							CUi.displayMsg("Working...");
							fin.parentNode.submit();
							
							CUtil.waitForIt( function() {
								if(ifrmOB.contentWindow.document.getElementById('MSG_RETURN'))
								{
									CUi.displayMsg("");
									cb(ifrmOB.contentWindow.document.getElementById('MSG_RETURN').value);
									soleOB.parentNode.removeChild(soleOB);
									return(true);
								}
								else
									return(false);
							} );
							
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
			else
				return(false);
		} );		
	}		
}

CUi._VIEW_TABLES = new Array();

CUi.toggleViewTable = function(ob,cc)
{	
	var p = CUtil.getParentByName(ob,"vtable");
	if(p) 
	{
		if(ob.getAttribute("name")=="HEAD_CLOSE")
		{			
			if (!CUtil.varok(CUi._VIEW_TABLES[cc]))
			{		
				CUi._VIEW_TABLES[cc]=new Array();
				CUi._VIEW_TABLES[cc]["rstr"]=p.innerHTML;
				CUi._VIEW_TABLES[cc]["clcap"] = ""
				var cap = CUtil.getChildByName(p,"cname","TR",true);
				
				if(cap) 
				{
					var caphtml=CUtil.strsub(cap.innerHTML,"headcl","headop");					
					CUi._VIEW_TABLES[cc]["clcap"]=CUtil.strsub(caphtml,"HEAD_CLOSE","HEAD_OPEN");
				}
				else return(false);				
			}
			
			p.innerHTML = CUi._VIEW_TABLES[cc]["clcap"];			
			return(true);			
		}
		else
		{
			if (CUtil.varok(CUi._VIEW_TABLES[cc]))
			{				
				p.innerHTML = CUi._VIEW_TABLES[cc]["rstr"];
			return(true);			
			}
		}
	}
	
	return(false);
}
