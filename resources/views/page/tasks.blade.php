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
            id="taskDate" 
            value="{{ request('year', now()->year) }}-{{ str_pad(request('month', now()->month), 2, '0', STR_PAD_LEFT) }}-{{ str_pad(request('day', now()->day), 2, '0', STR_PAD_LEFT) }}"
            class="border p-2 rounded"
        /></div>

        @if(Auth::user()->id == $userguru->id || Auth::user()->role == "admin")
        <div class="section-divider"></div>
        <!-- Laporan Bulanan -->
        <div class="daily-tasks-header">
            <h1 class="daily-tasks-title">Laporan Bulanan</h1>
        </div>
        <div class="task-items-wrapper">
            @php
                $month = \Carbon\Carbon::now()->month;
                $year = \Carbon\Carbon::now()->year;
                $laporanBulanIni = \App\Models\Laporan::where('user_id', auth()->id())
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();
            @endphp
            @if(Auth::user()->role != "admin")
            <form action="{{ route('laporan.store') }}" method="POST">
                @csrf
                <div class="task-laporan">
                    <div class="task-name-primary">Upload Laporan Bulanan</div>
                    <div class="task-status-text">
                        <input 
                            type="text" 
                            name="link" 
                            class="custom-input-field laporan-field" 
                            value="{{ $laporanBulanIni ? $laporanBulanIni->link : '' }}" 
                            placeholder="Masukkan Link Google Drive" 
                            required
                        />
                        <button type="submit" class="btn btn-primary btn-mini">
                            {{ $laporanBulanIni ? 'Update Laporan' : 'Kirim Laporan' }}
                        </button>
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
        </div>
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
                @endphp
                <a href="javascript:;" 
                    class="task-item-card {{ $checked ? 'completed-task' : 'pending-task' }}" 
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
                @endphp
                <a href="javascript:;" 
                    class="task-item-card {{ $checked ? 'completed-task' : 'pending-task' }}" 
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}">
                    <div class="completion-indicator"></div>
                    <div class="task-name-primary">{{ $task->judul_task }}</div>
                    <div class="task-status-text">{{ $checked ? 'Task completed' : 'Task undone' }}</div>
                </a>
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
                @endphp
                <a href="javascript:;" 
                    class="task-item-card {{ $checked ? 'completed-task' : 'pending-task' }}" 
                    data-user="{{ $userguru->id }}"
                    data-jenis="{{ $task->jenis }}"
                    data-tipe="{{ $task->tipe }}"
                    data-task="{{ $task->judul_task }}"
                    data-proyek="{{ $task->proyek }}">
                    <div class="completion-indicator"></div>
                    <div class="task-name-primary">{{ $task->judul_task }}</div>
                    <div class="task-status-text">{{ $checked ? 'Task completed' : 'Task undone' }}</div>
                </a>
            @empty
                <p class="task-empty">Belum ada monthly task</p>
            @endforelse
        </div>
    </div>

    @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
    <script>
        // Checklist Toggle Script
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.task-item-card').forEach(function(card) {
                card.addEventListener('click', function(e) {
                    e.preventDefault();

                    const el = this;

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
                        } else {
                            el.classList.remove('completed-task');
                            el.classList.add('pending-task');
                            el.querySelector('.task-status-text').innerText = 'Task undone';
                        }
                    });
                });
            });
        });
    </script>
    @else
    <script>
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
            let month = date.getMonth() + 1; // bulan dimulai dari 0
            let year = date.getFullYear();

            // Ambil ID dari URL sekarang (/tasks/{id})
            let pathParts = window.location.pathname.split('/');
            let taskId = pathParts[pathParts.length - 1]; 

            // Redirect ke URL dengan parameter day, month, year
            window.location.href = `/tasks/${taskId}?day=${day}&month=${month}&year=${year}`;
        });
    </script>
</x-app-layout>