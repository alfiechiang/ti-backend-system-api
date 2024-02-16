<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{


    protected $service;
    public function __construct()
    {
        $this->service = new UploadService();
    }

    public function uplaod(Request $request)
    {

        $path = $this->service->uplaod($request->image);
        $data = [];
        $data['imagePath'] = env('UPLOAD_HOST') . "/" . $path;
        return Response::format(200, $data, "上傳圖片成功");
    }
}
