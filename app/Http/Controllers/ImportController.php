<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importStudents(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xlx,xls,xlsx', 'max:2048']
        ]);

        Excel::import(new StudentsImport(), $data['file']);

        return response()->json([
            'message' => 'imported successfully',
            'data' => true
        ]);
    }
}
