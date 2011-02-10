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

	$url = $_SERVER['PHP_SELF'];
	$path = explode("/",$url);
	
	if(!isset($limelightModule))
	{
		$limelightModule = $path[count($path)-1];
		$limelightModule = explode(".php",$limelightModule);
		$limelightModule = $limelightModule[0];
	}
	if(!isset($_LIMELIGHT_PATH)) $_LIMELIGHT_PATH = "";
	?>
	<script type="text/javascript">
		var AJXModule = "<?php echo $limelightModule;?>";
		var LIMELIGHT_PATH = "<?php echo $_LIMELIGHT_PATH;?>";
	</script>
	<script type="text/javascript" src="<?php echo $_LIMELIGHT_PATH;?>core/tools.js"></script>
	<script type="text/javascript" src="<?php echo $_LIMELIGHT_PATH;?>core/potajx.js"></script>
	<?php
	echo "<script type=\"text/javascript\" src=\"".$_LIMELIGHT_PATH."classes/HtmlFieldSelect.js\"></script>";
	echo "<script type=\"text/javascript\" src=\"".$_LIMELIGHT_PATH."modules/ajx_".$limelightModule.".js\"></script>";
	include_once($_LIMELIGHT_PATH."classes/HtmlFieldSelect.php");
	include_once($_LIMELIGHT_PATH."classes/HtmlFieldInputText.php");
	function ajx_span($id)
	{
		echo "<span id=\"ajx_$id\"></span>";
	}
?>