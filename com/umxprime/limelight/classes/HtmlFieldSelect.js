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
function HtmlFieldSelect()
{
	this._fieldId = "";
 	this._fieldName = "";
 	this._fieldClass = "";
 	this._labelName = "";
 	this._labelClass = "";
 	this._label = "";
 	this._optionValues = new Array();
 	this._optionNames = new Array();
 	this._optionCount = 0;
 	this._error = false;
 	this._fieldIdsList = new Array();
}

function setFieldId(id)
{
	this._fieldId = id;
}

function setFieldName(name)
{
	this._fieldName=name;
}

function setFieldClass(classStr)
{
	this._fieldClass=classStr;
}
	
function setLabel(label)
{
	this._isThisFieldIdAlreadyBeenRendered();
	if(this._error)return;
	this._labelName=label;
	this._buildLabel();
}
	
function setLabelClass(classStr)
{
	this._labelClass=classStr;
}
	
function setFieldOptions(values,names)
{
	if(this._error)return;
	if(!this._isSameNumberOfArrayItems(values,names))
	{
		this._setErrorAsOption("Arrays are not of same length");
		return;
	}
	this._optionValues = values;
	this._optionNames = names;
	this._optionCount = values.length;
}
	
function clearFieldOptions()
{
	this._optionValues = new Array();
	this._optionNames = new Array();
	this._optionCount = 0;
}
	
function clearLabel()
{
	this._label = "";
	this._labelName = "";
}
	
function appendFieldOption(value,name)
{
	this._optionValues.push(value);
	this._optionNames.push(name);
	this._optionCount++;
}
	
function renderField()
{
	this._isThisFieldIdAlreadyBeenRendered();
	var render = "";
	render += this._isLabelSet()?this._label:"";
	render += "<select";
	render += this._isFieldIdSet()?" id=\""+this._fieldId+"\"":"";
	render += this._isFieldNameSet()?" name=\""+this._fieldName+"\"":"";
	render += this._isFieldClassSet()?" class=\""+this._fieldClass+"\"":"";
	render += ">\n";
	render += this._buildOptions();
	render += "</select>\n";
	this._fieldIdsList.push(this._fieldId);
	this._clearError();
	return render;
}
	
//private methods
function _isThisFieldIdAlreadyBeenRendered()
{
	for(var i=0;i<this._fieldIdsList.length;i++)
	{
		if(this._fieldIdsList[i]==this._fieldId) this._setErrorAsOption("The id \""+this._fieldId+"\" has already been rendered by this HtmlFieldSelect object");
	}
}
	
function _isSameNumberOfArrayItems(a,b)
{
	return a.length == b.length;
}
	
function _isFieldIdSet()
{
	return this._fieldId!="";
}
	
function _isFieldNameSet()
{
	return this._fieldName!="";
}
	
function _isFieldClassSet()
{
	return this._fieldClass!="";
}
	
function _isLabelSet()
{
	return this._label!="";
}
	
function _setErrorAsOption(error)
{
	this._optionValues = new Array("-1");
	this._optionCount = 1;
	this._optionNames = new Array("htmlFieldSelect error : "+error);
	this._error=true;
}
	
function _buildLabel()
{
	if(!this._isFieldIdSet()){
		this._setErrorAsOption("Id is needed before adding a label");
		return;
	}
	var label = "<label for=\""+this._fieldId+"\"";
	label += (this._labelClass!="")?" class=\""+this._labelClass+"\"":"";
	label += ">"+this._labelName+"</label>\n";
	this._label = label;
}
	
function _buildOptions()
{
	var options = "";
	for(var i=0;i<this._optionCount;i++)
	{
		options += "\t<option value=\""+this._optionValues[i]+"\">";
		options += this._optionNames[i];
		options += "</option>\n";
	}
	return options;
}
	
function _clearError()
{
	this._error=false;
}

///////////////

HtmlFieldSelect.prototype.setFieldId = setFieldId;
HtmlFieldSelect.prototype.setFieldName = setFieldName;
HtmlFieldSelect.prototype.setFieldClass = setFieldClass;
HtmlFieldSelect.prototype.setLabel = setLabel;
HtmlFieldSelect.prototype.setLabelClass = setLabelClass;
HtmlFieldSelect.prototype.setFieldOptions = setFieldOptions;
HtmlFieldSelect.prototype.clearFieldOptions = clearFieldOptions;
HtmlFieldSelect.prototype.clearLabel = clearLabel;
HtmlFieldSelect.prototype.appendFieldOption = appendFieldOption;
HtmlFieldSelect.prototype.renderField = renderField;
HtmlFieldSelect.prototype._isThisFieldIdAlreadyBeenRendered = _isThisFieldIdAlreadyBeenRendered;
HtmlFieldSelect.prototype._isSameNumberOfArrayItems = _isSameNumberOfArrayItems;
HtmlFieldSelect.prototype._isFieldIdSet = _isFieldIdSet;
HtmlFieldSelect.prototype._isFieldNameSet = _isFieldNameSet;
HtmlFieldSelect.prototype._isFieldClassSet = _isFieldClassSet;
HtmlFieldSelect.prototype._isLabelSet = _isLabelSet;
HtmlFieldSelect.prototype._setErrorAsOption = _setErrorAsOption;
HtmlFieldSelect.prototype._buildLabel = _buildLabel;
HtmlFieldSelect.prototype._buildOptions = _buildOptions;
HtmlFieldSelect.prototype._clearError = _clearError;