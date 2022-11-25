<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\CourseResource;
use App\Models\CourseModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Morilog\Jalali\CalendarUtils;

class CourseAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(CourseModel::class)
            ->setResource(CourseResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:250'],
                ],
                'update' => [
                    'title' => ['string', 'max:250'],
                ],
                'get_query' => [
                    'search' => 'string|max:250'
                ]
            ]);
        parent::__construct();
    }
}
