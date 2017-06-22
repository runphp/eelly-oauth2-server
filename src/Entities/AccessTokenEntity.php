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

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * @author hehui<hehui@eelly.net>
 */
class AccessTokenEntity extends AbstractMongoEntity implements AccessTokenEntityInterface
{
    use AccessTokenTrait, EntityTrait, TokenEntityTrait;

    protected $client_id;

    public function initialize(): void
    {
        $this->useImplicitObjectIds(true);
        $this->selectDb('oauth');
    }

    public function beforeSave(): void
    {
        $this->client_id = $this->client->getIdentifier();
        $this->expiryDateTime = new \MongoDB\BSON\UTCDateTime(floor($this->expiryDateTime->format('U.u') * 1000));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Phalcon\Mvc\Collection::getReservedAttributes()
     */
    public function getReservedAttributes()
    {
        $reserved = parent::getReservedAttributes();
        $reserved['client'] = true;

        return $reserved;
    }

    public function getExpiryDateTime()
    {
        return $this->expiryDateTime->toDateTime();
    }
}
