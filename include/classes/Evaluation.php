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
 * Cursus uses the Limelight Framework
 * released under the GPL <http://www.gnu.org/licenses/>
 * by Maxime CHAPELET (umxprime@umxprime.com)
 * 
 **/

class Evaluation
{	
	private $note1;
	private $note2;
	private $appreciation1;
	private $appreciation2;
	private $credits;
	private $notesSaisies;
	private $appreciationsSaisies;
	private $estUnRattrapage;
	private $toutSaisi;
	
	public function __construct($note1,$note2,$appreciation1,$appreciation2,$credits)
	{
		$this->note1 = $note1;
		$this->note2 = $note2;
		$this->appreciation1 = $appreciation1;
		$this->appreciation2 = $appreciation2;
		$this->credits = $credits;
		$this->toutSaisi = false;
		$this->verifierStatut();
	}
	
	public function conformerNote($note)
	{
		return (strlen($note)>0) ? $note : "-";
	}
	
	public function estUneNoteSaisie($note)
	{
		return $this->conformerNote($note)!="-";
	}
	
	public function estUneAppreciationSaisie($appreciation)
	{
		return strlen($appreciation)>0;
	}
	
	public function estUneNoteSuffisante($note)
	{
		return strpos("_ABCDabcd",$this->conformerNote($note))>0;
	}
	
	public function estUneNoteInsuffisante($note)
	{
		return strpos("_EFef",$this->conformerNote($note))>0;
	}
	
	public function aUneNoteSuffisante()
	{
		return $this->estUneNoteSuffisante($this->note1) || $this->estUneNoteSuffisante($this->note2);
	}
	
	public function estCrediteeDe()
	{
		if(!$this->notesSaisies)return 0;
		if($this->estUneNoteSuffisante($this->note1)) return $this->credits;
		else if ($this->estUneNoteSuffisante($this->note2)) return $this->credits;
		else return 0;
	}
	
	public function verifierStatut()
	{
		$estUnRattrapage = false;
		$notesSaisies = false;
		$appreciationsSaisies = false;
		if ($this->estUneAppreciationSaisie($this->appreciation1)) $appreciationsSaisies=true;
		if ($this->estUneNoteSaisie($this->note1)) $notesSaisies=true;
		if ($notesSaisies && $appreciationsSaisies && !$this->estUneNoteSuffisante($this->note1))
		{
			$estUnRattrapage = true;
			$notesSaisies = false;
			$appreciationsSaisies = false;
			if ($this->estUneAppreciationSaisie($this->appreciation2)) $appreciationsSaisies=true;
			if ($this->estUneNoteSaisie($this->note2)) $notesSaisies=true;
		}
		$this->notesSaisies=$notesSaisies;
		$this->appreciationsSaisies=$appreciationsSaisies;
		$this->estUnRattrapage=$estUnRattrapage;
		if($this->notesSaisies && $this->appreciationsSaisies) $this->toutSaisi = true;
	}
	
	function couleursPDF()
	{
		if (!$this->estUneNoteSaisie($this->note1))
		{
			$couleurTitreFond = "#D8D8D8";
			$couleurTitreTexte = "#404040";
			$couleurECTSTexte = "#404040";
			$couleurECTSFond = "white";
		}else if($this->aUneNoteSuffisante())
		{
			$couleurTitreTexte = "white";
			$couleurTitreFond = "#80B711";
			$couleurECTSTexte = "#80B711";
			$couleurECTSFond = "white";
		}else{
			$couleurTitreTexte = "white";
			$couleurTitreFond = "#FF8500";
			$couleurECTSTexte = "#FF8500";
			$couleurECTSFond = "white";
		}
		if($this->estUneNoteSuffisante($this->note1))
		{
			$couleurNote1Texte = "white";
			$couleurNote1Fond = "#80B711";
		} else if (!$this->estUneNoteSaisie($this->note1)){
			$couleurNote1Texte = "white";
			$couleurNote1Fond = "#D8D8D8";
		}else{
			$couleurNote1Texte = "white";
			$couleurNote1Fond = "#FF8500";
		}
		if($this->estUneNoteSuffisante($this->note2))
		{
			$couleurNote2Texte = "white";
			$couleurNote2Fond = "#80B711";
		} else if (!$this->estUneNoteSaisie($this->note2)){
			$couleurNote2Texte = "white";
			$couleurNote2Fond = "#D8D8D8";
		} else {
			$couleurNote2Texte = "white";
			$couleurNote2Fond = "#FF8500";
		}
		return array(
		"couleurTitreFond"=>$couleurTitreFond,"couleurTitreTexte"=>$couleurTitreTexte,
		"couleurECTSFond"=>$couleurECTSFond,"couleurECTSTexte"=>$couleurECTSTexte,
		"couleurNote1Fond"=>$couleurNote1Fond,"couleurNote1Texte"=>$couleurNote1Texte,
		"couleurNote2Fond"=>$couleurNote2Fond,"couleurNote2Texte"=>$couleurNote2Texte
		);
	}
}
?>