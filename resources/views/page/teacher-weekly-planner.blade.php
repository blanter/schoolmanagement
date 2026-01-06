<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/teacher-planner/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Weekly Planner</div>
                    <div class="header-subtitle" id="display-month">Januari 2026</div>
                </div>
                <div class="calendar-filter-trigger" id="calendar-trigger">
                    <i class="ph-fill ph-calendar"></i>
                </div>
                <input type="month" id="date-picker" class="date-hidden-input">
            </div>

            <div class="cal-strip" id="calendar-container">
                <!-- Populated by JS -->
            </div>
        </header>

        <main class="project-main-content">
            <div class="calendar-content-wrapper">
                <!-- Top Section: List of Plans -->
                <div class="note-list-section">
                    <div class="note-section-header">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-calendar-check" style="color: #7F56D9;"></i> Agenda Tanggal Ini
                        </h3>
                        <button id="add-new-plan-btn" class="btn-cal-primary"
                            style="padding: 8px 16px; font-size: 12px; border-radius: 10px;">
                            <i class="ph-bold ph-plus"></i> Tambah Agenda
                        </button>
                    </div>

                    <div id="plans-list-container" class="note-list-scroll">
                        <!-- Populated by JS -->
                        <div class="note-empty-state">
                            <i class="ph-bold ph-calendar-blank"
                                style="font-size: 48px; color: #E5E7EB; margin-bottom: 10px;"></i>
                            <p style="color: #9CA3AF; font-size: 14px;">Pilih tanggal untuk melihat agenda</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Input Form -->
                <div id="plan-form-section" class="note-input-section" style="display: none;">
                    <div class="note-section-header">
                        <h3 id="form-title" style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">
                            <i class="ph-bold ph-plus-circle" style="color: #7F56D9;"></i> Tambah Agenda Baru
                        </h3>
                        <span id="selected-date-label"
                            style="font-size: 13px; font-weight: 600; color: #9CA3AF;">-</span>
                    </div>

                    <div class="note-form-card">
                        <!-- Hidden ID for editing -->
                        <input type="hidden" id="plan-id">

                        <div style="margin-bottom: 15px;">
                            <label
                                style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Mata
                                Pelajaran / Tema / Topik</label>
                            <input type="text" id="plan-subject" class="note-editor"
                                style="min-height: 45px; border-radius: 16px; margin-bottom: 0;"
                                placeholder="Contoh: Matematika - Aljabar">
                        </div>

                        <label
                            style="display: block; font-size: 12px; font-weight: 700; color: #4B5563; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Detail
                            Rencana / Catatan</label>
                        <div class="note-toolbar">
                            <button type="button" class="note-format-btn" data-cmd="bold" title="Bold"><i
                                    class="ph-bold ph-text-b"></i></button>
                            <button type="button" class="note-format-btn" data-cmd="italic" title="Italic"><i
                                    class="ph-bold ph-text-italic"></i></button>
                            <div class="note-toolbar-divider"></div>
                            <button type="button" class="note-format-btn" data-cmd="insertUnorderedList"
                                title="Bullet List"><i class="ph-bold ph-list-bullets"></i></button>
                            <button type="button" class="note-format-btn" data-cmd="insertOrderedList"
                                title="Numbered List"><i class="ph-bold ph-list-numbers"></i></button>
                        </div>
                        <div id="plan-note-editor" class="note-editor" contenteditable="true"
                            data-placeholder="Tuliskan detail rencana pembelajaran..."></div>

                        <div class="cal-form-actions">
                            <button id="cancel-plan-btn" class="btn-cal-secondary">Batal</button>
                            <button id="save-plan-btn" class="btn-cal-primary">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Agenda
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <!-- Full Calendar Modal -->
    <div class="cal-modal-overlay" id="full-calendar-modal">
        <div class="cal-modal">
            <div class="cal-modal-header">
                <h3><i class="ph-fill ph-calendar"></i> Pilih Tanggal</h3>
                <button class="cal-close-modal" id="close-calendar-modal"><i class="ph ph-x"></i></button>
            </div>
            <div class="cal-modal-body">
                <div class="cal-month-nav">
                    <button class="cal-nav-btn" id="prev-month-modal"><i class="ph-bold ph-caret-left"></i></button>
                    <div class="cal-month-display" id="modal-month-title">Januari 2026</div>
                    <button class="cal-nav-btn" id="next-month-modal"><i class="ph-bold ph-caret-right"></i></button>
                </div>
                <div class="cal-grid" id="modal-calendar-grid"></div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $(document).ready(function () {
            let currentSelectedDate = new Date();
            let datesWithPlans = @json($datesWithPlans);
            let currentPlans = [];
            let isSaving = false;

            updateCalendarUI(currentSelectedDate);
            loadPlans(formatDate(currentSelectedDate));

            const y = currentSelectedDate.getFullYear();
            const m = String(currentSelectedDate.getMonth() + 1).padStart(2, '0');
            $('#date-picker').val(`${y}-${m}`);

            // WYSIWYG
            $('.note-format-btn').on('click', function (e) {
                e.preventDefault();
                document.execCommand($(this).data('cmd'), false, null);
                $('#plan-note-editor').focus();
            });

            // Events
            $('#calendar-trigger').on('click', function () { openCalendarModal(currentSelectedDate); });
            $('#close-calendar-modal, .cal-modal-overlay').on('click', function (e) {
                if (e.target === this || e.target.closest('#close-calendar-modal')) $('#full-calendar-modal').fadeOut(200);
            });
            $('#prev-month-modal').on('click', function () { renderModalCalendar(new Date(parseInt($('#modal-month-title').data('year')), parseInt($('#modal-month-title').data('month')) - 1, 1)); });
            $('#next-month-modal').on('click', function () { renderModalCalendar(new Date(parseInt($('#modal-month-title').data('year')), parseInt($('#modal-month-title').data('month')) + 1, 1)); });

            $('#add-new-plan-btn').on('click', function () {
                resetForm();
                $('#plan-form-section').fadeIn();
                $('html, body').animate({ scrollTop: $('#plan-form-section').offset().top - 100 }, 500);
            });

            $('#cancel-plan-btn').on('click', function () { $('#plan-form-section').fadeOut(); });

            $('#save-plan-btn').on('click', function () {
                if (isSaving) return;
                const subject = $('#plan-subject').val();
                if (!subject) { showToast('Mata Pelajaran harus diisi', 'error'); return; }

                const note = $('#plan-note-editor').html();
                const cleanNote = ($('#plan-note-editor').text().trim() === '' && note.indexOf('<') === -1) ? '' : note;

                savePlan({
                    id: $('#plan-id').val(),
                    user_id: {{ $userguru->id }},
                    tanggal: formatDate(currentSelectedDate),
                    subject: subject,
                    note: cleanNote
                });
            });

            function renderModalCalendar(date) {
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
                const year = date.getFullYear();
                const month = date.getMonth();
                $('#modal-month-title').text(months[month] + " " + year).data('month', month).data('year', year);
                const firstDay = new Date(year, month, 1).getDay();
                const lastDate = new Date(year, month + 1, 0).getDate();
                const prevLastDate = new Date(year, month, 0).getDate();
                const today = formatDate(new Date());
                const sel = formatDate(currentSelectedDate);
                let html = '';
                dayNames.forEach(d => html += `<div class="cal-day-name">${d}</div>`);
                for (let i = firstDay; i > 0; i--) html += `<div class="cal-day other-month">${prevLastDate - i + 1}</div>`;
                for (let i = 1; i <= lastDate; i++) {
                    const ds = formatDate(new Date(year, month, i));
                    const hasP = datesWithPlans.includes(ds);
                    html += `<div class="cal-day ${ds === today ? 'today-highlight' : ''} ${ds === sel ? 'selected-day' : ''} ${hasP ? 'has-note-marker' : ''}" onclick="window.navigateFromModal('${ds}')">${i}</div>`;
                }
                $('#modal-calendar-grid').html(html);
            }

            function openCalendarModal(baseDate) { $('#full-calendar-modal').css('display', 'flex').hide().fadeIn(200); renderModalCalendar(baseDate); }
            window.navigateFromModal = function (ds) { currentSelectedDate = new Date(ds); updateCalendarUI(currentSelectedDate); loadPlans(ds); $('#full-calendar-modal').fadeOut(200); };

            function loadPlans(dateStr) {
                const display = new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                $('#selected-date-label').text(display);
                $('#plans-list-container').html('<div class="note-empty-state"><i class="ph-bold ph-spinner ph-spin" style="font-size: 32px;"></i><p>Memuat agenda...</p></div>');

                $.ajax({
                    url: '{{ route("teacher.weekly.get") }}',
                    method: 'GET',
                    data: { user_id: {{ $userguru->id }}, tanggal: dateStr },
                    dataType: 'json',
                    success: function (res) {
                        currentPlans = res.plans || [];
                        renderPlans(currentPlans);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        $('#plans-list-container').html('<div class="note-empty-state"><i class="ph-bold ph-warning-circle" style="font-size: 32px; color: #EF4444;"></i><p>Gagal memuat agenda. Coba segarkan halaman.</p></div>');
                    }
                });
            }

            function renderPlans(plans) {
                const container = $('#plans-list-container');
                if (!plans || plans.length === 0) {
                    container.html('<div class="note-empty-state"><i class="ph-bold ph-calendar-blank" style="font-size: 48px; color: #E5E7EB; margin-bottom: 10px;"></i><p style="color: #9CA3AF; font-size: 14px;">Belum ada agenda untuk hari ini.</p></div>');
                    return;
                }
                let html = '';
                plans.forEach(plan => {
                    html += `
                        <div class="note-item" style="position: relative;">
                            <div style="position: absolute; top: 15px; right: 15px; display: flex; gap: 8px;">
                                <button onclick="window.prepareEdit(${plan.id})" class="note-format-btn" style="background:#EBF4FF; color:#7F56D9;"><i class="ph-bold ph-pencil"></i></button>
                                <button onclick="window.confirmDelete(${plan.id})" class="note-format-btn" style="background:#FEE2E2; color:#EF4444;"><i class="ph-bold ph-trash"></i></button>
                            </div>
                            <div class="note-item-date" style="font-size: 15px; margin-bottom: 5px;">${plan.subject}</div>
                            <div class="note-item-content" style="font-size: 14px; opacity: 0.8;">${plan.note || '<i style="color:#9CA3AF">Tidak ada detail catatan</i>'}</div>
                        </div>
                    `;
                });
                container.html(html);
            }

            window.prepareEdit = function (id) {
                const plan = currentPlans.find(p => p.id === id);
                if (!plan) return;

                $('#plan-id').val(plan.id);
                $('#plan-subject').val(plan.subject);
                $('#plan-note-editor').html(plan.note || '');
                $('#form-title').html('<i class="ph-bold ph-pencil-circle" style="color: #7F56D9;"></i> Edit Agenda');
                $('#save-plan-btn').html('<i class="ph-bold ph-floppy-disk"></i> Update Agenda');
                $('#plan-form-section').fadeIn();
                $('html, body').animate({ scrollTop: $('#plan-form-section').offset().top - 100 }, 500);
            };

            window.confirmDelete = function (id) {
                if (!confirm('Yakin ingin menghapus agenda ini?')) return;
                $.ajax({
                    url: '{{ route("teacher.weekly.delete") }}',
                    method: 'POST',
                    data: { id: id, user_id: {{ $userguru->id }} },
                    success: function (res) {
                        showToast('Agenda dihapus', 'success');
                        datesWithPlans = res.datesWithPlans;
                        updateCalendarUI(currentSelectedDate);
                        loadPlans(formatDate(currentSelectedDate));
                        if ($('#plan-id').val() == id) resetForm();
                    }
                });
            };

            function savePlan(data) {
                isSaving = true;
                const btn = $('#save-plan-btn');
                btn.prop('disabled', true).html('<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...');
                $.ajax({
                    url: '{{ route("teacher.weekly.save") }}',
                    method: 'POST',
                    data: data,
                    success: function (res) {
                        showToast(data.id ? 'Agenda diperbarui' : 'Agenda ditambahkan', 'success');
                        datesWithPlans = res.datesWithPlans;
                        updateCalendarUI(currentSelectedDate);
                        loadPlans(data.tanggal);
                        $('#plan-form-section').fadeOut();
                    },
                    complete: function () { isSaving = false; btn.prop('disabled', false).html('<i class="ph-bold ph-floppy-disk"></i> Simpan Agenda'); }
                });
            }

            function resetForm() {
                $('#plan-id').val('');
                $('#plan-subject').val('');
                $('#plan-note-editor').html('');
                $('#form-title').html('<i class="ph-bold ph-plus-circle" style="color: #7F56D9;"></i> Tambah Agenda Baru');
                $('#save-plan-btn').html('<i class="ph-bold ph-floppy-disk"></i> Simpan Agenda');
            }

            function updateCalendarUI(baseDate) {
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                const days = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
                const y = baseDate.getFullYear(), m = baseDate.getMonth(), today = formatDate(new Date());
                $('#display-month').text(months[m] + " " + y);
                const last = new Date(y, m + 1, 0).getDate();
                let html = '';
                for (let i = 1; i <= last; i++) {
                    const d = new Date(y, m, i), ds = formatDate(d), isA = i === baseDate.getDate() ? 'active' : '', hasP = datesWithPlans.includes(ds);
                    const showW = (d.getDay() === 1 || i === 1), wN = Math.ceil((i + new Date(y, m, 1).getDay() - 1) / 7);
                    html += `<div class="cal-strip-day ${d.getDay() === 1 ? 'week-start' : ''}" onclick="window.selectDay(this, '${ds}')">${showW ? `<span class="cal-week-marker">W${wN}</span>` : ''}<span class="strip-day-name">${days[d.getDay()]}</span><div class="strip-day-number ${isA} ${ds === today ? 'today' : ''}">${i}</div>${hasP ? '<div class="cal-note-dot"></div>' : ''}</div>`;
                }
                $('#calendar-container').html(html);
                setTimeout(() => { const active = document.querySelector('.strip-day-number.active'); if (active) document.getElementById('calendar-container').scrollTo({ left: active.parentElement.offsetLeft - (document.getElementById('calendar-container').offsetWidth / 2) + 20, behavior: 'smooth' }); }, 100);
            }

            window.selectDay = function (el, ds) { currentSelectedDate = new Date(ds); $('.strip-day-number').removeClass('active'); $(el).find('.strip-day-number').addClass('active'); loadPlans(ds); };
            function formatDate(date) { return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`; }
            function showToast(msg, type) { const id = 'toast-' + Date.now(); const bg = type === 'success' ? '#D1FAE5' : '#FEE2E2'; $('#toast-container').append(`<div id="${id}" style="margin-bottom:10px; background:${bg}; padding:12px 20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-weight:600; font-size: 14px; color: #1F2937;">${msg}</div>`); setTimeout(() => { $(`#${id}`).fadeOut(300, function () { $(this).remove(); }); }, 2000); }
        });
    </script>
</x-app-layout>