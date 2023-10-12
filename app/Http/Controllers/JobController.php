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
            $this->middleware('auth:worker,api');
        
    }


    public function index(){
        return Job::all();
    }

    
    public function get_jobs(Request $request){
        $validator = Validator::make($request->all(),[
            'profession'=>'required|array'
        ]);

        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }
        
        return Job::all()->whereIn('profession',$request->profession);
    }

    
    public function add(Request $request){

        $profession = [
            'fan',
            'screen',
            'conditioning',
            'gasStove',
            'washingMachine',
            'refrigerator',
            'carCleaning',
            'houseCleaning',
            'electricity',
            'plumbing',
            'carpentry',
            'tileInstallation',
            'engraver'
        ];


        $validator = Validator::make($request->all(),[
            'description'=>'required',
            'title'=>'required',
            'img' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'user_id'=>'required',
            'zone'=>'required',
            'length'=>'required',
            'width'=>'required',
            'profession'=>'required|in:'.implode(',',$profession)
        ]);

        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }

        $imageName = time().'.'.$request->img->extension();
        // $request->img->move(public_path('job'), $imageName);


        try{
            Job::create([
               'description'=>$request->description,
               'title'=>$request->title,
               'zone'=>$request->zone,
               'length'=>$request->length,
               'width'=>$request->width,
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
