<?php

namespace App\Http\Controllers\API;

use App\HeaderChecker;
use App\JsonReturner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use JsonReturner, HeaderChecker;

    function login(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $validated = Validator::make($request->all(), [
            'nik' => 'required|numeric|digits:16|exists:users,username',
            'password' => 'required|string'
        ], [
            'nik.exists' => 'N.I.K tidak terdaftar'
        ], [
            'nik' => 'N.I.K',
            'password' => 'Password'
        ]);

        if ($validated->fails()) {
            return $this->validationErrorResponse($validated->errors()->first());
        }

        DB::beginTransaction();
        try {
            $user = User::where('username', $request->nik)->first();
            if ($user) {
                if (password_verify($request->password, $user->password)) {
                    $token = $user->createToken('authToken')->plainTextToken;

                    // make log start
                    $log = [
                        'id' => uniqid(),
                        'user_id' => $user->id,
                        'action' => 'login',
                        'model' => 'users',
                        'endpoint' => 'api/login',
                        'payload' => json_encode($request->all()),
                        'message' => 'User login'
                    ];
                    DB::table('user_logs')->insert($log);
                    // make log end

                    DB::commit();

                    $pers = DB::table('pers_profile')->where('user_id', $user->id)->first();
                    $returnData = [
                        'fullname' => $user->fullname,
                        'email' => $user->email,
                        'nik' => $user->username,
                        'username' => $user->username,
                        'role_id' => $user->role_id,
                        'role_name' => DB::table('roles')->where('id', $user->role_id)->value('name'),
                        'pers_unique_id' => $pers->unique_id,
                        'token' => $token
                    ];

                    return $this->successResponse($returnData);
                } else {
                    return $this->validationErrorResponse(['password' => 'Password Anda salah']);
                }
            } else {
                return $this->errorResponse('N.I.K tidak terdaftar');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
