<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\MessageTemplate;
use App\Http\Resources\MessageTemplateResource;

class MessageTemplateAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(MessageTemplate::class)
            ->setResource(MessageTemplateResource::class);

        parent::__construct();
    }
}
