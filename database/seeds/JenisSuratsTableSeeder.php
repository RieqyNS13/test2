<?php

use Illuminate\Database\Seeder;
use App\JenisSurat;
class JenisSuratsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenisSurat = new JenisSurat;
        $jenisSurat->name = "Surat Permohonan Pengembangan";
        $jenisSurat->save();

        $jenisSurat = new JenisSurat;
        $jenisSurat->name = "Surat Proposal";
        $jenisSurat->save();

        $jenisSurat = new JenisSurat;
        $jenisSurat->name = "Surat Penerjunan";
        $jenisSurat->save();

	 	$jenisSurat = new JenisSurat;
        $jenisSurat->name = "Surat Penarikan";
        $jenisSurat->save();        

    }
}
