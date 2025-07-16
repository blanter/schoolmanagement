<x-app-layout>
    <div class="leaderboard-container">
        <!-- HEADER -->
        <div class="leaderboard-header">
            <div class="back-arrow">←</div>
            <h1 class="leaderboard-title">Leaderboard</h1>
        </div>

        <!-- TABS -->
        <div class="time-filter">
            <button class="filter-btn active" data-tab="weekly">Weekly</button>
            <button class="filter-btn" data-tab="alltime">All Time</button>
        </div>

        <div class="performance-banner">
            <div class="performance-rank">#4</div>
            <div class="performance-text">You are doing better than<br>60% of other players!</div>
        </div>

        <div class="time-info">
            <span class="time-icon">🕐</span>
            <span>16 July 2025</span>
        </div>

        <!-- Tab Content 1 -->
        <div id="weekly-content" class="tab-content active">
            <div class="podium-section">
                <div class="podium-player podium-second">
                    <div class="podium-avatar">AD</div>
                    <div class="podium-name">Alena Donin</div>
                    <div class="podium-points">1,469 GP</div>
                    <div class="podium-base">2</div>
                </div>

                <div class="podium-player podium-first">
                    <div class="winner-crown">👑</div>
                    <div class="podium-avatar">DC</div>
                    <div class="podium-name">Davis Curtis</div>
                    <div class="podium-points">2,569 GP</div>
                    <div class="podium-base">1</div>
                </div>

                <div class="podium-player podium-third">
                    <div class="podium-avatar">CG</div>
                    <div class="podium-name">Craig Gouse</div>
                    <div class="podium-points">1,053 GP</div>
                    <div class="podium-base">3</div>
                </div>
            </div>

            <div class="other-players">
                <div class="player-item">
                    <div class="player-rank">4</div>
                    <div class="player-avatar-small">MD</div>
                    <div class="player-info">
                        <div class="player-name">Madelyn Dias</div>
                        <div class="player-points">590 points</div>
                    </div>
                </div>

                <div class="player-item">
                    <div class="player-rank">5</div>
                    <div class="player-avatar-small">ZV</div>
                    <div class="player-info">
                        <div class="player-name">Zain Vaccaro</div>
                        <div class="player-points">448 points</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content 2 -->
        <div id="alltime-content" class="tab-content">
            <div class="podium-section">
                <div class="podium-player podium-second">
                    <div class="podium-avatar">AB</div>
                    <div class="podium-name">Alena Donin</div>
                    <div class="podium-points">1,469 GP</div>
                    <div class="podium-base">2</div>
                </div>

                <div class="podium-player podium-first">
                    <div class="winner-crown">👑</div>
                    <div class="podium-avatar">DC</div>
                    <div class="podium-name">Davis Curtis</div>
                    <div class="podium-points">2,569 GP</div>
                    <div class="podium-base">1</div>
                </div>

                <div class="podium-player podium-third">
                    <div class="podium-avatar">CG</div>
                    <div class="podium-name">Craig Gouse</div>
                    <div class="podium-points">1,053 GP</div>
                    <div class="podium-base">3</div>
                </div>
            </div>

            <div class="other-players">
                <div class="player-item">
                    <div class="player-rank">4</div>
                    <div class="player-avatar-small">MD</div>
                    <div class="player-info">
                        <div class="player-name">Madelyn Dias</div>
                        <div class="player-points">590 points</div>
                    </div>
                </div>

                <div class="player-item">
                    <div class="player-rank">5</div>
                    <div class="player-avatar-small">ZV</div>
                    <div class="player-info">
                        <div class="player-name">Zain Vaccaro</div>
                        <div class="player-points">448 points</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    </script>
</x-app-layout>