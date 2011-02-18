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

LOGTYPE_PNOM = 0;
LOGTYPE_PRENOMNOM = 1;
LOGTYPE_PERSONNALISE = 2;

function init()
{
	AJX.newRequest("init");
	AJX.setAction("init", "init", "message:Test d'initialisation");
	AJX.send("init");
	addListener(gEBI("categories"),"change",changeCategorie);
	addListener(gEBI("utilisateurs"),"change",changeUtilisateur);
	addListener(gEBI("nom"),"change",changeNom);
	addListener(gEBI("prenom"),"change",changePrenom);
	//addListener(gEBI("log"),"change",changeLog);
	addListener(gEBI("log"),"blur",faitLog);
	addListener(gEBI("logtype"),"change",changeLogType);
	addListener(gEBI("logtype"),"blur",changeLogType);
	addListener(gEBI("niveau"),"change",changeNiveau);
	addListener(gEBI("filtre"),"change",nouvelleEntree);
	AJX.newRequest("chargeListeUtilisateurs");
	AJX.newRequest("chargeInfosUtilisateurs");
	AJX.newRequest("chargeCyclesSelonSemestre");
	AJX.newRequest("valider");
	AJX.newRequest("validationLog");
	changeCategorie();
}

addListener(window,"load",init);

function nouvelleEntree()
{
	//AJX.debug("chargeListeUtilisateurs");
	AJX.setAction("chargeListeUtilisateurs", "chargeListeUtilisateurs", "base:"+gVBI("categories")+",periode:"+gVBI("semestre_courant")+",filtre:"+gVBI("filtre"));
	AJX.send("chargeListeUtilisateurs");
	gEBI("nom").clearValue();
	gEBI("prenom").clearValue();
	gEBI("log").clearValue();
	gEBI("passw").clearValue();
	gEBI("logtype").value = LOGTYPE_PNOM;
	gEBI("niveau").value = 0;
	gEBI("cycle").clearOptions();
	gEBI("nom").focus();
	gEBI("auto").value ="p";
	changeNiveau();
}

function changeCategorie()
{
	nouvelleEntree();
	if(gVBI("categories")=="etudiants")
	{
		gEBI("champsEtudiants").className = "displayblock";
		gEBI("champsProfesseurs").className = "displaynone";
	}
	if(gVBI("categories")=="professeurs")
	{
		gEBI("champsEtudiants").className = "displaynone";
		gEBI("champsProfesseurs").className = "displayblock";
	}
}

function changeUtilisateur()
{
	AJX.setAction("chargeInfosUtilisateurs", "chargeInfosUtilisateurs", "base:"+gVBI("categories")+",utilisateur:"+gVBI("utilisateurs")+",periode:"+gVBI("semestre_courant"));
	AJX.send("chargeInfosUtilisateurs");
}

function chargeUtilisateur(id)
{
	AJX.setAction("chargeInfosUtilisateurs", "chargeInfosUtilisateurs", "base:"+gVBI("categories")+",periode:"+gVBI("semestre_courant")+",utilisateur:"+id);
	AJX.send("chargeInfosUtilisateurs");
}

function changeNom()
{
	gEBI("nom").value = gVBI("nom").toUpperCase();
	changeLog();
}

function changePrenom()
{
	prenom = gVBI("prenom").toLowerCase();
	prenom = prenom.substr(0,1).toUpperCase()+prenom.substr(1);
	gEBI("prenom").value = prenom;
	changeLog();
}

function changeLog()
{
	if(gVBI("logtype")!=LOGTYPE_PERSONNALISE) faitLog();
}

function changeLogType()
{
	faitLog();
}

function faitLog()
{
	var nom = gVBI("nom");
	var prenom = gVBI("prenom");
	var log="";
	switch(parseInt(gVBI("logtype"),10))
	{
	case LOGTYPE_PNOM:
		log = (prenom.substr(0,1)+nom).toLowerCase();
		break;
	case LOGTYPE_PRENOMNOM:
		log = (prenom+nom).toLowerCase();
		break;
	case LOGTYPE_PERSONNALISE:
		log = gVBI("log");
		if(log.length<3)
		{
			alert("Veuillez saisir un log personnalisé d'au moins 3 caractères");
			gEBI("log").focus();
			return;
		}
		break;
	}
	gEBI("log").value=no_accent(log);
}

