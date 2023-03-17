<?php

namespace MGGFLOW\MsvcAuth\Facades;

use MGGFLOW\AuthBase\Exceptions\AuthBaseException;
use MGGFLOW\ExceptionManager\Interfaces\UniException;
use MGGFLOW\ExceptionManager\ManageException;
use MGGFLOW\MsvcAuth\Implementations\AuthByCookieD;
use MGGFLOW\MsvcAuth\Implementations\AuthByPasswordD;
use MGGFLOW\MsvcAuth\Implementations\AuthByTokenD;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use MGGFLOW\AuthBase\AuthByCookie;
use MGGFLOW\AuthBase\AuthByPassword;
use MGGFLOW\AuthBase\AuthByToken;
use MGGFLOW\AuthBase\Interfaces\Authenticator;

class Authenticate
{
    protected Authenticator $authenticator;
    public string $cookieKey;

    protected ConnectionInterface $connection;
    protected string $usersTable;
    protected string $connectionName;

    public function __construct()
    {
        $this->connectionName = config('msvc.auth.connection_name', 'auth');
        $this->usersTable = config('msvc.auth.users_table', 'users');
        $this->cookieKey = config('msvc.auth.cookie_key', 'au');

        $this->connection = DB::connection($this->connectionName);
    }

    /**
     * @throws UniException
     */
    public function auth($password = false, $username = false, $email = false, $token = false): self
    {
        $this->makeAuthenticator(
            $password, $username, $email,
            $token,
            Cookie::get($this->cookieKey, false)
        );

        try {
            $this->authenticator->auth();
        } catch (AuthBaseException $exception) {
            throw ManageException::build()->e()->import($exception);
        }


        return $this;
    }

    public function getCurrentUser(): ?object
    {
        $user = $this->authenticator->getCurrentUser();

        if ($user !== null) {
            $user = clone $user;
            unset($user->pwd_hash);
            unset($user->verification_code);
        }

        return $user;
    }

    /**
     * @throws UniException
     */
    protected function makeAuthenticator($password, $username, $email, $token, $cookie): void
    {
        if (!empty($token)) {
            $this->authenticator = new AuthByToken(
                new AuthByTokenD($this->connection, $this->usersTable)
            );
            $this->authenticator->setToken($token);
        } elseif (!empty($cookie)) {
            $this->authenticator = new AuthByCookie(
                new AuthByCookieD($this->connection, $this->usersTable)
            );
            $this->authenticator->setCookie($cookie);
        } elseif (!empty($password) and (!empty($username) or !empty($email))) {
            $this->authenticator = new AuthByPassword(
                new AuthByPasswordD($this->connection, $this->usersTable)
            );
            $this->authenticator->setPassword($password);
            $this->authenticator->setEmail($email);
            $this->authenticator->setUsername($username);
        } else {
            throw ManageException::build()
                ->log()->info()->b()
                ->desc()->areEmpty('Auth Credentials')->b()
                ->fill();
        }
    }
}
