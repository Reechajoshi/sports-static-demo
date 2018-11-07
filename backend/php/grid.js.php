
<?php
	echo("var __cssGridHeight=18;");
?>

// Grid
//////////////////////////////////////////////////////////////////////

CGrid = function(gridID, cellAsLink, dblClickFunc)
{
	this._gridID = gridID; 
	this._realGridID = CGrid._M_GID_PREFIX + gridID;
	this._cellAsLink = cellAsLink;
	this._dblClickFunc = dblClickFunc;
	
	this._selrow = '';
	this._rowShiftOK = false;
	this._wrapInFloat = false;

	this._colLastSort = false;
	this._colLastDirAsc = false;
	
	this._cols = new Array();
	this._rows = new Array();
	
	CGrid._allGlobalGrids[gridID] = this;
}

CGrid._M_IMG_SORT_ASC = false;
CGrid._M_IMG_SORT_DSC = false;

CGrid.init = function()
{
}

CGrid._M_GID_PREFIX = "gridx";

CGrid._allGlobalGrids = new Array();

CGrid._M_GRID_TOP_NAME = "gridFullCon";
CGrid._M_GRID_ID_PREFIX = "cvGrid";
CGrid._M_GRID_HEAD_PREFIX = "cvGridHead";
CGrid._M_GRID_ROW_PREFIX = "cvGridRow";
CGrid._M_GRID_CEL_PREFIX = "cvGridCel";

CGrid.prototype.handleRowSel = function(me)
{
	var rowcon = CUtil.getParentByName(me,"gridConRows");	
	var colOfSelRow = "#FACEBE";
	var blankColOfSelRow = "#FFFFFF"; //TODO: needs to be plugged into background user settings
	
	var __funcMarkRow = function(row,fromcol,tocol) 
	{
		for( var x = 0; row.childNodes[x]; x++ )
		{
			var c = row.childNodes[x];			
			if(c.tagName=="DIV")
			{ 
				var bc = CUtil.getRealColourHex(c.style.backgroundColor);
				if(bc=='' || bc == fromcol)
					c.style.backgroundColor = tocol;
			}
		}
	}
	//CUtil.log(this._selrow + " - " + me.getAttribute("name") + " - " + (this._selrow=='') + " - " + (this._selrow=='0'));

	if((!CUtil.isNumber(this._selrow)) && this._selrow=='')
	{
		__funcMarkRow(me,blankColOfSelRow,colOfSelRow);
		this._selrow=me.getAttribute("name"); //index of row
	}
	else if(this._selrow==me.getAttribute("name"))
	{		
		__funcMarkRow(me,colOfSelRow,blankColOfSelRow);
		this._selrow='';
	}
	else
	{
		for( var x = 0; rowcon.childNodes[x]; x++ )
		{
			if(rowcon.childNodes[x].getAttribute("name") == this._selrow)
				{ __funcMarkRow(rowcon.childNodes[x],colOfSelRow,blankColOfSelRow); break; }
		}

		__funcMarkRow(me,blankColOfSelRow,colOfSelRow);
		this._selrow=me.getAttribute("name"); //index of row
	}	
}

CGrid.prototype.getSelectedRowValue = function(overID)
{
	if(CUtil.varok(overID) && CUtil.varok(this._rows[overID]))
		return(this._rows[overID]);
	else if(this._selrow!='' && CUtil.varok(this._rows[this._selrow]))
		return(this._rows[this._selrow]);
	else
		return(false);
}

CGrid.prototype.getAllGridRows = function(rowLen)
{
	var html = "";
	
	for(var ir in this._rows)
		html += this.getRowHTML(ir,rowLen); 
	
	this._selrow = '';
	
	return(html);
}

CGrid.resizeMargin = 3;
CGrid._M_MAX_COL_SIM_RESIZE_COUNT = 30;

CGrid._colInResize = false; 
CGrid._colGridResizeAllRowsInMM = false; 
CGrid._colParentOrgWidth = false;
CGrid._colInResizeOrgWidth = false;
CGrid._colInResizeStartCord = false;

