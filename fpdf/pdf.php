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
 * Cursus uses FPDF released by Olivier PLATHEY
 *
 * Cursus uses the Limelight Framework
 * released under the GPL <http://www.gnu.org/licenses/>
 * by Maxime CHAPELET (umxprime@umxprime.com)
 * 
 **/

require('fpdf.php');

class PDF extends FPDF
{
function WordWrap(&$text, $maxwidth) // Auteur : Ron Korving @ FPDF.org
{
    $text = trim($text);
    if ($text==='')
        return 0;
    $space = $this->GetStringWidth(' ');
    $lines = explode("\n", $text);
    $text = '';
    $count = 0;

    foreach ($lines as $line)
    {
        $words = preg_split('/ +/', $line);
        $width = 0;

        foreach ($words as $word)
        {
            $wordwidth = $this->GetStringWidth($word);
            if ($wordwidth > $maxwidth)
            {
                // Word is too long, we cut it
                for($i=0; $i<strlen($word); $i++)
                {
                    $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                    if($width + $wordwidth <= $maxwidth)
                    {
                        $width += $wordwidth;
                        $text .= substr($word, $i, 1);
                    }
                    else
                    {
                        $width = $wordwidth;
                        $text = rtrim($text)."\n".substr($word, $i, 1);
                        $count++;
                    }
                }
            }
            elseif($width + $wordwidth <= $maxwidth)
            {
                $width += $wordwidth + $space;
                $text .= $word.' ';
            }
            else
            {
                $width = $wordwidth + $space;
                $text = rtrim($text)."\n".$word.' ';
                $count++;
            }
        }
        $text = rtrim($text)."\n";
        $count++;
    }
    $text = rtrim($text);
    return $count;
}
function HTML2RGB($c, &$r, &$g, &$b) // Auteur : MorphSoft @ FPDF.org ing_ldf@yahoo.com.mx
{
    static $colors = array('black'=>'#000000','silver'=>'#C0C0C0','gray'=>'#808080','white'=>'#FFFFFF',
                        'maroon'=>'#800000','red'=>'#FF0000','purple'=>'#800080','fuchsia'=>'#FF00FF',
                        'green'=>'#008000','lime'=>'#00FF00','olive'=>'#808000','yellow'=>'#FFFF00',
                        'navy'=>'#000080','blue'=>'#0000FF','teal'=>'#008080','aqua'=>'#00FFFF');

    $c=strtolower($c);
    if(isset($colors[$c]))
        $c=$colors[$c];
    if($c[0]!='#')
        $this->Error('Incorrect color: '.$c);
    $r=hexdec(substr($c,1,2));
    $g=hexdec(substr($c,3,2));
    $b=hexdec(substr($c,5,2));
}

function SetDrawColor($r, $g=-1, $b=-1) // Auteur : MorphSoft @ FPDF.org ing_ldf@yahoo.com.mx
{
    if(is_string($r))
        $this->HTML2RGB($r,$r,$g,$b);
    parent::SetDrawColor($r,$g,$b);
}

function SetFillColor($r, $g=-1, $b=-1) // Auteur : MorphSoft @ FPDF.org ing_ldf@yahoo.com.mx
{
    if(is_string($r))
        $this->HTML2RGB($r,$r,$g,$b);
    parent::SetFillColor($r,$g,$b);
}

function SetTextColor($r,$g=-1,$b=-1) // Auteur : MorphSoft @ FPDF.org ing_ldf@yahoo.com.mx
{
    if(is_string($r))
        $this->HTML2RGB($r,$r,$g,$b);
    parent::SetTextColor($r,$g,$b);
}
function Footer()
{
	$texte = $this->PageNo()."/{nb}";
    $this->SetFont('Georgia','',12);
    $this->SetTextColor('#303030');
    $this->SetXY($this->w-$this->GetStringWidth($texte),$this->h-10);
    $this->Cell($this->GetStringWidth($texte),10,$texte,0,0,'C');
}
}
?>