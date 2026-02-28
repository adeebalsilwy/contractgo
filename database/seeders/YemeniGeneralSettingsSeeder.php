<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class YemeniGeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Yemeni company general settings
        $yemeniSettings = [
            'company_title' => 'الشركة اليمنية للإنشاء والتطوير',
            'site_url' => 'https://alafary-yemen.com',
            'timezone' => 'Asia/Aden',
            'currency_full_form' => 'ريال يمني',
            'currency_symbol' => '﷼',
            'currency_code' => 'YER',
            'currency_symbol_position' => 'after',
            'currency_formate' => 'comma_separated',
            'decimal_points_in_currency' => '2',
            'date_format' => 'DD-MM-YYYY|d-m-Y',
            'time_format' => 'H:i:s',
            'toast_position' => 'toast-top-right',
            'toast_time_out' => '5',
            'footer_text' => '<p>صنع بفخر في اليمن | شركة الافاري للإنشاء والتطوير</p>',
            'full_logo' => 'logos/default_full_logo.png',
            'half_logo' => 'logos/default_half_logo.png',
            'favicon' => 'logos/default_favicon.png',
            'footer_logo' => 'logos/footer_logo.png',
            'allowed_max_upload_size' => '512',
            'allowSignup' => '1',
            'priLangAsAuth' => '1',
            'upcomingBirthdays' => '5',
            'upcomingWorkAnniversaries' => '5',
            'membersOnLeave' => '5',
            'recaptcha_enabled' => '0',
            'recaptcha_site_key' => '',
            'recaptcha_secret_key' => '',
        ];

        // Create or update the general settings
        $setting = Setting::updateOrCreate(
            ['variable' => 'general_settings'],
            ['value' => json_encode($yemeniSettings)]
        );

        $this->command->info('Yemeni general settings seeded successfully!');
    }
}