<?php

namespace App\Actions;

use App\Models\ClassModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ReportCardModel;
use App\Http\Resources\ReportCardResource;

class ReportCardAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setValidationRules([
                'store' => [
                    'title' => ['string', 'max:150'],
                    'month' => ['string', 'max:100'],
                    'educational_year' => ['string', 'max:100'],
                    'class_id' => ['integer']
                ],
            ])
            ->setModel(ReportCardModel::class)
            ->setResource(ReportCardResource::class);
        parent::__construct();
    }

    protected function store(array $data, callable $storing = null): mixed
    {
        throw_if(
            ReportCardModel::query()->where('class_id', $data['class_id'])->where('was_issued', false)->exists(),
            CustomException::class, 'this class already has a not issued report card', '416732', '400'
        );

        $class = ClassModel::query()
            ->with([
                'courses.course',
                'students'
            ]);

        return parent::store($data, $storing);
    }
}
