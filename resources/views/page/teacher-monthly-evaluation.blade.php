<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/teacher-planner/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Monthly Evaluation</div>
                    <div class="header-subtitle">{{ $userguru->name }}</div>
                </div>
            </div>

            <!-- Month Navigator -->
            <div class="month-navigator-bar">
                <button id="prev-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <div id="current-month-label">-</div>
                <button id="next-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </header>

        <main class="project-main-content">
            <div class="evaluation-container" style="padding: 0 20px 40px;">
                <div class="evaluation-grid">
                    <!-- LEFT COLUMN: GURU EVALUATION (FIXED) -->
                    <div class="evaluation-guru-section">
                        @php
                            $sections = [
                                'Monthly Evaluation' => [
                                    ['label' => 'Evaluasi', 'id' => 'evaluasi'],
                                    ['label' => 'Student Progress', 'id' => 'student_progress'],
                                ],
                                'Monthly Review' => [
                                    ['label' => 'Review', 'id' => 'review'],
                                ],
                                'Monthly Reflection' => [
                                    ['label' => 'Apa yang berhasil?', 'id' => 'berhasil'],
                                    ['label' => 'Apa yang belum berhasil?', 'id' => 'belum_berhasil'],
                                    ['label' => 'Contoh Tauladan', 'id' => 'tauladan'],
                                ]
                            ];
                        @endphp

                        @foreach($sections as $title => $fields)
                            <div class="evaluation-card"
                                style="background: white; border-radius: 25px; border: 1.5px solid #F3F4F6; padding: 25px; margin-bottom: 25px;">
                                <h3
                                    style="font-size: 18px; font-weight: 800; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                    <i class="ph-bold ph-newspaper" style="color: #7F56D9;"></i> {{ $title }}
                                </h3>

                                @foreach($fields as $field)
                                    <div class="form-field" style="margin-bottom: 20px;">
                                        <label
                                            style="display: block; font-size: 13px; font-weight: 700; color: #4B5563; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">{{ $field['label'] }}</label>

                                        @if(auth()->id() == $userguru->id)
                                            <div class="note-toolbar" data-for="{{ $field['id'] }}">
                                                <button type="button" class="note-format-btn" data-cmd="bold"><i
                                                        class="ph-bold ph-text-b"></i></button>
                                                <button type="button" class="note-format-btn" data-cmd="italic"><i
                                                        class="ph-bold ph-text-italic"></i></button>
                                                <div class="note-toolbar-divider"></div>
                                                <button type="button" class="note-format-btn" data-cmd="insertUnorderedList"><i
                                                        class="ph-bold ph-list-bullets"></i></button>
                                            </div>
                                        @endif

                                        <div id="guru-{{ $field['id'] }}" class="note-editor guru-field"
                                            contenteditable="{{ auth()->id() == $userguru->id ? 'true' : 'false' }}"
                                            data-field="{{ $field['id'] }}" style="min-height: 120px; border-radius: 16px;"
                                            data-placeholder="Tuliskan di sini..."></div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        @if(auth()->id() == $userguru->id)
                            <div
                                style="position: sticky; bottom: 85px; display: flex; justify-content: flex-end; z-index: 10;">
                                <button id="save-guru-btn" class="btn-cal-primary"
                                    style="box-shadow: 0 10px 25px rgba(127, 86, 217, 0.3);">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Semua Evaluasi
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- RIGHT COLUMN: NON GURU (DYNAMIC) -->
                    <br />
                    <div class="evaluation-nonguru-section">
                        <div class="evaluation-card"
                            style="background: #F9FAFB; border-radius: 25px; border: 1.5px solid #F3F4F6; padding: 25px; position: sticky; top: 100px;">
                            <div class="note-section-header" style="margin-bottom: 20px;">
                                <h3 style="font-size: 16px; font-weight: 800; color: #1F2937; margin: 0;">
                                    <i class="ph-bold ph-briefcase" style="color: #10B981;"></i> Non Guru Eval
                                </h3>
                                @if(auth()->id() == $userguru->id)
                                    <button id="add-nonguru-btn" class="btn-cal-primary"
                                        style="padding: 6px 12px; font-size: 11px; border-radius: 8px;">
                                        <i class="ph-bold ph-plus"></i> Tambah
                                    </button>
                                @endif
                            </div>

                            <div id="nonguru-list" class="note-list-scroll"
                                style="max-height: calc(100vh - 350px); overflow-y: auto;">
                                <!-- Dynamic List -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Non Guru Entry -->
    <div id="nonguru-modal" class="cal-modal-overlay"
        style="display: none; align-items: center; justify-content: center;">
        <div class="cal-modal" style="width: 500px; max-width: 95%;">
            <div class="cal-modal-header">
                <h3 id="nonguru-modal-title">Tambah Non Guru Eval</h3>
                <button class="cal-close-modal" onclick="$('#nonguru-modal').fadeOut()"><i class="ph ph-x"></i></button>
            </div>
            <div class="cal-modal-body" style="padding: 20px;">
                <input type="hidden" id="nonguru-id">
                <div class="form-field" style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px;">JUDUL</label>
                    <input type="text" id="nonguru-title" class="project-input" placeholder="Masukkan judul..."
                        style="width: 100%;">
                </div>
                <div class="form-field">
                    <label
                        style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px;">DESKRIPSI</label>
                    <div class="note-toolbar">
                        <button type="button" class="note-format-btn" data-cmd="bold"><i
                                class="ph-bold ph-text-b"></i></button>
                        <button type="button" class="note-format-btn" data-cmd="italic"><i
                                class="ph-bold ph-text-italic"></i></button>
                    </div>
                    <div id="nonguru-description" class="note-editor" contenteditable="true"
                        style="min-height: 200px; border-radius: 16px;" data-placeholder="Tuliskan deskripsi..."></div>
                </div>
                <div class="cal-form-actions" style="margin-top: 25px;">
                    <button class="btn-cal-secondary" onclick="$('#nonguru-modal').fadeOut()">Batal</button>
                    <button id="save-nonguru-btn" class="btn-cal-primary">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $(document).ready(function () {
            let currentDate = new Date();
            let isSaving = false;
            let currentData = null;
            let currentNonGuru = [];
            let lastSavedGuru = {};
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const isOwner = {{ auth()->id() == $userguru->id ? 'true' : 'false' }};

            // Initialize
            updateUI();
            loadData();

            // WYSIWYG
            $('.note-format-btn').on('click', function (e) {
                e.preventDefault();
                const cmd = $(this).data('cmd');
                const $editor = $(this).closest('.form-field').find('.note-editor');
                document.execCommand(cmd, false, null);
                $editor.focus();
            });

            // Navigator
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

            // Guru Save
            $('#save-guru-btn').on('click', function () {
                saveGuru();
            });

            // Auto Save Guru
            if (isOwner) {
                setInterval(() => {
                    if (isSaving) return;
                    let changed = false;
                    $('.guru-field').each(function () {
                        const field = $(this).data('field');
                        if ($(this).html() !== (lastSavedGuru[field] || '')) changed = true;
                    });
                    if (changed) saveGuru(true);
                }, 45000);
            }

            // Non Guru Actions
            $('#add-nonguru-btn').on('click', function () {
                $('#nonguru-id').val('');
                $('#nonguru-title').val('');
                $('#nonguru-description').html('');
                $('#nonguru-modal-title').text('Tambah Non Guru Eval');
                $('#nonguru-modal').css('display', 'flex').hide().fadeIn();
            });

            $('#save-nonguru-btn').on('click', function () {
                saveNonGuru();
            });

            function updateUI() {
                $('#current-month-label').text(months[currentDate.getMonth()] + " " + currentDate.getFullYear());
            }

            function loadData() {
                $('.guru-field').html('<i style="color:#9CA3AF; font-size:12px;">Memuat...</i>');
                $('#nonguru-list').html('<p style="text-align:center; padding:20px; color:#9CA3AF; font-size:12px;">Memuat...</p>');

                $.ajax({
                    url: '{{ route("teacher.evaluation.get") }}',
                    method: 'GET',
                    data: {
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1
                    },
                    success: function (res) {
                        // Populate Guru Fields
                        lastSavedGuru = res.evaluation || {};
                        $('.guru-field').each(function () {
                            const field = $(this).data('field');
                            $(this).html(lastSavedGuru[field] || '');
                        });

                        // Populate Non Guru List
                        currentNonGuru = res.nonGuruEvaluations || [];
                        renderNonGuru();
                    }
                });
            }

            function renderNonGuru() {
                const container = $('#nonguru-list');
                if (currentNonGuru.length === 0) {
                    container.html('<div style="text-align:center; padding:30px; color:#9CA3AF; font-size:13px;"><i class="ph ph-mask-sad" style="font-size:32px; display:block; margin-bottom:10px;"></i>Belum ada evaluasi non-guru.</div>');
                    return;
                }

                let html = '';
                currentNonGuru.forEach(item => {
                    html += `
                        <div class="note-item" style="padding: 15px; background: white; border-radius: 16px; margin-bottom: 12px; border: 1px solid #F3F4F6;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:5px;">
                                <div style="font-weight:800; color:#1F2937; font-size:14px;">${item.title}</div>
                                ${isOwner ? `
                                <div style="display:flex; gap:5px;">
                                    <button onclick="editNonGuru(${item.id})" style="border:none; background:#F3F4F6; width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#7F56D9;"><i class="ph ph-pencil"></i></button>
                                    <button onclick="deleteNonGuru(${item.id})" style="border:none; background:#FEE2E2; width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#EF4444;"><i class="ph ph-trash"></i></button>
                                </div>
                                ` : ''}
                            </div>
                            <div style="font-size:13px; color:#6B7280; line-height:1.4;">${item.description || ''}</div>
                        </div>
                    `;
                });
                container.html(html);
            }

            window.editNonGuru = function (id) {
                const item = currentNonGuru.find(i => i.id === id);
                if (!item) return;

                $('#nonguru-id').val(item.id);
                $('#nonguru-title').val(item.title);
                $('#nonguru-description').html(item.description || '');
                $('#nonguru-modal-title').text('Edit Non Guru Eval');
                $('#nonguru-modal').css('display', 'flex').hide().fadeIn();
            }

            window.deleteNonGuru = function (id) {
                if (!confirm('Hapus data ini?')) return;
                $.ajax({
                    url: '{{ route("teacher.evaluation.deleteNonGuru") }}',
                    method: 'POST',
                    data: { id: id, user_id: {{ $userguru->id }} },
                    success: function () {
                        showToast('Berhasil dihapus', 'success');
                        loadData();
                    }
                });
            }

            function saveGuru(isAuto = false) {
                if (isSaving) return;
                isSaving = true;

                const data = {
                    user_id: {{ $userguru->id }},
                    year: currentDate.getFullYear(),
                    month: currentDate.getMonth() + 1
                };
                $('.guru-field').each(function () {
                    data[$(this).data('field')] = $(this).html();
                });

                if (!isAuto) {
                    $('#save-guru-btn').prop('disabled', true).html('<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...');
                }

                $.ajax({
                    url: '{{ route("teacher.evaluation.saveGuru") }}',
                    method: 'POST',
                    data: data,
                    success: function () {
                        if (!isAuto) showToast('Eveluasi disimpan!', 'success');
                        // Update local last saved
                        $('.guru-field').each(function () {
                            lastSavedGuru[$(this).data('field')] = $(this).html();
                        });
                    },
                    complete: function () {
                        isSaving = false;
                        if (!isAuto) {
                            $('#save-guru-btn').prop('disabled', false).html('<i class="ph-bold ph-floppy-disk"></i> Simpan Semua Evaluasi');
                        }
                    }
                });
            }

            function saveNonGuru() {
                const title = $('#nonguru-title').val();
                if (!title) { showToast('Judul wajib diisi', 'error'); return; }

                $.ajax({
                    url: '{{ route("teacher.evaluation.saveNonGuru") }}',
                    method: 'POST',
                    data: {
                        id: $('#nonguru-id').val(),
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1,
                        title: title,
                        description: $('#nonguru-description').html()
                    },
                    success: function () {
                        showToast('Berhasil disimpan', 'success');
                        $('#nonguru-modal').fadeOut();
                        loadData();
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

    <style>
        .evaluation-guru-section .note-editor {
            background: #F9FAFB;
            border: 1.5px solid #F3F4F6;
            padding: 15px;
            transition: all 0.2s;
        }

        .evaluation-guru-section .note-editor:focus {
            background: #fff;
            border-color: #7F56D9;
            box-shadow: 0 0 0 4px rgba(127, 86, 217, 0.1);
            outline: none;
        }

        @media (max-width: 1024px) {
            .evaluation-grid {
                grid-template-columns: 1fr !important;
            }

            .evaluation-nonguru-section {
                position: static !important;
            }

            .evaluation-nonguru-section .evaluation-card {
                position: static !important;
            }
        }
    </style>
</x-app-layout>