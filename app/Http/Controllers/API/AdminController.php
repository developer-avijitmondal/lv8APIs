<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Usersession;

class AdminController extends BaseController
{
    public function getCommonUsers(){
        $users = User::where('is_admin',null)
        ->get();
        $success['users'] = $users;
        return $this->sendResponse($success, 'user list');
    }
}
