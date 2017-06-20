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
