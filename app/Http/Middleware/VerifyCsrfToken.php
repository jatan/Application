<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //TODO: Resolve CSRFTokenError
        'user/account/create',
        'user/account/getbyId/*',
        'user/account/hide_toggle/*',
        'user/account/sync/*',
		'user/account/syncAll/*',
        'user/budget/create',
        'user/budget/update',
	    'user/budget/delete'
    ];

}
