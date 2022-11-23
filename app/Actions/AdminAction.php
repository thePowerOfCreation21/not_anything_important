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
                    'user_name' => ['required', 'string', 'max:25', 'unique:admins'],
                    'password' => ['required', 'string', 'max:150'],
                    'privileges' => ['array','max:'.count(AdminModel::$privileges_list)]
                ],
                'update' => [
                    'user_name' => ['string', 'max:25', 'unique:admins'],
                    'password' => ['string', 'max:150'],
                    'privileges' => ['array|max:'.count(AdminModel::$privileges_list) ]
                ],
                'login' => [
                    'user_name' => ['required', 'string', 'max:25'],
                    'password' => ['required', 'string', 'max:150'],
                ]
            ]);
        foreach (AdminModel::$privileges_list as $privilege)
        {
            $this->validationRules['store']["privileges.$privilege"] = 'boolean';
            $this->validationRules['update']["privileges.$privilege"] = 'boolean';
        }
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
        $data['privileges'] = AdminModel::fix_privileges(
            (object) (!isset($data['privileges']) ? [] : $data['privileges'])
        );

        return parent::store($data, $storing);
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        if (is_null($updating))
        {
            $updating = function ($eloquent, &$update_data)
            {
                $admin = $this->getFirstByEloquent($eloquent);

                if ($admin->is_primary)
                {
                    throw new CustomException('primary accounts can not be edited', 11, 400);
                }

                if (
                    isset($update_data['user_name'])
                    &&
                    $this->model::where('user_name', $update_data['user_name'])
                        ->where('id', '!=', $admin->id)
                        ->count() > 0
                )
                {
                    throw new CustomException('this user_name is already taken', 6, 400);
                }

                if (isset($update_data['privileges']))
                {
                    $update_data['privileges'] = AdminModel::fix_privileges(
                        (object) $update_data['privileges'],
                        AdminModel::fix_privileges(
                            (object) $admin->privileges
                        )
                    );
                    $update_data['privileges'] = (array) $update_data['privileges'];
                }

                isset($update_data['password']) && $update_data['password'] = Hash::make($update_data['password']);
            };
        }
        return parent::update($updateData, $updating);
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
