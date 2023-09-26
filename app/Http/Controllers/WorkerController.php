<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerRequest;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator ;
use Tymon\JWTAuth\Facades\JWTAuth;

class WorkerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['store','code','verify']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkerRequest $request)
    {
        $code = rand(1111,9999);
        $phone = $request->phone;
        $worker = Worker::where('phone',$phone)->first();

        if($worker){
            $worker->update(['phone_verify_code'=>$code]);
            return response()->json(['msg'=>true]);
        }else{
            Worker::create([
                'phone'=>$phone,
                'phone_verify_code'=>$code
            ]);
            return response()->json(['msg'=>true]);
        }

    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code'=>'required|min:4|max:4',
            'phone'=>'required|max:20'
        ]);


        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }
        

        $worker = Worker::where('phone',$request->phone)->whereNotNull('phone_verify_at')->first();
        
        if($worker){
            if(Worker::where('phone_verify_code',$request->code)->where('phone',$request->phone)->first())
            {
                $worker->update(['phone_verify_at'=>now()]);
                return response()->json(['msg'=>'old user','token'=>JWTAuth::fromUser($worker)]);
            }else{
                return response()->json(['msg'=>false]);
            };
        }else{
            if($worker = Worker::where(['phone'=>$request->phone,'phone_verify_code'=>$request->code])->first())
            {
                $worker->update(['phone_verify_at'=>now()]);
                return response()->json(['msg'=>'new user','token'=>JWTAuth::fromUser($worker)]);

            }else{
                return response()->json(['msg'=>false]);
            };
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required',
            'first_name'=>'required|max:255',
            'last_name'=>'required|max:255',
            'phone'=>'required|max:20'
        ]);


        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }
        

        $worker = Worker::where('phone',$request->phone);

        if($worker->first()){
            $worker->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
            ]);

            return response()->json(['msg'=>true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function code(string $phone)
    {
        if(Worker::where('phone',$phone)->first()){

            return Worker::where('phone',$phone)->first()['phone_verify_code'];
        }else{
            return 'no code ';
        }
    }


}
