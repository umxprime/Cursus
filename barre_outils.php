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
?>
<div id="menuCursus">
<ul>
<?php
echo "<li";
echo ($outil=="modules")?" class=\"courant\" ":"";
echo "><a href=\"sessions.php?nPeriode=$semestre_courant\">modules</a></li>\n";
echo "<li";
echo ($outil=="tutorat")?" class=\"courant\" ":"";
echo "><a href=\"tutorats.php?nPeriode=$semestre_courant\">tutorats</a></li>\n";
if($droits[$_SESSION['auto']]["menu_utilisateurs"]==true){
	echo "<li";
	echo ($outil=="utilisateurs")?" class=\"courant\" ":"";
	echo "><a href=\"edition_utilisateurs.php?nPeriode=$semestre_courant\">utilisateurs</a></li>\n";
}
if($droits[$_SESSION['auto']]["menu_coordination"]==true){
	echo "<li";
	echo ($outil=="coordination")?" class=\"courant\" ":"";
	echo "><a href=\"vue_etu_sem.php?ns=1&nPeriode=$semestre_courant\">coordination</a></li>\n";
}
/*else{
	$req = "select session.id, modules.code, modules.obligatoire, modules.id from session, modules where session.periode='".$semestre_courant."' ";
	$req .= " and modules.id=session.module and modules.code LIKE 'PP_EVL_%' and modules.enseignants like'%".$_SESSION['username']."%';";
	//echo $req;
	$res = mysql_query($req);
	$nc=0;
	while($arr = mysql_fetch_array($res)){
			echo "<li";
			echo ($outil=="coordination")?" class=\"courant\" ":"";
			echo "><a href=\"vue_etu_sem.php?ns=".($arr['obligatoire']*1)."&nPeriode=$semestre_courant\">coordination</a></li>\n";
		}
		
	}*/
if($droits[$_SESSION['auto']]["menu_niveaux"]==true){
	echo "<li";
	echo ($outil=="niveaux")?" class=\"courant\" ":"";
	echo "><a href=\"passages_niveaux.php?nPeriode=$semestre_courant\">niveaux</a></li>\n";
}
echo "<li><a href=\"http://bugs.esa-npdc.net\" target=\"_blank\">signaler un bug</a></li>\n";

echo "<li";
echo ($outil=="infoperso")?" class=\"courant\" ":"";
echo "><a href=\"edition_prof.php?nPeriode=$semestre_courant\" title=\"Modifier votre mot de passe et indentifiant\">infos perso</a></li>\n";
echo "<li class=\"nomEnseignant\">".$_SESSION['username']."</li>\n";
echo "<li class=\"logoff\"><a href=\"login.php\">d&eacute;connexion</a></li>\n";
?>
</ul>
</div>