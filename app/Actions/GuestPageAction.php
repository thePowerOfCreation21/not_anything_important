<?php

namespace App\Actions;

use App\KeyValueConfigs\AboutUs;
use App\KeyValueConfigs\Header;

class GuestPageAction
{
    /**
     * @param bool $forceGetFromDB
     * @return array|\Illuminate\Contracts\Cache\Repository|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getHomePage (bool $forceGetFromDB = false): mixed
    {
        $homePage = cache()->get('homePage');

        if (empty($homePage) || $forceGetFromDB)
        {
            $homePage = [
                'header' => Header::get(), // will forget cache when update
                'about_us' => AboutUs::get(), // will forget cache when update
                'gallery_image' => (new GalleryImageAction())
                    ->makeEloquent()
                    ->applyManualChangeToEloquent(function($q){
                        $q->limit(20);
                    })
                    ->getByEloquent(), // will forget cache when store, update, delete
                'important_news' => (new NewsAction())
                    ->makeEloquent()
                    ->applyManualChangeToEloquent(function($q){
                        $q->where('is_important', true)->limit(20);
                    })
                    ->getByEloquent() // will forget cache when store, update, delete
            ];

            cache()->set('homePage', $homePage);
        }

        return $homePage;
    }
}
