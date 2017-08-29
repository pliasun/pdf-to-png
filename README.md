# pdf-to-png
Convert a pdf to png

/config/app.php

Package Service Providers...
Pliasun\PdfToPng\PdfToPngServiceProvider::class,
 
 ``
 'aliases' => [
  'PdfToPng' => Pliasun\PdfToPng\PdfToPngFacade::class,
 ``
 
 in code
 
 PdfToPng::convert_size($from, $to, $size_x, $size_y, $quality, $ratio);
 
 PdfToPng::convert($from, $to, $resolution_x, $resolution_y, $quality)
 
 ##$ratio = width | height | both | null
