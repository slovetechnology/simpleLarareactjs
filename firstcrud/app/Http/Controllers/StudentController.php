<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Student;

class StudentController extends Controller
{
    public function index() {
        $students = Student::orderBy('created_at', 'DESC')->get();
        if($students->count() > 0) {
            return response()->json([
                'status' => 200,
                'msg' => $students
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'msg' => 'No Student record found'
            ]);
        }
    }
    public function store(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|numeric',
            'class' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:3045',
        ]);
        if($validate->fails()){
            return response()->json([
                'status' => 400,
                'msg' => $validate->errors()->first()
            ]);
        }else{
            $item = new Student;
            $item->name = $request->input('name');
            $item->contact = $request->input('contact');
            $item->class = $request->input('class');
            if($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time().'.'.$ext;
                $file->move('images/profiles/', $filename);
                $item->profile = 'images/profiles/'.$filename;
            }
            $item->save();
            return response()->json([
                'status' => 200,
                'msg' => 'Student profile successfully created!...'
            ]);
        }
    }
    public function edit($id) {
        $item = Student::findOrFail($id);
        if($item) {
            return response()->json([
                'status' => 200,
                'msg' => $item
            ]);
        }else{
            return response()>json([
                'status' => 404,
                'msg' => 'Invalid Student Locator Passed'
            ]);
        }
    }
    public function update_student(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|numeric',
            'class' => 'required|string',
        ]);
        if($validate->fails()){
            return response()->json([
                'status' => 400,
                'msg' => $validate->errors()->first()
            ]);
        }else{
            $item = Student::find($id);
            if($item) {
                $item->name = $request->input('name');
                $item->contact = $request->input('contact');
                $item->class = $request->input('class');
                if($request->hasFile('image')) {
                    $path = $item->profile;
                    if(File::exists($path)) {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $ext = $file->getClientOriginalExtension();
                    $filename = time().'.'.$ext;
                    $file->move('images/profiles/', $filename);
                    $item->profile = 'images/profiles/'.$filename;
                }
                $item->update();
                return response()->json([
                    'status' => 200,
                    'msg' => $request->name.'\'s profile successfully Updated!...'
                ]);
            }else{
                return response()->json([
                    'status' => 400,
                    'msg' => 'Error Updating Student Profile'
                ]);
            }
        }
    }
    public function delete_student($id) {
        $item = Student::find($id);
        if($item) {
            $path = $item->profile;
            File::delete($path);
            $item->delete();
            return response()->json([
                'status' => 200,
                'msg' => 'Student Profile Successfully Deleted'
            ]);
        }
    }
}
