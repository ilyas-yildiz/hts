<x-app-layout>
    <div x-data="{ open: false }" class="absolute top-4 right-2 z-50"> 
        
        <button @click="open = !open" class="p-2 rounded-full text-gray-400 bg-gray-900 bg-opacity-70 hover:bg-opacity-100 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-white" title="Menü">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-md bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-700"
             style="display: none;">
            
            <div class="py-1">
                <a href="{{ route('profile.edit') }}" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    <strong>{{ Auth::user()->name }}</strong> (Profil)
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white" title="Çıkış Yap">
                        Çıkış Yap
                    </button>
                </form>
            </div>
        </div>
    </div>

<div class="h-full flex flex-col">

    <div id="hts-breadcrumb-container" class="flex-shrink-0 px-4 sm:px-6 lg:px-8 pt-4 pb-2 space-y-1 block lg:hidden">
        </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-6 min-h-0 min-w-0 px-4 sm:px-6 lg:px-8">         
            
            <div id="col-1" class="column flex flex-col border-r border-gray-700">
            <div class="sticky top-0 z-10 bg-gray-800 flex-shrink-0 p-4 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-white">5 Yıllık Hedefler</h2>
                        <p class="text-sm text-gray-400">Ana Kategoriler</p>
                    </div>
                    <div class="mr-2"> 
                        <button id="open-category-modal-btn" class="p-1 rounded-full text-gray-400 bg-gray-700 hover:bg-blue-600 hover:text-white transition-colors duration-200" title="Yeni Kategori Ekle">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </button>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="agenda-date-picker" class="block text-xs font-medium text-gray-400 mb-1">Ajanda Tarihi Seç:</label>
                    <input type="date" id="agenda-date-picker" 
                           class="block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                           style="color-scheme: dark; padding-top: 6px; padding-bottom: 6px;"> 
                </div>
            </div>
            <div id="list-col-1" class="flex-1 overflow-y-auto p-2 space-y-1">
                <div class="p-4 text-center text-gray-500">Yükleniyor...</div>
            </div>
        </div>

            <div id="col-2" class="column hidden lg:flex flex-col border-r border-gray-700">
                <div class="sticky top-0 z-10 bg-gray-800 flex-shrink-0 p-4 border-b border-gray-700 flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="back-to-col-1" class="p-1 rounded-full text-gray-400 hover:text-white mr-2 block lg:hidden" title="Geri (5 Yıllık)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Yıllık Hedefler</h2>
                            <p class="text-sm text-gray-400">Yıllık Dağılım</p>
                        </div>
                    </div>
                    <div class="mr-2"> 
                        <button id="open-annual-goal-modal-btn" class="p-1 rounded-full text-gray-400 bg-gray-700 hover:bg-blue-600 hover:text-white transition-colors duration-200" title="Yıllık Hedef Ekle">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </button>
                    </div>
                </div>
                <div id="list-col-2" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            </div>

            <div id="col-3" class="column hidden lg:flex flex-col border-r border-gray-700">
                <div class="sticky top-0 z-10 bg-gray-800 flex-shrink-0 p-4 border-b border-gray-700 flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="back-to-col-2" class="p-1 rounded-full text-gray-400 hover:text-white mr-2 block lg:hidden" title="Geri (Yıllık)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Aylık Hedefler</h2>
                            <p class="text-sm text-gray-400">Aylık Plan</p>
                        </div>
                    </div>
                    <div class="mr-2"> 
                        <button id="open-monthly-goal-modal-btn" class="p-1 rounded-full text-gray-400 bg-gray-700 hover:bg-blue-600 hover:text-white transition-colors duration-200" title="Aylık Hedef Ekle">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </button>
                    </div>
                </div>
                <div id="list-col-3" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            </div>

            <div id="col-4" class="column hidden lg:flex flex-col border-r border-gray-700">
                <div class="sticky top-0 z-10 bg-gray-800 flex-shrink-0 p-4 border-b border-gray-700 flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="back-to-col-3" class="p-1 rounded-full text-gray-400 hover:text-white mr-2 block lg:hidden" title="Geri (Aylık)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Haftalık Hedefler</h2>
                            <p class="text-sm text-gray-400">Haftalık Plan</p>
                        </div>
                    </div>
                    <div class="mr-2"> 
                        <button id="open-weekly-goal-modal-btn" class="p-1 rounded-full text-gray-400 bg-gray-700 hover:bg-blue-600 hover:text-white transition-colors duration-200" title="Haftalık Hedef Ekle">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </button>
                    </div>
                </div>
                <div id="list-col-4" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            </div>

            <div id="col-5" class="column hidden lg:flex flex-col border-r border-gray-700">
                <div class="sticky top-0 z-10 bg-gray-800 flex-shrink-0 p-4 border-b border-gray-700 flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="back-to-col-4" class="p-1 rounded-full text-gray-400 hover:text-white mr-2 block lg:hidden" title="Geri (Haftalık)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Günlük Hedefler</h2>
                            <p class="text-sm text-gray-400">Günlük Plan</p>
                        </div>
                    </div>
                    <div class="mr-2"> 
                        <button id="open-daily-goal-modal-btn" class="p-1 rounded-full text-gray-400 bg-gray-700 hover:bg-blue-600 hover:text-white transition-colors duration-200" title="Günlük Hedef Ekle">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </button>
                    </div>
                </div>
                <div id="list-col-5" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            </div>

            <div id="col-6" class="column hidden lg:flex flex-col">
                <div class="sticky top-0 z-10 bg-gray-800 flex-shrink-0 p-4 border-b border-gray-700 flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="back-btn-col-6" class="p-1 rounded-full text-gray-400 hover:text-white mr-2 block lg:hidden" title="Geri">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-white">Saatlik Görevler</h2>
                            <p class="text-sm text-gray-400">Saat Listesi</p>
                        </div>
                    </div>
                    <div class="mr-2"> 
                        <button id="open-task-modal-btn" class="p-1 rounded-full text-gray-400 bg-gray-700 hover:bg-blue-600 hover:text-white transition-colors duration-200" title="Saatlik Hedef Ekle">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </button>
                    </div>
                </div>
                <div id="list-col-6" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            </div>
    </div> </div> <div id="category-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
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
                    <button type="button" id="close-category-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-category-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
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
                    <button type="button" id="close-annual-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-annual-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
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
                    <button type="button" id="close-monthly-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-monthly-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
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
                    <div>
                        <label for="weekly-goal-start-date" class="block text-sm font-medium text-gray-300">Hafta Başlangıç Tarihi (Opsiyonel)</label>
                        <input type="date" id="weekly-goal-start-date" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-weekly-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-weekly-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
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
                    <div>
                        <label for="daily-goal-date" class="block text-sm font-medium text-gray-300">Günün Tarihi (Opsiyonel)</label>
                        <input type="date" id="daily-goal-date" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-daily-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-daily-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <div id="task-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4 text-white">Yeni Görev Ekle</h3>
           <form id="task-form">
            <div id="task-modal-error" class="hidden p-3 mb-4 text-sm text-red-300 bg-red-800 bg-opacity-50 rounded-md border border-red-700">
                    </div>
                <div class="space-y-4">
                    
                    <div id="task-category-selector" class="hidden">
                        <label for="task-goal-category" class="block text-sm font-medium text-gray-300">Hangi 5 Yıllık Hedef (Kategori)?</label>
                        <select id="task-goal-category" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Lütfen bir kategori seçin...</option>
                        </select>
                    </div>

                    <div>
                        <label for="task-goal-date" class="block text-sm font-medium text-gray-300">Görevin Tarihi</label>
                        <input type="date" id="task-goal-date" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="task-start-time" class="block text-sm font-medium text-gray-300">Başlangıç Saati (Ops.)</label>
                            <input type="time" id="task-start-time" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="task-end-time" class="block text-sm font-medium text-gray-300">Bitiş Saati (Ops.)</label>
                            <input type="time" id="task-end-time" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="task-desc" class="block text-sm font-medium text-gray-300">Görev Açıklaması</label>
                        <textarea id="task-desc" rows="5" placeholder="Örn: Proje planı revize edilecek" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm p-2 text-white focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="close-task-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-task-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-xl font-semibold mb-2 text-white">Silme Onayı</h3>
            <p class="text-gray-300 mb-6">Bu öğeyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancel-delete-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                <button type="button" id="confirm-delete-btn" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Evet, Sil</button>
            </div>
        </div>
    </div>
<div id="global-tooltip" class="fixed hidden p-3 rounded-md shadow-lg bg-gray-900 border-gray-700 text-white text-sm max-w-xs z-[200]" 
         style="white-space: pre-wrap; transition: opacity 0.2s ease; opacity: 0; pointer-events: none;">
        </div>
</x-app-layout>