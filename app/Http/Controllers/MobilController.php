<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $mobil = Mobil::all();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $mobil
            ], 200); //status code 200 = success
        }
        catch(\Exception $e){
            return response()->json([
                "status" => false,
                "massage" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            //$request->all() untuk mengambil semua input dalam request body
            $mobil = Mobil::create($request->all());
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $mobil
            ], 200); //status code 200 = success
        }
        catch(\Exception $e){
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
        try{
            $mobil = Mobil::find($id);

            if(!$mobil) throw new \Exception("Mobil tidak ditemukan");
            
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $mobil
            ], 200); //status code 200 = success
        }
        catch(\Exception $e){
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
        try{
            $mobil = Mobil::find($id);

            if(!$mobil) throw new \Exception("Mobil tidak ditemukan");
            
            $mobil->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $mobil
            ], 200); //status code 200 = success
        }
        catch(\Exception $e){
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
        try{
            $mobil = Mobil::find($id);

            if(!$mobil) throw new \Exception("Mobil tidak ditemukan");
            
            $mobil->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $mobil
            ], 200); //status code 200 = success
        }
        catch(\Exception $e){
            return response()->json([
                "status" => false,
                "massage" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }
}
