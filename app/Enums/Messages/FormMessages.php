<?php

namespace App\Enums\Messages;

enum FormMessages: string
{
    case SAVED   = 'Form başarıyla kaydedildi!';
    case UPDATED = 'Form başarıyla güncellendi!';
    case DELETED = 'Form başarıyla silindi!';
}