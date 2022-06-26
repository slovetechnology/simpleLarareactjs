<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index() {
        $blogs = Blog::orderBy('created_at', 'DESC')->get();
        if($blogs->count() > 0) {
            return response()->json([
                'status' => 200,
                'msg' => $blogs
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'msg' => 'No Blog post found!..'
            ]);
        }
    }
    public function store(Request $request) {
        $validate = Validator::make($request->all(), [
            'content' => 'required|string'
        ]);
        if($validate->fails()) {
            return response()->json([
                'status' => 400,
                'msg' => $validate->errors()->first()
            ]);
        }else{
            $blog = new Blog;
            $blog->content = $request->content;
            $blog->save();
            return response()->json([
                'status' => 200,
                'msg' => 'Blog post successfully created!...'
            ]);
        }
    }
    public function destroy($id) {
        $blog = Blog::find($id);
        if($blog) {
            $path = $blog->profile;
            if(File::exists($path)) {
                File::delete($path);
            }
            $blog->delete();
            return response()->json([
                'status' => 200,
                'msg' => 'Blog post successfully deleted!..'
            ]);
        }
    }
    public function edit($id) {
        $blog = Blog::find($id);
        if($blog) {
            return response()->json([
                'status' => 200,
                'msg' => $blog
            ]);
        }
    }
}
