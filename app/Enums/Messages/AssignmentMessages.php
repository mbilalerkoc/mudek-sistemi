<?php

namespace App\Enums\Messages;

enum AssignmentMessages: string
{
    case CREATED = 'Ödev başarıyla oluşturuldu.';
    case UPDATED = 'Ödev bilgileri güncellendi.';
    case DELETED = 'Ödev başarıyla silindi.';
    case ERROR = 'İşlem sırasında bir hata oluştu.';
    case SUBMISSION_SAVED  = 'Teslim bilgileri başarıyla kaydedildi.';
    case SUBMISSION_UPDATED = 'Teslim bilgileri başarıyla güncellendi.';
}