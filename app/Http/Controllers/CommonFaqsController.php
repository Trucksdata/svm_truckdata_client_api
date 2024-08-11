<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommonFaqsRequest;
use App\Http\Requests\UpdateCommonFaqsRequest;
use App\Models\CommonFaq;
use Illuminate\Http\Request;

class CommonFaqsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','super_admin'])->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?: 15;
        return  CommonFaq::orderBy('created_at','desc')->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommonFaqsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommonFaqsRequest $request)
    {
        CommonFaq::truncate();
        // $commonFaq =  CommonFaq::create($request->validated());
        // return $this->successResponse(['message' => 'created', 'data' => $commonFaq]);
        $data = $request->validate([
            '*.question' => 'required|string',
            '*.answer' => 'required|string',
        ]);

        $faqs = CommonFaq::insert($data);

        return response()->json(['message' => 'FAQs created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommonFaq  $commonFaq
     * @return \Illuminate\Http\Response
     */
    public function show(CommonFaq $commonFaq)
    {
        return $commonFaq;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommonFaqRequest  $request
     * @param  \App\Models\CommonFaq  $commonFaq
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommonFaqRequests $request, CommonFaq $commonFaq)
    {
        $commonFaq->update($request->validated());
        return $this->successResponse(['message' => 'updated', 'data' => $commonFaq]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommonFaq  $commonFaq
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommonFaq $commonFaq)
    {
        $commonFaq->delete();
        return $this->successResponse(['message' => 'deleted']);
    }
}
