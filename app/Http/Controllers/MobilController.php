<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $mobil = Mobil::all();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $mobil
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $storeData = $request->all();
            $storeData['tipe'] = strtolower($request->tipe);
            $storeData['bahan_bakar'] = strtolower($request->bahan_bakar);
            $storeData['transmisi'] = strtolower($request->transmisi);
            $validate = Validator::make($storeData, [
                'id_cabang' => 'required|numeric',
                'tipe' => 'required|in:suv,hatchback,sedan,sport,convertible,truck,off road, mpv',
                'nama' => 'required|max:60',
                'harga_sewa' => 'required|numeric',
                'tahun' => 'required|date_format:Y',
                'bahan_bakar' => 'required|in:bensin,hybrid,elektrik,diesel',
                'jml_tempat_duduk' => 'required|numeric',
                'transmisi' => 'required|in:manual,matic',
                'no_polisi' => 'required|max:10',
                'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }

            $cabang = Cabang::find($storeData['id_cabang']);
            if (!$cabang) throw new \Exception("Cabang tidak ditemukan");

            if ($request->hasFile('image')) {
                $uploadFolder = 'mobil';
                $image = $request->file('image');
                $image_uploaded_path = $image->store($uploadFolder, 'public');
                $uploadedImageResponse = basename($image_uploaded_path);
                $storeData['image'] = $uploadedImageResponse;
            }
            $storeData['disewa'] = false;
            $mobil = Mobil::create($storeData);
            return response()->json([
                "status" => true,
                "message" => 'Berhasil insert data',
                "data" => $mobil
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
    public function show($id)
    {
        try {
            $mobil = Mobil::find($id);

            if (!$mobil) throw new \Exception("Mobil tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $mobil
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    public function showMobilByCabang($id)
    {
        try {
            $cabang = Cabang::find($id);

            if (!$cabang) {
                throw new \Exception("Cabang tidak ditemukan");
            }
            $mobil = Mobil::where('id_cabang', $id)->get();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $mobil
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
            $mobil = Mobil::find($id);

            if (!$mobil) {
                throw new \Exception("Mobil tidak ditemukan");
            }

            $updateData = $request->only([
                'id_cabang', 'tipe', 'nama', 'harga_sewa', 'tahun',
                'bahan_bakar', 'jml_tempat_duduk', 'transmisi', 'no_polisi', 'disewa'
            ]);

            $rules = [
                'id_cabang' => 'sometimes|required|numeric',
                'tipe' => 'sometimes|required|in:suv,hatchback,sedan,sport,convertible,truck,off road, mpv',
                'nama' => 'sometimes|required|max:60',
                'harga_sewa' => 'sometimes|required|numeric',
                'tahun' => 'sometimes|required|date_format:Y',
                'bahan_bakar' => 'sometimes|required|in:bensin,hybrid,elektrik,diesel',
                'jml_tempat_duduk' => 'sometimes|required|numeric',
                'transmisi' => 'sometimes|required|in:manual,matic',
                'no_polisi' => 'sometimes|required|max:10',
                'disewa' => 'sometimes|required|boolean',
            ];

            $validator = Validator::make($updateData, $rules);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }

            foreach ($updateData as $key => $value) {
                if ($request->has($key)) {
                    $mobil->{$key} = $value;
                }
            }

            $mobil->save();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil update data',
                "data" => $mobil
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
            $mobil = Mobil::find($id);

            if (!$mobil) throw new \Exception("Mobil tidak ditemukan");

            if ($mobil->image !== null) {
                $filename = basename($mobil->image);
                if (Storage::disk('public')->exists('mobil/' . $filename)) {
                    Storage::disk('public')->delete('mobil/' . $filename);
                }
            }
            $mobil->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil hapus data',
                "data" => $mobil
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
