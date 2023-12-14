<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use Illuminate\Http\Request;

class SewaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $sewa = Sewa::all();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $sewa
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
            $sewa = Sewa::create($request->all());
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $sewa
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
            $sewa = Sewa::find($id);

            if(!$sewa) throw new \Exception("ID tidak ditemukan");
            
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $sewa
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
            $sewa = Sewa::find($id);

            if(!$sewa) throw new \Exception("ID tidak ditemukan");
            
            $sewa->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $sewa
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
            $sewa = Sewa::find($id);

            if(!$sewa) throw new \Exception("ID tidak ditemukan");
            
            $sewa->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data', 
                "data" => $sewa
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
