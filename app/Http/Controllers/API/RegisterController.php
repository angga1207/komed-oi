<?php

namespace App\Http\Controllers\API;

use App\HeaderChecker;
use App\JsonReturner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use JsonReturner, HeaderChecker;

    function register(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $validated = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
        ], [], [
            'fullname' => 'Nama Lengkap',
            'nik' => 'N.I.K',
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password'
        ]);

        if ($validated->fails()) {
            $messages = [];
            foreach ($validated->errors()->getMessages() as $key => $value) {
                $messages[$key] = $value[0];
            }
            return $this->validationErrorResponse($messages);
            return $this->validationErrorResponse($validated->errors()->first());
        }

        DB::beginTransaction();
        try {
            $now = now();

            $firstName = explode(' ', $request->fullname)[0];
            $lastName = explode(' ', $request->fullname)[1] ?? '';


            $userCheck = DB::table('users')->where('username', $request->nik)->first();
            if (!$userCheck) {
                $userID = DB::table('users')->insertGetId([
                    'fullname' => $request->fullname,
                    'first_name' => $firstName ?? $request->fullname,
                    'last_name' => $lastName ?? '',
                    'username' => $request->nik,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'role_id' => 4,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $user = User::find($userID);

                // formula unique_id = PERS.xxxx.xxxxx
                $unique_id = 'PERS.' . date('my') . '.' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

                $pers = DB::table('pers_profile')->insert([
                    'user_id' => $user->id,
                    'unique_id' => $unique_id,
                    'nik' => $request->nik,
                    'verified_status' => null,
                    'verification_deadline' => null,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $pers = DB::table('pers_profile')->where('user_id', $user->id)->first();

                // make Token
                $token = $user->createToken('authToken')->plainTextToken;

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

                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => $user->id,
                    'action' => 'register',
                    'model' => 'akun',
                    'endpoint' => 'api/register',
                    'payload' => json_encode($request->all()),
                    'message' => 'Melakukan registrasi'
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::commit();
                return $this->successResponse($returnData, 'Registrasi berhasil');
            } else {
                return $this->validationErrorResponse(['nik' => 'N.I.K sudah terdaftar']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage() . ' - ' . $e->getLine());
        }
    }
}
