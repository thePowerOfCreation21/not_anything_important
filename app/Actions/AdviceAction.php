<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Models\AdviceDateModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\AdviceModel;
use App\Http\Resources\AdviceResource;
use Throwable;

class AdviceAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(AdviceModel::class)
            ->setResource(AdviceResource::class)
            ->setValidationRules([
                'storeByStudent' => [
                    'advice_hour_id' => ['integer', 'exists:advice_hours,id'],
                    'advice_date_id' => ['integer'],
                ],
                'update' => [
                    'status' => ['string', 'in:pending,accepted,rejected', 'max:255']
                ],
                'getQuery' => [
                    'student_id' => ['integer'],
                    'search' => ['string', 'max:255'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                    'educational_year' => ['string', 'max:50']
                ],
                'getByStudent' => [
                    'search' => ['string', 'max:255'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('student_id', $query['student_id']);
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('adviceDate', function($q) use ($query){
                        $q->whereDate('advice_dates.date', '>=', $query['from_date']);
                    });
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('adviceDate', function($q) use ($query){
                        $q->whereDate('advice_dates.date', '<=', $query['to_date']);
                    });
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent
                        ->where(function ($q) use($query){
                            $q
                                ->where('status', 'LIKE', "%{$query['search']}%")
                                ->orWhereHas('student', function ($q) use($query){
                                    $q->where('full_name', 'LIKE', "%{$query['search']}%");
                                });
                        });
                }
            ]);
        parent::__construct();
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        if(isset($updateData['date']))
        {
            $updateData['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($updateData['date']);
        }

        return parent::update($updateData, $updating);
    }

    /**
     * @return mixed
     * @throws CustomException|Throwable
     */
    public function storeByStudentByRequest (): mixed
    {
        return $this->store(
            array_merge($this->getDataFromRequest(), ['student_id' => $this->request->user()->id])
        );
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws Throwable
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $adviceDate = AdviceDateModel::query()
            ->where('id', $data['advice_date_id'])
            ->firstOrFail();

        throw_if(strtotime($adviceDate->date) < time(), CustomException::class, 'could not store advice for passed dates', '986756', 400);

        $data['educational_year'] = $adviceDate->educational_year;

        throw_if(
            AdviceModel::query()
                ->where('student_id', $data['student_id'])
                ->where('advice_date_id', $data['advice_date_id'])
                // ->where('hour_id', $data['hour_id'])
                ->exists(),
            CustomException::class, 'this student already has advice in this date', '37102', 400
        );

        return parent::store($data, $storing);
    }
}
