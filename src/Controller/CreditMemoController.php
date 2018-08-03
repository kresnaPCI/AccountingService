<?php

namespace App\Controller;

use App\Command\CreditMemo\RefundCommand;
use App\Transformer\CreditMemoTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CreditMemoController
 * @package App\Controller
 */
class CreditMemoController extends Controller
{
    /**
     * @param Request $request
     * @param string $accountId
     * @param CreditMemoTransformer $creditMemoTransformer
     * @return Response
     */
    public function create(Request $request, string $accountId, CreditMemoTransformer $creditMemoTransformer): Response
    {
        $data = json_decode($request->getContent(), true);
        $data['accountId'] = $accountId;

        $creditMemo = $creditMemoTransformer->transform($data);

        $this->get('accounting.service.creditmemo')->create($creditMemo);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @param Request $request
     * @param string $accountId
     * @param int $creditMemoId
     * @return Response
     */
    public function refund(Request $request, string $accountId, int $creditMemoId): Response
    {
        $body = json_decode($request->getContent(), true);

        $command = new RefundCommand(
            $accountId,
            $creditMemoId,
            $body['method'],
            $body['transactionId'],
            $body['pdfUrl']
        );

        $this->get('accounting.service.creditmemo')->refund($command);

        return new JsonResponse(['success' => true]);
    }

}