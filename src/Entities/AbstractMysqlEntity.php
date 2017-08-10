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

namespace Eelly\OAuth2\Server\Entities;

use Phalcon\Mvc\Model;

/**
 * @author hehui<hehui@eelly.net>
 */
abstract class AbstractMysqlEntity extends Model
{
    public function initialize(): void
    {
        $this->setWriteConnectionService('dbMaster');
        $this->setReadConnectionService('dbSlave');
    }
}
