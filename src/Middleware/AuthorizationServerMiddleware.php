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

namespace Eelly\OAuth2\Server\Middleware;

use Eelly\OAuth2\Server\ClientCredentialsAuthorizationServer;
use Eelly\OAuth2\Server\Middleware\Traits\ResponseTrait;
use Eelly\OAuth2\Server\PasswordAuthorizationServer;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use Phalcon\Http\RequestInterface;
use Phalcon\Http\ResponseInterface;

/**
 * @author hehui<hehui@eelly.net>
 */
class AuthorizationServerMiddleware
{
    use ResponseTrait;

    /**
     * @var CryptKey
     */
    private $cryptKey;

    /**
     * @var string
     */
    private $encryptionKey;

    /**
     * AuthorizationServerMiddleware constructor.
     *
     * @param CryptKey $cryptKey
     * @param string   $encryptionKey
     */
    public function __construct(CryptKey $cryptKey, string $encryptionKey)
    {
        $this->cryptKey = $cryptKey;
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

    private function getAuthorizationServer($grantType)
    {
        switch ($grantType) {
            case 'client_credentials':
                $server = new ClientCredentialsAuthorizationServer($this->cryptKey, $this->encryptionKey);
                break;
            case 'password':
                $server = new PasswordAuthorizationServer($this->cryptKey, $this->encryptionKey);
                break;
            default:
                throw OAuthServerException::unsupportedGrantType();
        }

        return $server;
    }
}
