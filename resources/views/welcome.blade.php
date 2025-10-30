<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hedef Takip Sistemi</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        /* Özel seçili öğe stili */
        .list-item.selected {
            background-color: #3b82f6; /* Mavi (blue-500) */
            color: white;
            font-weight: 500;
        }
        /* Görev tamamlandı stili */
        .task-item.completed .task-desc { /* Sadece metni etkile */
            text-decoration: line-through;
            color: #6b7280; /* gray-500 */
        }
        /* Sütunların varsayılan olarak gizlenmesi için */
        .column.hidden {
            display: none;
        }
    </style>
</head>
<body class="h-full flex flex-col">

    <header class="flex-shrink-0 bg-gray-900 shadow-md">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12M3.75 3h16.5M3.75 3v16.5M19.5 3c.621 0 1.125.504 1.125 1.125v14.625c0 .621-.504 1.125-1.125 1.125H9.75a1.125 1.125 0 0 1-1.125-1.125V17.25m8.625-14.625H6a2.25 2.25 0 0 0-2.25 2.25v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V3.375c0-.209-.056-.412-.16-.587l-.092-.148Z" />
                    </svg>
                    <span class="ml-3 text-2xl font-semibold text-white">Hedef Takip Sistemi</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300">İlyas Yıldız</span>
                    <button class="p-2 rounded-full text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                    </button>
                    <button class="p-2 rounded-full text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        <span class="sr-only">Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 flex min-h-0">
        <div class="flex-1 grid grid-cols-6 min-h-0 min-w-0 max-w-screen-2xl mx-auto">
            
            <div id="col-1" class="column flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">5 Yıllık Hedefler</h2>
                    <p class="text-sm text-gray-400">Ana Kategoriler</p>
                </div>
                <div id="list-col-1" class="flex-1 overflow-y-auto p-2 space-y-1">
                    <div class="p-4 text-center text-gray-500">Yükleniyor...</div>
                </div>
                <div class="p-2 border-t border-gray-700">
                    <button id="open-category-modal-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Yeni Kategori Ekle
                    </button>
                </div>
            </div>

            <div id="col-2" class="column hidden flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-2" class="text-lg font-semibold text-white truncate">Yıllar</h2>
                    <p class="text-sm text-gray-400">Yıllık Hedef Dağılımı</p>
                </div>
                <div id="list-col-2" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
                
                <div class="p-2 border-t border-gray-700">
                    <button id="open-annual-goal-modal-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Yeni Yıllık Hedef Ekle
                    </button>
                </div>
            </div>

           <div id="col-3" class="column hidden flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-3" class="text-lg font-semibold text-white truncate">Aylar</h2>
                    <p class="text-sm text-gray-400">Aylık Hedefler (1. Yıl)</p>
                </div>
                <div id="list-col-3" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
                
                <div class="p-2 border-t border-gray-700">
                    <button id="open-monthly-goal-modal-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Yeni Aylık Hedef Ekle
                    </button>
                </div>
            </div>

            <div id="col-4" class="column hidden flex flex-col border-r border-gray-700">
                 <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-4" class="text-lg font-semibold text-white truncate">Haftalar</h2>
                    <p class="text-sm text-gray-400">Haftalık Hedefler (1. Ay)</p>
                </div>
                <div id="list-col-4" class="flex-1 overflow-y-auto p-2 space-y-1"></div>

                <div class="p-2 border-t border-gray-700">
                    <button id="open-weekly-goal-modal-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Yeni Haftalık Hedef Ekle
                    </button>
                </div>
            </div>

           <div id="col-5" class="column hidden flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-5" class="text-lg font-semibold text-white truncate">Günler</h2>
                    <p class="text-sm text-gray-400">Günlük Hedefler (1. Hafta)</p>
                </div>
                <div id="list-col-5" class="flex-1 overflow-y-auto p-2 space-y-1"></div>

                <div class="p-2 border-t border-gray-700">
                    <button id="open-daily-goal-modal-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Yeni Günlük Hedef Ekle
                    </button>
                </div>
            </div>

            <div id="col-6" class="column hidden flex flex-col">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-6" class="text-lg font-semibold text-white truncate">Görevler</h2>
                    <p class="text-sm text-gray-400">Günlük Plan</p>
                </div>
                <div id="list-col-6" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
                <div class="p-2 border-t border-gray-700">
                    <button id="open-task-modal-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Yeni Görev Ekle
                    </button>
                </div>
            </div>

        </div>
    </main>

    <div id="category-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4 text-white">Yeni Kategori Ekle</h3>
            <form id="category-form">
                <div class="space-y-4">
                    <div>
                        <label for="category-name" class="block text-sm font-medium text-gray-300">Kategori Adı</label>
                        <input type="text" id="category-name" placeholder="Örn: Sağlıklı Yaşam" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-category-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        İptal
                    </button>
                    <button type="submit" id="save-category-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

