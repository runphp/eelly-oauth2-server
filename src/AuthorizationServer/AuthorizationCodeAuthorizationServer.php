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

namespace Eelly\OAuth2\Server\AuthorizationServer;

use Eelly\OAuth2\Server\Repositories\AccessTokenRepository;
use Eelly\OAuth2\Server\Repositories\AuthCodeRepository;
use Eelly\OAuth2\Server\Repositories\ClientRepository;
use Eelly\OAuth2\Server\Repositories\RefreshTokenRepository;
use Eelly\OAuth2\Server\Repositories\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\AuthCodeGrant;

/**
 * Class AuthorizationCodeAuthorizationServer.
 */
class AuthorizationCodeAuthorizationServer extends LeagueAuthorizationServer
{
    /**
     * AuthorizationCodeAuthorizationServer constructor.
     *
     * @param CryptKey $privateKey
     * @param string   $encryptionKey
     */
    public function __construct(CryptKey $privateKey, string $encryptionKey)
    {
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        $authCodeRepository = new AuthCodeRepository(); // instance of AuthCodeRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface
        parent::__construct($clientRepository, $accessTokenRepository, $scopeRepository, $privateKey, $encryptionKey);
        $grant = new AuthCodeGrant(
            $authCodeRepository,
            $refreshTokenRepository,
            new \DateInterval('PT10M') // authorization codes will expire after 10 minutes
        );
        $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // refresh tokens will expire after 1 month
        // Enable the authentication code grant on the server
        $this->enableGrantType(
            $grant,
            new \DateInterval('PT1H') // access tokens will expire after 1 hour
        );
    }
}
