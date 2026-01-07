<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/teacher-planner/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Student Progress</div>
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
            <div class="calendar-content-wrapper">
                <!-- Records List Section -->
                <div class="note-list-section">
                    <div class="note-section-header">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-chart-line-up" style="color: #10B981;"></i> Progress Murid
                        </h3>
                        @if(auth()->id() == $userguru->id)
                            <button id="add-record-btn" class="btn-cal-primary"
                                style="padding: 8px 16px; font-size: 12px; border-radius: 10px;">
                                <i class="ph-bold ph-plus"></i> Tambah Data
                            </button>
                        @endif
                    </div>

                    <div id="records-list-container" class="note-list-scroll">
                        <!-- Populated by JS -->
                        <div class="note-empty-state">
                            <i class="ph-bold ph-chart-line-up"
                                style="font-size: 48px; color: #E5E7EB; margin-bottom: 10px;"></i>
                            <p style="color: #9CA3AF; font-size: 14px;">Memuat data...</p>
                        </div>
                    </div>
                </div>

                @if(auth()->id() == $userguru->id)
                    <!-- Form Section -->
                    <div id="record-form-section" class="note-input-section" style="display: none;">
                        <div class="note-section-header">
                            <h3 id="form-title" style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                                <i class="ph-bold ph-plus-circle" style="color: #7F56D9;"></i> Tambah Progress
                            </h3>
                        </div>

                        <div class="note-form-card">
                            <input type="hidden" id="record-id">

                            <!-- Student Selector (Searchable Multiple Select) -->
                            <div style="margin-bottom: 15px; position: relative;">
                                <label
                                    style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px; text-transform: uppercase;">Pilih
                                    Murid</label>
                                <div class="student-select-wrapper"
                                    style="border: 1.5px solid #F3F4F6; border-radius: 16px; padding: 10px; background: #fff;">
                                    <div id="selected-students-pills"
                                        style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                                        <!-- Pills go here -->
                                    </div>
                                    <input type="text" id="student-search" class="project-input"
                                        style="border: none; outline: none; width: 100%; min-height: 20px; font-size: 14px;"
                                        placeholder="Cari nama murid...">

                                    <div id="student-dropdown" class="custom-dropdown"
                                        style="display: none; position: absolute; z-index: 100; left: 0; right: 0; background: white; border: 1.5px solid #F3F4F6; border-radius: 16px; margin-top: 5px; max-height: 200px; overflow-y: auto; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                                        @foreach($students as $student)
                                            <div class="student-option" data-id="{{ $student->id }}"
                                                data-name="{{ $student->name }}"
                                                style="padding: 10px 15px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #F9FAFB;">
                                                {{ $student->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label
                                    style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px; text-transform: uppercase;">Mata
                                    Pelajaran / Topik</label>
                                <input type="text" id="record-subject" class="note-editor"
                                    style="min-height: 45px; border-radius: 16px; margin-bottom: 0;"
                                    placeholder="Contoh: Matematika - Perkalian">
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label
                                    style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px; text-transform: uppercase;">Nilai</label>
                                <input type="text" id="record-score" class="note-editor"
                                    style="min-height: 45px; border-radius: 16px; margin-bottom: 0;"
                                    placeholder="Contoh: 85 atau A">
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label
                                    style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px; text-transform: uppercase;">Deskripsi
                                    Singkat</label>
                                <textarea id="record-description" class="note-editor"
                                    style="min-height: 80px; border-radius: 16px; margin-bottom: 0; padding: 12px;"
                                    placeholder="Tuliskan perkembangan murid..."></textarea>
                            </div>

                            <div class="cal-form-actions">
                                <button id="cancel-record-btn" class="btn-cal-secondary">Batal</button>
                                <button id="save-record-btn" class="btn-cal-primary">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Progress
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <style>
                        .calendar-content-wrapper {
                            grid-template-columns: 1fr !important;
                        }

                        .note-list-section {
                            max-height: none !important;
                        }
                    </style>
                @endif
            </div>
        </main>
    </div>

    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const urlMonth = urlParams.get('month');
            const urlYear = urlParams.get('year');

            let currentDate = new Date();
            if (urlMonth && urlYear) {
                currentDate = new Date(urlYear, urlMonth - 1, 1);
            } else {
                currentDate.setMonth(currentDate.getMonth() - 1);
            }
            let isSaving = false;
            let currentRecords = [];
            let selectedStudentIds = [];
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const isOwner = {{ auth()->id() == $userguru->id ? 'true' : 'false' }};

            // Initialize
            updateUI();
            loadRecords();

            // Navigator Actions
            $('#prev-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateUI();
                loadRecords();
            });

            $('#next-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() + 1);
                updateUI();
                loadRecords();
            });

            // Form Actions
            $('#add-record-btn').on('click', function () {
                resetForm();
                $('#record-form-section').fadeIn();
                $('html, body').animate({ scrollTop: $('#record-form-section').offset().top - 100 }, 500);
            });

            $('#cancel-record-btn').on('click', function () {
                $('#record-form-section').fadeOut();
            });

            $('#save-record-btn').on('click', function () {
                saveRecord();
            });

            // Student Search Logic
            $('#student-search').on('focus input', function () {
                const query = $(this).val().toLowerCase();
                let visibleCount = 0;

                $('.student-option').each(function () {
                    const name = $(this).data('name').toLowerCase();
                    const id = $(this).data('id').toString();

                    if (name.includes(query) && !selectedStudentIds.includes(id)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount > 0) $('#student-dropdown').show();
                else $('#student-dropdown').hide();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.student-select-wrapper').length) {
                    $('#student-dropdown').hide();
                }
            });

            $(document).on('click', '.student-option', function () {
                const id = $(this).data('id').toString();
                const name = $(this).data('name');
                addStudentPill(id, name);
                $('#student-search').val('').focus();
                $('#student-dropdown').hide();
            });

            function addStudentPill(id, name) {
                if (selectedStudentIds.includes(id)) return;
                selectedStudentIds.push(id);

                $('#selected-students-pills').append(`
                    <div class="student-pill" data-id="${id}" style="background: #F3F4F6; color: #4B5563; padding: 4px 12px; border-radius: 100px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                        ${name}
                        <i class="ph ph-x" style="cursor: pointer;" onclick="event.stopPropagation(); removeStudentPill('${id}')"></i>
                    </div>
                `);
            }

            window.removeStudentPill = function (id) {
                selectedStudentIds = selectedStudentIds.filter(sid => sid !== id);
                $(`.student-pill[data-id="${id}"]`).remove();
            }

            function updateUI() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const label = months[month] + " " + year;
                $('#current-month-label').text(label);
            }

            function loadRecords() {
                $('#records-list-container').html('<div class="note-empty-state"><i class="ph-bold ph-spinner ph-spin" style="font-size: 32px;"></i><p>Memuat data...</p></div>');
                $.ajax({
                    url: '{{ route("teacher.progress.get") }}',
                    method: 'GET',
                    data: {
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1
                    },
                    success: function (res) {
                        currentRecords = res.records || [];
                        renderRecords(currentRecords);
                    }
                });
            }

            function renderRecords(records) {
                const container = $('#records-list-container');
                if (records.length === 0) {
                    container.html('<div class="note-empty-state"><i class="ph-bold ph-chart-line-up" style="font-size: 48px; color: #E5E7EB; margin-bottom: 10px;"></i><p style="color: #9CA3AF; font-size: 14px;">Belum ada progress murid bulan ini.</p></div>');
                    return;
                }

                let html = '';
                records.forEach(record => {
                    const studentNames = record.student_names.join(', ');
                    html += `
                        <div class="note-item" style="position: relative;">
                            ${isOwner ? `
                            <div style="position: absolute; top: 15px; right: 15px; display: flex; gap: 8px;">
                                <button onclick="prepareEdit(${record.id})" class="note-format-btn" style="background:#EBF4FF; color:#7F56D9;"><i class="ph-bold ph-pencil"></i></button>
                                <button onclick="confirmDelete(${record.id})" class="note-format-btn" style="background:#FEE2E2; color:#EF4444;"><i class="ph-bold ph-trash"></i></button>
                            </div>
                            ` : ''}
                            <div class="note-item-date" style="font-size: 15px; font-weight: 700; color:#1F2937; margin-bottom: 5px;">${record.subject}</div>
                            <div style="font-size: 13px; color: #7F56D9; font-weight: 600; margin-bottom: 8px;">
                                <i class="ph-bold ph-students"></i> ${studentNames}
                            </div>
                            ${record.score ? `<div style="display:inline-block; background: #ECFDF5; color: #10B981; padding: 2px 10px; border-radius: 6px; font-size: 12px; font-weight: 800; margin-bottom: 8px;">Nilai: ${record.score}</div>` : ''}
                            <div class="note-item-content" style="font-size: 14px; opacity: 0.8; line-height: 1.5;">${record.description || '<i style="color:#9CA3AF">Tidak ada deskripsi</i>'}</div>
                        </div>
                    `;
                });
                container.html(html);
            }

            window.prepareEdit = function (id) {
                const record = currentRecords.find(r => r.id === id);
                if (!record) return;

                resetForm();
                $('#record-id').val(record.id);
                $('#record-subject').val(record.subject);
                $('#record-score').val(record.score);
                $('#record-description').val(record.description);
                $('#form-title').html('<i class="ph-bold ph-pencil-circle" style="color: #7F56D9;"></i> Edit Progress');

                const studentIds = Array.isArray(record.student_ids) ? record.student_ids : JSON.parse(record.student_ids);
                studentIds.forEach(sid => {
                    const option = $(`.student-option[data-id="${sid}"]`);
                    if (option.length) {
                        addStudentPill(sid, option.data('name'));
                    }
                });

                $('#record-form-section').fadeIn();
                $('html, body').animate({ scrollTop: $('#record-form-section').offset().top - 100 }, 500);
            }

            window.confirmDelete = function (id) {
                if (confirm('Hapus data progress ini?')) {
                    $.ajax({
                        url: '{{ route("teacher.progress.delete") }}',
                        method: 'POST',
                        data: {
                            id: id,
                            user_id: {{ $userguru->id }}
                        },
                        success: function (res) {
                            if (res.success) {
                                showToast('Berhasil dihapus!', 'success');
                                loadRecords();
                            }
                        }
                    });
                }
            }

            function saveRecord() {
                if (isSaving) return;

                const subject = $('#record-subject').val();
                if (!subject) { showToast('Mata Pelajaran harus diisi', 'error'); return; }
                if (selectedStudentIds.length === 0) { showToast('Pilih minimal satu murid', 'error'); return; }

                isSaving = true;
                const btn = $('#save-record-btn');
                const originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...');

                $.ajax({
                    url: '{{ route("teacher.progress.save") }}',
                    method: 'POST',
                    data: {
                        id: $('#record-id').val(),
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1,
                        student_ids: selectedStudentIds,
                        subject: subject,
                        score: $('#record-score').val(),
                        description: $('#record-description').val()
                    },
                    success: function (res) {
                        if (res.success) {
                            showToast('Berhasil disimpan!', 'success');
                            $('#record-form-section').fadeOut();
                            loadRecords();
                        }
                    },
                    complete: function () {
                        isSaving = false;
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            }

            function resetForm() {
                $('#record-id').val('');
                $('#record-subject').val('');
                $('#record-score').val('');
                $('#record-description').val('');
                $('#selected-students-pills').empty();
                selectedStudentIds = [];
                $('#form-title').html('<i class="ph-bold ph-plus-circle" style="color: #7F56D9;"></i> Tambah Progress Baru');
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
        .student-option:hover {
            background: #F9F5FF;
            color: #7F56D9;
        }
    </style>
</x-app-layout>