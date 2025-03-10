<?php

namespace App\Http\Controllers\API;

use App\HeaderChecker;
use App\JsonReturner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
                        'message' => 'Login ke aplikasi mobile',
                        'created_at' => now()
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

    function logout(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $user = auth()->user();
        // make log start
        $log = [
            'id' => uniqid(),
            'user_id' => $user->id,
            'action' => 'logout',
            'model' => 'users',
            'endpoint' => 'api/logout',
            'payload' => json_encode($request->all()),
            'message' => 'Keluar dari aplikasi mobile',
            'created_at' => now()
        ];
        DB::table('user_logs')->insert($log);
        // make log end

        $user->tokens()->delete();
        return $this->successResponse('Berhasil logout');
    }

    function updateFcmToken(Request $request)
    {
        // if ($this->checkHeader($request) == false) {
        //     return $this->unauthorizedResponse('Unauthorized');
        // }

        $validated = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'type' => 'required|string|in:web,mobile',
            'device_id' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return $this->validationErrorResponse($validated->errors()->first());
        }

        $user = auth()->user();
        $oldFcmToken = DB::table('firebase_tokens')
            ->where('user_id', $user->id)
            ->where('type', $request->type)
            ->first();
        if ($oldFcmToken) {
            DB::table('firebase_tokens')
                ->where('user_id', $user->id)
                ->where('type', $request->type)
                ->update([
                    'token' => $request->fcm_token,
                    'device_id' => $request->device_id
                ]);
        } else {
            DB::table('firebase_tokens')->insert([
                'id' => uniqid(),
                'user_id' => $user->id,
                'type' => $request->type,
                'token' => $request->fcm_token,
                'device_id' => $request->device_id
            ]);
        }

        return $this->successResponse('Berhasil update FCM Token');
    }


    function serverCheck(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }
        $returns = [];
        $bearer =  $request->bearerToken();
        $returnData = null;
        $user = null;
        if ($bearer) {
            $bearerId = str()->of($bearer)->explode('|')[0];
            $token = DB::table('personal_access_tokens')
                ->where('id', $bearerId)
                ->first();
            if ($token) {
                $user = User::find($token->tokenable_id);
                if ($user) {
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
                }
            }
        }

        $returns['token'] = $bearer;
        $returns['user'] = $returnData;
        return $this->successResponse($returns, 'Server is running');
    }
}
