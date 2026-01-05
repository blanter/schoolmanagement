@section('title', 'Nilai Laporan Bulanan '.$users->name)
<x-app-layout>
    <style>.app-container{max-width:900px}</style>
    
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/semua-laporan" title="Back">←</a>
            <h1 class="daily-tasks-title tasks-title-mobile">Nilai Laporan {{$users->name}}</h1>
        </div>
        
        <div class="content laporan-content">
            <!-- Kirim Nilai -->
            <form method="POST" action="/nilai-laporan/{{$laporans->id}}" class="filters">
                @csrf
                <div class="filter-row">
                    <div class="form-group">
                        <label for="link">Link Laporan 
                        <a href="{{$laporans->link}}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a> (Periode: {{$laporans->month}}-{{$laporans->year}})</label>
                        <input name="link" id="link" class="form-control" value="{{$laporans->link}}" disabled/>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="form-group">
                        <label for="point">Berikan Nilai</label>
                        <input name="point" id="point" class="form-control" value="{{$laporans->point}}" required="" min="1" max="100"/>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="form-group">
                        <label for="comment">Berikan Komentar</label>
                        <input name="comment" id="comment" class="form-control" value="{{$laporans->comment}}" type="text"/>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</x-app-layout>