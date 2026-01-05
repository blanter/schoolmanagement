@section('title', 'Statistik Data - ' . $thisuser->name)
<x-app-layout>
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/statistik/{{$thisuser->id}}" title="Back">←</a>
            <h1 class="daily-tasks-title tasks-title-mobile">Statistik {{$thisuser->name}} -
                {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</h1>
        </div>

        <div class="task-grid">
            @php
                $titles = [
                    'daily' => 'Daily Task',
                    'weekly' => 'Weekly Task',
                    'monthly' => 'Monthly Task',
                ];
            @endphp

            @foreach (['daily', 'weekly', 'monthly'] as $jenis)
                @php
                    $data = $summary[$jenis] ?? ['total' => 0, 'done' => 0, 'percentage' => 0];
                @endphp

                <div class="task-card loading" style="animation-delay: 0.{{ $loop->iteration }}s">
                    <div class="card-header">
                        <h2 class="card-title">{{ $titles[$jenis] }}</h2>
                    </div>

                    <div class="stats-container">
                        <div class="stat-item">
                            <div class="stat-label">Total Task</div>
                            <div class="stat-value total">{{ $data['total'] }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Diselesaikan</div>
                            <div class="stat-value done">{{ $data['done'] }}</div>
                        </div>
                    </div>

                    <div class="chart-container">
                        <canvas class="chart-canvas" id="chart-{{ $jenis }}" width="120" height="120"></canvas>
                    </div>

                    <div class="progress-section">
                        <div class="progress-header">
                            <span class="progress-label">Progress</span>
                            <span class="progress-percentage">{{ $data['percentage'] }}%</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" data-width="{{ $data['percentage'] }}%" style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="score-display">
                        <div class="score-text">Skor Pencapaian</div>
                        <div class="score-value">{{ $data['done'] }} dari {{ $data['total'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script src="{{asset('/js/chart.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const data = @json($summary);

            Object.keys(data).forEach(jenis => {
                const ctx = document.getElementById(`chart-${jenis}`);
                if (ctx) {
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Selesai', 'Belum'],
                            datasets: [{
                                data: [data[jenis].done, data[jenis].total - data[jenis].done],
                                backgroundColor: ['#4ade80', '#f87171'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            cutout: '70%',
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: true }
                            }
                        }
                    });
                }

                // Progress bar animation
                const bar = document.querySelector(`#chart-${jenis}`)?.closest('.task-card').querySelector('.progress-bar');
                if (bar) {
                    bar.style.width = bar.dataset.width;
                }
            });
        });
    </script>
</x-app-layout>