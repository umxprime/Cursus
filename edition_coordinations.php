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

include("lesotho.php");
include("fonctions.php");
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
if($_SESSION['auto']!='a'){$id=$_SESSION['userid'];}

//echo "id= ".$id;
//echo "old_id= ".$_POST['old_id'];
$message_rec= "Valider les modifications";
echo $_POST["action"];
$ok_edit=($_SESSION['auto']=='a')?1:0;
//echo "ok_edit : ".$ok_edit;
if($ok_edit==1){
	if($_POST["action"]==$message_rec){
	for($an = 1; $an<=5; $an++){
		$nomvar = "coord_".$an;	
		$req = "update modules set enseignants='".$_POST[$nomvar]."' where code like 'PP_EVL_".($an*2)."' or code like 'PP_EVL_".($an*2-1)."';";
			$res=mysql_query($req);
		echo mysql_error($res);
		echo "requete : ".$req." <br />\n";
		echo "ok coord_".$an." <br />\n";
		}
	}
	for($an = 1; $an<=5; $an++){
			$req = "select id,enseignants from modules where code like 'PP_EVL_".($an*2)."';";
			$res=mysql_query($req);
			$arr = mysql_fetch_array($res);
			$nomvar = "coord_".$an;
			$$nomvar= $arr["enseignants"];
		}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<link rel="stylesheet" href="cursus.css" type="text/css">
<title>&Eacute;dition des coordinations</title>
</head>
<body>
<form method="post" action="edition_coordinations.php" name="formulaire">
<table style="align:center; text-align: left; width: 300px;" border="1" cellpadding="2"
	cellspacing="2">
	<tbody>
		<tr>
			<td style="width: 149px;">Coordination ann&eacute;e 1</td>
			<td style="width: 149px;">
			<?php echo selecteurObjets("","professeurs","coord_1","nom_complet","nom_complet",$connexion,$coord_1,0,0,"nom"); ?></td>
		</tr>
		<tr>
			<td style="width: 149px;">Coordination ann&eacute;e 2</td>
			<td style="width: 149px;">
			<?php echo selecteurObjets("","professeurs","coord_2","nom_complet","nom_complet",$connexion,$coord_2,0,0,"nom"); ?></td>
		</tr>
		<tr>
			<td style="width: 149px;">Coordination ann&eacute;e 3</td>
			<td style="width: 149px;">
			<?php echo selecteurObjets("","professeurs","coord_3","nom_complet","nom_complet",$connexion,$coord_3,0,0,"nom"); ?></td>
		</tr>
		<tr>
			<td style="width: 149px;">Coordination ann&eacute;e 4</td>
			<td style="width: 149px;">
			<?php echo selecteurObjets("","professeurs","coord_4","nom_complet","nom_complet",$connexion,$coord_4,0,0,"nom"); ?></td>
		</tr>
		<tr>
			<td style="width: 149px;">Coordination ann&eacute;e 5</td>
			<td style="width: 149px;">
			<?php echo selecteurObjets("","professeurs","coord_5","nom_complet","nom_complet",$connexion,$coord_5,0,0,"nom"); ?></td>
		</tr>
	</tbody>
</table>

<input type="submit" name="action" value="<?php echo $message_rec; ?>" />
</form>

</body>
</html>
<?php
}
?>
