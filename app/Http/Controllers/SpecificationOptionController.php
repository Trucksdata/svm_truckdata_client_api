<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecificationOptionRequest;
use App\Http\Requests\UpdateSpecificationOptionRequest;
use App\Models\Specification;
use App\Models\SpecificationOption;
use Illuminate\Http\Request;

class SpecificationOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate(['specification_id' => 'required|exists:specifications,id']);
        $limit = $request->limit ?: 100;
        return SpecificationOption::where('specification_id', $request->specification_id)->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSpecificationOptionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpecificationOptionRequest $request)
    {
        $specification = Specification::findOrFail($request->specification_id);

        if ($request->options) {
            foreach ($request->options as $option) {
                $parentOption = $specification->options()->create([
                    'option' => $option['option'],
                    'parent_option_id' => isset($option['parent_option_id']) ? $option['parent_option_id'] : null
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

        return $this->successResponse('created', ['data' => $specification->options]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SpecificationOption  $specificationOption
     * @return \Illuminate\Http\Response
     */
    public function show(SpecificationOption $specificationOption)
    {
        return $specificationOption->load('specification');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpecificationOptionRequest  $request
     * @param  \App\Models\SpecificationOption  $specificationOption
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpecificationOptionRequest $request, SpecificationOption $specificationOption)
    {
        $specificationOption->update($request->validated());
        return $this->successResponse('updated', ['data' => $specificationOption]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpecificationOption  $specificationOption
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecificationOption $specificationOption)
    {
        $specificationOption->delete();

        return $this->successResponse('deleted');
    }
}
