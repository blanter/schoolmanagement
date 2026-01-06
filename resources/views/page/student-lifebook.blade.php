<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/my-tasks/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Student Controlling My Lifebook</div>
                    <div class="header-subtitle">{{ $userguru->name }}</div>
                </div>
            </div>

            <!-- Month Navigator -->
            <div class="month-navigator-bar">
                <button id="prev-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <div id="current-month-label">...</div>
                <button id="next-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </header>

        <main class="project-main-content">
            <div class="eval-container">
                <div class="evaluation-guru-section" style="width: 100%;">
                    @php
                        $sections = [
                            ['id' => 'goals_monthly', 'label' => 'Goal/Target di Bulan <span data-label-type="month">...</span>', 'icon' => 'ph-bold ph-target'],
                            ['id' => 'life_aspects', 'label' => 'Aspek-aspek Kehidupan dan Tujuannya', 'icon' => 'ph-bold ph-heart-beat'],
                            ['id' => 'vision_yearly', 'label' => 'Visi/Tujuan Tahun <span data-label-type="year">...</span>', 'icon' => 'ph-bold ph-flag-banner'],
                            ['id' => 'vision_progress', 'label' => 'Seberapa Jauh Visi Kalian Berjalan', 'icon' => 'ph-bold ph-map-trifold'],
                            ['id' => 'gratitude', 'label' => 'Apa yang Membuatmu Bersyukur dan Alasannya', 'icon' => 'ph-bold ph-hands-praying'],
                        ];
                    @endphp

                    @foreach($sections as $section)
                        <div class="eval-card" style="margin-bottom: 25px;">
                            <h3 class="eval-section-title">
                                <i class="{{ $section['icon'] }}"></i> {!! $section['label'] !!}
                            </h3>

                            @if(auth()->id() == $userguru->id)
                                <div class="note-toolbar" data-for="{{ $section['id'] }}">
                                    <button type="button" class="note-format-btn" data-cmd="bold"><i
                                            class="ph-bold ph-text-b"></i></button>
                                    <button type="button" class="note-format-btn" data-cmd="italic"><i
                                            class="ph-bold ph-text-italic"></i></button>
                                    <div class="note-toolbar-divider"></div>
                                    <button type="button" class="note-format-btn" data-cmd="insertUnorderedList"><i
                                            class="ph-bold ph-list-bullets"></i></button>
                                </div>
                            @endif

                            <div id="editor-{{ $section['id'] }}" class="note-editor lifebook-editor"
                                contenteditable="{{ auth()->id() == $userguru->id ? 'true' : 'false' }}"
                                data-field="{{ $section['id'] }}" data-placeholder="Tuliskan di sini..."></div>
                        </div>
                    @endforeach

                    @if(auth()->id() == $userguru->id)
                        <div class="eval-sticky-actions"
                            style="display: flex; align-items: center; justify-content: flex-end; gap: 15px;">
                            <span id="auto-save-status" style="font-size: 11px; color: #9CA3AF; font-style: italic;"></span>
                            <button id="save-lifebook-btn" class="btn-cal-primary eval-btn-save-main-shadow">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan My Lifebook
                            </button>
                        </div>
                    @endif
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
            let currentDate = new Date();
            let isSaving = false;
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            let lastSavedData = {};
            let isOwner = {{ auth()->id() == $userguru->id ? 'true' : 'false' }};

            // Initialize
            updateUI();
            loadData();

            // Auto Save Interval (every 60 seconds)
            if (isOwner) {
                setInterval(function () {
                    if (isSaving) return;
                    let changed = false;
                    $('.lifebook-editor').each(function () {
                        const field = $(this).data('field');
                        const currentVal = $(this).html().trim();
                        const lastVal = (lastSavedData[field] || '').trim();
                        if (currentVal !== lastVal) changed = true;
                    });

                    if (changed) {
                        saveLifebook(true);
                    }
                }, 60000);
            }

            // WYSIWYG
            $('.note-format-btn').on('click', function (e) {
                e.preventDefault();
                const cmd = $(this).data('cmd');
                document.execCommand(cmd, false, null);
                $(this).closest('.eval-card').find('.note-editor').focus();
            });

            // Navigator Actions
            $('#prev-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateUI();
                loadData();
            });

            $('#next-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() + 1);
                updateUI();
                loadData();
            });

            $('#save-lifebook-btn').on('click', function () {
                saveLifebook();
            });

            function updateUI() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const label = months[month] + " " + year;
                $('#current-month-label').text(label);

                // Update dynamic labels
                $('[data-label-type="month"]').text(months[month]);
                $('[data-label-type="year"]').text(year);
            }

            function loadData() {
                $('.lifebook-editor').html('<i style="color:#9CA3AF; font-size:12px;">Memuat...</i>');
                $.ajax({
                    url: '{{ route("student.lifebook.get") }}',
                    method: 'GET',
                    data: {
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1
                    },
                    success: function (res) {
                        lastSavedData = res.data || {};
                        $('.lifebook-editor').each(function () {
                            const field = $(this).data('field');
                            $(this).html(lastSavedData[field] || '');
                        });
                    }
                });
            }

            function saveLifebook(isAutoSave = false) {
                if (isSaving) return;

                const data = {
                    user_id: {{ $userguru->id }},
                    year: currentDate.getFullYear(),
                    month: currentDate.getMonth() + 1
                };

                $('.lifebook-editor').each(function () {
                    const field = $(this).data('field');
                    data[field] = $(this).html();
                });

                isSaving = true;
                const btn = $('#save-lifebook-btn');
                const originalHtml = btn.html();

                if (isAutoSave) {
                    $('#auto-save-status').text('Menyimpan otomatis...');
                } else {
                    btn.prop('disabled', true).html('<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...');
                }

                $.ajax({
                    url: '{{ route("student.lifebook.save") }}',
                    method: 'POST',
                    data: data,
                    success: function (res) {
                        if (res.success) {
                            $('.lifebook-editor').each(function () {
                                const field = $(this).data('field');
                                lastSavedData[field] = $(this).html();
                            });

                            const now = new Date();
                            const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

                            if (isAutoSave) {
                                $('#auto-save-status').text('Draft disimpan otomatis ' + timeStr);
                            } else {
                                showToast('Berhasil disimpan!', 'success');
                                $('#auto-save-status').text('Terakhir disimpan ' + timeStr);
                            }
                        }
                    },
                    complete: function () {
                        isSaving = false;
                        if (!isAutoSave) {
                            btn.prop('disabled', false).html(originalHtml);
                        }
                    }
                });
            }

            function showToast(msg, type) {
                const id = 'toast-' + Date.now();
                const bg = type === 'success' ? '#D1FAE5' : '#FEE2E2';
                $('#toast-container').append(`<div id="${id}" style="margin-bottom:10px; background:${bg}; padding:12px 20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-weight:600; font-size: 14px; color: #1F2937;">${msg}</div>`);
                setTimeout(() => { $(`#${id}`).fadeOut(300, function () { $(this).remove(); }); }, 2000);
            }
        });
    </script>
</x-app-layout>