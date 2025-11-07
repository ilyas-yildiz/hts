<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full"> <!-- h-full eklendi -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- HTS için zaten gerekliydi -->

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts (Inter) - HTS'den taşındı -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- YENİ: SortableJS Kütüphanesi - HTS'den taşındı -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

        <!-- YENİ: HTS'nin Özel CSS Stilleri - HTS'den taşındı -->
        <style>
        /* Temel stiller */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1f2937; /* Koyu Gri (gray-800) */
            color: #f3f4f6; /* Açık Gri (gray-100) */
        }
        /* Scrollbar stilleri */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #374151; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #6b7280; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        
        /* Seçili öğe stili (Senin düzelttiğin gibi 'task-item' kullanılıyor) */
        .task-item.selected {
            background-color: #3b82f6 !important; /* Mavi (blue-500) */
            color: white;
            font-weight: 500;
        }
        
        /* Sütun gizleme */
        .column.hidden {
            display: none;
        }

        /* Tamamlandı Stilleri */
        .task-item.completed .item-text,
        .task-item.completed .task-desc {
            text-decoration: line-through;
            color: #6b7280; /* gray-500 */
        }

        /* Hover (Üzerine Gelme) Efekti */
        .item-actions {
            opacity: 0;
            transition: opacity 0.15s ease-in-out;
            flex-shrink: 0;
            margin-left: 8px;
        }
        .task-item:hover .item-actions {
            opacity: 1;
        }
        .action-checkbox {
            height: 1.1rem; width: 1.1rem;
            margin-right: 8px; cursor: pointer;
            accent-color: #3b82f6; border-radius: 4px;
        }
        .action-delete {
            background: none; border: none;
            color: #ef4444; /* red-500 */
            cursor: pointer; padding: 2px;
        }
        .action-delete:hover { color: #f87171; /* red-400 */ }
        .action-edit {
            background: none; border: none;
            color: #f0f9ff; /* light-blue-50 */
            cursor: pointer; padding: 2px;
            margin-right: 4px;
        }
        .action-edit:hover { color: #60a5fa; /* blue-400 */ }


        /* Metin Davranışları */
        
        /* Sütun 1-5 (Bunlar kaydırmaya devam etsin, 3 nokta yok) */
        .item-text {
            word-break: break-word;
            white-space: normal;
        }

        /* Sütun 6 (Bu KISALTSIN ve 'help' imleci göstersin) */
        .task-desc {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* 2 satırla sınırla */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
            cursor: help; /* Tooltip'in burada olduğunu belirtmek için */
        }
        
        /* Tooltip Kapsayıcısı (Sıkışma sorununu çözen) */
        [data-tooltip] {
            position: relative; 
        }

      
        /* Hover anında tooltip'i göster */
        [data-tooltip]:hover::after {
            opacity: 1;
            visibility: visible;
        }
        
        /* Sürükle-Bırak Stilleri */
        .sortable-ghost {
            opacity: 0.4;
            background-color: #4b5563; /* gray-600 */
        }
        .sortable-drag {
            opacity: 1 !important;
        }
        .task-item { cursor: grab; }
        .task-item:active { cursor: grabbing; }
    </style>

        <!-- Scripts (Breeze'in derlenmiş CSS ve JS'i) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <!-- DÜZELTME: HTS'nin koyu arka planı ve h-full (tam yükseklik) için body güncellendi -->
    <body class="h-full font-sans antialiased" style="background-color: #1f2937;">
        <!-- DÜZELTME: Breeze'in varsayılan 'min-h-screen bg-gray-100' kaldırıldı,
             HTS'nin 'h-full flex flex-col' yapısı eklendi -->
        <div class="h-full flex flex-col">
            <!-- Page Content (HTS arayüzü buraya gelecek) -->
            <!-- DÜZELTME: 'flex-1 min-h-0' eklendi -->
            <main class="flex-1 min-h-0">
                {{ $slot }}
            </main>
        </div>

        <!-- YENİ: HTS'nin tüm JavaScript kodunu buraya taşıdık -->
        <!-- (welcome.blade.php'den kopyalanan tam script bloğu) -->
        <script>
            // --- GLOBAL STATE ---
            const state = {
                selectedCategoryId: null,
                selectedAnnualId: null,
                selectedMonthlyId: null,
                selectedWeeklyId: null,
                selectedDailyId: null,
                selectedGoalDate: null,
                itemToDelete: null,
                editingItem: null 
            };
        
           // --- BU FONKSİYONU GÜNCELLE (422 Hatasını JSON olarak fırlat) ---
        async function fetchData(endpoint, options = {}) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const defaultHeaders = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', };
            if (csrfToken) { defaultHeaders['X-CSRF-TOKEN'] = csrfToken; }
            const url = endpoint; 
            
            try {
                const response = await fetch(url, { ...options, headers: { ...defaultHeaders, ...options.headers, }, });

                // DÜZELTME: Yanıt OK değilse
                if (!response.ok) { 
                    const errorData = await response.json(); // Hata JSON'ını al
                    console.error(`API Hatası (${response.status}): ${response.statusText}`, errorData);
                    
                    // HATA FIRLAT: String (metin) yerine JSON objesinin kendisini fırlat
                    throw errorData; 
                }
                
                if (response.status === 204) { return null; }
                return await response.json(); 

            } catch (error) {
                // Eğer 'fetch'in kendisi çökerse (örn: ağ hatası) VEYA
                // 'throw errorData' çalışırsa, burası yakalar.
                
                // Zaten bizim fırlattığımız bir JSON hatasıysa, tekrar fırlat
                if (error.message) {
                    throw error;
                }
                
                // Ağ hatasıysa (JSON değilse)
                console.error('Fetch Hatası:', endpoint, error);
                showError('Ağ hatası: Veri alınırken bir hata oluştu. Konsolu kontrol edin.');
                throw error; // Bir üstteki 'catch'in de görmesi için fırlat
            }
        }
            function escapeHTML(str) {
            if (str === null || str === undefined) return '';
            // toString() ile sayıları veya diğer tipleri de güvenle çevir
            return str.toString()
                     .replace(/&/g, '&amp;')
                     .replace(/</g, '&lt;')
                     .replace(/>/g, '&gt;')
                     .replace(/"/g, '&quot;')
                     .replace(/'/g, '&#039;');
        }
            /**
         * YENİ: Tarihi (örn: '2025-11-03') '3 Kasım Pzt' formatına çevirir.
         */
     function formatDateTR(dateString) {
            if (!dateString) return '';
            try {
                // DÜZELTME:
                // Laravel'den gelen tam tarih string'ini (örn: "2025-11-03 00:00:00")
                // doğrudan 'new Date()' içine al.
                // Ekstra '+ T00:00:00' (HATALI KISIM) kaldırıldı.
                const date = new Date(dateString); 
                
                // 'tr-TR' (Türkçe) formatını kullan
                const day = date.toLocaleDateString('tr-TR', { day: 'numeric' });
                const month = date.toLocaleDateString('tr-TR', { month: 'short' });
                const weekday = date.toLocaleDateString('tr-TR', { weekday: 'short' });
                
                // Saat dilimi (timezone) kaymalarını önlemek için (eğer tarih 1 gün geri gelirse)
                // UTC (Eşgüdümlü) tarihleri kullanabiliriz, ama şimdilik bu daha basit:
                if (day === undefined || month === undefined || weekday === undefined) {
                    throw new Error('Tarih formatı anlaşılamadı.');
                }

                return `${day} ${month} ${weekday}`;
            } catch (e) {
                console.error("Tarih formatlama hatası:", dateString, e);
                return dateString; // Hata olursa, ham tarihi döndür
            }
        }

const globalTooltip = document.getElementById('global-tooltip');

        function showTooltip(e) {
            const el = e.currentTarget;
            const tooltipText = el.getAttribute('data-tooltip');
            
            if (!tooltipText) return; // Tooltip metni yoksa çık

            globalTooltip.innerHTML = tooltipText;
            
            // Tooltip'i pozisyonla
            const rect = el.getBoundingClientRect();
            let top = rect.bottom + 5; // Öğenin 5px altı
            let left = rect.left + 5; // Öğenin 5px sağı

            // Sıkışma kontrolü (CSS'teki 'tooltip-align-left' sınıfına göre)
            if (el.classList.contains('tooltip-align-left')) {
                 left = rect.right - 300 - 5; // 300px = tooltip genişliği
            }
            
            globalTooltip.style.left = `${left}px`;
            globalTooltip.style.top = `${top}px`;
            globalTooltip.classList.remove('hidden');
            setTimeout(() => globalTooltip.style.opacity = '1', 10); // Fade-in
        }

        function hideTooltip() {
            globalTooltip.style.opacity = '0';
            setTimeout(() => globalTooltip.classList.add('hidden'), 200); // Fade-out
        }

            // --- DATA LOADING FUNCTIONS ---
            async function fetchCategories() {
                console.log('fetchCategories çağrıldı.');
                const data = await fetchData('/api/goal-categories'); 
                if (data) { console.log('Kategoriler yüklendi:', data); renderList('list-col-1', data); } 
                else { console.error('Kategoriler yüklenemedi, data null.'); }
            }
            async function fetchAnnualGoals(categoryId) {
                console.log(`fetchAnnualGoals çağrıldı (Kategori ID: ${categoryId})`);
                const data = await fetchData(`/api/annual-goals/${categoryId}`);
                if (data) { renderList('list-col-2', data); showColumn(2); }
            }
            async function fetchMonthlyGoals(annualGoalId) {
                console.log(`fetchMonthlyGoals çağrıldı (Yıllık ID: ${annualGoalId})`);
                const data = await fetchData(`/api/monthly-goals/${annualGoalId}`);
                if (data) { renderList('list-col-3', data); showColumn(3); }
            }
            async function fetchWeeklyGoals(monthlyGoalId) {
                console.log(`fetchWeeklyGoals çağrıldı (Aylık ID: ${monthlyGoalId})`);
                const data = await fetchData(`/api/weekly-goals/${monthlyGoalId}`);
                if (data) { renderList('list-col-4', data); showColumn(4); }
            }
            async function fetchDailyGoals(weeklyGoalId) {
                console.log(`fetchDailyGoals çağrıldı (Haftalık ID: ${weeklyGoalId})`);
                const data = await fetchData(`/api/daily-goals/${weeklyGoalId}`);
                if (data) { renderList('list-col-5', data); showColumn(5); }
            }
// --- BU FONKSİYONU GÜNCELLE (Hata 2 Düzeltmesi: 'Lütfen önce bir gün seçin') ---
        async function fetchTasks(dailyGoalId) {
            console.log(`fetchTasks çağrıldı (Günlük ID: ${dailyGoalId})`);
            const data = await fetchData(`/api/tasks/${dailyGoalId}`);
            const listElement = document.getElementById('list-col-6');
            
            if (data && data.length > 0) {
                listElement.innerHTML = '';
                data.forEach(task => {
                    const item = document.createElement('div');
                    item.className = `task-item flex items-center justify-between p-3 rounded-md bg-gray-700 shadow ${task.is_completed ? 'completed' : ''}`;
                    item.dataset.id = task.id;
                    
                    const safeDescription = escapeHTML(task.task_description);

                    // DÜZELTME (V3): 'time_label' yerine 'start_time' ve 'end_time' göster
                    let timeDisplay = '';
                    if (task.start_time) { // (Hata 1 Düzeltmesi sonrası bu 'if' çalışmalı)
                        // Saati '14:30' formatına çevir (saniyeleri at)
                        const startTime = task.start_time.substring(0, 5);
                        if (task.end_time) {
                            const endTime = task.end_time.substring(0, 5);
                            timeDisplay = `${startTime} - ${endTime}`;
                        } else {
                            timeDisplay = startTime;
                        }
                    } else {
                        timeDisplay = 'Tüm Gün'; // Saati yoksa
                    }

                    item.innerHTML = `
                        <div class="item-content flex-1 flex items-center min-w-0">
                            <input type="checkbox" class="action-checkbox" 
                                   title="Tamamlandı olarak işaretle"
                                   ${task.is_completed ? 'checked' : ''}>
                            <div class="ml-2">
                                <div class="text-xs font-semibold text-gray-400">${timeDisplay}</div>
                                <div class="text-sm text-white task-desc">
                                    ${task.task_description}
                                </div>
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="action-edit" title="Düzenle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                    <path d="m15 5 4 4"/>
                                </svg>
                            </button>
                            <button class="action-delete" title="Sil">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    
                    const checkbox = item.querySelector('.action-checkbox');
                    const editBtn = item.querySelector('.action-edit');
                    const deleteBtn = item.querySelector('.action-delete');
                    const content = item.querySelector('.item-content');

                    checkbox.addEventListener('change', async (e) => { const isCompleted = e.target.checked; await toggleTaskStatus(task.id, isCompleted); item.classList.toggle('completed', isCompleted); });
                    editBtn.addEventListener('click', (e) => { e.stopPropagation(); openEditModal('task', task); });
                    deleteBtn.addEventListener('click', (e) => { e.stopPropagation(); handleDelete('task', task.id, item); });
                    
                    content.addEventListener('click', (e) => {
                        if (e.target.tagName.toLowerCase() === 'input') return;
                        e.currentTarget.closest('.flex-1.overflow-y-auto').querySelectorAll('.task-item').forEach(el => el.classList.remove('selected'));
                        item.classList.add('selected');
                        
                        // --- DÜZELTME BURADA (Hata 2) ---
                        // 'state.selectedDailyId'yi, artık var olmayan 'task.daily_goal_id' 
                        // ile GÜNCELLEMEYİ BIRAKIYORUZ. Zaten Sütun 5'ten seçili.
                        // state.selectedDailyId = task.daily_goal_id; // <-- BU SATIR SİLİNDİ
                    });
                    
                    listElement.appendChild(item);

                    // Tooltip mantığı (Bu kod doğru, kalmalı)
                    setTimeout(() => {
                        const taskDesc = item.querySelector('.task-desc');
                        if (!taskDesc) return;
                        const isOverflowing = taskDesc.scrollHeight > taskDesc.clientHeight;
                        if (isOverflowing) {
                            const tooltipContainer = item.querySelector('.ml-2');
                            tooltipContainer.setAttribute('data-tooltip', escapeHTML(task.task_description));
                            const rect = tooltipContainer.getBoundingClientRect();
                            if (rect.right + 300 > window.innerWidth) {
                                tooltipContainer.classList.add('tooltip-align-left');
                            }
                            tooltipContainer.addEventListener('mouseenter', showTooltip);
                            tooltipContainer.addEventListener('mouseleave', hideTooltip);
                        } else {
                            taskDesc.style.cursor = 'default';
                        }
                    }, 50);
                });
                
                initSortable('list-col-6', 'Task');
            } else {
                listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Bu gün için planlanmış görev yok.</div>`;
            }
            showColumn(6);
        }

            // --- TASK (GÖREV) ACTIONS ---
            async function toggleTaskStatus(taskId, isCompleted) { await fetchData(`/api/tasks/toggle/${taskId}`, { method: 'PUT', body: JSON.stringify({ is_completed: isCompleted }) }); }
            
            // --- GOAL (HEDEF) ACTIONS ---
            async function handleToggleGoal(type, id, isCompleted) {
                let endpoint = '';
                switch (type) {
                    case '1': endpoint = 'goal-categories'; break;
                    case '2': endpoint = 'annual-goals'; break;
                    case '3': endpoint = 'monthly-goals'; break;
                    case '4': endpoint = 'weekly-goals'; break;
                    case '5': endpoint = 'daily-goals'; break;
                    default: console.error('Bilinmeyen toggle tipi:', type); return;
                }
                console.log(`handleToggleGoal çağrıldı (Tip: ${type}, ID: ${id}, Durum: ${isCompleted})`);
                await fetchData(`/api/${endpoint}/toggle/${id}`, {
                    method: 'PUT',
                    body: JSON.stringify({ is_completed: isCompleted })
                });
            }
            async function handleDelete(type, id, itemElement) {
                state.itemToDelete = { type, id, itemElement };
                document.getElementById('delete-confirm-modal').classList.remove('hidden');
            }
            async function confirmDelete() {
                if (!state.itemToDelete) return; 
                const { type, id, itemElement } = state.itemToDelete;
                console.log(`confirmDelete çağrıldı (Tip: ${type}, ID: ${id})`);
                let endpoint = '';
                switch (type) {
                    case '1': endpoint = 'goal-categories'; break;
                    case '2': endpoint = 'annual-goals'; break;
                    case '3': endpoint = 'monthly-goals'; break;
                    case '4': endpoint = 'weekly-goals'; break;
                    case '5': endpoint = 'daily-goals'; break;
                    case 'task': endpoint = 'tasks'; break;
                    default:
                        console.error('Bilinmeyen silme tipi:', type);
                        state.itemToDelete = null;
                        closeModal('delete-confirm-modal');
                        return;
                }
                const btn = document.getElementById('confirm-delete-btn');
                btn.disabled = true; btn.textContent = 'Siliniyor...';
                await fetchData(`/api/${endpoint}/${id}`, { method: 'DELETE' });
                itemElement.remove();
                switch (type) {
                    case '1': resetColumns(2); break;
                    case '2': resetColumns(3); break;
                    case '3': resetColumns(4); break;
                    case '4': resetColumns(5); break;
                    case '5': resetColumns(6); break;
                }
                btn.disabled = false; btn.textContent = 'Evet, Sil';
                closeModal('delete-confirm-modal');
                state.itemToDelete = null; 
            }

            // --- MODAL (EKLEME/DÜZENLEME) YÖNETİMİ ---
            async function addNewCategory(e) {
                e.preventDefault();
                if (state.editingItem) { await handleUpdate(e); return; }
                const name = document.getElementById('category-name').value.trim();
                if (!name) return;
                const data = { name: name };
                const btn = document.getElementById('save-category-btn');
                btn.disabled = true; btn.textContent = 'Kaydediliyor...';
                const newCategory = await fetchData('/api/goal-categories', { method: 'POST', body: JSON.stringify(data) });
                if (newCategory) {
                    fetchCategories(); 
                    closeModal('category-modal'); 
                } else {
                    btn.disabled = false; btn.textContent = 'Kaydet';
                }
            }
            async function addNewAnnualGoal(e) {
                e.preventDefault();
                if (state.editingItem) { await handleUpdate(e); return; }
                if (!state.selectedCategoryId) return;
                const title = document.getElementById('annual-goal-title').value.trim();
                const year = document.getElementById('annual-goal-year').value;
                const period_label = document.getElementById('annual-goal-period').value.trim();
                if (!title || !year || !period_label) return;
                const data = { goal_category_id: state.selectedCategoryId, title: title, year: parseInt(year, 10), period_label: period_label };
                const btn = document.getElementById('save-annual-goal-btn');
                btn.disabled = true; btn.textContent = 'Kaydediliyor...';
                const newAnnualGoal = await fetchData('/api/annual-goals', { method: 'POST', body: JSON.stringify(data) });
                if (newAnnualGoal) {
                    fetchAnnualGoals(state.selectedCategoryId); 
                    closeModal('annual-goal-modal'); 
                } else {
                    btn.disabled = false; btn.textContent = 'Kaydet';
                }
            }
            async function addNewMonthlyGoal(e) {
                e.preventDefault();
                if (state.editingItem) { await handleUpdate(e); return; }
                if (!state.selectedAnnualId) return;
                const title = document.getElementById('monthly-goal-title').value.trim();
                const label = document.getElementById('monthly-goal-label').value.trim();
                if (!title || !label) return;
                const data = { annual_goal_id: state.selectedAnnualId, title: title, month_label: label };
                const btn = document.getElementById('save-monthly-goal-btn');
                btn.disabled = true; btn.textContent = 'Kaydediliyor...';
                const newMonthlyGoal = await fetchData('/api/monthly-goals', { method: 'POST', body: JSON.stringify(data) });
                if (newMonthlyGoal) {
                    fetchMonthlyGoals(state.selectedAnnualId); 
                    closeModal('monthly-goal-modal'); 
                } else {
                    btn.disabled = false; btn.textContent = 'Kaydet';
                }
            }
           // --- BU FONKSİYONU GÜNCELLE (start_date eklendi) ---
        async function addNewWeeklyGoal(e) {
            e.preventDefault();
            if (state.editingItem) { await handleUpdate(e); return; }
            if (!state.selectedMonthlyId) return;
            
            // Yeni verileri al
            const title = document.getElementById('weekly-goal-title').value.trim();
            const label = document.getElementById('weekly-goal-label').value.trim();
            const startDate = document.getElementById('weekly-goal-start-date').value; // YENİ
            
            if (!title || !label) return;
            const data = { 
                monthly_goal_id: state.selectedMonthlyId, 
                title: title, 
                week_label: label,
                start_date: startDate || null // YENİ
            };
            
            const btn = document.getElementById('save-weekly-goal-btn');
            btn.disabled = true; btn.textContent = 'Kaydediliyor...';
            const newWeeklyGoal = await fetchData('/api/weekly-goals', { method: 'POST', body: JSON.stringify(data) });
            if (newWeeklyGoal) { fetchWeeklyGoals(state.selectedMonthlyId); closeModal('weekly-goal-modal'); } 
            else { btn.disabled = false; btn.textContent = 'Kaydet'; }
        }

        // --- BU FONKSİYONU GÜNCELLE (goal_date eklendi) ---
        async function addNewDailyGoal(e) {
            e.preventDefault();
            if (state.editingItem) { await handleUpdate(e); return; }
            if (!state.selectedWeeklyId) return;
            
            // Yeni verileri al
            const label = document.getElementById('daily-goal-label').value.trim();
            const title = document.getElementById('daily-goal-title').value.trim();
            const goalDate = document.getElementById('daily-goal-date').value; // YENİ
            
            if (!label) return;
            const data = { 
                weekly_goal_id: state.selectedWeeklyId, 
                day_label: label, 
                title: title || null,
                goal_date: goalDate || null // YENİ
            };
            
            const btn = document.getElementById('save-daily-goal-btn');
            btn.disabled = true; btn.textContent = 'Kaydediliyor...';
            const newDailyGoal = await fetchData('/api/daily-goals', { method: 'POST', body: JSON.stringify(data) });
            if (newDailyGoal) { fetchDailyGoals(state.selectedWeeklyId); closeModal('daily-goal-modal'); } 
            else { btn.disabled = false; btn.textContent = 'Kaydet'; }
        }

       // --- BU FONKSİYONU GÜNCELLE (422 'catch' bloğu düzeltildi) ---
        async function addNewTask(e) {
            e.preventDefault(); 
            if (state.editingItem) { await handleUpdate(e); return; }
            
            const desc = document.getElementById('task-desc').value;
            const goalDate = document.getElementById('task-goal-date').value;
            const startTime = document.getElementById('task-start-time').value;
            const endTime = document.getElementById('task-end-time').value;

            if (!desc || !state.selectedCategoryId || !goalDate) {
                showError('Görev açıklaması, Kategori (Sütun 1) ve Tarih (Modal) zorunludur.');
                return;
            }
            
            const data = {
                goal_category_id: state.selectedCategoryId,
                goal_date: goalDate,
                start_time: startTime || null,
                end_time: endTime || null,
                task_description: desc
            };
            
            const btn = document.getElementById('save-task-btn');
            btn.disabled = true; btn.textContent = 'Kaydediliyor...';

            try {
                const newTask = await fetchData('/api/tasks', { method: 'POST', body: JSON.stringify(data) });
                
                if (newTask) {
                    fetchTasks(state.selectedDailyId);
                    closeModal('task-modal');
                }
            } catch (error) { // 'error' artık bizim JSON objemiz
                // DÜZELTME: 422 Çakışma hatasını yakala ve göster
                if (error.errors && error.errors.time) {
                    alert(error.errors.time[0]); // "Bu saat aralığı zaten dolu."
                } else {
                    console.error('addNewTask Hatası:', error);
                    showError('Bilinmeyen bir hata oluştu.');
                }
            } finally {
                // Hata da olsa, başarılı da olsa butonu aç
                btn.disabled = false; btn.textContent = 'Kaydet';
            }
        }

      // --- BU FONKSİYONU GÜNCELLE (V2: Görev (Task) düzenleme için Tarih ekle) ---
        function openEditModal(type, item) {
            state.editingItem = { type, item }; 
            let modalId = '';
            
            const formatDate = (dateString) => {
                if (!dateString) return '';
                return dateString.split('T')[0];
            };

            const formatTime = (timeString) => {
                if (!timeString) return '';
                return timeString.substring(0, 5);
            };

            switch (type) {
           case '1':
                        modalId = 'category-modal';
                        document.getElementById('category-name').value = item.name;
                        break;
                    case '2':
                        modalId = 'annual-goal-modal';
                        document.getElementById('annual-goal-title').value = item.title;
                        document.getElementById('annual-goal-year').value = item.year;
                        document.getElementById('annual-goal-period').value = item.period_label;
                        break;
                    case '3':
                        modalId = 'monthly-goal-modal';
                        document.getElementById('monthly-goal-title').value = item.title;
                        document.getElementById('monthly-goal-label').value = item.month_label;
                        break;
                    case '4':
                        modalId = 'weekly-goal-modal';
                        document.getElementById('weekly-goal-title').value = item.title;
                        document.getElementById('weekly-goal-label').value = item.week_label;
                        break;
                    case '5':
                        modalId = 'daily-goal-modal';
                        document.getElementById('daily-goal-label').value = item.day_label;
                        document.getElementById('daily-goal-title').value = item.title;
                        break;
                
               case 'task': // Sütun 6 (Görev)
                    modalId = 'task-modal';
                    document.getElementById('task-goal-date').value = formatDate(item.goal_date);
                    document.getElementById('task-start-time').value = formatTime(item.start_time); // YENİ
                    document.getElementById('task-end-time').value = formatTime(item.end_time);   // YENİ
                    document.getElementById('task-desc').value = item.task_description;
                    break;
                default:
                    console.error('Bilinmeyen düzenleme tipi:', type);
                    return;
            }
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.querySelector('h3').textContent = 'Öğeyi Düzenle';
                modal.querySelector('button[type="submit"]').textContent = 'Güncelle';
                modal.classList.remove('hidden');
            }
        }

       // --- BU FONKSİYONU GÜNCELLE (422 'catch' bloğu düzeltildi) ---
        async function handleUpdate(e) {
            e.preventDefault();
            if (!state.editingItem) return;
            const { type, item } = state.editingItem;
            let data = {}; let endpoint = ''; let btnId = '';
            
            try {
                switch (type) {
                    // ... (case '1' den '5'e kadar aynı) ...
                    case '1': data = { name: document.getElementById('category-name').value }; endpoint = `/api/goal-categories/${item.id}`; btnId = 'save-category-btn'; break;
                    case '2': data = { title: document.getElementById('annual-goal-title').value, year: document.getElementById('annual-goal-year').value, period_label: document.getElementById('annual-goal-period').value }; endpoint = `/api/annual-goals/${item.id}`; btnId = 'save-annual-goal-btn'; break;
                    case '3': data = { title: document.getElementById('monthly-goal-title').value, month_label: document.getElementById('monthly-goal-label').value }; endpoint = `/api/monthly-goals/${item.id}`; btnId = 'save-monthly-goal-btn'; break;
                    case '4': data = { title: document.getElementById('weekly-goal-title').value, week_label: document.getElementById('weekly-goal-label').value, start_date: document.getElementById('weekly-goal-start-date').value || null }; endpoint = `/api/weekly-goals/${item.id}`; btnId = 'save-weekly-goal-btn'; break;
                    case '5': data = { day_label: document.getElementById('daily-goal-label').value, title: document.getElementById('daily-goal-title').value || null, goal_date: document.getElementById('daily-goal-date').value || null }; endpoint = `/api/daily-goals/${item.id}`; btnId = 'save-daily-goal-btn'; break;
                    
                    case 'task': 
                        data = {
                            goal_date: document.getElementById('task-goal-date').value,
                            start_time: document.getElementById('task-start-time').value || null,
                            end_time: document.getElementById('task-end-time').value || null,
                            task_description: document.getElementById('task-desc').value
                        };
                        endpoint = `/api/tasks/${item.id}`;
                        btnId = 'save-task-btn';
                        break;
                    default:
                        throw new Error('Bilinmeyen güncelleme tipi');
                }
                
                const btn = document.getElementById(btnId);
                btn.disabled = true; btn.textContent = 'Güncelleniyor...';
                const updatedItem = await fetchData(endpoint, { method: 'PUT', body: JSON.stringify(data) });
                
                if (updatedItem) {
                    console.log('Öğe güncellendi:', updatedItem);
                    // Listeyi yenile
                    if (type === 'task') {
                        fetchTasks(state.selectedDailyId);
                    } else if (type === '1') { fetchCategories(); }
                    else if (type === '2') { fetchAnnualGoals(state.selectedCategoryId); }
                    else if (type === '3') { fetchMonthlyGoals(state.selectedAnnualId); }
                    else if (type === '4') { fetchWeeklyGoals(state.selectedMonthlyId); }
                    else if (type === '5') { fetchDailyGoals(state.selectedWeeklyId); }

                    closeModal(btn.closest('.fixed').id);
                }
            } catch (error) { // 'error' artık bizim JSON objemiz
                // DÜZELTME: 422 Çakışma hatasını yakala ve göster
                if (error.errors && error.errors.time) {
                    alert(error.errors.time[0]); // "Bu saat aralığı dolu."
                } else {
                    console.error('Güncelleme hatası:', error);
                    showError('Bilinmeyen bir hata oluştu.');
                }
                
                if (btnId) { const btn = document.getElementById(btnId); btn.disabled = false; btn.textContent = 'Güncelle'; }
            }
        }

            // --- REORDERING (Sıralama) ---
            async function handleReorder(modelType, listElement) {
                console.log(`handleReorder çağrıldı (Tip: ${modelType})`);
                const items = listElement.querySelectorAll('.task-item');
                const ids = Array.from(items).map(item => parseInt(item.dataset.id, 10));
                if (ids.length === 0) return; 
                const data = { model_type: modelType, ids: ids };
                try {
                    await fetchData('/api/reorder', { method: 'PUT', body: JSON.stringify(data) });
                    console.log('Sıralama güncellendi:', modelType, ids);
                } catch (error) {
                    console.error('Sıralama güncellenirken hata:', error);
                    showError('Sıralama güncellenirken bir hata oluştu!');
                }
            }
            function initSortable(listId, modelType) {
                const listElement = document.getElementById(listId);
                if (!listElement) return;
                if (listElement.sortableInstance) { listElement.sortableInstance.destroy(); }
                listElement.sortableInstance = new Sortable(listElement, {
                    animation: 150, 
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    onEnd: function (evt) {
                        handleReorder(modelType, listElement);
                    }
                });
            }

// --- BU FONKSİYONU GÜNCELLE (Hata: fetchTasks yanlış parametrelerle çağrılıyor) ---
        function renderList(listId, data) {
            const listElement = document.getElementById(listId);
            listElement.innerHTML = ''; 
            const listType = listId.split('-')[2];
            if (!data || data.length === 0) {
                listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Veri bulunamadı.</div>`;
                return;
            }

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'task-item p-3 rounded-md hover:bg-gray-700 transition-colors duration-150 flex justify-between items-center';
                div.dataset.id = item.id;
                if (item.is_completed) { div.classList.add('completed'); }
                
                let topText = ''; let bottomText = ''; let bottomFontSizeClass = 'text-sm text-white'; 
                switch (listType) {
                    case '1': topText = ''; bottomText = item.name; bottomFontSizeClass = 'text-white'; break;
                    case '2': topText = `Yıl ${item.year}: ${item.period_label}`; bottomText = item.title; break;
                    case '3': topText = item.month_label; bottomText = item.title; break;
                    case '4': topText = item.start_date ? formatDateTR(item.start_date) : item.week_label; bottomText = item.title; break;
                    case '5': topText = item.goal_date ? formatDateTR(item.goal_date) : item.day_label; bottomText = item.title || ''; break;
                }
                
                div.innerHTML = `
                    <div class="item-content flex-1 flex items-center min-w-0">
                        <input type="checkbox" class="action-checkbox" title="Tamamlandı olarak işaretle" ${item.is_completed ? 'checked' : ''}>
                        <div class="ml-2" title="${topText ? topText + ': ' : ''}${bottomText}">
                            ${topText ? `<div class="text-xs font-semibold text-gray-400">${topText}</div>` : ''}
                            <div class="item-text ${bottomFontSizeClass}">${bottomText}</div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="action-edit" title="Düzenle">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                <path d="m15 5 4 4"/>
                            </svg>
                        </button>
                        <button class="action-delete" title="Sil">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                `;

                // Tıklama olaylarını ata
                const content = div.querySelector('.item-content');
                const checkbox = div.querySelector('.action-checkbox');
                const editBtn = div.querySelector('.action-edit');
                const deleteBtn = div.querySelector('.action-delete');
                
                checkbox.addEventListener('click', (e) => { e.stopPropagation(); const isCompleted = e.target.checked; div.classList.toggle('completed', isCompleted); handleToggleGoal(listType, item.id, isCompleted); });
                
                content.addEventListener('click', (e) => { 
                    if (e.target.tagName.toLowerCase() === 'input') return;
                    e.currentTarget.closest('.flex-1.overflow-y-auto').querySelectorAll('.task-item').forEach(el => el.classList.remove('selected'));
                    div.classList.add('selected'); 
                    
                    switch (listType) { 
                        case '1': state.selectedCategoryId = item.id; resetColumns(2); fetchAnnualGoals(item.id); break; 
                        case '2': state.selectedAnnualId = item.id; resetColumns(3); fetchMonthlyGoals(item.id); break; 
                        case '3': state.selectedMonthlyId = item.id; resetColumns(4); fetchWeeklyGoals(item.id); break; 
                        case '4': state.selectedWeeklyId = item.id; resetColumns(5); fetchDailyGoals(item.id); break; 
                        
                        // --- DÜZELTME BURADA ---
                        // fetchTasks'i *yanlış* çağıran (senin kodundaki) 'case 5'i düzeltiyoruz.
                        // (Benim 'geri al' talimatım buydu ve doğruydu)
                        case '5': 
                            state.selectedDailyId = item.id;
                            state.selectedGoalDate = item.goal_date; // (Bu satır doğru, 'Ekle' butonu için kalsın)
                            resetColumns(6);
                            fetchTasks(item.id); // Sadece 'item.id' (DailyGoal ID) gönder
                            break; 
                        // --- DÜZELTME SONU ---
                    } 
                });
                
                editBtn.addEventListener('click', (e) => { e.stopPropagation(); openEditModal(listType, item); });
                deleteBtn.addEventListener('click', (e) => { e.stopPropagation(); handleDelete(listType, item.id, div); });

                listElement.appendChild(div);
                
                // Tooltip mantığı (Sütun 1-5'in alta kayan metinleri için)
                setTimeout(() => {
                    const itemText = div.querySelector('.item-text');
                    if (itemText && (itemText.scrollHeight > itemText.clientHeight)) {
                        const tooltipContainer = div.querySelector('.ml-2');
                        tooltipContainer.setAttribute('data-tooltip', escapeHTML(bottomText));
                        const rect = tooltipContainer.getBoundingClientRect();
                        if (rect.right + 300 > window.innerWidth) {
                            tooltipContainer.classList.add('tooltip-align-left');
                        }
                        tooltipContainer.addEventListener('mouseenter', showTooltip);
                        tooltipContainer.addEventListener('mouseleave', hideTooltip);
                    } else if (itemText) {
                        itemText.style.cursor = 'default';
                    }
                }, 50); 
            });

            // Sürükle-bırak (SortableJS) başlatma
            let modelType = '';
            switch (listType) {
                case '1': modelType = 'GoalCategory'; break;
                case '2': modelType = 'AnnualGoal'; break;
                case '3': modelType = 'MonthlyGoal'; break;
                case '4': modelType = 'WeeklyGoal'; break;
                case '5': modelType = 'DailyGoal'; break;
            }
            if (modelType) {
                initSortable(listId, modelType);
            }
        }

            function resetColumns(startColumnIndex) {
                console.log(`resetColumns çağrıldı (Başlangıç: ${startColumnIndex})`);
                for (let i = startColumnIndex; i <= 6; i++) {
                    document.getElementById(`col-${i}`).classList.add('hidden');
                    document.getElementById(`list-col-${i}`).innerHTML = '';
                }
            }
            function showColumn(colIndex) { document.getElementById(`col-${colIndex}`).classList.remove('hidden'); }
            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    const form = modal.querySelector('form');
                    if (form) form.reset(); 
                    if (modalId !== 'delete-confirm-modal') {
                        modal.querySelector('h3').textContent = modal.querySelector('h3').textContent.replace('Düzenle', 'Yeni Ekle');
                        const submitBtn = modal.querySelector('button[type="submit"]');
                        if (submitBtn) { submitBtn.textContent = 'Kaydet'; submitBtn.disabled = false; }
                    }
                    state.editingItem = null;
                }
            }
            function showError(message) { console.error('UYGULAMA HATASI:', message); }
          // --- BU FONKSİYONU GÜNCELLE (Scroll listener eklendi) ---
        async function initApp() {
            console.log('Uygulama başlıyor (initApp)...');
            resetColumns(2); 
            await fetchCategories();
            
            // 3. Modalları ayarla
            setupModal('task-modal', 'open-task-modal-btn', 'close-task-modal-btn', 'task-form');
            setupModal('category-modal', 'open-category-modal-btn', 'close-category-modal-btn', 'category-form');
            setupModal('annual-goal-modal', 'open-annual-goal-modal-btn', 'close-annual-goal-modal-btn', 'annual-goal-form');
            setupModal('monthly-goal-modal', 'open-monthly-goal-modal-btn', 'close-monthly-goal-modal-btn', 'monthly-goal-form');
            setupModal('weekly-goal-modal', 'open-weekly-goal-modal-btn', 'close-weekly-goal-modal-btn', 'weekly-goal-form');
            setupModal('daily-goal-modal', 'open-daily-goal-modal-btn', 'close-daily-goal-modal-btn', 'daily-goal-form');
            
            // Silme Modalı butonlarını ayarla
            document.getElementById('confirm-delete-btn').addEventListener('click', confirmDelete);
            document.getElementById('cancel-delete-btn').addEventListener('click', () => { closeModal('delete-confirm-modal'); state.itemToDelete = null; });
            
            // YENİ EKLENDİ: Kaydırma (scroll) sırasında tooltip'i gizle
            document.querySelectorAll('.overflow-y-auto').forEach(el => {
                el.addEventListener('scroll', hideTooltip);
            });
            
            console.log('Uygulama başarıyla yüklendi.');
        }
            // --- BU FONKSİYONU GÜNCELLE (Task Modalı açılırken Tarihi otomatik doldur) ---
        function setupModal(modalId, openBtnId, closeBtnId, formId) {
            const modal = document.getElementById(modalId);
            const openBtn = document.getElementById(openBtnId);
            const closeBtn = document.getElementById(closeBtnId);
            const form = document.getElementById(formId);
            if (!modal || !openBtn || !closeBtn || !form) { console.error(`Modal elementleri bulunamadı (${modalId}, ${openBtnId}). ID'leri kontrol edin.`); return; }
            
            openBtn.addEventListener('click', () => {
                
                // Hiyerarşik kontroller (Bunlar zaten vardı)
                if (modalId === 'task-modal' && !state.selectedDailyId) { showError("Lütfen önce bir gün seçin."); return; }
                if (modalId === 'annual-goal-modal' && !state.selectedCategoryId) { showError("Lütfen önce bir ana kategori (Sütun 1) seçin."); return; }
                if (modalId === 'monthly-goal-modal' && !state.selectedAnnualId) { showError("Lütfen önce bir yıllık hedef (Sütun 2) seçin."); return; }
                if (modalId === 'weekly-goal-modal' && !state.selectedMonthlyId) { showError("Lütfen önce bir aylık hedef (Sütun 3) seçin."); return; }
                if (modalId === 'daily-goal-modal' && !state.selectedWeeklyId) { showError("Lütfen önce bir haftalık hedef (Sütun 4) seçin."); return; }

                // --- DÜZELTME BURADA ---
                // Eğer "Yeni Görev Ekle" modalını açıyorsak (Düzenleme DEĞİLSE)
                // ve Sütun 5'ten bir tarih seçilmişse, o tarihi modal'a ata.
                if (modalId === 'task-modal' && state.selectedGoalDate && !state.editingItem) {
                    const goalDate = state.selectedGoalDate.split('T')[0]; // '2025-11-07T...' -> '2025-11-07'
                    document.getElementById('task-goal-date').value = goalDate;
                }
                // --- DÜZELTME SONU ---

                modal.classList.remove('hidden');
            });
            
            closeBtn.addEventListener('click', () => closeModal(modalId));
            modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(modalId); });

            // Form gönderimini ilgili fonksiyona bağla (Bu kısım aynı)
            if (formId === 'task-form') form.addEventListener('submit', addNewTask);
            else if (formId === 'category-form') form.addEventListener('submit', addNewCategory);
            else if (formId === 'annual-goal-form') form.addEventListener('submit', addNewAnnualGoal);
            else if (formId === 'monthly-goal-form') form.addEventListener('submit', addNewMonthlyGoal);
            else if (formId === 'weekly-goal-form') form.addEventListener('submit', addNewWeeklyGoal);
            else if (formId === 'daily-goal-form') form.addEventListener('submit', addNewDailyGoal);
        }

            // --- DOM HAZIR OLDUĞUNDA UYGULAMAYI BAŞLAT ---
        document.addEventListener('DOMContentLoaded', function() {
            // DÜZELTME: HTS JavaScript'ini SADECE dashboard'da çalıştır.
            // ('col-1', HTS arayüzümüzün ana sütunudur)
            if (document.getElementById('col-1')) {
                initApp();
            }
        });

        </script>
    </body>
</html>