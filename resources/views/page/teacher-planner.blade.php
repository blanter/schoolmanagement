<x-app-layout>
    <div class="planner-container">
        <!-- Header Section -->
        <div class="page-header-unified center">
            <h1>Teacher Planner & Reflection</h1>

            <div class="planner-progress-wrapper">
                <div class="planner-progress-bar">
                    <div class="planner-progress-fill" style="width: {{ $completionPercentage }}%"></div>
                </div>
                <div class="planner-percentage">{{ $completionPercentage }}%</div>
            </div>
        </div>

        <!-- Grid Menu Section -->
        <div class="planner-grid">
            @foreach($plannerItems as $item)
                <a href="{{ $item['route'] }}" class="planner-item">
                    <div class="planner-icon-circle" style="background-color: {{ $item['color'] }}">
                        <i class="{{ $item['icon'] }}"></i>
                    </div>
                    <div class="planner-label">{{ $item['name'] }}</div>
                </a>
            @endforeach
        </div>

        <!-- Bottom Navigation (Reuse style from my-tasks) -->
        <div class="bottom-navigation">
            <a href="/my-tasks/{{ $userguru->id }}" class="nav-btn nav-btn-back">
                <i class="ph ph-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>
</x-app-layout>