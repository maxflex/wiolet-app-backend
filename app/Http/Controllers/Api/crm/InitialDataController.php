<?php

namespace App\Http\Controllers\Api\crm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Загрузка изначальных данных,
 * необходимых для работы приложения
 */
class InitialDataController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
            ],
            'user' => auth()->user()
        ]);
    }
}
