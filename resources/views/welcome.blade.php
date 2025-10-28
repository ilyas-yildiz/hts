<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hedef Takip Sistemi</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font (Inter) -->
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
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #374151; /* gray-700 */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #6b7280; /* gray-500 */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af; /* gray-400 */
        }
        /* Özel seçili öğe stili */
        .list-item.selected {
            background-color: #3b82f6; /* Mavi (blue-500) */
            color: white;
            font-weight: 500;
        }
        /* Görev tamamlandı stili */
        .task-item.completed {
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

    <!-- Header -->
    <header class="flex-shrink-0 bg-gray-900 shadow-md">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Başlık -->
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12M3.75 3h16.5M3.75 3v16.5M19.5 3c.621 0 1.125.504 1.125 1.125v14.625c0 .621-.504 1.125-1.125 1.125H9.75a1.125 1.125 0 0 1-1.125-1.125V17.25m8.625-14.625H6a2.25 2.25 0 0 0-2.25 2.25v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V3.375c0-.209-.056-.412-.16-.587l-.092-.148Z" />
                    </svg>
                    <span class="ml-3 text-2xl font-semibold text-white">Hedef Takip Sistemi</span>
                </div>
                <!-- Sağ Menü -->
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

    <!-- Ana İçerik Alanı -->
    <main class="flex-1 flex min-h-0">
        <!-- Kolonlar -->
        <div class="flex-1 grid grid-cols-6 min-h-0 min-w-0 max-w-screen-2xl mx-auto">
            
            <!-- Kolon 1: 5 Yıllık Hedef Kategorileri -->
            <div id="col-1" class="column flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">5 Yıllık Hedefler</h2>
                    <p class="text-sm text-gray-400">Ana Kategoriler</p>
                </div>
                <div id="list-col-1" class="flex-1 overflow-y-auto p-2 space-y-1">
                    <!-- Veri JavaScript ile buraya yüklenecek -->
                    <div class="p-4 text-center text-gray-500">Yükleniyor...</div>
                </div>
            </div>

            <!-- Kolon 2: Yıllar -->
            <div id="col-2" class="column flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-2" class="text-lg font-semibold text-white truncate">Yıllar</h2>
                    <p class="text-sm text-gray-400">Yıllık Hedef Dağılımı</p>
                </div>
                <div id="list-col-2" class="flex-1 overflow-y-auto p-2 space-y-1">
                    <!-- Veri JavaScript ile buraya yüklenecek -->
                </div>
            </div>

            <!-- Kolon 3: Aylar -->
            <div id="col-3" class="column flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-3" class="text-lg font-semibold text-white truncate">Aylar</h2>
                    <p class="text-sm text-gray-400">Aylık Hedefler (1. Yıl)</p>
                </div>
                <div id="list-col-3" class="flex-1 overflow-y-auto p-2 space-y-1">
                    <!-- Veri JavaScript ile buraya yüklenecek -->
                </div>
            </div>

            <!-- Kolon 4: Haftalar -->
            <div id="col-4" class="column flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-4" class="text-lg font-semibold text-white truncate">Haftalar</h2>
                    <p class="text-sm text-gray-400">Haftalık Hedefler (1. Ay)</p>
                </div>
                <div id="list-col-4" class="flex-1 overflow-y-auto p-2 space-y-1">
                    <!-- Veri JavaScript ile buraya yüklenecek -->
                </div>
            </div>

            <!-- Kolon 5: Günler -->
            <div id="col-5" class="column flex flex-col border-r border-gray-700">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-5" class="text-lg font-semibold text-white truncate">Günler</h2>
                    <p class="text-sm text-gray-400">Günlük Hedefler (1. Hafta)</p>
                </div>
                <div id="list-col-5" class="flex-1 overflow-y-auto p-2 space-y-1">
                    <!-- Veri JavaScript ile buraya yüklenecek -->
                </div>
            </div>

            <!-- Kolon 6: Saatler / Görevler -->
            <div id="col-6" class="column flex flex-col">
                <div class="flex-shrink-0 p-4 border-b border-gray-700">
                    <h2 id="title-col-6" class="text-lg font-semibold text-white truncate">Görevler</h2>
                    <p class="text-sm text-gray-400">Günlük Plan</p>
                </div>
                <div id="list-col-6" class="flex-1 overflow-y-auto p-2 space-y-1">
                    <!-- Veri JavaScript ile buraya yüklenecek -->
                </div>
                <div class="p-2 border-t border-gray-700">
                    <button id="open-task-modal-btn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Yeni Görev Ekle
                    </button>
                </div>
            </div>

        </div>
    </main>

    <!-- Yeni Görev Ekleme Modalı -->
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
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- GLOBAL STATE ---
        // Seçili olan öğelerin ID'lerini tutmak için bir global nesne.
        // Bu, hiyerarşiyi ve API isteklerini yönetmemize yardımcı olacak.
        const state = {
            selectedCategoryId: null,
            selectedAnnualId: null,
            selectedMonthlyId: null,
            selectedWeeklyId: null,
            selectedDailyId: null,
        };

        // --- API HELPERS ---

        /**
         * API'den veri çekmek için merkezi fonksiyon.
         * Hataları yakalar ve JSON olarak döndürür.
         * @param {string} endpoint - /api/ ile başlayan tam yol (örn: /api/goal-categories)
         * @param {object} [options={}] - fetch() için opsiyonel ayarlar (method, body, headers)
         */
        async function fetchData(endpoint, options = {}) {
            // CSRF token'ı her istek için başlığa ekle
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const defaultHeaders = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                // Laravel'in POST/PUT/DELETE istekleri için CSRF koruması
                // Blade'e <meta name="csrf-token" content="{{ csrf_token() }}"> eklenmeli.
                // Şimdilik GET için gerekmiyor ama POST için (görev ekleme) gerekecek.
            };

            // Blade'de CSRF meta tag'ı varsa X-CSRF-TOKEN başlığını ekle
            // Şu an HTML'de bu meta tag yok, eklenince çalışacak.
            // POST/PUT/DELETE işlemleri için bu şart.
            if (csrfToken) {
                defaultHeaders['X-CSRF-TOKEN'] = csrfToken;
            }

            // URL'leri birleştirirken çift // olmasını engelle
            // ÖNEMLİ: 'split' hatasını düzelten kısım burası.
            // '/api/goal-categories' gibi tam yolu bekliyoruz.
            // '/api' base URL'i artık burada birleştirilmiyor, doğrudan kullanılıyor.
            const url = endpoint; // Örn: /api/goal-categories

            try {
                const response = await fetch(url, {
                    ...options,
                    headers: {
                        ...defaultHeaders,
                        ...options.headers,
                    },
                });

                if (!response.ok) {
                    // API 4xx veya 5xx bir hata döndürürse
                    const errorData = await response.json();
                    console.error(`API Hatası (${response.status}): ${response.statusText}`, errorData);
                    throw new Error(`API Hatası: ${response.statusText}`);
                }
                
                // response.json() bir Promise döndürür, bu yüzden await kullanılmalı
                return await response.json(); 

            } catch (error) {
                // Network hatası veya fetch/json parse hatası
                console.error('Fetch Hatası:', endpoint, error);
                showError('Veri alınırken bir hata oluştu. Lütfen konsolu kontrol edin.');
                return null; // Hata durumunda null döndür
            }
        }
        
        // --- DATA LOADING FUNCTIONS ---

        /**
         * Kolon 1: Ana kategorileri yükler (Kişisel Gelişim, Finansal vb.)
         */
        async function fetchCategories() {
            console.log('fetchCategories çağrıldı.');
            const data = await fetchData('/api/goal-categories');
            if (data) {
                console.log('Kategoriler yüklendi:', data);
                renderList('list-col-1', data, (item) => {
                    // Tıklandığında
                    state.selectedCategoryId = item.id;
                    resetColumns(2); // 2. sütundan sonrasını temizle/gizle
                    fetchAnnualGoals(item.id); // 2. sütunu doldur
                    document.getElementById('title-col-2').textContent = item.name;
                });
            } else {
                console.error('Kategoriler yüklenemedi, data null.');
            }
        }

        /**
         * Kolon 2: Yıllık hedefleri yükler (Seçili kategori ID'sine göre)
         */
        async function fetchAnnualGoals(categoryId) {
            console.log(`fetchAnnualGoals çağrıldı (Kategori ID: ${categoryId})`);
            const data = await fetchData(`/api/annual-goals/${categoryId}`);
            if (data) {
                console.log('Yıllık Hedefler yüklendi:', data);
                renderList('list-col-2', data, (item) => {
                    // Tıklandığında
                    state.selectedAnnualId = item.id;
                    resetColumns(3);
                    
                    // Sadece 1. Yıl (year == 1) ise Ayları (Kolon 3) yükle
                    if (item.year === 1) {
                        fetchMonthlyGoals(item.id);
                        document.getElementById('title-col-3').textContent = item.period_label;
                    } else {
                        // 2. yıl veya sonrasına tıklandıysa, "Planlama daha sonra" mesajı göster
                        document.getElementById('list-col-3').innerHTML = 
                            `<div class="p-4 text-center text-gray-500">
                                Bu yılın aylık planlaması henüz aktif değil.
                            </div>`;
                    }
                }, 'period_label'); // Görüntülenecek metin alanı: period_label
                
                showColumn(2); // Sütunu göster
            }
        }

        /**
         * Kolon 3: Aylık hedefleri yükler (Seçili yıllık hedef ID'sine göre)
         */
        async function fetchMonthlyGoals(annualGoalId) {
            console.log(`fetchMonthlyGoals çağrıldı (Yıllık ID: ${annualGoalId})`);
            const data = await fetchData(`/api/monthly-goals/${annualGoalId}`);
            if (data) {
                console.log('Aylık Hedefler yüklendi:', data);
                renderList('list-col-3', data, (item) => {
                    // Tıklandığında
                    state.selectedMonthlyId = item.id;
                    resetColumns(4);
                    // TODO: Sadece ilk ay ise haftaları yükle (backend'den gelen veriye göre)
                    fetchWeeklyGoals(item.id);
                    document.getElementById('title-col-4').textContent = item.month_label;
                }, 'month_label');
                
                showColumn(3); // Sütunu göster
            }
        }

        /**
         * Kolon 4: Haftalık hedefleri yükler (Seçili aylık hedef ID'sine göre)
         */
        async function fetchWeeklyGoals(monthlyGoalId) {
            console.log(`fetchWeeklyGoals çağrıldı (Aylık ID: ${monthlyGoalId})`);
            const data = await fetchData(`/api/weekly-goals/${monthlyGoalId}`);
            if (data) {
                console.log('Haftalık Hedefler yüklendi:', data);
                renderList('list-col-4', data, (item) => {
                    // Tıklandığında
                    state.selectedWeeklyId = item.id;
                    resetColumns(5);
                    // TODO: Sadece ilk hafta ise günleri yükle
                    fetchDailyGoals(item.id);
                    document.getElementById('title-col-5').textContent = item.week_label;
                }, 'week_label');
                
                showColumn(4); // Sütunu göster
            }
        }

        /**
         * Kolon 5: Günlük hedefleri yükler (Seçili haftalık hedef ID'sine göre)
         */
        async function fetchDailyGoals(weeklyGoalId) {
            console.log(`fetchDailyGoals çağrıldı (Haftalık ID: ${weeklyGoalId})`);
            const data = await fetchData(`/api/daily-goals/${weeklyGoalId}`);
            if (data) {
                console.log('Günlük Hedefler yüklendi:', data);
                renderList('list-col-5', data, (item) => {
                    // Tıklandığında
                    state.selectedDailyId = item.id; // Görev eklerken bu ID'yi kullanacağız
                    resetColumns(6);
                    fetchTasks(item.id);
                    document.getElementById('title-col-6').textContent = `Görevler - ${item.day_label}`;
                }, 'day_label');
                
                showColumn(5); // Sütunu göster
            }
        }

        /**
         * Kolon 6: Görevleri (Task) yükler (Seçili günlük hedef ID'sine göre)
         */
        async function fetchTasks(dailyGoalId) {
            console.log(`fetchTasks çağrıldı (Günlük ID: ${dailyGoalId})`);
            const data = await fetchData(`/api/tasks/${dailyGoalId}`);
            const listElement = document.getElementById('list-col-6');
            
            if (data && data.length > 0) {
                console.log('Görevler yüklendi:', data);
                listElement.innerHTML = ''; // Listeyi temizle
                data.forEach(task => {
                    const item = document.createElement('div');
                    item.className = `task-item flex items-center justify-between p-3 rounded-md bg-gray-700 shadow ${task.is_completed ? 'completed' : ''}`;
                    item.dataset.id = task.id;
                    
                    item.innerHTML = `
                        <div class="flex-1">
                            <div class="text-xs font-semibold text-gray-400">${task.time_label}</div>
                            <div class="text-sm text-white">${task.task_description}</div>
                        </div>
                        <input type="checkbox" class="task-checkbox ml-4 h-5 w-5 rounded bg-gray-600 border-gray-500 text-blue-500 focus:ring-blue-500" 
                               ${task.is_completed ? 'checked' : ''}>
                    `;
                    
                    // Checkbox'a tıklama olayı (Görevi tamamlandı/tamamlanmadı olarak işaretle)
                    item.querySelector('.task-checkbox').addEventListener('change', async (e) => {
                        await toggleTaskStatus(task.id, e.target.checked);
                        // Arayüzü anında güncelle
                        item.classList.toggle('completed', e.target.checked);
                    });
                    
                    listElement.appendChild(item);
                });
            } else {
                console.log('Bu gün için görev bulunamadı.');
                listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Bu gün için planlanmış görev yok.</div>`;
            }
            
            showColumn(6); // Sütunu göster
        }

        // --- TASK (GÖREV) ACTIONS ---

        /**
         * Bir görevin 'is_completed' durumunu API üzerinden günceller (toggle).
         */
        async function toggleTaskStatus(taskId, isCompleted) {
            console.log(`toggleTaskStatus çağrıldı (Task ID: ${taskId}, Durum: ${isCompleted})`);
            // Not: 'is_completed' verisi şu an API'ye gönderilmiyor, 
            // TaskController'daki 'toggle' metodu mevcut durumu tersine çeviriyor.
            // Bu, 'update' metodu için daha uygun olabilir ama 'toggle' için yeterli.
            await fetchData(`/api/tasks/toggle/${taskId}`, {
                method: 'PUT'
            });
        }

        /**
         * Yeni bir görev ekler.
         */
        async function addNewTask(e) {
            e.preventDefault(); // Formun sayfayı yenilemesini engelle
            
            const time = document.getElementById('task-time').value;
            const desc = document.getElementById('task-desc').value;
            
            if (!desc || !state.selectedDailyId) {
                showError('Görev açıklaması boş olamaz veya bir gün seçili değil.');
                return;
            }
            
            const data = {
                daily_goal_id: state.selectedDailyId,
                time_label: time || "Zamanlanmamış", // Zaman boşsa varsayılan değer
                task_description: desc
            };
            
            console.log('Yeni görev gönderiliyor:', data);

            // POST isteği için CSRF token'ı eklememiz GEREKİYOR.
            // Blade şablonunuzun <head> kısmına bunu ekleyin:
            // <meta name="csrf-token" content="{{ csrf_token() }}">
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.warn('CSRF Token meta tag bulunamadı. POST isteği başarısız olabilir.');
                // Geliştirme aşamasında CSRF'i devre dışı bıraktıysanız (app/Http/Middleware/VerifyCsrfToken.php),
                // bu bir sorun olmaz. Ama production'da bu şarttır.
            } else {
                console.log('CSRF Token bulundu ve isteğe eklenecek.');
            }
            
            const newTask = await fetchData('/api/tasks', {
                method: 'POST',
                headers: {
                    // CSRF token'ı (eğer varsa) 'fetchData' fonksiyonu tarafından
                    // otomatik eklenmeye çalışılacak. Manuel ekleyelim:
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });
            
            if (newTask) {
                console.log('Görev eklendi:', newTask);
                // Görev listesini yeniden yükle
                fetchTasks(state.selectedDailyId);
                // Modalı kapat ve formu temizle
                closeModal();
            }
        }


        // --- UI (ARAYÜZ) HELPERS ---

        /**
         * Verilen bir listeyi (array) DOM'a (HTML listesine) render eder.
         * @param {string} listId - (list-col-1, list-col-2 vb.)
         * @param {Array<object>} data - API'den gelen veri dizisi
         * @param {Function} onClickCallback - Her öğeye tıklanınca çalışacak fonksiyon
         * @param {string} [textField='name'] - Öğenin metni için kullanılacak alan (örn: 'name', 'period_label')
         */
        function renderList(listId, data, onClickCallback, textField = 'name') {
            const listElement = document.getElementById(listId);
            listElement.innerHTML = ''; // Önce listeyi temizle

            if (!data || data.length === 0) {
                listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Veri bulunamadı.</div>`;
                return;
            }

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'list-item p-3 rounded-md cursor-pointer hover:bg-gray-700 transition-colors duration-150 flex justify-between items-center';
                div.dataset.id = item.id;
                
                // Görüntülenecek metin (textField veya item.title)
                // (GoalController'dan gelen 'title' alanını da hesaba katıyoruz)
                div.textContent = item[textField] || item.title || 'İsimsiz';

                // Sağdaki > ikonu
                const icon = document.createElement('span');
                icon.className = 'text-gray-500';
                icon.innerHTML = '&gt;';
                div.appendChild(icon);

                // Tıklama olayı
                div.addEventListener('click', (e) => {
                    // Tüm kardeş öğelerden 'selected' sınıfını kaldır
                    e.currentTarget.parentElement.querySelectorAll('.list-item').forEach(el => {
                        el.classList.remove('selected');
                    });
                    // Sadece tıklanan öğeye 'selected' sınıfını ekle
                    e.currentTarget.classList.add('selected');
                    
                    // Verilen callback fonksiyonunu çalıştır
                    onClickCallback(item);
                });

                listElement.appendChild(div);
            });
        }

        /**
         * Belirtilen sütun numarasından (dahil) başlayarak tüm sütunları gizler ve temizler.
         * Örn: resetColumns(3) -> 3, 4, 5, 6. sütunları gizler ve listelerini boşaltır.
         * @param {number} startColumnIndex - Gizlemeye başlanacak sütun numarası (1-6)
         */
        function resetColumns(startColumnIndex) {
            console.log(`resetColumns çağrıldı (Başlangıç: ${startColumnIndex})`);
            for (let i = startColumnIndex; i <= 6; i++) {
                document.getElementById(`col-${i}`).classList.add('hidden');
                document.getElementById(`list-col-${i}`).innerHTML = '';
                document.getElementById(`title-col-${i}`).textContent = document.getElementById(`title-col-${i}`).parentElement.querySelector('p').textContent.split('(')[0].trim(); // Başlığı sıfırla
            }
        }
        
        /**
         * Belirtilen sütunu görünür yapar.
         * @param {number} colIndex - Gösterilecek sütun numarası (1-6)
         */
        function showColumn(colIndex) {
            document.getElementById(`col-${colIndex}`).classList.remove('hidden');
        }

        /**
         * "Yeni Görev Ekle" modalını yönetir.
         */
        function setupModal() {
            console.log('setupModal çağrıldı.');
            const modal = document.getElementById('task-modal');
            const openBtn = document.getElementById('open-task-modal-btn');
            const closeBtn = document.getElementById('close-task-modal-btn');
            const form = document.getElementById('task-form');

            if (modal && openBtn && closeBtn && form) {
                openBtn.addEventListener('click', () => {
                    if (!state.selectedDailyId) {
                        showError("Lütfen önce bir gün seçin.");
                        return;
                    }
                    modal.classList.remove('hidden');
                });
                
                closeBtn.addEventListener('click', closeModal);
                form.addEventListener('submit', addNewTask);
                
                // Modala tıklayınca değil, dışına tıklayınca kapat
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });

            } else {
                console.error('Modal elementleri (butonlar, form veya modalın kendisi) DOM\'da bulunamadı.');
            }
        }

        /**
         * Modalı kapatır ve formu temizler
         */
        function closeModal() {
            document.getElementById('task-modal').classList.add('hidden');
            document.getElementById('task-form').reset();
        }

        /**
         * Hata mesajı göster (Geçici, daha iyi bir UI ile değiştirilebilir)
         */
        function showError(message) {
            // alert() kullanmak yerine konsola ve geçici bir UI elemanına yaz
            console.error('UYGULAMA HATASI:', message);
            // İleride buraya şık bir "toast" bildirimi eklenebilir.
        }

        /**
         * Uygulamayı başlatan ana fonksiyon.
         */
        async function initApp() {
            console.log('Uygulama başlıyor (initApp)...');
            
            // "Boş Sütunlar" hatasını çözmek için:
            // Önce sütunları gizle ve veriyi yükle, modal ayarlarını sonra yap.
            
            // 1. Başlangıçta 2. sütundan sonrasını gizle
            resetColumns(2); 
            
            // 2. İlk kolonun verisini (Kategoriler) API'dan çek
            // Not: await kullanmak, initApp'in bu adımdan önce bitmemesini sağlar.
            await fetchCategories();
            
            // 3. Modal butonları ve form için event listener'ları ayarla
            setupModal();
            
            console.log('Uygulama başarıyla yüklendi.');
        }

        // --- DOM HAZIR OLDUĞUNDA UYGULAMAYI BAŞLAT ---
        document.addEventListener('DOMContentLoaded', initApp);

    </script>
</body>
</html>


