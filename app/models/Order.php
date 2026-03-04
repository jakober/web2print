<?php

class Order extends Eloquent {

    protected $table = 'orders';
    public static $unguarded = true;

    public function vorlage() {
        return $this->belongsTo('Vorlage');
    }

    public function status() {
        return $this->belongsTo('Status');
    }

    public function protokoll() {
        return $this->hasMany('Protokoll');
    }

    public function anschrift() {
        return $this->belongsTo('Lieferanschrift');
    }

    public function extras() {
        return $this->hasMany('OrderExtra');
    }

    public function mandant() {
        return $this->belongsTo('Mandant');
    }

    public function createPDF($print = false, $margin = 0, $beschnitt = 0) {
        $vorlage = $this->vorlage()->first();
        $druckbogen = $vorlage->druckbogen()->first();
        //echo $vorlage->width + 2 * $margin, ' ', $vorlage->height + 2 * $margin; die();
        if ($print) {
            $margin = $druckbogen->dx / 2;
        } else {
            $margin = 0;
        }

        $margin += $beschnitt;

        $pdf = new FPDI('', 'mm', array($vorlage->width + 2 * $margin, $vorlage->height + 2 * $margin));

        if ($print && (!$druckbogen->vordruck_1 || !$druckbogen->vordruck_2) || !$print) {
            $template = $vorlage->pdfTemplate();
            $pdf->setSourceFile($template);
        }

        // Reset
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetAutoPageBreak(false);
        $pdf->setCellPaddings(0, 0, 0, 0);

        $json = json_decode($this->output, true);

        $addedFonts = array();
        if($vorlage->mandant_id==9){
            $gesamtseiten = $druckbogen->seiten;
        }else{
            $gesamtseiten = $vorlage->seiten;
        }

        for ($i = 0; $i < $gesamtseiten; $i++) {

            $pdf->addPage();
            if ($i == 0 && !$druckbogen->vordruck_1 || $i == 1 && !$druckbogen->vordruck_2 || !$print) {
                $tpl = $pdf->importPage($i + 1);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->useTemplate($tpl, ($vorlage->width - $size['w']) / 2 + $margin, ($vorlage->height - $size['h']) / 2 + $margin);
            }
            $texts = $json[$i]['texts'];

            for ($j = 0; $j < count($texts); $j++) {
                $o = $texts[$j];

                $family = $o['fontFamily'];

                $style = isset($o['fontStyle']) ? $o['style'] : '';
                $weight = isset($o['fontWeight']) ? $o['fontWeight'] : '';
                $alignment = 'L';
                if (isset($o['textAnchor'])) {
                    if ($o['textAnchor'] === 'middle') {
                        $alignment = 'C';
                    } elseif ($o['textAnchor'] === 'end') {
                        $alignment = 'R';
                    }
                }
                if ($style == 'normal') {
                    $style = '';
                }
                if ($weight == 'normal') {
                    $weight = '';
                }
                $code = ($weight == 'bold' ? 'B' : '') . ($style == 'italic' ? 'I' : '');

                $font = Font::where('family', '=', $family)->where('style', '=', $style)->where('weight', '=', $weight)->first();
                $pdf->addFont("helveticaneueltw1glt", '', 7, '', false);
                $pdf->addFont("genosgfg", '', 7, '', false);
                $pdf->addFont("helveticaneueltw1gmd", '', 7, '', false);
                $pdf->addFont("helveticanowvar", '', 7, '', false);
                $pdf->addFont("robotolight", '', 7, '', false);
                $pdf->addFont("robotob", '', 7, '', false);

                $pdf->addFont('acuminpromedium', '', 7, '', false);
                $pdf->addFont('acuminprolight', '', 7, '', false);


/*                $pdf->addFont("robotob", '', 7, '', false);
                 $pdf->addFont("robotolight", '', 7, '', false);*/
                //$pdf->addFont("hero", '', 7, '', false);
                if ($font == null) {
                    throw new Exception('Font nicht gefunden: [' .$family.':'.$style.':'.$weight.']');
                }
                if ($font && !isset($addedFonts[$font->id])) {
                    $pdf->addFont($family, $code, $font->basefilename);
                    $addedFonts[$font->id] = 1;
                }

                $pdf->setFont($family, $code, $o['fontSize']);
                preg_match('/cmyk\\((\\d+),(\\d+),(\\d+),(\\d+)\\)/', $o['color'], $m);

                $pdf->setTextColor($m[1], $m[2], $m[3], $m[4]);

                $textWidth = $pdf->GetStringWidth($o['text']);
                if ($alignment === 'R') {
                    $xPosition = $o['x'] - $textWidth;
                } else {
                    $xPosition = $o['x'];
                }
                $pdf->SetXY($margin + $xPosition, $margin + $o['y']);
                /*$pdf->setXY($margin + $o['x'], $margin + $o['y']);*/

                 $cellWidth = ($alignment === 'R') ? $textWidth : 0;

                $pdf->Cell($cellWidth, 0, $o['text'], 0, 0, $alignment, 0, '', 0, false, 'L', 'C');
            }

            if (isset($json[$i]['qrcodes'])) {
                $qrcodes = $json [$i]['qrcodes'];
                $border_style = array('all' => array('width' => 0, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'phase' => 0));
                for ($j = 0; $j < count($qrcodes); $j++) {
                    $o = $qrcodes[$j];

                    preg_match('/cmyk\\((\\d+),(\\d+),(\\d+),(\\d+)\\)/', $o['color'], $m);

                    $pdf->setFillColor($m[1], $m[2], $m[3], $m[4]);
                    $n = count($o['matrix']);
                    $margin2 = isset($o['margin']) ? $o['margin'] : 1.0;
                    $pw = ($o['size'] - 2 * $margin2) / $n;
                    $x0 = $margin + $margin2 + $o['x'];
                    $y0 = $margin + $margin2 + $o['y'];
                    for ($y = 0; $y < $n; $y++) {
                        for ($x = 0; $x < $n; $x++) {
                            if ($o['matrix'][$y][$x]) {
                                $pdf->rect($x0 + $x * $pw - 0.8, $y0 + $y * $pw, $pw, $pw, 'F', $border_style);
                            }
                        }
                    }
                }
            }
        }
        return $pdf;
    }

    public function getPreis() {
        $o = $this->vorlage()->first();
        if ($o) {
            $o = $o->preisschema()->first();
            if ($o) {
                $o = $o->preise()->where('menge', '=', $this->menge)->first();
                if ($o) {
                    $preis = $o->preis;
                    $extras = $this->extras()->get();
                    foreach ($extras as $e) {
                        $preis += $e->price;
                    }
                    return $preis;
                }
            }
        }
        throw new Exception($this->id);
    }

    public function getFreitext1() {
        $m = $this->mandant()->first();
        if ($m->freitext_feld1) {
            if ($m->freitext_feld1 == '$vorlage') {
                return $this->vorlage()->first()->name;
            }
            $input = json_decode($this->input, true);
            if (isset($input[$m->freitext_feld1])) {
                return $input[$m->freitext_feld1];
            }
        }
        return null;
    }

    public function updateFreitext1() {
        $this->freitext1 = $this->getFreitext1();
    }

}
