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

use Eelly\OAuth2\Server\Entities\AccessTokenEntity;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * @author hehui<hehui@eelly.net>
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::getNewToken()
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::persistNewAccessToken()
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $accessTokenEntity->save();
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::revokeAccessToken()
     */
    public function revokeAccessToken($tokenId): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::isAccessTokenRevoked()
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        return false; // Access token hasn't been revoked
    }
}
