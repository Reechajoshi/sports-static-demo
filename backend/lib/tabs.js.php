
CTabs = window.prototype = {
	_M_SAFE_CLOSE_MAP: new Array(),
	_M_ROUND_CORNER_DIRECTIVE: "-webkit-border-radius: 12px;-moz-border-radius: 12px;border-radius: 12px;",
		
	_M_TAB_CON_FIX: "__tab_",
	_M_TAB_DEF_HEIGHT: 20,

	_M_HTML_REM_TAGS: new Array("SELECT","INPUT"), //must be value assosciated. todo: enhance to provide attribute to remember for future now only value.

	/*_M_IMG_TABL: new Image(),
	_M_IMG_TAB: new Image(),
	_M_IMG_TABR: new Image(),
	_M_IMG_TABC: new Image(),*/

	_M_TAB_TYPE_HTML: 1,
	_M_TAB_TYPE_IFRAME: 2,
	_M_TAB_TYPE_CALLBACK: 3,

	allTabs: new Array(),
	resizeTab: false, //ONLY ONE RESIZE TAB CAN EXISTS
	
	init: function()
	{
/*		CTabs._M_IMG_TABL.src = "images/tabl.gif";
		CTabs._M_IMG_TAB.src = "images/tabt.gif";
		CTabs._M_IMG_TABR.src = "images/tabr.gif";
		CTabs._M_IMG_TABC.src = "images/tabclose.gif";*/
	},
	
	tabs: function(tabconid, params)
	{
		this._id = tabconid;
		this._realId = CTabs._M_TAB_CON_FIX + tabconid;
		this.setupParams(params);
		
		if(this._def.useFullArea)
		{
			CUi.regWinResizeCB( CTabs.resizeCB );
			CTabs.resizeTab = this._id;
			
			//CUi.regWinResize(0,2,{ id: this._realId + 'tabArea', doWidth:true, widthOffset: 0, doHeight: true, heightOffset: 0});
			//CUi.regWinResize(1,1,{ id: this._realId, doWidth:true, widthOffset: 0, doHeight: true, heightOffset: this._def.areaBaseMargin});
		}
	
		this._tabs = new Array();
		this._assoTabTags = new Array();
		this._curSelTabI = false;

		CTabs.allTabs[this._id]=this;
	},	
	
	resizeCB: function(e)
	{		
		if( CUtil.varok( CTabs.allTabs[ CTabs.resizeTab ] ) )
		{
			CTabs.allTabs[ CTabs.resizeTab ].handleResize(e);
		}
	},
	
	regSafeClose: function(tid,ctxt)
	{
		CTabs._M_SAFE_CLOSE_MAP[tid] = ctxt;
	},
	
	getTabObject: function(id)
	{
		if(CUtil.varok(CTabs.allTabs[id]))
			return(CTabs.allTabs[id]);
		else
			return(false);
	},

	shiftTabY: function(me, y,lt,t,rt)
	{				
		if(lt.style.zIndex!=30)
		{
			y *= 3;
			var __func = function(o) {
				o.style.top = y; 
				if(y==0) {
					o.style.height=me._def.tabHeight + 'px';
					CUtil.setHand(o,false);
				} else { 
					o.style.height =  parseInt(o.style.height) - y; 
					CUtil.setHand(o,true);
				}
			}
			__func(lt); __func(t); __func(rt);	
			//lt.style.cursor='pointer'; //setAttribute('id','acur');
		}
	}
};

