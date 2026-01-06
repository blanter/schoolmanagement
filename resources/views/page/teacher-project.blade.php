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
            <!-- Tabs -->
            <div class="tabs-wrapper">
                <button class="tab-trigger active" data-tab="penelitian">Karya Penelitian</button>
                <button class="tab-trigger" data-tab="video">Karya Video / DIY</button>
                <button class="tab-trigger" data-tab="barang">Pengadaan Barang</button>
            </div>

            <!-- Tab Content: Penelitian -->
            <div id="content-penelitian" class="tab-content-panel">
                <div class="task-list">
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #FEB2D3;">
                            <i class="ph-bold ph-book-open"></i>
                        </div>
                        <div class="project-task-label">Judul Pendahuluan</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }}"
                            @if(auth()->id() == $user->id) onclick="toggleTaskCheck(this)" @endif></div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #FFE7A0;">
                            <i class="ph-bold ph-file-text"></i>
                        </div>
                        <div class="project-task-label">Rumusan Masalah</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }}"
                            @if(auth()->id() == $user->id) onclick="toggleTaskCheck(this)" @endif></div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #A0C4FF;">
                            <i class="ph-bold ph-microscope"></i>
                        </div>
                        <div class="project-task-label">Penelitian</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }}"
                            @if(auth()->id() == $user->id) onclick="toggleTaskCheck(this)" @endif></div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #B9FBC0;">
                            <i class="ph-bold ph-check-square"></i>
                        </div>
                        <div class="project-task-label">Kesimpulan</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }}"
                            @if(auth()->id() == $user->id) onclick="toggleTaskCheck(this)" @endif></div>
                    </div>
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
        $(document).ready(function () {
            let currentSelectedDate = new Date();
            let datesWithPlans = []; // Teacher Project doesn't have dots yet

            // Initialize
            updateCalendarUI(currentSelectedDate);

            // Tab Switching
            $('.tab-trigger').on('click', function () {
                const target = $(this).data('tab');
                $('.tab-trigger').removeClass('active');
                $(this).addClass('active');
                $('.tab-content-panel').hide();
                $('#content-' + target).fadeIn(300);
            });

            // Events
            $('#calendar-trigger').on('click', function () { openCalendarModal(currentSelectedDate); });
            $('#close-calendar-modal, .cal-modal-overlay').on('click', function (e) {
                if (e.target === this || e.target.closest('#close-calendar-modal')) $('#full-calendar-modal').fadeOut(200);
            });
            $('#prev-month-modal').on('click', function () { renderModalCalendar(new Date(parseInt($('#modal-month-title').data('year')), parseInt($('#modal-month-title').data('month')) - 1, 1)); });
            $('#next-month-modal').on('click', function () { renderModalCalendar(new Date(parseInt($('#modal-month-title').data('year')), parseInt($('#modal-month-title').data('month')) + 1, 1)); });

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
                    const d = new Date(year, month, i);
                    const ds = formatDate(d);
                    const isWeekend = d.getDay() === 0 || d.getDay() === 6;
                    html += `<div class="cal-day ${ds === today ? 'today-highlight' : ''} ${ds === sel ? 'selected-day' : ''} ${isWeekend ? 'cal-is-weekend' : ''}" onclick="window.navigateFromModal('${ds}')">${i}</div>`;
                }
                $('#modal-calendar-grid').html(html);
            }

            function openCalendarModal(baseDate) { $('#full-calendar-modal').css('display', 'flex').hide().fadeIn(200); renderModalCalendar(baseDate); }
            window.navigateFromModal = function (ds) { currentSelectedDate = new Date(ds); updateCalendarUI(currentSelectedDate); $('#full-calendar-modal').fadeOut(200); };

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
                    const isWeekend = d.getDay() === 0 || d.getDay() === 6;
                    html += `
                        <div class="cal-strip-day ${d.getDay() === 1 ? 'week-start' : ''} ${isWeekend ? 'cal-is-weekend' : ''}" onclick="window.selectDay(this, '${ds}')">
                            ${showW ? `<span class="cal-week-marker">W${wN}</span>` : ''}
                            <span class="strip-day-name">${days[d.getDay()]}</span>
                            <div class="strip-day-number ${isA} ${ds === today ? 'today' : ''}">${i}</div>
                            ${hasP ? '<div class="cal-note-dot"></div>' : ''}
                        </div>`;
                }
                $('#calendar-container').html(html);
                setTimeout(() => {
                    const active = document.querySelector('.strip-day-number.active');
                    if (active) {
                        const container = document.getElementById('calendar-container');
                        container.scrollTo({ left: active.parentElement.offsetLeft - (container.offsetWidth / 2) + 20, behavior: 'smooth' });
                    }
                }, 100);
            }

            window.selectDay = function (el, ds) {
                currentSelectedDate = new Date(ds);
                $('.strip-day-number').removeClass('active');
                $(el).find('.strip-day-number').addClass('active');
                console.log("Selected date:", ds);
            };

            function formatDate(date) { return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`; }
        });

        function toggleTaskCheck(el) {
            $(el).toggleClass('checked');
            if ($(el).hasClass('checked')) {
                $(el).html('<i class="ph-bold ph-check"></i>');
            } else {
                $(el).empty();
            }
        }
    </script>
</x-app-layout>