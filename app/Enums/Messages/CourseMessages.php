<?php

namespace App\Enums\Messages;

enum CourseMessages: string
{
    case CREATED            = 'Ders başarıyla eklendi!';
    case UPDATED            = 'Ders başarıyla güncellendi!';
    case DELETED            = 'Ders başarıyla silindi!';
    case NOT_FOUND          = 'Ders bulunamadı.';
    case USER_ASSIGNED      = 'Kullanıcı derse atandı!';
    case USER_REMOVED       = 'Kullanıcı dersten çıkarıldı!';
    case STUDENT_ENROLLED   = 'öğrenci derse eklendi!';
    case STUDENT_UNENROLLED = 'öğrenci dersten çıkarıldı!';
    case STUDENT_REMOVED    = 'Öğrenci dersten başarıyla çıkarıldı!';
}