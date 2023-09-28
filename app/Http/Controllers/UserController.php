<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store','verify','code','google']]);
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
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $code = rand(1111,9999);
        $phone = $request->phone;
        $user = User::where('phone',$phone)->first();

        if($user){
            $user->update(['phone_verify_code'=>$code]);
            return response()->json(['msg'=>true]);
        }else{
            User::create([
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
        

        $user = User::where('phone',$request->phone)->whereNotNull('phone_verify_at')->first();
        
        if($user){
            if(User::where('phone_verify_code',$request->code)->where('phone',$request->phone)->first())
            {
                $user->update(['phone_verify_at'=>now()]);
                return response()->json(['msg'=>'old user','token'=>JWTAuth::fromUser($user)]);
            }else{
                return response()->json(['msg'=>false]);
            };
        }else{
            if($user = User::where(['phone'=>$request->phone,'phone_verify_code'=>$request->code])->first())
            {
                $user->update(['phone_verify_at'=>now()]);
                return response()->json(['msg'=>'new user','token'=>JWTAuth::fromUser($user)]);

            }else{
                return response()->json(['msg'=>false]);
            };
        }

    }

    public function google(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id'=>'required|max:255',
            'user_name'=>'required|max:255'
        ]);

        if($validator->fails()){
            return response()->json(['msg'=>false,'data'=>$validator->errors()]);
        }

        $user = User::where('social_id',$request->user_id)->first();

        if($user){
            return response()->json(['msg'=>'login','token'=>JWTAuth::fromUser($user)]);
        }else{
            User::create([
                'first_name'=>$request->user_name,
                'social_id'=>$request->user_id,
                'social_type'=>'google'
            ]);
            
            return response()->json(['msg'=>'created']);
        }

    }

    public function code(string $phone)
    {
        if(User::where('phone',$phone)->first()){
            return User::where('phone',$phone)->first()['phone_verify_code'];
        }else{
            return 'no code ';
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
        

        $user = User::where('phone',$request->phone);

        if($user->first()){
            $user->update([
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
}
