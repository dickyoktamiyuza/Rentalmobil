<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Types;
use App\Models\Brands;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Item::with(['brand', 'type']);

            return DataTables::of($query)
                ->addColumn('thumbnail', function ($item) {
                    return '<img src="' . $item->thumbnail . '"alt="thumbnail" class="w-10 mx-auto rounded-md">';
                })


                ->addColumn('action', function ($item) {
                    return '
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('admin.item.edit', $item->id) . '">
                            Sunting
                        </a>
                        <form class="block w-full" onsubmit="return confirm(\'Apakah anda yakin?\');" -block" action="' . route('admin.item.destroy', $item->id) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Hapus
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->rawColumns(['action', 'thumbnail'])
                ->make();
        }
        return view('admin.item.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()

    {
        $brands = Brands::all();
        $types = Types::all();
        return view('admin.item.create', compact('brands', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
        //upload multiple
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('assets/item', 'public');

                //push array
                array_push($photos, $photoPath);
            }
            $data['photos'] = json_encode($photos);
        }
        // Check if 'star' exists and is not NULL
        if (!array_key_exists('star', $data)) {
            $data['star'] = null;
        }

        Item::create($data);
        return redirect()->route('admin.item.index');
    }

    /**
     * Display the specified resourc    e.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $item->load('brand', 'type');
        $brands = Brands::all();
        $types = Types::all();
        return view('admin.item.edit', compact('brands', 'types', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, Item $item)
    {
        $data = $request->all();
        // $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
        //upload multiple
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('assets/item', 'public');

                //push array
                array_push($photos, $photoPath);
            }
            $data['photos'] = json_encode($photos);
        } else {
            $data['photos'] = $item->photos;
        }
        $item->update($data);
        return redirect()->route('admin.item.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.item.index')->with('success', 'Brand Berhasil Di Hapus');
    }
}
