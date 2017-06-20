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
