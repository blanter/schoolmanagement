@section('title', 'Laporan Bulanan')
<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/dashboard" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Laporan Bulanan</div>
                    <div class="header-subtitle">{{ auth()->user()->role == 'admin' ? 'Semua Laporan' : 'Status Evaluasi' }}</div>
                </div>
            </div>

            <!-- Month Navigator -->
            <div class="month-navigator-bar">
                <a href="{{ route('laporanall', ['month' => $prevMonthObj->month, 'year' => $prevMonthObj->year, 'user_id' => request('user_id')]) }}" 
                   class="month-nav-btn" style="text-decoration: none;">
                    <i class="ph-bold ph-caret-left"></i>
                </a>
                <div id="current-month-label">{{ $months[(int)$month] }} {{ $year }}</div>
                <a href="{{ route('laporanall', ['month' => $nextMonthObj->month, 'year' => $nextMonthObj->year, 'user_id' => request('user_id')]) }}" 
                   class="month-nav-btn" style="text-decoration: none;">
                    <i class="ph-bold ph-caret-right"></i>
                </a>
            </div>
        </header>

        <main class="project-main-content margin-top-25">
            <!-- Table/List -->
            <div class="task-list">
                <div style="padding: 10px 20px 20px; font-weight: 700; color: #4B5563; font-size: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <span>Daftar Evaluasi Bulanan</span>
                    <span>Total: {{ $laporans->total() }}</span>
                </div>

                @forelse($laporans as $laporan)
                    <div class="laporan-card-admin">
                        <div class="laporan-card-top">
                            <div class="laporan-user-profile">
                                <div class="laporan-user-avatar-box">
                                    <i class="ph-bold ph-user-circle"></i>
                                </div>
                                <div class="laporan-user-meta">
                                    <span class="name">{{ $laporan->user_name }}</span>
                                    <span class="period">Periode: {{ $months[$laporan->month] }} {{ $laporan->year }}</span>
                                </div>
                            </div>

                            @if(auth()->user()->role == 'admin' || auth()->id() == $laporan->user_id)
                                <div class="laporan-status-badge"
                                    style="background: {{ $laporan->point ? '#ECFDF5' : '#FEF2F2' }}; color: {{ $laporan->point ? '#059669' : '#EF4444' }};">
                                    {{ $laporan->point ? 'Skor: ' . $laporan->point : 'Belum Dinilai' }}
                                </div>
                            @else
                                <div class="laporan-status-badge" style="background: #F0F4FF; color: #4F46E5;">
                                    Terkirim
                                </div>
                            @endif
                        </div>

                        @if(auth()->user()->role == 'admin' || auth()->id() == $laporan->user_id)
                            @if($laporan->comment)
                                <div class="laporan-comment-preview">
                                    <i class="ph-bold ph-quotes"></i> "{{ Str::limit($laporan->comment, 120) }}"
                                </div>
                            @elseif(auth()->id() == $laporan->user_id)
                                <div class="laporan-comment-preview"
                                    style="border-left-color: #F3F4F6; color: #9CA3AF; font-style: italic;">
                                    Belum ada komentar atau feedback dari admin.
                                </div>
                            @endif
                        @endif

                        <div class="laporan-card-actions-grid" style="{{ auth()->user()->role != 'admin' ? 'grid-template-columns: 1fr;' : '' }}">
                            <a href="/my-tasks/{{ $laporan->user_id }}?month={{ $laporan->month }}&year={{ $laporan->year }}"
                                class="btn-teacher-project btn-admin-v2 btn-admin-primary">
                                <i class="ph-bold ph-eye"></i> Lihat Laporan
                            </a>
                            @if(auth()->user()->role == 'admin')
                                <a href="/nilai-laporan/{{ $laporan->user_id }}?month={{ $laporan->month }}&year={{ $laporan->year }}"
                                    class="btn-teacher-project btn-admin-v2 btn-admin-success">
                                    <i class="ph-bold ph-star"></i> Beri Nilai
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="project-empty-state-placeholder"
                        style="background: #fff; border-radius: 20px; padding: 60px 20px;">
                        <i class="ph-bold ph-article project-empty-state-icon"
                            style="font-size: 64px; color: #E5E7EB; margin-bottom: 20px;"></i>
                        <p class="project-empty-state-text" style="color: #9CA3AF; font-weight: 600;">Tidak ada data laporan
                            evaluasi untuk periode ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($laporans->hasPages())
                <div style="margin-top: 30px; margin-bottom: 50px;">
                    {{ $laporans->appends(request()->query())->links() }}
                </div>
            @endif
        </main>
    </div>
</x-app-layout>