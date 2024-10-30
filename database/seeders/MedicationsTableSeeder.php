<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medications = [
            ['medication' => 'Tiotropium 18mcg/dose', 'isRefillable' => true],
            ['medication' => 'Methyldopa 250mg/ tablet', 'isRefillable' => true],
            ['medication' => 'Clonidine 75mcg tablet', 'isRefillable' => true],
            ['medication' => 'Salbutamol 1mg/ml 2.5ml nebule', 'isRefillable' => true],
            ['medication' => 'Salbutamol 100mcg/dose 200 acuation MDI', 'isRefillable' => true],
            ['medication' => 'Salbutamol 2mg/5ml, syrup 60mL bottle', 'isRefillable' => true],
            ['medication' => 'Salbutamol + Ipratropium Bromide 2.5mg/500mcg 2.5ml nebule', 'isRefillable' => true],
            ['medication' => 'Salmeterol Xinofoate + Fluticasone Propionate 25mcg/250mcg 120acuation MDI', 'isRefillable' => true],
            ['medication' => 'Salmeterol Xinofoate + Fluticasone Propionate 25mcg/125mcg 120acuation MDI', 'isRefillable' => true],
            ['medication' => 'Salmeterol Xinofoate + Fluticasone Propionate 50mcg/250mcg DPI', 'isRefillable' => true],
            ['medication' => 'Budesonide 160mg/Formoterol 4.5mcg Turbohaler', 'isRefillable' => true],
            ['medication' => 'Budesonide 160mg/Formoterol 4.5mcg Rapihaler', 'isRefillable' => true],
            ['medication' => 'Oxymetazoline HCl 0.05% Nasal Spray', 'isRefillable' => true],
            ['medication' => 'Brimonidine 0.15% 5ml eyedrop', 'isRefillable' => true],
            ['medication' => 'Terazosin 2mg tablet', 'isRefillable' => true],
            ['medication' => 'Terazosin 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Tamsulosin 200mcg tablet', 'isRefillable' => true],
            ['medication' => 'Tamsulosin 400mcg tablet', 'isRefillable' => true],
            ['medication' => 'Dorzolamide 20mg + Timolol 5mg Eye Drops Solution', 'isRefillable' => true],
            ['medication' => 'Brimonidine 2mg + Timolol 5mg Eye Drops Solution', 'isRefillable' => true],
            ['medication' => 'Metoprolol 50mg tablet', 'isRefillable' => true],
            ['medication' => 'Metoprolol 100mg tablet', 'isRefillable' => true],
            ['medication' => 'Betaxolol 0.5% eyedrop', 'isRefillable' => true],
            ['medication' => 'Timolol 0.5% 5ml eyedrop', 'isRefillable' => true],
            ['medication' => 'Carvedilol 6.25mg tablet', 'isRefillable' => true],
            ['medication' => 'Carvedilol 25mg tab tablet', 'isRefillable' => true],
            ['medication' => 'Furosemide 20mg tablet', 'isRefillable' => true],
            ['medication' => 'Furosemide 40mg tablet', 'isRefillable' => true],
            ['medication' => 'Furosemide 10mg/ml 2ml', 'isRefillable' => true],
            ['medication' => 'Spironolactone 25mg tablet', 'isRefillable' => true],
            ['medication' => 'Spironolactone 50mg tablet', 'isRefillable' => true],
            ['medication' => 'Spironolactone 100mg tablet', 'isRefillable' => true],
            ['medication' => 'Dorzolamide 2% 5ml eyedrop', 'isRefillable' => true],
            ['medication' => 'Acetazolamide 250mg tablet', 'isRefillable' => true],
            ['medication' => 'Amlodipine besylate 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Amlodipine besylate 10mg tablet', 'isRefillable' => true],
            ['medication' => 'Felodipine 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Nifedipine 30mg tablet', 'isRefillable' => true],
            ['medication' => 'Verapamil Hydrochloride 80mg tablet', 'isRefillable' => true],
            ['medication' => 'Verapamil Hydrochloride 240mg tablet', 'isRefillable' => true],
            ['medication' => 'Captopril 25mg tablet', 'isRefillable' => true],
            ['medication' => 'Enalapril 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Perindopril 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Perindopril 5mg + Indapamide 1.25mg tablet', 'isRefillable' => true],
            ['medication' => 'Perindopril 3.5mg + Amlodipine 2.5mg tablet', 'isRefillable' => true],
            ['medication' => 'Perindopril 5mg + Amlodipine 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Perindopril 5mg + Amlodipine 5mg + Indapamide 1.25mg tablet', 'isRefillable' => true],
            ['medication' => 'Losartan 50mg tablet', 'isRefillable' => true],
            ['medication' => 'Losartan 100mg tablet', 'isRefillable' => true],
            ['medication' => 'Irbesartan 150mg tablet', 'isRefillable' => true],
            ['medication' => 'Irbesartan 300mg tablet', 'isRefillable' => true],
            ['medication' => 'Losartan 50mg + HCTZ 12.5mg tablet', 'isRefillable' => true],
            ['medication' => 'Irbesartan 150mg + HCTZ 12.5mg tablet', 'isRefillable' => true],
            ['medication' => 'Irbesartan 300mg + HCTZ 12.5mg tablet', 'isRefillable' => true],
            ['medication' => 'Sacubitril + Valsartan 50mg tablet', 'isRefillable' => true],
            ['medication' => 'Sacubitril + Valsartan 100mg tablet', 'isRefillable' => true],
            ['medication' => 'Sacubitril + Valsartan 200mg tablet', 'isRefillable' => true],
            ['medication' => 'Isosorbide Mononitrate 60mg tablet', 'isRefillable' => true],
            ['medication' => 'Isosorbide Dinitrate 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Digoxin 0.25mg tablet', 'isRefillable' => true],
            ['medication' => 'Amiodarone Hcl 200mg tablet', 'isRefillable' => true],
            ['medication' => 'Trimetazidine 35mg tablet', 'isRefillable' => true],
            ['medication' => 'Ivabradine 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Chlorphenamine/Phenylpropanolamine 1MG/5MG/5ML syrup 60ml or 120ml bottle', 'isRefillable' => true],
            ['medication' => 'Cetirizine 10mg/ml per 10ml drops', 'isRefillable' => true],
            ['medication' => 'Cetirizine 5mg/5ml Syrup', 'isRefillable' => true],
            ['medication' => 'Cetirizine Dihydrochloride 10mg tablet', 'isRefillable' => true],
            ['medication' => 'Chlorphenamine 2.5mg/5ml syrup', 'isRefillable' => true],
            ['medication' => 'Chlorphenamine Maleate 4mg tablet', 'isRefillable' => true],
            ['medication' => 'Loratadine 10mg tablet', 'isRefillable' => true],
            ['medication' => 'Levocetirizine 5mg tablet', 'isRefillable' => true],
            ['medication' => 'Ebastine 10mg tablet', 'isRefillable' => true],
            ['medication' => 'Ranitidine 300mg tablet', 'isRefillable' => true],
            ['medication' => 'Amoxicillin 100mg/ml drops', 'isRefillable' => false],
            ['medication' => 'Amoxicillin 250mg/5ml suspension', 'isRefillable' => false],
            ['medication' => 'Amoxicillin 500mg cap', 'isRefillable' => false],
            ['medication' => 'Penicillin G Benzathine 1.2M units Vial', 'isRefillable' => false],
            ['medication' => 'Ampicillin (as Sodium Salt)1g vial', 'isRefillable' => false],
            ['medication' => 'Cloxacillin 250mg/5ml 60ml suspension', 'isRefillable' => false],
            ['medication' => 'Cloxacillin 500mg tablet', 'isRefillable' => false],
            ['medication' => 'Co-amoxiclav 228.5mg/5ml suspension', 'isRefillable' => false],
            ['medication' => 'Co-amoxiclav 457mg/5ml suspension', 'isRefillable' => false],
            ['medication' => 'Co-amoxiclav 642.9mg/5ml suspension', 'isRefillable' => false],
            ['medication' => 'Co-amoxiclav 625mg tablet', 'isRefillable' => false],
            ['medication' => 'Co-amoxiclav 1000mg tablet', 'isRefillable' => false],
            ['medication' => 'Sultamicillin 750mg tablet', 'isRefillable' => false],
            ['medication' => 'Cefalexin 250mg/5ml 60ml suspension', 'isRefillable' => false],
            ['medication' => 'Cefalexin 500mg tablet', 'isRefillable' => false],
            ['medication' => 'Cefuroxime 250mg/5ml suspension', 'isRefillable' => false],
            ['medication' => 'Cefuroxime 500mg tablet', 'isRefillable' => false],
            ['medication' => 'Cefixime Trihydrate 100mg/5ml, 60ml suspension', 'isRefillable' => false],
            ['medication' => 'Cefixime 200mg tablet', 'isRefillable' => false],
            ['medication' => 'Cefixime 400mg tablet', 'isRefillable' => false],
            ['medication' => 'Ceftriaxone disodium/ Sodium 1g tablet', 'isRefillable' => false]
        ];

        DB::table('medications')->insert($medications);
    }
}