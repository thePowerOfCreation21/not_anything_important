<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Imports\StudentsImport2;
use App\Imports\StudentsImport3;
use App\Imports\TeachersImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importStudents(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xlx,xls,xlsx', 'max:2048'],
            'number' => ['required', 'in:1,2,3']
        ]);

        switch ($data['number'])
        {
            case '1':
                Excel::import(new StudentsImport(), $data['file']);
                break;
            case '2':
                Excel::import(new StudentsImport2(), $data['file']);
                break;
            case '3':
                Excel::import(new StudentsImport3(), $data['file']);
                break;
        }

        return response()->json([
            'message' => 'imported successfully',
            'data' => true
        ]);
    }

    public function importTeachers(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xlx,xls,xlsx', 'max:2048'],
            'number' => ['required', 'in:1']
        ]);

        if ($data['number'] == '1')
            Excel::import(new TeachersImport(), $data['file']);

        return response()->json([
            'message' => 'imported successfully',
            'data' => true
        ]);
    }
}
