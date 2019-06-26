<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Http\Resources\Photo\PhotoResource;

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

        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $original_name = $file->getClientOriginalName();
        $filename = uniqid() . '.' . $extension;
        $file->storeAs('public/' . Photo::UPLOAD_PATH, $filename);

        $item = auth()->user()->photos()->create([
            'filename' => $filename
        ]);

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
