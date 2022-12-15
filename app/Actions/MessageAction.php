<?php

namespace App\Actions;

use App\Models\MessageStudent;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\Message;
use App\Actions\MessageStudentAction;
use App\Actions\MessageTemplateAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use App\Http\Resources\MessageResource;

class MessageAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(Message::class)
            ->setResource(MessageResource::class)
            ->setValidationRules([
                'store' => [
                    'type' => ['required', 'string', 'in:sms,notification,toast'],
                    'text' => ['string', 'max:500'],
                    'template_id' => ['string', 'max:20'],
                    'students_id' => ['required','array', 'max:100'],
                    'students_id.*.student_id' => ['required', 'string', 'max:20']
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

            if($templateObject->sms_status)
            {
                //we have to send sms later.
            }

            $data['text'] = $templateObject->text;
        }

        $message = parent::store($data, $storing);

        $studentsId = [];

        foreach ($data['students_id'] as $studentId)
        {
            $studentsId[$studentId['student_id']] = $studentId;
            $studentsId[$studentId['student_id']]['message_id'] = $message->id;
        }

        MessageStudent::insert(array_values($studentsId));

        return $message;
    }
}
