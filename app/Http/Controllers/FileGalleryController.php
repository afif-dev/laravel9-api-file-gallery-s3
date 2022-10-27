<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Libraries\FileFilters;
use App\Models\FileGallery;
use App\Http\Requests\StoreFileGalleryRequest;
use App\Http\Requests\UpdateFileGalleryRequest;
use App\Http\Resources\FileGalleryCollection;

class FileGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $galleries = FileGallery::where('user_id', $request->user()->id)->paginate(10);
        foreach($galleries as $gallery){
            $url = Storage::disk('s3')->temporaryUrl($gallery->path_ext, now()->addMinutes(5));
            $gallery->url = $url;
        }

        return new FileGalleryCollection($galleries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFileGalleryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileGalleryRequest $request)
    {
        $validated = $request->validated();
        
        if($validated) {

            $s3_bucket_dir = "images";
            
            $file = $validated['file']; 
            $name = $file->hashName();
            // $name = $file->getClientOriginalName();
            // $extension = $file->extension();
            
            $path = $file->storeAs($s3_bucket_dir, $name,'s3');

            $file_filter = FileFilters::uploadFileScript($path);
            if(!$file_filter) {
                return response()->json([
                    'message' =>  __('fileupload.error_malicious'),
                ], 400);
            }
            
            $fileGallery = new FileGallery;
            $fileGallery->user_id = $request->user()->id;
            $fileGallery->title = $validated['title'] ?? null;
            $fileGallery->desc = $validated['desc'] ?? null;
            $fileGallery->path_ext = $s3_bucket_dir.'/'.$name;
            $fileGallery->save();

            $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(5));
            $fileGallery->url = $url;
            
            return $fileGallery;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FileGallery  $fileGallery
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, FileGallery $fileGallery)
    {
        if($request->user()->id == $fileGallery->user_id) {

            $url = Storage::disk('s3')->temporaryUrl($fileGallery->path_ext, now()->addMinutes(5));
            $fileGallery->url = $url;
            
            return $fileGallery;
        }

        return response()->json([
            'message' => __('auth.noauth'),
        ], 401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFileGalleryRequest  $request
     * @param  \App\Models\FileGallery  $fileGallery
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFileGalleryRequest $request, FileGallery $fileGallery)
    {
        
        if($request->user()->id == $fileGallery->user_id) {
            $validated = $request->validated();

            if($validated) {
                if ($validated['title']) $fileGallery->title = $validated['title'];
                if ($validated['desc']) $fileGallery->desc = $validated['desc'];
                $fileGallery->save();

                $url = Storage::disk('s3')->temporaryUrl($fileGallery->path_ext, now()->addMinutes(5));
                $fileGallery->url = $url;

                return $fileGallery;
            }
        }
        
        return response()->json([
            'message' => __('auth.noauth'),
        ], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FileGallery  $fileGallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FileGallery $fileGallery)
    {
        if($request->user()->id == $fileGallery->user_id) {
            if($fileGallery->delete()) {
                return response()->json([
                    'message' => __('fileupload.is_deleted'),
                ]);
            }
        }

        return response()->json([
            'message' => __('auth.noauth'),
        ], 401);
    }
}
