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
    public function create(Request $request, string $accountId, CreditMemoTransformer $creditMemoTransformer): Response
    {
        $data = json_decode($request->getContent(), true);
        $data['accountId'] = $accountId;

        $CreditMemo = $creditMemoTransformer->transform($data);

        $CreditAdpater = $this->get('accounting.service.creditmemo')->create($CreditMemo);
        // file_put_contents('markpaid.txt',print_r($CreditMemo, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        file_put_contents('markpaid.txt',print_r($CreditAdpater, true).PHP_EOL , FILE_APPEND | LOCK_EX);
        return new JsonResponse(['success' => true]);
    }

    // /**
    //  * @param Request $request
    //  * @return Response
    //  */
    // public function updateDate(Request $request, string $accountId, int $invoiceId): Response
    // {
    //     $body = json_decode($request->getContent(), true);

    //     $command = new UpdateDateCommand($accountId, $invoiceId, new DateTime($body['date']), $body['pdfUrl']);

    //     $this->get('accounting.service.invoice')->updateDate($command);

    //     return new JsonResponse(['success' => true]);
    // }

}
