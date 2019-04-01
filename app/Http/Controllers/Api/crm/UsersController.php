<?php

namespace App\Http\Controllers\Api\crm;

use Illuminate\Http\Request;
use App\Http\Controllers\CrmController as Controller;
use App\Models\User\User;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        return $this->showBy($request, $query);
    }
}
