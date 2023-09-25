<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerRequest;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator ;
use Tymon\JWTAuth\Facades\JWTAuth;

class WorkerController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:worker', ['except' => ['login','code']]);
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
        Worker::create([
            'phone'=>$request->phone,
            'phone_verify_code'=>$code,
        ]);

        return response()->json(['msg'=>true]);
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

        $worker = Worker::where(['phone'=>$request->phone,'phone_verify_code'=>$request->code])->first();

        if($worker){
            $worker::where(['phone'=>$request->phone,'phone_verify_code'=>$request->code])->update(['phone_verify_at'=>now()]);
            return response()->json(['msg'=>true]);
        }

        return response()->json(['msg'=>false]);
    }

    public function login(){
        if(request('phone')){
            $worker = Worker::where('phone',request('phone'))->whereNotNull('phone_verify_at')->first();
            if (!$worker ) {
                return response()->json(['msg' => 'Unauthorized'], 401);
            }
    
            return $this->respondWithToken(JWTAuth::fromUser($worker));
        }else{
            return response()->json(['msg' => 'Unauthorized'], 401);
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
    public function update(Request $request, string $id)
    {
        //
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

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            // 'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'msg'=>true
        ]);
    }
}
