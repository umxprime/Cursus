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

//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
$requete= "select * from modules where pre_requis like 'Module obligatoire du semestre 1'";
$resreq = mysql_query($requete);
echo "erreur : ".mysql_error();
$nligne = 1;
while($module = mysql_fetch_array($resreq)){
	$var1 = "intitule_module_".$nligne;
	$var2 = "code_module_".$nligne;
	$$var1 = $module["intitule"];
	$$var2 = $module["code"];
	$nligne++;
}
//en + : note1_module_n;
//en + : cred1_module_n;
//en + : appr1_module_n;

//en + : note2_module_n;
//en + : cred2_module_n;
//en + : appr2_module_n;

//en + : total1_credits;
//en + : total2_credits;


while($nligne <19){
	$var1 = "intitule_module_".$nligne;
	$var2 = "code_module_".$nligne;
	$$var1 = "";
	$$var2 = "";
	$nligne++;
}
mysql_free_result($resreq);

//les classes de traitement des fichiers openOffice
include_once('tbs_class.php');
include_once('tbsooo_class.php');
$requete= "select * from etudiants where semestre = 1";
$resreq = mysql_query($requete);
echo "erreur : ".mysql_error();
while($etudiant = mysql_fetch_array($resreq)){
	$nom_etudiant = $etudiant["nom"];
	$prenom_etudiant = $etudiant["prenom"];
	$semestre = 1;
	$tuteur_1 = $etudiant["tuteur_1"];
	$tuteur_2 = $etudiant["tuteur_2"];
	// instantiate a TBS OOo class
	$OOo = new clsTinyButStrongOOo;

	// setting the object
	//$OOo->SetZipBinary('c:\\zippers\\zip\\zip.exe');
	$OOo->SetZipBinary('/usr/bin/zip');
	//$OOo->SetUnzipBinary('c:\\zippers\\unzip\\unzip.exe');
	$OOo->SetUnzipBinary('/usr/bin/unzip');
	//$OOo->SetProcessDir("C:\\wamp\\www\\crusus\\temp\\");
	$OOo->SetProcessDir('/temp');
	$OOo->SetDataCharset('ISO 8859-1');
	//$destDir = "C:\\wamp\\www\\crusus\\oodest\\";	
	$destDir = getcwd."/oodest/";
	//$docSrc = "C:\\wamp\\www\\crusus\\oosrc\\bulletin_indiv_v4.ots";
	$docSrc = getcwd."/oosrc/bulletin_indiv_v4.ots";


	// create a new openoffice document from the template with an unique id
	$OOo->NewDocFromTpl($docSrc);
	$path = $OOo->GetPathnameDoc();
	// merge data with OOo file content.xml
	$OOo->LoadXmlFromDoc('content.xml');
	//	//$OOo->MergeBlock('modules', $modules);
	//$OOo->MergeBlock('etu', $tableau);
	$OOo->SaveXmlToDoc();
	if (!copy($path, $destDir.$nom_etudiant."_s1_2006.ods")) {
		echo "La copie du fichier $path n'a pas r�ussi...\n";
	}
}
mysql_free_result($resreq);
?>
