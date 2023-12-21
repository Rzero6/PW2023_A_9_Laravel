<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Mobil;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transaksi = Transaksi::all();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $transaksi
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
            $validate = Validator::make($storeData, [
                'id_mobil' => 'required|numeric',
                'id_cabang_pickup' => 'required|numeric',
                'id_cabang_dropoff' => 'required|numeric',
                'waktu_pickup' => 'required|date_format:Y-m-d',
                'waktu_dropoff' => 'required|date_format:Y-m-d',
                'metode_pembayaran' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }


            $idUser = Auth::user()->id;
            $user = User::find($idUser);
            if (is_null($user)) {
                return response([
                    'message' => 'User Not Found'
                ], 404);
            }
            $storeData['id_peminjam'] = $idUser;
            $mobil = Mobil::find($storeData['id_mobil']);
            if (is_null($mobil)) {
                return response([
                    'message' => 'Mobil Not Found'
                ], 404);
            }
            $pickupDate = new \DateTime($request->waktu_pickup);
            $dropoffDate = new \DateTime($request->waktu_dropoff);
            $interval = $pickupDate->diff($dropoffDate);
            $storeData['total'] = $interval->days * $mobil->harga_sewa;

            if ($storeData['id_cabang_pickup'] === $storeData['id_cabang_dropoff']) {
                $cabang = Cabang::find($storeData['id_cabang_pickup']);
                if (is_null($cabang)) {
                    return response([
                        'message' => 'Cabang Not Found'
                    ], 404);
                }
                $storeData['pickup'] = $cabang->kota;
                $storeData['dropoff'] = $cabang->kota;
            } else {
                $cabang = Cabang::find($storeData['id_cabang_pickup']);
                if (is_null($cabang)) {
                    return response([
                        'message' => 'Cabang Not Found'
                    ], 404);
                }
                $storeData['pickup'] = $cabang->kota;
                $cabang = Cabang::find($storeData['id_cabang_dropoff']);
                if (is_null($cabang)) {
                    return response([
                        'message' => 'Cabang Not Found'
                    ], 404);
                }
                $storeData['dropoff'] = $cabang->kota;
            }

            $storeData['mobil'] = $mobil->nama;
            $storeData['peminjam'] = $user->nama;
            $storeData['status'] = 'berjalan';

            //Update status mobil disewa
            $mobil->disewa = 1;
            $mobil->save();

            //Update status user menyewa
            $user->menyewa = 1;
            $user->save();

            $transaksi = Transaksi::create($storeData);

            return response()->json([
                "status" => true,
                "message" => 'Berhasil insert data',
                "data" => $transaksi
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
            $transaksi = Transaksi::find($id);

            if (!$transaksi) throw new \Exception("Transaksi tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $transaksi
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400); //status code 400 = bad request
        }
    }

    public function showTransaksiByUserAndStatus($status)
    {
        try {
            $idUser = Auth::user()->id;
            $transaksi = Transaksi::where('id_peminjam', $idUser)
                ->where('status', $status)
                ->get();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil ambil data',
                "data" => $transaksi
            ], 200); //status code 200 = success
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 404); //status code 404 = not found or bad request
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request,  $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:berjalan,selesai,batal,dinilai',
            ]);
            $transaksi = Transaksi::find($id);
            if (!$transaksi) throw new \Exception("Transaksi tidak ditemukan");

            $transaksi->status = $request->status;
            $transaksi->save();

            if ($request->status === 'selesai' || $request->status === 'batal') {
                $mobil = Mobil::find($transaksi->id_mobil);
                if (!$mobil) throw new \Exception("Mobil tidak ditemukan");
                $user = User::find($transaksi->id_peminjam);
                if (!$user) throw new \Exception("User tidak ditemukan");
                $mobil->disewa = 0;
                if ($request->status === 'batal') {
                    $mobil->id_cabang = $transaksi->id_cabang_pickup;
                } else {
                    $mobil->id_cabang = $transaksi->id_cabang_dropoff;
                }
                $mobil->save();
                $user->menyewa = 0;
                $user->save();
            }

            return response()->json([
                "status" => true,
                "message" => 'Berhasil update data',
                "data" => $transaksi
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
            $transaksi = Transaksi::find($id);

            if (!$transaksi) throw new \Exception("Transaksi tidak ditemukan");

            $transaksi->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil hapus data',
                "data" => $transaksi
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
