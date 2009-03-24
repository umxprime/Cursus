<?php 
	/**
	 * 
	 * Copyright © 2007,2008,2009 Roland DECAUDIN (roland@xcvbn.net)
	 * Copyright © 2008,2009 Maxime CHAPELET (umxprime@umxprime.com)
	 *
	 * This file is a part of Cursus
	 *
	 * Cursus is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * Cursus is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with Cursus.  If not, see <http://www.gnu.org/licenses/>.
	 *
	 * Cursus uses a modified version of TinyButStrong and TinyButStrongOOo
	 * originally released under the LGPL <http://www.gnu.org/licenses/>
	 * by Olivier LOYNET (tbsooo@free.fr)
	 *
	 * Cursus uses Potajx
	 * released under the GPL <http://www.gnu.org/licenses/>
	 * by Maxime CHAPELET (umxprime@umxprime.com)
	 * 
	 **/

function image_format($imagename,$orig_dir, $maxwidth, $maxheight, $dest_dir)
{

	if(empty($imagename))
	return false;
	
	$orig["dirimage"] = $orig_dir;
	
	if(!is_file($orig["dirimage"]))
	return "pas de fichier trouv�";
	
	$orig["res"] = @imagecreatefromjpeg($orig["dirimage"]);
	$orig["x"] = imagesx($orig["res"]);
	$orig["y"] = imagesy($orig["res"]);
	
	if($orig["x"] > $orig["y"])
	{
		$new["x"] = $maxwidth;
		$new["y"] = ($maxheight / $orig["x"]) * $orig["y"];
	}
	else
	{
		$new["y"] = $maxheight;
		$new["x"] = ($maxwidth / $orig["y"] ) * $orig["x"];
	}
	
	$new["res"] = imagecreatetruecolor($new["x"],$new["y"]);
	
	//set background to white
	$fill = imagecolorallocate($new["res"], 255, 255, 255);
	imagefill($new["res"], 0, 0, $fill);
	imagecopyresized($new["res"], $orig["res"], 0, 0, 0, 0, $new["x"], $new["y"], $orig["x"], $orig["y"]);
	
	$new["dir"] = $dest_dir;
	$new["dirimage"] = $new["dir"] . substr($imagename,0,strlen($imagename)-4) . "_format.jpg";
	imagejpeg($new["res"], $new["dirimage"]);
	
	imagedestroy($orig["res"]);
	imagedestroy($new["res"]);
	
	return $new["dirimage"];
}

function image_carre($imagename, $orig_dir, $cote, $dest_dir){
	if(empty($imagename))
	return false;
	
	if(!is_file($orig_dir))
	return "pas de fichier trouv�";
	
	$orig["res"] = @imagecreatefromjpeg($orig_dir);
	$orig["x"] = imagesx($orig["res"]);
	$orig["y"] = imagesy($orig["res"]);
	if ($orig["x"]>$orig["y"]){
		$orig["max"]=0.6*$orig["y"];
		$orig["left"] = 0.2*$orig["y"];
		$orig["top"] = ($orig["x"]-$orig["max"])/2;
		$orig["right"] = $orig["left"]+$orig["max"];
		$orig["bottom"] = $orig["top"]+$orig["max"];
	}
	else {
		$orig["max"]=0.6*$orig["x"];
		$orig["left"] = 0.2*$orig["x"];
		$orig["top"] = ($orig["y"]-$orig["max"])/2;
		$orig["right"] = $orig["left"]+$orig["max"];
		$orig["bottom"] = $orig["top"]+$orig["max"];
	}
	
	$new["res"] = imagecreatetruecolor($cote,$cote);
	imagecopyresized($new["res"], $orig["res"], 0, 0, $orig["left"], $orig["top"], $cote, $cote, $orig["max"], $orig["max"]);
	
	$new["dir"] = $dest_dir;
	$new["dirimage"] = $new["dir"] . substr($imagename,0,strlen($imagename)-4) . "_carre.jpg";
	imagejpeg($new["res"], $new["dirimage"]);
	
	imagedestroy($orig["res"]);
	imagedestroy($new["res"]);
	
	return $new["dirimage"];
}
?>
