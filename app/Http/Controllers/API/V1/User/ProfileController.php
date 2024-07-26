<?php

namespace App\Http\Controllers\API\V1\User;

use App\Constants\ErrorCodes;
use App\Http\Controllers\API\V1\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProfileController extends Controller
{

    public function profile()
    {
        $ProfileResource = Auth::user();

        return ResponseBuilder::success(['profile' => new ProfileResource($ProfileResource)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ],
            [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.unique' => 'The email has already been taken.',
                'password.required' => 'The password field is required.',
            ]
        );

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
        }

        $requests = $validator->validated();
        Auth::user()->update($requests);

        return ResponseBuilder::success(null, __('messages.update'), 200);
    }
}
