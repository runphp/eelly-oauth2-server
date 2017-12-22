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

namespace Shadon\OAuth2\Server\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Shadon\OAuth2\Server\Entities\ScopeEntity;

class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Repositories\ScopeRepositoryInterface::getScopeEntityByIdentifier()
     */
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        $scopes = [
            'basic' => [
                'description' => 'Basic details about you',
            ],
            'email' => [
                'description' => 'Your email address',
            ],
        ];
        if (false === array_key_exists($scopeIdentifier, $scopes)) {
            return;
        }
        $scope = new ScopeEntity();
        $scope->setIdentifier($scopeIdentifier);

        return $scope;
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Repositories\ScopeRepositoryInterface::finalizeScopes()
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        if (1 === (int) $userIdentifier) {
            $scope = new ScopeEntity();
            $scope->setIdentifier('email');
            $scopes[] = $scope;
        }

        return $scopes;
    }
}
