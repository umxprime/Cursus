/**
 * 
 * Copyright © 2009 Maxime CHAPELET (umxprime@umxprime.com)
 *
 * This file is a part of Potajx
 *
 * Potajx is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Potajx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Potajx.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

var ajx_busy=false;
var ajx_thread=null;
var AJX_ASYNC=1;
var AJX_SYNC=2;

function ajx(page,action,params,mode,id,reset)
{
	if(ajx_get_id("ajx_loader")) ajx_get_id("ajx_loader").className = "displayblock";
	var retry = function(){
			ajx(page,action,params,mode,id,reset);
	};
	var xhr_object = null;
	if (window.XMLHttpRequest) // Firefox
		xhr_object = new XMLHttpRequest();
	else if (window.ActiveXObject) // Internet Explorer
		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non supporté par le navigateur
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	}
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
}

function ajx_get_id(id)
{
	return document.getElementById(id);
}

function ajx_get_value(id)
{
	return ajx_get_id(id).value;
}

function ajx_vide(id)
{
	ajx_get_id("ajx_"+id).innerHTML="";
}

function ajx_cache(id)
{
	ajx_get_id(id).className = "off";
}

function ajx_montre(id)
{
	ajx_get_id(id).className = "on";
}

function ajx_content(id,content)
{
	ajx_get_id("ajx_"+id).innerHTML=content;
}

function ajx_fill(id,val)
{
	ajx_get_id(id).innerHTML = val;
}

function ajx_select(id,onchangefunc,values,addnew,baseselected)
{
	//alert(ajx_busy);
	var element = ajx_get_id("ajx_"+id);
	var selected;
	if (ajx_get_id(id))
	{
		ajx_get_id(id).blur();
		selected = ajx_get_value(id);
	}
	var content="";
	content+="<select id="+id+">";
	var a_values;
	a_values = values.split(",");

	if (addnew) content += "<option value='new'>Nouvel Enregistrement</option>";
	for(var i=0;i<a_values.length;i++)
	{
		var a_value = a_values[i].split(":");
		content += "<option ";
		if (baseselected) content += a_value[2]=="selected"?"selected ":"";
		else content += a_value[0]==selected?"selected ":"";
		content += "value="+a_value[0];
		content += ">";
		content += a_value[1];
		content += "</option>";
	}
	content+="</select>";
	element.innerHTML = content;
	ajx_get_id(id).onchange=eval(onchangefunc);
}

function ajx_inputTexts(fields)
{
	/*
	 * Ecrit un lot d' <input type="text"/> dans des blocs html identifiés par des id, selon des champs de bdd
	 * Arguments :
	 * fields = "id_html1:champs_bdd1,id_html2:champs_bdd2,..."
	 */
	fields = fields.split(",");
	for (i = 0; i < fields.length; i++)
	{
		var field = fields[i].split(":")[0];
		var value = fields[i].split(":")[1];
		var element = ajx_get_id("ajx_"+field);
		var content = "";
		if (!value) value="";
		content += "<input id=\""+field+"\" type=\"text\" value=\""+value+"\"/>";
		element.innerHTML = content;
	}
}

function ajx_genMotDePasse(field)
{
	var element = ajx_get_id(field);
	var patterns = ["xyxyxzzz","yxyxyzzz","zzxyxyzz","zzyxyxzz"];
	var pattern = patterns[Math.floor(Math.random()*patterns.length)];
	var x = "aeiouy";
	var y = "zrtpqsdfghjklmwxcvbn";
	var z = "1234567890";
	var i;
	var passw = "";
	var chr;
	for (i=0; i<pattern.length; i++)
	{
		switch (pattern.substr(i,1))
		{
			case "x":
				chr = x.substr(Math.floor(Math.random()*x.length),1);
				break;
			case "y":
				chr = y.substr(Math.floor(Math.random()*y.length),1);
				break;
			case "z":
				chr = z.substr(Math.floor(Math.random()*z.length),1);
				break;
		}
		passw += chr;
	}
	element.value = passw;
}

function ajx_submit(page,action,params)
{
	var message = ajx(page,action,params);
	return message;	
}

//Cross-browser implementation of element.addEventListener()
function addListener(element, type, expression, bubbling) {
	bubbling = bubbling || false;

	if (window.addEventListener) { // Standard
		element.addEventListener(type, expression, bubbling);
		return true;
	} else if (window.attachEvent) { // IE
		element.attachEvent('on' + type, expression);
		return true;
	} else
		return false;
}

function ajx_uppercase(evt)
{
	evt.target.value = evt.target.value.toUpperCase();
}

function ajx_instore(str)
{
	var reg_virgule=new RegExp("(,)", "g");
	var reg_dpoint=new RegExp("(:)", "g");
	return encodeURIComponent(str.replace(reg_virgule,"%#%").replace(reg_dpoint,"%##%"));
}