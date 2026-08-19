<?php

namespace App\Enums\Messages;

enum ExamMessages: string
{
    case CREATED       = 'Sınav başarıyla oluşturuldu!';
    case UPDATED       = 'Sınav başarıyla güncellendi!';
    case DELETED       = 'Sınav başarıyla silindi!';
    case NOT_FOUND     = 'Sınav bulunamadı.';
    case GRADES_SAVED   = 'Notlar başarıyla kaydedildi!';
    case GRADES_UPDATED = 'Notlar başarıyla güncellendi!';
}