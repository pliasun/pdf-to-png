<?php

namespace Pliasun\PdfToPng;

/**
 * Class PdfToPngClient
 * @package Pliasun\PdfToPng
 */
class PdfToPngClient
{
    public function convert($from, $to, $resolution_x = 72, $resolution_y = 72, $quality = 144)
    {
        
        $pdf = new PdfToPng($from);
        $pdf->setResolution($quality);
        $pdf->setImageResolution($resolution_x, $resolution_y);
        $pdf->setOutputFormat('png')->saveImage($to);
        return true;
    }

    public function convert_size($from, $to, $size_x = 800, $size_y = 600, $quality = 72, $ratio = null)
    {   
        $pdf = new PdfToPng($from);
        $data = $pdf->getResolutionData();
        $pdf->setResolution($quality);

        $rx = $size_x/$data['geo']['width'];
        $ry = $size_y/$data['geo']['height'];
        
        switch ($ratio) {
        	case 'width':
        		$pdf->setImageResolution($data['x']*$rx, $data['y']*$rx);
        		break;
       		case 'height':
       			$pdf->setImageResolution($data['x']*$ry, $data['y']*$ry);
       			break;
          case 'both':
            $pdf->setImageResolution($data['x']*min($rx, $ry), $data['y']*min($rx, $ry));  
            break;
       		default:
       			$pdf->setImageResolution($data['x']*$rx, $data['y']*$ry);
       			break;
       	}
        	
        $pdf->setOutputFormat('png')->saveImage($to);
        return $pdf->getResolutionData();
    }

}
