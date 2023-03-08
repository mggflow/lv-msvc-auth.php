# Laravel Microservice Authentication

## About
This package is using to apply AuthBase component in Laravel Microservice for request authentication.

## Usage
To install:
```
composer require mggflow/lv-msvc-auth
```

Don`t forget to add database settings to ``config/database.php``:
```
/**
 * Auth database settings
 */
'auth' => [
    'driver' => 'mysql',
    'url' => env('LV_MSVC_AUTH_DATABASE_URL', null),
    'host' => env('LV_MSVC_AUTH_DB_HOST', '127.0.0.1'),
    'port' => env('LV_MSVC_AUTH_DB_PORT', '3306'),
    'database' => env('LV_MSVC_AUTH_DB_DATABASE', 'auth'),
    'username' => env('LV_MSVC_AUTH_DB_USERNAME', 'root'),
    'password' => env('LV_MSVC_AUTH_DB_PASSWORD', ''),
    'unix_socket' => env('LV_MSVC_AUTH_DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
```

Env:
```
LV_MSVC_AUTH_TOKEN_HEADER_NAME=Auth-Access-Token
LV_MSVC_AUTH_CONNECTION_NAME=auth
LV_MSVC_AUTH_USERS_TABLE=users
LV_MSVC_AUTH_COOKIE_KEY=au

LV_MSVC_AUTH_DATABASE_URL=
LV_MSVC_AUTH_DB_HOST=127.0.0.1
LV_MSVC_AUTH_DB_PORT=3306
LV_MSVC_AUTH_DB_SOCKET=
LV_MSVC_AUTH_DB_DATABASE=auth
LV_MSVC_AUTH_DB_USERNAME=root
LV_MSVC_AUTH_DB_PASSWORD=
```

Authenticate facade usage example:
```
$password = $request->input('password', false);
$username = $request->input('username', false);
$email = $request->input('email', false);
$token = $request->header($this->authHeaderName, $request->input('token', false));
        
$this->authenticateFacade = new Authenticate();
$authenticateFacade->auth($password, $username, $email, $token);

// object|null
$currentUser = $authenticateFacade->getCurrentUser();
```

AuthRequest case usage example:
```
$requestAuthentication = new AuthRequest();
$currentUser = $requestAuthentication->auth($request)->getCurrentUser();
```

AuthenticateRequestUser trait usage example:
```
class AuthenticateRequestUserUsage
{
    use AuthenticateRequestUser;
    
    public someFunction($request){
        $this->authRequestUser($request);
        
        if(!$this->userIsAuthenticated()){
            throw new AccessDenied();
        }
        
        $user = $this->currentUser;
    }
}
```