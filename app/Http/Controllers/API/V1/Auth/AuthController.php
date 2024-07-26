<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Constants\ErrorCodes;
use App\Http\Controllers\API\V1\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Rules\MatchNewPassword;
use App\Http\Rules\MatchOldPassword;
use App\Models\Device as DeviceModel;
use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ],
                [
                    'name.required' => 'The name field is required.',
                    'email.required' => 'The email field is required.',
                    'email.unique' => 'The email has already been taken.',
                    'password.required' => 'The password field is required.',
                    'password.confirmed' => 'The password confirmation does not match.',
                ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
            }

            // Create user
            $user = UserModel::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return ResponseBuilder::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 'Registration successful.');

        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ],
                [
                    'name.required' => 'The name field is required.',
                    'email.required' => 'The email field is required.',
                    'email.unique' => 'The email has already been taken.',
                    'password.required' => 'The password field is required.',
                ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return ResponseBuilder::error('Invalid login!');
            }

            $user = UserModel::where('email', $request['email'])->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            DeviceModel::updateOrCreate([
                'device_token' => $request->header('X-Device-ID'),
                'user_id' => $user->id,
            ]);

            DB::commit();

            return ResponseBuilder::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 'You are logged in successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            return $e->getMessage();
        }
    }

    public function logout(Request $request)
    {
        Auth::user()->device()->delete();
        $request->user()->currentAccessToken()->delete();

        return ResponseBuilder::success(null, ('You are logout in successfully!'), 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'current_password' => ['required', new MatchOldPassword()],
            'password' => ['required', 'confirmed', 'min:8', new MatchNewPassword()],
        ], [
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422, ErrorCodes::VALIDATION);
        }

        $updatePassword = Auth::guard('api')->user()->update([
            'password' => bcrypt($request->password)
        ]);

        if ($updatePassword) {
            return ResponseBuilder::success(['access_token' => createToken()], 'update successfully !', 200);
        }
    }
}
