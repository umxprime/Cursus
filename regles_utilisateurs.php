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
				//if($val==null) echo $k1.":".$v1."<br/>";
				if($droit=="*")
				{
					foreach($droits[$k1] as $k2=>$v2)
					{
						//if($val==null) echo "-".$k1.":".$k2."=".($droits[$k1][$k2])."<br/>";
						if($val!=null) chdroits($droits,$k1,$k2,$val);
					}
				}
				else
				{
					//if($val==null) echo $k1.":".$droit."=".($droits[$k1][$droit])."<br/>";
					if($val!=null) chdroits($droits,$k1,$droit,$val);
				}
				
			}
			return;
		}
		if($val==null)
		{
			//echo $groupe.":".$droit."=".($droits[$groupe][$droit]);
		}
		else
		{
			//echo "set ".$groupe.":".$droit." to ".$val."<br/>";
			$droits[$groupe][$droit]=$val;
		}
	}
	
	$droits = array();
	
	$droits["super"]["menu_utilisateurs"] = true;
	$droits["super"]["menu_coordination"] = true;
	$droits["super"]["menu_modules"] = true;
	$droits["super"]["menu_niveaux"] = true;
	$droits["super"]["edit_utilisateurs"] = true;
	$droits["super"]["edit_coordination"] = true;
	$droits["super"]["edit_coordination_s13"] = true;
	$droits["super"]["edit_niveaux"] = true;
	$droits["super"]["edit_tous_niveaux"] = true;
	$droits["super"]["edit_modules"] = true;
	$droits["super"]["edit_tous_evaluations"] = true;
	$droits["super"]["edit_evaluations"] = true;
	$droits["super"]["edit_modules_adv"] = true;
	$droits["super"]["edit_tous_modules"] = true;
	$droits["super"]["voir_tous_modules"] = true;
	$droits["super"]["voir_tous_sites"] = true;
	$droits["super"]["ajouter_module"] = true;
	$droits["super"]["voir_tutorats"] = true;
	$droits["super"]["admin_tutorats"] = true;
	
	$droits["admin"] = $droits["super"];
	$droits["admin"]["edit_tous_niveaux"] = false;
	$droits["admin"]["edit_tous_evaluations"] = false;
	$droits["admin"]["edit_coordination_s13"] = false;
	$droits["admin"]["voir_tous_sites"] = false;
	
	$droits["coord"] = $droits["admin"];
	$droits["coord"]["ajouter_module"] = false;
	$droits["coord"]["voir_utilisateurs"] = false;
	$droits["coord"]["menu_utilisateurs"] = false;
	
	$droits["coord_semestre"] = $droits["coord"];
	$droits["coord_semestre"]["edit_tous_modules"] = false;
	$droits["coord_semestre"]["edit_modules_adv"] = false;
	$droits["coord_semestre"]["menu_niveaux"] = false;
	$droits["coord_semestre"]["edit_niveaux"] = false;
	
	$droits["p"] = $droits["coord_semestre"];
	$droits["p"]["admin_tutorats"] = false;
	$droits["p"]["voir_tous_modules"] = false;
	$droits["p"]["menu_coordination"] = false;
	
	//chdroits($droits,"*","*");
	//chdroits($droits,"*","*",true);
	//chdroits($droits,"*","*");
	
?>