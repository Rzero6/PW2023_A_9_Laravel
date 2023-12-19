<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
                "massage" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    /**
     * Display the specified resource.
     */
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
                "massage" => $e->getMessage(),
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
                'nama', 'email', 'password', 'profil_pic'
            ]);
            $rules = [
                'nama' => 'sometimes|required',
                'email' => 'sometimes|required|email:rfc,dns|unique:users,email,' . $id,
                'password' => 'sometimes|required|min:8',
                'profil_pic' => 'sometimes|required|image:jpeg,png,jpg,gif,svg|max:2048',
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
            if ($request->hasFile('image')) {
                if ($user->image !== null) {
                    $filename = basename($user->image);
                    if (Storage::disk('public')->exists('user/' . $filename)) {
                        Storage::disk('public')->delete('user/' . $filename);
                    }
                }
                $uploadFolder = 'user';
                $image = $request->file('image');
                $image_uploaded_path = $image->store($uploadFolder, 'public');
                $uploadedImageResponse = basename($image_uploaded_path);
                $user->image = $uploadedImageResponse;
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
                "massage" => $e->getMessage(),
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

            $user->delete();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil hapus data',
                "data" => $user
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "massage" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }
}
