<x-app-layout>
    <div class="teacher-project-page">
        <!-- Header Section matching Teacher Planner theme -->
        <header class="page-header-unified center">
            <div class="header-top">
                <a href="/my-tasks/{{ $userguru->id }}" class="nav-header-back">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="header-title-container">
                    <div class="header-main-title">Non Guru Task Note</div>
                    <div class="header-subtitle">{{ $userguru->name }}</div>
                </div>
            </div>
        </header>

        <main class="project-main-content">
            <div class="note-sticky-container margin-top-25">
                <div id="notes-stack-container" class="note-stack">
                    <!-- Notes will be loaded here via JS -->
                    <div class="note-empty-state">
                        <i class="ph-bold ph-note-pencil note-empty-icon"></i>
                        <p class="note-empty-text">Memuat catatan...</p>
                    </div>
                </div>
            </div>
        </main>

        @if(auth()->id() == $userguru->id)
            <button class="btn-add-note-float" onclick="openAddModal()">
                <i class="ph-bold ph-plus"></i>
            </button>
        @endif
    </div>

    <!-- Modal Add/Edit Category -->
    <div id="category-modal" class="modal-overlay">
        <div class="modal-content-note">
            <h3 style="font-weight: 800; font-size: 20px; margin-bottom: 20px;">Tambah Kategori Catatan</h3>
            <input type="hidden" id="category-id">
            <div class="form-field">
                <label class="project-label-premium">Judul Kategori</label>
                <input type="text" id="category-title" class="project-input"
                    placeholder="Contoh: Pekerjaan, Groceries, dll...">
            </div>
            <div class="form-field" style="margin-top: 20px;">
                <label class="project-label-premium">Warna Kertas</label>
                <div class="note-color-picker">
                    <div class="color-option selected" style="background: #FEB2D3;" data-color="#FEB2D3"></div>
                    <div class="color-option" style="background: #FFE7A0;" data-color="#FFE7A0"></div>
                    <div class="color-option" style="background: #A0C4FF;" data-color="#A0C4FF"></div>
                    <div class="color-option" style="background: #B9FBC0;" data-color="#B9FBC0"></div>
                    <div class="color-option" style="background: #D4A5FF;" data-color="#D4A5FF"></div>
                    <div class="color-option" style="background: #FFD6A5;" data-color="#FFD6A5"></div>
                </div>
            </div>
            <div class="project-actions" style="margin-top: 30px; justify-content: flex-end;">
                <button onclick="closeModal()" class="btn-teacher-project btn-teacher-project-grey"
                    style="width: auto; padding: 12px 25px;">Batal</button>
                <button onclick="saveCategory()" class="btn-teacher-project"
                    style="width: auto; padding: 12px 25px;">Simpan</button>
            </div>
        </div>
    </div>

    <div id="toast-container" style="position: fixed; bottom: 85px; right: 20px; z-index: 9999;"></div>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let selectedColor = '#FEB2D3';

        $(document).ready(function () {
            loadNotes();

            $('.color-option').on('click', function () {
                $('.color-option').removeClass('selected');
                $(this).addClass('selected');
                selectedColor = $(this).data('color');
            });
        });

        let expandedCategoryId = null;

        function loadNotes() {
            $.ajax({
                url: '{{ route("non-guru.note.get") }}',
                method: 'GET',
                data: { user_id: {{ $userguru->id }} },
                success: function (categories) {
                    renderNotes(categories);
                }
            });
        }

        function renderNotes(categories) {
            const container = $('#notes-stack-container');
            if (categories.length === 0) {
                container.html(`
                    <div class="note-empty-state">
                        <i class="ph-bold ph-note-pencil note-empty-icon"></i>
                        <p class="note-empty-text">Belum ada kategori catatan. Klik tombol + untuk menambah.</p>
                    </div>
                `);
                return;
            }

            let html = '';
            categories.forEach((cat, index) => {
                const total = cat.items.length;
                const checked = cat.items.filter(i => i.is_checked == 1 || i.is_checked === true).length;
                const isExpanded = cat.id == expandedCategoryId;

                // Calculate rotation and Y offset for stacked effect
                const rotation = index % 2 === 0 ? (index * 0.5) : -(index * 0.5);
                const yOffset = index * 5;

                // If expanded, remove the stack transform
                const styleTransform = isExpanded ? 'transform: translateY(0px) rotate(0deg);' : `transform: rotate(${rotation}deg) translateY(${yOffset}px);`;

                html += `
                    <div class="note-card ${isExpanded ? 'expanded' : ''}" id="note-cat-${cat.id}" 
                         style="background-color: ${cat.color}; ${styleTransform} z-index: ${isExpanded ? 1000 : (index + 1)};"
                         onclick="toggleExpand(${cat.id})">
                        
                        <div class="note-actions">
                            @if(auth()->id() == $userguru->id)
                                <button class="btn-note-action edit" onclick="event.stopPropagation(); editCategory(${cat.id}, '${cat.title.replace(/'/g, "\\'")}', '${cat.color}')">
                                    <i class="ph-bold ph-pencil"></i>
                                </button>
                                <button class="btn-note-action delete" onclick="event.stopPropagation(); deleteCategory(${cat.id})">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            @endif
                        </div>

                        <div class="note-card-header">
                            <h2 class="note-card-title">${cat.title}</h2>
                            <p class="note-card-subtitle">${checked} of ${total} Tasks</p>
                        </div>

                        <div class="note-checklist">
                            <div class="note-items-list" id="items-list-${cat.id}">
                                ${cat.items.map(item => renderItem(item)).join('')}
                            </div>
                            
                            @if(auth()->id() == $userguru->id)
                                <div class="note-add-item-bar" onclick="event.stopPropagation()">
                                    <input type="text" class="note-add-input" id="new-item-input-${cat.id}" placeholder="Tambah item baru...">
                                    <button class="btn-note-add" onclick="addItem(${cat.id})">
                                        <i class="ph-bold ph-plus"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                `;
            });
            container.html(html);
        }

        function renderItem(item) {
            const isChecked = item.is_checked == 1 || item.is_checked === true;
            return `
                <div class="note-check-item" id="note-item-${item.id}" 
                     onclick="event.stopPropagation(); toggleCheck(${item.id}, ${isChecked ? 0 : 1})">
                    <div class="note-item-check ${isChecked ? 'checked' : ''}">
                        ${isChecked ? '<i class="ph-bold ph-check"></i>' : ''}
                    </div>
                    <div class="note-item-text ${isChecked ? 'checked' : ''}">${item.content}</div>
                    @if(auth()->id() == $userguru->id)
                        <button class="btn-note-action delete" style="width: 24px; height: 24px; font-size: 12px;" onclick="event.stopPropagation(); deleteItem(${item.id})">
                            <i class="ph ph-x"></i>
                        </button>
                    @endif
                </div>
            `;
        }

        function toggleExpand(id) {
            if (expandedCategoryId == id) {
                expandedCategoryId = null;
            } else {
                expandedCategoryId = id;
            }
            loadNotes();
        }

        function openAddModal() {
            $('#category-id').val('');
            $('#category-title').val('');
            $('#category-modal h3').text('Tambah Kategori Catatan');
            $('#category-modal').css('display', 'flex');
        }

        function editCategory(id, title, color) {
            $('#category-id').val(id);
            $('#category-title').val(title);
            selectedColor = color;
            $(`.color-option`).removeClass('selected');
            $(`.color-option[data-color="${color}"]`).addClass('selected');
            $('#category-modal h3').text('Edit Kategori');
            $('#category-modal').css('display', 'flex');
        }

        function closeModal() {
            $('#category-modal').hide();
        }

        function saveCategory() {
            const title = $('#category-title').val();
            const id = $('#category-id').val();
            if (!title) return showToast('Judul harus diisi', 'error');

            $.ajax({
                url: '{{ route("non-guru.note.category.save") }}',
                method: 'POST',
                data: {
                    user_id: {{ $userguru->id }},
                    id: id,
                    title: title,
                    color: selectedColor
                },
                success: function (res) {
                    if (res.success) {
                        showToast('Berhasil disimpan', 'success');
                        closeModal();
                        loadNotes();
                    }
                }
            });
        }

        function deleteCategory(id) {
            if (!confirm('Hapus kategori ini beserta isinya?')) return;
            $.ajax({
                url: '{{ route("non-guru.note.category.delete") }}',
                method: 'POST',
                data: { id: id },
                success: function (res) {
                    if (res.success) {
                        showToast('Kategori dihapus', 'success');
                        loadNotes();
                    }
                }
            });
        }

        function addItem(catId) {
            const input = $(`#new-item-input-${catId}`);
            const content = input.val();
            if (!content) return;

            $.ajax({
                url: '{{ route("non-guru.note.item.save") }}',
                method: 'POST',
                data: {
                    category_id: catId,
                    content: content
                },
                success: function (res) {
                    if (res.success) {
                        input.val('');
                        loadNotes();
                    }
                }
            });
        }

        function toggleCheck(itemId, isChecked) {
            $.ajax({
                url: '{{ route("non-guru.note.item.check") }}',
                method: 'POST',
                data: {
                    id: itemId,
                    is_checked: isChecked
                },
                success: function (res) {
                    if (res.success) {
                        loadNotes();
                    }
                }
            });
        }

        function deleteItem(itemId) {
            if (!confirm('Hapus item ini?')) return;
            $.ajax({
                url: '{{ route("non-guru.note.item.delete") }}',
                method: 'POST',
                data: { id: itemId },
                success: function (res) {
                    if (res.success) {
                        $(`#note-item-${itemId}`).remove();
                        loadNotes();
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
    </script>
</x-app-layout>