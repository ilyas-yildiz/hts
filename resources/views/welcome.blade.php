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
     .task-item.selected {
            background-color: #0000005d; /* Mavi (blue-500) */
            color: white;
            font-weight: 500;
        }
        
        /* Sütunların varsayılan olarak gizlenmesi için */
        .column.hidden {
            display: none;
        }

       .task-item.completed .item-text,
        .task-item.completed .task-desc {
            text-decoration: line-through;
            color: #6b7280; /* gray-500 */
        }
        /*
         * ÜZERİNE GELME (HOVER) EFEKTİ
         */
        .item-actions {
            opacity: 0;
            transition: opacity 0.15s ease-in-out;
            flex-shrink: 0; /* Butonların büzülmesini engelle */
            margin-left: 8px; /* Metinle arasında boşluk */
        }
        .list-item:hover .item-actions,
        .task-item:hover .item-actions {
            opacity: 1;
        }
        .action-checkbox {
            height: 1.1rem;
            width: 1.1rem;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #3b82f6;
            border-radius: 4px;
        }
        .action-delete {
            background: none;
            border: none;
            color: #ef4444; /* red-500 */
            cursor: pointer;
            padding: 2px;
        }
        .action-delete:hover {
            color: #f87171; /* red-400 */
        }

        .action-edit {
    background: none;
    border: none;
    color: #f0f9ff; /* light-blue-50 */
    cursor: pointer;
    padding: 2px;
    margin-right: 4px; /* Silme ikonundan ayırmak için */
}
.action-edit:hover {
    color: #60a5fa; /* blue-400 */
}
        
        /* --- DÜZELTME (Metin Kaydırma) --- */
        /* * Hatalı '.item-content' kuralı kaldırıldı.
         * Kural artık Sütun 1-5'in metin sınıfı (.item-text) ve 
         * Sütun 6'nın metin sınıfı (.task-desc) için geçerli.
        */
        .item-text,
        .task-desc {
            word-break: break-word; /* Uzun kelimeleri satır sonunda kır */
            white-space: normal; /* Metnin alt satıra kaymasına izin ver */
        }
        /* --- DÜZELTME SONU --- */
    </style>
