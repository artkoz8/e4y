<?php

namespace App\Common\Ports\Rest;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class AbstractRestController extends AbstractController
{
    public function __construct(
        protected LoggerInterface $logger
    ) {}

    protected function validateAndDecodeJsonContent(Request $request): array
    {
        $content = $request->getContent();
        if (!json_validate($content)) {
            throw new BadRequestHttpException("Invalid JSON");
        }

        return json_decode($request->getContent(), true);
    }

    protected function preconditionResponse(Throwable $throwable, array $payload = null): JsonResponse
    {
        $this->logger->error(
            message: "Precondition Response: {$throwable->getMessage()}",
            context: [
                "exceptionClass" => get_class($throwable),
                "exceptionCode" => $throwable->getCode(),
                "exceptionTrace" => $throwable->getTraceAsString(),
                "payload" => $payload
            ]
        );

        return new JsonResponse(['errorMessage' => $throwable->getMessage()], Response::HTTP_PRECONDITION_FAILED);
    }

    protected function badRequestResponse(Throwable $throwable): JsonResponse
    {
        $this->logger->error(
            message: "Bad request: {$throwable->getMessage()}",
            context: [
                "exceptionClass" => get_class($throwable),
                "exceptionCode" => $throwable->getCode(),
                "exceptionTrace" => $throwable->getTraceAsString()
            ]
        );

        return new JsonResponse(['errorMessage' => $throwable->getMessage()], Response::HTTP_BAD_REQUEST);
    }

    protected function internalServerErrorResponse(Throwable $throwable, string $message = "Internal server error."): JsonResponse
    {
        $this->logger->error(
            message: "Bad request: {$throwable->getMessage()}",
            context: [
                "exceptionClass" => get_class($throwable),
                "exceptionCode" => $throwable->getCode(),
                "exceptionTrace" => $throwable->getTraceAsString(),
                "errorMessage" => $message
            ]
        );

        return new JsonResponse(['errorMessage' => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}