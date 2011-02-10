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
	require ("connect_info.php");
	//puis la connexion standard;
	require ("connexion.php");
	include ("inc_sem_courant.php");
	include ("fonctions_eval.php");
	//les classes de traitement des fichiers openOffice
	include_once ('tbs_class.php');
	include_once ('tbsooo_class.php');
	if (!isset($_GET["periode"])) die('?periode=');
	
	$req = "SELECT modules.* FROM modules,session WHERE session.periode='".$_GET["periode"]."' AND modules.id=session.module ORDER BY modules.code ASC;";
	
	$res = mysql_query($req);
	$modules = array();
	while($module = mysql_fetch_array($res))
	{
		array_push($modules,$module);
	}
	
	$OOo = new clsTinyButStrongOOo;

    $OOo->SetZipBinary('zip');
    $OOo->SetUnzipBinary('unzip');

	$destDir = "oodest/";
    $OOo->SetProcessDir($destDir);
    $OOo->SetDataCharset('ISO 8859-1');
    $docSrc = "oosrc/liste_modules.odt";
	
    $OOo->NewDocFromTpl($docSrc);
	
    $OOo->LoadXmlFromDoc('content.xml');
	$OOo->MergeBlock('module',$modules);
    $OOo->SaveXmlToDoc();
	$output = "liste_modules.".$OOo->_ooo_file_ext;
	exec("chmod -R a+rw ".$OOo->_ooo_basename);
    exec("mv -f ".$OOo->_ooo_basename.".".$OOo->_ooo_file_ext." ".$destDir.$output);

	header("Location: ".$destDir.$output);
	$OOo->FlushDoc();
	$OOo->RemoveDoc();
?>