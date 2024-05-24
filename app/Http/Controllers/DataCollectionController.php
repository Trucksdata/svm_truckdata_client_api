<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataCollectionRequest;
use App\Http\Requests\UpdateDataCollectionRequest;
use App\Models\DataCollection;
use Illuminate\Http\Request;

class DataCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?: 15;
        return DataCollection::paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDataCollectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDataCollectionRequest $request)
    {
        $data = DataCollection::create($request->validated());
        return $this->successResponse(['message' => 'created', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataCollection  $dataCollection
     * @return \Illuminate\Http\Response
     */
    public function show(DataCollection $dataCollection)
    {
        return $dataCollection;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDataCollectionRequest  $request
     * @param  \App\Models\DataCollection  $dataCollection
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDataCollectionRequest $request, DataCollection $dataCollection)
    {
        $dataCollection->update($request->validated());
        return $this->successResponse(['message' => 'updated', 'data' => $dataCollection]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataCollection  $dataCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataCollection $dataCollection)
    {
        $dataCollection->delete();
        return $this->successResponse(['message' => 'deleted']);
    }
}
