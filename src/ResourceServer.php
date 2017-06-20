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
use League\OAuth2\Server\ResourceServer as LeagueResourceServer;

class ResourceServer extends LeagueResourceServer
{
    /**
     * @param string $cryptKeyPath 秘钥目录
     */
    public function __construct($cryptKeyPath)
    {
        parent::__construct(new AccessTokenRepository(), $cryptKeyPath.'/public.key');
    }
}
