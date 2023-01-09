<?php

namespace App\KeyValueConfigs;

use Genocide\Radiocrud\Helpers;
use Genocide\Radiocrud\Services\KeyValueConfigService;

class AboutUs extends KeyValueConfigService
{
    protected string $key = 'about_us';

    protected array $default_values = [
        'text' => null,
        'image' => null,
        'video' => null,
    ];

    protected array|string $validationRule = [
        'text' => ['nullable', 'string', 'max:25000'],
        'image' => ['file', 'mimes:mp4,webm,ogg,3gp,3g2,3gpp,3gpp2,png,jpg,jpeg,gif', 'max:5000'],
        'video' => ['file', 'mimes:mp4,webm,ogg,3gp,3g2,3gpp,3gpp2,png,jpg,jpeg,gif', 'max:10000'],
    ];

    protected array $casts = [
        'image' => ['file'],
        'video' => ['file'],
    ];
}

