<x-app-layout>
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/tasks/{{$userguru->id}}" title="Back">←</a>
            <h1 class="daily-tasks-title">Task Management - {{ $userguru->name }}</h1>
        </div>
        <div class="guru-task-mgmt-container">
            @foreach (['days' => 'Daily', 'week' => 'Weekly', 'month' => 'Monthly'] as $jenis => $label)
                <div class="guru-task-section-wrapper" data-jenis="{{ $jenis }}">
                    <div class="guru-task-section-header">
                        <h3 class="guru-task-section-title">{{ $label }} Tasks</h3>
                        <button class="guru-task-add-btn" data-jenis="{{ $jenis }}">+ Add New Task</button>
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
                                <input type="text" class="guru-task-title-field" value="{{ $task->judul_task }}" />
                                <button class="guru-task-update-btn">Update</button>
                                <button class="guru-task-delete-btn">Delete</button>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userId = '{{ $userguru->id }}';

        // Tambah Task
        document.querySelectorAll('.guru-task-add-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const jenis = btn.dataset.jenis;
                const container = btn.closest('.guru-task-section-wrapper').querySelector('.guru-task-list');
                const input = prompt("Enter new task title:");
                if (!input || input.trim() === '') return;

                // Show loading state
                btn.innerHTML = '<span class="guru-task-loading"></span> Adding...';
                btn.disabled = true;

                fetch(`/user-tasks/${userId}/store`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        jenis: jenis,
                        tipe: 'guru',
                        judul_task: input.trim()
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error adding task. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error. Please check your connection.');
                })
                .finally(() => {
                    btn.innerHTML = '+ Add New Task';
                    btn.disabled = false;
                });
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

                // Show loading state
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
                        // Visual feedback
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

                // Show loading state
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
                        // Smooth removal animation
                        li.style.transform = 'translateX(-100%)';
                        li.style.opacity = '0';
                        setTimeout(() => {
                            li.remove();
                            
                            // Check if list is empty and show empty state
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

        // Add Enter key support for input fields
        document.querySelectorAll('.guru-task-title-field').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const updateBtn = input.closest('.guru-task-item').querySelector('.guru-task-update-btn');
                    updateBtn.click();
                }
            });
        });
    });
    </script>
</x-app-layout>