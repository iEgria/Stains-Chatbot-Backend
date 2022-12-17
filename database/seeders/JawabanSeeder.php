<?php

namespace Database\Seeders;

use App\Models\Jawaban;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jawaban::create([
            'keyword' => 'harga',
            'jawaban' => 'Harga untuk {barang.nama_barang} adalah Rp. {barang.harga_jual_formated}',
            'require' => 'barang',
        ]);

        Jawaban::create([
            'keyword' => 'stock,stok',
            'jawaban' => 'Stock {barang.nama_barang} saat ini ada {barang.stok_barang} buah.',
            'require' => 'barang',
        ]);
    }
}
