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
<div id="semestre">
	<table class="center"><tr><td>
	<ul>
		<?php
			$plus = (!isset($plus_nav_semestre))?array():$plus_nav_semestre;
			$req = "SELECT * FROM periodes WHERE activite='14' ";
			$req .="AND debut >='".$periode["fin"]."' ";
			$req .="ORDER BY debut LIMIT 1;";
			$res = mysql_query($req);
			$nextSem = mysql_fetch_array($res);
			$nexts = $nextSem["id"];
			$req = "SELECT * FROM periodes WHERE activite='14' ";
			$req .="AND fin <='".$periode["debut"]."' ";
			$req .="ORDER BY fin DESC LIMIT 1;";
			//echo $req;
			$res = mysql_query($req);
			$precSem = mysql_fetch_array($res);
			$precs = $precSem["id"];
			$retour = $_SERVER["PHP_SELF"];
			if(isset($_GET['ns']))
			{
				$retour .= "?ns=".$ns;
				$liaison="&";
			}else{
				$liaison="?";
			}
			$lien_suite= $retour.$liaison."nPeriode=".$precs;
			$lien_precedent = $retour.$liaison."nPeriode=".$nexts;
			foreach($plus as $ajout)
			{
				$lien_suite.="&".$ajout['var']."=".$ajout['val'];
				$lien_precedent.="&".$ajout['var']."=".$ajout['val'];
			}
			if(is_array($precSem))
			{
				if(!$disableNavSemPrec)echo "<li id=\"sem-precedent\"><a href=\"".$lien_suite."\">&lt;---   </a></li>";
			}
			echo "<li><h3>".$periode['annee']."</h3><h2>".$periode['nom']."</h2></li>";
			if(is_array($nextSem))
			{
				if(!$disableNavSemSuiv)echo "<li id=\"sem-suivant\"><a href=\"".$lien_precedent."\">---&gt;</a></li>";
			}
		?>
	</ul>
	</td></tr></table>
</div>