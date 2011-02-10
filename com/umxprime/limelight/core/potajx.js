/**
 * 
 * Copyright © 2010,2011 Maxime CHAPELET (umxprime@umxprime.com)
 *
 * This file is a part of the Limelight Framework
 *
 * The Limelight Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Limelight Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Limelight Framework.  If not, see <http://www.gnu.org/licenses/>.
 * 
 **/

var AJX = new ajxcore();

function ajxcore()
{
	this.AJXSTACK = new Object();
	this.count=0;
}

function ajxNewRequestWithAction(name,action,params)
{
	this.newRequest(name);
	this.setAction(name,action,params);
}

function AJAXHandler()
{
	if (window.XMLHttpRequest)
		this.requester = new XMLHttpRequest();
	else if (window.ActiveXObject)
		this.requester = new ActiveXObject("Microsoft.XMLHTTP");
	else {
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	}
	
}

function ajxNewRequest(name)
{
	var aRequest = {'debug':false,'name':name,'id':this.count,'action':'','params':'','handler':new AJAXHandler()};
	this.AJXSTACK[this.count]=false;
	this.AJXSTACK[name]=aRequest;
	this.count++;
}

function ajxSetAction(name,action,params)
{
	this.AJXSTACK[name].action=action;
	this.AJXSTACK[name].params=params;
}

function ajxDebug(name)
{
	this.AJXSTACK[name].debug=true;
}

function ajxPrintRequestParams(name)
{
	document.body.innerHTML += "Request Name : "+name+"<br/>\n";
	document.body.innerHTML += "Request ID : "+this.AJXSTACK[name].id+"<br/>\n";
	document.body.innerHTML += "Request Action : "+this.AJXSTACK[name].action+"<br/>\n";
	document.body.innerHTML += "Request Action Parameters : "+this.AJXSTACK[name].params+"<br/>\n";
	document.body.innerHTML += "Request Handler : "+this.AJXSTACK[name].handler.requester+"<br/>\n";
}

function ajxSend(name)
{
	var requester = this.AJXSTACK[name].handler.requester;
	var action = this.AJXSTACK[name].action;
	var params = this.AJXSTACK[name].params;
	this.AJXSTACK[this.AJXSTACK[name].id]=true;
	var ref=this;
	requester.open("GET",LIMELIGHT_PATH+"core/potajxcall.php?module="+AJXModule+"&action="+action+"&params="+params, true);
	requester.send(null);
	showLoader();
	requester.onreadystatechange = function()
	{
		if (requester.readyState == 4) {
			ref.AJXSTACK[ref.AJXSTACK[name].id]=false;
			var isBusy=false;
			for(poll=0;poll<ref.count;poll++)
			{
				if(ref.AJXSTACK[poll]==true)isBusy=true;
			}
			if(!isBusy)hideLoader();
			if(ref.AJXSTACK[name].debug)
			{
				ref.AJXSTACK[name].debug = false;
				alert(requester.responseText);
			}
			eval(requester.responseText);
			return 0;
		}
	};
}

ajxcore.prototype.newRequest = ajxNewRequest;
ajxcore.prototype.setAction = ajxSetAction;
ajxcore.prototype.newRequestWithAction = ajxNewRequestWithAction;
ajxcore.prototype.printRequestParams = ajxPrintRequestParams;
ajxcore.prototype.send = ajxSend;
ajxcore.prototype.debug = ajxDebug;

/*
var AJXSTACK = new Array();
function ajx2(name)
{
	this.verbose=false;
	this.name = name;
	this.AJX_SYNC = 0
	this.AJX_ASYNC = 1;
	this.page = "";
	this.action = "";
	this.params = "";
	this.mode = this.AJX_ASYNC;
	this.reset = false;
	this.timeout = 100;
	this.xhr_object = null;
	if (window.XMLHttpRequest)
		this.xhr_object = new XMLHttpRequest();
	else if (window.ActiveXObject)
		this.xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else {
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	}
	this.id = 0;
	
	//methods
	this.showLoader = ajxShowLoader;
	this.send = ajxSend;
	this.testStack = ajxTestStack;
	this.stack = ajxStack;
	this.switchVerbose = ajxSwitchVerbose;
	return 0;
}

function ajxSwitchVerbose()
{
	this.verbose=!this.verbose;
}

function ajxStack()
{
	this.id = 0;
	if(AJXSTACK.length>0)this.id = AJXSTACK[AJXSTACK.length-1].id+1;
	AJXSTACK.push(this);
	this.stackposition = AJXSTACK.length-1;
	this.sent=false;
}

function ajxSend()
{
	eval("delete "+this.name);
	this.showLoader();
	if(this.mode==this.AJX_ASYNC)
	{
		if(AJXSTACK.length)
		if(AJXSTACK[0].id!=this.id){
			if(this.verbose)document.body.innerHTML+="ajx with name:"+this.name+" is waiting...<br/>";
			this.testStack();
			timer = setTimeout(function(thisObj){thisObj.send()},this.timeout,this);
			return 0;
		}
		document.body.innerHTML += "action : "+AJXSTACK[0].action;
		this.params=eval(this.params);
		AJXSTACK.shift();
		for(var i=0;i<AJXSTACK.length;i++)AJXSTACK[i].stackposition--;
		this.sent=true;
		if(this.verbose)document.body.innerHTML+="ajx with name:"+this.name+" is done.<br/>";
		
	}
}

function ajxShowLoader()
{
	if(ajxGetId("ajx_loader")) ajxGetId("ajx_loader").className = "displayblock";
}

function ajxTestStack()
{
	if(this.id == AJXSTACK[0].id)
	{
		if(this.verbose)document.body.innerHTML+="this ajx request is current, position : "+this.stackposition+"<br/>";
	} else {
		if(this.verbose)document.body.innerHTML+="this ajx request is in queue, position : "+this.stackposition+"<br/>";
	}
}

function ajxGetId(id)
{
	return document.getElementById(id);
}

function ajx(page,action,params,mode,id,reset)
{
	// if(ajx_get_id("ajx_loader")) ajx_get_id("ajx_loader").className = "displayblock";
	// var retry = function(){
	// 		ajx(page,action,params,mode,id,reset);
	// };
	// var xhr_object = null;
	// if (window.XMLHttpRequest) // Firefox
	// 	xhr_object = new XMLHttpRequest();
	// else if (window.ActiveXObject) // Internet Explorer
	// 	xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	// else { // XMLHttpRequest non supporté par le navigateur
	// 	alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	// }
	var timeout = 10;
	if(id && mode==AJX_ASYNC)
	{
		if(ajx_thread!=id && ajx_busy){
			var timer = setTimeout(retry,timeout);
			return 0;
		}
		params=eval(params);
		ajx_thread=id;
	}
	ajx_busy=true;
	xhr_object.open("GET", "potajx/potajx.php?page="+page+"&action="+action+"&params="+params, mode==AJX_ASYNC);
	xhr_object.send(null);
	if(mode==AJX_ASYNC)
	{
		xhr_object.onreadystatechange = function()
		{
			if (xhr_object.readyState == 4) {
				if(ajx_get_id("ajx_loader"))
				{
					ajx_get_id("ajx_loader").className = "displaynone";
				}
				eval(xhr_object.responseText);
				ajx_busy = false;
				if(reset) ajx_thread=null;
				return 0;
			}
		};
	}else{
		if (xhr_object.readyState == 4) {
			if(ajx_get_id("ajx_loader"))
			{
				ajx_get_id("ajx_loader").className = "displaynone";
			}
			ajx_busy = false;
			return xhr_object.responseText;
		}
	}
}*/