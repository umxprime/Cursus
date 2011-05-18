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
	AJX.newRequest("inscrire");
	AJX.newRequest("desinscrire");
	AJX.newRequest("tout_desinscrire");
}

addListener(window,"load",init);

function desinscrire(eval,nom,etudiant)
{
	var session = gVBI("session");
	var semestre_courant = gVBI("semestre_courant");
	AJX.setAction("desinscrire", "desinscrire", "nom:"+nom+",etudiant:"+etudiant+",eval:"+eval+",session:"+session+",semestre_courant:"+semestre_courant);
	if(window.confirm("Êtes-vous certain de vouloir désinscrire "+nom+" de ce module ?"))AJX.send("desinscrire");
}

function inscrire()
{
	var etudiant = gVBI("etudiant");
	var session = gVBI("session");
	var semestre_courant = gVBI("semestre_courant");
	AJX.setAction("inscrire", "inscrire", "etudiant:"+etudiant+",session:"+session+",semestre_courant:"+semestre_courant);
	AJX.send("inscrire");
}

function tout_desinscrire()
{
	var session = gVBI("session");
	var semestre_courant = gVBI("semestre_courant");
	AJX.setAction("tout_desinscrire", "tout_desinscrire", "session:"+session+",semestre_courant:"+semestre_courant);
	if(window.confirm("Êtes-vous certain de vouloir désinscrire tous les étudiants de ce module ?"))AJX.send("tout_desinscrire");
}

function presence(p)
{
	var neval = gVBI("neval");
	for(var i=1;i<=neval;i++)
	{
		var value = gEBI("presence_"+i+"-"+p).checked;
		if (value)
		{
			gEBI("presence_"+i+"-"+p).checked=false;
		}
		else
		{
			gEBI("presence_"+i+"-"+p).checked=true;
		}
	}
}

function publier()
{
	var neval = gVBI("neval");
	for(var i=1;i<=neval;i++)
	{
		var value = gEBI("publier_"+i).checked;
		if (value)
		{
			gEBI("publier_"+i).checked=false;
		}
		else
		{
			gEBI("publier_"+i).checked=true;
		}
	}
}

function appliquer()
{
	
}