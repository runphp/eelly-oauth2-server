<?php

declare(strict_types=1);
/*
 * PHP version 7.1
 *
 * @copyright Copyright (c) 2012-2017 EELLY Inc. (https://www.eelly.com)
 * @link      https://api.eelly.com
 * @license   衣联网版权所有
 */

namespace Eelly\OAuth2\Server;

use Eelly\OAuth2\Server\Repositories\AccessTokenRepository;
use Eelly\OAuth2\Server\Repositories\ClientRepository;
use Eelly\OAuth2\Server\Repositories\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;

/**
 * @author hehui<hehui@eelly.net>
 */
class ClientCredentialsAuthorizationServer extends LeagueAuthorizationServer
{
    /**
     * @param string $cryptKeyPath 秘钥目录
     */
    public function __construct($cryptKeyPath)
    {
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        parent::__construct($clientRepository, $accessTokenRepository, $scopeRepository, $cryptKeyPath.'/private.key', $cryptKeyPath.'/public.key');
        $this->enableGrantType(new ClientCredentialsGrant(), new \DateInterval('PT1H'));
    }
}
