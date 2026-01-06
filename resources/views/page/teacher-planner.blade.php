<x-app-layout>
    <div class="planner-container">
        <!-- Header Section -->
        <div class="page-header-unified center">
            <div class="header-top">
                <a href="/my-tasks/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Teacher Planner & Reflection</div>
                    <div class="header-subtitle">{{ $userguru->name }}</div>
                    <div style="font-size: 11px; color: rgba(255,255,255,0.7); margin-top: 5px; font-weight: 600;">
                        Periode: {{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }}
                    </div>
                </div>
            </div>

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

    </div>
</x-app-layout>