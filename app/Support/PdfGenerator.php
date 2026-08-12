<?php

namespace App\Support;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

// Thin wrapper around mPDF, configured with the Kalpurush Bengali font
// registered as the default typeface. Used instead of barryvdh/laravel-dompdf
// everywhere in this app: dompdf does not shape complex scripts (it can't
// render Bengali conjuncts/ligatures correctly — যুক্তাক্ষর come out broken
// or as boxes), while mPDF's font-shaping engine handles Bengali properly
// once a Bengali-capable TTF is registered.
class PdfGenerator
{
    public static function make(array $options = []): Mpdf
    {
        $fontDirs = (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'];

        return new Mpdf(array_merge([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'kalpurush',
            'fontDir' => array_merge($fontDirs, [resource_path('fonts')]),
            'fontdata' => array_merge($fontData, [
                'kalpurush' => ['R' => 'Kalpurush.ttf'],
            ]),
            // useOTL (OpenType Layout) MUST be enabled — without it mPDF
            // renders each Bengali character's glyph independently instead
            // of applying the font's GSUB substitution tables, so conjuncts
            // (যুক্তাক্ষর) come out overlapping/broken instead of forming
            // the correct combined glyph. 0xFF enables it for all script
            // categories mPDF recognizes.
            'useOTL' => 0xFF,
            'useKashida' => 75,
            'tempDir' => storage_path('app/mpdf-tmp'),
        ], $options));
    }

    // Renders a Blade view to a PDF and streams it as a download response.
    public static function download(string $view, array $data, string $filename, array $options = []): \Symfony\Component\HttpFoundation\Response
    {
        $mpdf = self::make($options);
        $mpdf->WriteHTML(view($view, $data)->render());

        return response($mpdf->Output($filename, Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // Renders raw HTML (e.g. a certificate template with tokens already
    // substituted) to PDF bytes, for saving to disk rather than downloading.
    public static function htmlToBytes(string $html, array $options = []): string
    {
        $mpdf = self::make($options);
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }
}
