<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
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
                ],
                'updateByAdmin' => [
                    'class_course_id' => ['nullable', 'string', 'max:20'],
                    'class_id' => ['nullable', 'string', 'max:20'],
                    'title' => ['string', 'max:20'],
                    'file' => ['file', 'mimes:png,jpg,jpeg,gif,svg,zip,rar,7zip,pdf', 'max:20000']
                ],
                'getQuery' => [
                    'class_id' => ['string', 'max:20'],
                    'class_course_id' => ['string', 'max:20'],
                    'educational_year' => ['string', 'max:50'],
                    'search' => ['string', 'max:150'],
                    'added_by_admin' => ['boolean'],
                    'added_by_teacher' => ['boolean'],
                    'added_by_student' => ['boolean'],
                ]
            ])
            ->setCasts([
                'file' => ['file']
            ])
            ->setQueryToEloquentClosures([
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where(function($q) use ($query){
                        $q
                            ->where('class_id', $query['class_id'])
                            ->orWhereHas('classCourse', function($q) use($query){
                                $q->where('class_id', $query['class_id']);
                            });
                    });
                },
                'class_course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('class_course_id', $query['class_course_id']);
                },
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                },
                'added_by_admin' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('author_type', $query['added_by_admin'] ? '=' : '!=', 'App\\Models\\AdminModel');
                },
                'added_by_teacher' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('author_type', $query['added_by_teacher'] ? '=' : '!=', 'App\\Models\\TeacherModel');
                },
                'added_by_student' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('author_type', $query['added_by_student'] ? '=' : '!=', 'App\\Models\\StudentModel');
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year'] != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                }
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
        $data['educational_year'] = $data['educational_year'] ?? PardisanHelper::getCurrentEducationalYear();

        return parent::store($data, $storing);
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
                    'size' => Helpers::humanFilesize($this->originalRequestData['file']->getSize()),
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

    /**
     * @param callable|null $deleting
     * @return mixed
     */
    public function delete(callable $deleting = null): mixed
    {
        foreach ($this->getEloquent()->get() AS $classFile)
        {
            is_file($classFile->file) && unlink($classFile->file);
        }

        return parent::delete($deleting);
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        foreach ($this->getEloquent()->get() AS $classFile)
        {
            isset($updateData['file']) && is_file($classFile->file) && unlink($classFile->file);
        }

        return parent::update($updateData, $updating);
    }

    /**
     * @param callable|null $updating
     * @return bool|int
     * @throws CustomException
     */
    public function updateByRequest(callable $updating = null): bool|int
    {
        $updateData = array_merge(
            $this->getDataFromRequest(),
            $this->getAuthorByRequest()
        );

        if (isset($updateData['file']))
        {
            $updateData['size'] = Helpers::humanFilesize($this->originalRequestData['file']->getSize());
        }

        return $this->update($updateData, $updating);
    }
}