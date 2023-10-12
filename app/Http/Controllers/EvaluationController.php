<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Job;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    public function __construct()
    {
            $this->middleware('auth:worker,api');
        
    }
    public function assess(Request $request){
        $validator = Validator::make($request->all(),[
            'job_id'=>'required',
            'assess'=>'required',
            'stars'=>'required|in:1,2,3,4,5',
        ]);
        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }

        if(Evaluation::where('job_id',$request->job_id)->first()){
            return response()->json(['msg'=>false,'data'=>'the worker has already assessed!']);
        }

        $job = Job::where('id',$request->job_id)->first();
        if($job->stage != 2){
            return response()->json(['msg'=>false,'data'=>'no worker had work yet!']);
        }

        try{
             Evaluation::create([
                'job_id'=>$request->job_id,
                'assess'=>$request->assess,
                'stars'=>$request->stars,
             ]);
             return response()->json(['msg'=>true]);
        }catch(Exception $ex){
            return response()->json(['msg'=>false]);
        }
    }

    public function index(){
        return Evaluation::all();
    }

    public function get_evaluations(Request $request){

        $validator = Validator::make($request->all(),[
            'job_id'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }
        try{
            $ev = Evaluation::where('job_id',$request->job_id)->first();
            if(!$ev){
                return response()->json(['msg'=>false,'data'=>'there is no evaluation']);
            }
            return response()->json(['msg'=>true,'data'=>$ev]);
        }catch(Exception $ex){
            return response()->json(['msg'=>false]);
        }
    }
}
