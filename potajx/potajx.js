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

function ajx(page,action,params,evaluate){
	var xhr_object = null;
	
	if (window.XMLHttpRequest) // Firefox
		xhr_object = new XMLHttpRequest();
	else if (window.ActiveXObject) // Internet Explorer
		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non supporté par le navigateur
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	}
	
	xhr_object.open("GET", "potajx/potajx.php?page="+page+"&action="+action+"&params="+params, false);
	xhr_object.send(null);
	if (xhr_object.readyState == 4) {
		if (evaluate){
			eval(xhr_object.responseText);
			return 0;
		}
		return xhr_object.responseText;
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

function ajx_select(id,onchangefunc,values,page,action,params,addnew,baseselected)
{
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
	if (values=="ajx")
	{
		a_values = ajx(page,action,params,false);
		a_values = a_values.split(",");
	}
	else a_values = values.split(",");

	if (addnew) content += "<option value='new'>Nouvel Enregistrement</option>"
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

function ajx_inputTexts(fields,page,action,params)
{
	/*
	 * Ecrit un lot d' <input type="text"/> dans des blocs html identifiés par des id, selon des champs de bdd
	 * Arguments :
	 * fields = "id_html1:champs_bdd1,id_html2:champs_bdd2,..."
	 * page = "nom_de_la_lib_php_associée_à_la_page_à_traiter"
	 * action = "nom_de_la_fonction_à_utiliser_dans_la_lib"
	 * params = "param1:value1,param2:value2,..." paramêtres à passer dans la fonction déclarée dans action
	 */
	var a_elements = fields.split(",");
	var a_req = [];
	var a_fields = [];
	var i;
	for (i = 0; i < a_elements.length; i++)
	{
		var elements = a_elements[i].split(":")
		a_fields.push(elements[0]);
		a_req.push(elements[1]);
	}
	a_req = a_req.join(";");
	var values = ajx(page,action,"fields:"+a_req+","+params,false);
	values = values.split(",");
	for (i = 0; i < a_fields.length; i++)
	{
		var element = ajx_get_id("ajx_"+a_fields[i]);
		var content = "";
		if (!values[i]) values[i]="";
		content += "<input type=\"text\" value=\""+values[i]+"\"/>";
		element.innerHTML = content;
	}
}