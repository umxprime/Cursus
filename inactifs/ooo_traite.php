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

//les classes de traitement des fichiers openOffice
include_once('tbs_class.php');
include_once('tbsooo_class.php');
//Data
$tableau=array();
foreach($_POST as $key=>$val){
	$$key=$val;
	echo $key." : ".$val."\n";
	
}
// instantiate a TBS OOo class
$OOo = new clsTinyButStrongOOo;

// setting the object
$OOo->SetZipBinary('c:\\zippers\\zip\\zip.exe');
$OOo->SetUnzipBinary('c:\\zippers\\unzip\\unzip.exe');
$OOo->SetProcessDir("C:\\wamp\\www\\crusus\\temp\\");
$OOo->SetDataCharset('ISO 8859-1');
$destDir = "C:\\wamp\\www\\crusus\\oodest\\";
$docSrc = "C:\\wamp\\www\\crusus\\oosrc\\bulletin_indiv_v4.ots";


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
	// display
//	header('Content-type: '.$OOo->GetMimetypeDoc());
//	header('Content-Length: '.filesize($OOo->GetPathnameDoc()));
//	$OOo->FlushDoc();
//	$OOo->RemoveDoc();

?>
