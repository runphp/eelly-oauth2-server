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

use Eelly\OAuth2\Server\Entities\Traits\RevokedTrait;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use MongoDB\BSON\UTCDateTime;

class RefreshTokenEntity extends AbstractMongoEntity implements RefreshTokenEntityInterface
{
    use RefreshTokenTrait, EntityTrait, RevokedTrait;

    public function initialize(): void
    {
        $this->useImplicitObjectIds(true);
        $this->selectDb('oauth');
    }

    public function beforeSave(): void
    {
        if (!$this->expiryDateTime instanceof UTCDateTime) {
            $this->expiryDateTime = new UTCDateTime(floor($this->expiryDateTime->format('U.u') * 1000));
        }
    }

    public function getExpiryDateTime()
    {
        return $this->expiryDateTime->toDateTime();
    }
}
