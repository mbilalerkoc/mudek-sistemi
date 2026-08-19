<?php

namespace App\Enums\Messages;

enum StudentMessages: string
{
    case CREATED   = 'Öğrenci başarıyla eklendi!';
    case UPDATED   = 'Öğrenci bilgileri güncellendi!';
    case DELETED   = 'Öğrenci sistemden silindi!';
    case NOT_FOUND = 'Öğrenci bulunamadı.';
    case BULK_IMPORTED = 'öğrenci sisteme eklendi!';
}