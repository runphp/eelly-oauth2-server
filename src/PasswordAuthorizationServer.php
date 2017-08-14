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

namespace Eelly\OAuth2\Server;

use Eelly\OAuth2\Server\Repositories\AccessTokenRepository;
use Eelly\OAuth2\Server\Repositories\ClientRepository;
use Eelly\OAuth2\Server\Repositories\RefreshTokenRepository;
use Eelly\OAuth2\Server\Repositories\ScopeRepository;
use Eelly\OAuth2\Server\Repositories\UserRepository;
use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\PasswordGrant;

/**
 * Class PasswordAuthorizationServer.
 */
class PasswordAuthorizationServer extends LeagueAuthorizationServer
{
    /**
     * PasswordAuthorizationServer constructor.
     *
     * @param CryptKey                 $privateKey
     * @param string                   $encryptionKey
     * @param \Eelly\SDK\User\Api\User $user
     */
    public function __construct(CryptKey $privateKey, string $encryptionKey, \Eelly\SDK\User\Api\User $user)
    {
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        parent::__construct($clientRepository, $accessTokenRepository, $scopeRepository, $privateKey, $encryptionKey);
        $userRepository = new UserRepository($user); // instance of UserRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

        $grant = new PasswordGrant($userRepository, $refreshTokenRepository);
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        $this->enableGrantType($grant, new \DateInterval('PT1H'));
    }
}
