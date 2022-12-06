<?php

namespace App\Http\Controllers;

use App\Actions\ClassScoreStudentAction;
use Illuminate\Http\Request;

class ClassScoreStudentController extends Controller
{
    public function get (Request $request)
    {
        return response()->json(
            (new ClassScoreStudentAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['attendance.classCourse.classModel'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
