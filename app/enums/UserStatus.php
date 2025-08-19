<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'ACTIVO';
    case DELETED = 'ELIMINADO';
    case BLOCKED = 'bloqueado';
}
