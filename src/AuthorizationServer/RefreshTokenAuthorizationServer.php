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

namespace Shadon\OAuth2\Server\AuthorizationServer;

use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Shadon\OAuth2\Server\Repositories\AccessTokenRepository;
use Shadon\OAuth2\Server\Repositories\ClientRepository;
use Shadon\OAuth2\Server\Repositories\RefreshTokenRepository;
use Shadon\OAuth2\Server\Repositories\ScopeRepository;

class RefreshTokenAuthorizationServer extends LeagueAuthorizationServer
{
    /**
     * RefreshTokenAuthorizationServer constructor.
     *
     * @param CryptKey $privateKey
     * @param string   $encryptionKey
     */
    public function __construct(CryptKey $privateKey, string $encryptionKey)
    {
        $clientRepository = new ClientRepository();
        $accessTokenRepository = new AccessTokenRepository();
        $scopeRepository = new ScopeRepository();
        $refreshTokenRepository = new RefreshTokenRepository();

        parent::__construct($clientRepository, $accessTokenRepository, $scopeRepository, $privateKey, $encryptionKey);
        $grant = new RefreshTokenGrant($refreshTokenRepository);
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        $this->enableGrantType(
            $grant,
            new \DateInterval('P7D') // new access tokens will expire after an hour
        );
    }
}
