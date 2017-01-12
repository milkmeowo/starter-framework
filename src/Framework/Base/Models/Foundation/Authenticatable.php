<?php

namespace Milkmeowo\Framework\Base\Models\Foundation;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Laravel\Passport\HasApiTokens;
use Milkmeowo\Framework\Base\Traits\Auth\Authorizable as AuthorizableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Milkmeowo\Framework\Base\Models\BaseModel;

class Authenticatable extends BaseModel implements
    AuthorizableContract,
    AuthenticatableContract,
    CanResetPasswordContract
{
    use AuthorizableTrait, AuthenticatableTrait, CanResetPasswordTrait;
    use HasApiTokens;
}
