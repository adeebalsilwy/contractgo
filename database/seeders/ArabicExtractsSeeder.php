<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\EstimatesInvoice;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Client;
use App\Models\User;
use Faker\Factory as Faker;

class ArabicExtractsSeeder extends Seeder
{
    /**
     * Run the database seeds with realistic Arabic Yemeni data.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA'); // Arabic Saudi locale for Arabic text
        
        // Arabic Yemeni data arrays
        $arabicContractTypes = [
            'عقد تنفيذ أعمال البناء',
            'عقد الصيانة والتشغيل',
            'عقد التوريد والتركيب',
            'عقد الاستشارات الهندسية',
            'عقد الخدمات اللوجستية',
            'عقد التوريد والخدمات',
            'عقد التشغيل والصيانة',
            'عقد المشاريع العامة'
        ];

        $arabicItems = [
            [
                'title' => 'أعمال الجبس والتشطيب',
                'description' => 'تركيب ألواح الجبس مع المواد اللازمة والتشطيب النهائي'
            ],
            [
                'title' => 'دهانات داخلية',
                'description' => 'دهان الجدران الداخلية بالدهانات الفاخرة عالية الجودة'
            ],
            [
                'title' => 'بلاطات الأرضيات',
                'description' => 'تركيب بلاطات سيراميك الأرضيات مع اللصق والتجليد'
            ],
            [
                'title' => 'التركيبات الكهربائية',
                'description' => 'التركيب الكهربائي الكامل بما في ذلك الأسلاك والمفاتيح'
            ],
            [
                'title' => 'أعمال السباكة',
                'description' => 'تركيب السباكة لتجهيزات المياه والصرف الصحي'
            ],
            [
                'title' => 'أعمال النجارة المعمارية',
                'description' => 'تصنيع وتركيب الأعمال الخشبية المعمارية'
            ],
            [
                'title' => 'أعمال الصلب والإنشاءات المعدنية',
                'description' => 'تركيب الهياكل الفولاذية والإنشاءات المعدنية'
            ],
            [
                'title' => 'أعمال العزل الحراري والصوتي',
                'description' => 'تركيب مواد العزل الحراري والصوتي'
            ],
            [
                'title' => 'أعمال الشبكات والاتصالات',
                'description' => 'تركيب شبكات الاتصالات وأنظمة الشبكات'
            ],
            [
                'title' => 'أعمال التكييف والتهوية',
                'description' => 'تركيب أنظمة التكييف والتهوية المركزية'
            ]
        ];

        $arabicUnits = [
            ['title' => 'متر مربع', 'description' => 'م²'],
            ['title' => 'متر خطي', 'description' => 'م'],
            ['title' => 'قطعة', 'description' => 'قطعة'],
            ['title' => 'مجموعة', 'description' => 'مجموعة'],
            ['title' => 'ساعة', 'description' => 'ساعة'],
            ['title' => 'يوم عمل', 'description' => 'يوم'],
            ['title' => 'كيلوغرام', 'description' => 'كغ'],
            ['title' => 'لتر', 'description' => 'لتر']
        ];

        $arabicStatuses = [
            'draft' => 'مسودة',
            'sent' => 'مرسلة',
            'accepted' => 'مقبولة',
            'declined' => 'مرفوضة',
            'expired' => 'منتهية الصلاحية',
            'partially_paid' => 'مدفوعة جزئياً',
            'fully_paid' => 'مدفوعة بالكامل',
            'cancelled' => 'ملغاة',
            'due' => 'مستحقة'
        ];

        $arabicNotes = [
            'مستخلص أعمال تنفيذية ل.phase الأول من المشروع',
            'مستخلص شهري لأعمال الصيانة والتشغيل',
            'مستخلص توريد المواد والمعدات',
            'مستخلص خدمات استشارية هندسية',
            'مستخلص أعمال التشطيب النهائي',
            'مستخلص أعمال التمديدات الكهربائية',
            'مستخلص أعمال السباكة والصرف الصحي',
            'مستخلص أعمال الدهانات والتشطيبات',
            'مستخلص أعمال النجارة والخشب',
            'مستخلص أعمال العزل والتشطيبات'
        ];

        $arabicCities = [
            'صنعاء', 'عدن', 'تعز', 'الحديدة', '-Taiz', 'Ibb', 'ذمار', 'عمران', 'البيضاء', 'حضرموت',
            'شبوة', 'لحج', 'أبين', 'الضالع', 'مأرب', 'صنعاء الجديدة', 'الجوف', 'حجة', 'صعدة', 'ريمة'
        ];

        $arabicStates = [
            'صنعاء', 'عدن', 'تعز', 'الحديدة', '-Taiz', 'Ibb', 'ذمار', 'عمران', 'البيضاء', 'حضرموت',
            'شبوة', 'لحج', 'أبين', 'الضالع', 'مأرب', 'صنعاء الجديدة', 'الجوف', 'حجة', 'صعدة', 'ريمة'
        ];

        $arabicCountries = ['اليمن', 'السعودية', 'الإمارات', 'قطر', 'الكويت', 'البحرين', 'عمان', 'الأردن', 'مصر', 'السودان'];

        // Get existing records
        $contracts = Contract::all();
        $clients = Client::all();
        $users = User::all();
        
        if ($contracts->isEmpty() || $clients->isEmpty() || $users->isEmpty()) {
            $this->command->info('Please make sure you have contracts, clients, and users in the database before running this seeder.');
            return;
        }

        $this->command->info('Starting to seed Arabic extracts data...');

        // Create extracts (estimates and invoices) for each contract
        foreach ($contracts as $contract) {
            $client = $clients->find($contract->client_id);
            if (!$client) {
                continue;
            }

            // Create 2-4 extracts per contract
            $extractCount = rand(2, 4);
            
            for ($i = 0; $i < $extractCount; $i++) {
                // Determine if it's an estimate or invoice (70% estimates, 30% invoices)
                $isEstimate = $faker->boolean(70);
                $type = $isEstimate ? 'estimate' : 'invoice';
                
                // Generate realistic dates within contract period
                $startDate = new \DateTime($contract->start_date);
                $endDate = new \DateTime($contract->end_date);
                $extractDate = $faker->dateTimeBetween($startDate, $endDate);
                
                // Generate realistic amounts based on contract value
                $baseAmount = $contract->value * $faker->randomFloat(2, 0.1, 0.3); // 10-30% of contract value
                $taxAmount = $baseAmount * 0.15; // 15% VAT (common in Yemen)
                $finalTotal = $baseAmount + $taxAmount;

                // Create the extract
                $extract = EstimatesInvoice::create([
                    'workspace_id' => $contract->workspace_id,
                    'client_id' => $contract->client_id,
                    'contract_id' => $contract->id,
                    'name' => $isEstimate ? 'مستخلص تقديري - ' . $contract->title : 'فاتورة مستخلص - ' . $contract->title,
                    'address' => $client->address ?? $faker->address,
                    'city' => $client->city ?? $faker->randomElement($arabicCities),
                    'state' => $client->state ?? $faker->randomElement($arabicStates),
                    'country' => $client->country ?? $faker->randomElement($arabicCountries),
                    'zip_code' => $client->zip_code ?? $faker->randomNumber(5),
                    'phone' => $client->phone ?? $faker->phoneNumber,
                    'type' => $type,
                    'status' => $faker->randomElement(array_keys($arabicStatuses)),
                    'from_date' => $extractDate->format('Y-m-d'),
                    'to_date' => $faker->dateTimeBetween($extractDate, $endDate)->format('Y-m-d'),
                    'total' => $baseAmount,
                    'tax_amount' => $taxAmount,
                    'final_total' => $finalTotal,
                    'created_by' => 'u_' . $users->random()->id,
                    'note' => $faker->randomElement($arabicNotes) . ' - ' . $contract->title,
                    'personal_note' => $isEstimate ? 'مستخلص تقديري للمرحلة ' . ($i + 1) : 'فاتورة مستخلص للخدمات المقدمة'
                ]);

                // Attach 3-6 items to each extract
                $itemCount = rand(3, 6);
                $selectedItems = $faker->randomElements($arabicItems, min($itemCount, count($arabicItems)));
                
                $runningTotal = 0;
                foreach ($selectedItems as $arabicItem) {
                    // Find or create the item
                    $item = Item::firstOrCreate(
                        ['title' => $arabicItem['title']],
                        [
                            'description' => $arabicItem['description'],
                            'workspace_id' => $contract->workspace_id,
                            'unit_id' => Unit::inRandomOrder()->first()->id,
                            'price' => $faker->randomFloat(2, 5000, 50000) // Yemeni Rial amounts
                        ]
                    );
                    
                    $unit = Unit::inRandomOrder()->first();
                    $quantity = $faker->randomFloat(2, 10, 200);
                    $rate = $faker->randomFloat(2, 1000, 20000); // Yemeni Rial rates
                    $amount = $quantity * $rate;
                    $runningTotal += $amount;
                    
                    $extract->items()->attach($item->id, [
                        'qty' => $quantity,
                        'unit_id' => $unit->id,
                        'rate' => $rate,
                        'tax_id' => null,
                        'amount' => $amount,
                    ]);
                }
                
                // Update the extract totals to match the actual item amounts
                $extract->update([
                    'total' => $runningTotal,
                    'tax_amount' => $runningTotal * 0.15,
                    'final_total' => $runningTotal * 1.15
                ]);

                $this->command->info("Created {$type} for contract: {$contract->title}");
            }
        }

        // Create some standalone extracts not linked to contracts (10% of total)
        $standaloneCount = max(5, intval($contracts->count() * 0.1));
        
        for ($j = 0; $j < $standaloneCount; $j++) {
            $client = $clients->random();
            $isEstimate = $faker->boolean(60);
            $type = $isEstimate ? 'estimate' : 'invoice';
            
            $baseAmount = $faker->randomFloat(2, 100000, 1000000); // Yemeni Rial
            $taxAmount = $baseAmount * 0.15;
            $finalTotal = $baseAmount + $taxAmount;

            $extract = EstimatesInvoice::create([
                'workspace_id' => 1,
                'client_id' => $client->id,
                'contract_id' => null, // No contract link
                'name' => $isEstimate ? 'تقدير خدمات - ' . $faker->company : 'فاتورة خدمات - ' . $faker->company,
                'address' => $client->address ?? $faker->address,
                'city' => $client->city ?? $faker->randomElement($arabicCities),
                'state' => $client->state ?? $faker->randomElement($arabicStates),
                'country' => $client->country ?? $faker->randomElement($arabicCountries),
                'zip_code' => $client->zip_code ?? $faker->randomNumber(5),
                'phone' => $client->phone ?? $faker->phoneNumber,
                'type' => $type,
                'status' => $faker->randomElement(array_keys($arabicStatuses)),
                'from_date' => $faker->date('Y-m-d'),
                'to_date' => $faker->date('Y-m-d'),
                'total' => $baseAmount,
                'tax_amount' => $taxAmount,
                'final_total' => $finalTotal,
                'created_by' => 'u_' . $users->random()->id,
                'note' => $faker->randomElement($arabicNotes),
                'personal_note' => $isEstimate ? 'تقدير خدمات متنوعة' : 'فاتورة خدمات عامة'
            ]);

            // Attach items to standalone extract
            $itemCount = rand(2, 5);
            $selectedItems = $faker->randomElements($arabicItems, min($itemCount, count($arabicItems)));
            
            $runningTotal = 0;
            foreach ($selectedItems as $arabicItem) {
                $item = Item::firstOrCreate(
                    ['title' => $arabicItem['title']],
                    [
                        'description' => $arabicItem['description'],
                        'workspace_id' => 1,
                        'unit_id' => Unit::inRandomOrder()->first()->id,
                        'price' => $faker->randomFloat(2, 5000, 50000)
                    ]
                );
                
                $unit = Unit::inRandomOrder()->first();
                $quantity = $faker->randomFloat(2, 5, 100);
                $rate = $faker->randomFloat(2, 2000, 30000);
                $amount = $quantity * $rate;
                $runningTotal += $amount;
                
                $extract->items()->attach($item->id, [
                    'qty' => $quantity,
                    'unit_id' => $unit->id,
                    'rate' => $rate,
                    'tax_id' => null,
                    'amount' => $amount,
                ]);
            }
            
            $extract->update([
                'total' => $runningTotal,
                'tax_amount' => $runningTotal * 0.15,
                'final_total' => $runningTotal * 1.15
            ]);

            $this->command->info("Created standalone {$type} for client: {$client->name}");
        }

        $this->command->info('Arabic extracts seeding completed successfully!');
        $this->command->info('Total extracts created: ' . EstimatesInvoice::count());
        $this->command->info('Extracts linked to contracts: ' . EstimatesInvoice::whereNotNull('contract_id')->count());
        $this->command->info('Standalone extracts: ' . EstimatesInvoice::whereNull('contract_id')->count());
    }
}