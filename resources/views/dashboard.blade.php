<x-app-layout>
    <!-- Header (HTS Arayüzünden kopyalandı) -->
   <header class="flex-shrink-0 bg-gray-900 shadow-md">
        <div class="px-4 sm:px-6 lg:px-8"> 
            <div class="flex justify-between items-center h-16">
                
                <!-- Sol Taraf (Başlık) - Bu zaten vardı -->
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-500" ...>
                        <path ... />
                    </svg>
                    <span class="ml-3 text-2xl font-semibold text-white">Hedef Takip Sistemi</span>
                </div>
                
                <!-- DÜZELTME: Sağ Taraf (Kullanıcı Adı ve Çıkış Butonu) EKLENDİ -->
                <div class="flex items-center space-x-4">
                    <!-- Giriş yapan kullanıcının adını göster -->
                    <span class="text-gray-300">{{ Auth::user()->name }}</span>
                    
                    <!-- Çıkış Yap Formu (Breeze'den kopyalandı) -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-full text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" title="Çıkış Yap">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            <span class="sr-only">Çıkış Yap</span>
                        </button>
                    </form>
                </div>
                
            </div>
        </div>
    </header>


    <div class="flex-1 grid grid-cols-6 min-h-0 min-w-0 px-4 sm:px-6 lg:px-8" style="height: calc(100vh - 64px);">
        
        <!-- Kolon 1: 5 Yıllık Hedef Kategorileri -->
        <div id="col-1" class="column flex flex-col border-r border-gray-700">
            <div class="flex-shrink-0 p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">5 Yıllık Hedefler</h2>
                <p class="text-sm text-gray-400">Ana Kategoriler</p>
            </div>
            <div id="list-col-1" class="flex-1 overflow-y-auto p-2 space-y-1">
                <div class="p-4 text-center text-gray-500">Yükleniyor...</div>
            </div>
            <div class="p-2 border-t border-gray-700">
                <button id="open-category-modal-btn" class="w-full bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                    + Yeni Kategori Ekle
                </button>
            </div>
        </div>

        <!-- Kolon 2: Yıllık Hedefler -->
        <div id="col-2" class="column hidden flex flex-col border-r border-gray-700">
            <div class="flex-shrink-0 p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Yıllık Hedefler</h2>
                <p class="text-sm text-gray-400">Yıllık Dağılım</p>
            </div>
            <div id="list-col-2" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            <div class="p-2 border-t border-gray-700">
                <button id="open-annual-goal-modal-btn" class="w-full bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                    + Yıllık Hedef Ekle
                </button>
            </div>
        </div>

        <!-- Kolon 3: Aylık Hedefler -->
        <div id="col-3" class="column hidden flex flex-col border-r border-gray-700">
            <div class="flex-shrink-0 p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Aylık Hedefler</h2>
                <p class="text-sm text-gray-400">Aylık Plan</p>
            </div>
            <div id="list-col-3" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            <div class="p-2 border-t border-gray-700">
                <button id="open-monthly-goal-modal-btn" class="w-full bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                    + Aylık Hedef Ekle
                </button>
            </div>
        </div>

        <!-- Kolon 4: Haftalık Hedefler -->
        <div id="col-4" class="column hidden flex flex-col border-r border-gray-700">
             <div class="flex-shrink-0 p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Haftalık Hedefler</h2>
                <p class="text-sm text-gray-400">Haftalık Plan</p>
            </div>
            <div id="list-col-4" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            <div class="p-2 border-t border-gray-700">
                <button id="open-weekly-goal-modal-btn" class="w-full bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                    + Haftalık Hedef Ekle
                </button>
            </div>
        </div>

        <!-- Kolon 5: Günlük Hedefler -->
        <div id="col-5" class="column hidden flex flex-col border-r border-gray-700">
            <div class="flex-shrink-0 p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Günlük Hedefler</h2>
                <p class="text-sm text-gray-400">Günlük Plan</p>
            </div>
            <div id="list-col-5" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            <div class="p-2 border-t border-gray-700">
                <button id="open-daily-goal-modal-btn" class="w-full bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                    + Günlük Hedef Ekle
                </button>
            </div>
        </div>

        <!-- Kolon 6: Görevler -->
        <div id="col-6" class="column hidden flex flex-col">
            <div class="flex-shrink-0 p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Görevler</h2>
                <p class="text-sm text-gray-400">Günlük Görev Listesi</p>
            </div>
            <div id="list-col-6" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            <div class="p-2 border-t border-gray-700">
                <button id="open-task-modal-btn" class="w-full bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                    + Saatlik Hedef Ekle
                </button>
            </div>
        </div>
    </div>

    <!-- HTS MODALLARI (Tümü) -->
    
    <!-- Kategori Modalı -->
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
                    <button type="button" id="close-category-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-category-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Yıllık Hedef Modalı -->
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

    <!-- Aylık Hedef Modalı -->
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

    <!-- Haftalık Hedef Modalı -->
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
                    <button type="button" id="close-weekly-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-weekly-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Günlük Hedef Modalı -->
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
                    <button type="button" id="close-daily-goal-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-daily-goal-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Görev Modalı -->
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
                    <button type="button" id="close-task-modal-btn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">İptal</button>
                    <button type="submit" id="save-task-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Silme Onay Modalı -->
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

</x-app-layout>
