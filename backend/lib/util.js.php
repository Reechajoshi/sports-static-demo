
// Java script utilities
//////////////////////////////////////////////////////////////////////////////////////////

CUtil = function() {}

CUtil.M_KEY_CODE_BACK= 8
CUtil.M_KEY_CODE_TAB= 9
CUtil.M_KEY_CODE_ENTER= 13
CUtil.M_KEY_CODE_ESCAPE= 27
CUtil.M_KEY_CODE_LEFT_ARROW = 37
CUtil.M_KEY_CODE_TOP_ARROW = 38
CUtil.M_KEY_CODE_RIGHT_ARROW= 39
CUtil.M_KEY_CODE_BOTTOM_ARROW= 40
CUtil.M_KEY_CODE_DEL= 46
CUtil.M_KEY_CODE_COLON= 58
CUtil.M_KEY_CODE_FUNCTION_1= 112
CUtil.M_KEY_CODE_FUNCTION_2= 113
CUtil._M_FB = false

CUtil.M_CHAR_CODE_LEFT_ARROW = String.fromCharCode(CUtil.M_KEY_CODE_LEFT_ARROW);
CUtil.M_CHAR_CODE_RIGHT_ARROW = String.fromCharCode(CUtil.M_KEY_CODE_RIGHT_ARROW);

CUtil.init = function()
{
}

CUtil.removeNamedIndex = function(x,ni)
{
	var na = new Array();
	
	for(i in x)
	{
		if(i!=ni) 
			na[i]=x[i]; 
	}	
	
	return(na);
}	

CUtil.delCookie = function(name)
{
	CUtil.setCookie(name,"",-1);
}

CUtil.setCookie = function( name, value, expires, path, domain, secure ) 
{
	// set time, it's in milliseconds
	var today = new Date();
	today.setTime( today.getTime() );

	//day to ms = expires * 1000 * 60 * 60 * 24;
	if (CUtil.varok(expires))
		expires = new Date( today.getTime() + (expires) ); 

	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires.toGMTString() : "" ) + 
	( ( path ) ? ";path=" + path : "" ) + 
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}

