<x-app-layout>
    <div class="leaderboard-container">
        <!-- HEADER -->
        <div class="leaderboard-header">
            <h1 class="leaderboard-title">Leaderboard</h1>
        </div>

        <!-- TABS -->
        <div class="time-filter">
            <button class="filter-btn active" data-tab="weekly">Guru</button>
            <button class="filter-btn" data-tab="alltime">Non-Guru</button>
        </div>

        @if(Auth::user()->role == "admin")
        <div class="performance-banner">
            <div class="performance-text">
                Hello, Welcome Admin!
            </div>
        </div>
        @endif

        <!-- Tab Content 1 -->
        <div id="weekly-content" class="tab-content active">
            @if(Auth::user()->role == "guru")
            <div class="performance-banner">
                <div class="performance-rank">#{{ $rankNumberGuru ?? '-' }}</div>
                <div class="performance-text">
                    You are doing better than<br>{{ $betterThanGuru }}% of other guru players!
                </div>
            </div>
            @endif

            <div class="time-info">
                <span class="time-icon">🕐</span>
                <span>{{ today()->format('d M Y') }}</span>
            </div>

            <div class="podium-section">
                {{-- Podium Kedua (Rank 2) - KIRI --}}
                @if(isset($userguru[1]))
                <div class="podium-player podium-second">
                    <div class="podium-avatar">
                        <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $userguru[1]['image'] }}"/>
                    </div>
                    <div class="podium-name">
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                        <a href="/tasks/{{ $userguru[1]['id'] }}">{{ $userguru[1]['name'] }}</a>
                        @else
                        {{ $userguru[1]['name'] }}
                        @endif
                    </div>
                    <div class="podium-points">{{ number_format($userguru[1]['total_amount']) }} GP</div>
                    <div class="podium-base">2</div>
                </div>
                @endif

                {{-- Podium Pertama (Rank 1) - TENGAH --}}
                @if(isset($userguru[0]))
                <div class="podium-player podium-first">
                    <div class="winner-crown">👑</div>
                    <div class="podium-avatar">
                        <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $userguru[0]['image'] }}"/>
                    </div>
                    <div class="podium-name">
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                        <a href="/tasks/{{ $userguru[0]['id'] }}">{{ $userguru[0]['name'] }}</a>
                        @else
                        {{ $userguru[0]['name'] }}
                        @endif
                    </div>
                    <div class="podium-points">{{ number_format($userguru[0]['total_amount']) }} GP</div>
                    <div class="podium-base">1</div>
                </div>
                @endif

                {{-- Podium Ketiga (Rank 3) - KANAN --}}
                @if(isset($userguru[2]))
                <div class="podium-player podium-third">
                    <div class="podium-avatar">
                        <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $userguru[2]['image'] }}"/>
                    </div>
                    <div class="podium-name">
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                        <a href="/tasks/{{ $userguru[2]['id'] }}">{{ $userguru[2]['name'] }}</a>
                        @else
                        {{ $userguru[2]['name'] }}
                        @endif
                    </div>
                    <div class="podium-points">{{ number_format($userguru[2]['total_amount']) }} GP</div>
                    <div class="podium-base">3</div>
                </div>
                @endif
            </div>

            <div class="other-players">
                @foreach($userguru->slice(3) as $index => $user)
                    <div class="player-item">
                        <div class="player-rank">{{ $index + 1 }}</div>
                        <div class="player-avatar-small">
                                <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $user['image'] }}"/>
                        </div>
                        <div class="player-info">
                            <div class="player-name">
                                @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                                <a href="/tasks/{{ $user['id'] }}">{{ $user['name'] }}</a>
                                @else
                                {{ $user['name'] }}
                                @endif
                            </div>
                            <div class="player-points">{{ number_format($user->total_amount) }} points</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tab Content 2 -->
        <div id="alltime-content" class="tab-content">
            @if(Auth::user()->role == "guru")
            <div class="performance-banner">
                <div class="performance-rank">#{{ $rankNumberNonguru ?? '-' }}</div>
                <div class="performance-text">
                    You are doing better than<br>{{ $betterThanNonguru }}% of other nonguru players!
                </div>
            </div>
            @endif

            <div class="time-info">
                <span class="time-icon">🕐</span>
                <span>{{ today()->format('d M Y') }}</span>
            </div>

            <div class="podium-section">
                {{-- Podium Kedua (Rank 2) - KIRI --}}
                @if(isset($usernonguru[1]))
                <div class="podium-player podium-second">
                    <div class="podium-avatar">
                        <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $usernonguru[1]['image'] }}"/>
                    </div>
                    <div class="podium-name">
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                        <a href="/tasks/{{ $usernonguru[1]['id'] }}">{{ $usernonguru[1]['name'] }}</a>
                        @else
                        {{ $usernonguru[1]['name'] }}
                        @endif
                    </div>
                    <div class="podium-points">{{ number_format($usernonguru[1]['total_amount']) }} GP</div>
                    <div class="podium-base">2</div>
                </div>
                @endif

                {{-- Podium Pertama (Rank 1) - TENGAH --}}
                @if(isset($usernonguru[0]))
                <div class="podium-player podium-first">
                    <div class="winner-crown">👑</div>
                    <div class="podium-avatar">
                        <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $usernonguru[0]['image'] }}"/>
                    </div>
                    <div class="podium-name">
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                        <a href="/tasks/{{ $usernonguru[0]['id'] }}">{{ $usernonguru[0]['name'] }}</a>
                        @else
                        {{ $usernonguru[0]['name'] }}
                        @endif
                    </div>
                    <div class="podium-points">{{ number_format($usernonguru[0]['total_amount']) }} GP</div>
                    <div class="podium-base">1</div>
                </div>
                @endif

                {{-- Podium Ketiga (Rank 3) - KANAN --}}
                @if(isset($usernonguru[2]))
                <div class="podium-player podium-third">
                    <div class="podium-avatar">
                        <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $usernonguru[2]['image'] }}"/>
                    </div>
                    <div class="podium-name">
                        @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                        <a href="/tasks/{{ $usernonguru[2]['id'] }}">{{ $usernonguru[2]['name'] }}</a>
                        @else
                        {{ $usernonguru[2]['name'] }}
                        @endif
                    </div>
                    <div class="podium-points">{{ number_format($usernonguru[2]['total_amount']) }} GP</div>
                    <div class="podium-base">3</div>
                </div>
                @endif
            </div>

            <div class="other-players">
                @foreach($usernonguru->slice(3) as $index => $user)
                    <div class="player-item">
                        <div class="player-rank">{{ $index + 1 }}</div>
                        <div class="player-avatar-small">
                            <img src="https://elearning.lifebookacademy.sch.id/public/small/{{ $user['image'] }}"/>
                        </div>
                        <div class="player-info">
                            <div class="player-name">
                                @if(Auth::user()->id == "2" || Auth::user()->id == "15" || Auth::user()->id == "27")
                                <a href="/tasks/{{ $user['id'] }}">{{ $user['name'] }}</a>
                                @else
                                {{ $user['name'] }}
                                @endif
                            </div>
                            <div class="player-points">{{ number_format($user->total_amount) }} points</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if(Auth::user()->role == "guru")
    <!-- Mobile Button -->
    <a class="nav-mobile-btn" href="/tasks/{{Auth::user()->id}}">
        <i class="ph ph-check-fat"></i>
        <span>My Tasks</span>
    </a>
    @endif

    <!-- SCRIPT -->
    <script>
        $(document).ready(function() {
            $('.filter-btn').click(function() {
                const tabId = $(this).data('tab');
                
                // Remove active class from all buttons
                $('.filter-btn').removeClass('active');
                
                // Add active class to clicked button
                $(this).addClass('active');
                
                // Hide all tab contents with fade effect
                $('.tab-content.active').fadeOut(150, function() {
                    $(this).removeClass('active');
                    
                    // Show selected tab content with fade effect
                    $('#' + tabId + '-content').addClass('active').fadeIn(150);
                });
            });
        });

        // Transparent App
        $(".app-container").addClass('transparent');
    </script>
</x-app-layout>