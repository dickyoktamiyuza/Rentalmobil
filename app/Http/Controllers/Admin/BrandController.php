<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Brands::query();

            if ($request->input('trashed')) {
                $query->onlyTrashed();
            }
            return DataTables::of($query)
                ->addColumn('action', function ($brand) use ($request) {
                    $actionHtml = '';

                    if ($request->input('trashed')) {
                        // Tampilan aksi untuk brand yang dihapus
                        $actionHtml .= '
                            <div class="flex flex-row items-center justify-center">
                                <button class="restore-button px-2 py-1 text-xs text-white transition duration-500 bg-green-500 border border-green-500 rounded-md select-none ease hover:bg-green-600 focus:outline-none focus:shadow-outline mr-2" 
                                    type="button" data-restore-url="' . route('admin.brands.restore', $brand->id) . '">
                                    Pulihkan
                                </button>
                                <button class="delete-button px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline"
                                type="button" data-delete-url="' . route('admin.brands.deletePermanent', $brand->id) . '"
                                data-delete-type="permanent">
                                Hapus
                                </button>
                            </div>';
                    } else {
                        // Tampilan aksi untuk brand yang aktif
                        $actionHtml .= '
                            <div class="flex flex-row items-center justify-center">
                                <a class="block px-2 py-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline mr-2" 
                                    href="' . route('admin.brands.edit', $brand->id) . '">
                                    Sunting
                                </a>
                                <button class="delete-button px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline"
                                    type="button" data-delete-url="' . route('admin.brands.destroy', $brand->id) . ' "data-delete-type="soft">
                                    Hapus
                                </button>
                                ' . method_field('delete') . csrf_field() . '
                            </div>';
                    }

                    return $actionHtml;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make();
        }

        return view('admin.brands.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($data['name'] . '-' . Str::lower(Str::random(5)));
        Brands::create($data);
        return redirect()->route('admin.brands.index')->with('success', 'Brand Berhasil Di tambahkah');
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
    public function edit(Brands $brand)
    {
        return view('admin.brands.edit', [
            'brand' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brands $brand)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brands $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand Berhasil Di Hapus');
    }

    public function restore($id)
    {
        Brands::withTrashed()->find($id)->restore();
        return redirect()->route('admin.brands.index');
    }

    // Metode untuk menghapus brand secara permanen
    public function deletePermanent($id)
    {
        Brands::withTrashed()->find($id)->forceDelete();
        // dd('restore method executed');
        return redirect()->route('admin.brands.index');
    }
}
