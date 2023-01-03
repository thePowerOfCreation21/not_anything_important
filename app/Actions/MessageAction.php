<?php

namespace App\Actions;

use App\Models\MessageStudentModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\MessageModel;
use App\Actions\MessageStudentAction;
use App\Actions\MessageTemplateAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use App\Http\Resources\MessageResource;

class MessageAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(MessageModel::class)
            ->setResource(MessageResource::class)
            ->setValidationRules([
                'store' => [
                    'type' => ['required', 'string', 'in:sms,notification,toast,message'],
                    'text' => ['string', 'max:500'],
                    'template_id' => ['string', 'max:20'],
                    'students' => ['required','array', 'max:100'],
                    'students.*' => ['required', 'integer', 'between:1,999999999999999999']
                ]
            ]);

        parent::__construct();
    }


    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     */
    public function store(array $data, callable $storing = null): mixed
    {
        if($data['type'] == 'sms')
        {
            $templateObject = (new MessageTemplateAction())->getById($data['template_id']);

            if($templateObject->is_smsable)
            {
                //we have to send sms later.
            }

            $data['text'] = $templateObject->text;
        }

        $message = parent::store($data, $storing);

        $messageStudents = [];

        foreach ($data['students'] as $studentId)
        {
            $messageStudents[] = [
                'student_id' => $studentId,
                'message_id' => $message->id
            ];
        }

        MessageStudentModel::insert($messageStudents);

        return $message;
    }
}
