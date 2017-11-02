<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use Lcobucci\JWT\Parser;



class ReadToken
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (str_contains($request->url(), 'oauth/token'))
        {
            $data = json_decode($request->getContent());

            if (str_contains($data->grant_type, 'password'))
            {
                /** @var Response $response */
                $response = $next($request);
                $content = json_decode($response->getContent());
                $token = (new Parser())->parse((string) $content->access_token);
                $content->user = User::with('roles')->whereId($token->getClaim('sub'))->get()->first();
                $response->setContent(json_encode($content));

                return $response;
            }
        }

        return $next($request);
    }


}
