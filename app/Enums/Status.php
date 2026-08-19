<?php

namespace App\Enums;

enum Status: string
{
    case PASSED  = 'passed';
    case FAILED  = 'failed';
    case ONGOING = 'ongoing';
}   