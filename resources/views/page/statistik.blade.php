@section('title', 'Statistik - '.$thisuser->name)
<x-app-layout>
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/tasks/{{$thisuser->id}}" title="Back">←</a>
            <h1 class="daily-tasks-title tasks-title-mobile">Statistik {{$thisuser->name}}</h1>
        </div>

        <div class="statistik-calendar">
            <div class="calendar-grid-container">
                @php
                    $months = [
                        1 => ['name' => 'Januari', 'english' => 'January'],
                        2 => ['name' => 'Februari', 'english' => 'February'],
                        3 => ['name' => 'Maret', 'english' => 'March'],
                        4 => ['name' => 'April', 'english' => 'April'],
                        5 => ['name' => 'Mei', 'english' => 'May'],
                        6 => ['name' => 'Juni', 'english' => 'June'],
                        7 => ['name' => 'Juli', 'english' => 'July'],
                        8 => ['name' => 'Agustus', 'english' => 'August'],
                        9 => ['name' => 'September', 'english' => 'September'],
                        10 => ['name' => 'Oktober', 'english' => 'October'],
                        11 => ['name' => 'November', 'english' => 'November'],
                        12 => ['name' => 'Desember', 'english' => 'December']
                    ];
                    
                    $currentYear = date('Y');
                    $currentMonth = (int) date('n'); // numeric month without leading zeros
                @endphp

                @foreach($months as $monthNumber => $monthData)
                    <div class="month-card-wrapper month-{{ strtolower($monthData['english']) }}" 
                        data-month="{{ $monthNumber }}">
                        <a href="/statistik/{{ $currentYear }}/{{ $monthNumber }}/{{ $thisuser->id }}" 
                        class="month-card-link">
                            <div class="month-card-box">
                                <div class="month-text-content">
                                    <div class="month-name-primary">{{ $monthData['name'] }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Get current month dari JavaScript Date object
            var now = new Date();
            var currentMonth = now.getMonth() + 1; // getMonth() returns 0-11, so add 1 for 1-12
            
            // Add active class to current month
            $('.month-card-wrapper[data-month="' + currentMonth + '"]').addClass('active');
            
            // Debug: Log current month
            console.log('Current month detected: ' + currentMonth);
            
            // Optional: Update active state setiap menit untuk real-time update
            setInterval(function() {
                var newDate = new Date();
                var newMonth = newDate.getMonth() + 1;
                
                // Jika bulan berubah, update active state
                if (newMonth !== currentMonth) {
                    $('.month-card-wrapper').removeClass('active');
                    $('.month-card-wrapper[data-month="' + newMonth + '"]').addClass('active');
                    currentMonth = newMonth;
                    console.log('Month changed to: ' + currentMonth);
                }
            }, 60000); // Check every minute
        });
    </script>
</x-app-layout>