CGrid._rowInMove = false; 
CGrid._rowInMoveWin = false; 
CGrid._rowInMoveGlob = false; 
CGrid._rowInMovePos = false;
CGrid._rowInMoveGrid = false;
CGrid._rowInMoveWinBody = false;
CGrid._rowInMoveGridElm = false;

CGrid._colInMove = false;
CGrid._colGridInMove = false;		

CGrid.resetGridAlterStates = function()
{
	if(CGrid._colInResize && !CGrid._colGridResizeAllRowsInMM)
	{
		var nd = (CUi._mouseX - CGrid._colInResizeStartCord);
		var nw = CGrid._colInResizeOrgWidth + nd; //- CGrid._colScrollOrgWidth; //TODO check this properly
		if(nw<10) { nw=10; nd = (nw - CGrid._colInResizeOrgWidth); }
		CGrid._colInResize.parentNode.style.width = CGrid._colParentOrgWidth + nd+2;
		CGrid._colInResize.style.width = nw;
		CGrid._colGridResizeAllRowsInMM = true; 
		CGrid.resizeAllColsAs(nw,CGrid._colParentOrgWidth + nd+2);			
	}
	
	if(CGrid._rowInMoveGlob)
		CUi.doc.body.removeChild(CGrid._rowInMoveGlob);
		
	CGrid._colInResize = false; 
	CGrid._colGridResizeAllRowsInMM = false; 
	CGrid._colParentOrgWidth = false;
	CGrid._colInResizeOrgWidth = false;
	CGrid._colInResizeStartCord = false;

	CGrid._rowInMove = false; 
	CGrid._rowInMovePos = false;
	CGrid._rowInMoveGrid = false;
	CGrid._rowInMoveGridElm = false;
	CGrid._rowInMoveWin = false;
	CGrid._rowInMoveWinBody = false;
	
	CGrid._rowInMoveGlob = false;

	CGrid._colInMove = false;
	CGrid._colGridInMove = false;
}

CGrid.resizeAllColsAs = function(nw,nrw)
{
	var colname = CGrid._colInResize.getAttribute("name");
	var p = CGrid._colInResize.parentNode.parentNode.childNodes[1]; //ouch major assumtion on element layout
	var headid = parseInt(CGrid._colInResize.id);
	
	if(CGrid._colGridResizeAllRowsInMM)
	{
		for(var rc = 0, chc=0; p.childNodes[rc]; rc++ )
		{
			if(p.childNodes[rc].tagName=="DIV" && p.childNodes[rc].id.slice(0,CGrid._M_GRID_ROW_PREFIX.length)==CGrid._M_GRID_ROW_PREFIX)
			{
				p.childNodes[rc].style.width=nrw;
				var cells = p.childNodes[rc].getElementsByTagName("DIV");
				cells[headid].style.width=nw;
				
				//if(dopart && ++chc>=CGrid._colGridMaxVisibleCount)
				//	break; //done enough
			}
		}
	}
}

