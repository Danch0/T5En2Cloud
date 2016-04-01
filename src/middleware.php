<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(function ($request, $response, $next) {
    // add media parser
    $request->registerMediaTypeParser(
        "json",
        function ($input) {
            return json_decode($input, true);
        }
    );
    return $next($request, $response);
});

$app->add(function ($request, $response, $next) {
    // add media parser
    $request->registerMediaTypeParser(
        "application/json",
        function ($input) {
            return json_decode($input, true);
        }
    );
    $newResp = $response->withHeader('Content-type', 'application/json');
    // $newResp = $newResp->withAddedHeader('Access-Control-Allow-Origin', '*');
    $newResp = $newResp->withAddedHeader("Access-Control-Allow-Origin", "*");
    $newResp = $newResp->withAddedHeader("Access-Control-Allow-Headers", "origin, x-requested-with, content-type");
    $newResp = $newResp->withAddedHeader("Access-Control-Allow-Methods", "PUT, GET, POST, DELETE, OPTIONS");
    return $next($request, $newResp);
});

$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

$app->add(function ($request, $response, $next) {
    // echo $request->getHeader('PHP_AUTH_USER')[0].$request->getHeader('PHP_AUTH_PW')[0];
    $ip_address = $request->getServerParams()['REMOTE_ADDR'];
    $myheaders = $request->getHeaders();
    $resourceUri = explode("/",$request->getUri());
    if($resourceUri[count($resourceUri)-1] != "login") {
        if(array_key_exists('PHP_AUTH_USER', $myheaders) && array_key_exists('PHP_AUTH_PW', $myheaders)){
          $db = new MyPDO("token_ma","token_ma",$this->db);
          $myheaders['ip_address'] = $ip_address;
          $token = $db->isTokenValid($myheaders);
          // if($request->getHeader('PHP_AUTH_USER')[0].$request->getHeader('PHP_AUTH_PW')[0] == "T53NTUCL0UDTRU350LUT10N5")
          if($token['estado'])
              return $next($request, $response);
          else {
              $res = $response
               ->withStatus(404)
               ->withHeader('Content-Type', 'text/html')
               ->write($token['mensaje']);
               return $res;
          }
        }
        else {
            $res = $response
             ->withStatus(404)
             ->withHeader('Content-Type', 'text/html')
             ->write('Page not found');
             return $res;
        }
    }else
      return $next($request, $response);
});