<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\CreditMemo\UpdateDateCommand;
use App\Command\CreditMemo\UpdateStatusCommand;
use App\Transformer\CreditMemoTransformer;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvoiceController
 * @package App\Controller
 */
class CreditMemoController extends Controller
{
    /**
     * @param Request $request
     * @param InvoiceTransformer $invoiceTransformer
     * @return Response
     */
    public function create(Request $request, string $invoiceId, CreditMemoTransformer $creditMemoTransformer): Response
    {
        $data = json_decode($request->getContent(), true);
        $data['invoiceId'] = $invoiceId;

        $CreditMemo = $creditMemoTransformer->transform($data);

        $CreditAdpater = $this->get('accounting.service.creditmemo')->create($CreditMemo);
        return new JsonResponse(['success' => $CreditAdpater]);
    }


    public function updateDate(Request $request, string $accountId, int $creditMemoId): Response
    {
        $body = json_decode($request->getContent(), true);

        $command = new UpdateDateCommand($accountId, $creditMemoId, new DateTime($body['date']), $body['pdfUrl']);

        $this->get('accounting.service.creditmemo')->updateDate($command);

        return new JsonResponse(['success' => true]);

    }


    public function markPaid(Request $request, string $accountId, int $creditMemoId, string $transactionId): Response
    {
        $data = json_decode($request->getContent(), true);
        $command = new UpdateStatusCommand($creditMemoId, $accountId, $transactionId, $data['pdfUrl'], $data['status'], $data['payment_type'], $data['partner_type']);
        $this->get('accounting.service.creditmemo')->markPaid($command);

         return new JsonResponse(['success' => true]);
    }

    
    public function cancelCreditMemo(Request $request, int $creditMemoId): Response
    {
        $memo_open = $this->get('accounting.service.creditmemo')->cancelCreditMemo($creditMemoId);
        if (empty($memo_open)){
            return new JsonResponse(['success' => false]);
        }else{
            return new JsonResponse(['success' => true]);
        }
    }

}
