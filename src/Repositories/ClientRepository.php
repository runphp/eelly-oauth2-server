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