<div id="annual-goal-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4 text-white">Yeni Yıllık Hedef Ekle</h3>
            <form id="annual-goal-form">
                <div class="space-y-4">
                    <div>
                        <label for="annual-goal-title" class="block text-sm font-medium text-gray-300">Yıllık Hedef Başlığı</label>
                        <input type="text" id="annual-goal-title" placeholder="Örn: Yılda 50 kitap okuma" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="annual-goal-year" class="block text-sm font-medium text-gray-300">Yıl Sırası (1-5)</label>
                        <input type="number" id="annual-goal-year" min="1" max="5" placeholder="1" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="annual-goal-period" class="block text-sm font-medium text-gray-300">Dönem Etiketi</label>
                        <input type="text" id="annual-goal-period" placeholder="Örn: Eylül 2026 Sonu" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-annual-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        İptal
                    </button>
                    <button type="submit" id="save-annual-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="monthly-goal-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4 text-white">Yeni Aylık Hedef Ekle</h3>
            <form id="monthly-goal-form">
                <div class="space-y-4">
                    <div>
                        <label for="monthly-goal-title" class="block text-sm font-medium text-gray-300">Aylık Hedef Başlığı</label>
                        <input type="text" id="monthly-goal-title" placeholder="Örn: 4 kitap bitir" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="monthly-goal-label" class="block text-sm font-medium text-gray-300">Ay Etiketi</label>
                        <input type="text" id="monthly-goal-label" placeholder="Örn: Ekim 2025" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-monthly-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        İptal
                    </button>
                    <button type="submit" id="save-monthly-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
<div id="weekly-goal-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4 text-white">Yeni Haftalık Hedef Ekle</h3>
            <form id="weekly-goal-form">
                <div class="space-y-4">
                    <div>
                        <label for="weekly-goal-title" class="block text-sm font-medium text-gray-300">Haftalık Hedef Başlığı</label>
                        <input type="text" id="weekly-goal-title" placeholder="Örn: 1. Kitaba başla" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="weekly-goal-label" class="block text-sm font-medium text-gray-300">Hafta Etiketi</label>
                        <input type="text" id="weekly-goal-label" placeholder="Örn: 1. Hafta (1-7 Ekim)" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-weekly-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        İptal
                    </button>
                    <button type="submit" id="save-weekly-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="daily-goal-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4 text-white">Yeni Günlük Hedef Ekle</h3>
            <form id="daily-goal-form">
                <div class="space-y-4">
                    <div>
                        <label for="daily-goal-label" class="block text-sm font-medium text-gray-300">Gün Etiketi</label>
                        <input type="text" id="daily-goal-label" placeholder="Örn: Pazartesi" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="daily-goal-title" class="block text-sm font-medium text-gray-300">Günün Ana Hedefi (Opsiyonel)</label>
                        <input type="text" id="daily-goal-title" placeholder="Örn: Backend Rotaları" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-daily-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        İptal
                    </button>
                    <button type="submit" id="save-daily-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="task-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4 text-white">Yeni Görev Ekle</h3>
            <form id="task-form">
                <div class="space-y-4">
                    <div>
                        <label for="task-time" class="block text-sm font-medium text-gray-300">Zaman Aralığı</label>
                        <input type="text" id="task-time" placeholder="Örn: 09:00 - 10:00" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="task-desc" class="block text-sm font-medium text-gray-300">Görev Açıklaması</label>
                        <textarea id="task-desc" rows="3" placeholder="Örn: Proje planı revize edilecek" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-task-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        İptal
                    </button>
                    <button type="submit" id="save-task-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ... (Tüm HTML kodunuz bittikten sonra) ... -->

