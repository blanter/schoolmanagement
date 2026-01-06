<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section matching Teacher Planner theme but with Project features -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/my-tasks/{{ $user->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Teacher Project</div>
                    <div class="header-subtitle">{{ $user->name }}</div>
                </div>
            </div>
        </header>

        <main class="project-main-content">
            <!-- Tabs -->
            <div class="tabs-wrapper">
                <button class="tab-trigger active" data-tab="penelitian">Karya Penelitian</button>
                <button class="tab-trigger" data-tab="video">Karya Video / DIY</button>
                <button class="tab-trigger" data-tab="barang">Pengadaan Barang</button>
            </div>

            <!-- Tab Content: Penelitian -->
            <div id="content-penelitian" class="tab-content-panel">
                <div class="project-period-banner">
                    <div class="project-banner-title">Periode Penelitian</div>
                    <div class="project-banner-subtitle">
                        @if($semester == 1)
                            Juli - Desember {{ $baseYear }} (Semester 1)
                        @else
                            Januari - Juni {{ $baseYear + 1 }} (Semester 2)
                        @endif
                    </div>
                </div>

                <div class="task-list">
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #FEB2D3;">
                            <i class="ph-bold ph-book-open"></i>
                        </div>
                        <div class="project-task-label">Judul Pendahuluan</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->judul_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'judul_check')" @endif>
                            {!! $project->judul_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #FFE7A0;">
                            <i class="ph-bold ph-file-text"></i>
                        </div>
                        <div class="project-task-label">Rumusan Masalah</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->rumusan_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'rumusan_check')" @endif>
                            {!! $project->rumusan_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #A0C4FF;">
                            <i class="ph-bold ph-microscope"></i>
                        </div>
                        <div class="project-task-label">Penelitian</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->penelitian_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'penelitian_check')" @endif>
                            {!! $project->penelitian_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #B9FBC0;">
                            <i class="ph-bold ph-check-square"></i>
                        </div>
                        <div class="project-task-label">Kesimpulan</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->kesimpulan_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'kesimpulan_check')" @endif>
                            {!! $project->kesimpulan_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                </div>

                <div class="project-info-card">
                    <label class="project-label-premium">
                        <i class="ph-bold ph-link"></i>
                        Lembar Link Penelitian
                    </label>
                    <input type="text" id="research_link" class="project-input project-input-premium"
                        placeholder="Masukkan link google drive / dokumen penelitian..."
                        value="{{ $project->research_link }}"
                        @if(auth()->id() == $user->id) onchange="saveResearchField('research_link', this.value)" @endif
                        @if(auth()->id() != $user->id) disabled @endif>
                    <p class="project-footer-info">
                        <i class="ph ph-info"></i> Link akan tersimpan secara otomatis setelah Anda selesai mengetik.
                    </p>
                </div>
            </div>

            <!-- Tab Content: Video -->
            <div id="content-video" class="tab-content-panel" style="display: none;">
                <div class="project-period-banner video">
                    <div class="project-banner-title">Periode Karya Video / DIY</div>
                    <div class="project-banner-subtitle">
                        @if ($semester == 1)
                            Juli - Desember {{ $baseYear }} (Semester 1)
                        @else
                            Januari - Juni {{ $baseYear + 1 }} (Semester 2)
                        @endif
                    </div>
                </div>

                @if (auth()->id() == $user->id)
                    <div class="project-form-card project-info-card video-form">
                        <div class="form-field">
                            <label class="project-label-premium">Nama Karya Video / DIY</label>
                            <input type="text" id="video_name" class="project-input project-input-premium" placeholder="Masukkan nama karya...">
                        </div>
                        <div class="form-field" style="margin-top: 15px;">
                            <label class="project-label-premium">Link Karya (YouTube/Drive)</label>
                            <input type="text" id="video_link" class="project-input project-input-premium" placeholder="https://youtube.com/...">
                        </div>
                        <div class="project-actions" style="margin-top: 20px; justify-content: flex-end;">
                            <button onclick="saveVideoProject()" class="btn-teacher-project" style="width: auto; padding: 10px 25px;">
                                <i class="ph-bold ph-plus"></i> Tambah Karya
                            </button>
                        </div>
                    </div>
                @endif

                <div id="video-list-container" class="task-list">
                    @forelse ($videoProjects as $video)
                        <div class="task-item project-task-item project-video-item-card" id="video-item-{{ $video->id }}">
                            <div class="project-video-item-header">
                                <div class="project-video-title-wrapper">
                                    <div class="project-video-icon-box">
                                        <i class="ph-bold ph-video-camera"></i>
                                    </div>
                                    <div class="project-video-name-text">{{ $video->name }}</div>
                                </div>
                                @if (auth()->id() == $user->id)
                                    <button onclick="deleteVideo({{ $video->id }})" class="project-delete-trigger">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                @endif
                            </div>
                            @if ($video->link)
                                <a href="{{ $video->link }}" target="_blank" class="project-video-link-anchor">
                                    <i class="ph ph-link"></i> {{ Str::limit($video->link, 40) }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <div id="no-video-placeholder" class="project-empty-state-placeholder">
                            <i class="ph-bold ph-video project-empty-state-icon"></i>
                            <p class="project-empty-state-text">Belum ada karya video yang ditambahkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content: Barang -->
            <div id="content-barang" class="tab-content-panel" style="display: none;">
                <div class="project-empty-state-placeholder large">
                    <i class="ph-bold ph-package project-empty-state-icon"></i>
                    <p class="project-empty-state-text">Belum ada pengadaan barang.</p>
                </div>
            </div>
        </main>

    </div>

    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $(document).ready(function () {
            // Tab Switching
            $('.tab-trigger').on('click', function () {
                const target = $(this).data('tab');
                $('.tab-trigger').removeClass('active');
                $(this).addClass('active');
                $('.tab-content-panel').hide();
                $('#content-' + target).fadeIn(300);
            });
        });

        // Research Project Functions
        window.toggleResearchCheck = function (el, field) {
            const isChecked = !$(el).hasClass('checked');
            $(el).toggleClass('checked');

            if (isChecked) {
                $(el).html('<i class="ph-bold ph-check"></i>');
            } else {
                $(el).empty();
            }

            saveResearchField(field, isChecked ? 1 : 0);
        }

        window.saveResearchField = function (field, value) {
            $.ajax({
                url: '{{ route("teacher.research.save") }}',
                method: 'POST',
                data: {
                    user_id: {{ $user->id }},
                    field: field,
                    value: value
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Data diperbarui', 'success');
                    }
                },
                error: function () {
                    showToast('Gagal menyimpan data', 'error');
                }
            });
        }

        // Video Project Functions
        window.saveVideoProject = function () {
            const name = $('#video_name').val();
            const link = $('#video_link').val();

            if (!name) {
                showToast('Nama karya harus diisi', 'error');
                return;
            }

            $.ajax({
                url: '{{ route("teacher.video.save") }}',
                method: 'POST',
                data: {
                    user_id: {{ $user->id }},
                    name: name,
                    link: link
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Karya berhasil ditambahkan', 'success');
                        $('#video_name').val('');
                        $('#video_link').val('');
                        $('#no-video-placeholder').remove();

                        const video = res.data;
                        const linkHtml = video.link ? `
                            <a href="${video.link}" target="_blank" class="project-video-link-anchor">
                                <i class="ph ph-link"></i> ${video.link.length > 40 ? video.link.substring(0, 40) + '...' : video.link}
                            </a>
                        ` : '';

                        $('#video-list-container').append(`
                            <div class="task-item project-task-item project-video-item-card" id="video-item-${video.id}">
                                <div class="project-video-item-header">
                                    <div class="project-video-title-wrapper">
                                        <div class="project-video-icon-box">
                                            <i class="ph-bold ph-video-camera"></i>
                                        </div>
                                        <div class="project-video-name-text">${video.name}</div>
                                    </div>
                                    <button onclick="deleteVideo(${video.id})" class="project-delete-trigger">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                                ${linkHtml}
                            </div>
                        `);
                    }
                },
                error: function () {
                    showToast('Gagal menambahkan karya', 'error');
                }
            });
        }

        window.deleteVideo = function (id) {
            if (!confirm('Hapus karya ini?')) return;

            $.ajax({
                url: '{{ route("teacher.video.delete") }}',
                method: 'POST',
                data: {
                    id: id,
                    user_id: {{ $user->id }}
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Karya dihapus', 'success');
                        $(`#video-item-${id}`).fadeOut(300, function () {
                            $(this).remove();
                            if ($('#video-list-container').children().length === 0) {
                                $('#video-list-container').html(`
                                    <div id="no-video-placeholder" class="project-empty-state-placeholder">
                                        <i class="ph-bold ph-video project-empty-state-icon"></i>
                                        <p class="project-empty-state-text">Belum ada karya video yang ditambahkan.</p>
                                    </div>
                                `);
                            }
                        });
                    }
                }
            });
        }

        function showToast(msg, type) {
            const id = 'toast-' + Date.now();
            const bg = type === 'success' ? '#ECFDF5' : '#FEF2F2';
            const color = type === 'success' ? '#065F46' : '#991B1B';
            const borderColor = type === 'success' ? '#10B981' : '#EF4444';

            $('#toast-container').append(`
                <div id="${id}" style="margin-bottom:10px; background:${bg}; color:${color}; padding:14px 22px; border-radius:16px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); font-weight:700; font-size: 13px; border-left: 5px solid ${borderColor}; display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s ease-out;">
                    <i class="ph-fill ph-${type === 'success' ? 'check-circle' : 'warning-circle'}" style="font-size: 18px;"></i>
                    ${msg}
                </div>
            `);
            setTimeout(() => { $(`#${id}`).fadeOut(300, function () { $(this).remove(); }); }, 2500);
        }
    </script>
</x-app-layout>