@section('title', 'Nilai Laporan Bulanan ' . $users->name)
<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="{{ route('laporanall', ['month' => $month, 'year' => $year]) }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Penilaian Laporan</div>
                    <div class="header-subtitle">{{ $users->name }}</div>
                </div>
            </div>
        </header>

        <main class="project-main-content">
            <div class="eval-details-container">
                <!-- Header Banner -->
                <div class="project-period-banner admin-period-banner">
                    <div class="laporan-banner-flex">
                        <div>
                            <div class="project-banner-title">Halaman Penilaian Laporan</div>
                            <div class="project-banner-subtitle">Periode: {{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }}</div>
                        </div>
                        <a href="/teacher-planner/{{ $users->id }}?month={{ $month }}&year={{ $year }}" target="_blank" class="btn-teacher-project btn-admin-action" style="background: #7D52DE; min-width: 180px;">
                            <i class="ph-bold ph-arrow-square-out"></i> Lihat Laporan Guru
                        </a>
                    </div>
                </div>

                <!-- Scoring Form Section -->
                <div class="scoring-section scoring-section-clean">
                    <div class="project-info-card project-info-card-left">
                        <h3 class="scoring-title-box">
                            <i class="ph-bold ph-star" style="color: #F59E0B;"></i> Beri Nilai Laporan
                        </h3>

                        <form method="POST" action="/nilai-laporan/{{ $users->id }}">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">

                            <div class="form-field">
                                <label class="project-label-premium">Skor (1 - 100)</label>
                                <input type="number" name="point" class="project-input project-input-premium"
                                    value="{{ $laporans->point ?? '' }}" required min="1" max="100" placeholder="0-100">
                            </div>

                            <div class="form-field" style="margin-top: 20px;">
                                <label class="project-label-premium">Komentar / Feedback</label>
                                <textarea name="comment" class="project-input project-input-premium"
                                    style="height: 120px; resize: none;"
                                    placeholder="Berikan saran atau apresiasi...">{{ $laporans->comment ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn-teacher-project"
                                style="margin-top: 25px; width: 100%; justify-content: center; padding: 14px;">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Penilaian
                            </button>
                        </form>

                        <div class="scoring-note-box">
                            <p class="scoring-note-text">
                                <i class="ph ph-info" style="vertical-align: middle; margin-right: 4px;"></i>
                                Nilai ini akan muncul pada dashboard guru sebagai apresiasi atas kinerja bulanan mereka.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>