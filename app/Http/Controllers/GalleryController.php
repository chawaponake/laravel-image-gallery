<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Media;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            return response()->json(['data' => MediaResource::collection(Media::all())]);
        }
        return view('gallery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lists = [];
        if($request->hasfile('files'))
        {
            foreach($request->file('files') as $file)
            {
                $name = $file->getClientOriginalName();
                $mime_type = $file->getClientMimeType();
                $size = $file->getClientSize();
                $path = $file->store('public/medias');
                if($mime_type == 'image/jpeg' || $mime_type == 'image/png'){
                    $requestImagePath = $file->getRealPath() . '.jpg';
                    $thumbnail = Image::make($file)->resize(200, 200)->encode('jpg');
                    $thumbnail->save($requestImagePath);
                    $thumbnail_path = Storage::putFile('public/medias/thumbnails' , new File($requestImagePath));
                }else{
                    $thumbnail_path = "";
                }

                $media = Media::create([
                   'name' => $name,
                   'mime_type' => $mime_type,
                   'size' => $size,
                   'path' => $path,
                   'thumbnail_path' => $thumbnail_path
                ]);

                array_push($lists,new MediaResource($media));
            }
        }

        return response()->json([
            'success' => 'Upload Successfully',
            'data' => $lists,
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        Storage::delete([$media->path,$media->thumbnail_path]);
        $media->delete();

        return response()->json(['success' => 'Delete successfully']);

    }
}
