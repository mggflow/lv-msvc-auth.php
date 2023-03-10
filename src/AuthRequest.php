<?php

namespace MGGFLOW\MsvcAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MGGFLOW\ExceptionManager\Interfaces\UniException;
use MGGFLOW\ExceptionManager\ManageException;
use MGGFLOW\MsvcAuth\Facades\Authenticate;

class AuthRequest
{

    public Authenticate $authenticateFacade;
    protected string $authHeaderName;

    public function __construct()
    {
        $this->authHeaderName = env('LV_MSVC_AUTH_TOKEN_HEADER_NAME', 'Auth-Access-Token');

        $this->authenticateFacade = new Authenticate();
    }

    /**
     * @throws UniException
     */
    public function auth(Request $request): Authenticate
    {
        $this->validate($request);

        $password = $request->input('password', false);
        $username = $request->input('username', false);
        $email = $request->input('email', false);
        $token = $request->header($this->authHeaderName, $request->input('token', false));

        $this->authenticateFacade->auth($password, $username, $email, $token);

        return $this->authenticateFacade;
    }

    /**
     * @throws UniException
     */
    protected function validate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => ['alpha_num', 'min:4', 'max:32'],
                'username' => ['alpha_num', 'min:4', 'max:64'],
                'email' => ['email', 'max:128'],
                'token' => ['alpha_num', 'min:32', 'max:128'],
            ]
        );

        if ($validator->fails()) {
            throw ManageException::build()
                ->log()->info()->b()
                ->desc()->areInvalid('Authentication Data')->b()
                ->fill();
        }
    }
}
