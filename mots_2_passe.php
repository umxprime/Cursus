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

function generatePassword ($length = 8)
{

	// start with a blank password
	$password = "";

	// define possible characters
	$possible = "123456789bcdfghjkmnpqrstvwxyz";
	$v = "aeiou" ;
	$c = "bcdfghjkmnpqrstvwxyz";
	$alterv = "@&!#%:1346";
	$alterc = "()=$[].891234567";
	$num = "123456789";
	$spe = "!@#$%&*()-+";
	// set up a counter
	$i = 0;
	$chs = Array("cvcvcvcv", "cvvcvvcv","cvcvvcvv","cvvcvcvv");
	$pch = $chs[mt_rand(0,count($chs)-1)];
	//echo $pch;
	// add random characters to $password until $length is reached
	while ($i < strlen($pch)) {

		$var = substr($pch,$i,1);
		//echo $var." : ".$i."<br />\n";
		if (mt_rand(0,100)<95){
				
			$pioche = $$var;
		}else{
			$var ="alter".$var;
			$pioche = $$var;
		}
		//echo $var." : ".$$var."<br />\n";
		// pick a random character from the possible ones
		$char = substr($pioche, mt_rand(0, strlen($pioche)-1), 1);

		// we don't want this character if it's already in the password
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}

	}

	// done!
	return $password;

}
include("etuauto.php");
//echo $idd_session."|";
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
//echo $idd_session."|";
require("connexion.php");
//echo $idd_session."|";
include("fonctions.php");
//echo $idd_session."|";
include("inc_sem_courant.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<?php
include("inc_css_thing.php");
?>
<title><?php echo $module['intitule'] ?></title>
</head>
<body>
<code>
<?php
$sql = "SELECT * FROM etudiants";
$res = mysql_query($sql);

while ($etudiant=mysql_fetch_array($res)) {
	
	$prenomFormat = strtolower(str_replace(utf8_encode("�"),"e",$etudiant['prenom']));
	$nomFormat = strtolower($etudiant['nom']);
	$destsMail = $prenomFormat{0}.$nomFormat."@esa-cambrai.net>";

	//if($etudiant['mail']=='' or $etudiant['passw']=='' or $etudiant['log']=''){
		$prenomFormat = strtolower(str_replace(utf8_encode("�"),"e",$etudiant['prenom']));
		$nomFormat = strtolower($etudiant['nom']);
		$log = $prenomFormat{0}.$nomFormat;
		if($etudiant['mail']==''){

			$mail = $log."@esa-cambrai.net";
		}else{
			$mail = $etudiant['mail'];
		}
		if($etudiant['passw']==''){
			$passw = generatePassword();
		}else{
			$passw = $etudiant['passw'];
		}
		$header = "From: rdecaudin@esa-cambrai.net";
		//$req = "update etudiants set passw='".$passw."', mail='".$mail."' where id=".$etudiant['id'].";";
		$req =  "update etudiants set log='".$log."' where id=".$etudiant['id'].";";
		$mess = "Bonjour, \n";
		$mess .= "Vous avez desormais acces aux informations concernant votre scolarite a cette adresse : \n";
		$mess .= "http://www.esa-cambrai.net/cursus/etudiants.php\n";
		$mess .= "votre identitfiant est : ".$log."\n";
		$mess .= "votre mot de passe est : ".$passw."\n";
		$mess .= "\n\nBonne Lecture\n";
		$mess .= "\n\nRoland Decaudin";
		$mess .= "\n\nNB tous les o sont des lettres les mots de passe ne contiennent pas le chiffre 0";
		//$res_envoi = mail($mail,"acces a votre bulletins par Internet",$mess,$header);
		//$res_update = mysql_query($req);
		//echo $mess."<br />";
		echo $req."<br />";

	}
//}
?> </code>
</body>
</html>
