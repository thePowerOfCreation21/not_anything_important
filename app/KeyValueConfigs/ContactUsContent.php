<?php

namespace App\KeyValueConfigs;

use Genocide\Radiocrud\Services\KeyValueConfigService;

class ContactUsContent extends KeyValueConfigService
{
    protected string $key = 'contact_us_content';

    protected array $default_values = [
        'address' => null,
        'phone_number' => null,
        'whatsapp' => null,
        'instagram' => null
    ];

    protected string|array $validationRule = [
        'address' => ['string', 'max:2500'],
        'phone_number' => ['string', 'max:15'],
        'whatsapp' => ['string', 'max:250'],
        'instagram' => ['string', 'max:250']
    ];
}
