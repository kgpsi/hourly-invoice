<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use mikehaertl\pdftk\Pdf;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number")
     */
    public function numberAction()
    {
        //$pdf = new Pdf('my.pdf');
        //$data = $pdf->getDataFields();
        //return $path = $this->get('kernel')->getRootDir() . '/../web/invoice-and-receipt-template.pdf';
        //return new Response($this->get('kernel')->getRootDir() . '/../web/invoice-and-receipt-template.pdf');
        $pdf = new Pdf($this->get('kernel')->getRootDir() . '/../web/invoice-and-receipt-template.pdf');
        //file_get_contents($this->get('kernel')->getRootDir() . '/../web/invoice-and-receipt-template.pdf');
        $bool = file_exists($this->get('kernel')->getRootDir() . '/../web/invoice-and-receipt-template.pdf');
        if ($bool) {
            //return new Response('yes');
        } else {
            //return new Response('no');
        }
        //$data = $pdf->getDataFields();
        //return new Response($data);
        $pdf->fillForm(array(
            'date'=> date("Y/m/d"),
            'bill_to' => '5321 Meadow Valley Dr',
            'description_1' => 'somthing...',
            'hourly_rate_1' => '500',
            'hours_1' => '3',
            'total_price_1' => 500 * 3,
        ))
        ->needAppearances()
        ->saveAs('filled.pdf');
        return $this->render('hourly_invoice_form.html.twig');
    }
}