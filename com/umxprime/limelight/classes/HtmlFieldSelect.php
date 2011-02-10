<?php
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
	class HtmlFieldSelect{
	 	// private vars
	 	var $_fieldId="";
	 	var $_fieldName="";
	 	var $_fieldClass="";
	 	var $_labelName="";
	 	var $_labelClass="";
	 	var $_label="";
	 	var $_selectedOption=-1;
	 	var $_optionValues = array();
	 	var $_optionNames = array();
	 	var $_optionCount = 0;
	 	var $_error = false;
	 	var $_fieldIdsList=array();
	 	
	 	// constructor
	 	function __construct(){}
	 	
	 	// public methods
	 	function setFieldId($id)
	 	{
	 		$this->_fieldId=$id;
	 	}
	 	
		function setFieldName($name)
	 	{
	 		$this->_fieldName=$name;
	 	}
	 	
		function setFieldClass($class)
	 	{
	 		$this->_fieldClass=$class;
	 	}
	 	
		function setLabel($label)
	 	{
	 		$this->_isThisFieldIdAlreadyBeenRendered();
	 		if($this->_error)return;
	 		$this->_labelName=$label;
	 		$this->_buildLabel();
	 	}
	 	
	 	function setLabelClass($class)
	 	{
	 		$this->_labelClass=$class;
	 	}
	 	
	 	function setFieldOptions($values,$names)
	 	{
	 		if($this->_error)return;
	 		if(!$this->_isSameNumberOfArrayItems($values,$names))
	 		{
	 			$this->_setErrorAsOption("Arrays are not of same length");
	 			return;
	 		}
	 		$this->_optionValues = $values;
	 		$this->_optionNames = $names;
	 		$this->_optionCount = count($values);
	 	}
	 	
	 	function clearFieldOptions()
	 	{
	 		$this->_optionValues = array();
	 		$this->_optionNames = array();
	 		$this->_optionCount = 0;
	 	}
	 	
		function clearLabel()
	 	{
	 		$this->_label = "";
	 		$this->_labelName = "";
	 	}
	 	
	 	function appendFieldOption($value,$name)
	 	{
	 		array_push($this->_optionValues,$value);
	 		array_push($this->_optionNames,$name);
	 		$this->_optionCount++;
	 	}
	 	
	 	function renderField()
	 	{
	 		$this->_isThisFieldIdAlreadyBeenRendered();
	 		$render = "";
	 		$render.= $this->_isLabelSet()?$this->_label:"";
	 		$render.= "<select";
	 		$render.= $this->_isFieldIdSet()?" id=\"".$this->_fieldId."\"":"";
	 		$render.= $this->_isFieldNameSet()?" name=\"".$this->_fieldName."\"":"";
	 		$render.= $this->_isFieldClassSet()?" class=\"".$this->_fieldClass."\"":"";
	 		$render.= ">\n";
	 		$render.= $this->_buildOptions();
	 		$render.= "</select>\n";
	 		array_push($this->_fieldIdsList,$this->_fieldId);
	 		echo $render;
	 		$this->_clearError();
	 	}
	 	
	 	function selectOption($option)
	 	{
	 		$this->_selectedOption = $option;
	 	}
	 	
	 	//private methods
	 	private function _isThisFieldIdAlreadyBeenRendered()
	 	{
	 		for($i=0;$i<count($this->_fieldIdsList);$i++)
	 		{
	 			if($this->_fieldIdsList[$i]==$this->_fieldId) $this->_setErrorAsOption("The id \"".$this->_fieldId."\" has already been rendered by this htmlFieldSelect object");
	 		}
	 	}
	 	
	 	private function _isSameNumberOfArrayItems($a,$b)
	 	{
	 		return count($a)==count($b);
	 	}
	 	
		private function _isFieldIdSet()
	 	{
	 		return $this->_fieldId!="";
	 	}
	 	
		private function _isFieldNameSet()
	 	{
	 		return $this->_fieldName!="";
	 	}
	 	
		private function _isFieldClassSet()
	 	{
	 		return $this->_fieldClass!="";
	 	}
	 	
	 	private function _isLabelSet()
	 	{
	 		return $this->_label!="";
	 	}
	 	
	 	private function _setErrorAsOption($error)
	 	{
	 		$this->_optionValues = array("-1");
	 		$this->_optionCount = 1;
	 		$this->_optionNames = array("htmlFieldSelect error : ".$error);
	 		$this->_error=true;
	 	}
	 	
	 	private function _buildLabel()
	 	{
	 		if(!$this->_isFieldIdSet()){
	 			$this->_setErrorAsOption("Id is needed before adding a label");
	 			return;
	 		}
	 		$label = "<label for=\"".$this->_fieldId."\"";
	 		$label.= ($this->_labelClass!="")?" class=\"".$this->_labelClass."\"":"";
	 		$label.= ">".$this->_labelName."</label>\n";
	 		$this->_label = $label;
	 	}
	 	
	 	private function _buildOptions()
	 	{
	 		$options = "";
	 		for($i=0;$i<$this->_optionCount;$i++)
	 		{
	 			$options .= "\t<option value=\"".$this->_optionValues[$i]."\"";
	 			if($this->_selectedOption==$this->_optionValues[$i])$options .= " selected ";
	 			$options .= ">";
	 			$options .= $this->_optionNames[$i];
	 			$options .= "</option>\n";
	 		}
	 		return $options;
	 	}
	 	
		private function _clearError()
	 	{
	 		$this->_error=false;
	 	}
	 }
?>