</head>
<body class="h-full flex flex-col">

    <header class="flex-shrink-0 bg-gray-900 shadow-md">
        <div class="px-4 sm:px-6 lg:px-8">
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
        <div class="flex-1 grid grid-cols-6 min-h-0 min-w-0 px-4 sm:px-6 lg:px-8">
            
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
                        + 5 Yıllık Hedef Ekle
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
                        + Yıllık Hedef Ekle
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
                        + Aylık Hedef Ekle
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
                        + Haftalık Hedef Ekle
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
                        + Günlük Hedef Ekle
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

    <!-- YENİ MODAL (Silme Onayı) -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-xl font-semibold mb-2 text-white">Silme Onayı</h3>
            <p class="text-gray-300 mb-6">Bu öğeyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancel-delete-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    İptal
                </button>
                <!-- DİKKAT: Bu buton kırmızı (tehlike) renginde -->
                <button type="button" id="confirm-delete-btn" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                    Evet, Sil
                </button>
            </div>
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
            itemToDelete: null,
            editingItem: null // YENİ EKLENDİ (Düzenlenen öğeyi (type, id, data) tutar)
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

    // --- BU FONKSİYONU GÜNCELLE (if (item.year === 1) Kuralı Kaldırıldı) ---

        async function fetchAnnualGoals(categoryId) {
            console.log(`fetchAnnualGoals çağrıldı (Kategori ID: ${categoryId})`);
            const data = await fetchData(`/api/annual-goals/${categoryId}`);
            if (data) {
                console.log('Yıllık Hedefler yüklendi:', data);
                renderList('list-col-2', data, (item) => {
                    state.selectedAnnualId = item.id;
                    resetColumns(3);
                    
                    // DÜZELTME: (item.year === 1) kuralı kaldırıldı.
                    // Artık hangi yıla tıklarsan tıkla (Yıl 2, 3, 4, 5),
                    // Sütun 3 (Aylar) her zaman açılacak.
                    fetchMonthlyGoals(item.id);
                    document.getElementById('title-col-3').textContent = item.period_label;

                }, 'period_label'); // 'period_label' yerine 'title' veya 'year' da kullanabiliriz
                
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

// --- BU FONKSİYONU GÜNCELLE (min-w-0 Düzeltmesi) ---
// --- BU FONKSİYONU GÜNCELLE (Hata 1 Düzeltmesi: 'undefined' sorunu) ---
        async function fetchTasks(dailyGoalId) {
            console.log(`fetchTasks çağrıldı (Günlük ID: ${dailyGoalId})`);
            const data = await fetchData(`/api/tasks/${dailyGoalId}`);
            const listElement = document.getElementById('list-col-6');
            
            if (data && data.length > 0) {
                listElement.innerHTML = '';
                
                // DİKKAT: Buradaki döngü değişkeni 'task', Sütun 1-5'teki 'item' değil.
                data.forEach(task => { 
                    const item = document.createElement('div'); // item = HTML element
                    item.className = `task-item flex items-center justify-between p-3 rounded-md bg-gray-700 shadow ${task.is_completed ? 'completed' : ''}`;
                    item.dataset.id = task.id;
                    
                    item.innerHTML = `
                        <div class="item-content flex-1 flex items-center min-w-0">
                            <input type="checkbox" class="action-checkbox" 
                                   title="Tamamlandı olarak işaretle"
                                   ${task.is_completed ? 'checked' : ''}>
                            <div class="ml-2">
                                <div class="text-xs font-semibold text-gray-400">${task.time_label || ''}</div>
                                <div class="text-sm text-white task-desc">${task.task_description}</div>
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

                    checkbox.addEventListener('change', async (e) => {
                        const isCompleted = e.target.checked;
                        await toggleTaskStatus(task.id, isCompleted);
                        item.classList.toggle('completed', isCompleted);
                    });

                    // DÜZELTME BURADA:
                    // Modal'a HTML element 'item' değil, veri objesi 'task' gönderilmeli.
                    editBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        openEditModal('task', task); // 'item' -> 'task' olarak düzeltildi
                    });
                    
                    deleteBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        handleDelete('task', task.id, item);
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

// --- MEVCUT 6 "addNew..." FONKSİYONUNU SİLİP BUNLARI YAPIŞTIRIN ---

        async function addNewCategory(e) {
            e.preventDefault();
            // DÜZENLEME MODU KONTROLÜ
            if (state.editingItem) {
                await handleUpdate(e);
                return;
            }
            
            const nameInput = document.getElementById('category-name');
            const name = nameInput.value.trim();
            if (!name) return;
            // ... (kalan kod aynı) ...
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
                fetchCategories(); 
                closeModal('category-modal'); 
            }
        }
        
        async function addNewAnnualGoal(e) {
            e.preventDefault();
            // DÜZENLEME MODU KONTROLÜ
            if (state.editingItem) {
                await handleUpdate(e);
                return;
            }
            
            if (!state.selectedCategoryId) return;
            const title = document.getElementById('annual-goal-title').value.trim();
            const year = document.getElementById('annual-goal-year').value;
            const period_label = document.getElementById('annual-goal-period').value.trim();
            if (!title || !year || !period_label) return;
            // ... (kalan kod aynı) ...
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
                fetchAnnualGoals(state.selectedCategoryId); 
                closeModal('annual-goal-modal'); 
            }
        }

        async function addNewMonthlyGoal(e) {
            e.preventDefault();
            // DÜZENLEME MODU KONTROLÜ
            if (state.editingItem) {
                await handleUpdate(e);
                return;
            }
            
            if (!state.selectedAnnualId) return;
            const title = document.getElementById('monthly-goal-title').value.trim();
            const label = document.getElementById('monthly-goal-label').value.trim();
            if (!title || !label) return;
            // ... (kalan kod aynı) ...
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
                fetchMonthlyGoals(state.selectedAnnualId); 
                closeModal('monthly-goal-modal'); 
            }
        }

        async function addNewWeeklyGoal(e) {
            e.preventDefault();
            // DÜZENLEME MODU KONTROLÜ
            if (state.editingItem) {
                await handleUpdate(e);
                return;
            }
            
            if (!state.selectedMonthlyId) return;
            const title = document.getElementById('weekly-goal-title').value.trim();
            const label = document.getElementById('weekly-goal-label').value.trim();
            if (!title || !label) return;
            // ... (kalan kod aynı) ...
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
                fetchWeeklyGoals(state.selectedMonthlyId); 
                closeModal('weekly-goal-modal'); 
            }
        }

        async function addNewDailyGoal(e) {
            e.preventDefault();
            // DÜZENLEME MODU KONTROLÜ
            if (state.editingItem) {
                await handleUpdate(e);
                return;
            }
            
            if (!state.selectedWeeklyId) return;
            const label = document.getElementById('daily-goal-label').value.trim();
            const title = document.getElementById('daily-goal-title').value.trim();
            if (!label) return;
            // ... (kalan kod aynı) ...
            const data = {
                weekly_goal_id: state.selectedWeeklyId,
                day_label: label,
                title: title || null
            };
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
                fetchDailyGoals(state.selectedWeeklyId); 
                closeModal('daily-goal-modal'); 
            }
        }
        
        async function addNewTask(e) {
            e.preventDefault(); 
            // DÜZENLEME MODU KONTROLÜ
            if (state.editingItem) {
                await handleUpdate(e);
                return;
            }

            const time = document.getElementById('task-time').value;
            const desc = document.getElementById('task-desc').value;
            if (!desc || !state.selectedDailyId) return;
            // ... (kalan kod aynı) ...
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
                fetchTasks(state.selectedDailyId);
                closeModal('task-modal');
            }
        }

        // --- BU İKİ YENİ FONKSİYONU EKLEYİN ---

        /**
         * YENİ: Sütun 1-5 için 'is_completed' durumunu API üzerinden günceller (toggle).
         */
        async function handleToggleGoal(type, id, isCompleted) {
            // 'type' (örn: 'col-1') -> 'goal-categories' (API endpoint)
            let endpoint = '';
            switch (type) {
                case '1': endpoint = 'goal-categories'; break;
                case '2': endpoint = 'annual-goals'; break;
                case '3': endpoint = 'monthly-goals'; break;
                case '4': endpoint = 'weekly-goals'; break;
                case '5': endpoint = 'daily-goals'; break;
                default:
                    console.error('Bilinmeyen toggle tipi:', type);
                    return;
            }

            console.log(`handleToggleGoal çağrıldı (Tip: ${type}, ID: ${id}, Durum: ${isCompleted})`);
            
            await fetchData(`/api/${endpoint}/toggle/${id}`, {
                method: 'PUT',
                body: JSON.stringify({ is_completed: isCompleted })
            });
            // Hata kontrolü eklenebilir, ancak şimdilik iyimser (optimistic) güncelleme yapıyoruz.
        }

        /**
         * YENİ: Bir öğeyi silmek için kullanılır (Tüm Sütunlar).
         * (Şimdilik backend'i hazır olmadığı için SADECE BİR UYARI GÖSTERECEK)
         */
        async function handleDelete(type, id, itemElement) {
            console.log(`handleDelete çağrıldı (Tip: ${type}, ID: ${id})`);
            
            // 1. Silinecek öğenin bilgilerini state'e kaydet
            state.itemToDelete = { type, id, itemElement };

            // 2. Silme onay modalını aç
            document.getElementById('delete-confirm-modal').classList.remove('hidden');
        }

// --- BU YENİ FONKSİYONU EKLEYİN ---
       // --- BU FONKSİYONU GÜNCELLE (Arayüzde Zincirleme Silme) ---

        /**
         * YENİ: Silme onay modalındaki "Evet, Sil" butonuna tıklandığında çalışır.
         */
        async function confirmDelete() {
            if (!state.itemToDelete) return; // Silinecek bir şey seçilmemişse çık

            const { type, id, itemElement } = state.itemToDelete;
            
            console.log(`confirmDelete çağrıldı (Tip: ${type}, ID: ${id})`);

            // 1. Endpoint'i (API Adresini) belirle
            let endpoint = '';
            switch (type) {
                case '1': endpoint = 'goal-categories'; break; // Sütun 1
                case '2': endpoint = 'annual-goals'; break; // Sütun 2
                case '3': endpoint = 'monthly-goals'; break; // Sütun 3
                case '4': endpoint = 'weekly-goals'; break; // Sütun 4
                case '5': endpoint = 'daily-goals'; break; // Sütun 5
                case 'task': endpoint = 'tasks'; break; // Sütun 6
                default:
                    console.error('Bilinmeyen silme tipi:', type);
                    state.itemToDelete = null; // State'i temizle
                    closeModal('delete-confirm-modal');
                    return;
            }
            
            // 2. Butonu "Siliniyor..." yap
            const btn = document.getElementById('confirm-delete-btn');
            btn.disabled = true;
            btn.textContent = 'Siliniyor...';

            // 3. API'ye DELETE isteği at
            await fetchData(`/api/${endpoint}/${id}`, {
                method: 'DELETE'
            });

            // 4. Arayüzden öğeyi kaldır
            itemElement.remove();

            // 5. DÜZELTME: Silinen öğeye bağlı alt sütunları ARAYÜZDEN temizle
            switch (type) {
                case '1': resetColumns(2); break; // Sütun 1 silindiyse, 2-6'yı temizle
                case '2': resetColumns(3); break; // Sütun 2 silindiyse, 3-6'yı temizle
                case '3': resetColumns(4); break; // Sütun 3 silindiyse, 4-6'yı temizle
                case '4': resetColumns(5); break; // Sütun 4 silindiyse, 5-6'yı temizle
                case '5': resetColumns(6); break; // Sütun 5 silindiyse, 6'yı temizle
                // case 'task' (Sütun 6) bir şey yapmaz, çocuğu yok.
            }

            // 6. Butonu eski haline getir ve modalı kapat
            btn.disabled = false;
            btn.textContent = 'Evet, Sil';
            closeModal('delete-confirm-modal');
            state.itemToDelete = null; // State'i temizle
        }

        // --- BU İKİ YENİ FONKSİYONU EKLEYİN ---

        /**
         * YENİ: "Düzenle" ikonuna tıklandığında modalı açar ve verilerle doldurur.
         */
        function openEditModal(type, item) {
            state.editingItem = { type, item }; // Düzenleme moduna gir
            
            let modalId = '';
            
            switch (type) {
                case '1': // Kategori
                    modalId = 'category-modal';
                    document.getElementById('category-name').value = item.name;
                    break;
                case '2': // Yıllık
                    modalId = 'annual-goal-modal';
                    document.getElementById('annual-goal-title').value = item.title;
                    document.getElementById('annual-goal-year').value = item.year;
                    document.getElementById('annual-goal-period').value = item.period_label;
                    break;
                case '3': // Aylık
                    modalId = 'monthly-goal-modal';
                    document.getElementById('monthly-goal-title').value = item.title;
                    document.getElementById('monthly-goal-label').value = item.month_label;
                    break;
                case '4': // Haftalık
                    modalId = 'weekly-goal-modal';
                    document.getElementById('weekly-goal-title').value = item.title;
                    document.getElementById('weekly-goal-label').value = item.week_label;
                    break;
                case '5': // Günlük
                    modalId = 'daily-goal-modal';
                    document.getElementById('daily-goal-label').value = item.day_label;
                    document.getElementById('daily-goal-title').value = item.title;
                    break;
                case 'task': // Görev
                    modalId = 'task-modal';
                    document.getElementById('task-time').value = item.time_label;
                    document.getElementById('task-desc').value = item.task_description;
                    break;
                default:
                    console.error('Bilinmeyen düzenleme tipi:', type);
                    return;
            }

            const modal = document.getElementById(modalId);
            if (modal) {
                // Modalı "Düzenle" moduna geçir
                modal.querySelector('h3').textContent = 'Öğeyi Düzenle';
                modal.querySelector('button[type="submit"]').textContent = 'Güncelle';
                modal.classList.remove('hidden');
            }
        }

        /**
         * YENİ: "Güncelle" butonuna basıldığında (Ekleme yerine) bu fonksiyon çalışır.
         */
      // --- BU FONKSİYONU GÜNCELLE (Hata 2 Düzeltmesi: 'kaydetmiyor' sorunu) ---
        async function handleUpdate(e) {
            e.preventDefault();
            if (!state.editingItem) return;

            const { type, item } = state.editingItem;
            
            let data = {};
            let endpoint = '';
            let btnId = '';
            
            try {
                // 1. Tipe göre veriyi ve API adresini hazırla
                switch (type) {
                    case '1':
                        data = { name: document.getElementById('category-name').value };
                        endpoint = `/api/goal-categories/${item.id}`;
                        btnId = 'save-category-btn';
                        break;
                    case '2':
                        data = {
                            title: document.getElementById('annual-goal-title').value,
                            year: document.getElementById('annual-goal-year').value,
                            period_label: document.getElementById('annual-goal-period').value
                        };
                        endpoint = `/api/annual-goals/${item.id}`;
                        btnId = 'save-annual-goal-btn';
                        break;
                        
                    // DÜZELTME BURADA: (case '3.' -> case '3')
                    case '3': 
                        data = {
                            title: document.getElementById('monthly-goal-title').value,
                            month_label: document.getElementById('monthly-goal-label').value
                        };
                        endpoint = `/api/monthly-goals/${item.id}`;
                        btnId = 'save-monthly-goal-btn';
                        break;
                        
                    // DÜZELTME BURADA: (case '4.' -> case '4')
                    case '4': 
                        data = {
                            title: document.getElementById('weekly-goal-title').value,
                            week_label: document.getElementById('weekly-goal-label').value
                        };
                        endpoint = `/api/weekly-goals/${item.id}`;
                        btnId = 'save-weekly-goal-btn';
                        break;
                        
                    case '5':
                        data = {
                            day_label: document.getElementById('daily-goal-label').value,
                            title: document.getElementById('daily-goal-title').value || null
                        };
                        endpoint = `/api/daily-goals/${item.id}`;
                        btnId = 'save-daily-goal-btn';
                        break;
                    case 'task':
                        data = {
                            time_label: document.getElementById('task-time').value,
                            task_description: document.getElementById('task-desc').value
                        };
                        endpoint = `/api/tasks/${item.id}`;
                        btnId = 'save-task-btn';
                        break;
                    default:
                        throw new Error('Bilinmeyen güncelleme tipi');
                }

                // 2. API'ye gönder
                const btn = document.getElementById(btnId);
                btn.disabled = true;
                btn.textContent = 'Güncelleniyor...';
                
                const updatedItem = await fetchData(endpoint, {
                    method: 'PUT',
                    body: JSON.stringify(data)
                });
                
                // 3. Başarılıysa, listeyi yenile ve modalı kapat
                if (updatedItem) {
                    console.log('Öğe güncellendi:', updatedItem);
                    // Hangi listenin yenileneceğini belirle
                    switch (type) {
                        case '1': fetchCategories(); break;
                        case '2': fetchAnnualGoals(state.selectedCategoryId); break;
                        case '3': fetchMonthlyGoals(state.selectedAnnualId); break;
                        case '4': fetchWeeklyGoals(state.selectedMonthlyId); break;
                        case '5': fetchDailyGoals(state.selectedWeeklyId); break;
                        case 'task': fetchTasks(state.selectedDailyId); break;
                    }
                    closeModal(btn.closest('.fixed').id);
                }
                
            } catch (error) {
                console.error('Güncelleme hatası:', error);
                if (btnId) {
                    const btn = document.getElementById(btnId);
                    btn.disabled = false;
                    btn.textContent = 'Güncelle';
                }
            }
        }

    // --- UI (ARAYÜZ) HELPERS ---
    
// --- BU FONKSİYONU GÜNCELLE (Sütun 6 Tasarımı için) ---

    // --- MEVCUT renderList FONKSİYONUNU SİLİP BUNU YAPIŞTIRIN ---
        function renderList(listId, data, onClickCallback, textField = 'name') {
            const listElement = document.getElementById(listId);
            listElement.innerHTML = ''; 

            if (!data || data.length === 0) {
                listElement.innerHTML = `<div class="p-4 text-center text-gray-500">Veri bulunamadı.</div>`;
                return;
            }

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'task-item p-3 rounded-md hover:bg-gray-700 transition-colors duration-150 flex justify-between items-center';
                div.dataset.id = item.id;

                if (item.is_completed) {
                    div.classList.add('completed');
                }
                
                let topText = '';
                let bottomText = '';
                let bottomFontSizeClass = 'text-sm text-white'; 
                const listType = listId.split('-')[2];

                switch (listType) {
                    case '1': 
                        topText = '';
                        bottomText = item.name;
                        bottomFontSizeClass = 'text-white';
                        break;
                    case '2': 
                        topText = `Yıl ${item.year}: ${item.period_label}`; 
                        bottomText = item.title;
                        break;
                    case '3':
                        topText = item.month_label;
                        bottomText = item.title;
                        break;
                    case '4':
                        topText = item.week_label;
                        bottomText = item.title;
                        break;
                    case '5':
                        topText = item.day_label;
                        bottomText = item.title || '';
                        break;
                }
                
                // HTML GÜNCELLENDİ: "action-edit" (Kalem ikonu) eklendi
                div.innerHTML = `
                    <div class="item-content flex-1 flex items-center min-w-0">
                        <input type="checkbox" 
                               class="action-checkbox" 
                               title="Tamamlandı olarak işaretle"
                               ${item.is_completed ? 'checked' : ''}>
                        
                        <div class="ml-2" title="${topText ? topText + ': ' : ''}${bottomText}">
                            ${topText ? `<div class="text-xs font-semibold text-gray-400">${topText}</div>` : ''}
                            <div class="item-text ${bottomFontSizeClass}">${bottomText}</div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <!-- YENİ DÜZENLEME BUTONU -->
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
                const editBtn = div.querySelector('.action-edit'); // YENİ
                const deleteBtn = div.querySelector('.action-delete');
                
                checkbox.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isCompleted = e.target.checked;
                    div.classList.toggle('completed', isCompleted); 
                    const type = listId.split('-')[2]; 
                    handleToggleGoal(type, item.id, isCompleted);
                });

                content.addEventListener('click', (e) => {
                    if (e.target.tagName.toLowerCase() === 'input') {
                        return;
                    }
                    e.currentTarget.closest('.flex-1.overflow-y-auto').querySelectorAll('.task-item').forEach(el => {
                        el.classList.remove('selected');
                    });
                    div.classList.add('selected');
                    onClickCallback(item);
                });

                // YENİ DÜZENLEME OLAYI
                editBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const type = listId.split('-')[2];
                    openEditModal(type, item);
                });

                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const type = listId.split('-')[2];
                    handleDelete(type, item.id, div);
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

  // --- BU FONKSİYONU GÜNCELLE (Hata 2 Düzeltmesi: 'kaydet basmıyor' sorunu) ---

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                
                const form = modal.querySelector('form');
                if (form) {
                    form.reset(); // Formu temizle
                }
                
                // Modalı "Yeni Ekle" moduna geri döndür
                // (Silme modalı hariç)
                if (modalId !== 'delete-confirm-modal') {
                    // DÜZELTME BURADA:
                    // Butonu bul, metnini "Kaydet" yap VE 'disabled' durumunu kaldır.
                    modal.querySelector('h3').textContent = modal.querySelector('h3').textContent.replace('Düzenle', 'Yeni Ekle');
                    
                    const submitBtn = modal.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.textContent = 'Kaydet';
                        submitBtn.disabled = false; // <-- EKSİK OLAN KOD BUYDU
                    }
                }
                
                // Düzenleme modundan çık
                state.editingItem = null;
            }
        }

    function showError(message) {
        console.error('UYGULAMA HATASI:', message);
    }

// --- BU FONKSİYONU GÜNCELLE ---
        // --- MEVCUT initApp FONKSİYONUNU SİLİP BUNU YAPIŞTIRIN ---
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
            
            // YENİ EKLENDİ: Silme Modalı butonlarını ayarla
            document.getElementById('confirm-delete-btn').addEventListener('click', confirmDelete);
            document.getElementById('cancel-delete-btn').addEventListener('click', () => {
                closeModal('delete-confirm-modal');
                state.itemToDelete = null; // İptal edilirse state'i temizle
            });
            
            console.log('Uygulama başarıyla yüklendi.');
        }

    // --- DOM HAZIR OLDUĞUNDA UYGULAMAYI BAŞLAT ---
    document.addEventListener('DOMContentLoaded', initApp);

</script>
</body>
</html>