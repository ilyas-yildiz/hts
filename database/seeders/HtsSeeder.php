<?php

namespace Database\Seeders;

use App\Models\AnnualGoal;
use App\Models\DailyGoal;
use App\Models\GoalCategory;
use App\Models\MonthlyGoal;
use App\Models\Task;
use App\Models\User;
use App\Models\WeeklyGoal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HtsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === 1. KULLANICI OLUŞTUR (firstOrCreate) ===
        // 'email' adresine göre bul, eğer yoksa 'name' ve 'password'ü de ekleyerek oluştur.
        $user = User::firstOrCreate(
            ['email' => 'mail@adresiniz.com'], // Bu email ile ara
            [
                'name' => 'İlyas Yıldız', // Bulamazsan bu verilerle oluştur
                'password' => Hash::make('123456'),
            ]
        );

        // === 2. KATEGORİLER OLUŞTUR (firstOrCreate) ===
        // 'user_id' ve 'name' kombinasyonuna göre ara
        $catKisisel = GoalCategory::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Kişisel Gelişim',
        ]);

        $catFinans = GoalCategory::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Finansal Özgürlük',
        ]);

        // === 3. YILLIK HEDEFLER (firstOrCreate) ===
        // 'goal_category_id' ve 'year' kombinasyonuna göre ara
        $yillik1 = AnnualGoal::firstOrCreate(
            [
                'goal_category_id' => $catKisisel->id,
                'year' => 1,
            ],
            [
                'period_label' => 'Eylül 2026 Sonu',
                'title' => 'Yılda 50 kitap okumak ve 5 adet backend projesi bitirmek',
            ]
        );

        $yillik2 = AnnualGoal::firstOrCreate(
            [
                'goal_category_id' => $catFinans->id,
                'year' => 1,
            ],
            [
                'period_label' => 'Eylül 2026 Sonu',
                'title' => 'Aylık 10.000 TL ek gelir oluşturmak',
            ]
        );

        // === 4. AYLIK HEDEFLER (firstOrCreate) ===
        $aylik1 = MonthlyGoal::firstOrCreate(
            [
                'annual_goal_id' => $yillik1->id,
                'month_label' => 'Ekim 2025',
            ],
            [
                'title' => '4 kitap oku, HTS projesine başla',
            ]
        );

        // === 5. HAFTALIK HEDEFLER (firstOrCreate) ===
        $haftalik1 = WeeklyGoal::firstOrCreate(
            [
                'monthly_goal_id' => $aylik1->id,
                'week_label' => '1. Hafta (1-7 Ekim)',
            ],
            [
                'title' => 'HTS Projesi Laravel kurulumunu bitir',
            ]
        );

        // === 6. GÜNLÜK HEDEFLER (firstOrCreate) ===
        $gunluk1 = DailyGoal::firstOrCreate(
            [
                'weekly_goal_id' => $haftalik1->id,
                'day_label' => 'Pazartesi',
            ],
            [
                'title' => 'Backend API Rotaları',
            ]
        );

        $gunluk2 = DailyGoal::firstOrCreate(
            [
                'weekly_goal_id' => $haftalik1->id,
                'day_label' => 'Salı',
            ],
            [
                'title' => 'Frontend Arayüz Bağlantısı',
            ]
        );

        DailyGoal::firstOrCreate(
            [
                'weekly_goal_id' => $haftalik1->id,
                'day_label' => 'Çarşamba',
            ],
            [
                'title' => null,
            ]
        );

        // === 7. GÖREVLER (firstOrCreate) ===
        // 'daily_goal_id' ve 'task_description' kombinasyonuna göre ara
        Task::firstOrCreate(
            [
                'daily_goal_id' => $gunluk1->id,
                'task_description' => 'Proje Planı Revize',
            ],
            [
                'time_label' => '09:00 - 10:00',
                'is_completed' => true,
            ]
        );

        Task::firstOrCreate(
            [
                'daily_goal_id' => $gunluk1->id,
                'task_description' => 'GoalController ve Rotaları yaz',
            ],
            [
                'time_label' => '10:00 - 12:00',
                'is_completed' => false,
            ]
        );

        Task::firstOrCreate(
            [
                'daily_goal_id' => $gunluk1->id,
                'task_description' => 'Seeder dosyasını hazırla',
            ],
            [
                'time_label' => '14:00 - 15:00',
                'is_completed' => false,
            ]
        );
    }
}

