<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bookings;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Bookings::with(['item.brand', 'user']);

            return DataTables::of($query)
                ->addColumn('action', function ($booking) {
                    return '
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('admin.booking.edit', $booking->id) . '">
                            Sunting
                        </a>
                        <form class="block w-full" onsubmit="return confirm(\'Apakah anda yakin?\');" -block" action="' . route('admin.booking.destroy', $booking->id) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Hapus
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('admin.booking.index');
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
    public function store(Request $request)
    {
        //
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
    public function edit(Bookings $bookings)
    {
        return view('admin.booking.edit', compact('bookings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingRequest $request, Bookings $booking)
    {
        $data = $request->all();
        $booking->update($data);
        return redirect()->route('admin.booking.index')->with('succes', 'Data Berhasil Di edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookings $booking)
    {
        $booking->delete();
        return redirect()->route('admin.booking.index');
    }
}
