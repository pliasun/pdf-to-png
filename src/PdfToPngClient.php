<?php

namespace Pliasun\PdfToPng;

/**
 * Class PdfToPngClient
 * @package Pliasun\PdfToPng
 */
class PdfToPngClient
{
    public function convert($from, $to)
    {
        
        $pdf = new PdfToPng($from);
        $pdf->setResolution(600);
        $pdf->setOutputFormat('png')->saveImage($to);
        return true;
    }

}
