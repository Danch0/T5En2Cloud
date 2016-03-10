<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(function ($request, $response, $next) {
    // add media parser
    $request->registerMediaTypeParser(
        "text/javascript",
        function ($input) {
            return json_decode($input, true);
        }
    );

    return $next($request, $response);
});

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