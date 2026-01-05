@section('title', 'Laporan Bulanan')
<x-app-layout>
    <style>.app-container{max-width:900px}td.myflex button.btn-remove{background:#fff0;border:none;color:#E91E63;cursor:pointer}td.myflex form{margin:0 5px}td.myflex{display:flex;align-items:center}</style>
    
    <div class="task-management-container">
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/dashboard" title="Back">←</a>
            <h1 class="daily-tasks-title tasks-title-mobile">Laporan Bulanan</h1>
        </div>
        
        <div class="content laporan-content">
            <!-- Filters -->
            <form method="GET" class="filters">
                <h3><i class="fas fa-filter"></i> Filter Data</h3>
                
                <div class="filter-row">
                    <div class="form-group">
                        <label for="user_id">Pilih Guru</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">-- Semua Guru --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="month">Bulan</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">-- Semua Bulan --</option>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="year">Tahun</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('laporanall') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-container">
                @if($laporans->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> No</th>
                                <th><i class="fas fa-user"></i> Nama Guru</th>
                                <th><i class="fas fa-calendar"></i> Periode</th>
                                <th><i class="fas fa-link"></i> Link Laporan</th>
                                <th><i class="fas fa-clock"></i> Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporans as $index => $laporan)
                                <tr>
                                    <td>{{ $laporans->firstItem() + $index }}</td>
                                    <td>
                                        @if(Auth::user()->role == "admin")
                                        <a class="user-lapor" href="/nilai-laporan/{{$laporan->id}}"><strong>{{ $laporan->user->name ?? 'User Tidak Ditemukan' }}</strong></a>
                                        @else
                                        <strong>{{ $laporan->user->name ?? 'User Tidak Ditemukan' }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-purple">
                                            {{ $laporan->month_name }} {{ $laporan->year }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ $laporan->link }}" 
                                           target="_blank" 
                                           class="link-btn">
                                            <i class="fas fa-external-link-alt"></i> Lihat Laporan
                                        </a>
                                    </td>
                                    <td class="myflex">{{ $laporan->created_at->format('d M Y, H:i') }}
                                    @if(Auth::user()->role == "admin")
                                    <form action="/hapus-laporan/{{ $laporan->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove" onclick="if(confirm('Yakin ingin menghapus?')) commentDelete(1); return false"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endif</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">
                        <i class="fas fa-inbox"></i>
                        <h3>Tidak ada data laporan</h3>
                        <p>Belum ada laporan yang sesuai dengan filter yang dipilih.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($laporans->hasPages())
                <div class="pagination">
                    {{ $laporans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</x-app-layout>