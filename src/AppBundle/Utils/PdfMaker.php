<?php
// src/AppBundle/Utils/Pdf.php
namespace AppBundle\Utils;
use mikehaertl\pdftk\Pdf;
class PdfMaker
{
	/**
     * Creates a copy of the pdf from the first param with the 
     * information from the second param and saves the new file.
     *
     * @param string $fillPath	Path of the pdf to be filled
     * @param array  $fillData  Information to fill the pdf
     *
     * @return string The filename of the new file.
     */
    public function fill($filePath, $fillData) {
    	// Should check if the new filename exist
    	$fileName = uniqid();
    	$pdf = new Pdf($filePath);
        $pdf->fillForm($fillData)
	        ->needAppearances()
	        ->flatten()
            ->saveAs($fileName . '.pdf');
    	return $fileName;
    }
}