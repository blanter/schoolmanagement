@section('title', 'Task Management - '.$userguru->name)
<x-app-layout>
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/tasks/{{$userguru->id}}" title="Back">←</a>
            <h1 class="daily-tasks-title tasks-title-mobile">Task Management - {{ $userguru->name }}</h1>
        </div>
        <div class="guru-task-mgmt-container">
            @foreach (['days' => 'Daily', 'week' => 'Weekly', 'month' => 'Monthly'] as $jenis => $label)
                <div class="guru-task-section-wrapper" data-jenis="{{ $jenis }}">
                    <div class="guru-task-section-header">
                        <h3 class="guru-task-section-title">{{ $label }} Tasks</h3>
                        <button class="guru-task-add-btn" data-jenis="{{ $jenis }}" onclick="showTaskModal()">+ Add New Task</button>
                    </div>

                    <ul class="guru-task-list">
                        @php
                            $tasks = match($jenis) {
                                'days' => $dailyTasks,
                                'week' => $weeklyTasks,
                                'month' => $monthlyTasks,
                                default => collect()
                            };
                        @endphp
                        
                        @forelse ($tasks as $task)
                            <li class="guru-task-item" data-id="{{ $task->id }}">
                                @if($task->tipe == "nonguru")
                                <div class="label-type">Non-Guru</div>
                                @endif
                                <input type="text" class="guru-task-title-field" value="{{ $task->judul_task }}" />
                                @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                                <button class="guru-task-update-btn">Update</button>
                                <button class="guru-task-delete-btn">Delete</button>
                                @endif
                                @if($task->user_id == Auth::user()->id && $task->proyek == "pribadi")
                                <button class="guru-task-update-btn">Update</button>
                                <button class="guru-task-delete-btn">Delete</button>
                                @endif
                            </li>
                        @empty
                            <li class="guru-task-empty-state">
                                No {{ strtolower($label) }} tasks yet. Click "Add New Task" to get started!
                            </li>
                        @endforelse
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Task Modal -->
    <div class="notification-modal-overlay" id="taskModal">
        <div class="notification-modal-container">
            <button class="notification-modal-close" onclick="hideTaskModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="notification-modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M320 64C334.7 64 348.2 72.1 355.2 85L571.2 485C577.9 497.4 577.6 512.4 570.4 524.5C563.2 536.6 550.1 544 536 544L104 544C89.9 544 76.9 536.6 69.6 524.5C62.3 512.4 62.1 497.4 68.8 485L284.8 85C291.8 72.1 305.3 64 320 64zM320 232C306.7 232 296 242.7 296 256L296 368C296 381.3 306.7 392 320 392C333.3 392 344 381.3 344 368L344 256C344 242.7 333.3 232 320 232zM346.7 448C347.3 438.1 342.4 428.7 333.9 423.5C325.4 418.4 314.7 418.4 306.2 423.5C297.7 428.7 292.8 438.1 293.4 448C292.8 457.9 297.7 467.3 306.2 472.5C314.7 477.6 325.4 477.6 333.9 472.5C342.4 467.3 347.3 457.9 346.7 448z"/></svg>
            </div>

            <h3 class="notification-modal-title">Tambah Task Baru</h3>

            <div class="notification-modal-form">
                <select class="custom-input-field" id="taskTipeInput">
                    <option value="" hidden selected>-Pilih Tipe-</option>
                    <option value="guru">Guru</option>
                    <option value="nonguru">Non-Guru</option>
                </select>
                <input type="text" class="custom-input-field" id="taskTitleInput" placeholder="Judul Task">
                <input type="hidden" id="taskJenisInput">
            </div>

            <div class="notification-modal-actions">
                <a href="javascript:;" class="notification-modal-btn notification-modal-btn-cancel" onclick="hideTaskModal()">Batal</a>
                <a href="javascript:;" class="notification-modal-btn notification-modal-btn-confirm">Simpan</a>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = '{{ $userguru->id }}';
        // Tambah Task
        document.querySelectorAll('.guru-task-add-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const jenis = btn.dataset.jenis;
                document.getElementById('taskJenisInput').value = jenis;
                document.getElementById('taskTipeInput').value = '';
                document.getElementById('taskTitleInput').value = '';

                // Attach submit event hanya sekali (tidak double listener)
                const confirmBtn = document.querySelector('.notification-modal-btn-confirm');
                confirmBtn.onclick = function () {
                    const userId = '{{ $userguru->id }}';
                    const title = document.getElementById('taskTitleInput').value.trim();
                    const jenis = document.getElementById('taskJenisInput').value;
                    const tipe = document.getElementById('taskTipeInput').value.trim();

                    if (!title) {
                        alert('Judul task tidak boleh kosong.');
                        return;
                    }
                    if (!tipe) {
                        alert('Tipe task tidak boleh kosong.');
                        return;
                    }

                    confirmBtn.textContent = 'Menyimpan...';
                    confirmBtn.disabled = true;

                    fetch(`/user-tasks/${userId}/store`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            jenis: jenis,
                            tipe: tipe,
                            judul_task: title,
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal menyimpan task.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan jaringan.');
                    })
                    .finally(() => {
                        confirmBtn.textContent = 'Simpan';
                        confirmBtn.disabled = false;
                    });
                };
            });
        });

        // Update Task
        document.querySelectorAll('.guru-task-update-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const li = btn.closest('.guru-task-item');
                const taskId = li.dataset.id;
                const input = li.querySelector('.guru-task-title-field');
                const newTitle = input.value.trim();

                if (!newTitle) {
                    alert('Task title cannot be empty!');
                    return;
                }

                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="guru-task-loading"></span>';
                btn.disabled = true;

                fetch(`/user-tasks/${taskId}/update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        judul_task: newTitle
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        li.style.backgroundColor = '#dcfce7';
                        setTimeout(() => {
                            li.style.backgroundColor = '';
                        }, 1000);
                        alert('Task updated successfully!');
                    } else {
                        alert('Error updating task. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please check your connection.');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
        });

        // Delete Task
        document.querySelectorAll('.guru-task-delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const li = btn.closest('.guru-task-item');
                const taskId = li.dataset.id;
                const taskTitle = li.querySelector('.guru-task-title-field').value;

                if (!confirm(`Are you sure you want to delete "${taskTitle}"?`)) return;

                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="guru-task-loading"></span>';
                btn.disabled = true;

                fetch(`/user-tasks/${taskId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        li.style.transform = 'translateX(-100%)';
                        li.style.opacity = '0';
                        setTimeout(() => {
                            li.remove();

                            const taskList = li.closest('.guru-task-list');
                            if (taskList.children.length === 0) {
                                const emptyState = document.createElement('li');
                                emptyState.className = 'guru-task-empty-state';
                                emptyState.textContent = 'No tasks yet. Click "Add New Task" to get started!';
                                taskList.appendChild(emptyState);
                            }
                        }, 300);
                    } else {
                        alert('Error deleting task. Please try again.');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please check your connection.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
        });

        // Support tekan Enter untuk update
        document.querySelectorAll('.guru-task-title-field').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const updateBtn = input.closest('.guru-task-item').querySelector('.guru-task-update-btn');
                    updateBtn.click();
                }
            });
        });
    });

    // Show Task Modal
    function showTaskModal() {
        $("#taskModal").addClass("nm-active");
    }

    function hideTaskModal() {
        $("#taskModal").removeClass("nm-active");
    }
</script>
</x-app-layout>