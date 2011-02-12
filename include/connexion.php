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

include_once("conf.php");
	
$host = $_SERVER['HTTP_HOST'];
$host = explode($conf["WebHost"],$host);

if (count($host)>1){
	define(NOM, $conf["WebHostSqlLog"]);
	define(PASSE, $conf["WebHostSqlPassw"]);
	define(BASE, $conf["WebHostDatabase"]);	
} else {
	define(NOM, $conf["LocalSqlLog"]);
	define(PASSE, $conf["LocalSqlPassw"]);
	define(BASE, $conf["LocalDatabase"]);
}
define(SERVEUR, "localhost");

$connexion = mysql_pconnect (SERVEUR, NOM, PASSE);

if (!$connexion)
{
	echo "Connexion &agrave; ".SERVEUR." impossible !";
	exit;
}

if (!mysql_select_db(BASE, $connexion))
{
	echo "Connexion &agrave; ".BASE." impossible !";
	exit;
}
?>