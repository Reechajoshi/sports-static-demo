// JS chat with server
///////////////////////////////////////////////////////////////////////////////////////////////////

CTalk = window.prototype = {
	_M_IO_UNIX_BYTE_NL: String.fromCharCode(10),
	_WAIT_ASYNC_REQ: 60000,
	
	_M_IFR_DIV_ID: '_ifr_div_hid',
	
	inAsyncReq: false,
	postFailTO: false,
	
	getHTTPReq: function()
	{
		var req = false;
		
		try
		{
			req = new XMLHttpRequest();
		}
		catch(err)
		{
			try
			{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(err)
			{
				try
				{
					req = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(err) {}				
			}
		}
		
		return(req);
	},

	_M_POST_BACKLOG: new Array(),
	_M_POST_CUR_REQ: false,
	
	//HACK: THIS SHOULD ALSO CHECK URL; BUT SINCE MAINLY USED FOR DEALING, DO NOT.. THUS NOT TRUELY A LIB
	isReqInProcOrQue: function(reqx)
	{		
		if(CTalk._M_POST_CUR_REQ && ( CTalk._M_POST_CUR_REQ.dvalue == reqx.dvalue ) )
			return(true);
		else
		{
			for(var xi in CTalk._M_POST_BACKLOG)
			{	
				if( CTalk._M_POST_BACKLOG[xi].dvalue == reqx.dvalue )
					return(true);
			}
		
			return(false);
		}
	},
	
	sendSimplePost: function(url, cb)
	{
		CTalk.sendPost(url,null,null, cb);
	},
	
	sendPost: function(url,dname,dvalue,cb,onQuedCB)
	{
		var req = { url: url, dname: dname, dvalue: dvalue, cb: cb };
			
		if(CTalk.inAsyncReq == false)
			CTalk.__sendPost(req);
		else
		{
			if(CTalk.isReqInProcOrQue(req))
				return(false);
			else
			{
				CTalk._M_POST_BACKLOG.push( req );
				
				if(CUtil.isfunc(onQuedCB))
					onQuedCB();
			}
		}
		
		return(true);
	},	
		
	__checkPostMore: function()
	{
		if(CTalk._M_POST_BACKLOG.length>0)
		{
			var nt = CTalk._M_POST_BACKLOG[0];			
			CTalk._M_POST_BACKLOG.splice(0,1); //removeed index zero
		
			CTalk.__sendPost(nt);
		}
		else
			CTalk.inAsyncReq = false; 
	},
	
	__sendPost: function(req)
	{
		CTalk._M_POST_CUR_REQ = req;
		
		var url = req.url,
			dname = req.dname,
			dvalue = req.dvalue,
			cb = req.cb;
			
		CTalk.inAsyncReq = true; 
		CTalk.postCBDone = false; 
		
		var postCB = function(resp) {
			if(CTalk.postCBDone == false)
			{
				CTalk.postCBDone = true;
				CTalk._M_POST_CUR_REQ = false;
				
				try { cb(resp); } catch(e) {}
			
				CTalk.__checkPostMore();
			}
		}
		
		//ASSUMTION: WE ONLY USE XHR
		//if(CUi._isIE)
			CTalk.__sendPost_XHR(url,dname,dvalue,postCB);
		//else
		//	CUtil.spawnx( function() { CTalk.__sendPost_IFR(url,dname,dvalue,postCB); } );
	},
	
	__ifr_parent: null,
	__ifrCallBack: null,
	
	__sendPost_IFR: function(url,dname,dvalue,postCB)
	{
		try
		{
			var ht = '<iframe src="' + url + '?' + dname + '=' + escape(dvalue) + '" ></iframe>';
			
			CTalk.__ifrCallBack = function(dat)
			{				
				CUi.bod.removeChild(CTalk.__ifr_parent);	
				
				postCB(dat);
				
				CTalk.__ifrCallBack = null;
				CTalk.__ifr_parent = null;
			};
			
			CTalk.__ifr_parent = CUi.createElm('div',CTalk._M_IFR_DIV_ID);
			
			CTalk.__ifr_parent.style.display='none';
			CTalk.__ifr_parent.innerHTML = ht;
			
			CUi.bod.appendChild(CTalk.__ifr_parent);
		}
		catch(e)
		{	
			if(CTalk.postFailTO)
				{ clearTimeout(CTalk.postFailTO); CTalk.postFailTO = false }			
				
			postCB("ERROR: " + e.message);
		} 
	},
	
	__sendPost_XHR: function(url,dname,dvalue,postCB)
	{
		try
		{			
			var reqStage1OK = false;
			var req = CTalk.getHTTPReq();
			
			CTalk.postFailTO = setTimeout( function() { 
				postCB(false);
				req.abort();
			}, CTalk._WAIT_ASYNC_REQ);
							
			req.onreadystatechange = function()
			{
				try
				{
					if(req) 
					{ 
						if(req.readyState==1)
							reqStage1OK = true;
						else if(req.readyState==4)						
						{
							if(CTalk.postFailTO)
								{ clearTimeout(CTalk.postFailTO); CTalk.postFailTO = false }			
								
							if(req.status==200)
							{
								postCB(req.responseText);
							}
							else
							{
								postCB("ERROR: " + req.status)
							}
						}
					}
				} catch(e) { 
					CUtil.log('onr catch ' + e.message);
					if(CTalk.postFailTO)
						{ clearTimeout(CTalk.postFailTO); CTalk.postFailTO = false }		
							
					postCB(false);
				} 
			}
			
			if(CUtil.varok(dname) && CUtil.varok(dvalue) && dname!="")
			{
				req.open("POST", url, true);
				req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				
				if( typeof(dname) == "object" )
				{
					var post_string = "";
					for( var ix = 0; ix< dname.length; ix++)
					{
						post_string += ( dname[ix]+"="+escape(dvalue[ix]) );
						if( (ix + 1) != dname.length )
							post_string += "&";
					}
					
					req.send( post_string );
				}
				else
					req.send(dname+"="+escape(dvalue));
			}
			else
			{
				req.open("GET", url, true);
				req.send();
			}
			
			CUtil.log("__sendPost done");
		}
		catch(e)
		{	
			if(CTalk.postFailTO)
				{ clearTimeout(CTalk.postFailTO); CTalk.postFailTO = false }			
				
			postCB("ERROR: " + e.message);
		} 
	},
	
	_M_STREAM_INTERVAL_CHECK: 7000,
	
	_M_STREAM_RECON_MAX: 5,

	streamOB: null,
	streamCheckTimer: false,
	streamConAttempts: 0,
	streamLastTickAt: 0,
	streamExplicitStop: false,
	
	stopChannel: function()
	{
		if( CTalk.streamCheckTimer )
		{
			clearInterval( CTalk.streamCheckTimer );
			CTalk.streamCheckTimer = false;
		}
		
		if(CTalk.streamOB)
		{
			try { 
				CTalk.streamOB.abort();
				CTalk.streamOB.onreadystatechang = null;
				CTalk.streamOB = null;				
				CTalk.streamExplicitStop = true;
			} catch(exc) {  }
		}
	},
	
	//NOTE: Will not work with IE
	initChannel: function(push_cgi,dname,dvalue,cb)
	{
		try
		{	
			var runningFresh = true; // set to true 
			CTalk.streamExplicitStop = false;
			
			if(CTalk.streamOB)
			{
				CUi.logFB('calling abourt of stream object');
				CTalk.streamOB.abort();
			}
				
			if( CTalk.streamCheckTimer )
			{
				CUi.logFB('on start clearing interval');
				clearInterval( CTalk.streamCheckTimer );
				CTalk.streamCheckTimer = false;
			}			
			
			var reConFunc = function()
			{			
				if(!CTalk.streamExplicitStop)
				{
					if( CTalk.streamCheckTimer )
					{
						CUi.logFB('clearing check timer');
						clearInterval( CTalk.streamCheckTimer );
						CTalk.streamCheckTimer = false;
					}
					
					if(++CTalk.streamConAttempts >= CTalk._M_STREAM_RECON_MAX)
					{
						if(CTalk._m_channelBrokenCB)
						{
							CUi.logFB('done calling CTalk._m_channelBrokenCB');
							CTalk._m_channelBrokenCB();
						}
					}
					else
					{
						CUi.logFB('recalling CTalk.initChannel 2');
						CTalk.initChannel(push_cgi,dname,dvalue,cb);
					}
				}
				else
				{
					CUi.logFB('explicit stop is on');
				}
			},
			sendCB = function(resp)
			{			
				if(resp.length > 0)
				{
					CTalk.streamLastTickAt = (new Date());
					CTalk.streamConAttempts = 0;
					
					if(runningFresh)
						CUi.logFB('calling stream CB on fresh');
						
					//CUi.logFB('stream last tick ' + CTalk.streamLastTickAt);
					cb(resp , runningFresh);					
					runningFresh = false;
				}
			};
		
			CUi.logFB('setting stream check timer');
			
			CTalk.streamCheckTimer = setInterval( function() {
				var ndiff = (new Date()) - CTalk.streamLastTickAt;
				
				if( ndiff > CTalk._M_STREAM_INTERVAL_CHECK )
				{
					reConFunc();
				}
			} , CTalk._M_STREAM_INTERVAL_CHECK );
		}
		catch(e)
		{
			CUi.logFB('talk exception: ' + e.message);
		}
		
		try
		{
			var lastResp = '';
						
			if(!CTalk.streamOB)
			{				
				CTalk.streamOB = CTalk.getHTTPReq();
			}
				
			CTalk.streamOB.multipart = true;
			
			CTalk.streamOB.open("POST",push_cgi,true);
			CTalk.streamOB.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			
			if( CUi._isFF )
			{
				CTalk.streamOB.onreadystatechange = function() {
					if(CTalk.streamOB != null)
					{
						if( CTalk.streamOB.readyState == 4 && CTalk.streamOB.status == 200 )
						{							
							sendCB(CTalk.streamOB.responseText);
						}
					}
				};
			}
			else if( CUi._isCH || CUi._isSF )
			{
				CTalk.streamOB.onreadystatechange = function() {
					if(CTalk.streamOB != null)
					{
						if( (CTalk.streamOB.readyState == 3) && ( CTalk.streamOB.status == 0 ) || (CTalk.streamOB.status == 200) ) // Handle incoming data status=0 in CH and 200 in SF
						{
							var resp = CTalk.streamOB.responseText,
								newResp = resp.substring(lastResp.length);
							
							sendCB(newResp);
							
							lastResp = resp;
						}
						else if( CTalk.streamOB.readyState == 4 ) //indicates completed request, which should not happen, so reconnect
							reConFunc();
					}
				};
			}
			
			CUi.logFB("sneding stream request.");
			CTalk.streamOB.send(dname+"="+escape(dvalue));			
		}
		catch(e)
		{
			CUi.logFB("error x: " + e.message);			
		}
	},
	
	_m_channelBrokenCB: null,
	setChannelBrokenCB: function(cb)
	{
		CTalk._m_channelBrokenCB = cb;
	}
};