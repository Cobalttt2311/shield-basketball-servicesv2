<?php

use App\Http\Middleware\MasterCoachMiddleware;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

test('it returns 401 when user is not authenticated', function () {
    $middleware = new MasterCoachMiddleware;
    $request = Request::create('/api/criteria', 'POST');

    $request->setUserResolver(fn () => null);

    $response = $middleware->handle($request, function () {
        return new Response('should not reach here');
    });

    expect($response->getStatusCode())->toBe(401);

    $data = json_decode($response->getContent(), true);
    expect($data['success'])->toBeFalse();
});

test('it returns 403 when user is not a master coach', function () {
    $middleware = new MasterCoachMiddleware;
    $request = Request::create('/api/criteria', 'POST');

    $user = Mockery::mock(User::class);
    $user->shouldReceive('getAttribute')->with('roles')->andReturn(['coach', 'head_coach']);

    $request->setUserResolver(fn () => $user);

    $response = $middleware->handle($request, function () {
        return new Response('should not reach here');
    });

    expect($response->getStatusCode())->toBe(403);

    $data = json_decode($response->getContent(), true);
    expect($data['success'])->toBeFalse();
});

test('it passes request when user is a master coach', function () {
    $middleware = new MasterCoachMiddleware;
    $request = Request::create('/api/criteria', 'POST');

    $user = Mockery::mock(User::class);
    $user->shouldReceive('getAttribute')->with('roles')->andReturn(['coach', 'master_coach', 'head_coach']);

    $request->setUserResolver(fn () => $user);

    $response = $middleware->handle($request, function () {
        return new Response('passed');
    });

    expect($response->getStatusCode())->toBe(200);
    expect($response->getContent())->toBe('passed');
});
