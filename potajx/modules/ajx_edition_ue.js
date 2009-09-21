/**
 * Copyright © 2009 Maxime CHAPELET (umxprime@umxprime.com)
 * 
 * This file is a part of Potajx and Cursus
 * 
 * Potajx and Cursus are free softwares: you can redistribute them and/or modify
 * them under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Potajx and Cursus are distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Potajx and Cursus.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

function init()
{
	ajx_select(	"liste_types",
				"init",
				"etudiants:Étudiants,professeurs:Professeurs",
				null, null, null, false);
	
	update_utilisateurs()
	
	ajx_get_id("liste_utilisateurs").value="new";
	
	ajx_select(	"liste_ecoles",
				null,
				"ajx",
				"edition_utilisateurs",
				"get_ecoles_selon_prof",
				"prof:"+ajx_get_value("liste_utilisateurs"),
				false, true);
	
	ajx_select(	"liste_cycles",
				null,
				"ajx",
				"edition_utilisateurs",
				"get_cycles_selon_etudiant",
				"etudiant:"+ajx_get_value("liste_utilisateurs")+",semestre:"+ajx_get_value("semestre_courant"),
				false, true);
				
	ajx_select(	"liste_semestres",
				"chg_semestre",
				"ajx",
				"edition_utilisateurs",
				"get_semestres_selon_etudiant",
				"etudiant:"+ajx_get_value("liste_utilisateurs")+",semestre:"+ajx_get_value("semestre_courant"),
				false, true);
				
	if (ajx_get_value("liste_types") == "professeurs") {
		ajx_cache("champs_etus");
		ajx_montre("champs_profs");
	}
	
	if (ajx_get_value("liste_types") == "etudiants") {
		ajx_cache("champs_profs");
		ajx_montre("champs_etus");
	}
	
	ajx_inputTexts(	"nom:nom,prenom:prenom,passw:passw",
					"edition_utilisateurs",
					"get_valeurs",
					"base:"+ajx_get_value("liste_types")+",id:"+ajx_get_value("liste_utilisateurs"));
}

function update_utilisateurs()
{
	ajx_select(	"liste_utilisateurs",
				"chg_utilisateur",
				"ajx",
				"edition_utilisateurs",
				"get_utilisateurs",
				"base:"+ajx_get_value("liste_types"),
				true);
}

function chg_utilisateur()
{
	update_utilisateurs()
	if (ajx_get_value("liste_types")=="professeurs")
	{
		ajx_cache("champs_etus");
		ajx_montre("champs_profs");
		ajx_select(	"liste_ecoles",
					null,
					"ajx",
					"edition_utilisateurs",
					"get_ecoles_selon_prof",
					"prof:"+ajx_get_value("liste_utilisateurs"),
					false, true);
	}
	if (ajx_get_value("liste_types")=="etudiants")
	{
		ajx_cache("champs_profs");
		ajx_montre("champs_etus");
		ajx_select(	"liste_cycles",
					null,
					"ajx",
					"edition_utilisateurs",
					"get_cycles_selon_etudiant",
					"etudiant:"+ajx_get_value("liste_utilisateurs")+",semestre:"+ajx_get_value("semestre_courant"),
					false, true);
		ajx_select(	"liste_semestres",
					"chg_semestre",
					"ajx",
					"edition_utilisateurs",
					"get_semestres_selon_etudiant",
					"etudiant:"+ajx_get_value("liste_utilisateurs")+",semestre:"+ajx_get_value("semestre_courant"),
					false, true);
	}
	ajx_inputTexts(	"nom:nom,prenom:prenom,passw:passw",
					"edition_utilisateurs",
					"get_valeurs",
					"base:"+ajx_get_value("liste_types")+",id:"+ajx_get_value("liste_utilisateurs"));
}

function chg_semestre()
{
		ajx_select(	"liste_cycles",
					null,
					"ajx",
					"edition_utilisateurs",
					"get_cycles_selon_etudiant",
					"etudiant:"+ajx_get_value("liste_utilisateurs")+",semestre:"+ajx_get_value("semestre_courant")+",niveau:"+ajx_get_value("liste_semestres"),
					false, true);
		ajx_select(	"liste_semestres",
					"chg_semestre",
					"ajx",
					"edition_utilisateurs",
					"get_semestres_selon_etudiant",
					"etudiant:"+ajx_get_value("liste_utilisateurs")+",semestre:"+ajx_get_value("semestre_courant"),
					false);
}

function submit()
{
	var base = ajx_get_value("liste_types");
	var user = ajx_get_value("liste_utilisateurs");
	if (base == "etudiants") {
		var form = "nom:"+ajx_get_value("nom");
		form += ",prenom:"+ajx_get_value("prenom");
		form += ",passw:"+ajx_get_value("passw");
		form += ",cycle:"+ajx_get_value("liste_cycles");
		form += ",niveau:"+ajx_get_value("liste_semestres");
		form += ",periode:"+ajx_get_value("semestre_courant")
		var newuser = ajx_submit(	"edition_utilisateurs",
									"set_etudiants",
									"base:"+base+",id:"+user+","+form);
	}
	if (base == "professeurs") {
		var form = "nom:"+ajx_get_value("nom");
		form += ",prenom:"+ajx_get_value("prenom");
		form += ",passw:"+ajx_get_value("passw");
		form += ",ecole:"+ajx_get_value("liste_ecoles");
		var newuser = ajx_submit(	"edition_utilisateurs",
									"set_professeurs",
									"base:"+base+",id:"+user+","+form);
	}
	update_utilisateurs()
	users = ajx_get_id("liste_utilisateurs");
	for (var i = 0 ; i < users.length; i++)
	{
		if (users.options[i].value==String(newuser))
		{
			users.options[i].selected = true;
			break;
		}
	}
	chg_utilisateur();
}
