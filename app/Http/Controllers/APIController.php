<?php

namespace App\Http\Controllers;

use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Maps;
use App\Models\Areas;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $maps = Maps::get();
            return response()->json(['success' => 'Successfully', "dataMaps" => $maps], 200);
        } catch (\Throwable $th) {
            return response()->json(['Error' => "error"], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // $area->acreage = $request->acreage;
    // $area->perimeter = $request->perimeter;
    public function updateArea(Request $request)
    {
        // return  response()->json(['title' => $request->all()], 200);
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200'
        ]);

        if ($validator->fails()) {
            return  response()->json(['title' => $validator->fails()], 422);
        }
        $update = Areas::where('id', $request->id)
        ->update([
            'title' => $request->title,
            'perimeter' => $request->perimeter,
            'acreage' => $request->acreage,
        ]);
        if ($update) {
            $areas = Areas::get();
            return response()->json(['success' => 'Successfully', 'listAreas' => $areas], 200);
        }
        return  response()->json(['error' => '500'], 500);

    }

    public function createArea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coordinatesSVG' => 'required|max:200',
            'title' => 'required|max:200',
            'map_id' => 'required'
        ]);

        if ($validator->fails()) {
            return  response()->json(['title' => $validator->fails()], 422);
        }

        $area = new Areas;
        $area->title = $request->title;
        $area->map_id = $request->map_id;
        $area->coordinatesSVG = $request->coordinatesSVG;
        $saved = $area->save();
        if ($saved) {
            return response()->json(['success' => 'Successfully'], 200);
        }
        return  response()->json(['error' => '500'], 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return  response()->json(['error' => $request->hasFile('image')], 200);
        if ($request->hasFile('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'required|image:jpeg,png,jpg,gif,svg|max:40560',
                'title' => 'required|unique:maps|max:200',
            ]);

            if ($validator->fails()) {
                return  response()->json($validator->errors(), 422);
            }

            $uploadFolder = 'maps';
            $image = $request->file('image');
            $imageName = $image->store($uploadFolder, 'public');

            if (Storage::disk('public')->exists($imageName)) {
                $url_image = Storage::url("public/" . $imageName);
                $maps = new Maps;
                $maps->title = $request->title;
                $maps->url_image = $url_image;
                $maps->description = $request->description;

                $saved = $maps->save();
                if (!$saved) {
                    Storage::delete("public/" . $imageName);
                    return  response()->json(['error' => '500'], 500);
                }
                $mapsdata = Maps::get();
                return response()->json(['success' => 'Successfully', "dataMaps" => $mapsdata], 200);
            }

            // return response()->json(['Successfully' => $request->all()], 200);
        }
        return response()->json(['Failed' => "Hinh anh la bat buoc"], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $map = Maps::where('id', $id)->get();
            return response()->json(['success' => 'Successfully', "dataMap" => $map], 200);
        } catch (\Throwable $th) {
            return response()->json(['Error' => "error"], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function getListArea()
    {
        $listArea = Areas::get();
        return response()->json(['success' => 'Successfully', "listArea" => $listArea], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
