<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecificationRequest;
use App\Http\Requests\UpdateSpecificationRequest;
use App\Models\Specification;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?: 100;
        return Specification::with(['category', 'options'])->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSpecificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpecificationRequest $request)
    {
        $specification = Specification::create($request->validated());
        if ($request->options) {
            foreach ($request->options as $option) {
                $parentOption = $specification->options()->create([
                    'option' => $option['option']
                ]);
                if (isset($option['child_options'])) {
                    foreach ($option['child_options'] as $childOption) {
                        $specification->options()->create([
                            'option' => $childOption['option'],
                            'parent_option_id' => $parentOption->id
                        ]);
                    }
                }
            }
        }

        return $this->successResponse('created', ['data' => $specification->load('options')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function show(Specification $specification)
    {
        return $specification->load(['category', 'options']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpecificationRequest  $request
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpecificationRequest $request, Specification $specification)
    {
        $specification->update($request->validated());
        return $this->successResponse('updated', ['data' => $specification]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specification $specification)
    {
        $specification->forceDelete();

        return $this->successResponse('deleted');
    }
}
