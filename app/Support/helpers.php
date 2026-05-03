<?php

use App\Support\BackendAuth;

if (! function_exists('backend_guard')) {
    function backend_guard(): ?string
    {
        return app(BackendAuth::class)->resolveGuard(request());
    }
}

if (! function_exists('backend_user')) {
    function backend_user()
    {
        return app(BackendAuth::class)->user(request());
    }
}

if (! function_exists('backend_route')) {
    function backend_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        if (! array_key_exists('ctx', $parameters) && str_starts_with($name, 'admin.')) {
            $guard = backend_guard();

            if ($guard) {
                $parameters['ctx'] = $guard;
            }
        }

        return route($name, $parameters, $absolute);
    }
}
