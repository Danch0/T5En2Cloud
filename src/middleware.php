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
    return $next($request, $response);
});