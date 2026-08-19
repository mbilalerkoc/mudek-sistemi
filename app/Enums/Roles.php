<?php

namespace App\Enums;

enum Roles: string
{
    case SUPER_ADMIN = 'super_admin';
    case USER        = 'user';
    case STUDENT     = 'student';
}