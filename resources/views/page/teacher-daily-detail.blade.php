<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/teacher-planner/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Daily Details</div>
                    <div class="header-subtitle">{{ $userguru->name }}</div>
                    <div class="header-subtitle hidden" id="display-month">Januari 2026</div>
                </div>
            </div>

            <!-- Month Navigator -->
            <div class="month-navigator-bar">
                <button id="prev-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <div id="current-month-label">Januari 2026</div>
                <button id="next-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </header>

        <main class="project-main-content">
            <div class="calendar-content-wrapper" style="display: block;">
                <div class="note-input-section" style="width: 100%;">
                    <div class="note-section-header">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-article" style="color: #7F56D9;"></i> Detail Harian
                        </h3>
                        <span id="selected-month-label"
                            style="font-size: 13px; font-weight: 600; color: #9CA3AF;">Januari 2026</span>
                    </div>

                    <div class="note-form-card">
                        @if(auth()->id() == $userguru->id)
                            <!-- WYSIWYG Toolbar -->
                            <div class="note-toolbar">
                                <button type="button" class="note-format-btn" data-cmd="bold" title="Bold"><i
                                        class="ph-bold ph-text-b"></i></button>
                                <button type="button" class="note-format-btn" data-cmd="italic" title="Italic"><i
                                        class="ph-bold ph-text-italic"></i></button>
                                <button type="button" class="note-format-btn" data-cmd="underline" title="Underline"><i
                                        class="ph-bold ph-text-underline"></i></button>
                                <div class="note-toolbar-divider"></div>
                                <button type="button" class="note-format-btn" data-cmd="insertUnorderedList"
                                    title="Bullet List"><i class="ph-bold ph-list-bullets"></i></button>
                                <button type="button" class="note-format-btn" data-cmd="insertOrderedList"
                                    title="Numbered List"><i class="ph-bold ph-list-numbers"></i></button>
                                <div class="note-toolbar-divider"></div>
                                <button type="button" class="note-format-btn" data-cmd="createLink" title="Insert Link"><i
                                        class="ph-bold ph-link"></i></button>
                                <button type="button" class="note-format-btn" data-cmd="unlink" title="Remove Link"><i
                                        class="ph-bold ph-link-break"></i></button>
                            </div>
                        @endif

                        <!-- Content Editable div -->
                        <div id="daily-detail-editor" class="note-editor"
                            contenteditable="{{ auth()->id() == $userguru->id ? 'true' : 'false' }}"
                            style="min-height: 400px;"
                            data-placeholder="{{ auth()->id() == $userguru->id ? 'Tuliskan detail harian terkait aktifitas pembelajaran atau pekerjaan di sini...' : 'Belum ada detail untuk bulan ini.' }}">
                        </div>

                        @if(auth()->id() == $userguru->id)
                            <div class="cal-form-actions">
                                <button id="clear-detail-btn" class="btn-cal-secondary">
                                    <i class="ph-bold ph-trash"></i> Hapus
                                </button>
                                <button id="save-detail-btn" class="btn-cal-primary">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Detail
                                </button>
                            </div>
                        @endif
                    </div>
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
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            let lastSavedContent = '';

            let isOwner = {{ auth()->id() == $userguru->id ? 'true' : 'false' }};

            // Initialize
            updateUI();
            loadNote();

            // Auto Save Interval (every 30 seconds)
            if (isOwner) {
                setInterval(function () {
                    const currentContent = $('#daily-detail-editor').html();
                    if (currentContent !== lastSavedContent && !isSaving) {
                        saveNote(undefined, true);
                    }
                }, 30000);
            }

            // WYSIWYG
            $('.note-format-btn').on('click', function (e) {
                e.preventDefault();
                const cmd = $(this).data('cmd');
                if (cmd === 'createLink') {
                    const url = prompt('Enter URL:', 'https://');
                    if (url) document.execCommand(cmd, false, url);
                } else {
                    document.execCommand(cmd, false, null);
                }
                $('#daily-detail-editor').focus();
            });

            // Navigator Actions
            $('#prev-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateUI();
                loadNote();
            });

            $('#next-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() + 1);
                updateUI();
                loadNote();
            });

            $('#save-detail-btn').on('click', function () {
                saveNote();
            });

            $('#clear-detail-btn').on('click', function () {
                if (confirm('Hapus detail bulanan ini?')) {
                    $('#daily-detail-editor').html('');
                    saveNote('');
                }
            });

            function updateUI() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const label = months[month] + " " + year;

                $('#display-month, #current-month-label, #selected-month-label').text(label);
            }

            function loadNote() {
                $('#daily-detail-editor').attr('data-placeholder', 'Memuat...');
                $.ajax({
                    url: '{{ route("teacher.daily.get") }}',
                    method: 'GET',
                    data: {
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1
                    },
                    success: function (res) {
                        const content = res.note || '';
                        $('#daily-detail-editor').html(content).attr('data-placeholder', 'Tuliskan detail harian terkait aktifitas pembelajaran atau pekerjaan di sini...');
                        lastSavedContent = content;
                    }
                });
            }

            function saveNote(overrideContent, isAutoSave = false) {
                if (isSaving) return;

                const content = overrideContent !== undefined ? overrideContent : $('#daily-detail-editor').html();
                const cleanContent = ($('#daily-detail-editor').text().trim() === '' && content.indexOf('<') === -1) ? '' : content;

                // Don't save if content hasn't changed
                if (cleanContent === lastSavedContent && !overrideContent) return;

                isSaving = true;
                const btn = $('#save-detail-btn');
                const originalHtml = btn.html();

                if (isAutoSave) {
                    btn.html('<i class="ph-bold ph-cloud-arrow-up"></i> Auto-saving...');
                } else {
                    btn.prop('disabled', true).html('<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...');
                }

                $.ajax({
                    url: '{{ route("teacher.daily.save") }}',
                    method: 'POST',
                    data: {
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1,
                        note: cleanContent
                    },
                    success: function (res) {
                        if (res.success) {
                            lastSavedContent = cleanContent;
                            if (isAutoSave) {
                                btn.html('<i class="ph-bold ph-cloud-check"></i> Saved');
                                setTimeout(() => btn.html(originalHtml), 3000);
                            } else {
                                showToast('Berhasil disimpan!', 'success');
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