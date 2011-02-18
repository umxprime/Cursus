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
 * Cursus uses FPDF released by Olivier PLATHEY
 *
 * Cursus uses the Limelight Framework
 * released under the GPL <http://www.gnu.org/licenses/>
 * by Maxime CHAPELET (umxprime@umxprime.com)
 * 
 **/

function init()
{
	AJX.newRequest("init");
	AJX.setAction("init", "init", "message:Test d'initialisation");
	AJX.send("init");
	addListener(gEBI("tuteur"),"change",changeTuteur);
	gEBI("tuteur").value = gVBI("tuteur_id");
}

addListener(window,"load",init);

function changeTuteur()
{
	var nPeriode = gVBI("nPeriode");
	var tuteur = gVBI("tuteur");
	window.location = "?nPeriode="+nPeriode+"&tuteur="+tuteur;
}

function desinscrire(id,nom) {
	document.getElementById('action').value="desinscrire";
	document.getElementById('tutorat').value=""+id;
	if(window.confirm("Êtes-vous certain de vouloir désinscrire "+nom+" des tutorats ?"))document.getElementById('formulaire2').submit();
}