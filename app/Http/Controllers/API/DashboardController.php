<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\JsonReturner;
use App\HeaderChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    use JsonReturner, HeaderChecker;

    function getDashboard(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $return = [];
        $pers = DB::table('pers_profile')->where('user_id', auth()->id())->first();

        $mediaOrders = DB::table('orders')
            ->where('media_id', $pers->id)
            ->select(['id', 'order_code', 'status'])
            ->get();

        $return['count']['total'] = $mediaOrders->count();
        $return['count']['belum_dikerjakan'] = count($mediaOrders->where('status', 'sent'));
        $return['count']['direview'] = count($mediaOrders->whereIn('status', ['review']));
        $return['count']['sudah_dikerjakan'] = count($mediaOrders->whereIn('status', ['verified', 'done']));

        $timelines = [];
        $arrTimeline = DB::table('orders')
            ->where('media_id', $pers->id)
            // ->whereDate('tanggal_pelaksanaan', Carbon::now()->subDay())
            // ->whereDate('tanggal_pelaksanaan', Carbon::now())
            ->whereDate('tanggal_pelaksanaan', '2025-02-12')
            ->orderBy('waktu_pelaksanaan')
            ->get();

        foreach ($arrTimeline as $tl) {
            $agenda = DB::table('agendas')->where('id', $tl->agenda_id)->first();
            $dataAgenda = collect(json_decode($agenda->data));
            $timelines[] = [
                'id' => $tl->id,
                'order_code' => $tl->order_code,
                'nama_acara' => $dataAgenda['nama_acara'] ?? null,
                'tanggal_pelaksanaan' => $tl->tanggal_pelaksanaan,
                'tanggal_pelaksanaan_akhir' => $tl->tanggal_pelaksanaan_akhir,
                'waktu_pelaksanaan' => $tl->waktu_pelaksanaan,
                'leading_sector' => $tl->leading_sector,
                'lokasi' => $dataAgenda['tempat_pelaksanaan_array'][0] ?? null,
                'status' => $tl->status,
                'created_at' => $tl->created_at,
                'deadline' => Carbon::parse($tl->created_at)->addDays(7)->isoFormat('Y-MM-DD HH:mm:ss'),
            ];
        }
        $return['timelines'] = $timelines;

        $chart = [];
        $range = CarbonPeriod::create(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        foreach ($range as $date) {
            $dateYMD = Carbon::parse($date)->isoFormat('Y-MM-DD');
            $dateStr = Carbon::parse($date)->isoFormat('D MMM YY');
            $order = DB::table('orders')
                ->where('media_id', $pers->id)
                ->whereDate('tanggal_pelaksanaan', $dateYMD)
                ->get();
            $chart[] = [
                'date_str' => $dateStr,
                'date' => $dateYMD,
                'total' => count($order),
                'selesai' => count($order->whereIn('status', ['verified', 'done'])),
                'dikerjakan' => count($order->whereIn('status', ['sent'])),
                'direview' => count($order->whereIn('status', ['review'])),
            ];
        }
        $return['chart'] = $chart;

        return $this->successResponse($return, 'Welcome to dashboard');
    }
}