CGrid.handleMouse = function(e)
{	
	var s = CUtil.getEventSrc(e);
				
	if(CGrid._colInMove)
	{
		
	}
	else if(CGrid._colInResize)
	{
		try {
			var nd = (CUi._mouseX - CGrid._colInResizeStartCord);
			var nw = CGrid._colInResizeOrgWidth + nd; //- CGrid._colScrollOrgWidth; //TODO check this properly
			
			if(nw<10) { nw=10; nd = (nw - CGrid._colInResizeOrgWidth); }
			
			CGrid._colInResize.parentNode.style.width = CGrid._colParentOrgWidth + nd+2;
			CGrid._colInResize.style.width = nw;
			
			CGrid.resizeAllColsAs(nw,CGrid._colParentOrgWidth + nd+2);			
				
			if(!CUtil.isMM(e))
			{
				CGrid._colGridResizeAllRowsInMM=true;
				CGrid.resizeAllColsAs(nw,CGrid._colParentOrgWidth + nd+2);
				
				CUi.doc.body.style.cursor = "default";
							
				if(CGrid._colInResize)
					CGrid._colInResize.style.cursor = "default";
					
				CGrid.resetGridAlterStates();			
			}
		} catch(e) { CUtil.log(e.message); }
	}
	else if(CGrid._rowInMove && s)
	{		
		var i = s.getAttribute("id");		
		if((i) && (i.slice(0,CGrid._M_GRID_CEL_PREFIX.length)==CGrid._M_GRID_CEL_PREFIX))
		{
			var p = s.parentNode;
			
			if((p != CGrid._rowInMove) && (CGrid._rowInMoveGridElm == CUtil.getParentByClass(p,"gridCon")))
			{
				var l = CGrid._rowInMoveWin.offsetLeft + CGrid._rowInMoveGridElm.offsetLeft; // CGrid._rowInMoveWinBody.scrollLeft;
				var t = CGrid._rowInMoveWin.offsetTop - CGrid._rowInMoveWinBody.scrollTop;
				
				if(CGrid._rowInMoveGrid._wrapInFloat)
				{
					if(CUi._isIE)
						l+= (CGrid._rowInMoveGridElm.parentNode.offsetLeft + 1);
					
					t-= (CGrid._rowInMoveGridElm.parentNode.scrollTop);					
				}
					
				if(CUi._isIE)
				{
					t += (p.offsetTop + CUi.__cssHeadHeight);
					if(CGrid._rowInMoveGrid._wrapInFloat)
						t += CGrid._rowInMoveGridElm.parentNode.offsetTop;
				}
				else
					t += (p.offsetTop) + ((parseInt(p.getAttribute("name")) + 1) * s.offsetHeight);

				if( t < (CGrid._rowInMoveWin.offsetTop + CUi.__cssHeadHeight))
					t = (CGrid._rowInMoveWin.offsetTop + CUi.__cssHeadHeight);					
					
				var w = 0;
								
				if(CUi._isIE)
				{
					w = Math.min(CGrid._rowInMoveWin.offsetWidth,CGrid._rowInMove.offsetWidth);
					if(CGrid._rowInMoveGrid._wrapInFloat)
						w= Math.min(w,CGrid._rowInMoveGridElm.parentNode.offsetWidth);
				}
				else
					w = Math.min(CGrid._rowInMove.parentNode.offsetWidth,CGrid._rowInMove.offsetWidth);
				
				if(CGrid._rowInMoveGlob)
				{
					CGrid._rowInMoveGlob.style.display='block';

					CGrid._rowInMoveGlob.childNodes[0].style.top = t;
					CGrid._rowInMoveGlob.childNodes[0].style.width = w;
				}
				else
				{ 
										
					CGrid._rowInMoveGlob = CUi.doc.createElement('div');
					CGrid._rowInMoveGlob.innerHTML = "<div style=\"overflow:hidden;position:absolute;left:" + l + ";top:" + t + ";width:" + w + ";height:2px;background-color:#F06028;z-index:" + CUi._zIndexHighest + "\">&#160;</div>";
					CUi.doc.body.appendChild(CGrid._rowInMoveGlob);				
				}
				
				if(CUtil.isMU(e))
				{
					CGrid._rowInMoveGrid.unSelRowif();
					CGrid._rowInMoveGrid.shiftRowToByIX(CGrid._rowInMovePos,p.getAttribute("name"));
				}
			}
		}
		else
		{
			if(CGrid._rowInMoveGlob)
				CGrid._rowInMoveGlob.style.display='none';
		}
	}
	else if(s)
	{ // for head related
		try { s = ((s.parentNode.getAttribute("name").slice(0,CGrid._M_GRID_HEAD_PREFIX.length)==CGrid._M_GRID_HEAD_PREFIX)?(s.parentNode):(s)); } catch(err) {}
		var n = s.getAttribute("name");		
		if((n) && (n.slice(0,CGrid._M_GRID_HEAD_PREFIX.length)==CGrid._M_GRID_HEAD_PREFIX))
		{			
			// ASSUMTION: all grids start at 0px for their container
			var r = s.parentNode;
			var iofs = parseInt(s.id);
			
			var wcon = CUtil.getParentByName(r,CGrid._M_GRID_TOP_NAME);
			
			var wbodSroll = wcon.scrollLeft;
					
			var winleft = wcon.offsetLeft - wbodSroll;
			var gridcon = CUtil.getParentByClass(s,"gridCon");
			
			for(var x = 0, cn = 0, totw = 0; r.childNodes[x]; x++ )
			{
				if(r.childNodes[cn].tagName=="DIV")
				{
					totw += r.childNodes[cn].offsetWidth;
					if( ++cn > iofs ) break;
				}
			}
			
			var l = winleft + totw - CUi.doc.body.scrollLeft;
			
			if(CUi._mouseX > (l-CGrid.resizeMargin))
			{					
				CUi.doc.body.style.cursor = "e-resize";
				s.style.cursor = CUi.doc.body.style.cursor;
				
				if(CUtil.isMD(e))
				{ 					
					CGrid._colInResize = s;
					
					CGrid._colInResizeStartCord = CUi._mouseX; CGrid._colInResizeOrgWidth=s.offsetWidth;
					CGrid._colParentOrgWidth = r.offsetWidth;
					CGrid._colGridResizeAllRowsInMM = ((CUi._isFF)||(grido.rowCount() <= CGrid._M_MAX_COL_SIM_RESIZE_COUNT));
					//CGrid._colGridMaxVisibleCount = Math.floor(wcon.childNodes[0].offsetHeight / __cssGridHeight);					
				}
			}
			else
			{
				s.style.cursor = "default";	
				CUi.doc.body.style.cursor = "default";
			}			
		}
		else
		{ //for row repositioning
			var i = s.getAttribute("id");
							
			if((i) && (CUtil.isMD(e)) && (i.slice(0,CGrid._M_GRID_CEL_PREFIX.length)==CGrid._M_GRID_CEL_PREFIX))
			{				
				CGrid._rowInMoveGridElm = CUtil.getParentByClass(s,"gridCon");
				CGrid._rowInMoveGrid = CGrid.getGridObject(CGrid._rowInMoveGridElm.getAttribute("name"));
				//var grido = CGrid.getGridObject(CUtil.getParentByClass(s,"gridCon").getAttribute("name"));
				if(CGrid._rowInMoveGrid._rowShiftOK)
				{
					CGrid._rowInMove = s.parentNode; 
					CGrid._rowInMoveWin = CUtil.getParentByName(CGrid._rowInMove,"winFullCon");
					CGrid._rowInMoveWinBody = CUtil.getChildByName(CGrid._rowInMoveWin,"winBody","DIV",true);
					CGrid._rowInMovePos = CGrid._rowInMove.getAttribute("name");					
				}
			}
		}		
	}
	
	if(CUtil.isMU(e) || CUtil.isMMButtonReleased(e))
		CGrid.resetGridAlterStates();
}

CGrid.prototype.getGridElement = function()
{
	return(CUi.doc.getElementById(this._realGridID));
}

CGrid.getGridObject = function(gridID)
{
	if(CUtil.varok(CGrid._allGlobalGrids[gridID])) 
		return(CGrid._allGlobalGrids[gridID]);
	else
		return(false);	
}

CGrid.getInnerGridObject = function(winel)
{
	var go= (CUtil.getChildByClass(winel,"gridCon", "DIV",true));	
	if(go)
		return(CGrid._allGlobalGrids[go.getAttribute("name")]);
	else
		return(false);
}

CGrid.getOuterGridObject = function(el)
{
	var go= (CUtil.getParentByClass(ek,"gridCon"));	
	if(go)
		return(CGrid._allGlobalGrids[go.getAttribute("name")]);
	else
		return(false);
}

CGrid.editCell = function(ocel)
{
	if(CUtil.deepestSoleChild(ocel)==ocel)
	{
		var r = prompt("Modify field:",ocel.innerHTML);
		if(r!=null)
		{
			ocel.innerHTML=r;			
			ocel.setAttribute('name','1');
		}
	}
	else
		alert("Sorry, you can only modify this type of field in Excel!");
}
