<?php

namespace App\Actions;

use App\Http\Resources\GeneralStatisticResource;
use App\Models\GeneralStatisticModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class GeneralStatisticAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(GeneralStatisticModel::class)
            ->setResource(GeneralStatisticResource::class);

        parent::__construct();
    }

    /**
     * @param string $label
     * @param string $educationalYear
     * @return mixed
     */
    public function getFirstByLabelAndEducationalYearOrCreate(string $label, string $educationalYear): mixed
    {
        $generalStatistic = GeneralStatisticModel::where('label', $label)->where('educational_year', $educationalYear)->first();

        return empty($generalStatistic) ? GeneralStatisticModel::create([
            'label' => $label,
            'educational_year' => $educationalYear
        ]) : $generalStatistic;
    }
}
