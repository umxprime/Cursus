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
require("connect_info.php");
require("connexion.php");
include("fonctions.php");
//echo $_GET["eval"];
include("upload_inc.php");
if($action="modifier"){
	$titre = ($_POST["titre"])?$_POST["titre"]:"Sans tire";
	$description = ($_POST["description"])?$_POST["description"]:"Sans commentaire";
	$credits = ($_POST["credits"])?$_POST["credits"]:"Droits r&eacute;serv&eacute;s";
	$article = ($_POST["article"])?$_POST["article"]:0;
	$ordre = ($_POST["ordre"])?$_POST["ordre"]:0;
	$date = ($_POST["date"])?$_POST["date"]:$today;
	$sql = "UPDATE INTO images SET credits='".$credits."', description='".$description."',";
	$sql .= " article='".$article."', ordre='".$ordre."', date='".$date."', titre='".$titre."' WHERE id='".$img_id."';";
	$res = mysql_query($sql);
}
if(isset($_GET["article_id"])){
		$req = "SELECT * FROM articles WHERE id='".$_GET['article_id']."';";
	$res = mysql_query($req);
	$article = mysql_fetch_array($res);
	}
if($_GET["img_id"]>0){
	$req = "SELECT * FROM images WHERE id='".$img_id."';";
	$resimg = mysql_query($req);
	$img = mysql_fetch_array($resimg);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<?php 
include("inc_css_thing.php");
?>
<title>Image pour article : <?php echo $article['titre']; ?></title>
</head>
<body>
<p>
<h2>Ajout d&apos;une image &agrave; l&apos;article : <?php echo $article['titre'];?></h2>
</p>
<p>
<form name="image" id="image" method="post" action="edit_image.php">

<table style="text-align: left; width: 626px; height: 228px;" border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 70px;">Intitul&eacute;</td>
      <td colspan="7" rowspan="1"><?php 
      $peut_ajouter=($_SESSION['auto']=="a")?1:0;
      echo selecteurObjets("edit_image.php","images","id","titre","id",$connexion,$img['id'],0,$peut_ajouter); ?>
	</td>
    </tr>
    <tr>
      <td style="width: 70px;">Titre</td>
      <td colspan="7" rowspan="1">
      <?php echo affiche_ligne("titre",$ligne['titre']); ?>
	</td>
      
    </tr>
    <tr>
    <td>titre</td>
      <td colspan="7" rowspan="1";">
      <?php echo affiche_champs("description",$ligne['description'],80); ?></td>
    </tr>
    <tr>
    <td>credits</td>
      <td colspan="5" rowspan="1";">
      <?php echo affiche_champs("credits",$ligne['credits'],80); ?></td>
      <td>ordre</td>
      <td>
      <?php echo selecteur_objets("",0,"ordre","ordre",$connexion,$img['ordre'],liste_numero(1,10,1,"",""),0); ?>
      </td>
    </tr>
    <tr>
    <td>description</td>
      <td colspan="7" rowspan="1";">
      <?php echo affiche_champs("description",$ligne['description'],80); ?></td>
    </tr>

<?php
	echo "<input type=\"hidden\" name=\"article\" value=\"".$article['id']."\" >";
	echo "<tr><td colspan=\"8\"><input type=\"submit\" value=\"modifier\" ></td></tr>";

?>

    <tr>
    <?php 
    if(isset($img['lien'])){
    	echo "<td colspan=\"8\"><img src=\"".$img['lien']."\" /><td>";
    }else{
    	include("upload_form.php");
    }
    ?>
    </tr>
    </tbody>
</table>
</form>

