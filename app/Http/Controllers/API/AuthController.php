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
                    // delete old token
                    $user->tokens()->delete();
                    $token = $user->createToken('authToken')->plainTextToken;

                    // make log start
                    $log = [
                        'id' => uniqid(),
                        'user_id' => $user->id,
                        'action' => 'login',
                        'model' => 'users',
                        'endpoint' => 'api/login',
                        'payload' => json_encode($request->all()),
                        'message' => 'Login ke aplikasi mobile'
                    ];
                    DB::table('user_logs')->insert($log);
                    // make log end

                    DB::commit();

                    $arrPers = DB::table('pers_profile')->where('user_id', $user->id)->get();
                    $pers = [];
                    foreach ($arrPers as $persProfile) {
                        $pers[] = [
                            'id' => $persProfile->id,
                            'unique_id' => $persProfile->unique_id,
                            'nama_perusahaan' => $persProfile->nama_perusahaan,
                            'nama_media' => $persProfile->nama_media,
                            'alias' => $persProfile->alias,
                            'jenis_media' => $persProfile->jenis_media,
                        ];
                    }

                    $returnData = [
                        'fullname' => $user->fullname,
                        'email' => $user->email,
                        'nik' => $user->username,
                        'username' => $user->username,
                        'photo' => asset($user->photo),
                        'role_id' => $user->role_id,
                        'role_name' => DB::table('roles')->where('id', $user->role_id)->value('name'),
                        'token' => $token,
                        'jumlah_media' => count($arrPers),
                        'media' => $pers
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
