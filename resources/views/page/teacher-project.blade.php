<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section matching Teacher Planner theme but with Project features -->
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
                        <div class="project-check-box" onclick="toggleTaskCheck(this)"></div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #FFE7A0;">
                            <i class="ph-bold ph-file-text"></i>
                        </div>
                        <div class="project-task-label">Rumusan Masalah</div>
                        <div class="project-check-box" onclick="toggleTaskCheck(this)"></div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #A0C4FF;">
                            <i class="ph-bold ph-microscope"></i>
                        </div>
                        <div class="project-task-label">Penelitian</div>
                        <div class="project-check-box" onclick="toggleTaskCheck(this)"></div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #B9FBC0;">
                            <i class="ph-bold ph-check-square"></i>
                        </div>
                        <div class="project-task-label">Kesimpulan</div>
                        <div class="project-check-box" onclick="toggleTaskCheck(this)"></div>
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
                <div class="project-actions">
                    <button class="btn-teacher-project btn-teacher-project-grey">
                        <i class="ph-bold ph-plus"></i> Tambah Data
                    </button>
                    <button class="btn-teacher-project">
                        <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                    </button>
                </div>
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

        <!-- Bottom Navigation -->
        <div class="bottom-navigation">
            <a href="/my-tasks/{{ $user->id }}" class="nav-btn nav-btn-back">
                <i class="ph ph-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // 1. Initialize Calendar with today
            let currentSelectedDate = new Date();
            updateCalendarUI(currentSelectedDate);

            // Set initial value for month picker
            const year = currentSelectedDate.getFullYear();
            const month = String(currentSelectedDate.getMonth() + 1).padStart(2, '0');
            $('#date-picker').val(`${year}-${month}`);

            // 2. Tab Switching
            $('.tab-trigger').on('click', function () {
                const target = $(this).data('tab');

                $('.tab-trigger').removeClass('active');
                $(this).addClass('active');

                $('.tab-content-panel').hide();
                $('#content-' + target).fadeIn(300);
            });

            // 3. Calendar icon click handler - improved for better compatibility
            $('#calendar-trigger').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const datePicker = document.getElementById('date-picker');

                // Try modern showPicker() method first (Chrome, Edge, Safari 16+)
                if (datePicker && typeof datePicker.showPicker === 'function') {
                    try {
                        datePicker.showPicker();
                    } catch (error) {
                        // Fallback if showPicker fails
                        datePicker.focus();
                        datePicker.click();
                    }
                } else {
                    // Fallback for older browsers
                    datePicker.focus();
                    datePicker.click();
                }
            });

            // 4. Date Picker Filter Logic - using month picker
            $('#date-picker').on('change', function () {
                const selectedValue = $(this).val(); // Format: YYYY-MM
                if (selectedValue) {
                    const [year, month] = selectedValue.split('-');
                    const newDate = new Date(year, month - 1, 1);
                    currentSelectedDate = newDate;
                    updateCalendarUI(currentSelectedDate);
                }
            });

            // 5. Drag to scroll functionality for calendar strip
            const calendarStrip = document.getElementById('calendar-container');
            let isDown = false;
            let startX;
            let scrollLeft;

            calendarStrip.addEventListener('mousedown', (e) => {
                isDown = true;
                calendarStrip.style.cursor = 'grabbing';
                startX = e.pageX - calendarStrip.offsetLeft;
                scrollLeft = calendarStrip.scrollLeft;
            });

            calendarStrip.addEventListener('mouseleave', () => {
                isDown = false;
                calendarStrip.style.cursor = 'grab';
            });

            calendarStrip.addEventListener('mouseup', () => {
                isDown = false;
                calendarStrip.style.cursor = 'grab';
            });

            calendarStrip.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - calendarStrip.offsetLeft;
                const walk = (x - startX) * 2; // Scroll speed multiplier
                calendarStrip.scrollLeft = scrollLeft - walk;
            });

            // Touch support for mobile
            let touchStartX = 0;
            let touchScrollLeft = 0;

            calendarStrip.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].pageX - calendarStrip.offsetLeft;
                touchScrollLeft = calendarStrip.scrollLeft;
            }, { passive: true });

            calendarStrip.addEventListener('touchmove', (e) => {
                const x = e.touches[0].pageX - calendarStrip.offsetLeft;
                const walk = (x - touchStartX) * 2;
                calendarStrip.scrollLeft = touchScrollLeft - walk;
            }, { passive: true });
        });

        function updateCalendarUI(baseDate) {
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const realDays = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];

            const year = baseDate.getFullYear();
            const month = baseDate.getMonth();
            $('#display-month').text(months[month] + " " + year);

            // Get number of days in the month
            const lastDay = new Date(year, month + 1, 0).getDate();

            let calendarHtml = '';
            // Show all days of the selected month
            for (let i = 1; i <= lastDay; i++) {
                let d = new Date(year, month, i);

                // Active state: if it matches the baseDate's day
                let isTarget = i === baseDate.getDate() ? 'active' : '';

                calendarHtml += `
                    <div class="strip-day" onclick="selectStripDay(this, '${d.toISOString()}')">
                        <span class="strip-day-name">${realDays[d.getDay()]}</span>
                        <div class="strip-day-number ${isTarget}">${d.getDate()}</div>
                    </div>
                `;
            }
            $('#calendar-container').html(calendarHtml);

            // Auto-scroll to center the active day
            setTimeout(() => {
                const container = document.getElementById('calendar-container');
                const activeDay = container.querySelector('.strip-day-number.active');
                if (activeDay && activeDay.parentElement) {
                    const dayElement = activeDay.parentElement;
                    const containerWidth = container.offsetWidth;
                    const dayLeft = dayElement.offsetLeft;
                    const dayWidth = dayElement.offsetWidth;
                    const scrollPosition = dayLeft - (containerWidth / 2) + (dayWidth / 2);
                    container.scrollTo({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                }
            }, 100);
        }

        function selectStripDay(el, dateStr) {
            $('.strip-day-number').removeClass('active');
            $(el).find('.strip-day-number').addClass('active');

            // Pulse effect
            $(el).find('.strip-day-number').css('transform', 'scale(1.1)');
            setTimeout(() => {
                $(el).find('.strip-day-number').css('transform', 'scale(1)');
            }, 200);

            // Here you would typically load data for this specific date
            console.log("Selected date:", new Date(dateStr).toLocaleDateString('id-ID'));
        }

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