<?php

namespace App\Http\Controllers;

use App\Models\SpecificationCategory;
use Illuminate\Http\Request;

class SpecificationCategoryController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->limit ?: 100;
        return SpecificationCategory::with('specifications')->paginate($limit);
    }

    public function show(SpecificationCategory $specificationCategory)
    {
        return $specificationCategory;
    }
}
