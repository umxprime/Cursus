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

function gEBI(id)
{
	return document.getElementById(id);
}

function gVBI(id)
{
	return gEBI(id).value;
}

function showLoader()
{
	if(gEBI("ajxLoader")) gEBI("ajxLoader").className = "ajxLoaderDisplay";
}

function hideLoader()
{
	if(gEBI("ajxLoader")) gEBI("ajxLoader").className = "ajxLoaderHide";
}

function htmlFieldSelectAppendOption(text,value)
{
	var elt = this;
	var option = document.createElement("option");
	option.text=text;
	option.value = value;
	try{
		elt.add(option,null);
	}catch(error){
		elt.add(option);
	}
}
HTMLSelectElement.prototype.appendOption = htmlFieldSelectAppendOption;

function htmlFieldSelectUpdateSelectedOption(text,value)
{
	var elt = this;
	var option = document.createElement("option");
	option.text = text;
	option.value = value;
	var index = elt.selectedIndex;
	elt.options[index]=option;
	elt.value = value;
}
HTMLSelectElement.prototype.updateSelectedOption = htmlFieldSelectUpdateSelectedOption;

function htmlFieldSelectClearOptions()
{
	var elt = this;
	while(elt.length)
	{
		elt.remove(0);
	}
}
HTMLSelectElement.prototype.clearOptions = htmlFieldSelectClearOptions;

function htmlFieldInputTextClear()
{
	var elt=this;
	elt.value="";
}
HTMLInputElement.prototype.clearValue = htmlFieldInputTextClear;

function genererMotDePasse()
{
	var element = this;
	var patterns = ["xyxyxzzz","yxyxyzzz","zzxyxyzz","zzyxyxzz","zzzxyzzz"];
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
HTMLInputElement.prototype.genererMotDePasse = genererMotDePasse;