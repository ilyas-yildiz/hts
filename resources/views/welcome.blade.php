<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hedef Takip Sistemi</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Inter font ailesini ve temel stilleri ayarla */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827; /* Tailwind bg-gray-900 */
        }
        /* Özel kaydırma çubuğu (isteğe bağlı, estetik için) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937; /* bg-gray-800 */
        }
        ::-webkit-scrollbar-thumb {
            background: #4b5563; /* bg-gray-600 */
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280; /* bg-gray-500 */
        }
        /* Kolonların yatayda kaymasını ve minimum genişliğini sağlar */
        .column {
            min-width: 280px; /* Her kolon için minimum genişlik */
            flex-shrink: 0; /* Kolonların büzülmesini engeller */
        }
    </style>
</head>
<body class="h-full text-gray-200 overflow-hidden">
    <!-- Ana Konteyner -->
    <div class="flex flex-col h-screen">
        
        <!-- Header / Başlık -->
        <header class="flex-shrink-0 bg-gray-900 border-b border-gray-700">
            <div class="max-w-full mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-white">
                    <span class="text-blue-500 font-bold">HTS</span> / Hedef Takip Sistemi
                </h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">İlyas Yıldız</span>
                    <button class="text-gray-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Kolonların olduğu ana içerik alanı -->
        <main class="flex-1 flex overflow-x-auto p-4 space-x-4 bg-gray-900" id="main-container">
            
            <!-- JavaScript tarafından dinamik olarak doldurulacak kolonlar için şablon -->
            
            <!-- Kolon 1: 5 Yıllık Hedef Kategorileri -->
            <div class="column bg-gray-800 rounded-lg shadow-md h-full flex flex-col">
                <h2 class="text-lg font-semibold p-4 border-b border-gray-700">5 Yıllık Hedefler</h2>
                <ul class="flex-1 overflow-y-auto p-2 space-y-2" id="col-five-year">
                    <!-- JS ile doldurulacak -->
                </ul>
            </div>

            <!-- Kolon 2: Yıllar (2026-2030) -->
            <div class="column bg-gray-800 rounded-lg shadow-md h-full flex flex-col hidden" id="col-annual-wrapper">
                <h2 class="text-lg font-semibold p-4 border-b border-gray-700" id="col-annual-title">Yıllar</h2>
                <ul class="flex-1 overflow-y-auto p-2 space-y-2" id="col-annual">
                    <!-- JS ile doldurulacak -->
                </ul>
            </div>

            <!-- Kolon 3: Aylar (Sadece 1. Yıl için) -->
            <div class="column bg-gray-800 rounded-lg shadow-md h-full flex flex-col hidden" id="col-monthly-wrapper">
                <h2 class="text-lg font-semibold p-4 border-b border-gray-700" id="col-monthly-title">Aylar - 1. Yıl</h2>
                <ul class="flex-1 overflow-y-auto p-2 space-y-2" id="col-monthly">
                    <!-- JS ile doldurulacak -->
                </ul>
            </div>
            
            <!-- Kolon 4: Haftalar (Sadece 1. Ay için) -->
            <div class="column bg-gray-800 rounded-lg shadow-md h-full flex flex-col hidden" id="col-weekly-wrapper">
                <h2 class="text-lg font-semibold p-4 border-b border-gray-700" id="col-weekly-title">Haftalar</h2>
                <ul class="flex-1 overflow-y-auto p-2 space-y-2" id="col-weekly">
                    <!-- JS ile doldurulacak -->
                </ul>
            </div>

            <!-- Kolon 5: Günler (Sadece 1. Hafta için) -->
            <div class="column bg-gray-800 rounded-lg shadow-md h-full flex flex-col hidden" id="col-daily-wrapper">
                <h2 class="text-lg font-semibold p-4 border-b border-gray-700" id="col-daily-title">Günler</h2>
                <ul class="flex-1 overflow-y-auto p-2 space-y-2" id="col-daily">
                    <!-- JS ile doldurulacak -->
                </ul>
            </div>

            <!-- Kolon 6: Görevler / Saatler -->
            <div class="column bg-gray-800 rounded-lg shadow-md h-full flex flex-col hidden" id="col-tasks-wrapper">
                <h2 class="text-lg font-semibold p-4 border-b border-gray-700" id="col-tasks-title">Saatler</h2>
                <div class="flex-1 overflow-y-auto p-2 space-y-3" id="col-tasks">
                    <!-- JS ile doldurulacak -->
                </div>
                <div class="p-4 border-t border-gray-700">
                    <button class
