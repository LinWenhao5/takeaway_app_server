<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class MediaController extends Controller
{
    public function index()
    {
        try {
            $media = Media::all();
            return response()->json($media, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load media: ' . $e->getMessage()], 500);
        }
    }

    public function showMediaLibrary()
    {
        $media = Media::all();
        return view('admin.media', compact('media'));
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $uploaded = Storage::disk('cloudinary')->putFile('uploads', $file);

                $cloudName = env('CLOUDINARY_CLOUD_NAME');
                $uploadedFileUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/{$uploaded}";

                Media::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $uploadedFileUrl,
                    'public_id' => $uploaded,
                ]);
            }

            return redirect()->route('admin.media.library')->with('success', 'Image uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to upload image: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $media = Media::findOrFail($id);

            if ($media->public_id) {
                Storage::disk('cloudinary')->delete($media->public_id);
            }

            $media->delete();

            return redirect()->route('admin.media.library')->with('success', 'Media deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete media: ' . $e->getMessage()]);
        }
    }
}
