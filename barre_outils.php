<?php
	/**
	 * 
	 * Copyright © 2007,2008,2009 Roland DECAUDIN (roland@xcvbn.net)
	 * Copyright © 2008,2009,2010,2011 Maxime CHAPELET (umxprime@umxprime.com)
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
if($droits[$_SESSION['auto']]["menu_stages"]==true){
	echo "<li";
	echo ($outil=="stages")?" class=\"courant\" ":"";
	echo "><a href=\"gestion_stages.php?nPeriode=$semestre_courant\">stages</a></li>\n";
}
if($droits[$_SESSION['auto']]["menu_utilisateurs"]==true){
	echo "<li";
	echo ($outil=="utilisateurs")?" class=\"courant\" ":"";
	echo "><a href=\"edition_utilisateurs.php?nPeriode=$semestre_courant\">utilisateurs</a></li>\n";
}
if($droits[$_SESSION['auto']]["menu_coordination"]==true){
	echo "<li";
	echo ($outil=="coordination")?" class=\"courant\" ":"";
	if(!isset($niveau))$niveau=1;
	echo "><a href=\"vue_coordination.php?ns=$niveau&nPeriode=$semestre_courant\">coordination</a></li>\n";
}
if($droits[$_SESSION['auto']]["menu_niveaux"]==true){
	echo "<li";
	echo ($outil=="niveaux")?" class=\"courant\" ":"";
	//echo "><a href=\"passages_niveaux.php?nPeriode=$semestre_courant\">niveaux</a></li>\n";
	echo "><a href=\"edition_niveaux.php?nPeriode=$semestre_courant\">niveaux</a></li>\n";
}
if($droits[$_SESSION['auto']]["menu_reglages"]==true){
	echo "<li";
	echo ($outil=="reglages")?" class=\"courant\" ":"";
	echo "><a href=\"reglages.php?nPeriode=$semestre_courant\">réglages</a></li>\n";
}
echo "<li><a href=\"http://bugs.esa-npdc.net\" target=\"_blank\" title=\"Signaler un bug\">signaler un bug</a></li>\n";

//echo "<li";
//echo ($outil=="infoperso")?" class=\"courant\" ":"";
//echo "><a href=\"edition_prof.php?nPeriode=$semestre_courant\" title=\"Modifier votre mot de passe et indentifiant\">infos perso</a></li>\n";
echo "<li class=\"nomEnseignant\">".utf8_encode($_SESSION['username'])."</li>\n";
include "inc_alertes.php";
echo "<li class=\"alertes\"><a href=\"vue_alertes.php?id=".$_SESSION["userid"]."&nPeriode=$semestre_courant\" class=\"".(($nAlertes>0)?"pb":"ok")." ".(($outil=="alertes")?"courant":"")."\">"."$nAlertes</a></li>\n";
echo "<li class=\"logoff\"><a href=\"login.php\">déconnexion</a></li>\n";
?>
</ul>
</div>