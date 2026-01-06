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
                <div
                    style="margin-bottom: 20px; padding: 15px; background: rgba(125, 82, 222, 0.05); border-radius: 15px; border-left: 4px solid #7D52DE;">
                    <div style="font-weight: 700; color: #7D52DE; font-size: 14px;">Periode Penelitian</div>
                    <div style="color: #4B5563; font-size: 12px; margin-top: 2px;">
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

                <div
                    style="margin-top: 25px; padding: 18px; background: #fff; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #F3F4F6;">
                    <label class="field-label"
                        style="font-weight: 700; font-size: 14px; color: #4B5563; display: block; margin-bottom: 10px;">
                        <i class="ph-bold ph-link"
                            style="color: #7D52DE; font-size: 18px; vertical-align: middle; margin-right: 5px;"></i>
                        Lembar Link Penelitian
                    </label>
                    <input type="text" id="research_link" class="project-input"
                        placeholder="Masukkan link google drive / dokumen penelitian..."
                        value="{{ $project->research_link }}"
                        style="width: 100%; border: 1.5px solid #F3F4F6; border-radius: 12px; padding: 12px 15px; font-size: 14px;"
                        @if(auth()->id() == $user->id) onchange="saveResearchField('research_link', this.value)" @endif
                        @if(auth()->id() != $user->id) disabled @endif>
                    <p
                        style="font-size: 11px; color: #9CA3AF; margin-top: 10px; display: flex; align-items: center; gap: 5px;">
                        <i class="ph ph-info"></i> Link akan tersimpan secara otomatis setelah Anda selesai mengetik.
                    </p>
                </div>
            </div>

            <!-- Tab Content: Video -->
            <div id="content-video" class="tab-content-panel" style="display: none;">
                <div class="project-form-card">
                    <div class="form-field">
                        <label class="field-label" style="font-weight: 700; font-size: 14px;">Nama Karya</label>
                        <input type="text" class="project-input" placeholder="Masukkan nama karya...">
                    </div>
                    <div class="form-field" style="margin-top: 15px;">
                        <label class="field-label" style="font-weight: 700; font-size: 14px;">Link Karya</label>
                        <input type="text" class="project-input" placeholder="https://youtube.com/...">
                    </div>
                </div>
                @if(auth()->id() == $user->id)
                    <div class="project-actions">
                        <button class="btn-teacher-project btn-teacher-project-grey">
                            <i class="ph-bold ph-plus"></i> Tambah Data
                        </button>
                        <button class="btn-teacher-project">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                        </button>
                    </div>
                @endif
            </div>

            <!-- Tab Content: Barang -->
            <div id="content-barang" class="tab-content-panel" style="display: none;">
                <div
                    style="text-align: center; padding: 40px; color: #9CA3AF; background: #F9FAFB; border-radius: 25px; border: 1.5px dashed #E5E7EB;">
                    <i class="ph-bold ph-package" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                    <p style="font-weight: 600;">Belum ada pengadaan barang.</p>
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