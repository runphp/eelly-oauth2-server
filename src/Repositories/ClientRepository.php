<?php

declare(strict_types=1);
/*
 * PHP version 7.1
 *
 * @copyright Copyright (c) 2012-2017 EELLY Inc. (https://www.eelly.com)
 * @link      https://api.eelly.com
 * @license   衣联网版权所有
 */

namespace Eelly\OAuth2\Server\Repositories;

use Eelly\OAuth2\Server\Entities\ClientEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * @author hehui<hehui@eelly.net>
 */
class ClientRepository implements ClientRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Repositories\ClientRepositoryInterface::getClientEntity()
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true)
    {
        $client = ClientEntity::findFirstByClientKey($clientIdentifier);
        // Check if client is registered
        if (false === $client) {
            return;
        }
        //dd(password_hash('abc123', PASSWORD_BCRYPT));
        if ($mustValidateSecret
            && (bool) $client->is_encrypt
            && false === password_verify($clientSecret, $client->client_secret)
            ) {
            return;
        }

        return $client;
    }
}
