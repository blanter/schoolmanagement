<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section matching Teacher Planner theme -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/teacher-planner/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Calendar Note</div>
                    <div class="header-subtitle" id="display-month">Januari 2026</div>
                </div>
                <div class="calendar-filter-trigger" id="calendar-trigger">
                    <i class="ph-fill ph-calendar"></i>
                </div>
                <!-- Hidden date picker for filter -->
                <input type="month" id="date-picker" class="date-hidden-input">
            </div>

            <div class="cal-strip" id="calendar-container">
                <!-- Populated by JS -->
            </div>
        </header>

        <main class="project-main-content">
            <div class="calendar-content-wrapper">
                <!-- Top/Section 1: Notes for selected date -->
                <div class="note-list-section">
                    <div class="note-section-header">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-notebook" style="color: #7F56D9;"></i> Catatan Tanggal Ini
                        </h3>
                        <span id="notes-count" style="font-size: 13px; color: #9CA3AF; font-weight: 600;">0 catatan</span>
                    </div>
                    
                    <div id="notes-list-container" class="note-list-scroll">
                        <!-- Populated by JS -->
                        <div class="note-empty-state">
                            <i class="ph-bold ph-note-blank" style="font-size: 48px; color: #E5E7EB; margin-bottom: 10px;"></i>
                            <p style="color: #9CA3AF; font-size: 14px;">Pilih tanggal untuk melihat catatan</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom/Section 2: Note Input WYSIWYG -->
                <div class="note-input-section">
                    <div class="note-section-header">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-note-pencil" style="color: #7F56D9;"></i> Tulis Catatan
                        </h3>
                        <span id="selected-date-label" style="font-size: 13px; font-weight: 600; color: #9CA3AF;">-</span>
                    </div>

                    <div class="note-form-card">
                        <!-- WYSIWYG Toolbar -->
                        <div class="note-toolbar">
                            <button type="button" class="note-format-btn" data-cmd="bold" title="Bold (CTRL+B)"><i class="ph-bold ph-text-b"></i></button>
                            <button type="button" class="note-format-btn" data-cmd="italic" title="Italic (CTRL+I)"><i class="ph-bold ph-text-italic"></i></button>
                            <button type="button" class="note-format-btn" data-cmd="underline" title="Underline (CTRL+U)"><i class="ph-bold ph-text-underline"></i></button>
                            <div class="note-toolbar-divider"></div>
                            <button type="button" class="note-format-btn" data-cmd="insertUnorderedList" title="Bullet List"><i class="ph-bold ph-list-bullets"></i></button>
                            <button type="button" class="note-format-btn" data-cmd="insertOrderedList" title="Numbered List"><i class="ph-bold ph-list-numbers"></i></button>
                            <div class="note-toolbar-divider"></div>
                            <button type="button" class="note-format-btn" data-cmd="createLink" title="Insert Link"><i class="ph-bold ph-link"></i></button>
                            <button type="button" class="note-format-btn" data-cmd="unlink" title="Remove Link"><i class="ph-bold ph-link-break"></i></button>
                        </div>

                        <!-- Content Editable div instead of textarea -->
                        <div id="teacher-note-editor" class="note-editor" contenteditable="true" 
                            data-placeholder="Tuliskan catatan atau refleksi untuk hari ini..."></div>
                        
                        <div class="cal-form-actions">
                            <button id="clear-note-btn" class="btn-cal-secondary">
                                <i class="ph-bold ph-trash"></i> Hapus
                            </button>
                            <button id="save-note-btn" class="btn-cal-primary">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <!-- Full Calendar Modal -->
    <div class="cal-modal-overlay" id="full-calendar-modal">
        <div class="cal-modal">
            <div class="cal-modal-header">
                <h3><i class="ph-fill ph-calendar"></i> Kalender Lengkap</h3>
                <button class="cal-close-modal" id="close-calendar-modal"><i class="ph ph-x"></i></button>
            </div>
            <div class="cal-modal-body">
                <div class="cal-month-nav">
                    <button class="cal-nav-btn" id="prev-month-modal"><i class="ph-bold ph-caret-left"></i></button>
                    <div class="cal-month-display" id="modal-month-title">Januari 2026</div>
                    <button class="cal-nav-btn" id="next-month-modal"><i class="ph-bold ph-caret-right"></i></button>
                </div>
                <div class="cal-grid" id="modal-calendar-grid">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>
    </div>

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
            $('.note-format-btn').on('click', function(e) {
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
                $('.note-format-btn').removeClass('active');
                if (document.queryCommandState('bold')) $('.note-format-btn[data-cmd="bold"]').addClass('active');
                if (document.queryCommandState('italic')) $('.note-format-btn[data-cmd="italic"]').addClass('active');
                if (document.queryCommandState('underline')) $('.note-format-btn[data-cmd="underline"]').addClass('active');
                if (document.queryCommandState('insertUnorderedList')) $('.note-format-btn[data-cmd="insertUnorderedList"]').addClass('active');
                if (document.queryCommandState('insertOrderedList')) $('.note-format-btn[data-cmd="insertOrderedList"]').addClass('active');
            });

            // Events
            $('#calendar-trigger').on('click', function() {
                openCalendarModal(currentSelectedDate);
            });

            $('#close-calendar-modal, .cal-modal-overlay').on('click', function(e) {
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
                dayNames.forEach(d => html += `<div class="cal-day-name">${d}</div>`);

                // Previous month dates
                for (let i = firstDay; i > 0; i--) {
                    html += `<div class="cal-day other-month">${prevLastDate - i + 1}</div>`;
                }

                // Current month dates
                for (let i = 1; i <= lastDate; i++) {
                    const ds = formatDate(new Date(year, month, i));
                    const isT = ds === today ? 'today-highlight' : '';
                    const isS = ds === sel ? 'selected-day' : '';
                    const hasN = datesWithNotes.includes(ds);
                    
                    html += `
                        <div class="cal-day ${isT} ${isS} ${hasN ? 'has-note-marker' : ''}" 
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
                    container.html('<div class="note-empty-state">Belum ada catatan untuk tanggal ini.</div>');
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
                        <div class="cal-strip-day ${d.getDay() === 1 ? 'week-start' : ''}" onclick="window.selectDay(this, '${ds}')">
                            ${showW ? `<span class="cal-week-marker">W${wN}</span>` : ''}
                            <span class="strip-day-name">${days[d.getDay()]}</span>
                            <div class="strip-day-number ${isA} ${isT}">${i}</div>
                            ${hasN ? '<div class="cal-note-dot"></div>' : ''}
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