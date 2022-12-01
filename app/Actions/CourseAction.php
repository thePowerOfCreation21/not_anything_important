<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\AdminResource;
use App\Http\Resources\CourseResource;
use App\Models\AdminModel;
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
                    'ratio' => ['required', 'integer', 'min:1', 'max:3']
                ],
                'update' => [
                    'title' => ['string', 'max:250'],
                    'ratio' => ['integer', 'min:1', 'max:3']
                ],
                'get_query' => [
                    'search' => 'string|max:250'
                ]
            ])
            ->setQueryToEloquentClosures([
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                }
            ]);
        parent::__construct();
    }
}
