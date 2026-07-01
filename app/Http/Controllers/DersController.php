<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class DersController extends Controller
{
    private function getStatikDersler()
    {
        return [
            (object) [
                'id' => 1, 
                'ders_kodu' => 'BIL301', 
                'ders_adi' => 'Veri Tabanı Yönetim Sistemleri', 
                'mevcut_katki' => 4,
                'toplam_form' => 17, 
                'doldurulan_form' => 10, 
                'yuzde' => 58
            ],
            (object) [
                'id' => 2, 
                'ders_kodu' => 'BIL402', 
                'ders_adi' => 'Yazılım Mühendisliği', 
                'mevcut_katki' => null,
                'toplam_form' => 5, 
                'doldurulan_form' => 0, 
                'yuzde' => 0
            ],
            (object) [
                'id' => 3, 
                'ders_kodu' => 'BIL205', 
                'ders_adi' => 'Web Programlama', 
                'mevcut_katki' => null,
                'toplam_form' => 8, 
                'doldurulan_form' => 8, 
                'yuzde' => 100
            ]
        ];
    }

    public function index()
    {
        $dersler = $this->getStatikDersler();
        
        return view('user.dersler', compact('dersler')); 
    }

    public function katkilariniKaydet(Request $request)
    {
        $request->validate([
            'katkilar'   => 'required|array',
            'katkilar.*' => 'nullable|integer|min:1|max:5',
        ], [
            'katkilar.*.min' => 'Katkı puanı en az 1 olmalıdır.',
            'katkilar.*.max' => 'Katkı puanı en fazla 5 olmalıdır.'
        ]);

        return redirect()->back()->with('success', 'Form statik olarak başarıyla test edildi! (Veriler geçici olarak kabul edildi)');
    }

    public function formGoster($ders_id, $form_id)
    {
        $seciliDers = collect($this->getStatikDersler())->firstWhere('id', (int)$ders_id);

        if (!$seciliDers) {
            abort(404, 'Ders bulunamadı'); 
        }

        // 'dersler' kısmını compact içinden sildik, sadece var olanları gönderiyoruz
        return view('user.ders_form_sayfasi', compact('seciliDers', 'form_id'));
    }
public function dersDetay($id)
    {
        // Tıklanan dersi bul
        $ders = collect($this->getStatikDersler())->firstWhere('id', (int)$id);

        if (!$ders) {
            abort(404, 'Ders bulunamadı');
        }

        // Bulunan dersi view'a gönder
        return view('user.ders-detay', compact('ders'));
    }
}