="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        + Yeni Görev Ekle
                    </button>
                </div>
            </div>

        </main>
    </div>

    <script>
        // --- ÖRNEK VERİ YAPISI ---
        // Gerçekte bu veriler Laravel API'nizden gelecek.
        const db = {
            // 5 Yıllık Ana Hedef Kategorileri
            fiveYearGoals: [
                { id: 'g1', name: 'Kişisel Gelişim' },
                { id: 'g2', name: 'Finansal Özgürlük' },
                { id: 'g3', name: 'Yazılım (Kariyer)' },
                { id: 'g4', name: 'İnşaat (Girişimcilik)' },
                { id: 'g5', name: 'Aile İlişkileri' },
            ],

            // Yıllık Hedefler (Ana kategoriye bağlı)
            // Not: Sizin mantığınıza göre, her kategori için 5 yıllık döküm olacak.
            annualGoals: {
                'g1': [ // Kişisel Gelişim
                    { id: 'y1-g1', year: 1, period: 'Eylül 2026 Sonu', title: 'Yılda 50 kitap okuma hedefi' },
                    { id: 'y2-g1', year: 2, period: 'Eylül 2027 Sonu', title: 'Yeni bir dil (İspanyolca) B1 seviyesi' },
                    { id: 'y3-g1', year: 3, period: 'Eylül 2028 Sonu', title: 'Topluluk önünde konuşma korkusunu yenme' },
                    { id: 'y4-g1', year: 4, period: 'Eylül 2029 Sonu', title: 'Maraton koşmak (Fiziksel hedef)' },
                    { id: 'y5-g1', year: 5, period: 'Eylül 2030 Sonu', title: 'Alanında uzman olarak 2 konferans verme' },
                ],
                'g2': [ // Finansal Özgürlük
                    { id: 'y1-g2', year: 1, period: 'Eylül 2026 Sonu', title: 'Acil durum fonu (6 aylık) tamamlama' },
                    { id: 'y2-g2', year: 2, period: 'Eylül 2027 Sonu', title: 'Aylık 10.000 TL pasif gelir' },
                    { id: 'y3-g2', year: 3, period: 'Eylül 2028 Sonu', title: 'Borsa portföyü 1M TL' },
                    { id: 'y4-g2', year: 4, period: 'Eylül 2029 Sonu', title: 'İlk yatırım amaçlı gayrimenkul alımı' },
                    { id: 'y5-g2', year: 5, period: 'Eylül 2030 Sonu', title: 'Finansal özgürlük (Hedef X TL)' },
                ],
                // Diğer kategoriler (g3, g4, g5) için de benzer dökümler olmalı...
                'g3': [
                    { id: 'y1-g3', year: 1, period: 'Eylül 2026 Sonu', title: 'Laravel\'de uzmanlaşma (Senior seviye)' },
                    { id: 'y2-g3', year: 2, period: 'Eylül 2027 Sonu', title: 'Vue.js veya React\'ta proje geliştirme' },
                    { id: 'y3-g3', year: 3, period: 'Eylül 2028 Sonu', title: 'Mikroservis mimarisi ile 1 proje' },
                    { id: 'y4-g3', year: 4, period: 'Eylül 2029 Sonu', title: 'Mobil uygulama geliştirme (Flutter/React Native)' },
                    { id: 'y5-g3', year: 5, period: 'Eylül 2030 Sonu', title: 'Kendi SaaS projemi başlatma' },
                ], 
                'g4': [
                    { id: 'y1-g4', year: 1, period: 'Eylül 2026 Sonu', title: 'Müteahhitlik belgesi ve süreçleri tamamlama' },
                    { id: 'y2-g4', year: 2, period: 'Eylül 2027 Sonu', title: 'İlk küçük ölçekli (tadilat/yenileme) proje' },
                    { id: 'y3-g4', year: 3, period: 'Eylül 2028 Sonu', title: 'İlk arsa alımı ve projelendirme' },
                    { id: 'y4-g4', year: 4, period: 'Eylül 2029 Sonu', title: 'İlk konut inşaatına başlama' },
                    { id: 'y5-g4', year: 5, period: 'Eylül 2030 Sonu', title: 'İlk projeyi (2-4 daire) tamamlama ve satma' },
                ], 
                'g5': [],
            },

            // Aylık Hedefler (Sadece 1. YIL için)
            // Key: 'y1-{goalId}' (örn: 'y1-g1' - Yıl 1, Kişisel Gelişim)
            monthlyGoals: {
                'y1-g1': [ // 1. Yıl, Kişisel Gelişim (50 kitap hedefi)
                    { id: 'm1-y1g1', month: 'Ekim 2025', title: '4 kitap bitir' },
                    { id: 'm2-y1g1', month: 'Kasım 2025', title: '4 kitap bitir (1 tanesi kurgu dışı)' },
                    { id: 'm3-y1g1', month: 'Aralık 2025', title: '5 kitap bitir' },
                    // ... 12 ay ...
                ],
                'y1-g2': [ // 1. Yıl, Finansal (Acil durum fonu)
                    { id: 'm1-y1g2', month: 'Ekim 2025', title: 'Gelirin %15\'ini fona ayır' },
                    { id: 'm2-y1g2', month: 'Kasım 2025', title: 'Ek işten gelen 2000 TL\'yi fona aktar' },
                    { id: 'm3-y1g2', month: 'Aralık 2025', title: 'Harcamaları %10 azalt, farkı fona aktar' },
                    // ... 12 ay ...
                ],
                'y1-g3': [ // 1. Yıl, Yazılım (Laravel Uzmanlaşma)
                    { id: 'm1-y1g3', month: 'Ekim 2025', title: 'Laravel 12 Yenilikleri ve Eloquent ORM derinlemesine' },
                    { id: 'm2-y1g3', month: 'Kasım 2025', title: 'Test Driven Development (TDD) öğrenme ve uygulama' },
                    { id: 'm3-y1g3', month: 'Aralık 2025', title: 'Queue, Jobs ve Horizon konularına hakim olma' },
                    // ... 12 ay ...
                ],
            },

            // Haftalık Hedefler (Sadece 1. AY için)
            // Key: 'm1-{annualGoalId}' (örn: 'm1-y1g1' - 1. Ay, Yıl 1, Kişisel Gelişim)
            weeklyGoals: {
                'm1-y1g1': [ // 1. Ay (Ekim 2025), Yıl 1, Kişisel Gelişim (4 kitap)
                    { id: 'w1-m1y1g1', week: '1. Hafta (1-7 Ekim)', title: '1. Kitaba başla (200 sayfa oku)' },
                    { id: 'w2-m1y1g1', week: '2. Hafta (8-14 Ekim)', title: '1. Kitabı bitir, 2. Kitaba başla' },
                    { id: 'w3-m1y1g1', week: '3. Hafta (15-21 Ekim)', title: '2. Kitabı bitir, 3. Kitaba başla' },
                    { id: 'w4-m1y1g1', week: '4. Hafta (22-31 Ekim)', title: '3. ve 4. Kitabı bitir' },
                ],
                'm1-y1g2': [ // 1. Ay (Ekim 2025), Yıl 1, Finansal (Gelirin %15'i)
                    { id: 'w1-m1y1g2', week: '1. Hafta (1-7 Ekim)', title: 'Harcama dökümünü çıkar' },
                    { id: 'w2-m1y1g2', week: '2. Hafta (8-14 Ekim)', title: 'Otomatik fon transfer talimatı ver' },
                    { id: 'w3-m1y1g2', week: '3. Hafta (15-21 Ekim)', title: 'Gereksiz abonelikleri iptal et' },
                    { id: 'w4-m1y1g2', week: '4. Hafta (22-31 Ekim)', title: 'Haftalık bütçe takibini yap' },
                ],
                'm1-y1g3': [ // 1. Ay (Ekim 2025), Yıl 1, Yazılım (Eloquent)
                    { id: 'w1-m1y1g3', week: '1. Hafta (1-7 Ekim)', title: 'Eloquent Relationships (Tüm ilişki tipleri) çalışma' },
                    { id: 'w2-m1y1g3', week: '2. Hafta (8-14 Ekim)', title: 'Eloquent Scopes (Global/Local) ve Accessors/Mutators' },
                    { id: 'w3-m1y1g3', week: '3. Hafta (15-21 Ekim)', title: 'Eloquent Collections ve API Resources' },
                    { id: 'w4-m1y1g3', week: '4. Hafta (22-31 Ekim)', title: 'N+1 problemi tespiti ve çözümü (Eager Loading)' },
                ]
            },

            // Günlük Hedefler (Sadece 1. HAFTA için)
            // Key: 'w1-{monthlyGoalId}' (örn: 'w1-m1y1g1' - 1. Hafta, 1. Ay, Yıl 1, Kişisel Gelişim)
            dailyGoals: {
                'w1-m1y1g1': [ // 1. Hafta, 1. Ay, Yıl 1, Kişisel Gelişim (1. Kitap 200 sayfa)
                    { id: 'd1-w1m1y1g1', day: 'Pazartesi', title: '30 sayfa oku' },
                    { id: 'd2-w1m1y1g1', day: 'Salı', title: '30 sayfa oku' },
                    { id: 'd3-w1m1y1g1', day: 'Çarşamba', title: '30 sayfa oku' },
                    { id: 'd4-w1m1y1g1', day: 'Perşembe', title: '30 sayfa oku' },
                    { id: 'd5-w1m1y1g1', day: 'Cuma', title: '30 sayfa oku' },
                    { id: 'd6-w1m1y1g1', day: 'Cumartesi', title: '50 sayfa oku' },
                    { id: 'd7-w1m1y1g1', day: 'Pazar', title: 'Haftalık değerlendirme' },
                ],
                'w1-m1y1g3': [ // 1. Hafta, 1. Ay, Yıl 1, Yazılım (Eloquent Relationships)
                    { id: 'd1-w1m1y1g3', day: 'Pazartesi', title: 'One to One & One to Many çalışma' },
                    { id: 'd2-w1m1y1g3', day: 'Salı', title: 'Many to Many çalışma' },
                    { id: 'd3-w1m1y1g3', day: 'Çarşamba', title: 'Polymorphic Relationships çalışma' },
                    { id: 'd4-w1m1y1g3', day: 'Perşembe', title: 'Mini-proje (Blog) ilişki kurulumu' },
                    { id: 'd5-w1m1y1g3', day: 'Cuma', title: 'Has Many Through & Polymorphic Many-to-Many' },
                    { id: 'd6-w1m1y1g3', day: 'Cumartesi', title: 'Tüm ilişki tipleriyle pratik yapma' },
                    { id: 'd7-w1m1y1g3', day: 'Pazar', title: 'Haftalık tekrar ve kod incelemesi' },
                ]
            },

            // Saatlik Görevler (Güne bağlı)
            // Key: 'd{dayIndex}-{weeklyGoalId}' (örn: 'd1-w1m1y1g1' - Pazartesi, 1. Hafta, 1. Ay, Yıl 1, Kişisel G.)
            tasks: {
                'd1-w1m1y1g1': [ // Pazartesi (30 sayfa oku)
                    { id: 't1', time: '06:00 - 06:30', task: 'Sabah okuması (15 sayfa)', completed: true },
                    { id: 't2', time: '12:30 - 13:00', task: 'Öğle arası okuması (15 sayfa)', completed: false },
                ],
                'd2-w1m1y1g1': [ // Salı (30 sayfa oku)
                    { id: 't3', time: '21:00 - 22:00', task: 'Akşam okuması (30 sayfa)', completed: false },
                ],
                 'd1-w1m1y1g3': [ // Pazartesi (Eloquent İlişkiler)
                    { id: 't4', time: '19:00 - 20:00', task: 'One to One (User-Profile) modeli kodla', completed: true },
                    { id: 't5', time: '20:00 - 21:00', task: 'One to Many (User-Posts) modeli kodla', completed: true },
                    { id: 't6', time: '21:00 - 21:30', task: 'Dokümantasyon okuma (İlişkileri sorgulama)', completed: false },
                ],
            }
        };

        // --- DOM Referansları ---
        const cols = {
            fiveYear: document.getElementById('col-five-year'),
            annual: document.getElementById('col-annual'),
            monthly: document.getElementById('col-monthly'),
            weekly: document.getElementById('col-weekly'),
            daily: document.getElementById('col-daily'),
            tasks: document.getElementById('col-tasks'),
        };

        const wrappers = {
            annual: document.getElementById('col-annual-wrapper'),
            monthly: document.getElementById('col-monthly-wrapper'),
            weekly: document.getElementById('col-weekly-wrapper'),
            daily: document.getElementById('col-daily-wrapper'),
            tasks: document.getElementById('col-tasks-wrapper'),
        };

        const titles = {
            annual: document.getElementById('col-annual-title'),
            monthly: document.getElementById('col-monthly-title'),
            weekly: document.getElementById('col-weekly-title'),
            daily: document.getElementById('col-daily-title'),
            tasks: document.getElementById('col-tasks-title'),
        };
        
        // --- Mevcut Seçim Durumu ---
        const selection = {
            fiveYearGoal: null,
            annualGoal: null,
            monthlyGoal: null,
            weeklyGoal: null,
            dailyGoal: null,
        };

        // --- Render Fonksiyonları ---

        // Liste elemanı (<li>) oluşturan yardımcı fonksiyon
        function createListItem(item, type) {
            const li = document.createElement('li');
            li.className = 'p-3 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition-colors flex justify-between items-center';
            li.dataset.id = item.id;
            li.dataset.type = type;

            let content = '';
            switch (type) {
                case 'fiveYear':
                    content = `<span>${item.name}</span>`;
                    break;
                case 'annual':
                    content = `<div class="flex flex-col">
                                 <span class="font-semibold">${item.period} (Yıl ${item.year})</span>
                                 <span class="text-sm text-gray-400">${item.title}</span>
                               </div>`;
                    break;
                case 'monthly':
                    content = `<div class="flex flex-col">
                                 <span class="font-semibold">${item.month}</span>
                                 <span class="text-sm text-gray-400">${item.title}</span>
                               </div>`;
                    break;
                case 'weekly':
                    content = `<div class="flex flex-col">
                                 <span class="font-semibold">${item.week}</span>
                                 <span class="text-sm text-gray-400">${item.title}</span>
                               </div>`;
                    break;
                case 'daily':
                     content = `<div class="flex flex-col">
                                 <span class="font-semibold">${item.day}</span>
                                 <span class="text-sm text-gray-400">${item.title || '(Boş gün)'}</span>
                               </div>`;
                    break;
            }
            
            li.innerHTML = `${content} <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>`;
            return li;
        }

        // Görev elemanı oluşturan yardımcı fonksiyon
        function createTaskItem(item) {
            const div = document.createElement('div');
            div.className = 'p-3 bg-gray-700 rounded-lg flex items-center space-x-3';
            div.dataset.id = item.id;
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = item.completed;
            // Tailwind'in form stillerini CDN'den alabilmesi için class eklemesi
            checkbox.className = 'form-checkbox h-5 w-5 bg-gray-800 border-gray-600 rounded text-blue-500 focus:ring-blue-500';
            
            checkbox.addEventListener('change', () => {
                item.completed = checkbox.checked;
                div.classList.toggle('opacity-50', item.completed);
                // Burada API'ye güncelleme gönderilebilir.
                console.log(`Task ${item.id} completed status: ${item.completed}`);
            });

            if (item.completed) {
                div.classList.add('opacity-50');
            }

            const content = document.createElement('div');
            content.className = 'flex flex-col';
            content.innerHTML = `<span class="font-medium ${item.completed ? 'line-through' : ''}">${item.task}</span>
                                 <span class="text-sm text-gray-400">${item.time}</span>`;
            
            div.appendChild(checkbox);
            div.appendChild(content);
            return div;
        }

        // Bir kolondaki seçimi temizle/ayarla
        function highlightSelection(columnUl, selectedId) {
            Array.from(columnUl.children).forEach(li => {
                li.classList.toggle('bg-blue-600', li.dataset.id === selectedId);
                li.classList.toggle('hover:bg-blue-700', li.dataset.id === selectedId);
                li.classList.toggle('bg-gray-700', li.dataset.id !== selectedId);
                li.classList.toggle('hover:bg-gray-600', li.dataset.id !== selectedId);
            });
        }

        // Sonraki tüm kolonları temizle ve gizle
        function clearNextColumns(level) {
            if (level <= 1) {
                wrappers.annual.classList.add('hidden');
                cols.annual.innerHTML = '';
                selection.annualGoal = null;
            }
            if (level <= 2) {
                wrappers.monthly.classList.add('hidden');
                cols.monthly.innerHTML = '';
                selection.monthlyGoal = null;
            }
            if (level <= 3) {
                wrappers.weekly.classList.add('hidden');
                cols.weekly.innerHTML = '';
                selection.weeklyGoal = null;
            }
            if (level <= 4) {
                wrappers.daily.classList.add('hidden');
        
        cols.daily.innerHTML = '';
                selection.dailyGoal = null;
            }
            if (level <= 5) {
                wrappers.tasks.classList.add('hidden');
                cols.tasks.innerHTML = '';
            }
        }

        // Kolon 1: 5 Yıllık Hedefler
        function renderFiveYearGoals() {
            cols.fiveYear.innerHTML = '';
            db.fiveYearGoals.forEach(goal => {
                cols.fiveYear.appendChild(createListItem(goal, 'fiveYear'));
            });
        }

        // Kolon 2: Yıllık Hedefler
        function renderAnnualGoals(goalId) {
            clearNextColumns(1);
            selection.fiveYearGoal = db.fiveYearGoals.find(g => g.id === goalId);
            if (!selection.fiveYearGoal) return;

            highlightSelection(cols.fiveYear, goalId);
            
            const data = db.annualGoals[goalId] || [];
            if (data.length > 0) {
                data.forEach(goal => {
                    cols.annual.appendChild(createListItem(goal, 'annual'));
                });
                titles.annual.textContent = `Yıllar (${selection.fiveYearGoal.name})`;
                wrappers.annual.classList.remove('hidden');
            }
        }

        // Kolon 3: Aylık Hedefler
        function renderMonthlyGoals(annualGoalId) {
            clearNextColumns(2);
            // Önceki seçimden 5 yıllık hedef ID'sini al
            if (!selection.fiveYearGoal) return;
            const goalId = selection.fiveYearGoal.id;
            
            selection.annualGoal = (db.annualGoals[goalId] || []).find(g => g.id === annualGoalId);
            if (!selection.annualGoal) return;

            highlightSelection(cols.annual, annualGoalId);

            // Sadece 1. Yıl için aylık döküm var (KURAL)
            if (selection.annualGoal.year === 1) {
                const dataKey = `y1-${goalId}`; // örn: 'y1-g1'
                const data = db.monthlyGoals[dataKey] || [];
                
                if (data.length > 0) {
                    data.forEach(goal => {
                        cols.monthly.appendChild(createListItem(goal, 'monthly'));
                    });
                    titles.monthly.textContent = `Aylar (Yıl 1 - ${selection.fiveYearGoal.name})`;
                    wrappers.monthly.classList.remove('hidden');
                }
            }
        }

        // Kolon 4: Haftalık Hedefler
        function renderWeeklyGoals(monthlyGoalId) {
            clearNextColumns(3);
            if (!selection.fiveYearGoal || !selection.annualGoal) return;
            
            const goalId = selection.fiveYearGoal.id;
            const dataKey = `y1-${goalId}`; // Aylık hedeflerin key'i (örn: 'y1-g1')
            
            selection.monthlyGoal = (db.monthlyGoals[dataKey] || []).find(g => g.id === monthlyGoalId);
            if (!selection.monthlyGoal) return;

            highlightSelection(cols.monthly, monthlyGoalId);
            
            // Sadece 1. Ay için haftalık döküm var (ÖRNEK KURAL)
            // Bu prototipte, seçilen ayın ID'sini (örn: 'm1-y1g1') haftalık hedeflerin key'i olarak kullanıyoruz.
            const data = db.weeklyGoals[monthlyGoalId] || [];
            
            if (data.length > 0) {
                data.forEach(goal => {
                    cols.weekly.appendChild(createListItem(goal, 'weekly'));
                });
                titles.weekly.textContent = `Haftalar (${selection.monthlyGoal.month})`;
                wrappers.weekly.classList.remove('hidden');
            }
        }
        
        // Kolon 5: Günlük Hedefler
        function renderDailyGoals(weeklyGoalId) {
            clearNextColumns(4);
            if (!selection.monthlyGoal) return;

            const monthlyGoalId = selection.monthlyGoal.id;
            selection.weeklyGoal = (db.weeklyGoals[monthlyGoalId] || []).find(g => g.id === weeklyGoalId);
            if (!selection.weeklyGoal) return;

            highlightSelection(cols.weekly, weeklyGoalId);

            // Prototipte, seçilen haftanın ID'sini (örn: 'w1-m1y1g1') günlük hedeflerin key'i olarak kullanıyoruz.
            const data = db.dailyGoals[weeklyGoalId] || [];

            if (data.length > 0) {
                 data.forEach(goal => {
                    cols.daily.appendChild(createListItem(goal, 'daily'));
                });
                titles.daily.textContent = `Günler (${selection.weeklyGoal.week})`;
                wrappers.daily.classList.remove('hidden');
            }
        }

        // Kolon 6: Görevler
        function renderTasks(dailyGoalId) {
            clearNextColumns(5);
            if (!selection.weeklyGoal) return;
            
            const weeklyGoalId = selection.weeklyGoal.id;
            selection.dailyGoal = (db.dailyGoals[weeklyGoalId] || []).find(g => g.id === dailyGoalId);
            if (!selection.dailyGoal) return;

            highlightSelection(cols.daily, dailyGoalId);

            // Prototipte, seçilen günün ID'sini (örn: 'd1-w1m1y1g1') görevlerin key'i olarak kullanıyoruz.
            const data = db.tasks[dailyGoalId] || [];
            cols.tasks.innerHTML = ''; // Öncekileri temizle

            if (data.length > 0) {
                data.forEach(task => {
                    cols.tasks.appendChild(createTaskItem(task));
                });
            } else {
                cols.tasks.innerHTML = `<div class="p-4 text-gray-500 text-center">Bu gün için görev girilmemiş.</div>`;
            }
            
            titles.tasks.textContent = `Görevler - ${selection.dailyGoal.day}`;
            wrappers.tasks.classList.remove('hidden');
        }


        // --- Ana Olay Yönlendirici (Event Listener) ---
        document.getElementById('main-container').addEventListener('click', (e) => {
            const li = e.target.closest('li[data-id]');
            if (!li) return; // Liste elemanına tıklanmadı

            const id = li.dataset.id;
            const type = li.dataset.type;

            switch (type) {
                case 'fiveYear':
                    renderAnnualGoals(id);
                    break;
                case 'annual':
                    renderMonthlyGoals(id);
                    break;
                case 'monthly':
                    renderWeeklyGoals(id);
                    break;
                case 'weekly':
                    renderDailyGoals(id);
                    break;
                case 'daily':
                    renderTasks(id);
                    break;
            }
        });

        // --- Başlangıç ---
        // Sayfa yüklendiğinde DOM'un hazır olduğundan emin ol
        document.addEventListener('DOMContentLoaded', (event) => {
            renderFiveYearGoals();
        });

    </script>
</body>
</html>
