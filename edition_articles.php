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
	$cols = liste_colonnes("articles");
	for($nc=0;$nc<count($cols);$nc++){
		$nom = $cols[$nc]['nom'];
		//echo "recup de : ".$nom."\n";
		$ligne[$nom]=(empty($_POST[$nom]))?-1:$_POST[$nom];
	}
}
else 
{
	$requete = "SELECT * FROM articles WHERE id = '".$id."';";
	$resultat = mysql_query($requete, $connexion);
	$ligne = mysql_fetch_array($resultat);
	$expDate = explode("-",$ligne['date']);
	$date = array('annee'=>$expDate[0],'mois'=>$expDate[1],'jour'=>$expDate[2]);
	$expDate = explode("-",$ligne['date_annonce']);
	$date_annonce = array('annee'=>$expDate[0],'mois'=>$expDate[1],'jour'=>$expDate[2]);
}
if(isset($_GET["id_module"])){
	$id_module = $_GET["id_module"];
	$req = "select id, intitule from modules where id='".$id_module."';";
	$res = mysql_query($req);
	$module = mysql_fetch_array($res);
	$dependance = "\n<td>Module :</td>\n";
    $dependance .= "<td colspan=\"3\">\n";
    $dependance .= "<a href=\"edition_modules.php?id=\"".$module['id']."\">".$module['intitule']."</a>";
    $dependance .= "\n  </td>";
}else if(isset($_GET["id_session"])){
	$id_session = $_GET["id_session"];
	$req = "select session.id, modules.intitule from session,modules where session.id='".$id_session."' AND modules.id=session.module;";
	$res = mysql_query($req);
	$session = mysql_fetch_array($res);
	$dependance = "\n<td>Session :</td>\n";
    $dependance .= "<td colspan=\"3\">\n";
    $dependance .= "<a href=\"gestion_modules.php?session=\"".$session['id']."\">".$session['intitule']."</a>";
    $dependance .= "\n  </td>";
}else{
	$dependance = "\n<td>Rubrique :</td>\n";
    $dependance .= "<td colspan=\"3\">\n";
    $dependance .= selecteurObjets("","rubriques","rubrique","titre","id",$connexion,$ligne['rubrique'],0,0,"titre");
    $dependance .= "\n  </td>";
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
<title>Edition article <?php echo $article['titre'] ?></title>
</head>
<body>
<form id="formulaire" name="formulaire" action="reg_article.php" method="post">
<table style="text-align: left; width: 626px; height: 228px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      
      <td style="width: 70px;">Titre</td>
      <td colspan="7" rowspan="1"><?php 
      $peut_ajouter=($_SESSION['auto']=="a")?1:0;
      echo selecteurObjets("edition_articles.php","articles","id","titre","id",$connexion,$ligne['id'],0,$peut_ajouter, "titre"); ?>
	</td>
    </tr>
    <tr>
      <td style="width: 70px;">Modifier ici</td>
      <td colspan="3" rowspan="1">
      <?php echo affiche_ligne("titre",$ligne['titre'], 30); ?>
	</td>
      <?php echo $dependance; ?>
    </tr>

    <tr>
      <td style="width: 70px;">Indication (survol du titre)</td>
      <td colspan="3">
      <?php echo affiche_ligne("indication",$ligne['indication'],60); ?>
      </td>
 	<td>Visible</td>
      <td>
      <input type="checkbox" name="visible" <?php echo ($ligne['visible'])?"checked":"" ?> />
      </td>
      <td>ordre</td>
      <td>
      <?php echo selecteur_objets("",0,"ordre","ordre",$connexion,$ligne['ordre'],liste_numero(0,20,1,"",""),0); ?>
     </td>
    <tr>
      <td colspan="2" rowspan="1" style="width: 70px;">
      R&eacute;sum&eacute;
      </td>
      <td colspan="6" rowspan="1" >
       <?php echo affiche_champs("resume",$ligne['resume'],80,4); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" rowspan="1" style="width: 70px;">
      Corps du texte
      </td>
      <td colspan="6" rowspan="1" style="width: 70px;">
       <?php echo affiche_champs("text",$ligne['text'],80,10); ?>
      </td>
    </tr>
    <tr>
    <?php 
    echo "<td colspan=\"1\"><div class=\"selecteur_dates\" >Date de l&apos;article : </div></td>\n<td colspan=\"3\">";
	echo selecteurDate("edition_articles.php","date", $date['mois'] , $date['annee'], $date['jour']);
	echo "\n</td></div>";
	echo "<td colspan=\"1\"><div class=\"selecteur_dates\" >Date de l&apos;&eacute;v&eacute;nement : </div></td>\n<td colspan=\"3\">";
	echo selecteurDate("edition_articles.php","date_annonce", $date_annonce['mois'] , $date_annonce['annee'], $date_annonce['jour']);
	echo "\n</td></div>";
	?>
	</tr>
  </tbody>
</table>
<input type="hidden" name="old_id" value="<?php echo ($ligne["id"])?$ligne["id"]:-1; ?>">
<input type="hidden" name="auteur" value="<?php echo ($ligne["auteur"])?$ligne["auteur"]:$_SESSION["userid"]; ?>">
<input type="submit" name="action" value="valider les modifications">
</form>
</body>
</html>