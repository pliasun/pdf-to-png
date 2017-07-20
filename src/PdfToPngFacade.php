<?php

namespace Pliasun\PdfToPng;

use Illuminate\Support\Facades\Facade;

/**
 * Class PdfToPngFacade
 * @package Pliasun\PdfToPng
 */
class PdfToPngFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PdfToPngClient';
    }
}
