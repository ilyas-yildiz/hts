<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}"> 

        <title>{{ config('app.name', 'HTS') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

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
        
        /* Seçili öğe stili */
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
        
        /* Sütun 1-5 */
        .item-text {
            word-break: break-word;
            white-space: normal;
        }

        /* Sütun 6 */
        .task-desc {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* 2 satırla sınırla */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
            cursor: help; 
        }
        
        /* Tooltip Kapsayıcısı */
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
        
        /* GÜNCELLEME: 'cursor: grab' sadece masaüstünde (lg) aktif olacak */
        @media (min-width: 1024px) {
            .task-item { cursor: grab; }
            .task-item:active { cursor: grabbing; }
        }
        /* Mobilde sürükleme ikonu (handle) için cursor */
        .drag-handle {
            cursor: move; /* (veya grab) */
        }
    </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="h-full font-sans antialiased" style="background-color: #1f2937;">
        <div class="h-full flex flex-col">
            <main class="flex-1 min-h-0">
                {{ $slot }}
            </main>
        </div>

<script>
    // --- GLOBAL STATE ---
    const state = {
        selectedCategoryId: null,
        selectedAnnualId: null,
        selectedMonthlyId: null,
        selectedWeeklyId: null,
        selectedDailyId: null,
        itemToDelete: null,
        editingItem: null,
        isAgendaMode: false,
        categoriesCache: [],
        selectedGoalDate: null,
        breadcrumb: [] 
    };

    // --- YARDIMCI FONKSİYONLAR ---
    function isMobile() {
        return window.innerWidth < 1024;
    }

    async function fetchData(endpoint, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const defaultHeaders = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', };
        if (csrfToken) { defaultHeaders['X-CSRF-TOKEN'] = csrfToken; }
        const url = endpoint; 
        
        try {
            const response = await fetch(url, { ...options, headers: { ...defaultHeaders, ...options.headers, }, });

            if (!response.ok) { 
                const errorData = await response.json(); 
                console.error(`API Hatası (${response.status}): ${response.statusText}`, errorData);
                throw errorData; 
            }
            
            if (response.status === 204) { return null; }
            return await response.json(); 

        } catch (error) {
            if (error.message) {
                throw error;
            }
            console.error('Fetch Hatası:', endpoint, error);
            showError('Ağ hatası: Veri alınırken bir hata oluştu. Konsolu kontrol edin.');
            throw error; 
        }
    }
        
    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return str.toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
    }

    function formatDateTR(dateString) {
        if (!dateString) return ''; 
        
        try {
            const date = new Date(dateString + 'T00:00:00'); 
            
            const day = date.toLocaleDateString('tr-TR', { day: 'numeric' });
            const month = date.toLocaleDateString('tr-TR', { month: 'short' });
            const weekday = date.toLocaleDateString('tr-TR', { weekday: 'short' });
            
            return `${day} ${month} ${weekday}`;
        } catch (e) {
            console.error("Tarih formatlama hatası:", dateString, e);
            return dateString;
        }
    }

    const globalTooltip = document.getElementById('global-tooltip');

    function showTooltip(e) {
        const el = e.currentTarget;
        const tooltipText = el.getAttribute('data-tooltip');
        
        if (!tooltipText) return; 

        globalTooltip.innerHTML = tooltipText;
        
        const rect = el.getBoundingClientRect();
        let top = rect.bottom + 5; 
        let left = rect.left + 5; 

        if (el.classList.contains('tooltip-align-left')) {
            left = rect.right - 300 - 5; 
        }
        
        globalTooltip.style.left = `${left}px`;
        globalTooltip.style.top = `${top}px`;
        globalTooltip.classList.remove('hidden');
        setTimeout(() => globalTooltip.style.opacity = '1', 10); 
    }

    function hideTooltip() {
        globalTooltip.style.opacity = '0';
        setTimeout(() => globalTooltip.classList.add('hidden'), 200); 
    }

    function renderBreadcrumb() {
        const container = document.getElementById('hts-breadcrumb-container');
        if (!container) return; 
        
        container.innerHTML = ''; 

        const labels = [
            "5 Yıllık Hedef:", 
            "Yıllık Hedef:",   
            "Aylık Hedef:",    
            "Haftalık Hedef:", 
            "Günlük Hedef:"    
        ];

        if (state.isAgendaMode) {
            container.innerHTML = `<div class="text-white font-semibold">Bugün'ün Ajandası</div>`;
            return;
        }

        if (state.breadcrumb.length === 0) {
            const baseItem = document.createElement('div');
            baseItem.className = 'text-gray-400 text-sm';
            baseItem.textContent = 'Ana Kategoriler';
            container.appendChild(baseItem);
            return;
        }
        
        state.breadcrumb.forEach((item, index) => {
            const isLast = index === state.breadcrumb.length - 1;
            const breadcrumbItem = document.createElement('div');
            
            let itemClass = 'text-sm ';
            if (isLast) {
                itemClass += 'text-white font-medium';
            } else {
                itemClass += 'text-blue-400 hover:text-blue-300 cursor-pointer';
                breadcrumbItem.dataset.level = index + 1; 
            }
            breadcrumbItem.className = itemClass;

            breadcrumbItem.innerHTML = `
                <span class="text-gray-400">${labels[index]}</span> 
                ${escapeHTML(item.name)}
            `;

            if (!isLast) {
                breadcrumbItem.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const targetLevel = parseInt(e.currentTarget.dataset.level, 10); 
                    
                    state.breadcrumb = state.breadcrumb.slice(0, targetLevel);
                    
                    showColumn(targetLevel);
                    
                    renderBreadcrumb();
                });
            }
            
            container.appendChild(breadcrumbItem);
        });
    }


    // --- DATA LOADING FUNCTIONS ---

    async function fetchCategories() {
        console.log('fetchCategories çağrıldı.');
        const data = await fetchData('/api/goal-categories'); 
        
        if (data) { 
            console.log('Kategoriler yüklendi:', data);
            
            state.categoriesCache = data;
            
            const todayAgenda = { 
                id: 'TODAY', 
                name: 'Bugün', 
                is_completed: 0, 
                order_index: -1 
            };
            
            let displayData = [todayAgenda, ...data];
            
            renderList('list-col-1', displayData); 
        } 
        else { console.error('Kategoriler yüklenemedi, data null.'); }
    }

    async function fetchAnnualGoals(categoryId) {
            console.log(`fetchAnnualGoals çağrıldı (Kategori ID: ${categoryId})`);
            const data = await fetchData(`/api/annual-goals/${categoryId}`);
            if (data) { renderList('list-col-2', data); }
    }
    async function fetchMonthlyGoals(annualGoalId) {
            console.log(`fetchMonthlyGoals çağrıldı (Yıllık ID: ${annualGoalId})`);
            const data = await fetchData(`/api/monthly-goals/${annualGoalId}`);
            if (data) { renderList('list-col-3', data); }
    }
    async function fetchWeeklyGoals(monthlyGoalId) {
            console.log(`fetchWeeklyGoals çağrıldı (Aylık ID: ${monthlyGoalId})`);
            const data = await fetchData(`/api/weekly-goals/${monthlyGoalId}`);
            if (data) { renderList('list-col-4', data); }
    }
    async function fetchDailyGoals(weeklyGoalId) {
            console.log(`fetchDailyGoals çağrıldı (Haftalık ID: ${weeklyGoalId})`);
            const data = await fetchData(`/api/daily-goals/${weeklyGoalId}`);
            if (data) { renderList('list-col-5', data); }
    }

 async function fetchTasks(dailyGoalId) {
        console.log(`fetchTasks çağrıldı (Günlük ID: ${dailyGoalId})`);
        const data = await fetchData(`/api/tasks/${dailyGoalId}`);
        const listElement = document.getElementById('list-col-6');
        
        if (data && data.length > 0) {
            listElement.innerHTML = '';
            data.forEach(task => {
                const item = document.createElement('div');
                item.className = `task-item flex items-center justify-between p-0 rounded-md bg-gray-700 shadow ${task.is_completed ? 'completed' : ''}`;
                item.dataset.id = task.id;
                
                const safeDescription = escapeHTML(task.task_description);

                let timeDisplay = '';
                if (task.start_time) { 
                    const startTime = task.start_time.substring(0, 5);
                    if (task.end_time) {
                        const endTime = task.end_time.substring(0, 5);
                        timeDisplay = `${startTime} - ${endTime}`;
                    } else {
                        timeDisplay = startTime;
                    }
                } else {
                    timeDisplay = 'Tüm Gün'; 
                }

                item.innerHTML = `
                    <div class="drag-handle block lg:hidden p-3 text-gray-500" title="Sıralamak için sürükle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <circle cx="4" cy="4" r="1.1"/>
                            <circle cx="8" cy="4" r="1.1"/>
                            <circle cx="12" cy="4" r="1.1"/>
                            <circle cx="4" cy="8" r="1.1"/>
                            <circle cx="8" cy="8" r="1.1"/>
                            <circle cx="12" cy="8" r="1.1"/>
                            <circle cx="4" cy="12" r="1.1"/>
                            <circle cx="8" cy="12" r="1.1"/>
                            <circle cx="12" cy="12" r="1.1"/>
                        </svg>
                    </div>
                    <div class="item-content flex-1 flex items-center min-w-0 p-3">
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
                    <div class="item-actions pr-3">
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
                });
                
                listElement.appendChild(item);

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
    }

async function fetchTodayAgenda() {
        console.log('fetchTodayAgenda çağrıldı (Tüm kategoriler, bugün)');
        
        const data = await fetchData('/api/agenda/today');
        const listElement = document.getElementById('list-col-6');

        if (data && data.length > 0) {
            listElement.innerHTML = '';
            
            data.forEach(task => {
                const item = document.createElement('div');
                item.className = `task-item flex items-center justify-between p-0 rounded-md bg-gray-700 shadow ${task.is_completed ? 'completed' : ''}`;
                item.dataset.id = task.id;
                
                let timeDisplay = '';
                if (task.start_time) {
                    const startTime = task.start_time.substring(0, 5);
                    timeDisplay = task.end_time ? `${startTime} - ${task.end_time.substring(0, 5)}` : startTime;
                } else {
                    timeDisplay = 'Tüm Gün';
                }

                const categoryName = task.goal_category ? task.goal_category.name : 'Kategori Yok';
                
                item.innerHTML = `
                    <div class="drag-handle block lg:hidden p-3 text-gray-500" title="Sıralamak için sürükle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <circle cx="4" cy="4" r="1.1"/>
                            <circle cx="8" cy="4" r="1.1"/>
                            <circle cx="12" cy="4" r="1.1"/>
                            <circle cx="4" cy="8" r="1.1"/>
                            <circle cx="8" cy="8" r="1.1"/>
                            <circle cx="12" cy="8" r="1.1"/>
                            <circle cx="4" cy="12" r="1.1"/>
                            <circle cx="8" cy="12" r="1.1"/>
                            <circle cx="12" cy="12" r="1.1"/>
                        </svg>
                    </div>
                    <div class="item-content flex-1 flex items-center min-w-0 p-3">
                        <input type="checkbox" class="action-checkbox" 
                               title="Tamamlandı olarak işaretle"
                               ${task.is_completed ? 'checked' : ''}>
                        <div class="ml-2">
                            <div class="text-xs font-semibold text-blue-400">${escapeHTML(categoryName)}</div>
                            
                            <div class="text-xs font-semibold text-gray-400">${timeDisplay}</div>
                            <div class="text-sm text-white task-desc">
                                ${task.task_description}
                            </div>
                        </div>
                    </div>
                    <div class="item-actions pr-3">
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
                });
                
                listElement.appendChild(item);

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
            listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Bugün için planlanmış görev yok.</div>`;
        }
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
        console.log(`handleDelete çağrıldı (Tip: ${type}, ID: ${id})`);
        
        state.itemToDelete = { type, id, itemElement };
        document.getElementById('delete-confirm-modal').classList.remove('hidden');
    }

    async function confirmDelete() {
        if (!state.itemToDelete) return; 
        const { type, id, itemElement } = state.itemToDelete;
        let endpoint = '';
        switch (type) {
            case '1': endpoint = 'goal-categories'; break;
            case '2': endpoint = 'annual-goals'; break;
            case '3': endpoint = 'monthly-goals'; break;
            case '4': endpoint = 'weekly-goals'; break;
            case '5': endpoint = 'daily-goals'; break;
            case 'task': endpoint = 'tasks'; break;
            default: console.error('Bilinmeyen silme tipi:', type); state.itemToDelete = null; closeModal('delete-confirm-modal'); return;
        }
        const btn = document.getElementById('confirm-delete-btn');
        btn.disabled = true; btn.textContent = 'Siliniyor...';
        await fetchData(`/api/${endpoint}/${id}`, { method: 'DELETE' });
        itemElement.remove();

        if (state.isAgendaMode && type === 'task') {
            // Ajanda modunda
        } else {
            // Planlama modunda
            if (!isMobile()) {
                // Masaüstü
                switch (type) {
                    case '1': resetColumns(2); renderBreadcrumb(); break; 
                    case '2': resetColumns(3); renderBreadcrumb(); break;
                    case '3': resetColumns(4); renderBreadcrumb(); break;
                    case '4': resetColumns(5); renderBreadcrumb(); break;
                    case '5': resetColumns(6); renderBreadcrumb(); break;
                }
            } else {
                // Mobil
                switch (type) {
                    case '1': state.breadcrumb = []; renderBreadcrumb(); showColumn(1); break;
                    case '2': state.breadcrumb = []; renderBreadcrumb(); showColumn(1); break;
                    case '3': state.breadcrumb = state.breadcrumb.slice(0, 1); renderBreadcrumb(); showColumn(2); break;
                    case '4': state.breadcrumb = state.breadcrumb.slice(0, 2); renderBreadcrumb(); showColumn(3); break;
                    case '5': state.breadcrumb = state.breadcrumb.slice(0, 3); renderBreadcrumb(); showColumn(4); break;
                    case 'task': state.breadcrumb = state.breadcrumb.slice(0, 4); renderBreadcrumb(); showColumn(5); break;
                }
            }
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
   async function addNewWeeklyGoal(e) {
        e.preventDefault();
        if (state.editingItem) { await handleUpdate(e); return; }
        if (!state.selectedMonthlyId) return;
        
        const title = document.getElementById('weekly-goal-title').value.trim();
        const label = document.getElementById('weekly-goal-label').value.trim();
        let startDate = document.getElementById('weekly-goal-start-date').value; // GÜNCELLEME: " 12:00:00" eki kaldırıldı
        
        if (!title || !label) return;

        // GÜNCELLEME: " 12:00:00" bloğu buradan kaldırıldı

        const data = { 
            monthly_goal_id: state.selectedMonthlyId, 
            title: title, 
            week_label: label,
            start_date: startDate || null
        };
        
        const btn = document.getElementById('save-weekly-goal-btn');
        btn.disabled = true; btn.textContent = 'Kaydediliyor...';
        const newWeeklyGoal = await fetchData('/api/weekly-goals', { method: 'POST', body: JSON.stringify(data) });
        if (newWeeklyGoal) { fetchWeeklyGoals(state.selectedMonthlyId); closeModal('weekly-goal-modal'); } 
        else { btn.disabled = false; btn.textContent = 'Kaydet'; }
    }

   async function addNewDailyGoal(e) {
        e.preventDefault();
        if (state.editingItem) { await handleUpdate(e); return; }
        if (!state.selectedWeeklyId) return;
        
        const label = document.getElementById('daily-goal-label').value.trim();
        const title = document.getElementById('daily-goal-title').value.trim();
        let goalDate = document.getElementById('daily-goal-date').value; // GÜNCELLEME: " 12:00:00" eki kaldırıldı
        
        if (!label) return;

        // GÜNCELLEME: " 12:00:00" bloğu buradan kaldırıldı

        const data = { 
            weekly_goal_id: state.selectedWeeklyId, 
            day_label: label, 
            title: title || null,
            goal_date: goalDate || null
        };
        
        const btn = document.getElementById('save-daily-goal-btn');
        btn.disabled = true; btn.textContent = 'Kaydediliyor...';
        const newDailyGoal = await fetchData('/api/daily-goals', { method: 'POST', body: JSON.stringify(data) });
        if (newDailyGoal) { fetchDailyGoals(state.selectedWeeklyId); closeModal('daily-goal-modal'); } 
        else { btn.disabled = false; btn.textContent = 'Kaydet'; }
    }

async function addNewTask(e) {
        e.preventDefault(); 
        if (state.editingItem) { await handleUpdate(e); return; }
        
        document.getElementById('task-modal-error').classList.add('hidden');
        
        const desc = document.getElementById('task-desc').value;
        let goalDate = document.getElementById('task-goal-date').value;
        const startTime = document.getElementById('task-start-time').value;
        const endTime = document.getElementById('task-end-time').value;

        let categoryId;
        if (state.isAgendaMode) {
            categoryId = document.getElementById('task-goal-category').value;
        } else {
            categoryId = state.selectedCategoryId;
        }

        if (!desc || !categoryId || !goalDate) {
            showError('Görev açıklaması, Kategori (Proje) ve Tarih zorunludur.');
            return;
        }
        
        const data = {
            goal_category_id: categoryId,
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
                if (state.isAgendaMode) {
                    fetchTodayAgenda();
                } else {
                    fetchTasks(state.selectedDailyId);
                }
                closeModal('task-modal');
            }
        } catch (error) {
            if (error.errors && error.errors.time) {
                const errorDiv = document.getElementById('task-modal-error');
                // GÜNCELLEME: '<strong>Çakışma:</strong>' ön eki kaldırıldı
                if (Array.isArray(error.errors.time)) {
                     errorDiv.innerHTML = error.errors.time.join('<br>');
                } else {
                    errorDiv.textContent = error.errors.time;
                }
                errorDiv.classList.remove('hidden');
            } else {
                console.error('addNewTask Hatası:', error);
                showError('Bilinmeyen bir hata oluştu.');
            }
        } finally {
            btn.disabled = false; btn.textContent = 'Kaydet';
        }
    }

function openEditModal(type, item) {
        state.editingItem = { type, item }; 
        let modalId = '';
        
        const formatTime = (timeString) => {
            if (!timeString) return '';
            return timeString.substring(0, 5);
        };

        switch (type) {
            case '1':
                modalId = 'category-modal';
                document.getElementById('category-name').value = item.name;
                break;
            //... (diğer case'ler aynı) ...
            case '5':
                modalId = 'daily-goal-modal';
                document.getElementById('daily-goal-label').value = item.day_label;
                document.getElementById('daily-goal-title').value = item.title;
                document.getElementById('daily-goal-date').value = item.goal_date; 
                break;
            case 'task':
                modalId = 'task-modal';
                // GÜNCELLEME: Hata mesajını temizle
                document.getElementById('task-modal-error').classList.add('hidden');
                
                document.getElementById('task-goal-date').value = item.goal_date; 
                document.getElementById('task-start-time').value = formatTime(item.start_time);
                document.getElementById('task-end-time').value = formatTime(item.end_time);
                document.getElementById('task-desc').value = item.task_description;
                
                const categorySelector = document.getElementById('task-goal-category');
                populateCategorySelector(categorySelector, item.goal_category_id);
                document.getElementById('task-category-selector').classList.remove('hidden');
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

async function handleUpdate(e) {
        e.preventDefault();
        if (!state.editingItem) return;

        if(state.editingItem.type === 'task') {
             document.getElementById('task-modal-error').classList.add('hidden');
        }

        const { type, item } = state.editingItem;
        let data = {}; let endpoint = ''; let btnId = '';
        try {
            switch (type) {
                case '1': data = { name: document.getElementById('category-name').value }; endpoint = `/api/goal-categories/${item.id}`; btnId = 'save-category-btn'; break;
                case '2': data = { title: document.getElementById('annual-goal-title').value, year: document.getElementById('annual-goal-year').value, period_label: document.getElementById('annual-goal-period').value }; endpoint = `/api/annual-goals/${item.id}`; btnId = 'save-annual-goal-btn'; break;
                case '3': data = { title: document.getElementById('monthly-goal-title').value, month_label: document.getElementById('monthly-goal-label').value }; endpoint = `/api/monthly-goals/${item.id}`; btnId = 'save-monthly-goal-btn'; break;
                
                case '4': 
                    let startDate = document.getElementById('weekly-goal-start-date').value;
                    data = { 
                        title: document.getElementById('weekly-goal-title').value, 
                        week_label: document.getElementById('weekly-goal-label').value,
                        start_date: startDate || null
                    }; 
                    endpoint = `/api/weekly-goals/${item.id}`; 
                    btnId = 'save-weekly-goal-btn'; 
                    break; 
                
                case '5':
                    let goalDate = document.getElementById('daily-goal-date').value;
                    data = { 
                        day_label: document.getElementById('daily-goal-label').value, 
                        title: document.getElementById('daily-goal-title').value || null,
                        goal_date: goalDate || null
                    }; 
                    endpoint = `/api/daily-goals/${item.id}`; 
                    btnId = 'save-daily-goal-btn'; 
                    break; 
                
                case 'task':
                    let taskGoalDate = document.getElementById('task-goal-date').value;
                    data = {
                        goal_category_id: document.getElementById('task-goal-category').value,
                        goal_date: taskGoalDate,
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
                
                if (state.isAgendaMode) {
                    fetchTodayAgenda();
                } else if (type === 'task') { fetchTasks(state.selectedDailyId); }
                else if (type === '1') { fetchCategories(); }
                else if (type === '2') { fetchAnnualGoals(state.selectedCategoryId); }
                else if (type === '3') { fetchMonthlyGoals(state.selectedAnnualId); }
                else if (type === '4') { fetchWeeklyGoals(state.selectedMonthlyId); }
                else if (type === '5') { fetchDailyGoals(state.selectedWeeklyId); }

                if (state.breadcrumb.length > 0 && !state.isAgendaMode) {
                    let newName = '';
                    switch (type) {
                        case '1': newName = updatedItem.name; break;
                        case '2': newName = updatedItem.title; break; 
                        case '3': newName = updatedItem.title; break; 
                        case '4': newName = updatedItem.title; break; 
                        case '5': newName = (updatedItem.title || updatedItem.day_label); break; 
                    }
                    if (newName && state.breadcrumb[state.breadcrumb.length - 1].level == type) {
                        state.breadcrumb[state.breadcrumb.length - 1].name = newName;
                        renderBreadcrumb();
                    }
                }

                closeModal(btn.closest('.fixed').id);
            }
        } catch (error) {
            if (type === 'task' && error.errors && error.errors.time) {
                const errorDiv = document.getElementById('task-modal-error');
                // GÜNCELLEME: '<strong>Çakışma:</strong>' ön eki kaldırıldı
                if (Array.isArray(error.errors.time)) {
                     errorDiv.innerHTML = error.errors.time.join('<br>');
                } else {
                    errorDiv.textContent = error.errors.time;
                }
                errorDiv.classList.remove('hidden');
            } else {
                console.error('Güncelleme hatası:', error);
                showError('Bilinmeyen bir hata oluştu.');
            }
            if (btnId) { const btn = document.getElementById(btnId); btn.disabled = false; btn.textContent = 'Güncelle'; }
        }
    }

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

        // SortableJS ayarlarını hazırla
        const options = {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            // TEMEL FİLTRE: Butonlar ASLA sürüklememeli (her iki platformda da)
            filter: '.action-checkbox, .action-edit, .action-delete', 
            onEnd: function (evt) {
                handleReorder(modelType, listElement);
            }
        };

        // GÜNCELLEME: Mobil ve Masaüstü filtre/handle mantığı düzeltildi
        if (isMobile()) {
            // MOBİLDE:
            // 1. Sadece ikondan sürükle
            options.handle = '.drag-handle';
            // 2. İçeriğe dokunmak sürüklemeyi BAŞLATMASIN (kaydırma için)
            options.filter += ', .item-content'; 
        } else {
            // MASAÜSTÜNDE (Desktop):
            // 1. 'handle' ayarı yok (tüm öğe sürükler)
            // 2. Gizli olan sürükleme ikonuna tıklamak sürüklemeyi BAŞLATMASIN
            options.filter += ', .drag-handle';
            // 3. (ÖNEMLİ) .item-content'i FİLTRELEME, böylece sürükleme yapılabilir.
        }

        listElement.sortableInstance = new Sortable(listElement, options);
    }

    // --- UI (ARAYÜZ) HELPERS ---

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
            div.className = 'task-item p-0 rounded-md hover:bg-gray-700 transition-colors duration-150 flex justify-between items-center';
            div.dataset.id = item.id;
            if (item.is_completed) { div.classList.add('completed'); }
            
            let topText = ''; let bottomText = ''; let bottomFontSizeClass = 'text-sm text-white'; 
            switch (listType) {
                case '1': 
                    topText = (item.id === 'TODAY') ? '' : ''; 
                    bottomText = item.name; 
                    break;
                case '2': topText = `Yıl ${item.year}: ${item.period_label}`; bottomText = item.title; break;
                case '3': topText = item.month_label; bottomText = item.title; break;
                case '4': topText = item.start_date ? formatDateTR(item.start_date) : item.week_label; bottomText = item.title; break;
                case '5': topText = item.goal_date ? formatDateTR(item.goal_date) : item.day_label; bottomText = item.title || ''; break;
            }
            
            div.innerHTML = `
                <div class="drag-handle block lg:hidden p-3 text-gray-500" title="Sıralamak için sürükle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <circle cx="4" cy="4" r="1.1"/>
                        <circle cx="8" cy="4" r="1.1"/>
                        <circle cx="12" cy="4" r="1.1"/>
                        <circle cx="4" cy="8" r="1.1"/>
                        <circle cx="8" cy="8" r="1.1"/>
                        <circle cx="12" cy="8" r="1.1"/>
                        <circle cx="4" cy="12" r="1.1"/>
                        <circle cx="8" cy="12" r="1.1"/>
                        <circle cx="12" cy="12" r="1.1"/>
                    </svg>
                </div>
                
                <div class="item-content flex-1 flex items-center min-w-0 p-3">
                    ${item.id !== 'TODAY' ? `<input type="checkbox" class="action-checkbox" title="Tamamlandı olarak işaretle" ${item.is_completed ? 'checked' : ''}>` : '<div class="w-6"></div>'}
                    
                    <div class="ml-2" title="${topText ? topText + ': ' : ''}${bottomText}">
                        ${topText ? `<div class="text-xs font-semibold text-gray-400">${topText}</div>` : ''}
                        <div class="item-text ${bottomFontSizeClass}">${bottomText}</div>
                    </div>
                </div>
                <div class="item-actions pr-3">
                    ${item.id !== 'TODAY' ? `
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
                    ` : ''}
                </div>
            `;

            const content = div.querySelector('.item-content');
            const checkbox = div.querySelector('.action-checkbox');
            const editBtn = div.querySelector('.action-edit');
            const deleteBtn = div.querySelector('.action-delete');
            
            if (checkbox) {
                checkbox.addEventListener('click', (e) => { e.stopPropagation(); const isCompleted = e.target.checked; div.classList.toggle('completed', isCompleted); handleToggleGoal(listType, item.id, isCompleted); });
            }

            content.addEventListener('click', (e) => {
                if (e.target.tagName.toLowerCase() === 'input') return;
                e.currentTarget.closest('.flex-1.overflow-y-auto').querySelectorAll('.task-item').forEach(el => el.classList.remove('selected'));
                div.classList.add('selected');

                switch (listType) {
                    case '1': 
                        if (item.id === 'TODAY') {
                            state.isAgendaMode = true;
                            state.breadcrumb = []; 
                            resetColumns(2); 
                            
                            if (isMobile()) {
                                showColumn(6); 
                            } else {
                                document.getElementById('col-6').classList.add('col-span-5'); 
                                showColumn(6); 
                            }
                            fetchTodayAgenda(); 
                        } else {
                            state.isAgendaMode = false;
                            state.selectedCategoryId = item.id;
                            state.breadcrumb = [{ level: 1, name: item.name }]; 
                            resetColumns(2);
                            showColumn(2); 
                            fetchAnnualGoals(item.id);
                        }
                        break;
                    case '2': 
                        state.isAgendaMode = false; state.selectedAnnualId = item.id; 
                        state.breadcrumb = state.breadcrumb.slice(0, 1); 
                        state.breadcrumb.push({ level: 2, name: item.title }); 
                        resetColumns(3); 
                        showColumn(3); 
                        fetchMonthlyGoals(item.id); 
                        break;
                    case '3': 
                        state.isAgendaMode = false; state.selectedMonthlyId = item.id; 
                        state.breadcrumb = state.breadcrumb.slice(0, 2); 
                        state.breadcrumb.push({ level: 3, name: item.title }); 
                        resetColumns(4); 
                        showColumn(4); 
                        fetchWeeklyGoals(item.id); 
                        break;
                    case '4': 
                        state.isAgendaMode = false; state.selectedWeeklyId = item.id; 
                        state.breadcrumb = state.breadcrumb.slice(0, 3); 
                        state.breadcrumb.push({ level: 4, name: item.title }); 
                        resetColumns(5); 
                        showColumn(5); 
                        fetchDailyGoals(item.id); 
                        break;
                    case '5': 
                        state.isAgendaMode = false; state.selectedDailyId = item.id; 
                        state.selectedGoalDate = item.goal_date; 
                        state.breadcrumb = state.breadcrumb.slice(0, 4); 
                        state.breadcrumb.push({ level: 5, name: (item.title || item.day_label) }); 
                        resetColumns(6); 
                        showColumn(6); 
                        fetchTasks(item.id); 
                        break;
                }
                
                renderBreadcrumb();
            });

            if (editBtn) {
                editBtn.addEventListener('click', (e) => { e.stopPropagation(); openEditModal(listType, item); });
            }
            if (deleteBtn) {
                deleteBtn.addEventListener('click', (e) => { e.stopPropagation(); handleDelete(listType, item.id, div); });
            }

            listElement.appendChild(div);
            
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
        
        if (isMobile()) {
            for (let i = startColumnIndex; i <= 6; i++) {
                document.getElementById(`list-col-${i}`).innerHTML = '';
            }
        } else {
            for (let i = startColumnIndex; i <= 6; i++) {
                document.getElementById(`col-${i}`).classList.add('hidden');
                document.getElementById(`list-col-${i}`).innerHTML = '';
            }
        }
        
        document.getElementById('col-6').classList.remove('col-span-5');

        if (startColumnIndex > 0) {
            state.breadcrumb = state.breadcrumb.slice(0, startColumnIndex - 1);
        }
    }
    
    function showColumn(colIndex) {
        if (isMobile()) {
            for (let i = 1; i <= 6; i++) {
                const col = document.getElementById(`col-${i}`);
                if (i === colIndex) {
                    col.classList.remove('hidden');
                    col.classList.add('flex'); 
                } else {
                    col.classList.add('hidden');
                    col.classList.remove('flex');
                }
            }
        } else {
            document.getElementById(`col-${colIndex}`).classList.remove('hidden');
            document.getElementById(`col-${colIndex}`).classList.add('flex');
        }
    }

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
            
            if (modalId === 'task-modal') {
                document.getElementById('task-category-selector').classList.add('hidden');
                // GÜNCELLEME: Hata mesajını temizle
                document.getElementById('task-modal-error').classList.add('hidden');
            }
        }
    }

    function showError(message) { console.error('UYGULAMA HATASI:', message); }

    function populateCategorySelector(selectElement, selectedId = null) {
        selectElement.innerHTML = '<option value="">Lütfen bir kategori seçin...</option>'; 
        
        if (state.categoriesCache.length > 0) {
            state.categoriesCache.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                if (category.id === selectedId) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            });
        }
    }


function setupModal(modalId, openBtnId, closeBtnId, formId) {
        const modal = document.getElementById(modalId);
        const openBtn = document.getElementById(openBtnId);
        const closeBtn = document.getElementById(closeBtnId);
        const form = document.getElementById(formId);
        if (!modal || !openBtn || !closeBtn || !form) { console.error(`Modal elementleri bulunamadı (${modalId}, ${openBtnId}). ID'leri kontrol edin.`); return; }
        
        openBtn.addEventListener('click', () => {
            
            if (modalId === 'annual-goal-modal' && !state.selectedCategoryId) { showError("Lütfen önce bir ana kategori (Sütun 1) seçin."); return; }
            if (modalId === 'monthly-goal-modal' && !state.selectedAnnualId) { showError("Lütfen önce bir yıllık hedef (Sütun 2) seçin."); return; }
            if (modalId === 'weekly-goal-modal' && !state.selectedMonthlyId) { showError("Lütfen önce bir aylık hedef (Sütun 3) seçin."); return; }
            if (modalId === 'daily-goal-modal' && !state.selectedWeeklyId) { showError("Lütfen önce bir haftalık hedef (Sütun 4) seçin."); return; }
            
            if (modalId === 'task-modal') {
                // GÜNCELLEME: Hata mesajını temizle
                document.getElementById('task-modal-error').classList.add('hidden');

                if (state.isAgendaMode) {
                    const categorySelector = document.getElementById('task-goal-category');
                    populateCategorySelector(categorySelector);
                    document.getElementById('task-category-selector').classList.remove('hidden');
                    document.getElementById('task-goal-date').value = new Date().toISOString().split('T')[0];
                    
                } else {
                    if (!state.selectedDailyId) { showError("Lütfen önce bir gün seçin."); return; }
                    document.getElementById('task-category-selector').classList.add('hidden');
                    
                    const goalDate = state.selectedGoalDate ? state.selectedGoalDate.split('T')[0] : '';
                    document.getElementById('task-goal-date').value = goalDate;
                }
            }
            
            modal.classList.remove('hidden');
        });
        
        closeBtn.addEventListener('click', () => closeModal(modalId));
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(modalId); });

        if (formId === 'task-form') form.addEventListener('submit', addNewTask);
        else if (formId === 'category-form') form.addEventListener('submit', addNewCategory);
        else if (formId === 'annual-goal-form') form.addEventListener('submit', addNewAnnualGoal);
        else if (formId === 'monthly-goal-form') form.addEventListener('submit', addNewMonthlyGoal);
        else if (formId === 'weekly-goal-form') form.addEventListener('submit', addNewWeeklyGoal);
        else if (formId === 'daily-goal-form') form.addEventListener('submit', addNewDailyGoal);
    }
    
    async function initApp() {
        console.log('Uygulama başlıyor (initApp)...');

        if (isMobile()) {
            showColumn(1);
        } else {
            resetColumns(2); 
        }
        
        await fetchCategories();
        
        renderBreadcrumb();

        setupModal('task-modal', 'open-task-modal-btn', 'close-task-modal-btn', 'task-form');
        setupModal('category-modal', 'open-category-modal-btn', 'close-category-modal-btn', 'category-form');
        setupModal('annual-goal-modal', 'open-annual-goal-modal-btn', 'close-annual-goal-modal-btn', 'annual-goal-form');
        setupModal('monthly-goal-modal', 'open-monthly-goal-modal-btn', 'close-monthly-goal-modal-btn', 'monthly-goal-form');
        setupModal('weekly-goal-modal', 'open-weekly-goal-modal-btn', 'close-weekly-goal-modal-btn', 'weekly-goal-form');
        setupModal('daily-goal-modal', 'open-daily-goal-modal-btn', 'close-daily-goal-modal-btn', 'daily-goal-form');
        
        document.getElementById('confirm-delete-btn').addEventListener('click', confirmDelete);
        document.getElementById('cancel-delete-btn').addEventListener('click', () => { closeModal('delete-confirm-modal'); state.itemToDelete = null; });
        
        document.getElementById('back-to-col-1').addEventListener('click', () => {
            state.breadcrumb = []; 
            renderBreadcrumb();
            showColumn(1);
        });
        document.getElementById('back-to-col-2').addEventListener('click', () => {
            state.breadcrumb = state.breadcrumb.slice(0, 1); 
            renderBreadcrumb();
            showColumn(2);
        });
        document.getElementById('back-to-col-3').addEventListener('click', () => {
            state.breadcrumb = state.breadcrumb.slice(0, 2); 
            renderBreadcrumb();
            showColumn(3);
        });
        document.getElementById('back-to-col-4').addEventListener('click', () => {
            state.breadcrumb = state.breadcrumb.slice(0, 3); 
            renderBreadcrumb();
            showColumn(4);
        });
        
        document.getElementById('back-btn-col-6').addEventListener('click', () => {
            if (state.isAgendaMode) {
                state.isAgendaMode = false; 
                state.breadcrumb = [];
                renderBreadcrumb();
                showColumn(1);
            } else {
                state.breadcrumb = state.breadcrumb.slice(0, 4); 
                renderBreadcrumb();
                showColumn(5);
            }
        });

        document.querySelectorAll('.overflow-y-auto').forEach(el => {
            el.addEventListener('scroll', hideTooltip);
        });
        
        console.log('Uygulama başarıyla yüklendi.');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('col-1')) {
            initApp();
        }
    });

</script>
    </body>
</html>