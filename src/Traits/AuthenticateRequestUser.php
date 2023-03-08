<?php

namespace MGGFLOW\MsvcAuth\Traits;

use MGGFLOW\ExceptionManager\Interfaces\UniException;
use MGGFLOW\MsvcAuth\AuthRequest;

trait AuthenticateRequestUser
{
    protected AuthRequest $requestAuthentication;
    protected ?object $currentUser;

    protected function authRequestUser($request)
    {
        try {
            $this->requestAuthentication = new AuthRequest();
            $this->currentUser = $this->requestAuthentication->auth($request)->getCurrentUser();
        } catch (UniException $exception) {
            $this->currentUser = (object)['id' => 0];
        }
    }

    protected function userIsAuthenticated(): bool
    {
        return !empty($this->currentUser->id);
    }
}

