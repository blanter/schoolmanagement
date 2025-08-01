@section('title', 'Tasks List')
<x-app-layout>
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/dashboard" title="Back">←</a>
            <h1 class="daily-tasks-title">{{ $userguru->name }}
                <div class="button-onlabel">
                    <a class="edit-onlabel" href="/user-tasks/{{$userguru->id}}" title="Edit"><i class="ph ph-pen"></i> <span>Edit</span></a>
                    <a class="edit-onlabel" href="/statistik/2025/8/{{$userguru->id}}" title="Statistik"><i class="ph ph-chart-pie-slice"></i> <span>Statistik</span></a>
                </div>
            </h1>
        </div>

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
                <a href="#" 
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
                <a href="#" 
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
                <a href="#" 
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
                            proyek: el.dataset.proyek
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
    </script>
</x-app-layout>