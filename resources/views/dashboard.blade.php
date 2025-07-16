<x-app-layout>
<!-- HEADER -->
<div class="custom-header">
    <div class="welcome-text">Selamat Datang,</div>
    <h1>{{Auth::user()->name}}</h1>
</div>

<div class="just-content scrollable">
    <!-- TOMBOL SCAN -->
    <a class="custom-button" href="/scan-code" title="Scan Barcode Unit">
        <i class="ph-fill ph-qr-code text-2xl"></i>
        <span>Scan Barcode Unit</span>
    </a>

    <!-- DATA SEWA -->
    <div class="status-unit-header">
        <h1 class="status-unit-title">Status Unit</h1>
        <div class="console-stats">
            <div class="console-stat">
                <span class="console-stat-label">PS5:</span>
                <span class="console-stat-value ready-value">2</span>
                <span class="console-stat-value sewa-value">1</span>
            </div>
            <div class="console-stat">
                <span class="console-stat-label">PS4:</span>
                <span class="console-stat-value ready-value">4</span>
                <span class="console-stat-value sewa-value">1</span>
            </div>
            <div class="console-stat">
                <span class="console-stat-label">PS3:</span>
                <span class="console-stat-value ready-value">5</span>
                <span class="console-stat-value sewa-value">1</span>
            </div>
        </div>
    </div>

    <!-- DATA ITEMS -->
    <div class="console-items">
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS5 ZONA 1</div>
                <div class="console-details">Kembali: 16 Jul 2025, 07:18</div>
            </div>
            <div class="status-badge status-disewa">
                <div class="status-dot"></div>
                Disewa
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
        <div class="console-item">
            <div class="console-info">
                <div class="console-name">PS3 ZONA 6</div>
            </div>
            <div class="status-badge status-ready">
                <div class="status-dot"></div>
                Ready
            </div>
        </div>
    </div>

    <!-- LEGENDA -->
    <div class="legend-section">
        <div class="legend-title">Legenda Status</div>
        <div class="legend-items">
            <div class="legend-item">
                <div class="legend-dot legend-ready"></div>
                Ready
            </div>
            <div class="legend-item">
                <div class="legend-dot legend-disewa"></div>
                Disewa
            </div>
        </div>
    </div>
</div>

<!-- MOBILE NAVBAR -->
<div class="mobile-navbar">
    <a class="active" href="/dashboard" title="Home">
        <i class="ph ph-house text-2xl"></i>
        <i class="ph-fill ph-house text-2xl"></i>
        <span>Home</span>
    </a>
    <a class="not-active" href="/riwayat" title="Riwayat">
        <i class="ph ph-receipt text-2xl"></i>
        <i class="ph-fill ph-receipt text-2xl"></i>
        <span>Riwayat</span>
    </a>
</div>
</x-app-layout>