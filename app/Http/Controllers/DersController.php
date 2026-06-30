<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DersController extends Controller
{
    public function index()
    {

        $dersler = [
            (object) [
                'id' => 1, 
                'ders_adi' => 'Veri Tabanı Yönetim Sistemleri', 
                'mevcut_katki' => 4
            ],
            (object) [
                'id' => 2, 
                'ders_adi' => 'Yazılım Mühendisliği', 
                'mevcut_katki' => null
            ],
            (object) [
                'id' => 3, 
                'ders_adi' => 'Web Programlama', 
                'mevcut_katki' => null
            ]
        ];
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
}