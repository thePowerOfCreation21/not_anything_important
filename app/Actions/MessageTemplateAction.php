<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\MessageTemplateModel;
use App\Http\Resources\MessageTemplateResource;

class MessageTemplateAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(MessageTemplateModel::class)
            ->setResource(MessageTemplateResource::class)
            ->setValidationRules([
                'store' => [
                    'name' => ['required', 'string', 'max:255'],
                    'text' => ['required', 'string', 'max:500'],
                    'is_smsable' => ['bool']
                ],
                'update' => [
                    'name' => ['string', 'max:255'],
                    'text' => ['string', 'max:500'],
                    'is_smsable' => ['bool']
                ]
            ]);

        parent::__construct();
    }
}
