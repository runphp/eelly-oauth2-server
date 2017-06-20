<?php

declare(strict_types=1);
/*
 * PHP version 7.1
 *
 * @copyright Copyright (c) 2012-2017 EELLY Inc. (https://www.eelly.com)
 * @link      https://api.eelly.com
 * @license   衣联网版权所有
 */

namespace Eelly\OAuth2\Server\Entities;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

class ScopeEntity extends AbstractMysqlEntity implements ScopeEntityInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Phalcon\Mvc\Model::getSource()
     */
    public function getSource()
    {
        return 'oauth_permission';
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->hash_name;
    }

    public function jsonSerialize(): void
    {
        return $this->getIdentifier();
    }
}
