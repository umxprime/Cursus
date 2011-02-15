<?php
require('fpdf.php');

class PDF extends FPDF
{
function WordWrap(&$text, $maxwidth)
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
function HTML2RGB($c, &$r, &$g, &$b)
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

function SetDrawColor($r, $g=-1, $b=-1)
{
    if(is_string($r))
        $this->HTML2RGB($r,$r,$g,$b);
    parent::SetDrawColor($r,$g,$b);
}

function SetFillColor($r, $g=-1, $b=-1)
{
    if(is_string($r))
        $this->HTML2RGB($r,$r,$g,$b);
    parent::SetFillColor($r,$g,$b);
}

function SetTextColor($r,$g=-1,$b=-1)
{
    if(is_string($r))
        $this->HTML2RGB($r,$r,$g,$b);
    parent::SetTextColor($r,$g,$b);
}
function Footer()
{
    $this->SetXY($this->w-10,$this->h-10);
    $this->SetFont('Georgia','',12);
    $this->SetTextColor('#303030');
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}
?>