CTabs.prototype = CTabs.tabs.prototype = {

	setupParams: function(p)
	{
		this._def = {			
			width: CUtil.getDim(true),
			height: CUtil.getDim(false),
			areaTopMargin: 0,
			areaTopMarginCB: function(me,stab) { return(''); },
			areaBaseMargin: 0,
			areaBackGroundColour: '#fff',
			areaBaseMarginCB: function(me,stab) { return(''); },
			useFullArea: false,
			useFullAreaXOff: 10,
			tabBackground: 'transparent',
			tabHeight: CTabs._M_TAB_DEF_HEIGHT,
			tabHeadTopPad: 7,
			tabViewWidthOffset: 0,
			tabHeadBackGroundColour: '#fff',
			tabSelectCB: function(me,fromtab,totab) {},
			borderUnSelect: '#9D9D9D 1px solid', //this style must coisnide with image border colours for each tab to make any sense.
			tabSideIsOn: false,
			tabRowLeftOffset: 0,
			tabSideCB: function(me) { return(''); },
			roundCorners: true
		};
		
		this._def._tabHeadTopPad_FIX_IE = (this._def.tabHeadTopPad - 1) // THE TOP RELATIVE - value is this IN GETALLTABS IS DEPENDED
		
		var useFullArea = (CUtil.varok(p.useFullArea) && (p.useFullArea));
		for(var i in p)
		{
			if(useFullArea && (i=='width'||i=='height'))
				continue;
			this._def[i]=p[i];
		}
		
		this._def.tabHeight = 24; //this._def.tabHeight + CUi.getAgentVal(8,6);	
		this._def.tabHeadHeight = this._def.tabHeight + CUi.getAgentVal(1,1)
		this._def.topIncluded = false; 
		
		//this._def.width = this._def.width - this._def.areaMargin;		
		//this._def.height = this._def.height - this._def.areaMargin;		
	},
	
	handleResize: function(e,bSecondCall)
	{		
		var telm = this.getTabElm(),
			taelm = this.getTabAreaElm(), //outser element
			tvelm = this.getTabViewElm(),
			ww = CUtil.getDim(true),
			wh = CUtil.getDim(false);

		tvelm.style.height = (wh - this._def.areaBaseMargin - this._def.tabHeadHeight - CUi.getAgentVal(0,5) - CTabs._M_TAB_DEF_HEIGHT - ((this._def.topIncluded)?(this._def.areaTopMargin):(0)));
		telm.style.height = wh - this._def.areaBaseMargin;		
		taelm.style.height = wh;
		
		//--------------
		
		taelm.style.width = (ww - (2*this._def.useFullAreaXOff));
		
		//--------------
		
		if( !CUtil.varok(bSecondCall) || (bSecondCall == false))
		{
			var me = this;
			setTimeout( function() { me.handleResize(e,true); }, 100);
		}
	},
	
	getTabElm: function()
	{
		return(CUi.doc.getElementById(this._realId));
	},
		
	getTabAreaElm: function()
	{
		return(CUi.doc.getElementById(this._realId + 'tabArea'));
	},
	
	getTabViewElm: function()
	{
		return(CUtil.getChildByName(this.getTabElm(),'tabView','DIV',false));
	},
	
	getTabViewTop: function()
	{
		return(CUtil.getChildByName(this.getTabElm(),'areaTop','DIV',true));
	},
	
	getTabViewBase: function()
	{
		return(CUtil.getChildByName(this.getTabElm().parentNode,'areaBase','DIV',false));
	},
	
	modifyTabText: function(tid,ttxt)
	{
		if(tid)
		{
			var tel = CUtil.getChildByName(CUtil.getChildByName(this.getTabElm(),'tabHeadCon','DIV',true),tid,'DIV',false);
			var tin = CUtil.getChildByName(tel,'tabname','DIV',true);
			this._tabs[tid].name = ttxt; 
			tin.innerHTML = ttxt;
		}
	},
	
	tabCount: function()
	{
		return(this._tabs.length);
	},

	tabExists: function(tabid)
	{
		for(var i in this._tabs)
			if(this._tabs[i].id==tabid)
				return(true);
		
		return(false);
	},

	getTabIndexFromID: function(tabid)
	{
		for(var i in this._tabs)
		{
			if(this._tabs[i].id==tabid)
				return(i);
		}
		
		return(null);
	},

	getTabIndexFromName: function(tabname)
	{
		for(var i in this._tabs)
		{
			if(this._tabs[i].name==tabname)
				return(i);
		}
		
		return(null);
	},

	removeTabByIndex: function(i)
	{
		if(CUtil.varok(this._tabs[i]))
		{
			this.removeAssoTag(this._tabs[i].id);
			this._tabs.splice(i,1);
		}
	},

	removeTabById: function(tabid)
	{
		this.removeTabByIndex(this.getTabIndexFromID(tabid));
	},

	updateTabDirect: function(tabid,params)
	{
		for(var i in this._tabs)
		{
			if(this._tabs[i].id==tabid)
			{
				for(var i in params)
					{ this._tabs[i][i] = p[i]; }
				return ; 
			}	
		}
	},

	removeAssoTag: function(tabid)
	{
		if(CUtil.varok(this._assoTabTags[tabid]))
			this._assoTabTags=CUtil.removeNamedIndex(this._assoTabTags,tabid);
	},

	setAssoTag: function(tabid,tabtag)
	{
		this._assoTabTags[tabid] = tabtag;
	},

	getAssoTag: function(tabid)
	{
		if(CUtil.varok(this._assoTabTags[tabid]))
			return(this._assoTabTags[tabid]);
		else
			return(false);
	},

	getTabParams: function(ttype,tid, p)	
	{
		var tp = {
			id: tid,
			type: ttype,
			tabLImage: "images/tabs/tabl.gif",
			tabTImage: "images/tabs/tabt.gif",
			tabRImage: "images/tabs/tabr.gif",
			tabSideWidth: '5px',
			tabCloseImage: "images/tabs/tabclose.gif",
			name: "",
			html: "",
			txtColor: '#28A9D3',
			txtSelectColor: '#28A9D3',
			borderSelect: '#fff 1px solid',
			htmlRemember: false,
			userTabControl: true,
			iframeURL: "",
			iframeRefreshIndicate: false,
			
			callbackFunc: function(x) {},
			callbackParam: null
		};
		
		for(var i in p)
			tp[i] = p[i];
		
		tp.html = "<div>" + tp.html + "</div>";
		return(tp);
	},
	
	addTab: function(tabid, params)
	{
		var p = this.getTabParams(CTabs._M_TAB_TYPE_HTML,tabid,params);
		
		//	this._tabs.push(new Array(CTabs._M_TAB_TYPE_HTML,tabid,tabname,inhtml,true));
			//this._tabs.push(new Array(CTabs._M_TAB_TYPE_HTML,tabid,tabname,inhtml));
		this._tabs.push(p);
		
		return(this.tabCount()-1);
	},

	addIFrameTab: function(tabid,params) //tabname,iurl,doref)
	{
		var p = this.getTabParams(CTabs._M_TAB_TYPE_IFRAME,tabid,params);
		
		this._tabs.push(p);
		//this._tabs.push(new Array(CTabs._M_TAB_TYPE_IFRAME,tabid,tabname,iurl,doref));
		return(this.tabCount()-1);
	},

	addCallBackTab: function(tabid,params) //,tabname,cb,cbval)
	{
		var p = this.getTabParams(CTabs._M_TAB_TYPE_CALLBACK,tabid,params);		
		this._tabs.push(p);
		//this._tabs.push(new Array(CTabs._M_TAB_TYPE_CALLBACK,tabid,tabname,cb,cbval));
		return(this.tabCount()-1);
	},

	indRefIFrame: function(ti)
	{
		if(CUtil.varok(this._tabs[ti]))
		{
			if(this._tabs[ti].type==CTabs._M_TAB_TYPE_IFRAME)
				this._tabs[ti].iframeRefreshIndicate = true;
		}
	},

	checkSafeRemove: function(tid)
	{
		if(CUtil.varok(CTabs._M_SAFE_CLOSE_MAP[this._tabs[tid].id]))
			return(confirm(CTabs._M_SAFE_CLOSE_MAP[this._tabs[tid].id]));
		else
			return(true);
	},

	removeThisTab: function(tabo)
	{
		var __funcFindTabID = function(o) { 
			if(CUtil.isNumber(o.getAttribute('name')))
				return(parseInt(o.getAttribute('name')));
			else if(o.parentNode)
				return(__funcFindTabID(o.parentNode));
			else
				return(false);
		}
		
		var tabid = __funcFindTabID(tabo);
		if(tabid)
		{
			if(tabid==0) //safty
				alert('Cannot remove this TAB');
			else
			{
				if(this.checkSafeRemove(tabid))
				{
					this.clearTabViewOf(tabid);
					this.removeTabByIndex(tabid);
					this.selectNext();
				}
			}
		}	
	},

	selectTabHead: function(selt)
	{
		var hp = CUtil.getChildByName(this.getTabElm(),'tabHeadP','DIV',true);
		if(hp)
			hp.innerHTML = this.getAllTabsHtml(selt);	
	},

	getAllTabsHtml: function(selt)
	{			
		var html = "<div style='overflow:hidden;position:relative;top:" + CUi.getAgentVal(0,1) + "px;background-color:" + this._def.tabHeadBackGroundColour + ";height:" + (this._def.tabHeadHeight + CUi.getAgentVal(0,this._def.tabHeadTopPad)) + "'>";
		html += "<div class=tabHeadCon name=tabHeadCon style='margin-top:" + CUi.getAgentVal(0,1) + "px;background-color:" + this._def.tabHeadBackGroundColour + ";padding-top:"+(this._def.tabHeadTopPad)+"px;height:" + (this._def.tabHeadHeight) + "'>";
		var rbc = 0;
		var ziL = 3;

		var tabUB = this.tabCount() - 1;
		
		if(CUi._isIE)
			{ tabStyleHTML = "style='position:relative;top:-" + this._def._tabHeadTopPad_FIX_IE + "px;'"; }
		else
			{ tabStyleHTML = ""; }
		
		for(var i in this._tabs)
		{					
			if(CUtil.varok(selt) && i==selt)
			{
				html += "<div id=acur class=tab name='"+i+"' " + tabStyleHTML + " onClick=\"" + this.getJSRun('select(this)') + "\" >";
				html += "<div class=tabhL style=\"width:" + this._tabs[i].tabSideWidth + ";height:" + this._def.tabHeight + "px;background: url('" + this._tabs[i].tabLImage + "') no-repeat top left scroll;'\"></div>";
				
				if(this._tabs[i].userTabControl && i!=0)
				{
					html += "<div class=tabh style=\"height:" + this._def.tabHeight + "px;background: url('" + this._tabs[i].tabTImage + "') repeat-x top left scroll;position:relative;left:"+rbc+"px;\"><div class='tabinblock no-selection' name='no-selection'><div name=tabname style='padding-right:3px;padding-left:3px;padding-top:3px;color:"+this._tabs[i].txtSelectColor+";font-size:"+this._tabs[i].txtFontSize+";' >"+this._tabs[i].name+"</div></div>";

					html += "<div class=tabinblock style='display:block;padding-left:2px;position:relative;top:3px'><img id=acur alt=C title=close src='" + this._tabs[i].tabCloseImage + "' border=0 onClick=\"CTabs.allTabs['"+this._id+"'].removeThisTab(this);event.cancelBubble=true; return false;\" style='position:relative;left:0;top:0' /></div></div>";
				}
				else
					html += "<div class=tabh style=\"height:" + this._def.tabHeight + "px;background: url(" + this._tabs[i].tabTImage + ") repeat-x top left scroll;position:relative;left:"+rbc+"px;\"><div class='tabinblock no-selection' name='no-selection'><div name=tabname style='padding-right:3px;padding-left:3px;padding-top:3px;color:"+this._tabs[i].txtSelectColor+";font-size:"+this._tabs[i].txtFontSize+";' >"+this._tabs[i].name+"</div></div></div>";
					
				html += "<div class=tabhR style=\"width:" + this._tabs[i].tabSideWidth + ";height:" + this._def.tabHeight + "px;background: url(" + this._tabs[i].tabRImage + ") no-repeat top right scroll;position:relative;left:"+rbc+"px;\"></div>";
			}
			else
			{
				html += "<div id=acur class=tab name='"+i+"' " + tabStyleHTML + " onClick=\"" + this.getJSRun('select(this)') + "\" >";
				html += "<div class=tabhL style=\"border-bottom: " + this._tabs[i].borderSelect + ";width:" + this._tabs[i].tabSideWidth + ";height:" + this._def.tabHeight + "px;'\"></div>";
				
				if(this._tabs[i].userTabControl && i!=0)
				{
					html += "<div class=tabh style=\"border-bottom: " + this._tabs[i].borderSelect + ";height:" + this._def.tabHeight + "px;position:relative;left:"+rbc+"px;\"><div class='tabinblock no-selection' name='no-selection' ><div name=tabname style='padding-right:3px;padding-left:3px;padding-top:3px;color:"+this._tabs[i].txtColor+";font-size:"+this._tabs[i].txtFontSize+";' >"+this._tabs[i].name+"</div></div>";

					html += "<div class=tabinblock style='display:none;padding-left:2px;position:relative;top:3px'><img id=acur alt=C title=close src='" + this._tabs[i].tabCloseImage + "' border=0 onClick=\"CTabs.allTabs['"+this._id+"'].removeThisTab(this);event.cancelBubble=true; return false;\" style='position:relative;left:0;top:0' /></div></div>";
				}
				else
					html += "<div class=tabh style=\"border-bottom: " + this._tabs[i].borderSelect + ";height:" + this._def.tabHeight + "px;position:relative;left:"+rbc+"px;\"><div class='tabinblock no-selection' name='no-selection' ><div name=tabname style='padding-right:3px;padding-left:3px;padding-top:3px;color:"+this._tabs[i].txtColor+";font-size:"+this._tabs[i].txtFontSize+";' >"+this._tabs[i].name+"</div></div></div>";
				
				html += "<div class=tabhR style=\"border-bottom: " + this._tabs[i].borderSelect + ";width:" + this._tabs[i].tabSideWidth + ";height:" + this._def.tabHeight + "px;position:relative;left:"+rbc+"px;\"></div>";
			}
			
			html += "</div>";
			rbc -= 0;				
			ziL++;	
		}
		
		if(CUi._isIE)
		{
			// This -6 is dependend on the tabHeadTopPadding which should be 7 for IE
			html += "<div class=tab style='position:relative;top:-" + this._def._tabHeadTopPad_FIX_IE + "px;left:-4px;border-bottom:" + this._def.borderUnSelect + ";height:" + this._def.tabHeight + "px' >&#160;</div>";
		}
		else
		{
			// noacur needs to be set HACKERS to prevent select() from marking as clickable
			html += "<div name=noacur class=tab style='position:relative;top:-1px;border-bottom:" + this._def.borderUnSelect + ";height:" + this._def.tabHeight + "px' >&#160;</div>";
		}
		
		var oy = this._tabs.length * 10;
		//html += "<div class=tab name='tabExtra' style='border-bottom:" + this._def.borderUnSelect + ";position:relative;top:0px;left:" + CUi.getAgentVal(rbc,-rbc-oy) + ";height:" + this._def.tabHeight + "px;z-index:10;text-align:right' >&#160;</div>";

		return(html+"</div></div>");
	},

	selectNext: function()
	{
		//this._curSelTabI = (this.tabCount() - 1);
		if((--this._curSelTabI) < 0)
			this._curSelTabI = 0;
			
		this.selectTabHead(this._curSelTabI);
		this.displayTab(this._curSelTabI);
	},

	selectEx: function(sid,ignoreBorders)
	{
		var elm = this.getTabElm();
		var tel = CUtil.getChildByName(CUtil.getChildByClass(elm,'tabHeadCon','DIV',true),sid,'DIV',false);
		if(tel)
			this.select(tel,ignoreBorders);
	},

	getSelTabHTML: function()
	{
		return(this.getHTML(this._curSelTabI));
	},

	getJSRun: function(f)
	{
		return('CTabs.allTabs[\'' + this._id + '\'].' + f + ';return(false);');
	},
	
	setHTML: function(selt)
	{
		CUi.doc.body.style.overflow = 'hidden';
		CUi.doc.body.innerHTML = this.getHTML(selt);
	},
	
	getHTML: function(selt)
	{
		//return("<div style='background-color:#00f;padding:0px;'><center>"+this.getSimpleHTML(selt)+"</center></div>");
		return(this.getSimpleHTML(selt));
	},
/*
	createTabCon: function(selt)
	{
		 var elm = CUi.createElm("div","");
		 elm.innerHTML = this.getSimpleHTML(selt);
		 return(elm);
	},
	*/
	
	getBorderSelect: function(selt)
	{
		if(CUtil.varok(this._tabs[selt]))
			return(this._tabs[selt].borderSelect);
		else
			return(this._def.borderUnSelect);
	},
	
	getSimpleHTML: function(selt)
	{		
		var rh = this._def.height - this._def.areaBaseMargin - this._def.tabHeadHeight - CUi.getAgentVal(0,5) - CTabs._M_TAB_DEF_HEIGHT;		//TODO: HACK: VERY BAD; GENERIC FIX OUT THE WINDOW
				
		var extBack='#fff'; //110% WIDTH FOR ie IS NEEDED; overflow hidden from CSS on the body stops the scrolls
		var html = "<div style='width:" + ( (CUi._isIE) ? ('110') : ('100') ) + "%;height:100%;background-color:" + this._def.areaBackGroundColour+ "'><div id='" + this._realId + "tabArea' style='overflow:hidden;position:relative;left:" + this._def.useFullAreaXOff + "px;width:"+(this._def.width-(2*this._def.useFullAreaXOff))+";height:"+(this._def.height)+"'><div id='" + this._realId + "' name='" + this._id + "' style='background-color:"+this._def.areaBackGroundColour+";overflow:hidden;height:"+(this._def.height - this._def.areaBaseMargin)+"'>";
		
		var bgStyle = '';
		{
			if( this._def.roundCorners )
				bgStyle = "background-color:" + this._def.tabBackground + ";" + CTabs._M_ROUND_CORNER_DIRECTIVE 
			else
				bgStyle = "background-color:" + this._def.tabBackground + ";";
		}
		
		html += '<table border=0 width=100% cellpadding=0 cellspacing=0 ' + CUi.getAgentVal('style="position:relative;z-index:999;top:1px"', '') + '>';
		
		html += '<tr><td align=left ><div name=tabHeadP style="padding-left: ' + this._def.tabRowLeftOffset + 'px;">' + this.getAllTabsHtml(selt) + '</div>';
		
		if(this._def.tabSideIsOn)
		{
			html += '<td align=right width=1px nowrap >';		
			html += this._def.tabSideCB(this)+'</td>';
		}
		else
			html += '<td width=1px align=right></td>';

		//border-bottom:' + this._def.borderUnSelect + ';
		
		html += '</tr></table>';	
		
		html += '<div name=areaTop >';
		
		this._def.topIncluded = false;		
		if((CUtil.varok(selt)) && ((topContent = this._def.areaTopMarginCB(this,this._tabs[selt])).length>0))
			{ html +=  topContent; this._def.topIncluded = true; }
		
		html += '</div>';
		//position:relative;z-index:99;
		html += "<div class=tabview id='" + this._realId + "tabView' name=tabView style='" + bgStyle + ("border:" + this._def.borderUnSelect) + ";height:"+(rh - ((this._def.topIncluded)?(this._def.areaTopMargin):(0))) + "'>";
					
		//width:"+(this._width-this._def.tabViewWidthOffset-100)+";
		if(CUtil.varok(selt))
			html += this.getTabViewHTML(this._tabs[selt]);
		
		html += "</div>";
		html += "</div>"; 
		
		html += "<div name=areaBase>";
		if((true)&&(CUtil.varok(selt)) && ((areaBaseContent = this._def.areaBaseMarginCB(this,this._tabs[selt])).length>0))
			html += areaBaseContent;
		
		html += "</div>";		
		html += "</div>";
		
		this._curSelTabI = selt;
		return(html);
	},

	select: function(o)
	{
		var si = o.getAttribute('name');
		
		if(CUtil.varok(this._tabs[si]) && this._curSelTabI != si) // && this.checkSafeRemove(this._curSelTabI))
		{			
			var fromtab = this._curSelTabI, totab = si;
			
			var tcs = new Array();
			var intabs=false;
			var tc = false;
			var me = this;
			
			if(true)
			{
				var lastTabIX = -1;
				
				CUtil.applyToChildNodes(o.parentNode,"DIV",true,function(o)
				{
					try {
						var c = CUtil.getClassName(o);
						var ixn = o.getAttribute('name');
											
						if( (ixn == "tabname") && CUtil.varok( me._tabs[lastTabIX] ) )
						{
							o.style.color = me._tabs[lastTabIX].txtColor;							
						}
						
						if(c == 'tab')
						{
							lastTabIX = ixn;
							if(si == ixn)
							{
								o.setAttribute('id','');
							}
							else
							{
								if(ixn != "noacur")
									o.setAttribute('id','acur');
							}
						}
											
						if(c.slice(0,4)=="tabh") // && o.style.zIndex==30)
						{			
							o.style.borderBottom = me._def.borderUnSelect;
							o.style.backgroundImage = "";
						}
						
						if(c == "tabh")
						{
							var iob = o.getElementsByTagName('img');						
							if(iob && iob.length==1) { iob[0].parentNode.style.display='none'; }
						}					
					} catch(exx) {}
				} );

				var lt = CUtil.getChildByClass(o,'tabhL','DIV');
				var ct = CUtil.getChildByClass(o,'tabh','DIV');
				var rt = CUtil.getChildByClass(o,'tabhR','DIV');
	
				//CTabs.shiftTabY(this,0,lt,ct,rt);
				//lt.style.zIndex=30; ct.style.zIndex=30; rt.style.zIndex=30;
				
				lt.style.backgroundImage = "url('" + this._tabs[si].tabLImage + "')";
				ct.style.backgroundImage = "url('" + this._tabs[si].tabTImage + "')"; 
				rt.style.backgroundImage = "url('" + this._tabs[si].tabRImage + "')";
				
				lt.style.borderBottom = this._tabs[si].borderSelect;
				ct.style.borderBottom = this._tabs[si].borderSelect; 
				rt.style.borderBottom = this._tabs[si].borderSelect;				
								
				var iob = ct.getElementsByTagName('img');
				if(iob && iob.length==1) { iob[0].parentNode.style.display='block'; }
				
				var tabNameSelect = CUtil.getChildByName(ct,'tabname','DIV',true);
				if(tabNameSelect)
					tabNameSelect.style.color = this._tabs[si].txtSelectColor;
					
				if(iob && iob.length==1) { iob[0].parentNode.style.display='block'; }
			}
			
			this.displayTab(si);								
			this._def.tabSelectCB(this,fromtab,totab);			
		}
	},

	procTabSides: function(si)
	{
		// Set the tabView to match this Tabs Colour Spec
		//
		var vel = this.getTabViewElm();
		var borderSelect = this.getBorderSelect(si);
		vel.style.borderLeft = borderSelect; vel.style.borderBottom = borderSelect; vel.style.borderRight = borderSelect;
		
		var ba = this.getTabViewBase();			
		if ( (ba) && (baseContent = this._def.areaBaseMarginCB(this,this._tabs[si])).length>0)
		{
			ba.style.display='block';
			ba.innerHTML = baseContent;
		}
		else if(ba)
			ba.style.display='none';			
		
		var ta = this.getTabViewTop();							
		this._def.topIncluded = false;
		if ((topContent = this._def.areaTopMarginCB(this,this._tabs[si])).length>0)
		{
			ta.style.display='block';
			ta.innerHTML = topContent;
			this._def.topIncluded = true;
		}
		else
			ta.style.display='none';						
	},
	
	resizeTabView: function(vel,topIncluded) //Such a  bad hack!!
	{	
		if(this._def.useFullArea)
		{	
			vel.style.height = (CUtil.getDim(false) - this._def.areaBaseMargin - this._def.tabHeadHeight - CUi.getAgentVal(0,5) - ((topIncluded)?(this._def.areaTopMargin):(0)));		
			//CUi.regWinResize(2,0,{ id: this._realId + 'tabView', doHeight: true, heightOffset: (this._def.tabHeadHeight + this._def.areaBaseMargin + CUi.getAgentVal(0,5) + ((topIncluded)?(this._def.areaTopMargin):(0))) });			
		}
	},
	
	recurseRemTags: function(ob,func)
	{
		for( var x = 0; ob.childNodes[x]; x++ )
		{			
			for( var i in CTabs._M_HTML_REM_TAGS)
			{
				if(ob.childNodes[x].tagName==CTabs._M_HTML_REM_TAGS[i])
					func(this,ob.childNodes[x]);						
			}
			
			this.recurseRemTags(ob.childNodes[x],func);
		}
	},

	displayNewTab: function(si)
	{
		this.selectTabHead(si);
		this.selectEx(si);
	},
	
	refreshCurrentTab: function(si)
	{
		this.displayTab(this._curSelTabI);
	},

	clearTabViewOf: function(si)
	{
		var vel = this.getTabViewElm();
		if(this._tabs[si].type == CTabs._M_TAB_TYPE_IFRAME)
		{
			var tf = CUtil.getChildByName(vel,this._tabs[si].id,'DIV',false);
			if(tf)
				tf.parentNode.removeChild(tf);
		}
	},

	displayTab: function(si)
	{		
		//this.procTabSides(si);
		
		var vel = this.getTabViewElm();
		
		if(CUtil.varok(this._curSelTabI) && this._curSelTabI!=si) 
		{
			// PERFORM ACTION ON THE TAB THAT HAS BEEN  UN-SELECTED
			if(this._tabs[this._curSelTabI].type == CTabs._M_TAB_TYPE_IFRAME)
			{
				var tf = CUtil.getChildByName(vel,this._tabs[this._curSelTabI].id,'DIV',false);
				if(tf)
					tf.style.display='none';
			}

			if(this._tabs[this._curSelTabI].type == CTabs._M_TAB_TYPE_HTML && this._tabs[this._curSelTabI].htmlRemember)
			{ 					
				var me = this;
				me._tabs[this._curSelTabI].htmlRememberMap = new Array();
				this.recurseRemTags(vel, function(me,ob) { 
					me._tabs[me._curSelTabI].htmlRememberMap[ob.getAttribute('name')] = ob.value; 
				} );		
			}
		}

		//for( var x = 0; vel.childNodes[x]; x++ )
		//	{ try { vel.childNodes[x].parentNode.removeChild(vel.childNodes[x]); } catch(e) {} }

		/*if(this._tabs[si][4]) //remove the iframe so refreshed if needed
		{
			this.clearTabViewOf(si);
			this._tabs[si][4]=false;
		}*/

		this.setTabView(vel,si);
		this._curSelTabI=si;
		
		this.captureIFrameContent();		
	},

	getTabViewHTML: function(tab) 
	{
		if(tab.type == CTabs._M_TAB_TYPE_HTML)
			return(tab.html);
		else if(tab.type == CTabs._M_TAB_TYPE_CALLBACK)
			return(tab.callbackFunc(tab.callbackParam));
		else if(tab.type == CTabs._M_TAB_TYPE_IFRAME)
		{
			var ix = this.setTabIFrame(false,tab);
			return(ix);
		}
		else
			return("");
	},

	setTabView: function(vel,si) 
	{
		if(this._tabs[si].type == CTabs._M_TAB_TYPE_HTML)
		{
			vel.innerHTML = (""); //clear it
			if(this._tabs[si].htmlRemember && CUtil.varok(this._tabs[si].htmlRememberMap))
			{
				vel.innerHTML = (this._tabs[si].html);
				var me=this;
				this.recurseRemTags(vel, function(me,ob) { 
					var vali = me._tabs[si].htmlRememberMap[ob.getAttribute('name')]
					if(typeof vali != "undefined")
						ob.value = vali;
				} );
			}
			else
				vel.innerHTML = (this._tabs[si].html);
		}
		else if(this._tabs[si].type == CTabs._M_TAB_TYPE_CALLBACK)
			vel.innerHTML = (tab.callbackFunc(tab.callbackParam));
		else if(this._tabs[si].type == CTabs._M_TAB_TYPE_IFRAME)
		{
			this.setTabIFrame(vel,this._tabs[si]);
		}
	},
	
	getIFrameObject: function()
	{
		var tab = this._tabs[this._curSelTabI];
		if(tab.type == CTabs._M_TAB_TYPE_IFRAME)
		{
			var vel = this.getTabViewElm();
			var tf = CUtil.getChildByName(vel,tab.id,'DIV',false);
			if(tf)
				return(tf.childNodes[0]);
		}
		
		return(false);
	},

	refSelectedIFrame: function()
	{
		var ifr = this.getIFrameObject();
		if(ifr)
			ifr.src=ifr.src;
	},

	captureIFrameContent: function()
	{
		CUtil.waitForIt(50,this._captureIFrameContent,this);
	},
	
	_captureIFrameContent: function(me)
	{
		try 
		{
			var ifr = me.getIFrameObject();
			if(ifr && ifr.contentWindow)
			{
				var w = ifr.contentWindow;
				var d = w.document;	
				if(d && d.body)
					{ me._tabs[me._curSelTabI]._iframeWindow=w; return(true); }
			}
		} catch(e) {}
		
		return(false);
	},
	
	getIFrameContent: function(oid)
	{
		if(CUtil.varok(this._tabs[this._curSelTabI]._iframeWindow))
		{
			CUtil.waitForIt(20,function(me) {
				var w = me._tabs[me._curSelTabI]._iframeWindow;
				var el = w.document.getElementById(oid);
				if(el) return(true); else return(false);
			}, this );
				
			return(this._tabs[this._curSelTabI]._iframeWindow.document.getElementById(oid));			
		}
		else
			return(false);
	},
	
	setTabIFrame: function(vel,tab)
	{		
		var roundCorner = '';
		if( this._def.roundCorners )
			roundCorner = CTabs._M_ROUND_CORNER_DIRECTIVE;
			
		var nhtml = "<iframe name='ifr" + tab.id + "' style='"+roundCorner+"' marginwidth=0 marginheight=0 height='100%' width=100% frameborder=0 scrolling=auto src='" + tab.iframeURL + "'></iframe>";

		if(vel)
		{
			var tf = CUtil.getChildByName(vel,tab.id,'DIV',false);
			if(tf)
			{
				tf.style.display='block';
			}
			else
			{
				var ndiv = CUi.doc.createElement('DIV');
				ndiv.setAttribute('name',tab.id);
				ndiv.style.width='100%';
				ndiv.style.height='100%';
				ndiv.innerHTML = nhtml;
				vel.appendChild(ndiv);
			}

			return('');
		}
		else
		{
			return("<div name='"+tab.id+"' style='width:100%;height:100%'>" + nhtml + "</div>");
		}
	}

}