<?php

namespace Pliasun\PdfToPng;

use Pliasun\PdfToPng\Exceptions\InvalidFormat;
use Pliasun\PdfToPng\Exceptions\PdfDoesNotExist;
use Pliasun\PdfToPng\Exceptions\PageDoesNotExist;

class PdfToPng
{
    protected $pdfFile;

    protected $resolution = 72;

    protected $outputFormat = 'png';

    protected $page = 1;

    protected $x = 72;
    protected $y = 72;

    protected $imagick;

    protected $validOutputFormats = ['jpg', 'jpeg', 'png'];

    /**
     * @param string $pdfFile The path or url to the pdffile.
     *
     * @throws \Pliasun\PdfToPng\Exceptions\PdfDoesNotExist
     */
    public function __construct($pdfFile)
    {
        if (! filter_var($pdfFile, FILTER_VALIDATE_URL) && ! file_exists($pdfFile)) {
            throw new PdfDoesNotExist();
        }

        $this->imagick = new \Imagick($pdfFile);
        $this->pdfFile = $pdfFile;
    }

    /**
     * Set the raster resolution.
     *
     * @param int $resolution
     *
     * @return $this
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Set the image resolution.
     *
     * @param int $resolution
     *
     * @return $this
     */
    public function setImageResolution($x, $y)
    {
        $this->x = $x;
        $this->y = $y;

        return $this;
    }


    /**
     * Set the output format.
     *
     * @param string $outputFormat
     *
     * @return $this
     *
     * @throws \Pliasun\PdfToPng\Exceptions\InvalidFormat
     */
    public function setOutputFormat($outputFormat)
    {
        if (! $this->isValidOutputFormat($outputFormat)) {
            throw new InvalidFormat('Format '.$outputFormat.' is not supported');
        }

        $this->outputFormat = $outputFormat;

        return $this;
    }

    /**
     * Determine if the given format is a valid output format.
     *
     * @param $outputFormat
     *
     * @return bool
     */
    public function isValidOutputFormat($outputFormat)
    {
        return in_array($outputFormat, $this->validOutputFormats);
    }

    /**
     * Set the page number that should be rendered.
     *
     * @param int $page
     *
     * @return $this
     *
     * @throws \Pliasun\PdfToPng\Exceptions\PageDoesNotExist
     */
    public function setPage($page)
    {
        if ($page > $this->getNumberOfPages()) {
            throw new PageDoesNotExist('Page '.$page.' does not exist');
        }

        $this->page = $page;

        return $this;
    }

    /**
     * Get the number of pages in the pdf file.
     *
     * @return int
     */
    public function getNumberOfPages()
    {
        return $this->imagick->getNumberImages();
    }

    /**
     * Save the image to the given path.
     *
     * @param string $pathToImage
     *
     * @return bool
     */
    public function saveImage($pathToImage)
    {
        $imageData = $this->getImageData($pathToImage);

        return file_put_contents($pathToImage, $imageData) === false ? false : true;
    }

    /**
     * Save the file as images to the given directory.
     *
     * @param string $directory
     * @param string $prefix
     *
     * @return array $files the paths to the created images
     */
    public function saveAllPagesAsImages($directory, $prefix = '')
    {
        $numberOfPages = $this->getNumberOfPages();

        if ($numberOfPages === 0) {
            return [];
        }

        return array_map(function ($pageNumber) use ($directory, $prefix) {
            $this->setPage($pageNumber);

            $destination = "{$directory}/{$prefix}{$pageNumber}.{$this->outputFormat}";

            $this->saveImage($destination);

            return $destination;
        }, range(1, $numberOfPages));
    }

    /**
     * Return raw image data.
     *
     * @param string $pathToImage
     *
     * @return \Imagick
     */
    public function getImageData($pathToImage)
    {
        $this->imagick->setResolution($this->resolution, $this->resolution);
        $this->imagick->setImageResolution(72, 72);
        $this->imagick->readImage(sprintf('%s[%s]', $this->pdfFile, $this->page - 1));
		$this->imagick->resampleImage($this->x, $this->y, \Imagick::FILTER_UNDEFINED, 1);
        $this->imagick->setFormat($this->determineOutputFormat($pathToImage));

        return $this->imagick;
    }

    /**
     * Determine in which format the image must be rendered.
     *
     * @param $pathToImage
     *
     * @return string
     */
    protected function determineOutputFormat($pathToImage)
    {
        $outputFormat = pathinfo($pathToImage, PATHINFO_EXTENSION);

        if ($this->outputFormat != '') {
            $outputFormat = $this->outputFormat;
        }

        $outputFormat = strtolower($outputFormat);

        if (! $this->isValidOutputFormat($outputFormat)) {
            $outputFormat = 'png';
        }

        return $outputFormat;
    }

    /**
     * Return info image.
     *
     * @param string $pathToImage
     *
     * @return arrray[geometry, dpi_x, dpi_y]
     */
    public function getResolutionData()
    {
        $geo = $this->imagick->getImageGeometry();
        $res = $this->imagick->getImageResolution();

        ($res['x']) ? $kx = $res['x'] : $kx = 72;
        ($res['y']) ? $ky = $res['y'] : $ky = 72;

        return ['geo' => $geo, 'x' => $kx, 'y' => $ky];
    }
}