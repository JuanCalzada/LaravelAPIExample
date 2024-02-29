<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken()??null;
        if($token && $this->validParenthesis($token)){
            return $next($request);
        } else {
            return new JsonResponse(['message'=>'There is no token or it is invalid','status'=>403],403);
        }
    }

    private function validParenthesis(string $token): bool
    {
        $tokenChar = str_split($token);
        $arrayCheck = [];
        foreach($tokenChar as $char){
            if (!in_array($char,['(',')','[',']','{','}'])) continue;

            $success = match ($char){
                '('=>array_push($arrayCheck,0),
                ')'=>array_pop($arrayCheck) === 0,
                '['=>array_push($arrayCheck,1),
                ']'=>array_pop($arrayCheck) === 1,
                '{'=>array_push($arrayCheck,2),
                '}'=>array_pop($arrayCheck) === 2,
            };

            if (!$success) return false;
        }

        return empty($arrayCheck);
    }
}
