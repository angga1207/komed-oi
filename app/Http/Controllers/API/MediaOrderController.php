<?php

namespace App\Http\Controllers\API;

use App\JsonReturner;
use App\HeaderChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class MediaOrderController extends Controller
{
    use JsonReturner, HeaderChecker;

    function getMediaOrder(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $validated = Validator::make($request->all(), [
            'date' => 'nullable|date',
        ], [], [
            'date' => 'Tanggal',
        ]);

        if ($validated->fails()) {
            return $this->validationErrorResponse($validated->errors()->first());
        }

        $now = now();
        $returns = [];
        $mediaPers = DB::table('pers_profile')
            ->where('user_id', auth()->id())->first();
        if ($request->date) {
            $datas = DB::table('orders')
                ->where('media_id', $mediaPers->id)
                ->whereDate('tanggal_pelaksanaan', $request->date)
                ->orderBy('tanggal_pelaksanaan')
                ->get();
        } else {
            $datas = DB::table('orders')
                ->where('media_id', $mediaPers->id)
                ->whereMonth('tanggal_pelaksanaan', $now)
                ->whereDate('tanggal_pelaksanaan', '>=', $now)
                ->orderBy('tanggal_pelaksanaan')
                ->get();
        }

        foreach ($datas as $order) {
            $agenda = DB::table('agendas')
                ->where('id', $order->agenda_id)
                ->first();
            $statusLogs = DB::table('log_order_status')
                ->where('media_id', $mediaPers->id)
                ->where('order_id', $order->id)
                ->latest()
                ->get();
            $returns[] = [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'media_id' => $mediaPers->id,
                'nama_media' => $mediaPers->nama_media,
                'nama_perusahaan' => $mediaPers->nama_perusahaan,
                'nama_acara' => $order->nama_acara,
                'lokasi' => $order->lokasi,
                'tanggal_pelaksanaan' => $order->tanggal_pelaksanaan,
                'tanggal_pelaksanaan_akhir' => $order->tanggal_pelaksanaan_akhir,
                'waktu_pelaksanaan' => $order->waktu_pelaksanaan,
                'leading_sector' => $order->leading_sector,
                // 'data_agenda' => $agenda->data ? json_decode($agenda->data) : [],
                'status' => $order->status,
                'created_at' => $order->created_at,
                'deadline' => $order->deadline ? Carbon::parse($order->deadline)->isoFormat('Y-MM-DD HH:mm:ss') : null,
                'updated_at' => $order->updated_at,
                'status_logs' => $statusLogs ?? [],
            ];
        }

        return $this->successResponse($returns, 'Profil berhasil diperbarui',);
    }

    function singleMediaOrder($id, Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $return = [];
        $data = DB::table('orders')
            ->where('id', $id)
            ->first();
        if (!$data) {
            return $this->notFoundResponse('Media Order tidak dapat kami temukan');
        }

        $agenda = DB::table('agendas')->where('id', $data->agenda_id)->first();
        if (!$agenda) {
            return $this->notFoundResponse('Agenda tidak dapat kami temukan');
        }
        $dataAgenda = collect(json_decode($agenda->data));

        $evidences = [];
        $evidences = DB::table('order_evidences')
            ->where('order_id', $data->id)
            ->oldest('created_at')
            ->get();

        $logs = [];
        $logs = DB::table('log_order_status')
            ->where('order_id', $data->id)
            ->latest('created_at')
            ->select(['id', 'status', 'note', 'created_at', 'updated_at'])
            ->get();

        $return['id'] = $data->id;
        $return['order_code'] = $data->order_code;
        $return['nama_acara'] = $data->nama_acara ?? null;
        $return['lokasi'] = $data->lokasi ?? null;
        $return['tanggal_pelaksanaan'] = $data->tanggal_pelaksanaan;
        $return['tanggal_pelaksanaan_akhir'] = $data->tanggal_pelaksanaan_akhir;
        $return['waktu_pelaksanaan'] = $data->waktu_pelaksanaan;
        $return['leading_sector'] = $data->leading_sector;
        $return['status'] = $data->status;
        $return['created_at'] = $data->created_at;
        $return['deadline'] = $data->deadline ? Carbon::parse($data->deadline)->isoFormat('Y-MM-DD HH:mm:ss') : null;
        $return['evidences'] = $evidences ?? [];
        $return['logs'] = $logs ?? [];

        return $this->successResponse($return, 'Media Order berhasil diambil');
    }

    function uploadEvidences($id, Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $now = now();
        $data = DB::table('orders')
            ->where('id', $id)
            ->first();
        if (!$data) {
            return $this->notFoundResponse('Media Order tidak dapat kami temukan');
        }
        if (in_array($data->status, ['sent', 'rejected']) == false) {
            return $this->validationErrorResponse('Media Order tidak dalam status yang benar');
        }

        $agenda = DB::table('agendas')->where('id', $data->agenda_id)->first();
        if (!$agenda) {
            return $this->notFoundResponse('Agenda tidak dapat kami temukan');
        }

        $validated = Validator::make($request->all(), [
            'type' => 'required|string',
        ], [], [
            'type' => 'Jenis Evidence',
        ]);

        if ($validated->fails()) {
            // return $this->validationErrorResponse($validated->errors());
            return $this->validationErrorResponse($validated->errors()->first());
        }

        if ($request->type == 'link') {
            $validated2 = Validator::make($request->all(), [
                'url' => 'required|url',
            ], [], [
                'url' => 'Link Evidence',
            ]);

            if ($validated2->fails()) {
                // return $this->validationErrorResponse($validated2->errors());
                return $this->validationErrorResponse($validated2->errors()->first());
            }

            DB::beginTransaction();
            try {
                DB::table('order_evidences')->insert([
                    'order_id' => $id,
                    'media_id' => $data->media_id,
                    'agenda_id' => $data->agenda_id,
                    'type' => 'link',
                    'url' => $request->url,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'upload-evidence',
                    'model' => 'media_order',
                    'endpoint' => 'media-order',
                    'payload' => json_encode(request()->all()),
                    'message' => 'Menunggah Bukti Baru di Media Order ' . $data->order_code,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::commit();
                return $this->successResponse(null, 'Evidence berhasil diupload');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage());
            }
        } elseif ($request->type == 'image') {
            // return $request->all();
            $validated2 = Validator::make($request->all(), [
                'evidences' => 'required|array',
                'evidences.*.file' => 'required|image|mimes:png,jpeg,jpg|max:10000',
                'evidences.*.description' => 'nullable|string|max:5000',
            ], [], [
                'evidences' => 'File Evidence',
                'evidences.*.file' => 'File Evidence',
                'evidences.*.description' => 'Deskripsi Evidence',
            ]);

            if ($validated2->fails()) {
                // return $this->validationErrorResponse($validated2->errors());
                return $this->validationErrorResponse($validated2->errors()->first());
            }

            DB::beginTransaction();
            try {
                foreach ($request->evidences as $key => $input) {
                    // return $input;
                    $file = $input['file'];
                    if ($file) {
                        $filename = $data->order_code . '-' . time() . $key . '.' . $file->extension();
                        $file->storeAs('public/evidences/' . $data->id, $filename, 'public');
                        $path = 'storage/public/evidences/' . $data->id . '/' . $filename;

                        DB::table('order_evidences')->insert([
                            'order_id' => $id,
                            'media_id' => $data->media_id,
                            'agenda_id' => $data->agenda_id,
                            'type' => 'image',
                            'url' => asset($path),
                            'description' => $input['description'] ?? null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }

                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'upload-evidence',
                    'model' => 'media_order',
                    'endpoint' => 'media-order',
                    'payload' => json_encode(request()->all()),
                    'message' => 'Menunggah Bukti Baru di Media Order ' . $data->order_code,
                    'created_at' => now()
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::commit();
                return $this->successResponse(null, 'Evidence berhasil diupload');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage());
            }
        }
    }

    function deleteEvidence($id, Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $now = now();
        $data = DB::table('order_evidences')
            ->where('id', $id)
            ->first();
        if (!$data) {
            return $this->notFoundResponse('Evidence tidak dapat kami temukan');
        }

        $mediaOrder = DB::table('orders')
            ->where('id', $data->order_id)
            ->first();

        DB::beginTransaction();
        try {
            DB::table('order_evidences')
                ->where('id', $id)
                ->delete();

            // make log start
            $log = [
                'id' => uniqid(),
                'user_id' => auth()->id(),
                'action' => 'delete-evidence',
                'model' => 'media_order',
                'endpoint' => 'media-order',
                'payload' => json_encode(request()->all()),
                'message' => 'Menghapus Eviden di Media Order ' . $mediaOrder->order_code,
                'created_at' => $now,
            ];
            DB::table('user_logs')->insert($log);
            // make log end

            DB::commit();
            return $this->successResponse(null, 'Evidence berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    function sentToVerificator($id, Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $now = now();
        $data = DB::table('orders')
            ->where('id', $id)
            ->first();
        if (!$data) {
            return $this->notFoundResponse('Media Order tidak dapat kami temukan');
        }
        if (in_array($data->status, ['sent', 'rejected']) == false) {
            return $this->validationErrorResponse('Media Order tidak dalam status yang benar');
        }
        $agenda = DB::table('agendas')->where('id', $data->agenda_id)->first();
        if (!$agenda) {
            return $this->notFoundResponse('Agenda tidak dapat kami temukan');
        }

        if ($data) {
            DB::beginTransaction();
            try {
                DB::table('orders')
                    ->where('order_code', $data->order_code)
                    ->whereIn('status', ['sent', 'rejected'])
                    ->update([
                        'status' => 'review',
                        'updated_at' => $now,
                        'deadline' => null,
                    ]);

                $note = 'Mengirimkan Lampiran Eviden kepada Admin';

                DB::table('log_order_status')
                    ->insert([
                        'order_id' => $data->id,
                        'media_id' => $data->media_id,
                        'agenda_id' => $data->agenda_id,
                        'status' => 'review',
                        'note' => $note,
                        'user_id' => auth()->id(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);


                // make log start
                $log = [
                    'id' => uniqid(),
                    'user_id' => auth()->id(),
                    'action' => 'sent-evidence',
                    'model' => 'media_order',
                    'endpoint' => 'media-order',
                    'payload' => json_encode(request()->all()),
                    'message' => $note,
                    'created_at' => $now,
                ];
                DB::table('user_logs')->insert($log);
                // make log end

                DB::commit();
                return $this->successResponse(null, 'Evidence berhasil dikirim');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage());
            }
        }
    }
}
