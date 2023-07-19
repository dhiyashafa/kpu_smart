<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KriteriasTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */

  //(NULL, 't', '15.00', '0.15', '2023-07-15 13:20:17', '2023-07-15 13:20:17')
  public function run()
  {
    // Kriteria::truncate();

    $Kriteria = [
      [
        'id' => '1',
        'nama' => 'Kelengkapan Data',
        'weight' => '40.00',
        'eigen' => '0.40',
        'updated_at' => Carbon::now(),
        'created_at'    => Carbon::now()
      ],
      [
        'id' => '2',
        'nama' => 'Tes Tulis',
        'weight' => '20.00',
        'eigen' => '0.20',
        'updated_at' => Carbon::now(),
        'created_at'    => Carbon::now()
      ],
      [
        'id' => '3',
        'nama' => 'Tes Wawancara',
        'weight' => '25.00',
        'eigen' => '0.25',
        'updated_at' => Carbon::now(),
        'created_at'    => Carbon::now()
      ],
      [
        'id' => '4',
        'nama' => 'Tanggapan Masyaraka',
        'weight' => '15.00',
        'eigen' => '0.15',
        'updated_at' => Carbon::now(),
        'created_at'    => Carbon::now()
      ],
    ];

    Kriteria::insert($Kriteria);
  }
}
