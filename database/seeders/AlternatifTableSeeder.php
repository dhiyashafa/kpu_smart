<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Kriteria;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AlternatifTableSeeder extends Seeder
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
        'nama' => 'User1',
        'alamat' => 'Malang',
        'nomer' => '1234',
        'updated_at' => Carbon::now(),
        'created_at'    => Carbon::now()
      ],
      [
        'id' => '2',
        'nama' => 'User2',
        'alamat' => 'Malang',
        'nomer' => '12345',
        'updated_at' => Carbon::now(),
        'created_at'    => Carbon::now()
      ],
    ];

    Alternatif::insert($Kriteria);
  }
}
