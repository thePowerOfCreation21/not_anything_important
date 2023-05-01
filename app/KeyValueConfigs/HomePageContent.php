<?php

namespace App\KeyValueConfigs;

use Genocide\Radiocrud\Services\KeyValueConfigService;

class HomePageContent extends KeyValueConfigService
{
    protected string $key = 'home_page_content';

    protected array $default_values = [
        'link_1' => '',
        'link_2' => '',
        'description_1' => '',
        'description_2' => '',
        'title_1' => '',
        'title_2' => '',
    ];

    protected array|string $validationRule = [
        'link_1' => ['string', 'max:1500'],
        'link_2' => ['string', 'max:1500'],
        'description_1' => ['string', 'max:10000'],
        'description_2' => ['string', 'max:10000'],
        'title_1' => ['string', 'max:1000'],
        'title_2' => ['string', 'max:1000'],
    ];
}
