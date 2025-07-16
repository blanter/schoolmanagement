<x-app-layout>
<style>.app-container{background:#f8f9fa}</style>

<div class="ps4rental-app">
    <div class="ps4rental-container">
        <div class="ps4rental-header">
            <a class="ps4rental-back-btn" href="/dashboard" title="Back">
                <i class="ph ph-arrow-left"></i>
            </a>
            <h1 class="ps4rental-header-title">Detail Unit</h1>
        </div>

        <div class="ps4rental-content">
            <div class="ps4rental-title-section">
                <h2 class="ps4rental-main-title">PS4 ZONA 1</h2>
                <div class="ps4rental-status">
                    <div class="ps4rental-status-dot"></div>
                    <span class="ps4rental-status-text">Ready</span>
                </div>
            </div>

            <h3 class="ps4rental-section-title">Pilih Jenis Sewa</h3>

            <div class="ps4rental-option ps4rental-clickable-feedback" onclick="selectInStoreRental()">
                <div class="ps4rental-icon">
                    <i class="ph ph-game-controller"></i>
                </div>
                <div class="ps4rental-info">
                    <h3>Sewa di Tempat</h3>
                    <p>Tarif per jam: Rp 10.000</p>
                </div>
            </div>

            <div class="ps4rental-home-section">
                <div class="ps4rental-home-header">
                    <div class="ps4rental-home-icon">
                        <i class="ph ph-house"></i>
                    </div>
                    <div>
                        <h3 class="ps4rental-home-title">Sewa Bawa Pulang</h3>
                        <p class="ps4rental-home-subtitle">Pilih durasi sewa</p>
                    </div>
                </div>

                <ul class="ps4rental-pricing-list">
                    <li class="ps4rental-pricing-item ps4rental-clickable-feedback" onclick="selectTakeHomeRental('12jam')">
                        <span class="ps4rental-duration">12 Jam</span>
                        <span class="ps4rental-price">Rp 80.000</span>
                    </li>
                    <li class="ps4rental-pricing-item ps4rental-clickable-feedback" onclick="selectTakeHomeRental('1hari')">
                        <span class="ps4rental-duration">1 Hari</span>
                        <span class="ps4rental-price">Rp 110.000</span>
                    </li>
                    <li class="ps4rental-pricing-item ps4rental-clickable-feedback" onclick="selectTakeHomeRental('3hari')">
                        <span class="ps4rental-duration">3 Hari</span>
                        <span class="ps4rental-price">Rp 290.000</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT DETAIL UNIT -->
<script>
    function selectInStoreRental() {
        alert('Anda memilih: Sewa di Tempat - Rp 10.000 per jam');
        // Di sini bisa ditambahkan logic untuk proses selanjutnya
    }
    function selectTakeHomeRental(duration) {
        let price = '';
        let durasi = '';
        
        switch(duration) {
            case '12jam':
                price = 'Rp 80.000';
                durasi = '12 Jam';
                break;
            case '1hari':
                price = 'Rp 110.000';
                durasi = '1 Hari';
                break;
            case '3hari':
                price = 'Rp 290.000';
                durasi = '3 Hari';
                break;
        }
        alert(`Anda memilih: Sewa Bawa Pulang - ${durasi} (${price})`);
        // Di sini bisa ditambahkan logic untuk proses selanjutnya
    }
    // Menambahkan event listener untuk tombol back
    document.querySelector('.ps4rental-back-btn').addEventListener('click', function() {
        // Simulasi kembali ke halaman sebelumnya
        alert('Kembali ke halaman utama?');
    });
</script>
</x-app-layout>