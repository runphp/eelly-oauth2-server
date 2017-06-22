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

namespace Eelly\OAuth2\Server\Middleware\Traits;

use Phalcon\Http\ResponseInterface;

/**
 * @author hehui<hehui@eelly.net>
 */
trait ResponseTrait
{
    private function convertResponse(\Psr\Http\Message\ResponseInterface $psr7Response, ResponseInterface $response): ResponseInterface
    {
        $response->setStatusCode($psr7Response->getStatusCode(), $psr7Response->getReasonPhrase());
        foreach ($psr7Response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $response->setHeader($name, $value);
            }
        }
        $body = $psr7Response->getBody();
        $body->seek(0);
        $response->setContent($body->getContents());

        return $response;
    }
}
