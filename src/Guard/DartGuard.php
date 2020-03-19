<?php

namespace Dart\ACL\Guard;

use Dart\ACL\Exceptions\InvalidPayloadException;
use Dart\ACL\Models\DartUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class DartGuard implements Guard
{

    private $user;
    private $provider;
    private $decodedToken;
    private $requestToken;
    /**
     * @var Request
     */
    private $request;

    public function __construct(UserProvider $provider, Request $request)
    {
//        dump('__construct');
        $this->user = null;
        $provider->setModel(DartUser::class);
        $this->provider = $provider;
        $this->decodedToken = null;
        $this->request = $request;
        $this->requestToken = substr($this->request->header('Authorization'), 7);


        $this->authenticate();

    }

    /**
     * Decode token, validate and authenticate user
     *
     * @return mixed
     */

    private function authenticate()
    {
//        dump('authenticate');
        try {
            $this->decodedToken = JWT::decode($this->requestToken, env('DART_JWT_SECRET'), ['HS256']);
        } catch (\Exception $e) {
            throw new InvalidPayloadException($e->getMessage());
        }

        if ($this->decodedToken) {
            $this->validate([]);
        }
    }

    /**
     * @inheritDoc
     */
    public function check()
    {
//        dump('check');
        return !is_null($this->user());
    }

    /**
     * @inheritDoc
     */
    public function guest()
    {
//        dump('guest');
        return !$this->check();
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
//        dump('user');
        if (is_null($this->user)) {
            return null;
        }
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
//        dump('id');
        if ($user = $this->user()) {
            return $this->user()->id;
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
//        dump('validate');
        if (!$this->decodedToken) {
            return false;
        }




        $class = $this->provider->getModel();
        $user = new $class();
        foreach ($this->decodedToken->sub->user as $paramKey => $paramValue){
            $user->{$paramKey} = $paramValue;
        }

        $this->setUser($user);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setUser(Authenticatable $user)
    {
//        dump('setUser');
        $this->user = $user;

        return $this;
    }

}
