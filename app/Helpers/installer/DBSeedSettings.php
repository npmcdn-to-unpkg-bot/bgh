<?php

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        Eloquent::unguard();
        User::create([
            'username'   => 'SITE_USERNAME',
            'password'   => Hash::make('SITE_PASSWORD'),
            'fullname'   => 'SITE_FULLNAME',
            'email'      => 'SITE_EMAIL',
            'confirmed'  => '1',
            'avatar'     => 'user',
            'permission' => 'admin',
        ]);

        DB::table('sitesettings')
            ->insert([
                ['option' => 'siteName', 'value' => 'Rodrigo'],
                ['option' => 'description', 'value' => 'Some Description'],
                ['option' => 'favIcon', 'value' => 'favicon.ico'],
                ['option' => 'tos', 'value' => 'add tos here'],
                ['option' => 'privacy', 'value' => 'add privacy policy here'],
                ['option' => 'faq', 'value' => 'add faq here'],
                ['option' => 'about', 'value' => 'add about us here'],
                ['option' => 'autoApprove', 'value' => '1'],
                ['option' => 'numberOfImagesInGallery', 'value' => '20'],
                ['option' => 'limitPerDay', 'value' => '20'],
                ['option' => 'tagsLimit', 'value' => '5'],
                ['option' => 'allowDownloadOriginal', 'value' => '1'],
                ['option' => 'maxImageSize', 'value' => '10'],
            ]);

        DB::table('categories')
            ->insert([
                ['name' => 'Uncategorized', 'slug' => 'uncategorized'],
            ]);

        // $this->call('UserTableSeeder');
    }

}