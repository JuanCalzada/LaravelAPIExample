<?php

namespace App\Http\Controllers;

use App\Http\Requests\URLShorterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class URLShorterController extends Controller
{
    public function __invoke(URLShorterRequest $request): JsonResponse
    {
        $inputUrl = $request->get('url');
        if (filter_var($inputUrl, FILTER_VALIDATE_URL)){
            return  new JsonResponse(['message'=>'Input URL is not valid','status'=>400],400);
        }
        $tiny = Http::get('https://tinyurl.com/api-create.php',['url'=>$inputUrl]);
        return  new JsonResponse(['url'=>$tiny->body()],$tiny->status());
    }
}
