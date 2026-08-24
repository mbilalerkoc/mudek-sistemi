@php
    $isAdmin = auth()->user()->role === 'super_admin';
    $updateRoute = $isAdmin ? 'admin.sinavlar.guncelle' : 'user.sinavlar.guncelle';
    $sorularRoute = $isAdmin ? 'admin.sinavlar.detay' : 'user.sinavlar.detay';
    $cevaplarRoute = $isAdmin ? 'admin.sinavlar.cevaplar' : 'user.sinavlar.cevaplar';
@endphp

<section class="section">
    @foreach (['midterm' => 'Vize', 'final' => 'Final', 'makeup' => 'Bütünleme'] as $type => $label)
        @php
            $exam = $course->exams->where('exam_type', $type)->first();
        @endphp

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">{{ $label }} Sınavı</h4>
                @if ($exam)
                    <div class="d-flex gap-2">
                        <a href="{{ route($sorularRoute, $exam->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-list-ol me-1"></i> Sorular
                        </a>
                        <a href="{{ route($cevaplarRoute, $exam->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-check2-square me-1"></i> Cevaplar
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse"
                            data-bs-target="#sinav-form-{{ $type }}">
                            <i class="bi bi-pencil me-1"></i> Düzenle
                        </button>
                    </div>
                @endif
            </div>

            <div class="card-body">
                @if ($exam)
                    <div class="row mb-3 g-3">
                        <div class="col-12 col-md-3">
                            <small class="text-muted d-block mb-1">Sınav Tarihi</small>
                            <span class="fw-bold">
                                {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('d.m.Y') : 'Henüz girilmedi' }}
                            </span>
                        </div>

                        <div class="col-12 col-md-3">
                            <small class="text-muted d-block mb-1">Etki Oranları</small>
                            <span class="badge bg-ktun-soft">
                                Sınav: %{{ $exam->weight ?? 80 }} · Ödev: %{{ 100 - ($exam->weight ?? 80) }}
                            </span>
                        </div>

                        <div class="col-12 col-md-3">
                            <small class="text-muted d-block mb-1">Puanlama Türü</small>
                            <span class="badge bg-ktun-soft">
                                @if (($exam->grading_type ?? 'weighted') === 'raw_sum')
                                    Ham Puan Toplama
                                @else
                                    Yüzdelik Ağırlıklı
                                @endif
                            </span>
                        </div>

                        <div class="col-12 col-md-3">
                            <small class="text-muted d-block mb-1">Soru Kağıdı</small>
                            @if ($exam->question_paper_path)
                                <a href="{{ asset('storage/' . $exam->question_paper_path) }}" target="_blank"
                                   class="ktun-text-link">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> İndir
                                </a>
                            @else
                                <span class="text-muted">Yüklenmedi</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">Cevap Anahtarı</small>
                            @if ($exam->answers_paper_path)
                                <a href="{{ asset('storage/' . $exam->answers_paper_path) }}" target="_blank"
                                   class="ktun-text-link">
                                    <i class="bi bi-file-earmark-check me-1"></i> İndir
                                </a>
                            @else
                                <span class="text-muted">Yüklenmedi</span>
                            @endif
                        </div>
                    </div>

                    <div class="collapse" id="sinav-form-{{ $type }}">
                        <hr>
                        <form action="{{ route($updateRoute, $exam->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Sınav Etki Oranı / Ağırlığı (%)</label>
                                    <input type="number" name="weight" class="form-control"
                                        value="{{ old('weight', $exam->weight ?? 80) }}" min="0" max="100"
                                        required>
                                    <small class="text-muted d-block mt-1">Örn: 80 (%80 Sınav, %20 Ödev)</small>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Puanlama / Hesaplama Türü</label>
                                    <select name="grading_type" class="form-select" required>
                                        <option value="weighted"
                                            {{ ($exam->grading_type ?? 'weighted') === 'weighted' ? 'selected' : '' }}>
                                            Yüzdelik Ağırlıklı (Örn: %80 Sınav, %20 Ödev)
                                        </option>
                                        <option value="raw_sum"
                                            {{ ($exam->grading_type ?? '') === 'raw_sum' ? 'selected' : '' }}>
                                            Doğrudan Ham Puan Toplama (Örn: 80'lik Sınav + 20'lik Ödev)
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Sınav Tarihi</label>
                                    <input type="date" name="exam_date" class="form-control"
                                        value="{{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Soru Kağıdı</label>
                                    <input type="file" name="question_paper_path"
                                        class="form-control file-size-check" accept=".pdf,.doc,.docx" data-max-size="10"
                                        data-warning-id="sinav-dosya-uyari-{{ $type }}-soru">
                                    <div id="sinav-dosya-uyari-{{ $type }}-soru"
                                        class="alert alert-danger mt-1 d-none">
                                        Dosya 10MB'dan büyük olamaz.
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">Cevap Anahtarı</label>
                                    <input type="file" name="answers_paper_path" class="form-control file-size-check"
                                        accept=".pdf,.doc,.docx" data-max-size="10"
                                        data-warning-id="sinav-dosya-uyari-{{ $type }}-cevap">
                                    <div id="sinav-dosya-uyari-{{ $type }}-cevap"
                                        class="alert alert-danger mt-1 d-none">
                                        Dosya 10MB'dan büyük olamaz.
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-toggle="collapse"
                                        data-bs-target="#sinav-form-{{ $type }}">
                                        İptal
                                    </button>
                                    <button type="submit" class="btn btn-primary-light px-4">
                                        <i class="bi bi-check-lg me-1"></i> Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    @if ($type === 'makeup')
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Bütünleme sınavı henüz oluşturulmamış. Final sonuçlarına göre admin tarafından eklenecek.
                        </p>
                    @else
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Sınav henüz tanımlanmamış.
                        </p>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
</section>