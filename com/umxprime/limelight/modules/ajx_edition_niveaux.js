	/**
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
	addListener(gEBI("ecoles"),"change",changeEcole);
	addListener(gEBI("cycles"),"change",changeCycle);
	addListener(gEBI("filtre"),"change",chargeListeEtudiants);
	AJX.newRequest("chargeCycles");
	AJX.newRequest("chargeListeEtudiants");
	AJX.newRequest("changeCycleEtudiant");
	AJX.newRequest("changeNiveauEtudiant");
	chargeCycles();
}

addListener(window,"load",init);

function changeEcole()
{
	chargeCycles();
}

function changeCycle()
{
	chargeListeEtudiants();
}

function chargeCycles()
{
	AJX.setAction("chargeCycles", "chargeCycles", "ecole:"+gVBI("ecoles"));
	AJX.send("chargeCycles");
}

function chargeListeEtudiants()
{
	AJX.setAction("chargeListeEtudiants", "chargeListeEtudiants", "periode:"+gVBI("semestre_courant")+",cycle:"+gVBI("cycles")+",ecole:"+gVBI("ecoles")+",filtre:"+gVBI("filtre"));
	AJX.send("chargeListeEtudiants");
}

function faitListeEtudiants(etudiants,listeCycles)
{
	var elt=gEBI("liste");
	var lignes="<table class=\"center\">\n<tr>\n<td>\n</td>\n<td>\nNom\n</td>\n<td>\nCycle\n</td>\n<td>\nNiveau</td>\n</tr>\n";
	for(var i=0; i<etudiants.length; i++)
	{
		
		var ligne="<tr style='color:#FFFFFF;background-color:#80B711;'>\n";
		if(etudiants[i]["niveau"]==-1) ligne="<tr style='color:#FFFFFF;background-color:#FF7500;'>\n";
		if(etudiants[i]["niveau"]==13) ligne="<tr style='color:#60ACBF;background-color:#AAA;'>\n";
		if(etudiants[i]["niveau"]==33) ligne="<tr style='color:#60ACBF;background-color:#303030;'>\n";
		ligne += "<td>\n";
		ligne += i+1;
		ligne += "</td>\n";
		ligne += "<td>\n";
		ligne += "<a class=\"bouton2\" href=\"vue_cursus.php?id="+etudiants[i]["id"]+"&nPeriode="+gVBI("semestre_courant")+"\">";
		ligne += etudiants[i]["nom"];
		ligne += " ";
		ligne += etudiants[i]["prenom"];
		ligne += "</a>";
		ligne += "</td>\n";
		var n=0;
		var cycle = new HtmlFieldSelect();
		cycle.setFieldId("cycle_"+etudiants[i]["id"]);
		//cycle.appendFieldOption(-1,"-");
		for(n=0;n<listeCycles["text"].length;n++)cycle.appendFieldOption(listeCycles["value"][n], listeCycles["text"][n]);
		ligne += "<td>\n";
		ligne += cycle.renderField();
		ligne += "</td>\n";
		var niveau = new HtmlFieldSelect();
		niveau.setFieldId("niveau_"+etudiants[i]["id"]);
		var niveauxVals = new Array();
		var niveauxTxt = new Array();
		niveauxVals.push(-1);
		niveauxTxt.push("-");
		niveauxVals.push(33);
		niveauxTxt.push("Auditeur libre");
		for(n=parseInt(etudiants[i]["semestre_debut"],10);n<=parseInt(etudiants[i]["semestre_fin"],10);n++) {niveauxVals.push(String(n));niveauxTxt.push("Année "+Math.round(n/2)+" (semestre "+n+")");}
		niveauxVals.push(13);
		niveauxTxt.push("Parti(e) au semestre précédent");
		niveau.setFieldOptions(niveauxVals, niveauxTxt);
		ligne += "<td>\n";
		ligne += niveau.renderField();
		//ligne += etudiants[i]["niveau"];
		ligne += "</td>\n";
		ligne += "<td>\n";
		ligne += "<a class=\"bouton2\" href=\"javascript:;\" id=\"appliquer_"+etudiants[i]["id"]+"\">Appliquer</a>\n";
		ligne += "</td>\n";
		ligne += "</tr>";
		lignes += ligne;		
	}
	lignes += "</table>\n";
	elt.innerHTML = lignes;
	for(i=0; i<etudiants.length; i++)
	{
		gEBI("niveau_"+etudiants[i]["id"]).value = parseInt(etudiants[i]["niveau"],10);
		gEBI("cycle_"+etudiants[i]["id"]).value = parseInt(etudiants[i]["cycle"],10);
		addListener(gEBI("niveau_"+etudiants[i]["id"]),"change",changeNiveauEtudiant,true);
		addListener(gEBI("appliquer_"+etudiants[i]["id"]),"click",changeNiveauEtudiant,true);
		addListener(gEBI("cycle_"+etudiants[i]["id"]),"change",changeCycleEtudiant,true);
	}
}

function changeNiveauEtudiant(evt)
{
	var id = evt.target.id.split("_")[1];
	var niveau = gVBI("niveau_"+id);
	AJX.setAction("changeNiveauEtudiant", "changeNiveauEtudiant", "periode:"+gVBI("semestre_courant")+",ecole:"+gVBI("ecoles")+",cycle:"+gVBI("cycle_"+id)+",id:"+id+",niveau:"+niveau);
	AJX.send("changeNiveauEtudiant");
}

function changeCycleEtudiant(evt)
{
	var elt = evt.target;
	var cycle = elt.value;
	var id = elt.id.split("_")[1];
	AJX.setAction("changeCycleEtudiant", "changeCycleEtudiant", "id:"+id+",cycle:"+cycle);
	AJX.send("changeCycleEtudiant");
}