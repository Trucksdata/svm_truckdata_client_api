<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileUploadRequest;
use App\Http\Requests\UpdateFileUploadRequest;
use App\Models\FileUpload;

class FileUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('super_admin')->only(['index', 'update', 'delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FileUpload::paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFileUploadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileUploadRequest $request)
    {
        $urls = [];
        foreach ($request->attachment as $media) {
            $attachment = new FileUpload();
            $attachment->save();
            $attachment->addMedia($media)->toMediaCollection();
            foreach ($attachment->getMedia() as $media) {
                if (strpos($media->mime_type, 'image/') !== false) {
                    $converted_url = [
                        'thumbnail' => $media->getUrl('thumbnail'),
                        'original' => $media->getUrl(),
                        'id' => $attachment->id
                    ];
                } else {
                    $converted_url = [
                        'thumbnail' => '',
                        'original' => $media->getUrl(),
                        'id' => $attachment->id
                    ];
                }
            }
            $urls[] = $converted_url;
        }
        return $urls;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FileUpload  $fileUpload
     * @return \Illuminate\Http\Response
     */
    public function show(FileUpload $fileUpload)
    {
        $mediaItem = $fileUpload->getMedia()->first();

        if (!$mediaItem) {
            return $this->errorResponse('File not found', 404);
        }

        $headers = [
            'Content-Type' => $mediaItem->mime_type,
        ];

        return response()->file($mediaItem->getPath(), $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFileUploadRequest  $request
     * @param  \App\Models\FileUpload  $fileUpload
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFileUploadRequest $request, FileUpload $fileUpload)
    {
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FileUpload  $fileUpload
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileUpload $fileUpload)
    {
        foreach ($fileUpload->getMedia() as $mediaItem) {
            $mediaItem->delete();
        }
        $fileUpload->delete();
        return $this->successResponse('file deleted successfully');
    }
}
