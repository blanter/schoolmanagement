<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section matching Teacher Planner theme -->
        <header class="page-header-unified center">
            <div class="header-top">
                <div class="header-title" id="display-month">Januari 2026</div>
                <div class="calendar-filter-trigger" id="calendar-trigger">
                    <i class="ph-fill ph-calendar"></i>
                </div>
                <!-- Hidden date picker for filter -->
                <input type="month" id="date-picker" class="date-hidden-input">
            </div>

            <div class="calendar-strip" id="calendar-container">
                <!-- Populated by JS -->
            </div>
        </header>

        <main class="project-main-content">
            <div class="calendar-content-wrapper">
                <!-- Top/Section 1: Notes for selected date -->
                <div class="notes-list-section">
                    <div class="section-header">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-notebook" style="color: #7F56D9;"></i> Catatan Tanggal Ini
                        </h3>
                        <span id="notes-count" style="font-size: 13px; color: #9CA3AF; font-weight: 600;">0 catatan</span>
                    </div>
                    
                    <div id="notes-list-container" class="notes-list">
                        <!-- Populated by JS -->
                        <div class="empty-state">
                            <i class="ph-bold ph-note-blank" style="font-size: 48px; color: #E5E7EB; margin-bottom: 10px;"></i>
                            <p style="color: #9CA3AF; font-size: 14px;">Pilih tanggal untuk melihat catatan</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom/Section 2: Note Input WYSIWYG -->
                <div class="note-input-section">
                    <div class="section-header">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-note-pencil" style="color: #7F56D9;"></i> Tulis Catatan
                        </h3>
                        <span id="selected-date-label" style="font-size: 13px; font-weight: 600; color: #9CA3AF;">-</span>
                    </div>

                    <div class="note-form-card">
                        <!-- WYSIWYG Toolbar -->
                        <div class="formatter-toolbar">
                            <button type="button" class="format-btn" data-cmd="bold" title="Bold (CTRL+B)"><i class="ph-bold ph-text-b"></i></button>
                            <button type="button" class="format-btn" data-cmd="italic" title="Italic (CTRL+I)"><i class="ph-bold ph-text-italic"></i></button>
                            <button type="button" class="format-btn" data-cmd="underline" title="Underline (CTRL+U)"><i class="ph-bold ph-text-underline"></i></button>
                            <div class="toolbar-divider"></div>
                            <button type="button" class="format-btn" data-cmd="insertUnorderedList" title="Bullet List"><i class="ph-bold ph-list-bullets"></i></button>
                            <button type="button" class="format-btn" data-cmd="insertOrderedList" title="Numbered List"><i class="ph-bold ph-list-numbers"></i></button>
                            <div class="toolbar-divider"></div>
                            <button type="button" class="format-btn" data-cmd="createLink" title="Insert Link"><i class="ph-bold ph-link"></i></button>
                            <button type="button" class="format-btn" data-cmd="unlink" title="Remove Link"><i class="ph-bold ph-link-break"></i></button>
                        </div>

                        <!-- Content Editable div instead of textarea -->
                        <div id="teacher-note-editor" class="note-editor" contenteditable="true" 
                            data-placeholder="Tuliskan catatan atau refleksi untuk hari ini..."></div>
                        
                        <div class="form-actions">
                            <button id="clear-note-btn" class="btn-secondary">
                                <i class="ph-bold ph-trash"></i> Hapus
                            </button>
                            <button id="save-note-btn" class="btn-primary">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <div class="bottom-navigation">
            <a href="/teacher-planner/{{ $userguru->id }}" class="nav-btn nav-btn-back">
                <i class="ph ph-arrow-left"></i>
                <span>Back to Planner</span>
            </a>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <!-- Full Calendar Modal -->
    <div class="modal-overlay" id="full-calendar-modal">
        <div class="calendar-modal">
            <div class="modal-header">
                <h3><i class="ph-fill ph-calendar"></i> Kalender Lengkap</h3>
                <button class="close-modal" id="close-calendar-modal"><i class="ph ph-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="modal-month-nav">
                    <button class="month-nav-btn" id="prev-month-modal"><i class="ph-bold ph-caret-left"></i></button>
                    <div class="current-month-display" id="modal-month-title">Januari 2026</div>
                    <button class="month-nav-btn" id="next-month-modal"><i class="ph-bold ph-caret-right"></i></button>
                </div>
                <div class="calendar-grid" id="modal-calendar-grid">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>
    </div>

    <style>
        .calendar-strip {
            display: flex;
            gap: 15px;
            padding: 10px 5px 15px !important;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .strip-day {
            position: relative;
            padding-top: 18px !important; /* Increased to fix week-marker cut off */
        }

        .week-marker {
            position: absolute;
            top: -2px; /* Adjusted position */
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px;
            font-weight: 800;
            color: #7F56D9;
            background: #F3E8FF;
            padding: 2px 5px;
            border-radius: 8px;
            white-space: nowrap;
            letter-spacing: 0.5px;
            z-index: 2;
        }

        .has-note-dot {
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #10B981;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
            animation: pulse-dot 2s infinite;
        }

        .strip-day.week-start {
            margin-left: 10px;
            border-left: 1.5px dashed rgba(127, 86, 217, 0.2);
            padding-left: 10px !important;
        }

        @keyframes pulse-dot {
            0%, 100% { transform: translateX(-50%) scale(1); opacity: 1; }
            50% { transform: translateX(-50%) scale(1.5); opacity: 0.5; }
        }

        /* Full Calendar Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .calendar-modal {
            background: #fff;
            width: 100%;
            max-width: 450px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, #7F56D9 0%, #9E77ED 100%);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .close-modal {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .close-modal:hover { background: rgba(255, 255, 255, 0.3); }

        .modal-body {
            padding: 20px;
        }

        .modal-month-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }

        .month-nav-btn {
            background: #F3F4F6;
            border: none;
            color: #4B5563;
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .month-nav-btn:hover { background: #E5E7EB; color: #7F56D9; }

        .current-month-display {
            font-size: 16px;
            font-weight: 700;
            color: #1F2937;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .grid-day-name {
            text-align: center;
            font-size: 11px;
            font-weight: 800;
            color: #9CA3AF;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        .grid-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            color: #4B5563;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            border: 1.5px solid transparent;
        }

        .grid-day:hover { background: #F3F4F6; }

        .grid-day.other-month { opacity: 0.3; cursor: default; }

        .grid-day.today-highlight {
            border-color: #7F56D9;
            color: #7F56D9;
            background: #F5F3FF;
        }

        .grid-day.selected-day {
            background: #7F56D9;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(127, 86, 217, 0.3);
        }

        .grid-day.has-note-marker::after {
            content: '';
            position: absolute;
            bottom: 6px;
            width: 4px;
            height: 4px;
            background: #10B981;
            border-radius: 50%;
        }

        .grid-day.selected-day.has-note-marker::after { background: #fff; }

        .calendar-content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 120px;
            width: 100%;
        }

        .notes-list-section, .note-input-section {
            background: #fff;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            width: 100%;
            box-sizing: border-box;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #F9FAFB;
        }

        .notes-list {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .notes-list::-webkit-scrollbar {
            width: 6px;
        }

        .notes-list::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 10px;
        }

        .notes-list::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9CA3AF;
        }

        .note-item {
            background: #F9FAFB;
            border: 1px solid #F3F4F6;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.2s;
        }

        .note-item-date {
            font-size: 13px;
            font-weight: 700;
            color: #7F56D9;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .note-item-content {
            font-size: 15px;
            color: #2D3748;
            line-height: 1.6;
            word-wrap: break-word;
        }

        /* Cleaner List Formatting */
        .note-item-content ul, .note-item-content ol, 
        .note-editor ul, .note-editor ol { 
            padding-left: 20px !important; 
            margin: 8px 0 !important; 
        }
        
        .note-item-content li, 
        .note-editor li { 
            margin-bottom: 4px !important; 
        }

        /* Toolbar styles */
        .formatter-toolbar {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 10px;
            background: #F8F9FA;
            border: 1.5px solid #F3F4F6;
            border-bottom: none;
            border-radius: 16px 16px 0 0;
        }

        .format-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 8px;
            color: #4A5568;
            cursor: pointer;
            transition: all 0.2s;
        }

        .format-btn:hover {
            background: #EDF2F7;
            color: #7F56D9;
        }

        .format-btn.active {
            background: #EBF4FF;
            color: #7F56D9;
        }

        .toolbar-divider {
            width: 1px;
            height: 20px;
            background: #E2E8F0;
            margin: 0 5px;
        }

        /* Editor styles */
        .note-editor {
            width: 100%;
            min-height: 200px;
            max-height: 500px;
            overflow-y: auto;
            border: 1.5px solid #F3F4F6;
            background: #F9FAFB;
            border-radius: 0 0 16px 16px;
            padding: 20px;
            font-size: 15px;
            font-family: inherit;
            color: #1F2937;
            transition: all 0.3s;
            box-sizing: border-box;
            outline: none;
        }

        .note-editor:focus {
            background: #fff;
            border-color: #7F56D9;
            box-shadow: 0 0 0 4px rgba(127, 86, 217, 0.05);
        }

        /* Placeholder logic for contenteditable */
        .note-editor:empty:before {
            content: attr(data-placeholder);
            color: #9CA3AF;
            font-style: italic;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 15px;
        }

        .btn-primary, .btn-secondary {
            padding: 12px 24px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary { background: #7F56D9; color: #fff; }
        .btn-secondary { background: #F3F4F6; color: #6B7280; }

        /* WYSIWYG element support */
        .note-editor b, .note-editor strong { font-weight: 700; }
        .note-editor i, .note-editor em { font-style: italic; }
        .note-editor u { text-decoration: underline; }
        .note-editor a { color: #7F56D9; text-decoration: underline; }
    </style>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $(document).ready(function () {
            let currentSelectedDate = new Date();
            let datesWithNotes = @json($datesWithNotes);
            let allNotes = @json($allNotes);
            let isSaving = false;

            // Initialize
            updateCalendarUI(currentSelectedDate);
            filterNotesByDate(formatDate(currentSelectedDate));

            const y = currentSelectedDate.getFullYear();
            const m = String(currentSelectedDate.getMonth() + 1).padStart(2, '0');
            $('#date-picker').val(`${y}-${m}`);

            loadNote(formatDate(currentSelectedDate));

            // WYSIWYG Formatter Logic
            $('.format-btn').on('click', function(e) {
                e.preventDefault();
                const cmd = $(this).data('cmd');
                
                if (cmd === 'createLink') {
                    const url = prompt('Enter URL:', 'https://');
                    if (url) document.execCommand(cmd, false, url);
                } else if (cmd === 'unlink') {
                    document.execCommand(cmd, false, null);
                } else {
                    document.execCommand(cmd, false, null);
                }
                $('#teacher-note-editor').focus();
            });

            // Monitor active states
            $('#teacher-note-editor').on('keyup mouseup focus', function() {
                $('.format-btn').removeClass('active');
                if (document.queryCommandState('bold')) $('.format-btn[data-cmd="bold"]').addClass('active');
                if (document.queryCommandState('italic')) $('.format-btn[data-cmd="italic"]').addClass('active');
                if (document.queryCommandState('underline')) $('.format-btn[data-cmd="underline"]').addClass('active');
                if (document.queryCommandState('insertUnorderedList')) $('.format-btn[data-cmd="insertUnorderedList"]').addClass('active');
                if (document.queryCommandState('insertOrderedList')) $('.format-btn[data-cmd="insertOrderedList"]').addClass('active');
            });

            // Events
            $('#calendar-trigger').on('click', function() {
                openCalendarModal(currentSelectedDate);
            });

            $('#close-calendar-modal, .modal-overlay').on('click', function(e) {
                if (e.target === this || e.target.closest('#close-calendar-modal')) {
                    $('#full-calendar-modal').fadeOut(200);
                }
            });

            $('#prev-month-modal').on('click', function() {
                const month = parseInt($('#modal-month-title').data('month'));
                const year = parseInt($('#modal-month-title').data('year'));
                const newDate = new Date(year, month - 1, 1);
                renderModalCalendar(newDate);
            });

            $('#next-month-modal').on('click', function() {
                const month = parseInt($('#modal-month-title').data('month'));
                const year = parseInt($('#modal-month-title').data('year'));
                const newDate = new Date(year, month + 1, 1);
                renderModalCalendar(newDate);
            });

            $('#date-picker').on('change', function() {
                const val = $(this).val();
                if (val) {
                    const [year, month] = val.split('-');
                    currentSelectedDate = new Date(year, month - 1, 1);
                    updateCalendarUI(currentSelectedDate);
                    const ds = formatDate(currentSelectedDate);
                    loadNote(ds);
                    filterNotesByDate(ds);
                }
            });

            // Functions for Modal
            function openCalendarModal(baseDate) {
                $('#full-calendar-modal').css('display', 'flex').hide().fadeIn(200);
                renderModalCalendar(baseDate);
            }

            function renderModalCalendar(date) {
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
                const year = date.getFullYear();
                const month = date.getMonth();
                
                $('#modal-month-title').text(months[month] + " " + year)
                    .data('month', month)
                    .data('year', year);

                const firstDay = new Date(year, month, 1).getDay();
                const lastDate = new Date(year, month + 1, 0).getDate();
                const prevLastDate = new Date(year, month, 0).getDate();
                const today = formatDate(new Date());
                const sel = formatDate(currentSelectedDate);

                let html = '';
                dayNames.forEach(d => html += `<div class="grid-day-name">${d}</div>`);

                // Previous month dates
                for (let i = firstDay; i > 0; i--) {
                    html += `<div class="grid-day other-month">${prevLastDate - i + 1}</div>`;
                }

                // Current month dates
                for (let i = 1; i <= lastDate; i++) {
                    const ds = formatDate(new Date(year, month, i));
                    const isT = ds === today ? 'today-highlight' : '';
                    const isS = ds === sel ? 'selected-day' : '';
                    const hasN = datesWithNotes.includes(ds);
                    
                    html += `
                        <div class="grid-day ${isT} ${isS} ${hasN ? 'has-note-marker' : ''}" 
                             onclick="window.navigateFromModal('${ds}')">
                            ${i}
                        </div>
                    `;
                }

                $('#modal-calendar-grid').html(html);
            }

            window.navigateFromModal = function(ds) {
                currentSelectedDate = new Date(ds);
                updateCalendarUI(currentSelectedDate);
                loadNote(ds);
                filterNotesByDate(ds);
                $('#full-calendar-modal').fadeOut(200);
            };

            $('#save-note-btn').on('click', function() {
                if (isSaving) return;
                const htmlContent = $('#teacher-note-editor').html();
                const cleanContent = ($('#teacher-note-editor').text().trim() === '' && htmlContent.indexOf('<') === -1) ? '' : htmlContent;
                saveNote(formatDate(currentSelectedDate), cleanContent);
            });

            $('#clear-note-btn').on('click', function() {
                if (confirm('Hapus catatan?')) {
                    $('#teacher-note-editor').html('');
                    saveNote(formatDate(currentSelectedDate), '');
                }
            });

            // Utils
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function loadNote(dateStr) {
                const display = new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                $('#selected-date-label').text(display);
                $('#teacher-note-editor').attr('data-placeholder', 'Memuat...');

                $.ajax({
                    url: '{{ route("teacher.note.get") }}',
                    method: 'GET',
                    data: { user_id: {{ $userguru->id }}, tanggal: dateStr },
                    success: function(res) {
                        $('#teacher-note-editor').html(res.note || '').attr('data-placeholder', 'Tuliskan catatan atau refleksi untuk hari ini...');
                    }
                });
            }

            function saveNote(dateStr, content) {
                isSaving = true;
                const btn = $('#save-note-btn');
                btn.prop('disabled', true).html('<i class="ph-bold ph-spinner ph-spin"></i>');

                $.ajax({
                    url: '{{ route("teacher.note.save") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: {{ $userguru->id }},
                        tanggal: dateStr,
                        note: content
                    },
                    success: function(res) {
                        if (res.success) {
                            showToast('Disimpan!', 'success');
                            datesWithNotes = res.datesWithNotes;
                            updateCalendarUI(currentSelectedDate);
                            loadAllNotes(dateStr);
                        }
                    },
                    complete: function() {
                        isSaving = false;
                        btn.prop('disabled', false).html('<i class="ph-bold ph-floppy-disk"></i> Simpan');
                    }
                });
            }

            function loadAllNotes(filterDate) {
                $.ajax({
                    url: '{{ route("teacher.note.all") }}',
                    method: 'GET',
                    data: { user_id: {{ $userguru->id }} },
                    success: function(res) {
                        allNotes = res.notes;
                        if (filterDate) filterNotesByDate(filterDate);
                    }
                });
            }

            function filterNotesByDate(dateStr) {
                const container = $('#notes-list-container');
                const filtered = allNotes.filter(n => n.tanggal === dateStr && n.note && n.note.trim() !== '');
                
                if (filtered.length === 0) {
                    container.html('<div class="empty-state">Belum ada catatan untuk tanggal ini.</div>');
                    $('#notes-count').text('0 catatan');
                    return;
                }

                let html = '';
                filtered.forEach(note => {
                    const label = new Date(note.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                    html += `
                        <div class="note-item">
                            <div class="note-item-date"><i class="ph-fill ph-calendar"></i> ${label}</div>
                            <div class="note-item-content">${note.note}</div>
                        </div>
                    `;
                });
                container.html(html);
                $('#notes-count').text(`${filtered.length} catatan`);
            }

            function updateCalendarUI(baseDate) {
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                const days = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
                const y = baseDate.getFullYear();
                const m = baseDate.getMonth();
                const today = formatDate(new Date());

                $('#display-month').text(months[m] + " " + y);
                const last = new Date(y, m + 1, 0).getDate();
                let html = '';

                for (let i = 1; i <= last; i++) {
                    const d = new Date(y, m, i);
                    const ds = formatDate(d);
                    const isA = i === baseDate.getDate() ? 'active' : '';
                    const isT = ds === today ? 'today' : '';
                    const hasN = datesWithNotes.includes(ds);
                    const showW = (d.getDay() === 1 || i === 1);
                    const wN = Math.ceil((i + new Date(y, m, 1).getDay() - 1) / 7);

                    html += `
                        <div class="strip-day ${d.getDay() === 1 ? 'week-start' : ''}" onclick="window.selectDay(this, '${ds}')">
                            ${showW ? `<span class="week-marker">W${wN}</span>` : ''}
                            <span class="strip-day-name">${days[d.getDay()]}</span>
                            <div class="strip-day-number ${isA} ${isT}">${i}</div>
                            ${hasN ? '<div class="has-note-dot"></div>' : ''}
                        </div>
                    `;
                }
                $('#calendar-container').html(html);
                setTimeout(() => {
                    const active = document.querySelector('.strip-day-number.active');
                    if (active) {
                        const container = document.getElementById('calendar-container');
                        if (container) {
                            container.scrollTo({ left: active.parentElement.offsetLeft - (container.offsetWidth/2) + 20, behavior: 'smooth' });
                        }
                    }
                }, 100);
            }

            window.selectDay = function(el, ds) {
                currentSelectedDate = new Date(ds);
                $('.strip-day-number').removeClass('active');
                $(el).find('.strip-day-number').addClass('active');
                loadNote(ds);
                filterNotesByDate(ds);
            };

            function showToast(msg, type) {
                const id = 'toast-' + Date.now();
                const bg = type === 'success' ? '#D1FAE5' : '#FEE2E2';
                const html = `<div id="${id}" style="margin-bottom:10px; background:${bg}; padding:12px 20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-weight:600; font-size: 14px; color: #1F2937;">${msg}</div>`;
                $('#toast-container').append(html);
                setTimeout(() => { $(`#${id}`).fadeOut(300, function(){ $(this).remove(); }); }, 2000);
            }
        });
    </script>
</x-app-layout>