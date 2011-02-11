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
	
 if ($PHPSESSID)
   session_start($PHPSESSID);
 else
   session_start();
require("connect_info.php");
require("connexion.php");
//include("image_fonctions.php");
//print_r($_SESSION);
//if($_SESSION['userid']>0){
//	$req = "INSERT INTO mouche (id,date, userid, type) values('','".date("Y-m-d H:i:s")."','".$_SESSION['userid']."','deconnexion');";
//	$res = mysql_query($req);
//	//echo "deconnexion op&eacute;r&eacute;e : ".$req;
//}
if($_POST["action"] == "Login")
{
	//error checking and results
	
	if(empty($_POST['username']))
	$error["username"] = "Un identifiant est requis.";
	
	if(empty($_POST['password']))
	$error["password"] = "Un mot de passe est requis.";
	
	//we have a username password to work with
	if(!is_array($error))
	{	
		session_unset();
		$username = trim(strtolower($_POST["username"]));
		$q = "SELECT * FROM etudiants WHERE log='".$username."';";
		$res = mysql_query ($q);
		$usr = mysql_fetch_array($res);
		$password = trim(strtolower($_POST["password"]));
		
		if($usr['passw'] == $password)
		{
			//echo $usr['passw']."===".$password;
			session_register("auto");
			$_SESSION['auto']= "e";
			session_register('userid');
			$_SESSION['etuid'] = $usr['id'];
			session_register('username');
			$_SESSION['username'] = $usr['prenom']." ".$usr['nom'];
//			$req = "INSERT INTO mouche (id,date, userid, type) values('','".date("Y-m-d H:i:s")."','".$usr['id']."','connexion');";
//			$res = mysql_query($req);
			//echo $req;
			
			header("Location: etudiants.php");
			
		}
		
		else{
		$error["login"] = "L&apos;indentification a &eacute;chou&eacute;.";
		
		}
		
	}
}
include("login_form.php");

if(is_array($error))
{
while(list($key, $val) = each($error))
{
echo $val;
echo "<br>\n";
}
}

?>
