<?php

declare(strict_types=1);

namespace App\Controller;

use App\API\BaseAPIResponse;
use App\API\ChangeBalanceCommand;
use App\Exception\EntityNotFoundException;
use App\Service\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class WalletController extends AbstractController
{
    public function __construct(
        private readonly WalletService $balanceService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly WalletService $walletService
    ) {
    }

    #[Route(
        path: '/api/balance/{walletId}',
        name: 'app_wallet_get_balance',
        requirements: ['walletId' => '\d+'],
        methods: 'GET'
    )]
    public function getBalance(int $walletId): Response
    {
        try {
            $balance = $this->balanceService->getBalance($walletId);
            return $this->json(
                BaseAPIResponse::createSuccessWithData($balance),
                Response::HTTP_OK
            );
        } catch (EntityNotFoundException) {
            return $this->json(
                BaseAPIResponse::createErrorResponse('Wallet not found'),
                Response::HTTP_NOT_FOUND,
            );
        } catch (Throwable $t) {
            return $this->json(
                BaseAPIResponse::createErrorResponse('Internal API error'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route(
        path: '/api/balance',
        name: 'app_wallet_change_balance',
        methods: 'POST'
    )]
    public function changeBalance(Request $request): Response
    {
        try {
            $command = $this->serializer->deserialize(
                $request->getContent(),
                ChangeBalanceCommand::class,
                JsonEncoder::FORMAT
            );
            $this->validate($command, 'Invalid validate input request');
            $this->walletService->changeBalance($command);
            return $this->json(BaseAPIResponse::createSuccessResponse(), Response::HTTP_OK);
        } catch (ValidatorException $exception) {
            return $this->json(
                BaseAPIResponse::createErrorResponse($exception->getMessage()),
                Response::HTTP_BAD_REQUEST
            );
        } catch (Throwable $t) {
            return $this->json(
                BaseAPIResponse::createErrorResponse('Internal API error'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function validate(object $object, string $message): void
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            $details = [];
            foreach ($errors as $error) {
                /** @var ConstraintViolation $error */
                $details[] = sprintf(
                    '%s (%s)',
                    $error->getPropertyPath(),
                    $error->getMessage()
                );
            }
            $message = sprintf('%s : %s', $message, implode("; ", $details));
            throw new ValidatorException($message);
        }
    }
}
