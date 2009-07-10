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
	
	// fonction récursive pour assigner les droits de groupes d'utilisateurs
	
	function chdroits(&$droits,$groupe,$droit,$val=null)
	{
		if ($groupe=="*")
		{
			foreach($droits as $k1=>$v1)
			{
				if($val==null) echo $k1.":".$v1."<br/>";
				if($droit=="*")
				{
					foreach($droits[$k1] as $k2=>$v2)
					{
						if($val==null) echo "-".$k1.":".$k2."=".($droits[$k1][$k2])."<br/>";
						if($val!=null) chdroits($droits,$k1,$k2,$val);
					}
				}
				else
				{
					if($val==null) echo $k1.":".$droit."=".($droits[$k1][$droit])."<br/>";
					if($val!=null) chdroits($droits,$k1,$droit,$val);
				}
				
			}
			return;
		}
		if($val==null)
		{
			echo $groupe.":".$droit."=".($droits[$groupe][$droit]);
		}
		else
		{
			echo "set ".$groupe.":".$droit." to ".$val."<br/>";
			$droits[$groupe][$droit]=$val;
		}
	}
	
	$droits = array();
	$droits["super"]["view_all_modules"] = true;
	$droits["super"]["edit_all_modules"] = true;
	$droits["super"]["edit_users"] = true;
	$droits["coorda"]["edit_users"]=false;
	$droits["coordc"]["edit_all_modules"]=false;
	chdroits($droits,"*","*",true);
	chdroits($droits,"*","*");
?>