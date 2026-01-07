<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/teacher-planner/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Monthly Evaluation</div>
                    <div class="header-subtitle">{{ $userguru->name }}</div>
                </div>
            </div>

            <!-- Month Navigator -->
            <div class="month-navigator-bar">
                <button id="prev-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <div id="current-month-label">-</div>
                <button id="next-month" class="month-nav-btn">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </header>

        <main class="project-main-content">
            <div class="eval-container">
                <div class="eval-grid">
                    <!-- LEFT COLUMN: GURU EVALUATION (FIXED) -->
                    <div class="evaluation-guru-section">
                        @php
                            $sections = [
                                'Monthly Evaluation' => [
                                    'desc' => 'Fokus: Penilaian terhadap keberhasilan atau efektivitas suatu hal berdasarkan kriteria tertentu. Tujuan: Menentukan apakah sesuatu berjalan sesuai harapan dan bagaimana perbaikannya. Contoh: Guru mengevaluasi hasil ujian siswa untuk melihat apakah metode pengajaran yang digunakan efektif / sejauh apa budi pekerti anak-anak berkembang dengan media pembelajaran operasi semut, mencintai teman, menghormati guru, dst. ',
                                    'fields' => [
                                        ['label' => 'Evaluasi', 'id' => 'evaluasi'],
                                        ['label' => 'Student Progress', 'id' => 'student_progress'],
                                    ]
                                ],
                                'Monthly Review' => [
                                    'desc' => 'Fokus: Analisis atau tinjauan terhadap suatu karya, produk, atau pengalaman.
                            Tujuan: Memberikan gambaran objektif mengenai kelebihan dan kekurangan suatu hal.
                            Contoh: Bulan ini mengadakan fieldtrip ke museum nasional, anak-anak sangat antusias karena banyak fasilitas yang luar biasa di dalamnya (sertakan foto kegiatan) / Bulan ini ada satu karya yang sangat unik / dapat the best dari penilaian gallery offline, dst',
                                    'fields' => [
                                        ['label' => 'Review', 'id' => 'review'],
                                    ]
                                ],
                                'Monthly Reflection' => [
                                    'desc' => 'Fokus: Penilaian terhadap pribadi teacher. 
                            Tujuan: Belajar memperbaiki dan menjadi lebih baik lagi.',
                                    'fields' => [
                                        ['label' => 'Apa yang berhasil?', 'id' => 'berhasil'],
                                        ['label' => 'Apa yang belum berhasil?', 'id' => 'belum_berhasil'],
                                        ['label' => 'Contoh Tauladan', 'id' => 'tauladan'],
                                    ]
                                ]
                            ];
                        @endphp

                        @foreach($sections as $title => $data)
                            <div class="eval-card">
                                <h3 class="eval-section-title">
                                    <i class="ph-bold ph-newspaper"></i> {{ $title }}
                                </h3>
                                <p class="eval-section-desc">{{ $data['desc'] }}</p>

                                @foreach($data['fields'] as $field)
                                    <div class="eval-modal-field">
                                        <label class="eval-field-label">{{ $field['label'] }}</label>

                                        @if(auth()->id() == $userguru->id)
                                            <div class="note-toolbar" data-for="{{ $field['id'] }}">
                                                <button type="button" class="note-format-btn" data-cmd="bold"><i
                                                        class="ph-bold ph-text-b"></i></button>
                                                <button type="button" class="note-format-btn" data-cmd="italic"><i
                                                        class="ph-bold ph-text-italic"></i></button>
                                                <div class="note-toolbar-divider"></div>
                                                <button type="button" class="note-format-btn" data-cmd="insertUnorderedList"><i
                                                        class="ph-bold ph-list-bullets"></i></button>
                                            </div>
                                        @endif

                                        <div id="guru-{{ $field['id'] }}" class="note-editor guru-field eval-guru-editor"
                                            contenteditable="{{ auth()->id() == $userguru->id ? 'true' : 'false' }}"
                                            data-field="{{ $field['id'] }}" data-placeholder="Tuliskan di sini..."></div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <!-- Image Upload Section -->
                        <div class="eval-card eval-image-upload-card" style="margin-top: 20px;">
                            <h3 class="eval-section-title">
                                <i class="ph-bold ph-images"></i> Dokumentasi Laporan
                            </h3>
                            <p class="eval-section-desc">Dokumentasi foto kegiatan atau checklist bulan ini.</p>
                            
                            <div id="image-preview-container" class="eval-image-grid">
                                @if(auth()->id() == $userguru->id)
                                    <div class="eval-image-upload-trigger" onclick="document.getElementById('eval-image-input').click()">
                                        <i class="ph ph-plus" style="font-size: 24px;"></i>
                                        <span>Tambah Foto</span>
                                    </div>
                                @endif
                            </div>
                            <input type="file" id="eval-image-input" multiple accept="image/*" style="display: none;">
                        </div>

                        @if(auth()->id() == $userguru->id)
                            <div class="eval-sticky-actions" style="display: flex; align-items: center; gap: 15px;">
                                <span id="auto-save-status"
                                    style="font-size: 11px; color: #9CA3AF; font-style: italic;"></span>
                                <button id="save-guru-btn" class="btn-cal-primary eval-btn-save-main-shadow">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Semua Evaluasi
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- RIGHT COLUMN: NON GURU (DYNAMIC) -->
                    <br />
                    <div class="evaluation-nonguru-section">
                        <div class="eval-card eval-card-nonguru">
                            <div class="note-section-header" style="margin-bottom: 20px;">
                                <h3 class="eval-section-title">
                                    <i class="ph-bold ph-briefcase"></i> Non Guru Eval
                                </h3>
                                @if(auth()->id() == $userguru->id)
                                    <button id="add-nonguru-btn" class="btn-cal-primary eval-btn-small">
                                        <i class="ph-bold ph-plus"></i> Tambah
                                    </button>
                                @endif
                            </div>
                            <div id="nonguru-list" class="eval-nonguru-list note-list-scroll">
                                <!-- Dynamic List -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Non Guru Entry -->
    <div id="nonguru-modal" class="cal-modal-overlay flex-center-center" style="display: none;">
        <div class="cal-modal eval-modal-content">
            <div class="cal-modal-header">
                <h3 id="nonguru-modal-title">Tambah Non Guru Eval</h3>
                <button class="cal-close-modal" onclick="$('#nonguru-modal').fadeOut()"><i class="ph ph-x"></i></button>
            </div>
            <div class="cal-modal-body eval-modal-body-pad">
                <input type="hidden" id="nonguru-id">
                <div class="eval-modal-field">
                    <label class="eval-modal-label">JUDUL</label>
                    <input type="text" id="nonguru-title" class="project-input eval-input-full"
                        placeholder="Masukkan judul...">
                </div>
                <div class="eval-modal-field">
                    <label class="eval-modal-label">DESKRIPSI</label>
                    <div class="note-toolbar">
                        <button type="button" class="note-format-btn" data-cmd="bold"><i
                                class="ph-bold ph-text-b"></i></button>
                        <button type="button" class="note-format-btn" data-cmd="italic"><i
                                class="ph-bold ph-text-italic"></i></button>
                    </div>
                    <div id="nonguru-description" class="note-editor eval-guru-editor" contenteditable="true"
                        data-placeholder="Tuliskan deskripsi..."></div>
                </div>
                <div class="cal-form-actions margin-top-25">
                    <button class="btn-cal-secondary" onclick="$('#nonguru-modal').fadeOut()">Batal</button>
                    <button id="save-nonguru-btn" class="btn-cal-primary">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $(document).ready(function () {
            let currentDate = new Date();
            let isSaving = false;
            let currentData = null;
            let currentNonGuru = [];
            let lastSavedGuru = {};
            let selectedImages = []; // Stores either File objects or string URLs
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const isOwner = {{ auth()->id() == $userguru->id ? 'true' : 'false' }};

            // Initialize
            updateUI();
            loadData();

            // WYSIWYG
            $('.note-format-btn').on('click', function (e) {
                e.preventDefault();
                const cmd = $(this).data('cmd');
                const $editor = $(this).closest('.eval-modal-field').find('.note-editor');
                document.execCommand(cmd, false, null);
                $editor.focus();
            });

            // Navigator
            $('#prev-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateUI();
                loadData();
            });
            $('#next-month').on('click', function () {
                currentDate.setMonth(currentDate.getMonth() + 1);
                updateUI();
                loadData();
            });

            // Guru Save
            $('#save-guru-btn').on('click', function () {
                saveGuru();
            });

            // Auto Save Guru
            // Auto Save Guru (Every 1 minute if changed)
            if (isOwner) {
                setInterval(() => {
                    if (isSaving) return;
                    let changed = false;
                    $('.guru-field').each(function () {
                        const field = $(this).data('field');
                        const currentVal = $(this).html().trim();
                        const lastVal = (lastSavedGuru[field] || '').trim();
                        if (currentVal !== lastVal) changed = true;
                    });
                    if (changed) {
                        console.log('Auto saving evaluation...');
                        saveGuru(true);
                    }
                }, 60000);
            }

            // Non Guru Actions
            $('#add-nonguru-btn').on('click', function () {
                $('#nonguru-id').val('');
                $('#nonguru-title').val('');
                $('#nonguru-description').html('');
                $('#nonguru-modal-title').text('Tambah Non Guru Eval');
                $('#nonguru-modal').css('display', 'flex').hide().fadeIn();
            });

            $('#save-nonguru-btn').on('click', function () {
                saveNonGuru();
            });

            // Image Upload Logic
            $('#eval-image-input').on('change', async function(e) {
                const files = e.target.files;
                if (!files.length) return;

                showToast('Mengompresi gambar...', 'info');
                
                for (let i = 0; i < files.length; i++) {
                    const compressed = await compressImage(files[i]);
                    selectedImages.push(compressed);
                }
                
                renderImagePreviews();
                $(this).val(''); // Reset input
            });

            window.removeImage = function(index) {
                selectedImages.splice(index, 1);
                renderImagePreviews();
            };

            function renderImagePreviews() {
                const container = $('#image-preview-container');
                const trigger = container.find('.eval-image-upload-trigger').detach();
                container.empty();
                
                selectedImages.forEach((img, index) => {
                    const src = typeof img === 'string' ? `/storage/${img}` : URL.createObjectURL(img);
                    container.append(`
                        <div class="eval-preview-wrapper">
                            <img src="${src}" class="eval-preview-img">
                            ${isOwner ? `<button type="button" onclick="removeImage(${index})" class="eval-remove-img"><i class="ph ph-x"></i></button>` : ''}
                        </div>
                    `);
                });
                
                if (isOwner) container.append(trigger);
            }

            async function compressImage(file) {
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = (event) => {
                        const img = new Image();
                        img.src = event.target.result;
                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            const MAX_WIDTH = 1200;
                            let width = img.width;
                            let height = img.height;

                            if (width > MAX_WIDTH) {
                                height *= MAX_WIDTH / width;
                                width = MAX_WIDTH;
                            }

                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);
                            
                            canvas.toBlob((blob) => {
                                resolve(new File([blob], file.name, {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                }));
                            }, 'image/jpeg', 0.3); // 30% quality
                        };
                    };
                });
            }

            function updateUI() {
                $('#current-month-label').text(months[currentDate.getMonth()] + " " + currentDate.getFullYear());
            }

            function loadData() {
                $('.guru-field').html('<i style="color:#9CA3AF; font-size:12px;">Memuat...</i>');
                $('#nonguru-list').html('<p style="text-align:center; padding:20px; color:#9CA3AF; font-size:12px;">Memuat...</p>');

                $.ajax({
                    url: '{{ route("teacher.evaluation.get") }}',
                    method: 'GET',
                    data: {
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1
                    },
                    success: function (res) {
                        // Populate Guru Fields
                        lastSavedGuru = res.evaluation || {};
                        $('.guru-field').each(function () {
                            const field = $(this).data('field');
                            $(this).html(lastSavedGuru[field] || '');
                        });

                        // Populate Images
                        selectedImages = [];
                        if (lastSavedGuru.images) {
                            try {
                                selectedImages = JSON.parse(lastSavedGuru.images);
                            } catch(e) {
                                selectedImages = [];
                            }
                        }
                        renderImagePreviews();

                        // Populate Non Guru List
                        currentNonGuru = res.nonGuruEvaluations || [];
                        renderNonGuru();
                    }
                });
            }

            function renderNonGuru() {
                const container = $('#nonguru-list');
                if (currentNonGuru.length === 0) {
                    container.html('<div style="text-align:center; padding:30px; color:#9CA3AF; font-size:13px;"><i class="ph ph-mask-sad" style="font-size:32px; display:block; margin-bottom:10px;"></i>Belum ada evaluasi non-guru.</div>');
                    return;
                }

                let html = '';
                currentNonGuru.forEach(item => {
                    html += `
                        <div class="eval-nonguru-item">
                            <div class="eval-nonguru-item-header">
                                <div class="eval-nonguru-item-title">${item.title}</div>
                                ${isOwner ? `
                                <div class="eval-nonguru-item-actions">
                                    <button onclick="editNonGuru(${item.id})" class="eval-btn-icon-edit"><i class="ph ph-pencil"></i></button>
                                    <button onclick="deleteNonGuru(${item.id})" class="eval-btn-icon-edit eval-btn-icon-delete"><i class="ph ph-trash"></i></button>
                                </div>
                                ` : ''}
                            </div>
                            <div class="eval-nonguru-item-desc">${item.description || ''}</div>
                        </div>
                    `;
                });
                container.html(html);
            }

            window.editNonGuru = function (id) {
                const item = currentNonGuru.find(i => i.id === id);
                if (!item) return;

                $('#nonguru-id').val(item.id);
                $('#nonguru-title').val(item.title);
                $('#nonguru-description').html(item.description || '');
                $('#nonguru-modal-title').text('Edit Non Guru Eval');
                $('#nonguru-modal').css('display', 'flex').hide().fadeIn();
            }

            window.deleteNonGuru = function (id) {
                if (!confirm('Hapus data ini?')) return;
                $.ajax({
                    url: '{{ route("teacher.evaluation.deleteNonGuru") }}',
                    method: 'POST',
                    data: { id: id, user_id: {{ $userguru->id }} },
                    success: function () {
                        showToast('Berhasil dihapus', 'success');
                        loadData();
                    }
                });
            }

            function saveGuru(isAuto = false) {
                if (isSaving) return;

                // Validation: Ensure at least one field is filled
                let anyFilled = false;
                $('.guru-field').each(function () {
                    const val = $(this).text().trim();
                    if (val) anyFilled = true;
                });

                if (!anyFilled) {
                    if (!isAuto) {
                        showToast('Harap isi minimal satu kolom evaluasi sebelum menyimpan.', 'error');
                    }
                    return;
                }

                isSaving = true;

                const formData = new FormData();
                formData.append('user_id', {{ $userguru->id }});
                formData.append('year', currentDate.getFullYear());
                formData.append('month', currentDate.getMonth() + 1);

                $('.guru-field').each(function () {
                    formData.append($(this).data('field'), $(this).html());
                });

                // Handle images
                const existingImages = [];
                selectedImages.forEach((img, idx) => {
                    if (typeof img === 'string') {
                        existingImages.push(img);
                    } else {
                        formData.append('new_images[]', img);
                    }
                });
                formData.append('existing_images', JSON.stringify(existingImages));

                if (!isAuto) {
                    $('#save-guru-btn').prop('disabled', true).html('<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...');
                }

                $.ajax({
                    url: '{{ route("teacher.evaluation.saveGuru") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function () {
                        const now = new Date();
                        const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

                        if (!isAuto) {
                            showToast('Evaluasi disimpan!', 'success');
                        }
                        $('#auto-save-status').text('Draft disimpan otomatis ' + timeStr);

                        // Update local last saved
                        $('.guru-field').each(function () {
                            lastSavedGuru[$(this).data('field')] = $(this).html();
                        });
                    },
                    complete: function () {
                        isSaving = false;
                        if (!isAuto) {
                            $('#save-guru-btn').prop('disabled', false).html('<i class="ph-bold ph-floppy-disk"></i> Simpan Semua Evaluasi');
                        }
                    }
                });
            }

            function saveNonGuru() {
                const title = $('#nonguru-title').val();
                if (!title) { showToast('Judul wajib diisi', 'error'); return; }

                $.ajax({
                    url: '{{ route("teacher.evaluation.saveNonGuru") }}',
                    method: 'POST',
                    data: {
                        id: $('#nonguru-id').val(),
                        user_id: {{ $userguru->id }},
                        year: currentDate.getFullYear(),
                        month: currentDate.getMonth() + 1,
                        title: title,
                        description: $('#nonguru-description').html()
                    },
                    success: function () {
                        showToast('Berhasil disimpan', 'success');
                        $('#nonguru-modal').fadeOut();
                        loadData();
                    }
                });
            }

            function showToast(msg, type) {
                const id = 'toast-' + Date.now();
                const bg = type === 'success' ? '#D1FAE5' : '#FEE2E2';
                $('#toast-container').append(`<div id="${id}" style="margin-bottom:10px; background:${bg}; padding:12px 20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); font-weight:600; font-size: 14px; color: #1F2937;">${msg}</div>`);
                setTimeout(() => { $(`#${id}`).fadeOut(300, function () { $(this).remove(); }); }, 2000);
            }
        });
    </script>

    <style>
        .eval-image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }

        .eval-image-upload-trigger {
            aspect-ratio: 1/1;
            border: 2px dashed #D1D5DB;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #9CA3AF;
            transition: all 0.2s;
            font-size: 12px;
            font-weight: 600;
        }

        .eval-image-upload-trigger:hover {
            border-color: #7F56D9;
            color: #7F56D9;
            background: #F9FAFB;
        }

        .eval-preview-wrapper {
            position: relative;
            aspect-ratio: 1/1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .eval-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .eval-remove-img {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .eval-remove-img:hover {
            transform: scale(1.1);
        }

        .eval-image-upload-card {
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</x-app-layout>