<script>
    // --- GLOBAL STATE ---
    const state = {
        selectedCategoryId: null,
        selectedAnnualId: null,
        selectedMonthlyId: null,
        selectedWeeklyId: null,
        selectedDailyId: null,
    };

    // --- API HELPERS ---
    async function fetchData(endpoint, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const defaultHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        if (csrfToken) {
            defaultHeaders['X-CSRF-TOKEN'] = csrfToken;
        }

        const url = endpoint; 

        try {
            const response = await fetch(url, {
                ...options,
                headers: {
                    ...defaultHeaders,
                    ...options.headers,
                },
            });

            if (!response.ok) {
                const errorData = await response.json();
                console.error(`API Hatası (${response.status}): ${response.statusText}`, errorData);
                throw new Error(`API Hatası: ${response.statusText}`);
            }
            
            if (response.status === 204) {
                return null;
            }

            return await response.json(); 

        } catch (error) {
            console.error('Fetch Hatası:', endpoint, error);
            showError('Veri alınırken bir hata oluştu. Lütfen konsolu kontrol edin.');
            return null;
        }
    }
    
    // --- DATA LOADING FUNCTIONS ---
    async function fetchCategories() {
        console.log('fetchCategories çağrıldı.');
        const data = await fetchData('/api/goal-categories');
        if (data) {
            console.log('Kategoriler yüklendi:', data);
            renderList('list-col-1', data, (item) => {
                state.selectedCategoryId = item.id;
                resetColumns(2);
                fetchAnnualGoals(item.id);
                document.getElementById('title-col-2').textContent = item.name;
            });
        } else {
            console.error('Kategoriler yüklenemedi, data null.');
        }
    }

    async function fetchAnnualGoals(categoryId) {
        console.log(`fetchAnnualGoals çağrıldı (Kategori ID: ${categoryId})`);
        const data = await fetchData(`/api/annual-goals/${categoryId}`);
        if (data) {
            console.log('Yıllık Hedefler yüklendi:', data);
            renderList('list-col-2', data, (item) => {
                state.selectedAnnualId = item.id;
                resetColumns(3);
                
                if (item.year === 1) {
                    fetchMonthlyGoals(item.id);
                    document.getElementById('title-col-3').textContent = item.period_label;
                } else {
                    document.getElementById('list-col-3').innerHTML = 
                        `<div class="p-4 text-center text-gray-500">
                            Bu yılın aylık planlaması henüz aktif değil.
                        </div>`;
                }
            }, 'period_label');
            
            showColumn(2);
        }
    }

    async function fetchMonthlyGoals(annualGoalId) {
        console.log(`fetchMonthlyGoals çağrıldı (Yıllık ID: ${annualGoalId})`);
        const data = await fetchData(`/api/monthly-goals/${annualGoalId}`);
        if (data) {
            renderList('list-col-3', data, (item) => {
                state.selectedMonthlyId = item.id;
                resetColumns(4);
                fetchWeeklyGoals(item.id);
                document.getElementById('title-col-4').textContent = item.month_label;
            }, 'month_label');
            showColumn(3);
        }
    }

    async function fetchWeeklyGoals(monthlyGoalId) {
        console.log(`fetchWeeklyGoals çağrıldı (Aylık ID: ${monthlyGoalId})`);
        const data = await fetchData(`/api/weekly-goals/${monthlyGoalId}`);
        if (data) {
            renderList('list-col-4', data, (item) => {
                state.selectedWeeklyId = item.id;
                resetColumns(5);
                fetchDailyGoals(item.id);
                document.getElementById('title-col-5').textContent = item.week_label;
            }, 'week_label');
            showColumn(4);
        }
    }

    async function fetchDailyGoals(weeklyGoalId) {
        console.log(`fetchDailyGoals çağrıldı (Haftalık ID: ${weeklyGoalId})`);
        const data = await fetchData(`/api/daily-goals/${weeklyGoalId}`);
        if (data) {
            renderList('list-col-5', data, (item) => {
                state.selectedDailyId = item.id;
                resetColumns(6);
                fetchTasks(item.id);
                document.getElementById('title-col-6').textContent = `Görevler - ${item.day_label}`;
            }, 'day_label');
            showColumn(5);
        }
    }

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
                
                item.innerHTML = `
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-400">${task.time_label || ''}</div>
                        <div class="text-sm text-white task-desc">${task.task_description}</div>
                    </div>
                    <input type="checkbox" class="task-checkbox ml-4 h-5 w-5 rounded bg-gray-600 border-gray-500 text-blue-500 focus:ring-blue-500" 
                           ${task.is_completed ? 'checked' : ''}>
                `;
                
                item.querySelector('.task-checkbox').addEventListener('change', async (e) => {
                    await toggleTaskStatus(task.id, e.target.checked);
                    item.classList.toggle('completed', e.target.checked);
                });
                
                listElement.appendChild(item);
            });
        } else {
            listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Bu gün için planlanmış görev yok.</div>`;
        }
        
        showColumn(6);
    }

    // --- TASK (GÖREV) ACTIONS ---
    async function toggleTaskStatus(taskId, isCompleted) {
        console.log(`toggleTaskStatus çağrıldı (Task ID: ${taskId}, Durum: ${isCompleted})`);
        await fetchData(`/api/tasks/toggle/${taskId}`, {
            method: 'PUT',
            body: JSON.stringify({ is_completed: isCompleted })
        });
    }

    async function addNewTask(e) {
        e.preventDefault(); 
        const time = document.getElementById('task-time').value;
        const desc = document.getElementById('task-desc').value;
        
        if (!desc || !state.selectedDailyId) {
            showError('Görev açıklaması boş olamaz veya bir gün seçili değil.');
            return;
        }
        
        const data = {
            daily_goal_id: state.selectedDailyId,
            time_label: time || "Zamanlanmamış",
            task_description: desc
        };
        
        const btn = document.getElementById('save-task-btn');
        btn.disabled = true;
        btn.textContent = 'Kaydediliyor...';

        const newTask = await fetchData('/api/tasks', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        btn.disabled = false;
        btn.textContent = 'Kaydet';

        if (newTask) {
            console.log('Görev eklendi:', newTask);
            fetchTasks(state.selectedDailyId); // Listeyi yenile
            closeModal('task-modal'); // Modalı kapat
        }
    }
    
    async function addNewCategory(e) {
        e.preventDefault();
        const nameInput = document.getElementById('category-name');
        const name = nameInput.value.trim();
        
        if (!name) {
            showError('Kategori adı boş olamaz.');
            return;
        }
        
        const data = { name: name };
        
        const btn = document.getElementById('save-category-btn');
        btn.disabled = true;
        btn.textContent = 'Kaydediliyor...';

        const newCategory = await fetchData('/api/goal-categories', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        btn.disabled = false;
        btn.textContent = 'Kaydet';

        if (newCategory) {
            console.log('Kategori eklendi:', newCategory);
            fetchCategories(); 
            closeModal('category-modal'); 
        }
    }
    
    async function addNewAnnualGoal(e) {
        e.preventDefault();
        
        if (!state.selectedCategoryId) {
            showError('Lütfen önce bir ana kategori (Sütun 1) seçin.');
            return;
        }
        
        const title = document.getElementById('annual-goal-title').value.trim();
        const year = document.getElementById('annual-goal-year').value;
        const period_label = document.getElementById('annual-goal-period').value.trim();
        
        if (!title || !year || !period_label) {
            showError('Lütfen tüm alanları doldurun.');
            return;
        }
        
        const data = {
            goal_category_id: state.selectedCategoryId,
            title: title,
            year: parseInt(year, 10),
            period_label: period_label
        };
        
        const btn = document.getElementById('save-annual-goal-btn');
        btn.disabled = true;
        btn.textContent = 'Kaydediliyor...';

        const newAnnualGoal = await fetchData('/api/annual-goals', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        btn.disabled = false;
        btn.textContent = 'Kaydet';

        if (newAnnualGoal) {
            console.log('Yıllık Hedef eklendi:', newAnnualGoal);
            fetchAnnualGoals(state.selectedCategoryId); 
            closeModal('annual-goal-modal'); 
        }
    }

    // --- EKSİK OLAN FONKSİYON BURADA ---
    async function addNewMonthlyGoal(e) {
        e.preventDefault();
        
        if (!state.selectedAnnualId) {
            showError('Lütfen önce bir yıllık hedef (Sütun 2) seçin.');
            return;
        }
        
        const title = document.getElementById('monthly-goal-title').value.trim();
        const label = document.getElementById('monthly-goal-label').value.trim();
        
        if (!title || !label) {
            showError('Lütfen tüm alanları doldurun.');
            return;
        }
        
        const data = {
            annual_goal_id: state.selectedAnnualId,
            title: title,
            month_label: label
        };
        
        const btn = document.getElementById('save-monthly-goal-btn');
        btn.disabled = true;
        btn.textContent = 'Kaydediliyor...';

        const newMonthlyGoal = await fetchData('/api/monthly-goals', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        btn.disabled = false;
        btn.textContent = 'Kaydet';

        if (newMonthlyGoal) {
            console.log('Aylık Hedef eklendi:', newMonthlyGoal);
            fetchMonthlyGoals(state.selectedAnnualId); 
            closeModal('monthly-goal-modal'); 
        }
    }

    // --- YENİ SÜTUN 4 FONKSİYONU BURADA ---
    async function addNewWeeklyGoal(e) {
        e.preventDefault();
        
        if (!state.selectedMonthlyId) {
            showError('Lütfen önce bir aylık hedef (Sütun 3) seçin.');
            return;
        }
        
        const title = document.getElementById('weekly-goal-title').value.trim();
        const label = document.getElementById('weekly-goal-label').value.trim();
        
        if (!title || !label) {
            showError('Lütfen tüm alanları doldurun.');
            return;
        }
        
        const data = {
            monthly_goal_id: state.selectedMonthlyId,
            title: title,
            week_label: label
        };
        
        const btn = document.getElementById('save-weekly-goal-btn');
        btn.disabled = true;
        btn.textContent = 'Kaydediliyor...';

        const newWeeklyGoal = await fetchData('/api/weekly-goals', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        btn.disabled = false;
        btn.textContent = 'Kaydet';

        if (newWeeklyGoal) {
            console.log('Haftalık Hedef eklendi:', newWeeklyGoal);
            fetchWeeklyGoals(state.selectedMonthlyId); 
            closeModal('weekly-goal-modal'); 
        }
    }

    /**
         * YENİ: Yeni bir Günlük Hedef (DailyGoal) ekler.
         */
        async function addNewDailyGoal(e) {
            e.preventDefault();
            
            // 1. Haftalık Hedef seçili mi diye kontrol et
            if (!state.selectedWeeklyId) {
                showError('Lütfen önce bir haftalık hedef (Sütun 4) seçin.');
                return;
            }
            
            // 2. Form verilerini al
            const label = document.getElementById('daily-goal-label').value.trim();
            const title = document.getElementById('daily-goal-title').value.trim();
            
            if (!label) {
                showError('Gün etiketi (Örn: Pazartesi) zorunludur.');
                return;
            }
            
            const data = {
                weekly_goal_id: state.selectedWeeklyId, // Bir üstteki Haftalık Hedef ID'si
                day_label: label,
                title: title || null // Opsiyonel olduğu için boşsa null gönder
            };
            
            // 3. API'ye gönder
            const btn = document.getElementById('save-daily-goal-btn');
            btn.disabled = true;
            btn.textContent = 'Kaydediliyor...';

            const newDailyGoal = await fetchData('/api/daily-goals', {
                method: 'POST',
                body: JSON.stringify(data)
            });
            
            btn.disabled = false;
            btn.textContent = 'Kaydet';

            if (newDailyGoal) {
                console.log('Günlük Hedef eklendi:', newDailyGoal);
                // Sütun 5'i yenile (mevcut seçili haftalık hedef için)
                fetchDailyGoals(state.selectedWeeklyId); 
                closeModal('daily-goal-modal'); // Modalı kapat
            }
        }

    // --- UI (ARAYÜZ) HELPERS ---
    
    // --- renderList (DÜZELTİLMİŞ FORMATLI HALİ) ---
    function renderList(listId, data, onClickCallback, textField = 'name') {
        const listElement = document.getElementById(listId);
        listElement.innerHTML = ''; 

        if (!data || data.length === 0) {
            listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Veri bulunamadı.</div>`;
            return;
        }

        data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'list-item p-3 rounded-md cursor-pointer hover:bg-gray-700 transition-colors duration-150 flex justify-between items-center';
            div.dataset.id = item.id;
            
            let displayText = item[textField] || item.title || 'İsimsiz';
            
            // Sütunlara göre özel formatlama
            if (listId === 'list-col-2' && item.year && item.title) {
                displayText = `Yıl ${item.year}: ${item.title}`;
            } else if (listId === 'list-col-3' && item.month_label && item.title) {
                displayText = `${item.month_label}: ${item.title}`;
            } else if (listId === 'list-col-4' && item.week_label && item.title) {
                displayText = `${item.week_label}: ${item.title}`;
            } else if (listId === 'list-col-5' && item.day_label) {
                displayText = item.title ? `${item.day_label}: ${item.title}` : item.day_label;
            }

            div.textContent = displayText;

            const icon = document.createElement('span');
            icon.className = 'text-gray-500';
            icon.innerHTML = '&gt;';
            div.appendChild(icon);

            div.addEventListener('click', (e) => {
                e.currentTarget.parentElement.querySelectorAll('.list-item').forEach(el => {
                    el.classList.remove('selected');
                });
                e.currentTarget.classList.add('selected');
                onClickCallback(item);
            });

            listElement.appendChild(div);
        });
    }

    function resetColumns(startColumnIndex) {
        console.log(`resetColumns çağrıldı (Başlangıç: ${startColumnIndex})`);
        for (let i = startColumnIndex; i <= 6; i++) {
            document.getElementById(`col-${i}`).classList.add('hidden');
            document.getElementById(`list-col-${i}`).innerHTML = '';
            const titleEl = document.getElementById(`title-col-${i}`);
            if (titleEl) {
                titleEl.textContent = titleEl.parentElement.querySelector('p').textContent.split('(')[0].trim();
            }
        }
    }
    
    function showColumn(colIndex) {
        document.getElementById(`col-${colIndex}`).classList.remove('hidden');
    }

 // --- BU FONKSİYONU GÜNCELLE ---
        function setupModal(modalId, openBtnId, closeBtnId, formId) {
            const modal = document.getElementById(modalId);
            const openBtn = document.getElementById(openBtnId);
            const closeBtn = document.getElementById(closeBtnId);
            const form = document.getElementById(formId);

            if (!modal || !openBtn || !closeBtn || !form) {
                console.error(`Modal elementleri bulunamadı (${modalId}, ${openBtnId}). ID'leri kontrol edin.`);
                return;
            }

            openBtn.addEventListener('click', () => {
                // Hiyerarşik kontroller
                if (modalId === 'task-modal' && !state.selectedDailyId) {
                    showError("Lütfen önce bir gün seçin.");
                    return;
                }
                if (modalId === 'annual-goal-modal' && !state.selectedCategoryId) {
                    showError("Lütfen önce bir ana kategori (Sütun 1) seçin.");
                    return;
                }
                if (modalId === 'monthly-goal-modal' && !state.selectedAnnualId) {
                    showError("Lütfen önce bir yıllık hedef (Sütun 2) seçin.");
                    return;
                }
                if (modalId === 'weekly-goal-modal' && !state.selectedMonthlyId) {
                    showError("Lütfen önce bir aylık hedef (Sütun 3) seçin.");
                    return;
                }
                // GÜNLÜK HEDEF MODALI İÇİN YENİ KONTROL:
                if (modalId === 'daily-goal-modal' && !state.selectedWeeklyId) {
                    showError("Lütfen önce bir haftalık hedef (Sütun 4) seçin.");
                    return;
                }
                modal.classList.remove('hidden');
            });
            
            closeBtn.addEventListener('click', () => closeModal(modalId));
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal(modalId);
                }
            });

            // Form gönderimini ilgili fonksiyona bağla
            if (formId === 'task-form') {
                form.addEventListener('submit', addNewTask);
            } else if (formId === 'category-form') {
                form.addEventListener('submit', addNewCategory);
            } else if (formId === 'annual-goal-form') {
                form.addEventListener('submit', addNewAnnualGoal);
            } else if (formId === 'monthly-goal-form') {
                form.addEventListener('submit', addNewMonthlyGoal);
            } else if (formId === 'weekly-goal-form') { 
                form.addEventListener('submit', addNewWeeklyGoal);
            } else if (formId === 'daily-goal-form') { // YENİ EKLENDİ
                form.addEventListener('submit', addNewDailyGoal);
            }
        }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
            }
        }
    }

    function showError(message) {
        console.error('UYGULAMA HATASI:', message);
        alert(message); 
    }

// --- BU FONKSİYONU GÜNCELLE ---
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
            // YENİ MODAL EKLENDİ:
            setupModal('daily-goal-modal', 'open-daily-goal-modal-btn', 'close-daily-goal-modal-btn', 'daily-goal-form');
            
            console.log('Uygulama başarıyla yüklendi.');
        }

    // --- DOM HAZIR OLDUĞUNDA UYGULAMAYI BAŞLAT ---
    document.addEventListener('DOMContentLoaded', initApp);

</script>
</body>
</html>