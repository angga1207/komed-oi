<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\JsonReturner;
use App\HeaderChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    use JsonReturner, HeaderChecker;

    public function index(Request $request)
    {
        if ($this->checkHeader($request) == false) {
            return $this->unauthorizedResponse('Unauthorized');
        }

        $announcements = DB::table('announcements')
            ->select(['id', 'title', 'content', 'image', 'link', 'created_by', 'created_at'])
            ->addSelect(DB::raw("
                CASE
                    WHEN image IS NULL THEN NULL
                    ELSE CONCAT('" . rtrim(asset(''), '/') . "', image)
                END as image
            "))
            ->addSelect(DB::raw("(SELECT fullname FROM users WHERE id = created_by) as created_by"))
            ->where('is_active', 1)
            ->where('published_at', '<=', date('Y-m-d H:i:s'))
            ->orderBy('created_at', 'asc')
            ->first();


        return $this->successResponse($announcements, 'Data Pengumuman');
    }
}
