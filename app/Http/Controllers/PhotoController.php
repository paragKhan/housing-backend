<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|mimes:jpeg,png,jpg|max:30000'
        ]);

        $photo_name = Str::random(10) . '.' . time() . '.' . $request->file('photo')->getClientOriginalExtension();

        $now = Carbon::now();
        $path_name = $now->year . '-' . $now->month . '-' . $now->day;

        if (!File:: exists(public_path('uploads'))) {
            File::makeDirectory(public_path('uploads'));
        }
        if (!File:: exists(public_path('uploads/' . $path_name))) {
            File::makeDirectory(public_path('uploads/' . $path_name));
        }

        $request->file('photo')->move('uploads/' . $path_name, $photo_name);

        $photo = Photo::create(['path' => $path_name.'/'.$photo_name, 'uniqid' => uniqid()]);

        return response()->json($photo->uniqid);
    }

    public static function delete($path)
    {
        if (!$path) return;
        if (file_exists(public_path('uploads/' . $path))) {
            unlink(public_path('uploads/' . $path));
        }
    }
}
