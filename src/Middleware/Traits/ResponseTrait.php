<?php

declare(strict_types=1);
/*
 * PHP version 7.1
 *
 * @copyright Copyright (c) 2012-2017 EELLY Inc. (https://www.eelly.com)
 * @link      https://api.eelly.com
 * @license   衣联网版权所有
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
