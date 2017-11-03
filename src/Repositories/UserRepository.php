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

namespace Eelly\OAuth2\Server\Repositories;

use Eelly\OAuth2\Server\Entities\UserEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * Class UserRepository.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var \Eelly\SDK\User\Api\User
     */
    private $user;

    /**
     * UserRepository constructor.
     */
    public function __construct(\Eelly\SDK\User\Api\User $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        $user = $this->user->getUserByPassword($username, $password);
        $entity = new UserEntity();
        $entity->setIdentifier($user->uid);

        return $entity;
    }

    /**
     * @param $uid
     *
     * @return UserEntity
     */
    public function getUserEntityByUid($uid)
    {
        $user = $this->user->getUser($uid);
        $entity = new UserEntity();
        $entity->setIdentifier($user->uid);

        return $entity;
    }
}
