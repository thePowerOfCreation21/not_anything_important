<?php

namespace App\KeyValueConfigs;

use Genocide\Radiocrud\Services\KeyValueConfigService;

class Header extends KeyValueConfigService
{
    protected string $key = 'header';

    protected array $default_values = [
        'title' => null,
        'description' => null,
        'link' => null,
    ];

    protected array|string $validationRule = [
        'title' => ['nullable', 'string', 'max:250'],
        'description' => ['nullable', 'string', 'max:5000'],
        'link' => ['nullable', 'string', 'max:1000'],
    ];

    public function updateHelper(array $new_general_value): bool
    {
        cache()->forget('homePage');

        return parent::updateHelper($new_general_value);
    }
}
