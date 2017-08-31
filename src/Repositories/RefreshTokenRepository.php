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

use Eelly\OAuth2\Server\Entities\RefreshTokenEntity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $refreshTokenEntity->save();
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId): void
    {
        $refreshTokenEntity = RefreshTokenEntity::findFirst([['identifier'=>$tokenId]]);
        if (false !== $refreshTokenEntity) {
            $refreshTokenEntity->setRevoked(true);
            $refreshTokenEntity->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $refreshTokenEntity = RefreshTokenEntity::findFirst([['identifier'=>$tokenId]]);
        $isRefreshTokenRevoked = true;
        if (false !== $refreshTokenEntity) {
            $isRefreshTokenRevoked = $refreshTokenEntity->isRevoked();
        }

        return $isRefreshTokenRevoked;
    }
}
