<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            ['name'=>'Երևան'],
            ['name'=>'Գյումրի'],
            ['name'=>'Վանաձոր'],
            ['name'=>'Էջմիածին'],
            ['name'=>'Հրազդան'],
            ['name'=>'Աբովյան'],
            ['name'=>'Կապան'],
            ['name'=>'Արարատ'],
            ['name'=>'Արմավիր'],
            ['name'=>'Ստեփանավան'],
            ['name'=>'Գավառ'],
            ['name'=>'Արտաշատ'],
            ['name'=>'Գորիս'],
            ['name'=>'Մասիս'],
            ['name'=>'Աշտարակ'],
            ['name'=>'Սևան'],
            ['name'=>'Հացավան'],
            ['name'=>'Սպիտակ'],
            ['name'=>'Իջևան'],
            ['name'=>'Դիլիջան'],
            ['name'=>'Ալավերդի'],
            ['name'=>'Վեդի'],
            ['name'=>'Վարդենիս'],
            ['name'=>'Մարտունի'],
            ['name'=>'Եղվարդ'],
            ['name'=>'Մեծամոր'],
            ['name'=>'Բերդ'],
            ['name'=>'Եղեգնաձոր'],
            ['name'=>'Վարդենիկ'],
            ['name'=>'Ախուրյան'],
            ['name'=>'Տաշիր'],
            ['name'=>'Ներքին Գետաշեն'],
            ['name'=>'Բյուրեղավան'],
            ['name'=>'Գառնի'],
            ['name'=>'Սարուխան'],
            ['name'=>'Ճամբարակ'],
            ['name'=>'Ապարան'],
            ['name'=>'Նորատուս'],
            ['name'=>'Վայք'],
            ['name'=>'Սարդարապատ'],
            ['name'=>'Նոյեմբերյան'],
            ['name'=>'Կարանլուխ'],
        ]);
    }
}
