<?php

namespace App\Support;

use App\Enums\RoleName;

final class Role
{
    public const ADMIN = RoleName::Admin->value;
    public const STUDENT = RoleName::Student->value;
    public const PARENT = RoleName::Parent->value;
}
