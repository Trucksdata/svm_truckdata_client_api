<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHomePageSettingRequest;
use App\Http\Requests\UpdateHomePageSettingRequest;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;

class HomePageSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'super_admin'])->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?: 15;
        return HomePageSetting::paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreHomePageSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHomePageSettingRequest $request)
    {
        $data = HomePageSetting::create($request->validated());
        return $this->successResponse(['message' => 'created', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HomePageSetting  $homePageSetting
     * @return \Illuminate\Http\Response
     */
    public function show(HomePageSetting $homePageSetting)
    {
        return $homePageSetting;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHomePageSettingRequest  $request
     * @param  \App\Models\HomePageSetting  $homePageSetting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHomePageSettingRequest $request, HomePageSetting $homePageSetting)
    {
        $homePageSetting->update($request->validated());
        return $this->successResponse(['message' => 'updated', 'data' => $homePageSetting]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomePageSetting  $homePageSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomePageSetting $homePageSetting)
    {
        $homePageSetting->delete();
        return $this->successResponse(['message' => 'deleted']);
    }
}
