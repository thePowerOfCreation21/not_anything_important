<?php

namespace App\Actions;

use App\Models\FoodDateFoodPivotModel;
use App\Models\FoodModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\FoodDateModel;
use App\Http\Resources\FoodDateResource;
use Throwable;

class FoodDateAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(FoodDateModel::class)
            ->setResource(FoodDateResource::class)
            ->setValidationRules([
                'store' => [
                    'date' => ['required', 'string'],
                    'foods' => ['required', 'array', 'max:100'],
                    'foods.*' => ['integer']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ]);
        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws Throwable
     */
    public function store(array $data, callable $storing = null): mixed
    {
        throw_if(strtotime($data['date']) < time(), CustomException::class, 'you can not add passed dates', '403878', '400');

        $foodDate = FoodDateModel::query()->firstOrCreate([
            'date' => $data['date']
        ]);

        $foods = FoodModel::query()->whereIn('id', $data['foods'])->whereDoesntHave('foodDates', function ($q) use ($foodDate) {
            $q->where('food_dates.id', $foodDate->id);
        })->get();

        $foodDateFoodPivots = [];

        foreach ($foods as $food) {
            $foodDateFoodPivots[] = [
                'food_date_id' => $foodDate->id,
                'food_id' => $food->id
            ];
        }

        FoodDateFoodPivotModel::query()->insert($foodDateFoodPivots);

        return $foodDate;
    }
}
