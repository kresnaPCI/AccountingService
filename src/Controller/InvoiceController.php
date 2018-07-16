<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\Invoice\UpdateDateCommand;
use App\Command\Invoice\UpdateStatusCommand;
use App\Transformer\InvoiceTransformer;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvoiceController
 * @package App\Controller
 */
class InvoiceController extends Controller
{
    /**
     * @param Request $request
     * @param InvoiceTransformer $invoiceTransformer
     * @return Response
     */
    public function create(Request $request, string $accountId, InvoiceTransformer $invoiceTransformer): Response
    {
        $data = json_decode($request->getContent(), true);
        $data['accountId'] = $accountId;

        $invoice = $invoiceTransformer->transform($data);

        $invoice_create = $this->get('accounting.service.invoice')->create($invoice);
        if (empty($invoice_create)){
            return new JsonResponse(['success' => false]);
        }else{
            return new JsonResponse(['success' => true]);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function updateDate(Request $request, string $accountId, int $invoiceId): Response
    {
        $body = json_decode($request->getContent(), true);

        $command = new UpdateDateCommand($accountId, $invoiceId, new DateTime($body['date']), $body['pdfUrl']);

        $this->get('accounting.service.invoice')->updateDate($command);

        return new JsonResponse(['success' => true]);
    }

     public function markPaid(Request $request, string $accountId, int $invoiceId, int $transactionId): Response
    {
        $data = json_decode($request->getContent(), true);
        $data = array(
            'accountId' => $accountId,
            'invoiceId' => $invoiceId
        );

        file_put_contents('markpaiddata.txt',print_r($data, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        $invoice_paid = $this->get('accounting.service.invoice')->markPaid($accountId, $invoiceId, $transactionId);
        if (empty($invoice_paid)){
            return new JsonResponse(['success' => false]);
        }else{
            return new JsonResponse(['success' => true]);
        }
    }

    public function cancelInvoice(Request $request, int $invoiceId): Response
    {
        $invoice_open = $this->get('accounting.service.invoice')->cancelInvoice($invoiceId);
        if (empty($invoice_open)){
            return new JsonResponse(['success' => false]);
        }else{
            return new JsonResponse(['success' => true]);
        }
    }

}
