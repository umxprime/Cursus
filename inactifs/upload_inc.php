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

if($_POST["action"] == "Envoyer Image")
{
	$image_titre = ($_POST["image_titre"])?$_POST["image_titre"]:"Sans tire";
	$image_commentaire = ($_POST["image_commentaire"])?$_POST["image_commentaire"]:"Sans commentaire";
	$image_credits = ($_POST["image_credits"])?$_POST["image_credits"]:"Droits r�serv�s";
	
	unset($imagename);
	
	if(!isset($_FILES) && isset($HTTP_POST_FILES))
	$_FILES = $HTTP_POST_FILES;
	
	if(!isset($_FILES['image_file']))
	$error["image_file"] = "An image was not found.";
	
	
	$imagename = basename($_FILES['image_file']['name']);
	//echo $imagename;
	
	if(empty($imagename))
	$error["imagename"] = "The name of the image was not found.";
	$cote = 440;
	switch ($id_clef){
		case "id_article" : $cote=440;
		break;
		case "id_lien" : $cote=156;
		break;
	}
	if(empty($error))
	{
		$result_full = image_format($_FILES['image_file']['name'],$_FILES['image_file']['tmp_name'], $cote,$cote,"images/");
		$result_carre = image_carre($_FILES['image_file']['name'],$_FILES['image_file']['tmp_name'], 26, "poucets/");
	}
	$sql = "INSERT INTO blorg_images (id_image, titre, ".$id_clef.",url_image, url_poucet, commentaires, credits)";
	$sql .= "VALUES ('', '".$image_titre."', '".$$id_clef."', '".$result_full."', '".$result_carre."',";
	$sql .= "'".$image_commentaire."','".$image_credits."');";
	
	mysql_query($sql);
}

include("upload_form.php");

?>
