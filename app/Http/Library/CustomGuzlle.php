<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 19.06.17
 * Time: 12:43
 */

namespace App\Http\Library;

use GuzzleHttp\Client;
use JWTAuth;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlMultiHandler;
use Psr\Http\Message\RequestInterface;


class CustomGuzlle
{
    function my_middleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                return $handler($request, $options);
            };
        };
    }

    private $client;
    public function __construct()
    {
        $handler = new CurlMultiHandler();
        $stack = HandlerStack::create($handler);
//        $stack->push($this->my_middleware());

        $this->client = new Client([
            'handler' => $stack,
            // Base URI is used with relative requests
            'base_uri' => 'http://test.loc/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
            //'verify' => false,
            //'http_errors' => false,
        ]);
    }

    public function request()
    {
        $r = $this->client->request('GET', 'posts')->getBody();
        dd(json_decode($r));
        return response()->json($r, 200);
    }

    public function postRequest()
    {
        $body['title'] = "Body Title1";
        $body['article'] = "Body Description1";


        $response = $this->client->request("POST", 'api/post/create2', [
            'auth' => ['ggg@mil.com', 'secret',],

//            'headers' => [
//                'Authorization' => 'Bearer '.JWTAuth::attempt([
//                    'email'=>'ggg@mil.com',
//                    'password'=>'secret'])],
            'form_params'=>$body
        ]);

        return response()->json([], $response->getStatusCode());
        dd($response);

        $r = $this->client->request('GET', '/posts')->getBody();
        dd(json_decode($r));
        return response()->json($r, 200);
    }
}