@section('title', 'Tasks List - ' . $userguru->name)
<x-app-layout>
    <div class="task-management-container">
        <!-- Header Section -->
        <header class="page-header-unified center premium">
            <div class="header-top">
                <a href="/my-tasks/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Checklist Rutinitas</div>
                    <div class="header-subtitle">{{ $userguru->name }}</div>
                </div>
                <div class="header-actions-premium">
                    @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27" || Auth::user()->id == $userguru->id)
                        <a href="/user-tasks/{{$userguru->id}}" class="header-action-btn" title="Edit">
                            <i class="ph-bold ph-pencil-simple"></i>
                        </a>
                        <a href="/statistik/{{$userguru->id}}" class="header-action-btn" title="Statistik">
                            <i class="ph-bold ph-chart-pie-slice"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="calendar-navigation-wrapper">
                <button type="button" class="cal-nav-arrow prev" id="cal-prev">
                    <i class="ph ph-caret-left"></i>
                </button>
                <div class="cal-strip" id="calendar-container">
                    <!-- Populated by JS -->
                </div>
                <button type="button" class="cal-nav-arrow next" id="cal-next">
                    <i class="ph ph-caret-right"></i>
                </button>
            </div>
            <input type="month" id="date-picker-month" class="date-hidden-input">
        </header>

        <div style="margin-top: 30px;"></div>

        @if(Auth::user()->id == $userguru->id || Auth::user()->role == "admin")
            <!--<div class="section-divider"></div>
            <div class="daily-tasks-header">
                <h1 class="daily-tasks-title">Laporan Bulanan</h1>
            </div>
            <div class="task-items-wrapper">
                @if(Auth::user()->role != "admin")
                <form action="{{ route('laporan.store') }}" id="laporanForm" method="POST">
                    @csrf
                    <div class="task-laporan">
                        <div class="task-name-primary tnp-flex">Upload Laporan Bulanan
                        <div class="task-points">Point: {{$laporanBulanIni->point ?? 0}}</div></div>
                        <div class="task-status-text">
                            <input 
                                type="text" 
                                name="link" 
                                class="custom-input-field laporan-field" 
                                value="{{ $laporanBulanIni ? $laporanBulanIni->link : '' }}" 
                                placeholder="Masukkan Link Google Drive" 
                                required
                            />
                            <input type="hidden" name="tanggal" id="tanggalHidden" value="{{ $selectedDate->toDateString() }}">
                            <button type="submit" class="btn btn-primary btn-mini">
                                {{ $laporanBulanIni ? 'Update Laporan' : 'Kirim Laporan' }}
                            </button>
                            <a class="btn btn-primary btn-mini" href="/semua-laporan">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </form>
                @else
                <div class="task-laporan">
                    <div class="task-name-primary">Laporan Bulanan</div>
                        <div class="task-status-text input-copy-wrapper">
                            <input 
                                type="text" 
                                name="link" 
                                id="laporanLink" 
                                class="custom-input-field laporan-field" 
                                value="{{ $laporanBulanIni ? $laporanBulanIni->link : '' }}" 
                                placeholder="Link Google Drive" readonly 
                                required
                            />
                            <button type="button" class="copy-btn" onclick="copyLaporanLink()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>-->
        @endif

        <div class="section-divider"></div>

        <!-- Daily Tasks -->
        <div class="daily-tasks-header">
            <h1 class="daily-tasks-title">Daily Tasks</h1>
        </div>
        <div class="task-items-wrapper">
            @forelse($dailyTasks as $task)
                @php
                    $key = $task->jenis . '|' . $task->tipe . '|' . $task->judul_task;
                    $checked = $taskChecksToday->has($key);
                    $skipKey = $task->jenis . '|' . $task->tipe . '|' . $task->judul_task;
                    $isSkipped = $taskSkipsToday->has($skipKey);
                @endphp
                <div class="box-tasks">
                    <a href="javascript:;"
                        class="task-item-card {{ $checked ? 'completed-task' : 'pending-task' }} {{ $isSkipped ? 'skipped-task' : '' }}"
                        data-user="{{ $userguru->id }}" data-jenis="{{ $task->jenis }}" data-tipe="{{ $task->tipe }}"
                        data-task="{{ $task->judul_task }}" data-proyek="{{ $task->proyek }}"
                        data-tanggal="{{ $customDate }}">
                        <div class="completion-indicator"></div>
                        <div class="task-name-primary">{{ $task->judul_task }}</div>
                        <div class="task-status-text">{{ $checked ? 'Task completed' : 'Task undone' }}</div>
                        @if($task->tipe == "nonguru")
                            <div class="task-tipe">Non-Guru</div>
                        @endif
                    </a>
                    @if(!$checked)
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                            <!-- Tombol Skip -->
                            <button class="task-skip-btn {{ $isSkipped ? 'skipped-task' : '' }}" data-user="{{ $userguru->id }}"
                                data-jenis="{{ $task->jenis }}" data-tipe="{{ $task->tipe }}" data-task="{{ $task->judul_task }}"
                                data-proyek="{{ $task->proyek }}" data-tanggal="{{ $customDate }}">
                                @if($isSkipped) <i class="ph ph-prohibit-inset"></i> @else <i class="ph ph-prohibit"></i>@endif
                            </button>
                        @endif
                    @endif
                </div>
            @empty
                <p class="task-empty">Belum ada daily task</p>
            @endforelse
        </div>

        <div class="section-divider"></div>

        <!-- Weekly Tasks -->
        <div class="daily-tasks-header">
            <h1 class="daily-tasks-title">Weekly Tasks</h1>
        </div>
        <div class="task-items-wrapper">
            @forelse($weeklyTasks as $task)
                @php
                    $key = $task->jenis . '|' . $task->tipe . '|' . $task->judul_task;
                    $checked = $taskChecksThisWeek->has($key);
                    $skipKey = $task->jenis . '|' . $task->tipe . '|' . $task->judul_task;
                    $isSkipped = $taskSkipsThisWeek->has($skipKey);
                    $weeklyDate = $selectedDate->copy()->startOfWeek()->toDateString();
                @endphp
                <div class="box-tasks">
                    <a href="javascript:;"
                        class="task-item-card {{ $checked ? 'completed-task' : 'pending-task' }} {{ $isSkipped ? 'skipped-task' : '' }}"
                        data-user="{{ $userguru->id }}" data-jenis="{{ $task->jenis }}" data-tipe="{{ $task->tipe }}"
                        data-task="{{ $task->judul_task }}" data-proyek="{{ $task->proyek }}"
                        data-tanggal="{{ $weeklyDate }}">
                        <div class="completion-indicator"></div>
                        <div class="task-name-primary">{{ $task->judul_task }}</div>
                        <div class="task-status-text">{{ $checked ? 'Task completed' : 'Task undone' }}</div>
                        @if($task->tipe == "nonguru")
                            <div class="task-tipe">Non-Guru</div>
                        @endif
                    </a>
                    @if(!$checked)
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                            <!-- Tombol Skip -->
                            <button class="task-skip-btn {{ $isSkipped ? 'skipped-task' : '' }}" data-user="{{ $userguru->id }}"
                                data-jenis="{{ $task->jenis }}" data-tipe="{{ $task->tipe }}" data-task="{{ $task->judul_task }}"
                                data-proyek="{{ $task->proyek }}" data-tanggal="{{ $weeklyDate }}">
                                @if($isSkipped) <i class="ph ph-prohibit-inset"></i> @else <i class="ph ph-prohibit"></i>@endif
                            </button>
                        @endif
                    @endif
                </div>
            @empty
                <p class="task-empty">Belum ada weekly task</p>
            @endforelse
        </div>

        <div class="section-divider"></div>

        <!-- Monthly Tasks -->
        <div class="daily-tasks-header">
            <h1 class="daily-tasks-title">Monthly Tasks</h1>
        </div>
        <div class="task-items-wrapper">
            @forelse($monthlyTasks as $task)
                @php
                    $key = $task->jenis . '|' . $task->tipe . '|' . $task->judul_task;
                    $checked = $taskChecksThisMonth->has($key);
                    $skipKey = $task->jenis . '|' . $task->tipe . '|' . $task->judul_task;
                    $isSkipped = $taskSkipsThisMonth->has($skipKey);
                    $monthlyDate = $selectedDate->copy()->startOfMonth()->toDateString();
                @endphp
                <div class="box-tasks">
                    <a href="javascript:;"
                        class="task-item-card {{ $checked ? 'completed-task' : 'pending-task' }} {{ $isSkipped ? 'skipped-task' : '' }}"
                        data-user="{{ $userguru->id }}" data-jenis="{{ $task->jenis }}" data-tipe="{{ $task->tipe }}"
                        data-task="{{ $task->judul_task }}" data-proyek="{{ $task->proyek }}"
                        data-tanggal="{{ $monthlyDate }}">
                        <div class="completion-indicator"></div>
                        <div class="task-name-primary">{{ $task->judul_task }}</div>
                        <div class="task-status-text">{{ $checked ? 'Task completed' : 'Task undone' }}</div>
                        @if($task->tipe == "nonguru")
                            <div class="task-tipe">Non-Guru</div>
                        @endif
                    </a>
                    @if(!$checked)
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                            <!-- Tombol Skip -->
                            <button class="task-skip-btn {{ $isSkipped ? 'skipped-task' : '' }}" data-user="{{ $userguru->id }}"
                                data-jenis="{{ $task->jenis }}" data-tipe="{{ $task->tipe }}" data-task="{{ $task->judul_task }}"
                                data-proyek="{{ $task->proyek }}" data-tanggal="{{ $monthlyDate }}">
                                @if($isSkipped) <i class="ph ph-prohibit-inset"></i> @else <i class="ph ph-prohibit"></i>@endif
                            </button>
                        @endif
                    @endif
                </div>
            @empty
                <p class="task-empty">Belum ada monthly task</p>
            @endforelse
        </div>
    </div>

    @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- Checklist Toggle ---
                document.querySelectorAll('.task-item-card').forEach(function (card) {
                    card.addEventListener('click', function (e) {
                        e.preventDefault();
                        // ✅ Cek kalau task sudah skipped → tidak bisa di-klik checklist
                        if (this.classList.contains('skipped-task')) {
                            return; // langsung keluar
                        }
                        const el = this;
                        const getSkip = el.closest('.box-tasks').querySelector('.task-skip-btn');
                        fetch('{{ route('task.check') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                user_id: el.dataset.user,
                                jenis: el.dataset.jenis,
                                tipe: el.dataset.tipe,
                                judul_task: el.dataset.task,
                                proyek: el.dataset.proyek,
                                tanggal: el.dataset.tanggal
                            })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'done') {
                                    el.classList.remove('pending-task');
                                    el.classList.add('completed-task');
                                    el.querySelector('.task-status-text').innerText = 'Task completed';
                                    getSkip.style.display = 'none';
                                } else {
                                    el.classList.remove('completed-task');
                                    el.classList.add('pending-task');
                                    el.querySelector('.task-status-text').innerText = 'Task undone';
                                    getSkip.style.display = 'inline-block';
                                }
                            });
                    });
                });

                // --- Skip Toggle ---
                document.querySelectorAll('.task-skip-btn').forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation(); // Jangan trigger checklist
                        const el = this;
                        const parentCard = el.closest('.box-tasks').querySelector('.task-item-card');
                        fetch('{{ route('task.skip') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                user_id: el.dataset.user,
                                jenis: el.dataset.jenis,
                                tipe: el.dataset.tipe,
                                judul_task: el.dataset.task,
                                proyek: el.dataset.proyek,
                                tanggal: el.dataset.tanggal
                            })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'done') {
                                    el.innerHTML = '<i class="ph ph-prohibit-inset"></i>';
                                    el.classList.add('skipped-task');
                                    parentCard.classList.add('skipped-task'); // ✅ tandai card skip
                                } else {
                                    el.innerHTML = '<i class="ph ph-prohibit"></i>';
                                    el.classList.remove('skipped-task');
                                    parentCard.classList.remove('skipped-task'); // ✅ hapus tanda skip
                                }
                            });
                    });
                });
            });
        </script>
    @else
        <script>
            // No Access
            $(".task-item-card").click(function () {
                alert('Maaf, Anda tidak memiliki akses centang!');
            });
        </script>
    @endif
    <script>
        // Add smooth hover effects and click animations
        document.addEventListener('DOMContentLoaded', function () {
            const taskCards = document.querySelectorAll('.task-item-card, .goal-item-card');
            taskCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
                card.addEventListener('mousedown', function () {
                    this.style.transform = 'translateY(-2px) scale(0.98)';
                });
                card.addEventListener('mouseup', function () {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    // Add ripple effect
                    const ripple = document.createElement('div');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.left = (e.clientX - this.getBoundingClientRect().left) + 'px';
                    ripple.style.top = (e.clientY - this.getBoundingClientRect().top) + 'px';
                    ripple.style.width = ripple.style.height = '20px';
                    ripple.style.marginLeft = ripple.style.marginTop = '-10px';
                    this.appendChild(ripple);
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Task Calendar Script
        $(document).ready(function () {
            let currentSelectedDate = new Date('{{ $selectedDate->toDateString() }}');
            let doneDates = @json($doneDates);

            // Initialize
            updateCalendarUI(currentSelectedDate);

            // Toggle month picker
            $('.header-title-container').on('click', function () {
                $('#date-picker-month').click();
            });

            $('#date-picker-month').on('change', function () {
                const val = $(this).val();
                if (val) {
                    const [year, month] = val.split('-');
                    window.location.href = `/tasks/{{ $userguru->id }}?day=1&month=${parseInt(month)}&year=${year}`;
                }
            });

            // Navigation scroll events
            $('#cal-prev').on('click', function () {
                const container = document.getElementById('calendar-container');
                if (container) container.scrollBy({ left: -300, behavior: 'smooth' });
            });

            $('#cal-next').on('click', function () {
                const container = document.getElementById('calendar-container');
                if (container) container.scrollBy({ left: 300, behavior: 'smooth' });
            });

            function formatDate(date) {
                return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
            }

            function updateCalendarUI(baseDate) {
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                const days = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
                const y = baseDate.getFullYear();
                const m = baseDate.getMonth();
                const todayStr = formatDate(new Date());

                $('.header-main-title').text('Checklist Rutinitas');
                $('.header-subtitle').text(months[m] + " " + y);

                const last = new Date(y, m + 1, 0).getDate();
                let html = '';

                for (let i = 1; i <= last; i++) {
                    const d = new Date(y, m, i);
                    const ds = formatDate(d);
                    const isA = i === baseDate.getDate() ? 'active' : '';
                    const isT = ds === todayStr ? 'today' : '';
                    const hasD = doneDates.includes(ds);
                    const isWeekend = d.getDay() === 0 || d.getDay() === 6;

                    // Show W marker on Mondays or 1st day
                    const showW = (d.getDay() === 1 || i === 1);
                    const wN = Math.ceil((i + new Date(y, m, 1).getDay() - 1) / 7);

                    let dotHtml = '';
                    if (hasD) {
                        dotHtml = '<div class="cal-dot cal-dot-filled"></div>';
                    } else if (!isWeekend && d <= new Date()) {
                        // Only show red if it's a weekday and in the past/today
                        dotHtml = '<div class="cal-dot cal-dot-empty"></div>';
                    }

                    html += `
                        <div class="cal-strip-day ${d.getDay() === 1 ? 'week-start' : ''} ${isWeekend ? 'cal-is-weekend' : ''}" onclick="window.selectDay('${ds}')">
                            ${showW ? `<span class="cal-week-marker">W${wN}</span>` : ''}
                            <span class="strip-day-name">${days[d.getDay()]}</span>
                            <div class="strip-day-number ${isA} ${isT}">${i}</div>
                            ${dotHtml}
                        </div>
                    `;
                }
                $('#calendar-container').html(html);

                // Auto scroll to active/today
                setTimeout(() => {
                    const active = document.querySelector('.strip-day-number.active') || document.querySelector('.strip-day-number.today');
                    if (active) {
                        const container = document.getElementById('calendar-container');
                        if (container) {
                            container.scrollTo({ left: active.parentElement.offsetLeft - (container.offsetWidth / 2) + 20, behavior: 'smooth' });
                        }
                    }
                }, 100);
            }

            window.selectDay = function (ds) {
                const [year, month, day] = ds.split('-');
                window.location.href = `/tasks/{{ $userguru->id }}?day=${parseInt(day)}&month=${parseInt(month)}&year=${year}`;
            };
        });

        // Copy
        function copyLaporanLink() {
            const input = document.getElementById("laporanLink");
            input.select();
            input.setSelectionRange(0, 99999); // untuk mobile
            navigator.clipboard.writeText(input.value).then(() => {
                alert("Link berhasil disalin!");
            }).catch(err => {
                alert("Gagal menyalin link.");
            });
        }
    </script>
</x-app-layout>