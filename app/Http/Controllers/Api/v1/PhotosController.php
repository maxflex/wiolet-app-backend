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
     * $request->photos = [
     *      ['id' => 16, 'position' => 0],
     *      ['id' => 11, 'position' => 1],
     * ]
     */
    public function update(Request $request)
    {
        foreach($request->all() as $photo) {
            Photo::whereId($photo['id'])->update(['position' => $photo['position']]);
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
    }
}