function validerLog()
{
	var id = gVBI("utilisateurs");
	var base = gVBI("categories");
	var log = gVBI("log");
	AJX.setAction("validationLog", "validationLog", "log:"+log+",base:"+base+",utilisateur:"+id);
	//AJX.debug("validationLog");
	AJX.send("validationLog");
}

function validationLog(log,valide)
{
	gEBI("log").value = log;
	if(valide)
	{
		soumettre();
	}else{
		//gEBI("log").value = log;
		alert("Le log "+log+" existe déjà, veuillez changer le type ou personnalisez le log.");
		//gEBI("log").value="";
		if(gVBI("logtype")==LOGTYPE_PERSONNALISE) gEBI("log").focus();
		else gEBI("logtype").focus();
	}
}

function changeNiveau()
{
	AJX.setAction("chargeCyclesSelonSemestre", "chargeCyclesSelonSemestre", "niveau:"+gVBI("niveau"));
	AJX.send("chargeCyclesSelonSemestre");
}

function chargeCycleSelonSemestre(cycle)
{
	AJX.setAction("chargeCyclesSelonSemestre", "chargeCyclesSelonSemestre", "niveau:"+gVBI("niveau")+",selectedcycle:"+cycle);
	AJX.send("chargeCyclesSelonSemestre");
}

function soumettre(){
	if(!checkForm())return;
	var base = gVBI("categories");
	var id = gVBI("utilisateurs");
	var nom = gVBI("nom");
	var prenom = gVBI("prenom");
	var log = gVBI("log");
	var logtype = gVBI("logtype");
	var passw = gVBI("passw");
	var niveau = gVBI("niveau");
	var cycle = gVBI("cycle");
	var auto = gVBI("auto");
	var ecole = gVBI("ecole");
	var credits = gVBI("credits");
	var params = "base:"+base;
	params += ",id:"+id;
	params += ",nom:"+nom;
	params += ",prenom:"+prenom;
	params += ",log:"+log;
	params += ",logtype:"+logtype;
	params += ",passw:"+passw;
	params += ",niveau:"+niveau;
	params += ",cycle:"+cycle;
	params += ",auto:"+auto;
	params += ",ecole:"+ecole;
	params += ",credits:"+credits;
	params += ",periode:"+gVBI("semestre_courant");
	AJX.setAction("valider", "valider", params);
	AJX.send("valider");
}

function checkForm()
{
	var nom = gVBI("nom");
	var prenom = gVBI("prenom");
	var passw = gVBI("passw");
	if(nom.length<2) {
		alert ("Le nom doit comporter au moins 2 lettres");
		gEBI("nom").focus();
		return false;
	}
	if(prenom.length<2) {
		alert ("Le prénom doit comporter au moins 2 lettres");
		gEBI("prenom").focus();
		return false;
	}
	if(passw.length<8 && gVBI("categories")=="etudiants") {
		alert ("Le mot de passe doit comporter au moins 8 caractères");
		gEBI("passw").focus();
		return false;
	}
	return true;
}

function preg_replace (array_pattern, array_pattern_replace, my_string) // Auteur : XUXU.fr
{
var new_string = String (my_string);
	for (i=0; i<array_pattern.length; i++) {
		var reg_exp= RegExp(array_pattern[i], "gi");
		var val_to_replace = array_pattern_replace[i];
		new_string = new_string.replace (reg_exp, val_to_replace);
	}
	return new_string;
}

function no_accent (my_string) // Auteur : XUXU.fr
{
	var new_string = "";
	var pattern_accent = new Array("é", "è", "ê", "ë", "ç", "à", "â", "ä", "î", "ï", "ù", "ô", "ó", "ö");
	var pattern_replace_accent = new Array("e", "e", "e", "e", "c", "a", "a", "a", "i", "i", "u", "o", "o", "o");
	if (my_string && my_string!= "") {
		new_string = preg_replace (pattern_accent, pattern_replace_accent, my_string);
	}
	return new_string;
}