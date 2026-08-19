<?php

namespace App\Enums;

enum ExamTypes: string
{
    case MIDTERM = 'vize';
    case FINAL   = 'final';
    case MAKEUP  = 'butunleme';
}