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

use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * @author hehui<hehui@eelly.net>
 */
class ClientEntity extends AbstractMysqlEntity implements ClientEntityInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Phalcon\Mvc\Model::getSource()
     */
    public function getSource()
    {
        return 'oauth_client';
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Entities\ClientEntityInterface::getIdentifier()
     */
    public function getIdentifier()
    {
        return $this->client_key;
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Entities\ClientEntityInterface::getName()
     */
    public function getName()
    {
        return $this->org_name.'/'.$this->app_name;
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\OAuth2\Server\Entities\ClientEntityInterface::getRedirectUri()
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }
}
