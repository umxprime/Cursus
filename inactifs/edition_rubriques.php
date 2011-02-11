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
$id = ($_POST['id'])?$_POST['id']:$_GET['id'];
if(!$id or $id<0 or $id==$_POST['old_id']){
	$cols = liste_colonnes("rubriques");
	for($nc=0;$nc<count($cols);$nc++){
		$nom = $cols['titre'];
		$ligne[$nom]=(empty($_POST[$nom]))?-1:$_POST[$nom];
	}
}
else 
{
	$requete = "SELECT * FROM rubriques WHERE id = '".$id."';";
	$resultat = mysql_query($requete, $connexion);
	$ligne = mysql_fetch_array($resultat);
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
<title><?php echo $ligne['titre'] ?></title>
</head>
<body>
<form id="formulaire" name="formulaire" action="reg_rubrique.php" method="post">
<table style="text-align: left; width: 626px; height: 228px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      
      <td style="width: 70px;">Intitul&eacute;</td>
      <td colspan="7" rowspan="1"><?php 
      $peut_ajouter=($_SESSION['auto']=="a")?1:0;
      echo selecteurObjets("edition_rubriques.php","rubriques","id","titre","id",$connexion,$ligne['id'],0,$peut_ajouter,"titre"); ?>
	</td>
    </tr>
    <tr>
      <td style="width: 70px;">Modifier ici</td>
      <td colspan="3" rowspan="1">
      <?php echo affiche_ligne("titre",$ligne['titre'],20); ?>
	</td>
      <td>indication (en survol du titre)</td>
      <td colspan="3">
      <?php echo affiche_ligne("indication",$ligne['indication'],20); ?>
      </td>
    </tr>
    <tr>
      <td>Dans la rubrique</td>
 
      <td colspan="3">
		<?php echo selecteurObjets("","rubriques","parent","titre","id",$connexion,$ligne['parent'],0,1,"titre"); ?>
		</td>
 
      <td>Visible</td>
      <td>
      <input type="checkbox" name="visible" <?php echo ($ligne['visible'])?"checked":"" ?> />
      </td>
      <td>ordre</td>
      <td>
      <?php echo selecteur_objets("",0,"ordre","ordre",$connexion,$ligne['ordre'],liste_numero(0,20,1,"",""),0); ?>
     </td>
    </tr>
  </tbody>
</table>
<input type="hidden" name="old_id" value="<?php echo ($ligne["id"])?$ligne["id"]:-1; ?>">
<input type="submit" name="action" value="valider les modifications">
</form>
</body>
</html>