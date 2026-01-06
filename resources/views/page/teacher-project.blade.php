<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section matching Teacher Planner theme but with Project features -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/my-tasks/{{ $user->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Teacher Project</div>
                    <div class="header-subtitle">{{ $user->name }}</div>
                </div>
            </div>
        </header>

        <main class="project-main-content">
            <!-- Period Filter -->
            <div class="period-filter-container">
                <a href="?semester=1&year={{ $baseYear }}"
                    class="period-filter-chip {{ $semester == 1 ? 'active' : '' }}">
                    <i class="ph-fill ph-number-circle-one"></i> Semester 1
                </a>
                <a href="?semester=2&year={{ $baseYear }}"
                    class="period-filter-chip {{ $semester == 2 ? 'active' : '' }}">
                    <i class="ph-fill ph-number-circle-two"></i> Semester 2
                </a>
            </div>

            <!-- Tabs -->
            <div class="tabs-wrapper">
                <button class="tab-trigger active" data-tab="penelitian">Karya Penelitian</button>
                <button class="tab-trigger" data-tab="video">Karya Video / DIY</button>
                <button class="tab-trigger" data-tab="barang">Pengadaan Barang</button>
            </div>

            <!-- Tab Content: Penelitian -->
            <div id="content-penelitian" class="tab-content-panel">
                <div class="project-period-banner">
                    <div class="project-banner-title">Periode Penelitian</div>
                    <div class="project-banner-subtitle">
                        @if($semester == 1)
                            Juli - Desember {{ $baseYear }} (Semester 1)
                        @else
                            Januari - Juni {{ $baseYear + 1 }} (Semester 2)
                        @endif
                    </div>
                </div>

                <div class="task-list">
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #FEB2D3;">
                            <i class="ph-bold ph-book-open"></i>
                        </div>
                        <div class="project-task-label">Judul Pendahuluan</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->judul_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'judul_check')" @endif>
                            {!! $project->judul_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #FFE7A0;">
                            <i class="ph-bold ph-file-text"></i>
                        </div>
                        <div class="project-task-label">Rumusan Masalah</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->rumusan_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'rumusan_check')" @endif>
                            {!! $project->rumusan_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #A0C4FF;">
                            <i class="ph-bold ph-microscope"></i>
                        </div>
                        <div class="project-task-label">Penelitian</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->penelitian_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'penelitian_check')" @endif>
                            {!! $project->penelitian_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                    <div class="task-item project-task-item">
                        <div class="project-task-icon" style="background: #B9FBC0;">
                            <i class="ph-bold ph-check-square"></i>
                        </div>
                        <div class="project-task-label">Kesimpulan</div>
                        <div class="project-check-box {{ auth()->id() == $user->id ? '' : 'disabled-check' }} {{ $project->kesimpulan_check ? 'checked' : '' }}"
                            @if(auth()->id() == $user->id) onclick="toggleResearchCheck(this, 'kesimpulan_check')" @endif>
                            {!! $project->kesimpulan_check ? '<i class="ph-bold ph-check"></i>' : '' !!}
                        </div>
                    </div>
                </div>

                <div class="project-info-card">
                    <label class="project-label-premium">
                        <i class="ph-bold ph-link"></i>
                        Lembar Link Penelitian
                    </label>
                    <input type="text" id="research_link" class="project-input project-input-premium"
                        placeholder="Masukkan link google drive / dokumen penelitian..."
                        value="{{ $project->research_link }}" @if(auth()->id() == $user->id)
                        onchange="saveResearchField('research_link', this.value)" @endif @if(auth()->id() != $user->id)
                        disabled @endif>
                    <p class="project-footer-info">
                        <i class="ph ph-info"></i> Link akan tersimpan secara otomatis setelah Anda selesai mengetik.
                    </p>
                </div>
            </div>

            <!-- Tab Content: Video -->
            <div id="content-video" class="tab-content-panel" style="display: none;">
                <div class="project-period-banner video">
                    <div class="project-banner-title">Periode Karya Video / DIY</div>
                    <div class="project-banner-subtitle">
                        @if ($semester == 1)
                            Juli - Desember {{ $baseYear }} (Semester 1)
                        @else
                            Januari - Juni {{ $baseYear + 1 }} (Semester 2)
                        @endif
                    </div>
                </div>

                @if (auth()->id() == $user->id)
                    <div class="project-form-card project-info-card video-form">
                        <div class="form-field">
                            <label class="project-label-premium">Nama Karya Video / DIY</label>
                            <input type="text" id="video_name" class="project-input project-input-premium"
                                placeholder="Masukkan nama karya...">
                        </div>
                        <div class="form-field" style="margin-top: 15px;">
                            <label class="project-label-premium">Link Karya (YouTube/Drive)</label>
                            <input type="text" id="video_link" class="project-input project-input-premium"
                                placeholder="https://youtube.com/...">
                        </div>
                        <div class="project-actions" style="margin-top: 20px; justify-content: flex-end;">
                            <button onclick="saveVideoProject()" class="btn-teacher-project"
                                style="width: auto; padding: 10px 25px;">
                                <i class="ph-bold ph-plus"></i> Tambah Karya
                            </button>
                        </div>
                    </div>
                @endif

                <div id="video-list-container" class="task-list">
                    @forelse ($videoProjects as $video)
                        <div class="task-item project-task-item project-video-item-card" id="video-item-{{ $video->id }}">
                            <div class="project-video-item-header">
                                <div class="project-video-title-wrapper">
                                    <div class="project-video-icon-box">
                                        <i class="ph-bold ph-video-camera"></i>
                                    </div>
                                    <div class="project-video-name-text">{{ $video->name }}</div>
                                </div>
                                @if (auth()->id() == $user->id)
                                    <button onclick="deleteVideo({{ $video->id }})" class="project-delete-trigger">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                @endif
                            </div>
                            @if ($video->link)
                                <a href="{{ $video->link }}" target="_blank" class="project-video-link-anchor">
                                    <i class="ph ph-link"></i> {{ Str::limit($video->link, 40) }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <div id="no-video-placeholder" class="project-empty-state-placeholder">
                            <i class="ph-bold ph-video project-empty-state-icon"></i>
                            <p class="project-empty-state-text">Belum ada karya video yang ditambahkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content: Barang -->
            <div id="content-barang" class="tab-content-panel" style="display: none;">
                <div class="project-period-banner barang">
                    <div class="project-banner-title">Periode Pengadaan Barang</div>
                    <div class="project-banner-subtitle">
                        @if ($semester == 1)
                            Juli - Desember {{ $baseYear }} (Semester 1)
                        @else
                            Januari - Juni {{ $baseYear + 1 }} (Semester 2)
                        @endif
                    </div>
                </div>

                @if (auth()->id() == $user->id)
                    <div class="project-form-card project-info-card barang-form">
                        <form id="procurement-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="year" value="{{ $baseYear }}">
                            <input type="hidden" name="semester" value="{{ $semester }}">

                            <div class="form-field">
                                <label class="project-label-premium">Nama Barang</label>
                                <input type="text" name="nama_barang" class="project-input project-input-premium"
                                    placeholder="Contoh: Laptop, Alat Tulis, dll..." required>
                            </div>

                            <div class="procurement-form-grid" style="margin-top: 15px;">
                                <div class="form-field">
                                    <label class="project-label-premium">Tanggal</label>
                                    <input type="date" name="tanggal" class="project-input project-input-premium"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-field">
                                    <label class="project-label-premium">Tipe</label>
                                    <select name="tipe" class="project-input project-input-premium" required>
                                        <option value="pengeluaran">Pengeluaran (Belanja)</option>
                                        <option value="pemasukan">Pemasukan (Dana)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="procurement-form-grid" style="margin-top: 15px;">
                                <div class="form-field">
                                    <label class="project-label-premium">Nominal (Rp)</label>
                                    <input type="text" name="nominal" id="nominal_input"
                                        class="project-input project-input-premium" placeholder="0" required>
                                </div>
                                <div class="form-field">
                                    <label class="project-label-premium">Link (Drive/YouTube/dll)</label>
                                    <input type="text" name="url" class="project-input project-input-premium"
                                        placeholder="https://...">
                                </div>
                            </div>

                            <div class="form-field" style="margin-top: 15px;">
                                <label class="project-label-premium">Bukti Pembayaran (Maks 5MB)</label>
                                <div class="file-input-wrapper">
                                    <div class="file-input-label">
                                        <i class="ph-bold ph-image"></i>
                                        <span id="file-name-label">Klik untuk Upload Gambar</span>
                                    </div>
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran_input"
                                        class="file-input-hidden" accept="image/*" onchange="previewImage(this)">
                                </div>
                                <div id="image-preview-container" class="preview-container">
                                    <img id="image-preview" src="#" alt="Preview" class="img-preview">
                                </div>
                            </div>

                            <div class="project-actions" style="margin-top: 25px; justify-content: flex-end;">
                                <button type="submit" class="btn-teacher-project" style="width: auto; padding: 12px 30px;">
                                    <i class="ph-bold ph-plus"></i> Simpan Jurnal
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div id="procurement-list-container">
                    @forelse ($procurements as $item)
                        <div class="procurement-item-card" id="procurement-item-{{ $item->id }}">
                            <div class="procurement-card-header">
                                <div class="procurement-date">
                                    <i class="ph ph-calendar-blank"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </div>
                                <span class="procurement-badge {{ $item->tipe }}">
                                    {{ $item->tipe }}
                                </span>
                            </div>
                            <div class="procurement-item-name">{{ $item->nama_barang }}</div>
                            <div class="procurement-nominal">Rp {{ number_format($item->nominal, 0, ',', '.') }}</div>

                            @if ($item->bukti_pembayaran)
                                <div class="procurement-proof-container">
                                    <img src="{{ asset($item->bukti_pembayaran) }}" class="procurement-proof-img"
                                        onclick="window.open(this.src)">
                                </div>
                            @endif

                            <div class="procurement-info-row">
                                <div>
                                    @if ($item->url)
                                        <a href="{{ $item->url }}" target="_blank" class="procurement-url-link">
                                            <i class="ph ph-link"></i> Lihat Link Terkait
                                        </a>
                                    @endif
                                </div>
                                @if (auth()->id() == $user->id)
                                    <button onclick="deleteProcurement({{ $item->id }})" class="procurement-delete-btn">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div id="no-procurement-placeholder" class="project-empty-state-placeholder large">
                            <i class="ph-bold ph-package project-empty-state-icon"></i>
                            <p class="project-empty-state-text">Belum ada pengadaan barang.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>

    </div>

    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $(document).ready(function () {
            // Tab Switching read from hash or default
            const hash = window.location.hash.substring(1);
            if (hash) {
                $('.tab-trigger[data-tab="' + hash + '"]').click();
            }

            $('.tab-trigger').on('click', function () {
                const target = $(this).data('tab');
                window.location.hash = target;
                $('.tab-trigger').removeClass('active');
                $(this).addClass('active');
                $('.tab-content-panel').hide();
                $('#content-' + target).fadeIn(300);

                // Update period filter links to include active tab
                updatePeriodLinks(target);
            });

            function updatePeriodLinks(tab) {
                $('.period-filter-chip').each(function () {
                    let href = $(this).attr('href');
                    if (href.indexOf('#') !== -1) {
                        href = href.substring(0, href.indexOf('#'));
                    }
                    $(this).attr('href', href + '#' + tab);
                });
            }

            // Initial update
            updatePeriodLinks($('.tab-trigger.active').data('tab'));
        });

        // Research Project Functions
        window.toggleResearchCheck = function (el, field) {
            const isChecked = !$(el).hasClass('checked');
            $(el).toggleClass('checked');

            if (isChecked) {
                $(el).html('<i class="ph-bold ph-check"></i>');
            } else {
                $(el).empty();
            }

            saveResearchField(field, isChecked ? 1 : 0);
        }

        window.saveResearchField = function (field, value) {
            $.ajax({
                url: '{{ route("teacher.research.save") }}',
                method: 'POST',
                data: {
                    user_id: {{ $user->id }},
                    field: field,
                    value: value
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Data diperbarui', 'success');
                    }
                },
                error: function () {
                    showToast('Gagal menyimpan data', 'error');
                }
            });
        }

        // Video Project Functions
        window.saveVideoProject = function () {
            const name = $('#video_name').val();
            const link = $('#video_link').val();

            if (!name) {
                showToast('Nama karya harus diisi', 'error');
                return;
            }

            $.ajax({
                url: '{{ route("teacher.video.save") }}',
                method: 'POST',
                data: {
                    user_id: {{ $user->id }},
                    name: name,
                    link: link
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Karya berhasil ditambahkan', 'success');
                        $('#video_name').val('');
                        $('#video_link').val('');
                        $('#no-video-placeholder').remove();

                        const video = res.data;
                        const linkHtml = video.link ? `
                            <a href="${video.link}" target="_blank" class="project-video-link-anchor">
                                <i class="ph ph-link"></i> ${video.link.length > 40 ? video.link.substring(0, 40) + '...' : video.link}
                            </a>
                        ` : '';

                        $('#video-list-container').append(`
                            <div class="task-item project-task-item project-video-item-card" id="video-item-${video.id}">
                                <div class="project-video-item-header">
                                    <div class="project-video-title-wrapper">
                                        <div class="project-video-icon-box">
                                            <i class="ph-bold ph-video-camera"></i>
                                        </div>
                                        <div class="project-video-name-text">${video.name}</div>
                                    </div>
                                    <button onclick="deleteVideo(${video.id})" class="project-delete-trigger">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                                ${linkHtml}
                            </div>
                        `);
                    }
                },
                error: function () {
                    showToast('Gagal menambahkan karya', 'error');
                }
            });
        }

        window.deleteVideo = function (id) {
            if (!confirm('Hapus karya ini?')) return;

            $.ajax({
                url: '{{ route("teacher.video.delete") }}',
                method: 'POST',
                data: {
                    id: id,
                    user_id: {{ $user->id }}
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Karya dihapus', 'success');
                        $(`#video-item-${id}`).fadeOut(300, function () {
                            $(this).remove();
                            if ($('#video-list-container').children().length === 0) {
                                $('#video-list-container').html(`
                                    <div id="no-video-placeholder" class="project-empty-state-placeholder">
                                        <i class="ph-bold ph-video project-empty-state-icon"></i>
                                        <p class="project-empty-state-text">Belum ada karya video yang ditambahkan.</p>
                                    </div>
                                `);
                            }
                        });
                    }
                }
            });
        }

        function showToast(msg, type) {
            const id = 'toast-' + Date.now();
            const bg = type === 'success' ? '#ECFDF5' : '#FEF2F2';
            const color = type === 'success' ? '#065F46' : '#991B1B';
            const borderColor = type === 'success' ? '#10B981' : '#EF4444';

            $('#toast-container').append(`
                <div id="${id}" style="margin-bottom:10px; background:${bg}; color:${color}; padding:14px 22px; border-radius:16px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); font-weight:700; font-size: 13px; border-left: 5px solid ${borderColor}; display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s ease-out;">
                    <i class="ph-fill ph-${type === 'success' ? 'check-circle' : 'warning-circle'}" style="font-size: 18px;"></i>
                    ${msg}
                </div>
            `);
            setTimeout(() => { $(`#${id}`).fadeOut(300, function () { $(this).remove(); }); }, 2500);
        }

        // Procurement Functions
        window.previewImage = function (input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#image-preview').attr('src', e.target.result);
                    $('#image-preview-container').fadeIn();
                    $('#file-name-label').text(input.files[0].name);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#procurement-form').on('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Clean nominal from formatting dots
            const nominalRaw = $('#nominal_input').val().replace(/\./g, '');
            formData.set('nominal', nominalRaw);

            $.ajax({
                url: '{{ route("teacher.procurement.save") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.success) {
                        showToast('Jurnal berhasil disimpan', 'success');
                        location.reload(); // Reload to show new item with proper formatting
                    }
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan jurnal';
                    showToast(msg, 'error');
                }
            });
        });

        window.deleteProcurement = function (id) {
            if (!confirm('Hapus jurnal pengadaan ini?')) return;

            $.ajax({
                url: '{{ route("teacher.procurement.delete") }}',
                method: 'POST',
                data: {
                    id: id,
                    user_id: {{ $user->id }}
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Jurnal dihapus', 'success');
                        $(`#procurement-item-${id}`).fadeOut(300, function () {
                            $(this).remove();
                            if ($('#procurement-list-container').children().length === 0) {
                                $('#procurement-list-container').html(`
                                    <div id="no-procurement-placeholder" class="project-empty-state-placeholder large">
                                        <i class="ph-bold ph-package project-empty-state-icon"></i>
                                        <p class="project-empty-state-text">Belum ada pengadaan barang.</p>
                                    </div>
                                `);
                            }
                        });
                    }
                }
            });
        }

        // Nominal Rupiah Formatting
        const nominalInput = document.getElementById('nominal_input');
        if (nominalInput) {
            nominalInput.addEventListener('keyup', function (e) {
                this.value = formatRupiah(this.value);
            });
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
</x-app-layout>