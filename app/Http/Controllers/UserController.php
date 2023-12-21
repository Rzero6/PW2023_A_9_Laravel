<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::where('role', 0)->get();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $users
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    /**
     * Display the specified resource.
     */
    public function updateProfilPic(Request $request)
    {
        try {
            $idUser = Auth::user()->id;
            $user = User::find($idUser);
            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
            $storeData = $request->all();
            $validate = Validator::make($storeData, [
                'profil_pic' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            if ($request->hasFile('profil_pic')) {
                if ($user->profil_pic !== null) {
                    $filename = basename($user->profil_pic);
                    if (Storage::disk('public')->exists('user/' . $filename)) {
                        Storage::disk('public')->delete('user/' . $filename);
                    }
                }
                $uploadFolder = 'user';
                $image = $request->file('profil_pic');
                $image_uploaded_path = $image->store($uploadFolder, 'public');
                $uploadedImageResponse = basename($image_uploaded_path);
                $storeData['profil_pic'] = $uploadedImageResponse;
                $user->profil_pic = $storeData['profil_pic'];
            }
            $user->save();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil update gambar',
                "data" => $user
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }


    public function show($id)
    {
        try {
            $user = User::find($id);

            if (!$user) throw new \Exception("User tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $user
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) throw new \Exception("User tidak ditemukan");
            $updatedData = $request->only([
                'nama', 'email', 'password'
            ]);
            $rules = [
                'nama' => 'sometimes|required',
                'email' => 'sometimes|required|email:rfc,dns|unique:users,email,' . $id,
                'password' => 'sometimes|required|min:8',
            ];
            $validate = Validator::make($updatedData, $rules);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            if ($request->has('password') && $request->password !== null) {
                $updatedData['password'] = bcrypt($request->password);
            }
            foreach ($updatedData as $key => $value) {
                if ($request->has($key)) {
                    $user->{$key} = $value;
                }
            }
            $user->save();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil update data',
                "data" => $user
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) throw new \Exception("User tidak ditemukan");
            if ($user->profil_pic !== null) {
                $filename = basename($user->profil_pic);
                if (Storage::disk('public')->exists('user/' . $filename)) {
                    Storage::disk('public')->delete('user/' . $filename);
                }
            }
            $user->delete();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil hapus data',
                "data" => $user
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }
}
