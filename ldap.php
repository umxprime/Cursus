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

// El�ments d'identification LDAP
//$ldaprdn = 'dc=esacambrai, dc=fr'; // DN ou RDN LDAP
//$ldappass = ''; // Mot de passe associ�
////Connexion au serveur LDAP
//$ldapconn = ldap_connect("192.168.0.10")
//or die("Impossible de se connecter au serveur LDAP.");
//if ($ldapconn) {
////Connexion au serveur LDAP
//$ldapbind = ldap_bind($ldapconn);
//// Identification
//if ($ldapbind) {
//echo "Connexion LDAP r�ussie";
//} else {
//echo "Connexion LDAP �chou�e";
//}
//}
?> <?php
// La s�quence de base avec LDAP est
// connexion, liaison, recherche, interpr�tation du r�sultat
// d�connexion
echo '<h3>requ�te de test de LDAP</h3>';
echo 'Connexion ...';
$ds=ldap_connect("192.168.0.10"); // doit �tre un serveur LDAP valide !
echo 'Le r�sultat de connexion est ' . $ds . '<br />';
if ($ds) {
	echo 'Liaison ...';
	$r=ldap_bind($ds); // connexion anonyme, typique
	// pour un acc�s en lecture seule.
	echo 'Le r�sultat de connexion est ' . $r . '<br />';
	echo 'Recherchons (sn=a*) ...';
	// Recherche par nom
	$sr=ldap_search($ds,"dc=esacambrai, dc=FR", "sn=a*");
	echo 'Le r�sultat de la recherche est ' . $sr . '<br />';
	echo 'Le nombre d\'entr�es retourn�es est ' . ldap_count_entries($ds,$sr)
	. '<br />';
	echo 'Lecture des entr�es ...<br />';
	$info = ldap_get_entries($ds, $sr);
	echo 'Donn�es pour ' . $info["count"] . ' entr�es:<br />';
	for ($i=0; $i<$info["count"]; $i++) {
		echo 'dn est : ' . $info[$i]["dn"] . '<br />';
		echo 'premiere entree cn : ' . $info[$i]["cn"][0] . '<br />';
		echo 'premier email : ' . $info[$i]["mail"][0] . '<br />';
	}
	echo 'Fermeture de la connexion';
	ldap_close($ds);}
	else {
		echo '<h4>Impossible de se connecter au serveur LDAP.</h4>';
	}
	?>
