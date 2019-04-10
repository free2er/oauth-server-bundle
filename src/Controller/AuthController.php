<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Controller;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер авторизации
 */
class AuthController
{
    /**
     * Сервер авторизации
     *
     * @var AuthorizationServer
     */
    private $authorizationServer;

    /**
     * Фабрика HTTP-сообщений PSR-7
     *
     * @var HttpMessageFactoryInterface
     */
    private $psrMessageFactory;

    /**
     * Фабрика HTTP-сообщений Symfony
     *
     * @var HttpFoundationFactoryInterface
     */
    private $symfonyMessageFactory;

    /**
     * Конструктор
     *
     * @param AuthorizationServer            $authorizationServer
     * @param HttpMessageFactoryInterface    $psrMessageFactory
     * @param HttpFoundationFactoryInterface $symfonyMessageFactory
     */
    public function __construct(
        AuthorizationServer $authorizationServer,
        HttpMessageFactoryInterface $psrMessageFactory,
        HttpFoundationFactoryInterface $symfonyMessageFactory
    ) {
        $this->authorizationServer   = $authorizationServer;
        $this->psrMessageFactory     = $psrMessageFactory;
        $this->symfonyMessageFactory = $symfonyMessageFactory;
    }

    /**
     * Обрабатывает запрос
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $request  = $this->psrMessageFactory->createRequest($request);
        $response = $this->psrMessageFactory->createResponse(new Response());

        try {
            $response = $this->authorizationServer->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        }

        return $this->symfonyMessageFactory->createResponse($response);
    }
}
