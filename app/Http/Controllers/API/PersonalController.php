<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\JsonReturner;
use App\HeaderChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    use JsonReturner, HeaderChecker;

    function getMe(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $user = User::find(auth()->id());
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
                'whatsapp' => $user->whatsapp,
                'photo' => asset($user->photo),
                'role_id' => $user->role_id,
                'role_name' => DB::table('roles')->where('id', $user->role_id)->value('name'),
                'jumlah_media' => count($arrPers),
                'media' => $pers
            ];

            return $this->successResponse(null, $returnData);
        } else {
            return $this->notFoundResponse('Data not found');
        }
    }

    function updateProfile(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $validated = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'nik' => 'required|string|min:5|max:20|unique:users,username,' . auth()->id(),
            'whatsapp' => 'required|numeric|digits_between:10,13',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
        ], [
            'whatsapp.digits_between' => 'Nomor WhatsApp harus diantara 10 sampai 13 digit'
        ], [
            'fullname' => 'Nama Lengkap',
            'nik' => 'N.I.K',
            'whatsapp' => 'Nomor WhatsApp',
            'email' => 'Email',
            'photo' => 'Foto Profil'
        ]);

        if ($validated->fails()) {
            return $this->validationErrorResponse($validated->errors()->first());
        }

        DB::beginTransaction();
        try {
            $firstName = explode(' ', $request->fullname)[0];
            $lastName = explode(' ', $request->fullname)[1] ?? '';
            $user = User::find(auth()->id());
            $user->fullname = $request->fullname;
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->username = $request->nik;
            $user->whatsapp = $request->whatsapp;
            $user->email = $request->email;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $request->photo->storeAs('images/users/', $photoName, 'public');
                $user->photo = '/storage/images/users/' . $photoName;
            }
            $user->save();
            $user->photo = asset($user->photo);


            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => $user->id,
                'action' => 'update',
                'model' => 'users',
                'endpoint' => 'api/profile/update',
                'payload' => json_encode($request->all()),
                'message' => 'Memperbarui profil',
                'created_at' => now()
            ];
            DB::table('user_logs')->insert($log);
            // make log end

            DB::commit();
            return $this->successResponse($user, 'Profil berhasil diperbarui',);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage() . ' at line ' . $e->getLine());
        }
    }

    function updatePassword(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $validated = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|same:new_password',
            'logout_all' => 'nullable|boolean'
        ]);

        if ($validated->fails()) {
            return $this->validationErrorResponse($validated->errors()->first());
        }

        $user = User::find(auth()->id());
        if (!password_verify($request->old_password, $user->password)) {
            return $this->validationErrorResponse('Password lama tidak sesuai');
        }

        DB::beginTransaction();
        try {
            $user->password = bcrypt($request->new_password);
            $user->save();

            if ($request->logout_all) {
                // logout all devices except current device
                $user->tokens->each(function ($token) {
                    if ($token->id != auth()->user()->currentAccessToken()->id) {
                        $token->delete();
                    }
                });
            }

            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => $user->id,
                'action' => 'update',
                'model' => 'users',
                'endpoint' => 'api/profile/update-password',
                'payload' => json_encode($request->all()),
                'message' => 'Memperbarui Password',
                'created_at' => now()
            ];
            DB::table('user_logs')->insert($log);
            // make log end

            DB::commit();
            return $this->successResponse('Password updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage() . ' at line ' . $e->getLine());
        }
    }

    function getLogs(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $returns = [];
        $arrLogs = DB::table('user_logs')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($arrLogs as $log) {
            $returns[] = [
                'id' => $log->id,
                'action' => $log->action,
                'model' => $log->model,
                'endpoint' => $log->endpoint,
                'payload' => json_decode($log->payload),
                'message' => $log->message,
                'created_at' => $log->created_at
            ];
        }

        return $this->successResponse(null, $returns);
    }

    function getNotifcations(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $returns = [];
        $arrNotifs = DB::table('notifications')
            ->where('notifiable_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($arrNotifs as $notif) {
            $rawData = collect(json_decode($notif->data));
            $title = null;
            if (isset($rawData['message']) && $rawData['message'] == 'Permintaan verifikasi media pers telah diverifikasi. Silahkan cek media pers anda.') {
                $title = 'Registrasi Terverifikasi';
            } else {
                $title = $rawData['title'] ?? null;
            }

            // return $rawData;
            $mediaId = $rawData['media']->id ?? null;
            $orderData = [];
            $orderDate = null;
            $notifType = 'registration';

            if (isset($rawData['order'])) {
                $notifType = 'media_order';
                $orderDataIds = collect($rawData['order'])->pluck('id');
                $orderData = [];
                $orderDatas = DB::table('orders')->whereIn('id', $orderDataIds)
                    ->get();
                foreach ($orderDatas as $dt) {
                    $agenda = DB::table('agendas')->where('id', $dt->agenda_id)->first();
                    $dataAgenda = collect(json_decode($agenda->data));
                    $orderData[] = [
                        'order_id' => $dt->id,
                        'agenda_id' => $dt->agenda_id,
                        'nama_acara' => $dataAgenda['nama_acara'] ?? $agenda->nama_acara,
                        'tanggal_pelaksanaan' => $dt->tanggal_pelaksanaan,
                        'tanggal_pelaksanaan_akhir' => $dt->tanggal_pelaksanaan_akhir,
                        'waktu_pelaksanaan' => $dt->waktu_pelaksanaan,
                        'leading_sector' => $dt->leading_sector,
                        'status' => $dt->status,
                        // 'agenda' => $dataAgenda,
                        'created_at' => $dt->created_at,
                        'updated_at' => $dt->updated_at,
                    ];
                }
                $orderDate = collect($rawData['order'])[0]->created_at ?? null;
                // return $orderDate;
            }
            $returns[] = [
                'id' => $notif->id,
                'type' => $notif->type,
                'title' => $title ?? null,
                'message' => $rawData['message'] ?? null,
                'notif_type' => $notifType,
                'media_id' => $mediaId ?? null,
                'data_order' => $orderData ?? [],
                'read_at' => $notif->read_at,
                'created_at' => $notif->created_at
            ];
        }

        return $this->successResponse(null, $returns);
    }

    function readedNotifcations(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $validated = Validator::make($request->all(), [
            'id' => 'required|uuid|exists:notifications,id',
        ], [], [
            'id' => 'ID Notification',
        ]);

        if ($validated->fails()) {
            return $this->validationErrorResponse($validated->errors()->first());
        }

        DB::beginTransaction();
        try {
            DB::table('notifications')->where('id', $request->id)
                ->update([
                    'read_at' => now(),
                ]);

            DB::commit();
            return $this->successResponse(null, 'Notifikasi telah dibaca');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage() . ' at line ' . $e->getLine());
        }
    }
}
