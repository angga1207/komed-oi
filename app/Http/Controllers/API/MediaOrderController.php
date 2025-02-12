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
}
