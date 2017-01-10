<?php

namespace Milkmeowo\Framework\Dingo\Auth\Providers;

use Dingo\Api\Auth\Provider\OAuth2 as DingoOAuth2;

class OAuth2 extends DingoOAuth2
{
    /**
     * @return \League\OAuth2\Server\ResourceServer
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return \League\OAuth2\Server\Entity\AccessTokenEntity
     */
    public function getAccessToken()
    {
        return $this->resource->getAccessToken();
    }

    /**
     * @return \League\OAuth2\Server\Entity\SessionEntity
     */
    public function getSession()
    {
        return $this->getAccessToken()->getSession();
    }

    /**
     * Get the resource owner ID of the current request.
     *
     * @return string
     */
    public function getResourceOwnerId()
    {
        return $this->getSession()->getOwnerId();
    }

    /**
     * Get the resource owner type of the current request (client or user).
     *
     * @return string
     */
    public function getResourceOwnerType()
    {
        return $this->getSession()->getOwnerType();
    }

    /**
     * Get the client of the current request.
     *
     * @return \League\OAuth2\Server\Entity\ClientEntity
     */
    public function getClient()
    {
        return $this->getSession()->getClient();
    }

    /**
     * Get the client id of the current request.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->getClient()->getId();
    }
}
