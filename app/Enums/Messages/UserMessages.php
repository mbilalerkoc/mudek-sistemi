<?php

namespace App\Enums\Messages;

enum UserMessages: string
{
    case CREATED      = 'Kullanıcı başarıyla eklendi!';
    case UPDATED      = 'Kullanıcı başarıyla güncellendi!';
    case DELETED      = 'Kullanıcı başarıyla silindi!';
    case NOT_FOUND    = 'Kullanıcı bulunamadı.';
    case UNAUTHORIZED = 'Bu işlem için yetkiniz yok.';
    case PASSWORD_UPDATED = 'Şifreniz başarıyla güncellendi! Yeni şifrenizle giriş yapabilirsiniz.';
    case LOGIN_FAILED = 'Girdiğiniz e-posta veya şifre hatalı.';
    case PROFILE_UPDATED  = 'Profil bilgileriniz başarıyla güncellendi!';
}