@section('title', 'Tasks List - '.$userguru->name)
<x-app-layout>
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/dashboard" title="Back">←</a>
            <h1 class="daily-tasks-title">{{ $userguru->name }}
                <div class="button-onlabel">
                    @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27" || Auth::user()->id == $userguru->id)
                    <a class="edit-onlabel" href="/user-tasks/{{$userguru->id}}" title="Edit"><i class="ph ph-pen"></i> <span>Edit</span></a>
                    <a class="edit-onlabel" href="/statistik/{{$userguru->id}}" title="Statistik"><i class="ph ph-chart-pie-slice"></i> <span>Statistik</span></a>
                    @endif
                </div>
            </h1>
        </div>

        <!-- Input tanggal -->
        <div class="center">
        <input 
            type="date"
            class="custom-input-field" 
            name="tanggal"
            id="taskDate" 
            value="{{ request('year', now()->year) }}-{{ str_pad(request('month', now()->month), 2, '0', STR_PAD_LEFT) }}-{{ str_pad(request('day', now()->day), 2, '0', STR_PAD_LEFT) }}"
            class="border p-2 rounded" max="{{ now()->format('Y-m-d') }}"
        /></div>

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
                        <input type="hidden" name="tanggal" id="tanggalHidden" value="{{ old('tanggal', now()->format('Y-m-d')) }}">
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
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}"
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
                <button class="task-skip-btn {{ $isSkipped ? 'skipped-task' : '' }}"
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}"
                    data-tanggal="{{ $customDate }}">
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
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}"
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
                <button class="task-skip-btn {{ $isSkipped ? 'skipped-task' : '' }}"
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}"
                    data-tanggal="{{ $weeklyDate }}">
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
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}"
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
                <button class="task-skip-btn {{ $isSkipped ? 'skipped-task' : '' }}"
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}"
                    data-tanggal="{{ $monthlyDate }}">
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
        document.addEventListener('DOMContentLoaded', function() {
            // --- Checklist Toggle ---
            document.querySelectorAll('.task-item-card').forEach(function(card) {
                card.addEventListener('click', function(e) {
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
            document.querySelectorAll('.task-skip-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
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
        $(".task-item-card").click(function(){
            alert('Maaf, Anda tidak memiliki akses centang!');
        });
    </script>
    @endif
    <script>
        // Add smooth hover effects and click animations
        document.addEventListener('DOMContentLoaded', function() {
            const taskCards = document.querySelectorAll('.task-item-card, .goal-item-card');
            taskCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
                card.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(-2px) scale(0.98)';
                });
                card.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('click', function(e) {
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

        // Date
        document.getElementById('taskDate').addEventListener('change', function() {
            let date = new Date(this.value);
            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            let pathParts = window.location.pathname.split('/');
            let taskId = pathParts[pathParts.length - 1]; 
            window.location.href = `/tasks/${taskId}?day=${day}&month=${month}&year=${year}`;
        });
        
        // Vanilla JS: copy saat submit
        document.getElementById('laporanForm').addEventListener('submit', function () {
        document.getElementById('tanggalHidden').value = document.getElementById('taskDate').value || '{{ now()->format("Y-m-d") }}';
        });
    </script>
</x-app-layout>