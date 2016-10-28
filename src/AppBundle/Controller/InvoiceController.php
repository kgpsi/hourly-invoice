<?php
// src/AppBundle/Controller/InvoiceController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController extends Controller
{
    /**
     * Display hourly invoice form.
     *
     * @Route("/invoice/form")
     */
    public function showInvoiceFormAction() {
        //$this->get('app.pdf')->fill();
        return $this->render('hourly_invoice_form.html.twig');
    }

    /**
     * Creates new hourly invoice pdf with information filled in.
     *
     * @Route("/invoice/fill-form", name="fill_invoice_form")
     */
    public function postInvoiceFormAction(Request $request) {
        // Should have validation to check if the params are correct type
        $params = $request->request->all();
        $filePath = $this->get('kernel')->getRootDir() . '/../web/invoice-and-receipt-template.pdf';
        $allTotal = [];
        if (!file_exists($filePath)) {
            return new Response(json_encode(['success' => false, 'error' => 'File not found.']), 400);
        }
        $fillData = [
            'date'=> date("Y/m/d"),
            'bill_to' => $params['address']."\n".$params['city']. ' ' . $params['state'] . ' ' . $params['zip_code'],
        ];
        foreach ($params['descriptions'] as $key => $description) {
            $fillData['description_' . $key] = $description['description'];
            $fillData['hourly_rate_' . $key] = $description['hourly_rate'];
            $fillData['hours_' . $key] = $description['hours'];
            $fillData['total_price_' . $key] = $allTotal[] = $description['hourly_rate'] * $description['hours'];
            /*
            foreach ($description as $title => $value) {
                $fillData[$title . '_' . $key] = $value;
            }
            */
        }
        // If tax was included the total price would be different
        $fillData['total'] = $fillData['subtotal'] = $subtotal = array_sum($allTotal);
        $filledFile = $this->get('app.pdf')->fill($filePath, $fillData);
        return $this->json(['success' => true, 'file_name' => $filledFile]);
    }

    /**
     * Retrieve hourly invoice pdf.
     *
     * @Route("/invoice/retrieve", name="retrieve_filled_invoice")
     */
    public function retrieveInvoiceAction(Request $request) {
        $name = urlencode($request->query->get('name'));
        $filePath = $this->get('kernel')->getRootDir() . '/../web/'. $name . '.pdf';
        if (!file_exists($filePath)) {
            return new Response(json_encode(['success' => false, 'error' => 'File not found.']), 400);
        }
        $response = new BinaryFileResponse($filePath);
        return $response;
    }
}