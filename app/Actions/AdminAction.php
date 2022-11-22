<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\AdminResource;
use App\Models\AdminModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Morilog\Jalali\CalendarUtils;

class AdminAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(AdminModel::class)
            ->setResource(AdminResource::class)
            ->setValidationRules([
                'store' => [
                    'user_name' => ['required', 'string', 'max:100', 'unique:admins'],
                    'password' => ['required', 'string', 'max:100'],
                ],
                'login' => [
                    'user_name' => ['required', 'string', 'max:100'],
                    'password' => ['required', 'string', 'max:100'],
                ]
            ]);

        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $data['password'] = Hash::make($data['password']);

        return parent::store($data, $storing);
    }

    public function login (array $data): NewAccessToken
    {
        $admin = $this->model::where('user_name', $data['user_name'])->first();

        if (! empty($admin))
        {
            if (Hash::check($data['password'], $admin->password))
            {
                return $admin->createToken('auth_token');
            }
        }

        throw new CustomException('name or password is wrong', 2, 400);
    }

    public function loginByRequest (): NewAccessToken
    {
        return $this->login(
            $this->getDataFromRequest()
        );
    }
}
