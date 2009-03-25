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
	if(!isset($rootpath)) $rootpath="";
	$conffile = $rootpath."cursus.conf";
	$conf = fopen($conffile,"r");
	$data = fread($conf, filesize($conffile));
	fclose($conf);
	$data = explode("\n", $data);
	for($i=0;$i<sizeof($data);$i++)
	{
		if (count(explode("#",$data[$i]))>1 or count($line=explode("=",$data[$i]))<2) continue;
		switch ($line[0]){
			case "Host" :
				//echo "Host : ".$line[1]."<br/>";
				$confHost = $line[1];
				break;
			case "LocalSqlLog" :
				//echo "Login Local SQL : ".$line[1]."<br/>";
				$confLocalSqlLog = $line[1];
				break;
			case "LocalSqlPassw" :
				//echo "Pass Local SQL : ".$line[1]."<br/>";
				$confLocalSqlPassw = $line[1];
				break;
			case "HostSqlLog" :
				//echo "Login Host SQL : ".$line[1]."<br/>";
				$confHostSqlLog = $line[1];
				break;
			case "HostSqlPassw" :
				//echo "Pass Host SQL : ".$line[1]."<br/>";
				$confHostSqlPassw = $line[1];
				break;
		}
	}
	
	$host = $_SERVER['HTTP_HOST'];
	$host = explode($confHost,$host);
	
	if (count($host)>1){
		define(NOM, $confHostSqlLog);
		define(PASSE, $confHostSqlPassw);	
	} else {
		define(NOM, $confLocalSqlLog);
		define(PASSE, $confLocalSqlPassw);
	}
	define(SERVEUR, "localhost");
	define(BASE, "cursus_share");
?>