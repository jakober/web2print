<?php

class FontController extends BaseController
{
    /**
     * Konvertiert die Fonts nach TCPDF-Format und gibt eine Test-PDF aus.
     * Erzeugt je Font: .php, .z, .ctg.z in storage/_fonts/
     */
    public function convert()
    {
        // 0) Pfad vorbereiten
        $fontDir = storage_path('fonts') . '/';

        // Ordner anlegen, falls nicht vorhanden
        if (!is_dir($fontDir)) {
            if (!@mkdir($fontDir, 0775, true)) {
                Log::error('Konnte Font-Ordner nicht anlegen', ['dir' => $fontDir]);
                return Response::make('Font-Ordner konnte nicht angelegt werden: '.$fontDir, 500);
            }
        }

        // Rechte checken
        if (!is_writable($fontDir)) {
            Log::error('Font-Ordner ist nicht beschreibbar', ['dir' => $fontDir]);
            return Response::make('Font-Ordner ist nicht beschreibbar: '.$fontDir, 500);
        }

        // 1) TCPDF Fonts-Verzeichnis setzen (wichtig: trailing slash!)
        if (!defined('K_PATH_FONTS')) {
            define('K_PATH_FONTS', $fontDir);
        }

        // 2) Zu konvertierende Fonts definieren (Quelle => (Engine, Hinweis))
        //  - TTF: 'TrueTypeUnicode'
        //  - OTF: 'OpenTypeUnicode'
        $sources = [
            'acumin-pro-light.ttf'  => 'TrueTypeUnicode',
            'acumin-pro-medium.ttf' => 'TrueTypeUnicode',
        ];

        $converted = [];
        $errors    = [];

        foreach ($sources as $file => $engine) {
            $src = $fontDir . $file;

            if (!is_file($src)) {
                $msg = "Font-Quelldatei fehlt: {$src}";
                Log::error($msg);
                $errors[] = $msg;
                continue;
            }

            try {
                // 3) Konvertieren – liefert den internen Fontnamen zurück (wichtig für SetFont)
                $name = \TCPDF_FONTS::addTTFfont($src, $engine, '', 32);

                if ($name === false) {
                    $msg = "TCPDF konnte den Font nicht konvertieren: {$file}";
                    Log::error($msg);
                    $errors[] = $msg;
                    continue;
                }

                // 4) Existenzcheck der erzeugten Dateien
                $php   = K_PATH_FONTS . $name . '.php';
                $z     = K_PATH_FONTS . $name . '.z';
                $ctgz  = K_PATH_FONTS . $name . '.ctg.z';

                $exists = [
                    'php'  => is_file($php),
                    'z'    => is_file($z),
                    'ctgz' => is_file($ctgz),
                ];

                Log::info('Font konvertiert', [
                    'source'  => $file,
                    'name'    => $name,
                    'engine'  => $engine,
                    'files'   => compact('php','z','ctgz'),
                    'exists'  => $exists,
                    'K_PATH_FONTS' => K_PATH_FONTS,
                ]);

                // Mindestens die .php muss existieren
                if (!$exists['php']) {
                    $msg = "Font-Definition (.php) fehlt nach Konvertierung: {$php}";
                    Log::error($msg);
                    $errors[] = $msg;
                }

                $converted[$file] = $name;
            } catch (\Exception $e) {
                $msg = "Fehler bei Font-Konvertierung: {$file} -> " . $e->getMessage();
                Log::error($msg);
                $errors[] = $msg;
            }
        }

        // 5) Test-PDF erzeugen (FPDI basiert auf TCPDF)
        try {
            $pdf = new \FPDI('P', 'mm', 'A4');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(15, 15, 15);
            $pdf->AddPage();

            $y = 30;

            // Medium-Text
            if (!empty($converted['acumin-pro-medium.otf'])) {
                $pdf->SetFont($converted['acumin-pro-medium.otf'], '', 16);
                $pdf->Text(15, $y, 'Hallo aus Acumin Pro Medium (OTF)');
                $y += 12;
            } else {
                $pdf->SetFont('helvetica', '', 16);
                $pdf->Text(15, $y, 'Fallback: Helvetica (Medium fehlte)');
                $y += 12;
            }

            // Light-Text
            if (!empty($converted['acumin-pro-light.ttf'])) {
                $pdf->SetFont($converted['acumin-pro-light.ttf'], '', 12);
                $pdf->Text(15, $y, 'Und das hier ist Acumin Pro Light (TTF)');
                $y += 10;
            } else {
                $pdf->SetFont('helvetica', '', 12);
                $pdf->Text(15, $y, 'Fallback: Helvetica (Light fehlte)');
                $y += 10;
            }

            // Infos ausgeben
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetY($y + 5);
            $pdf->MultiCell(0, 5,
                "K_PATH_FONTS: " . K_PATH_FONTS . "\n" .
                "Konvertiert: " . json_encode($converted) . "\n" .
                (!empty($errors) ? "WARN/ERROR:\n- " . implode("\n- ", $errors) : "Keine Fehler erkannt."),
                0, 'L', false, 1
            );

            // PDF ausgeben
            return $pdf->Output('fonts-test.pdf', 'I');
        } catch (\Exception $e) {
            Log::error('Fehler beim Erzeugen der Test-PDF', ['err' => $e->getMessage()]);
            return Response::make('Fehler beim Erzeugen der Test-PDF: '.$e->getMessage(), 500);
        }
    }
}






/*class FontController extends BaseController {

    public function convert() {
        $path = storage_path() . '/_fonts/';

        $pdf = new FPDI('', 'mm', array(55, 85));


        $pdf->addTTFfont($path . 'HLR____.otf', 'OpenTypeUnicode', '', 32);
        $pdf->addTTFfont($path . 'HLB____.otf', 'OpenTypeUnicode', '', 32);
        $pdf->addTTFfont($path . 'HLCB___.otf', 'OpenTypeUnicode', '', 32);

        $pdf = new FPDI('', 'mm', array(100, 100));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetAutoPageBreak(false);
        $pdf->setCellPaddings(0, 0, 0, 0);
        $pdf->addPage();


        $pdf->addFont('Helvetica Neue','','hlr____');

        $pdf->setFont('Helvetica Neue','',20);
        $pdf->setTextColor(0,0,0,100);
        $pdf->setXY(50,50);
        $pdf->Cell(0, 0, 'Test', 0, 0, 'L', 0, '', 0, false, 'L', 'C');
        $pdf->output();

    }

}*/
