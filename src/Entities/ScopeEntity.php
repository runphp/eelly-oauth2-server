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

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ScopeEntity extends AbstractMysqlEntity implements ScopeEntityInterface
{
    use EntityTrait;

    /**
     * {@inheritdoc}
     *
     * @see \Phalcon\Mvc\Model::getSource()
     */
    public function getSource()
    {
        return 'oauth_permission';
    }

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}
