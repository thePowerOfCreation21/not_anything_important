<?php

namespace App\Actions;

use App\Models\MessageReceiverPivotModel;
use App\Models\MessageStudentModel;
use App\Models\MessageTemplateModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\MessageModel;
use App\Actions\MessageStudentAction;
use App\Actions\MessageTemplateAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use App\Http\Resources\MessageResource;
use Genocide\Radiocrud\Services\SendSMSService;

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
                    'template_id' => ['integer'],
                    'students' => ['required','array', 'max:100'],
                    'students.*' => ['required', 'integer'],
                    'teachers' => ['required','array', 'max:100'],
                    'teachers.*' => ['required', 'integer']
                ]
            ]);

        parent::__construct();
    }


    public function store(array $data, callable $storing = null): mixed
    {
        if ($data['type'] == 'sms')
        {
            $template = MessageTemplateModel::query()
                ->where('id', @$data['template_id'])
                ->where('is_smsable', true)
                ->first();

            throw_if(empty($template), CustomException::class, 'template_id is required and should be right', '218602', 400);

            $phoneNumbers = [];

            if (isset($data['students']))
                foreach (StudentModel::query()->whereIn('id', $data['students'])->get() AS $student)
                    if (! empty($student->mobile_number)) $phoneNumbers[] = $student->mobile_number;
            else if (isset($data['teachers']))
                foreach (TeacherModel::query()->whereIn('id', $data['teachers'])->get() AS $teacher)
                    if (! empty($teacher->phone_number)) $phoneNumbers[] = $teacher->phone_number;

            (new SendSMSService())->sendOTP($phoneNumbers, $template->name, rand(1, 9999999));

            return 'sms sent';
        }

        $message = parent::store($data, $storing);

        $messageReceiverPivots = [];

        if (isset($data['students']))
            $receiverClass = StudentModel::class;
        elseif (isset($data['teachers']))
            $receiverClass = TeacherModel::class;

        if (isset($receiverClass))
            foreach ($receiverClass::query()->whereIn('id', $data['students'] ?? $data['teachers'])->get() AS $receiver)
                $messageReceiverPivots[] = [
                    'message_id' => $message->id,
                    'receiver_type' => $receiverClass,
                    'receiver_id' => $receiver->id,
                    'is_seen' => false
                ];

        if (! empty($messageReceiverPivots)) MessageReceiverPivotModel::query()->insert($messageReceiverPivots);

        return $message;
    }
}
