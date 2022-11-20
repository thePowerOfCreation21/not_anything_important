<?php

namespace App\Actions;

use App\Http\Resources\StudentFinancialResource;
use App\Models\StudentFinancialModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Actions\StudentAction;

class StudentFinancialAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentFinancialModel::class)
            ->setResource(StudentFinancialResource::class)
            ->setValidationRules([
                'store' => [
                    'amount' => ['int', 'required'],
                    'date' => ['date', 'required'],
                    'paid' => ['bool'],
                ]
            ]);

        parent::__construct();
    }

    /**
     * @param string $student_id
     * @return mixed
     * @throws CustomException
     */
    public function storeStudentFinancialById(string $student_id): mixed
    {
        $data = $this->getDataFromRequest();

        $data['student_id'] = (new StudentAction())->getById($student_id)->id;

        return parent::store($data);
    }
}
