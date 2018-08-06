<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\Invoice\MarkPaidCommand;
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
     * @param string $accountId
     * @param InvoiceTransformer $invoiceTransformer
     * @return Response
     */
    public function create(Request $request, string $accountId, InvoiceTransformer $invoiceTransformer): Response
    {
        $data = json_decode($request->getContent(), true);
        $data['accountId'] = $accountId;

        $invoice = $invoiceTransformer->transform($data);

        $this->get('accounting.service.invoice')->create($invoice);

        return new JsonResponse(['success' => true]);
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

    /**
     * @param Request $request
     * @param string $accountId
     * @param int $invoiceId
     * @return Response
     */
    public function pay(Request $request, string $accountId, int $invoiceId): Response
    {
        $body = json_decode($request->getContent(), true);

        $command = new MarkPaidCommand($accountId, $invoiceId, $body['transactionId'], $body['pdfUrl']);

        $this->get('accounting.service.invoice')->markPaid($command);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @param Request $request
     * @param string $accountId
     * @param int $invoiceId
     * @return Response
     */
    public function changeStatus(Request $request, string $accountId, int $invoiceId): Response
    {
        $body = json_decode($request->getContent(), true);

        $command = new UpdateStatusCommand($accountId, $invoiceId, $body['status'], $body['pdfUrl']);

        $success = $this->get('accounting.service.invoice')->updateStatus($command);

        return new JsonResponse(['success' => $success], $success ? 200 : 400);
    }
}
