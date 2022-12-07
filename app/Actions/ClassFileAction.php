<?php

namespace App\Actions;

use App\Http\Resources\ClassFileResource;
use App\Models\AdminModel;
use App\Models\ClassFileModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Helpers;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use JetBrains\PhpStorm\ArrayShape;

class ClassFileAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassFileModel::class)
            ->setResource(ClassFileResource::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'class_course_id' => ['nullable', 'string', 'max:20'],
                    'class_id' => ['nullable', 'string', 'max:20'],
                    'title' => ['required', 'string', 'max:20'],
                    'file' => ['required', 'file', 'mimes:png,jpg,jpeg,gif,svg,zip,rar,7zip,pdf', 'max:20000']
                ]
            ])
            ->setCasts([
                'file' => ['file']
            ]);

        parent::__construct();
    }

    /**
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     */
    public function storeByAdminByRequest (callable $storing = null): mixed
    {
        return $this->store(
            array_merge(
                $this->getDataFromRequest(),
                $this->getAuthorByRequest(),
                [
                    'size' => Helpers::humanFilesize($this->originalRequestData['file']->getSize())
                ]
            ),
            $storing
        );
    }

    /**
     * @return array
     * @throws CustomException
     */
    #[ArrayShape(['author_type' => "string", 'author_id' => "mixed"])]
    public function getAuthorByRequest (): array
    {
        $user = $this->getUserFromRequest();

        return [
            'author_type' => get_class($user),
            'author_id' => $user->id
        ];
    }
}
