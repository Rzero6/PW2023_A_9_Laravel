<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\MailSend;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $str = Str::random(100);
            $registrationData = $request->all();
            $registrationData['verify_key'] = $str;
            $validate = Validator::make($registrationData, [
                'nama' => 'required',
                'email' => 'required|email:rfc,dns|unique:users',
                'password' => 'required',
            ]);

            if ($validate->fails()) new \Exception($validate->errors());
            $registrationData['password'] = bcrypt($request->password);
            $registrationData['menyewa'] = false;
            $registrationData['profil_pic'] = 'https://picsum.photos/200';
            $registrationData['role'] = 0;
            $user = User::create($registrationData);
            $details = [
                'username' => $user->nama,
                'website' => 'Rental Mobil',
                'datetime' => date('Y-m-d H:i:s'),
                'url' => request()->getHttpHost() . '/register/verify/' . $str
            ];
            Mail::to($user->email)->send(new MailSend($details));
            return response()->json([
                'message' => 'Link verifikasi telah dikirim ke email anda. Silahkan cek email anda untuk mengaktifkan akun.',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function verify($verify_key)
    {
        $keyCheck = User::select('verify_key')->where('verify_key', $verify_key)->exists();
        if ($keyCheck) {
            User::where('verify_key', $verify_key)->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);
            return "Verifikasi berhasil. Akun anda sudah aktif.";
        } else {
            return "Key tidak Valid";
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            "email" => "required|email:rfc,dns",
            "password" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
            ], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response()->json([
                'message' => 'Email atau Password salah',
            ], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        if ($user->email_verified_at === null) {
            return response()->json([
                'message' => 'Email belum diverifikasi'
            ], 401);
        }
        $token = $user->createToken('Authentication Token')->accessToken;

        return response()->json([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ], 200);
    }

    public function registerAdmin(Request $request)
    {
        try {

            $str = Str::random(100);
            $registrationData = $request->all();
            $registrationData['verify_key'] = $str;
            $validate = Validator::make($registrationData, [
                'nama' => 'required',
                'email' => 'required|email:rfc,dns|unique:users',
                'password' => 'required',
            ]);

            if ($validate->fails()) new \Exception($validate->errors());
            $registrationData['password'] = bcrypt($request->password);
            $registrationData['menyewa'] = false;
            $registrationData['profil_pic'] = 'https://picsum.photos/200';
            $registrationData['role'] = 1;
            $registrationData['email_verified_at'] = date('Y-m-d H:i:s');
            $user = User::create($registrationData);

            return response()->json([
                'message' => 'Register Success',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function loginAdmin(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            "email" => "required|email:rfc,dns",
            "password" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
            ], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response()->json([
                'message' => 'Email atau Password salah',
            ], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        if ($user->role === 0) {
            return response()->json([
                'message' => 'Not Authorized'
            ], 401);
        }
        $token = $user->createToken('Authentication Token')->accessToken;

        return response()->json([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ], 200);
    }
}
