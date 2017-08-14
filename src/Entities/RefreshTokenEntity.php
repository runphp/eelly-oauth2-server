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

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

class RefreshTokenEntity extends AbstractMongoEntity implements RefreshTokenEntityInterface
{
    use RefreshTokenTrait, EntityTrait;

    public function initialize(): void
    {
        $this->useImplicitObjectIds(true);
        $this->selectDb('oauth');
    }

    public function beforeSave(): void
    {
        $this->expiryDateTime = new \MongoDB\BSON\UTCDateTime(floor($this->expiryDateTime->format('U.u') * 1000));
    }

    public function getExpiryDateTime()
    {
        return $this->expiryDateTime->toDateTime();
    }
}
