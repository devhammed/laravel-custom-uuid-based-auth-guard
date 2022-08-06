<?php

namespace App\Providers;

use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('api-token', function (Request $request) {
            $authorizationHeader = $request->header('Authorization');

            if (!\is_string($authorizationHeader) || empty($authorizationHeader)) {
                return \null;
            }

            $authorizationHeaderParts = \explode(' ', $authorizationHeader);

            if (\count($authorizationHeaderParts) !== 2) {
                return \null;
            }

            $tokenType = $authorizationHeaderParts[0];

            if ($tokenType !== 'Bearer') {
                return \null;
            }

            $tokenValue = \hash('sha256', $authorizationHeaderParts[1]);

            $apiToken = ApiToken::whereValue($tokenValue)->first();

            if ($apiToken === null) {
                return \null;
            }

            if (\now()->diffInSeconds($apiToken->created_at) >= \config('auth.api_token_timeout')) {
                $apiToken->delete();

                return \null;
            }

            return $apiToken->user;
        });
    }
}
