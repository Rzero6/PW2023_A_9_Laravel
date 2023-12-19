<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $review = Review::all();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $review
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $idUser = Auth::user()->id;
            $storeData = $request->all();
            $validate = Validator::make($storeData, [
                'id_transaksi' => 'required|numeric',
                'komen' => 'required',
                'rating' => 'required|numeric|between:1,10',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            $transaksi = Transaksi::find($storeData['id_transaksi']);
            if (!$transaksi) throw new \Exception("Transaksi tidak ditemukan");
            $storeData['id_mobil'] = $transaksi->id_mobil;
            $storeData['id_user'] = $idUser;

            $review = Review::create($storeData);
            $transaksi->status = 'reviewed';
            $transaksi->save();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil insert data',
                "data" => $review
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
            $review = Review::find($id);

            if (!$review) throw new \Exception("Review tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $review
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "massage" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    public function showByMobil($id)
    {
        try {
            $reviews = Review::where('id_mobil', $id)->get();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $reviews
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
            $review = Review::find($id);

            if (!$review) throw new \Exception("Transaksi tidak ditemukan");
            if ($request->rating !== null) {
                $review->rating = $request->rating;
            }
            if ($request->komen !== null) {
                $review->komen = $request->komen;
            }
            $review->save();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil update data',
                "data" => $review
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
            $review = Review::find($id);

            if (!$review) throw new \Exception("Review tidak ditemukan");

            $review->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil hapus data',
                "data" => $review
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
