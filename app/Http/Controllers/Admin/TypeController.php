<?php

namespace App\Http\Controllers\Admin;

use App\Models\Types;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\TypeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Types::query();

            if ($request->input('trashed')) {
                $query->onlyTrashed();
            }
            return DataTables::of($query)
                ->addColumn('action', function ($type) use ($request) {
                    $actionHtml = '';

                    if ($request->input('trashed')) {
                        // Tampilan aksi untuk type yang dihapus
                        $actionHtml .= '
                            <div class="flex flex-row items-center justify-center">
                                <button class="restore-button px-2 py-1 text-xs text-white transition duration-500 bg-green-500 border border-green-500 rounded-md select-none ease hover:bg-green-600 focus:outline-none focus:shadow-outline mr-2" 
                                    type="button" data-restore-url="' . route('admin.type.restore', $type->id) . '">
                                    Pulihkan
                                </button>
                                <button class="delete-button px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline"
                                type="button" data-delete-url="' . route('admin.type.deletePermanent', $type->id) . '"
                                data-delete-type="permanent">
                                Hapus
                                </button>
                            </div>';
                    } else {
                        // Tampilan aksi untuk type yang aktif
                        $actionHtml .= '
                            <div class="flex flex-row items-center justify-center">
                                <a class="block px-2 py-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline mr-2" 
                                    href="' . route('admin.type.edit', $type->id) . '">
                                    Sunting
                                </a>
                                <button class="delete-button px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline"
                                    type="button" data-delete-url="' . route('admin.type.destroy', $type->id) . ' "data-delete-type="soft">
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
        return view('admin.type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($data['name'] . '-' . Str::lower(Str::random(5)));
        Types::create($data);
        return redirect()->route('admin.type.index')->with('success', 'Type Berhasil Di tambahkah');
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
    public function edit(Types $type)
    {
        return view('admin.type.edit', [
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeRequest $request, Types $type)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
        $type->update($data);
        return redirect()->route('admin.type.index')->with('succes', 'Data Berhasil Di edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(types $type)
    {
        $type->delete();
        return redirect()->route('admin.type.index');
    }


    public function restore($id)
    {
        Types::withTrashed()->find($id)->restore();
        return redirect()->route('admin.type.index');
    }

    // Metode untuk menghapus brand secara permanen
    public function deletePermanent($id)
    {
        Types::withTrashed()->find($id)->forceDelete();
        // dd('restore method executed');
        return redirect()->route('admin.type.index');
    }
}
