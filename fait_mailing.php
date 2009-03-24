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
include("inc_sem_courant.php");
//les classes de traitement des fichiers openOffice
include_once('tbs_class.php');
include_once('tbsooo_class.php');

$req = "SELECT nom, prenom as pren, adresse as addr, cp as co, ville FROM etudiants where ville!='';";
$res = mysql_query($req);

// datas
$blk_query  = array();
$ib=0;
while($ligne=mysql_fetch_array($res)){
$blk_query[$ib]['nom']= utf8_decode($ligne['nom']);
$blk_query[$ib]['pren']=utf8_decode($ligne['pren']);
$blk_query[$ib]['addr']=utf8_decode($ligne['addr']);
$blk_query[$ib]['co']=utf8_decode($ligne['co']);
$blk_query[$ib]['ville']=utf8_decode($ligne['ville']);
$ib++;
}
$OOo = new clsTinyButStrongOOo;
// setting the object
		//$OOo->SetZipBinary('c:\\zippers\\zip\\zip.exe');
		$OOo->SetZipBinary('zip');
		//$OOo->SetUnzipBinary('c:\\zippers\\unzip\\unzip.exe');
		$OOo->SetUnzipBinary('unzip');
		//$OOo->SetProcessDir("C:\\wamp\\www\\crusus\\temp\\");
		$destDir = "oodest/";
		$OOo->SetProcessDir($destDir);
		$OOo->SetDataCharset('ISO 8859-1');
		$docSrc = "oosrc/mailing.stw";

		// create a new openoffice document from the template with an unique id
		$OOo->NewDocFromTpl($docSrc);
		// merge data with OOo file content.xml
		$OOo->LoadXmlFromDoc('content.xml');

		$OOo->MergeBlock('blk1', $blk_query) ;
		$OOo->SaveXmlToDoc();

// display
		$output = "mailing_etudiants.".$OOo->_ooo_file_ext;
		exec("chmod -R a+rw ".$OOo->_ooo_basename);
        exec("mv -f ".$OOo->_ooo_basename.".".$OOo->_ooo_file_ext." ".$destDir.$output);

		header("Location: ".$destDir.$output);
        $OOo->FlushDoc();
        $OOo->RemoveDoc();
//		$OOo = new clsTinyButStrongOOo;
//
//		
//		//	//$OOo->MergeBlock('modules', $modules);
//		//$OOo->MergeBlock('etu', $tableau);
//		$OOo->SaveXmlToDoc();
//		if (!copy($path, $destDir.$nom_etudiant."_s".$semestre.".ods")) {
//			echo "La copie du fichier $path n'a pas réussi...\n";
//		}else{
//			chmod($destDir.$nom_etudiant."_s".$semestre.".ods", 0666);
//		}
//	
//	mysql_free_result($resreq);
//	header("Location: oodest/".$nom_etudiant."_s".$semestre.".ods");

?>
