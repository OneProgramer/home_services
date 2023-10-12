<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Job;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function add(Request $request){
        $validator = Validator::make($request->all(),[
            'description'=>'required',
            'title'=>'required',
            'price'=>'required',
            'img' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'days'=>'required',
            'address'=>'required',
            'user_id'=>'required',
            'profession'=>'required',
        ]);

        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }

        $imageName = time().'.'.$request->img->extension();
        $request->img->move(public_path('job'), $imageName);


        try{
            Job::create([
               'description'=>$request->description,
               'title'=>$request->title,
               'price'=>$request->price,
               'days'=>$request->days,
               'address'=>$request->address,
               'user_id'=>$request->user_id,
               'profession'=>$request->profession,
               'img'=>$imageName
           ]);
   
               return response()->json(['msg'=>true]);
        }catch(Exception $ex){
            return response()->json(['msg'=>false]);
        }
        

    }

    public function select(Request $request){
        // [jobs.stage = 2 and comments.accept = 1 ]
        $validator = Validator::make($request->all(),[
            'comment_id'=>'required'
        ]);

        if($validator->fails())
        { 
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }

        try{
            $comment = Comment::where('id',$request->comment_id);
            $job_id = $comment->first()->job_id;

            $comment->update([
                'accept'=>1
            ]);

            Job::where('id',$job_id)->update(['stage'=>'2']);
            
            return response()->json(['msg'=>true]);
        }catch(Exception $ex)
        {
            return response()->json(['msg'=>false,'data'=>'the comment is not exist !']);
        }

    }

    

    
}
