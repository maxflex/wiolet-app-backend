<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Http\Resources\Photo\PhotoResource;
use Intervention\Image\ImageManagerStatic as Image;

class PhotosController extends Controller
{
    /**
     * Drag-n-drop фоток
     *
     * $request->photos = [16, 11, ... 2]
     * ID фоток в нужном порядке
     */
    public function update(Request $request)
    {
        foreach($request->photos as $index => $photoId) {
            Photo::whereId($photoId)->update(['position' => $index]);
        }
        return emptyResponse();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|image',
        ]);

        $item = auth()->user()->photos()->create();

        $request->file('photo')->storeAs('public/' . Photo::UPLOAD_PATH, Photo::getFilename($item));

        $resizedImage = Image::make($request->file('photo')->getRealPath());
        $resizedImage->resize(200, 200);
        $resizedImage->save(storage_path('app/public/' . Photo::UPLOAD_PATH)  . Photo::getFilename($item, true));

        return new PhotoResource($item);
    }

    public function destroy($id)
    {
        $item = Photo::find($id);
        $this->authorize('delete', $item);
        $item->delete();
        return emptyResponse();
    }
}
