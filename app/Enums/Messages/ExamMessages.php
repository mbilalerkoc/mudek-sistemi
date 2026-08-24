<?php

namespace App\Enums\Messages;

enum ExamMessages: string
{
    // Sınav CRUD
    case CREATED              = 'Sınav başarıyla oluşturuldu!';
    case UPDATED               = 'Sınav başarıyla güncellendi!';
    case DELETED                = 'Sınav başarıyla silindi!';
    case NOT_FOUND              = 'Sınav bulunamadı.';
    case EXAM_UPDATE_FAILED     = 'Sınav güncellenirken bir hata oluştu.';

    // Sorular
    case QUESTION_ADDED         = 'Soru başarıyla eklendi!';
    case QUESTION_DELETED       = 'Soru başarıyla silindi!';
    case QUESTION_STORE_FAILED  = 'Soru eklenirken bir hata oluştu.';

    // Notlar / Cevaplar
    case GRADES_SAVED           = 'Notlar başarıyla kaydedildi!';
    case GRADES_UPDATED         = 'Notlar başarıyla güncellendi!';
    case GRADES_SAVE_FAILED     = 'Cevaplar kaydedilirken bir hata oluştu.';

    // Excel içe aktarma
    case EXCEL_IMPORTED         = "Excel başarıyla içe aktarıldı ve notlar hesaplandı!";
    case EXCEL_IMPORT_FAILED    = 'Excel içe aktarılırken bir hata oluştu.';

    // Örnek sınav kağıtları
    case SAMPLE_PAPERS_SAVED    = 'Örnek sınav kağıtları başarıyla kaydedildi!';
    case SAMPLE_PAPERS_FAILED   = 'Örnek kağıtlar kaydedilirken bir hata oluştu.';

    // Genel
    case ERROR_OCCURRED         = 'Bir hata oluştu, lütfen tekrar deneyin.';
    case RAW_SUM_WARNING        = 'Uyarı: Ödev puanları toplamı, sınav ağırlığıyla (100 - sınav ağırlığı) uyuşmuyor. Kontrol ediniz.';
}