CUtil.getCookie = function(check_name)
{
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false;
	
	for ( i = 0; i < a_all_cookies.length; i++ )
	{
		a_temp_cookie = a_all_cookies[i].split( '=' );
		
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
	
		if ( cookie_name == check_name )
		{
			b_cookie_found = true;
			if ( a_temp_cookie.length > 1 )
			{
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}
			return cookie_value;
			break;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}

	if ( !b_cookie_found )
		return null;
}

CUtil.getElementsById = function(sId)
 {
    var outArray = new Array();	
	
	if(typeof(sId)!='string' || !sId)
	{
		return outArray;
	};
	
	if(document.evaluate)
	{
		var xpathString = "//*[@id='" + sId.toString() + "']";
		var xpathResult = document.evaluate(xpathString, document, null, 0, null);
		while ((outArray[outArray.length] = xpathResult.iterateNext())) { }
		outArray.pop();
	}
	else if(document.all)
	{
		if(CUtil.varok(document.all[sId]))
		{
			if(CUtil.varok(document.all[sId].length))
			{
				for(var i=0,j=document.all[sId].length;i<j;i+=1){
				outArray[i] =  document.all[sId][i];}
			}
			else
				outArray.push(document.all[sId]);
		}		
	}
	else if(document.getElementsByTagName)
	{	
		var aEl = document.getElementsByTagName( '*' );	
		for(var i=0,j=aEl.length;i<j;i+=1)
		{	
			if(aEl[i].id == sId )
			{
				outArray.push(aEl[i]);
			};
		};	
		
	};
	
	return outArray;
 }
 
 CUtil.isMM = function(e) { return(e.type=="mousemove") }
 CUtil.isMD = function(e) { return(e.type=="mousedown") }
 CUtil.isMU = function(e) { return(e.type=="mouseup") }
 
 CUtil.ensureRealEvent = function(e,twin)
 {
	if(CUtil.varok(e))
		return(e);
	else if((CUtil.varok(twin)) && (CUtil.varok(twin.event)))
		return(twin.event);	
	else if(CUtil.varok(window.event))
		return(window.event);
	else
		return(e);
 }
 
 CUtil.getEventSrc = function(e)
 {
	if(CUtil.varok(e.target))
		return(e.target);
	else if(CUtil.varok(e.srcElement))
		return(e.srcElement);
	else
		return(false);
 }
 
 CUtil.getEventDestSrc = function(e)
 {
	if(CUtil.varok(e.toElement))
		return(e.toElement);
	else if(CUtil.varok(e.relatedTarget))
		return(e.relatedTarget);
	else
		return(false);
 }
 
 CUtil.getClassName = function(ob)
 {
	var c = ob.getAttribute("ClassName");
	return(((c) ? (c) : (ob.getAttribute("class"))));
 }
 
 CUtil.getNeighbourByName = function(ob,nm,tagn,nests,depth)
 {
	if(!CUtil.varok(nests)) nests = 1;
	if(!CUtil.varok(depth)) depth = true;
	try { for(var x=0;(x<nests); ) { ob=ob.parentNode; if(ob.tagName==tagn) { x++; } } } catch(e) {}
	return(CUtil.getChildByName(ob,nm,tagn,depth));
 }
 
 CUtil.getNeighbourByClass = function(ob,nm,tagn,nests,depth)
 {
	if(!CUtil.varok(nests)) nests = 1;
	if(!CUtil.varok(depth)) depth = true;
	for(var x=0; x<nests; x++) { ob=ob.parentNode; }
	return(CUtil.getChildByClass(ob,nm,tagn,depth));
 }

CUtil.toggleViewByA = function(anob,visTXT,hidTXT,divob)
{
	if(divob.style.display=='none')
	{
		anob.innerHTML=hidTXT;
		divob.style.display='block';
	}
	else
	{
		anob.innerHTML=visTXT;
		divob.style.display='none';
	}
}

CUtil.switchDivViewIn = function(p,hd,vd)
{
	for(var h in hd)
	{
		var hdelm = CUtil.getChildByName(p,hd[h],'DIV',true);
		if(hdelm)
			hdelm.style.display='none';
	}

	for(var v in vd)
	{
		var vdelm = CUtil.getChildByName(p,vd[v],'DIV',true);

		if(vdelm)
			vdelm.style.display='block';
	}
}

CUtil.toggleDivInDOM = function(sob,dname,upc)
{
	var divob = CUtil.getNeighbourByName(sob,dname,'DIV',upc,true);
	if(divob)
	{
		if(divob.style.display=='none')
			divob.style.display='block';
		else
			divob.style.display='none';
	}
}

CUtil.roundFloat = function(f, p)
{
	var base10P = Math.pow(10, p);
	return((Math.round(f*base10P)/ base10P).toFixed(p));
} 

 CUtil.getParentByClass = function(ob,nm)
 {
	try
	{
		if(CUtil.varok(ob.parentNode))
		{
			if(CUtil.getClassName(ob.parentNode)==nm)
				return(ob.parentNode);
			
			return(CUtil.getParentByClass(ob.parentNode,nm));
		}			
	} catch(err) {}
	
	return(false);
 }

 CUtil.deepestSoleChild = function(ob)
 {
	try
	{
		if(!CUtil.varok(ob.childNodes[0].tagName))
			return(ob);
		else
			return(CUtil.deepestSoleChild(ob.childNodes[0]));
	}
	catch(e)
	{
		CUtil.log("deepestSoleChild: " + e.message);
	}
	
	return(ob);
 }
 
 CUtil.getParentByName = function(ob,nm)
 {
	try
	{
		if(CUtil.varok(ob.parentNode))
		{
			if(ob.parentNode.getAttribute("name")==nm)
				return(ob.parentNode);
			
			return(CUtil.getParentByName(ob.parentNode,nm));
		}			
	} catch(err) {}
	
	return(false);
 }
 
CUtil.getChildByName = function(ob,nm,tagn,depth)
{
	try
	{
		for( var x = 0; ob.childNodes[x]; x++ )
		{
			//if(ob.childNodes[x].tagName) alert(ob.childNodes[x].tagName);
			
			if(ob.childNodes[x].tagName==tagn && ob.childNodes[x].getAttribute("name")==nm)
				return(ob.childNodes[x]);
			else if(depth)
			{
				var ret = CUtil.getChildByName(ob.childNodes[x],nm,tagn,depth);
				if(ret!=false) return(ret);
			}
		}
	} catch(err) { }
	
	return(false);
}

CUtil.getChildByClass = function(ob,cln,tagn,depth)
{
	try
	{
		for( var x = 0; ob.childNodes[x]; x++ )
		{
			//if(ob.childNodes[x].tagName==tagn) 
			
			if(ob.childNodes[x].tagName==tagn && CUtil.getClassName(ob.childNodes[x])==cln)
				return(ob.childNodes[x]);
			else if(depth)
			{				
				var ret = CUtil.getChildByClass(ob.childNodes[x],cln,tagn,depth);
				if(ret!=false) return(ret);
			}
		}
	} catch(err) {}
	
	return(false);
}

CUtil.applyToChildNodes = function(ob,tagn,depth,func)
{
	try
	{
		//alert(ob.childNodes.length);
		for( var x = 0; ob.childNodes[x]; x++ )
		{			
			if(ob.childNodes[x].tagName==tagn)
				func(ob.childNodes[x]);
			
			if(depth)
				CUtil.applyToChildNodes(ob.childNodes[x],tagn,depth,func);
		}
	} catch(e) {CUtil.log(e.message);}	
}

CUtil.applyToMultiChildNodes = function(ob,tags,depth,func)
{
	try
	{
		for( var x = 0; ob.childNodes[x]; x++ )
		{			
			for( var i in tags)
			{
				if(ob.childNodes[x].tagName==tags[i])
					func(ob.childNodes[x]);
			}
			
			if(depth)
				CUtil.applyToMultiChildNodes(ob.childNodes[x],tags,depth,func);
		}
	} catch(e) {CUtil.log(e.message);}	
}

CUtil.applyToAllChildNodes = function(ob,depth,func)
{
	try
	{
		//alert(ob.childNodes.length);
		for( var x = 0; ob.childNodes[x]; x++ )
		{			
			func(ob.childNodes[x]);			
			if(depth) CUtil.applyToAllChildNodes(ob.childNodes[x],depth,func);
		}
	} catch(e) {CUtil.log(e.message);}	
}

CUtil.getChildById = function(ob,id,tagn,depth)
{
	try
	{
		for( var x = 0; ob.childNodes[x]; x++ )
		{
			if(ob.childNodes[x].tagName==tagn && ob.childNodes[x].getAttribute("id")==id)
				return(ob.childNodes[x]);
			else if(depth)
			{
				var ret = CUtil.getChildById(ob.childNodes[x],id,tagn,depth);
				if(ret!=false) return(ret);
			}
		}
	} catch(e) {CUtil.log(e.message);}
	
	return(false);
}

CUtil.forwardPadByZeros = function(n,pc)
{
	n = String(n);
	for(var i=n.length;i<pc;i++)
		n ="0" + n;
	
	return(n);
}

CUtil.getNow = function()
{
	var now = new Date();	
	return(CUtil.forwardPadByZeros(now.getHours(),2) + ":" + CUtil.forwardPadByZeros(now.getMinutes(),2) + ":" + CUtil.forwardPadByZeros(now.getSeconds(),2));
}

//but: 1:2:3=l:m:r & ie= 1:4:2 l:m:r
	
CUtil.isMMButtonReleased = function(e) //only works in IE (FF doesn't give us this info)
{
	return((CUi._isIE)&&(e.button==0));
}

CUtil.getEventKeyCode= function(e)
{
	return(e.keyCode);
}

CUtil.isOkCode = function(e,ct,cf,ce1)
{
	var c = CUtil.getEventKeyCode(e);
	return( (c>=ct && c<=cf) || (c==8) || (c==ce1) );
}

CUtil._M_RETR_IMG_STORE = new Array();

CUtil.dynRetrImg = function(iname,isrc)
{
	try 
	{
		//TODO: maybe a good idea to check oncomple asyncronously
		CUtil._M_RETR_IMG_STORE[iname] = CUi.doc.createElement('img');
		CUtil._M_RETR_IMG_STORE[iname].setAttribute("name",iname);
		CUtil._M_RETR_IMG_STORE[iname].src = (isrc);
		CUtil._M_RETR_IMG_STORE[iname].border=0;
	} catch(e) {}
}

CUtil.cutBodyHTML = function(html)
{
	var startBodyIndex = html.toUpperCase().indexOf("<BODY>"),
		endBodyIndex = html.toUpperCase().indexOf("</BODY>");

	if( startBodyIndex >= 0 && endBodyIndex > 0 )
		html = html.substring( startBodyIndex + 6,endBodyIndex );
	else if( startBodyIndex >= 0 && endBodyIndex == -1 )
		html = html.substring( startBodyIndex + 6 );
	
	return(html);
}

CUtil.varok = function(v)
{
	return((typeof(v) != "undefined") && (v!=null));
}

CUtil.hexMap = "0123456789ABCDEF";
CUtil.dec2hex = function(d)
{
	var h = CUtil.hexMap.substr(d&15,1);
	while(d>15) {d>>=4;h=CUtil.hexMap.substr(d&15,1)+h;}
	return(h);
}

CUtil.getRealColourHex = function(c)
{
	if(c.slice(0,3) == 'rgb')
	{
		var rgb = c.slice(4).split(',');
		return('#'+CUtil.dec2hex(parseInt(rgb[0]))+CUtil.dec2hex(parseInt(rgb[1]))+CUtil.dec2hex(parseInt(rgb[2])));
	}
	else
		return(c.toUpperCase());
}

CUtil.strTrim = function(s)
{
	return s.replace(/^\s*/, "").replace(/\s*$/, "");
}

CUtil.isTimeStr = function(s)
{
	return(s.match(/[0-9]+[:][0-9]+[:][0-9]+/)==s);
}

CUtil.getSafeVal = function(s)
{
	return((((typeof s =="string")&&(s.indexOf("NaN")==-1))?(s):('')));
}

// This will return true even if the first part of n is a number; i.e: 1234AA will eq true
CUtil.isNumber = function(n)
{
   var _n=parseInt(n);
   return(!isNaN(_n));
}

CUtil.isKeyEnterPressed = function(e)
{
	e = CUtil.ensureRealEvent(e);
	return(CUtil.getEventKeyCode(e) == CUtil.M_KEY_CODE_ENTER);
}

CUtil.logDTFormat = "";
CUtil.logHist = new Array();
CUtil.logViewID = "LOGVIEW";

CUtil.log = function(msg)
{
	CUtil.logHist.push(new Array(CUtil.getNow(), msg));
	
	if(CUtil.logHist.length>1000)
		CUtil.logHist.splice(0,1);		
}

CUtil.displayLog = function()
{
	var gl = new CGrid(CUtil.logViewID,false,false);
	
	gl.addCol("ts",140,"Time");
	gl.addCol("msg",300,"Log");
	
	var rvlog = CUtil.logHist.reverse();
	for(var ix in  CUtil.logHist)
		gl.addRow(ix,CUtil.logHist[ix]);
		
	gl.displayGridInWindow(CUtil.logViewID,CUtil.logViewID,"Log",405,200);
}

CUtil.parse_head_html = function(hhtml)
{
	alert(hhtml);
	CUtil.parse_head_script_html(hhtml, 0);
}

CUtil.parse_head_script_html = function(hhtml, mstart)
{	
	var stag = "<SCRIPT>", slen = stag.length;	
	var rege = /[<]script .*?[>]/gim;
	rege.lastIndex = mstart;
	var schtml = rege.exec(hhtml);
	if(schtml)
	{
		var rege_src = /src=['"].*?['"]/gi;
		var sc_src = rege_src.exec(schtml);		
	}
	alert(schtml);
}

CUtil.dynLinkScrip = function(scripSrc,cb)
{
	var head= document.getElementsByTagName('head')[0];
	
	CUtil.applyToChildNodes(head,"SCRIPT",false,function(ob) { 
		if(ob.getAttribute('src')==scripSrc)
			head.removeChild(ob);
	} );
	
   var script = document.createElement('script');
   script.type = 'text/javascript';
   script.src = scripSrc;   
   
   //TODO: current only supported in FF; use onreadystatechange for IE.
   script.onload=function() { cb(); }
   
   head.appendChild(script);
}

CUtil.dynSourceScrip = function(scripSrc,cb)
{
	var head= document.getElementsByTagName('head')[0];
	
	var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = scripSrc;   
   
   //TODO: current only supported in FF; use onreadystatechange for IE.
   script.onload=function() { cb(); }
   
   head.appendChild(script);
}

CUtil.serializeAll = function(oarr,excf)
{
	var szfo = '';
	
	for(var i in oarr)
		if(typeof oarr[i] == 'object')
			{ szfo += CUtil.serialize(oarr[i],true); }
			
	return(szfo);
}
		
CUtil.arrKeyJoin = function(arr,glue)
{
	var ret = "";
	for(var key in arr)
		ret += key + glue;
	
	if(ret=="")
		ret = ret.slice(0,-1);
		
	return(ret);
}

CUtil.arrKeySplit = function(ser,glue,allval)
{
	var parts = ser.split(glue);
	var ret = new Array();
	for(var i in parts)
	{
		if((parts[i]!="") && (parts[i].length!=0))
			ret[parts[i]] = allval;
	}
	
	return(ret);
}

CUtil.toBool = function(v) //Boolean(String(false))!=false the madness that is JS!
{
	if(v=="false")
		return(false);
	else if(v=="true")
		return(true);
	else
		return(Boolean(v));
}

CUtil.getCleanArray = function(asrc)
{
	var adst = new Array();
	for(var i in asrc)
	{
		if((i.length>0) && (asrc[i].length>0) && (asrc[i]!=""))
			adst[i] = asrc[i];
	}
	return(adst);
}

CUtil.cloneHeadEx = function(src,dst)
{
	var head_src = src.document.getElementsByTagName('head')[0];
	var head_dst = dst.document.getElementsByTagName('head')[0];
	
	for(var x=0; head_src.childNodes[x]; x++)
		head_dst.appendChild(head_src.childNodes[x].cloneNode(true));	
}

CUtil.spawnx = function(f,tot)
{
	setTimeout( f, tot);
}

CUtil.startsWith = function(str,chk)
{
	return(str.slice(0,chk.length) == chk);
}
CUtil.waitForIt = function(t,cb,prm)
{
	var exitNow = false;
	
	setTimeout( function() {
		exitNow = (cb(prm));
		if(!exitNow)
			CUtil.waitForIt(t,cb,prm);
	}, t);
}

CUtil.POP_IMG_ID = 'ABS_POP_IMG';

CUtil.popImg = function(uri,s,w,h)
{
	if((!CUtil.varok(w))||(!CUtil.varok(h)))
	{
		var p = uri.split('_');
		
		if((p.length>=2)&&(CUtil.isNumber(p[p.length-1]))&&(CUtil.isNumber(p[p.length-2])))
			{ w = parseInt(p[p.length-2]); h = parseInt(p[p.length-1]); }
		else
			return false;
	}
	
	if(CUtil.varok(s))
		{ w *= s; h *= s; }
	
	var l = ((CUi._mouseX + CUi.doc.body.scrollLeft) - (w/2));
	var t = ((CUi._mouseY + CUi.doc.body.scrollTop) - (h/2));
	
	if((t+h)>(CUi.doc.body.scrollTop+CUi.doc.body.clientHeight))
		t=(CUi.doc.body.scrollTop+CUi.doc.body.clientHeight)-h;
	
	if((l+w)>(CUi.doc.body.scrollLeft+CUi.doc.body.clientWidth))
		l=(CUi.doc.body.scrollLeft+CUi.doc.body.clientWidth)-w;
		
	if(l<0) l=0; if(t<0) t=0;
	
	var eldiv = CUi.doc.createElement('div');
	eldiv.setAttribute('id',CUtil.POP_IMG_ID);
	eldiv.setAttribute('name',CUtil.POP_IMG_ID);
	eldiv.style.display='block';
	eldiv.style.padding = '5px';
	eldiv.style.border = '#888 1px solid';
	eldiv.style.backgroundColor = '#aaa';
	eldiv.style.position='absolute';
	eldiv.style.left = l; eldiv.style.top = t;
	eldiv.innerHTML = 'loading...';	
	
	var elimg = CUi.doc.createElement('img');
	elimg.src = uri; elimg.border=0;
	elimg.width=w; elimg.height=h;
	elimg.style.cursor = ((CUi._isFF) ? ("pointer") : ("hand"));
	elimg.onclick = function() { eldiv.parentNode.removeChild(eldiv) }
	
	CUi.doc.body.appendChild(eldiv);
		
	CUtil.waitForIt( function() {
		if(elimg.complete)
		{
			try 
			{
				eldiv.innerHTML='';
				eldiv.style.background ='#fff url(images/2thatch.gif) repeat scroll top left';
				eldiv.appendChild(elimg);
			} catch(e) {}
			
			return(true);
		}
		else
			return(false);
	} );
}

CUtil.strsub = function(str,nedl,sub)
{
	var i=-1; //alert(typeof str);
	if ((i=str.indexOf(nedl)) > -1)
		return(str.substring(0,i)+sub+str.substring(i+sub.length));
	else
		return(str);
}

CUtil.strsubex = function()
{
	var args=CUtil.strsub.arguments;
	var Base=args[0];
	var Seek,Len,ix1,ix2,ix3;
	for (ix1=1; ix1<args.length; ix1++)
	{
		ix2=ix1-1;
		Seek='{'+ix2+'}';
		if ((ix3=Base.indexOf(Seek)) > -1)
		{
			Len=Seek.length;
			Base=Base.substring(0,ix3)+args[ix1]+Base.substring(ix3+Len);
		}
	}
	return Base;
}

CUtil.absIX = 1;

CUtil.addFileUp = function(ancClass,stxt,cid,bid,ifix)
{
	var cob = document.getElementById(cid);	

	if(cob)
	{
//		var divs = cob.getElementsByTagName('input');
		var mID = ifix + CUtil.absIX++;
		var newI = document.createElement('div');
		newI.setAttribute('name','filecont');
		newI.setAttribute('class','gencon'); //IE doesn;t support this technique..
		qh=CUi.getAgentVal("<div class=gencon>",'') + "<div class=gensideblock><div class=txt>" + stxt + "</div></div><div class=gensideblock><input type=file name='" + mID + "' size=30 /></div><div class=gensideblock><a class='" + ancClass + "' href=# onclick=\"var p=CUtil.getParentByName(this,'filecont');p.parentNode.removeChild(p);CUtil.rmvFileUpSBut('"+cid+"','"+bid+"');return(false);\">Remove</a></div>" + CUi.getAgentVal('</div>','');

		newI.innerHTML=qh;
		cob.appendChild(newI);	
		CUtil.rmvFileUpSBut(cid,bid);
	}
}

CUtil.rmvFileUpSBut = function(cid,bid)
{
	var cob = document.getElementById(cid);	
	var bob = document.getElementById(bid);	

	if(cob && bob)
	{
		if(cob.childNodes.length==0 && bob.style.display!='none')
			bob.style.display='none';
		else if(bob.style.display!='block')
			bob.style.display='block';
	}
}

CUtil.setHand = function(ob,doit)
{
	if(doit)
	{
		ob.style.cursor=((CUi._isIE)?('hand'):('pointer'));
	}
	else
		ob.style.cursor='default'; 
}

CUtil.getDim = function(bwidth,safety)
{
	if(!CUtil.varok(safety))
		safety = 0;
		
	if(bwidth)
		return(CUi.doc.body.clientWidth-safety);
	else
		return(CUi.doc.body.clientHeight-safety);
}

CUtil.markAll = function(ids,m)
{
	var els = CUtil.getElementsById(ids);

	for(var x=0; x< els.length;x++)
	{
		els[x].checked=m;
	}
}

CUtil.mergeAssoArr = function(asrc, atmpl)
{
	console.log( atmpl );
	console.log( asrc );
	var p = atmpl;
	for(var i in asrc)
	{
		p[i] = asrc[i];
	}	
	return(p);
}

CUtil.isfunc = function(f)
{
	return(typeof(f) == 'function');
}

CUtil._decimalNumersOnlyCB= function(e)
{
	e = CUtil.ensureRealEvent(e);
	return( CUtil.isNumerPart( CUtil.getEventCharCode( e ), true ) || CUtil.isInputControlKey( CUtil.getEventKeyCode( e ) ) );
}
	
CUtil._numersOnlyCB= function(e)
{	
	e = CUtil.ensureRealEvent(e);
	return( CUtil.isNumerPart( CUtil.getEventCharCode( e ), false ) || CUtil.isInputControlKey( CUtil.getEventKeyCode( e ) ) );
}

CUtil._timeOnlyCB= function(e)
{	
	e = CUtil.ensureRealEvent(e);
	return( CUtil.getEventCharCode( e ) == CUtil.M_KEY_CODE_COLON || CUtil.isNumerPart( CUtil.getEventCharCode( e ), false ) || CUtil.isInputControlKey( CUtil.getEventKeyCode( e ) ) );
}

CUtil.isNumerPart= function(kc, bAllowDecimal, numRange)
{
	if( bAllowDecimal && (kc == ".".charCodeAt(0) || kc == "-".charCodeAt(0)))
		return(true);
	else
	{
		if(!CUtil.isdef(numRange))
			numRange = "09";
		
		return( (kc >= numRange.charCodeAt(0)) && (kc <= numRange.charCodeAt(1)) );
	}
}

CUtil.getEventCharCode= function(e)
{
	return(e.charCode || e.keyCode);
}

CUtil.isInputControlKey= function(kc)
{
	//TODO: DEL KEY AND DECIMAL KEY SAME KEYCODE ; NEED  TO DISTINGUISH!
	return( (kc==CUtil.M_KEY_CODE_BACK) || (kc == CUtil.M_KEY_CODE_TAB) || (kc==CUtil.M_KEY_CODE_DEL) || (kc==CUtil.M_KEY_CODE_LEFT_ARROW) || (kc==CUtil.M_KEY_CODE_RIGHT_ARROW) || (kc==CUtil.M_KEY_CODE_BOTTOM_ARROW) || (kc==CUtil.M_KEY_CODE_TOP_ARROW) );
}

CUtil.isdef = function(v)
{	
	return(typeof(v) != "undefined");
}

try {
CUtil._M_FB = CUtil.varok(console.debug);
} catch(e) { CUtil._M_FB = false; }


CUtil.logFB = function(msg)
{
if(CUtil._M_FB) { console.debug(CUtil.getNow() + ": " + msg); }
}
