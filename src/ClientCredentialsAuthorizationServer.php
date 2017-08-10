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
use Eelly\OAuth2\Server\Repositories\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;

/**
 * @author hehui<hehui@eelly.net>
 */
class ClientCredentialsAuthorizationServer extends LeagueAuthorizationServer
{
    /**
     * ClientCredentialsAuthorizationServer constructor.
     *
     * @param CryptKey $cryptKey
     * @param string   $encryptionKey
     */
    public function __construct(CryptKey $cryptKey, string $encryptionKey)
    {
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        parent::__construct($clientRepository, $accessTokenRepository, $scopeRepository, $cryptKey, $encryptionKey);
        $this->enableGrantType(new ClientCredentialsGrant(), new \DateInterval('P1M'));
    }
}
