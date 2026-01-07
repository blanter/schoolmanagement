<x-app-layout>
    <div class="my-tasks-container">
        <!-- Header Profile Section -->
        <div class="page-header-unified">
            <div class="header-top">
                <a href="/dashboard" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
            </div>
            <div class="profile-section">
                <div class="profile-avatar">
                    @if($user->image)
                        <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $user->image }}"
                            alt="{{ $user->name }}" />
                    @else
                        <div class="avatar-placeholder">{{ substr($user->name, 0, 1) }}</div>
                    @endif
                    <div class="completion-badge">{{ $completionPercentage }}%</div>
                </div>
                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <p class="profile-role">{{ $user->role == 'guru' ? 'Guru' : ucfirst($user->role) }}</p>
                </div>
            </div>
        </div>

        <!-- My Tasks Title -->
        <div class="section-title">
            <h2>My Tasks</h2>
        </div>

        <!-- Task Categories List -->
        <div class="task-categories-list">
            @foreach($taskCategories as $category)
                <a href="{{ $category['route'] }}" class="task-category-item">
                    <div class="category-icon" style="background-color: {{ $category['color'] }}">
                        <i class="{{ $category['icon'] }}"></i>
                    </div>
                    <div class="category-info">
                        <div class="category-name">
                            {{ $category['name'] }}
                        </div>
                        <div class="category-description">
                            {{ $category['description'] }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Bottom Navigation -->
        <div class="bottom-navigation" style="justify-content: flex-end;">
            <a href="/semua-laporan" class="nav-btn-square" style="background: #10B981; color: white;">
                <i class="ph-bold ph-scroll"></i>
            </a>
            <a href="/teacher-calendar/{{ $user->id }}" class="nav-btn-square nav-btn-calendar">
                <i class="ph-bold ph-calendar"></i>
            </a>
        </div>
    </div>

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</x-app-layout>