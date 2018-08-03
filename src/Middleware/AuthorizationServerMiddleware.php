<?php

declare(strict_types=1);

/*
 * This file is part of eelly package.
 *
 * (c) eelly.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shadon\OAuth2\Server\Middleware;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use Phalcon\DiInterface as Di;
use Phalcon\Http\RequestInterface;
use Phalcon\Http\ResponseInterface;
use Shadon\Di\InjectionAwareInterface;
use Shadon\OAuth2\Server\AuthorizationServer\AuthorizationCodeAuthorizationServer;
use Shadon\OAuth2\Server\AuthorizationServer\ClientCredentialsAuthorizationServer;
use Shadon\OAuth2\Server\AuthorizationServer\PasswordAuthorizationServer;
use Shadon\OAuth2\Server\AuthorizationServer\QQAuthorizationServer;
use Shadon\OAuth2\Server\AuthorizationServer\RefreshTokenAuthorizationServer;
use Shadon\OAuth2\Server\Middleware\Traits\ResponseTrait;

/**
 * @author hehui<hehui@eelly.net>
 */
class AuthorizationServerMiddleware implements InjectionAwareInterface
{
    use ResponseTrait;

    private $di;

    /**
     * @var CryptKey
     */
    private $privateKey;

    /**
     * @var string
     */
    private $encryptionKey;

    /**
     * AuthorizationServerMiddleware constructor.
     *
     * @param CryptKey $privateKey
     * @param string   $encryptionKey
     */
    public function __construct(CryptKey $privateKey, string $encryptionKey)
    {
        $this->privateKey = $privateKey;
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param callable          $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $psr7Response = new Response();

        try {
            $grantType = $request->getPost('grant_type');
            $server = $this->getAuthorizationServer($grantType);
            $psr7Request = ServerRequest::fromGlobals();
            $psr7Response = $server->respondToAccessTokenRequest($psr7Request, $psr7Response);
            $this->convertResponse($psr7Response, $response);
        } catch (OAuthServerException $exception) {
            $psr7Response = $exception->generateHttpResponse($psr7Response);

            return $this->convertResponse($psr7Response, $response);
        }

        // Pass the request and response on to the next responder in the chain
        return $next($request, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function setDI(Di $di): void
    {
        $this->di = $di;
    }

    /**
     * {@inheritdoc}
     */
    public function getDI()
    {
        return $this->di;
    }

    /**
     * @param string|null $grantType
     *
     * @throws OAuthServerException
     *
     * @return ClientCredentialsAuthorizationServer|PasswordAuthorizationServer
     */
    private function getAuthorizationServer(string $grantType = null)
    {
        if (null === $grantType) {
            throw OAuthServerException::invalidRequest('grant_type');
        }
        switch ($grantType) {
            case 'client_credentials':
                $server = new ClientCredentialsAuthorizationServer($this->privateKey, $this->encryptionKey);
                break;
            case 'password':
                $server = new PasswordAuthorizationServer($this->privateKey, $this->encryptionKey, new \Eelly\SDK\User\Api\User());
                break;
            case 'authorization_code':
                $server = new AuthorizationCodeAuthorizationServer($this->privateKey, $this->encryptionKey);
                break;
            case 'refresh_token':
                $server = new RefreshTokenAuthorizationServer($this->privateKey, $this->encryptionKey);
                break;
            case 'qq':
                $server = new QQAuthorizationServer($this->privateKey, $this->encryptionKey, new \Eelly\SDK\User\Api\User());
                break;
            default:
                throw OAuthServerException::unsupportedGrantType();
        }

        return $server;
    }
}
