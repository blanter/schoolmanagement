<x-app-layout>
<script src="{{asset('/js/html5-qrcode.min.js')}}"></script>
<a class="fixed-back-button" href="/dashboard"><i class="ph-fill ph-house text-2xl"></i></a>

@if($errors->any())
<div class="notif-error">
    Data Zona tidak ditemukan.
</div>
@endif

<!-- QR CODE READER -->
<div class="only-reader">
    <div id="reader"></div>
    <div class="reader-text">
        <h4>Arahkan kamera ke QR Code</h4>
        <p>Memindai secara otomatis...</p>
    </div>
</div>
<form id="myForm" class="hidden" action="{{ route('searchdata') }}" method="POST">@csrf
    <input id="zona_id_input" type="number" name="zona_id" required value="">
</form>

<!-- QR SCANNER -->
<script>
function initializeQrScanner() {
    var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    function onScanSuccess(qrCodeMessage) {
        //alert('Scan Completed! Hello! '+qrCodeMessage);
        $("#myForm input#zona_id_input").val(qrCodeMessage);
        document.getElementById('myForm').submit();
        html5QrcodeScanner.clear();
    }
    html5QrcodeScanner.render(onScanSuccess);
}
setTimeout(initializeQrScanner, 1000);
</script>
</x-app-layout>