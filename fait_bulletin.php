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

//on requiert les variables de connexion;
require ("connect_info.php");
//puis la connexion standard;
require ("connexion.php");
include ("inc_sem_courant.php");
include ("fonctions_eval.php");
//les classes de traitement des fichiers openOffice
include_once ('tbs_class.php');
include_once ('tbsooo_class.php');
function moncode($chaine){
	if ($semestre_courant >24){
		return ($chaine);
	}
	else
	{
		return $chaine;
	}
}
if ( isset ($_GET['id_etudiant']))
{
    $id_etudiant = $_GET['id_etudiant'];

    $requete = "select etudiants.*,niveaux.niveau from etudiants, niveaux where etudiants.id ='".$id_etudiant."'";
    $requete .= " AND niveaux.etudiant = '".$id_etudiant."' AND niveaux.periode='".$semestre_courant."';";

    $resreq = mysql_query($requete);
    //echo "erreur : ".mysql_error();
    while ($etudiant = mysql_fetch_array($resreq))
    {
        $id_etudiant = $etudiant["id"];
        $nom_etudiant = ($etudiant["nom"]);
        $prenom_etudiant = ($etudiant["prenom"]);
        $semestre = $etudiant["niveau"];

        $total1_credits = 0;
        $total2_credits = 0;
        $nligne = 1;
        //		echo $semestre_courant."     ---     ".$semestre;
        if ($etudiant["niveau"] > 4)
        {
            $req = "select tutorats.niveau, evaluations.note_1,evaluations.note_2,evaluations.appreciation_1,";
            $req .= " evaluations.appreciation_2,  professeurs.nom_complet as enseignant from tutorats, evaluations, professeurs ";
            $req .= "WHERE tutorats.etudiant='".$id_etudiant."' and tutorats.semestre='".$semestre_courant."' ";
            $req .= "and tutorats.trash!=1 and evaluations.tutorat=tutorats.id and professeurs.id = tutorats.professeur";
            $req .= ";";
            $restut = mysql_query($req);
            //echo $req;
            //echo "erreur : ".mysql_error();
            if ($tut['niveau'] > 8)
            {
                $plus = 6;
            } else if ($tut['niveau'] > 6)
            {
                $plus = 4;
            } else
            {
                $plus = 3;
            }


            while ($tut = mysql_fetch_array($restut))
            {


                $tut["credits"] = $plus;
                $var1 = "intitule_module_".$nligne;
                $var2 = "code_module_".$nligne;
                $var3 = "cred1_module_".$nligne;
                $var4 = "note1_module_".$nligne;
                $var10 = "note_module_".$nligne;
                $var5 = "appr1_module_".$nligne;
                $var6 = "note2_module_".$nligne;
                $var7 = "appr2_module_".$nligne;
                $var8 = "cred2_module_".$nligne;
                $var9 = "profs_module_".$nligne;
                $var11 = "credits_module_".$nligne;
                $$var1 = "Tutorat ".($tut["enseignant"]);
                $$var2 = "TUTO_".$nligne;
                $$var4 = ( isset ($tut['note_1']))?$tut['note_1']:"";
                $$var10 = ( isset ($tut['note_1']))?$tut['note_1']:"";
                $$var11 = "";
                $$var5 = ( isset ($tut['appreciation_1']))?moncode($tut['appreciation_1']):
                    "";
                    $$var3 = "";
                    $$var8 = "";
                    $$var9 = ($tut['enseignant']);
                    $$var6 = ( isset ($tut['note_2']))?$tut['note_2']:"";
                    $$var7 = ($tut['appreciation_2']);
                    $vartut = "tuteur_".$nligne;
                    $$vartut = ($tut["enseignant"]);

                    $nligne++;
                }
                if (strlen($note1_module_1)>0?strpos("_abcdABCD", $note1_module_1) > 0:false and strlen($note1_module_2)>0?strpos("_abcdABCD", $note1_module_2) > 0:false)
                {
                    $total1_credits += $plus;
                    $cred1_module_2 = $plus;
                } else if (strlen($note1_module_1)>0?strpos("_abcdABCD", $note1_module_1) > 0:false or strlen($note2_module_1)>0?strpos("_abcdABCD", $note2_module_1) > 0:false)
                {

                    if (strlen($note1_module_2)>0?strpos("_abcdABCD", $note1_module_2) > 0:false or strlen($note2_module_2)>0?strpos("_abcdABCD", $note2_module_2) > 0:false)
                    {
                        $total2_credits += $plus;
                        $cred2_module_2 = $plus;
                    }
                } else
                {
                    $cred2_module_2 = 0;
                    $evalSup[] = $tut;
                }

            } else
            {
                $tuteur_1 = "";
                $tuteur_2 = "";
                $cred2_module_2 = "";
            }
            if (! isset ($tuteur_1))
            {
                $tuteur_1 = "";
            }
            if (! isset ($tuteur_2))
            {
                $tuteur_2 = "";
            }
            //if(!isset($cred2_module_2)){$cred2_module_2 = 0;}
            $requete = "select evaluations.*, session.module as module, modules.credits, modules.intitule, modules.code, modules.enseignants";
            $requete .= ", niveaux.niveau from evaluations, niveaux, session, modules where evaluations.etudiant = '".$id_etudiant."' ";
            $requete .= " and niveaux.niveau = '".$semestre."' and niveaux.etudiant = '".$id_etudiant."' ";
            $requete .= "and session.periode=niveaux.periode and evaluations.session=session.id and modules.id = session.module";
            $requete .= ";";
            $resEvals = mysql_query($requete);
            while ($eval = mysql_fetch_array($resEvals))
            {
                if ($eval['code'] != "PP_EVL_".$semestre)
                {
                    $var1 = "intitule_module_".$nligne;
                    $var2 = "code_module_".$nligne;
                    $var3 = "cred1_module_".$nligne;
                    $var4 = "note1_module_".$nligne;
                    $var10 = "note_module_".$nligne;
                    $var5 = "appr1_module_".$nligne;
                    $var6 = "note2_module_".$nligne;
                    $var7 = "appr2_module_".$nligne;
                    $var8 = "cred2_module_".$nligne;
                    $var9 = "profs_module_".$nligne;
                    $var11 = "credits_module_".$nligne;
                    $$var1 = $eval["intitule"];
                    $$var2 = $eval["code"];
                    $$var4 = verif($eval['note_1']);
                    $$var10 = verif($eval['note_1']);
                    $$var11 = "";
                    $$var5 = ( isset ($eval['appreciation_1']))?moncode($eval['appreciation_1']):
                        "";
                        $$var3 = "";
                        $$var8 = "";
                        $$var9 = ($eval['enseignants']);
                        $$var6 = verif($eval['note_2']);
                        $$var7 = moncode($eval['appreciation_2']);
                        if (strpos("_ABCDabcd", $$var4)>0)
                        {
                            $$var3 = $eval['credits'];
                            $$var11 = $eval['credits'];
                            $total1_credits += $$var3;
                        } else if (strpos("_efEF", $$var4))
                        {
                            $$var8 = (strpos("_ABCDabcd", $$var6) > 0)?$eval['credits']:0;
                            $total2_credits += $$var8;
                            $evalSup[] = $eval;
                        }
                        $nligne++;
                    } else
                    {
                        //evaluation semestrielle;
                        $note1_eval = verif($eval['note_1']);
                        $note2_eval = verif($eval['note_2']);
                        $appr1_eval = ( isset ($eval['appreciation_1']))?moncode($eval['appreciation_1']):
                            "";
                            $appr2_eval = ( isset ($eval['appreciation_2']))?moncode($eval['appreciation_2']):
                                "";
                                $cred1_eval = ( strlen($eval['note_1'])>0?strpos("_abcdABCD", $eval['note_1']):false )?$eval['credits']:0;
                                $cred2_eval = ( strlen($eval['note_2'])>0?strpos("_abcdABCD", $eval['note_2']):false )?$eval['credits']:0;
                                $total1_credits += $cred1_eval;
                                $total2_credits += $cred2_eval;
                            }
                        }
                        if (! isset ($note1_eval) and ! isset ($note2_eval))
                        {
                            $note1_eval = "";
                            $note2_eval = "";
                            $appr1_eval = "";
                            $appr2_eval = "";
                            $cred1_eval = 0;
                            $cred2_eval = 0;
                        }
                        //stages
                        $requete = "SELECT * FROM stages WHERE etudiant='".$id_etudiant."' AND periode='".$semestre_courant."';";
                        $resEvals = mysql_query($requete);
                        while ($eval = mysql_fetch_array($resEvals))
                        {

                            $var1 = "intitule_module_".$nligne;
                            $var2 = "code_module_".$nligne;
                            $var3 = "cred1_module_".$nligne;
                            $var4 = "note1_module_".$nligne;
                            $var10 = "note_module_".$nligne;
                            $var5 = "appr1_module_".$nligne;
                            $var6 = "note2_module_".$nligne;
                            $var7 = "appr2_module_".$nligne;
                            $var8 = "cred2_module_".$nligne;
                            $var9 = "profs_module_".$nligne;
                            $var11 = "credits_module_".$nligne;
                            $$var1 = ($eval["lieu"]);
                            $$var2 = "STAGE";
                            $$var4 = verif($eval['note']);
                            $$var10 = verif($eval['note']);
                            $$var11 = "";
                            $$var5 = ( isset ($eval['appreciation']))?($eval['appreciation']):
                                "";
                                $$var3 = "";
                                $$var8 = "";
                                $$var9 = "Florette Eymenier";
                                $$var6 = "";
                                $$var7 = "";
                                if ($eval['valide'])
                                {
                                    $$var3 = $eval['credits'];
                                    $$var11 = $eval['credits'];
                                    $total1_credits += $$var3;
                                }
                                $nligne++;
                            }




                            $total1_credits = min($total1_credits, 30);
                            $total2_credits = min($total1_credits+$total2_credits, 30);
                            //en + : note1_module_n;
                            //en + : cred1_module_n;
                            //en + : appr1_module_n;

                            //en + : note2_module_n;
                            //en + : cred2_module_n;
                            //en + : appr2_module_n;

                            //en + : total1_credits;
                            //en + : total2_credits;

                            $raz = 19;
                            while ($nligne < 25)
                            {
                                $var1 = "intitule_module_".$nligne;
                                $var2 = "code_module_".$nligne;
                                $var3 = "credits_module_".$nligne;
                                $var4 = "note_module_".$nligne;
                                $var5 = "appr1_module_".$nligne;
                                $var6 = "cred1_module_".$nligne;
                                $var7 = "note1_module_".$nligne;
                                $var9 = "cred2_module_".$nligne;
                                $var10 = "note2_module_".$nligne;
                                $var8 = "profs_module_".$nligne;
                                $$var1 = "";
                                $$var2 = "";
                                $$var3 = "";
                                $$var4 = "";
                                $$var5 = "";
                                $$var6 = "";
                                $$var7 = "";
                                $$var8 = "";
                                $$var9 = "";
                                $$var10 = "";
                                if (is_array($evalSup[$nligne-$raz]))
                                {
                                    $$var1 = $evalSup[$nligne-$raz]['intitule'];
                                    $$var2 = $evalSup[$nligne-$raz]['code'];
                                    $$var3 = $evalSup[$nligne-$raz]['credits'];
                                    $$var4 = verif($evalSup[$nligne-$raz]['note_2']);
                                    $$var5 = ($evalSup[$nligne-$raz]['appreciation_2']);
                                    $$var8 = ($evalSup[$nligne-$raz]['enseignants']);
                                }
                                $nligne++;
                            }

                            $OOo = new clsTinyButStrongOOo;

                            // setting the object
                            //$OOo->SetZipBinary('c:\\zippers\\zip\\zip.exe');
                            $OOo->SetZipBinary('zip');
                            $OOo->SetUnzipBinary('unzip');
                            //$OOo->SetProcessDir("C:\\wamp\\www\\crusus\\temp\\");
							$destDir = "oodest/";
                            $OOo->SetProcessDir($destDir);
                            $OOo->SetDataCharset('ISO 8859-1');
                            //$destDir = "C:\\wamp\\www\\crusus\\oodest\\";
                            //$docSrc = "C:\\wamp\\www\\crusus\\oosrc\\bulletin_indiv_v4.ots";
                            $docSrc = "oosrc/bulletin_indiv.ots";
							
							
                            // create a new openoffice document from the template with an unique id
                            $OOo->NewDocFromTpl($docSrc);
							//echo $OOo->_ooo_basename.".".$OOo->_ooo_file_ext;
							//echo $path;
                            // merge data with OOo file content.xml
                            $OOo->LoadXmlFromDoc('content.xml');
                            //	//$OOo->MergeBlock('modules', $modules);
                            //$OOo->MergeBlock('etu', $tableau);
                            $OOo->SaveXmlToDoc();
							$output = $nom_etudiant."_s".$semestre.".".$OOo->_ooo_file_ext;
							exec("chmod -R a+rw ".$OOo->_ooo_basename);
                            exec("mv -f ".$OOo->_ooo_basename.".".$OOo->_ooo_file_ext." ".$destDir.$output);
                        }
                        header("Location: ".$destDir.$output);
                        $OOo->FlushDoc();
                        $OOo->RemoveDoc();
                    }
?>
