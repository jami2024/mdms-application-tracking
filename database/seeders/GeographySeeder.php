<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Division;
use App\Models\Upazila;
use Illuminate\Database\Seeder;

class GeographySeeder extends Seeder
{
    // All 8 divisions and all 64 districts of Bangladesh — this part is
    // stable, well-documented public administrative data.
    //
    // Upazilas (~495 nationwide) are NOT fully seeded here: hand-writing
    // that many names from memory risks silent inaccuracies presented as
    // fact, which is worse than leaving them incomplete. A representative
    // sample is seeded for a handful of districts so the cascading dropdown
    // is demonstrably wired end-to-end. To complete the set, export the
    // official BBS (Bangladesh Bureau of Statistics) upazila list and load
    // it through the Import feature already built in Phase 2
    // (admin.upazilas — same pattern as the Users import), or via
    // `php artisan tinker` + Upazila::insert() from a CSV.
    public function run(): void
    {
        $divisions = [
            'Dhaka' => [
                'Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Kishoreganj', 'Madaripur',
                'Manikganj', 'Munshiganj', 'Narayanganj', 'Narsingdi', 'Rajbari', 'Shariatpur', 'Tangail',
            ],
            'Chattogram' => [
                'Bandarban', 'Brahmanbaria', 'Chandpur', 'Chattogram', 'Cumilla', "Cox's Bazar",
                'Feni', 'Khagrachhari', 'Lakshmipur', 'Noakhali', 'Rangamati',
            ],
            'Rajshahi' => [
                'Bogura', 'Joypurhat', 'Naogaon', 'Natore', 'Chapainawabganj', 'Pabna', 'Rajshahi', 'Sirajganj',
            ],
            'Khulna' => [
                'Bagerhat', 'Chuadanga', 'Jashore', 'Jhenaidah', 'Khulna', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira',
            ],
            'Barishal' => [
                'Barguna', 'Barishal', 'Bhola', 'Jhalokati', 'Patuakhali', 'Pirojpur',
            ],
            'Sylhet' => [
                'Habiganj', 'Moulvibazar', 'Sunamganj', 'Sylhet',
            ],
            'Rangpur' => [
                'Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Rangpur', 'Thakurgaon',
            ],
            'Mymensingh' => [
                'Jamalpur', 'Mymensingh', 'Netrokona', 'Sherpur',
            ],
        ];

        // A representative slice of upazilas — enough to prove the
        // Division -> District -> Upazila cascade end to end. Extend via
        // Import (see class docblock above) for full national coverage.
        $sampleUpazilas = [
            'Dhaka' => ['Savar', 'Dhamrai', 'Keraniganj', 'Nawabganj', 'Dohar'],
            'Gazipur' => ['Kaliakair', 'Kapasia', 'Sreepur', 'Kaliganj', 'Tongi'],
            'Chattogram' => ['Patiya', 'Sitakunda', 'Rangunia', 'Mirsharai', 'Boalkhali'],
            "Cox's Bazar" => ['Teknaf', 'Ukhia', 'Ramu', 'Chakaria', 'Maheshkhali'],
            'Khulna' => ['Dumuria', 'Batiaghata', 'Paikgacha', 'Rupsa', 'Dighalia'],
            'Rajshahi' => ['Paba', 'Godagari', 'Tanore', 'Bagmara', 'Charghat'],
            'Sylhet' => ['Beanibazar', 'Golapganj', 'Jaintiapur', 'Kanaighat', 'Zakiganj'],
            'Rangpur' => ['Badarganj', 'Gangachara', 'Kaunia', 'Mithapukur', 'Pirganj'],
        ];

        foreach ($divisions as $divisionName => $districts) {
            $division = Division::firstOrCreate(
                ['code' => $this->code($divisionName)],
                ['name' => $divisionName, 'status' => 'active']
            );

            foreach ($districts as $districtName) {
                $district = District::firstOrCreate(
                    ['code' => $this->code($districtName) . '-' . $division->id],
                    ['division_id' => $division->id, 'name' => $districtName, 'status' => 'active']
                );

                foreach ($sampleUpazilas[$districtName] ?? [] as $upazilaName) {
                    Upazila::firstOrCreate(
                        ['code' => $this->code($upazilaName) . '-' . $district->id],
                        ['district_id' => $district->id, 'name' => $upazilaName, 'status' => 'active']
                    );
                }
            }
        }
    }

    private function code(string $name): string
    {
        return strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 4));
    }
}
