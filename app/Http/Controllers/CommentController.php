<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Job;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:worker');
    }
    public function add(Request $request){
        $validator = Validator::make($request->all(),[
            'description'=>'required',
            'price'=>'required',
            'days'=>'required',
            'worker_id'=>'required',
            'job_id'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }

        //ensure the worker comment once
        $comment = Comment::where('job_id',$request->job_id)->where('worker_id',$request->worker_id)->first();

        if($comment){
            return response()->json(['msg'=>false,'data'=>'the worker is already commeted!']);
        }

        //ensure job stage == 1
        $stage = Job::where('id',$request->job_id)->where('stage','1')->first();
        if(!$stage){
            return response()->json(['msg'=>false,'data'=>'the job`s comments ended!']);
        }
        //adding comment
        try{
             Comment::create([
                'description'=>$request->description,
                'price'=>$request->price,
                'days'=>$request->days,
                'job_id'=>$request->job_id,
                'worker_id'=>$request->worker_id
            ]);
                return response()->json(['msg'=>true]);
        }catch(Exception $ex)
        {
            return response()->json(['msg'=>false]);
        }

    }
}
