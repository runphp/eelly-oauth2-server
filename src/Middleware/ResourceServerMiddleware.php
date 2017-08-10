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

use Eelly\OAuth2\Server\Middleware\Traits\ResponseTrait;
use Eelly\OAuth2\Server\ResourceServer;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use Phalcon\Http\RequestInterface;
use Phalcon\Http\ResponseInterface;

class ResourceServerMiddleware
{
    use ResponseTrait;

    /**
     * @var CryptKey
     */
    private $cryptKey;

    /**
     * ResourceServerMiddleware constructor.
     *
     * @param CryptKey $cryptKey
     */
    public function __construct($cryptKey)
    {
        $this->cryptKey = $cryptKey;
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
            $server = new ResourceServer($this->cryptKey);
            $psr7Request = ServerRequest::fromGlobals();
            $psr7Request = $server->validateAuthenticatedRequest($psr7Request);
        } catch (OAuthServerException $exception) {
            $psr7Response = $exception->generateHttpResponse($psr7Response);

            return $this->convertResponse($psr7Response, $response);
            // @codeCoverageIgnoreStart
        } catch (\Exception $exception) {
            $psr7Response = (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($psr7Response);

            return $this->convertResponse($psr7Response, $response);
            // @codeCoverageIgnoreEnd
        }

        // Pass the request and response on to the next responder in the chain
        return $next($psr7Request, $response);
    }
}
