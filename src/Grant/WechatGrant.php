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

namespace Shadon\OAuth2\Server\Grant;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class WechatGrant extends PasswordGrant
{
    /**
     * {@inheritdoc}
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    ) {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'wechat';
    }

    /**
     * @param ServerRequestInterface $request
     * @param ClientEntityInterface  $client
     *
     * @throws OAuthServerException
     *
     * @return UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $code = $this->getRequestParameter('code', $request);
        if (null === $code) {
            throw OAuthServerException::invalidRequest('code');
        }
        $parameters = $this->validateParameters(['encryptedData', 'iv', 'rawData', 'signature'], $request);
        if (empty($parameters)) {
            $user = $this->userRepository->getUserEntityByWechatCode($client->getIdentifier(), $code);
        } else {
            $user = $this->userRepository->getUserEntityByWechatJscode(
                $client->getIdentifier(),
                $code,
                $parameters['encryptedData'],
                $parameters['iv'],
                $parameters['rawData'],
                $parameters['signature']
            );
        }
        if (false === $user instanceof UserEntityInterface) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    /**
     * @param array                  $parameters
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function validateParameters(array $parameters, ServerRequestInterface $request)
    {
        $return = [];
        $prev = $this->getRequestParameter($parameters[0], $request);
        foreach ($parameters as $key => $parameter) {
            $value = $this->getRequestParameter($parameter, $request);
            if (null != $prev && null === $value) {
                throw OAuthServerException::invalidRequest($parameter);
            }
            if (null != $value) {
                if (null === $prev) {
                    throw OAuthServerException::invalidRequest($parameters[$key - 1]);
                }
                $return[$parameter] = $value;
            }
            $prev = $value;
        }

        return $return;
    }
}
