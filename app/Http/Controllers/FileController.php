<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;


class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt');
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if(!auth()->user()){

            return response()->json([
                'error' => 'Kullnıcı Yok.',
                'status' => 401
            ]);
        }

        $user_id = auth()->user()->id;

        $files = File::where('user_id', $user_id)->get();

        return response()->json([
            'files' => $files,
            'user_id' => $user_id
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        //İzin verilen dosya mimetype ları
        $fileTypes = ['image/jpeg','image/png','video/mp4'];

        $file = $request->uploads;

        if (!$request->hasFile('uploads') ) {

            return response()->json([
                'error' => 'Dosya Yok',
                'status' => 400
            ]);
        }

        $mimeType = $file->getMimeType();

        if(!in_array($mimeType, $fileTypes)){

            return response()->json([
                'error' => 'İzin Verilmeyen Dosya Türü'
            ]);
        }


        $destinationPath    = 'uploads/';
        $fileName           = $file->getFilename();
        $title              = $request->title ? $request->title : $fileName;
        $slug               = str_slug($fileName);
        $originalName       = $file->getClientOriginalName();
        $videoName          = $imageName = null;


        //Dosyayı Yüklüyoruz.
        $file->move($destinationPath,$originalName);


        File::create([
            'user_id'   => auth()->user()->id,
            'str_id'    => str_random(10),
            'title'     => $title,
            'slug'      => $slug,
            'image'     => $mimeType != "video/mp4" ? $fileName : null,
            'file'      => $mimeType === "video/mp4" ? $fileName : null,
        ]);


        $rows = File::all()->count();

        return response()->json([
            'rows' => $rows,
        ]);
    }

}
