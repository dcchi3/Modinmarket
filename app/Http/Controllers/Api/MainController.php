<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\Grandcategory;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class MainController extends Controller
{
    public function categories(){
    	$categories = Category::orderBy('position','ASC')->get();
    	return response()->json(['categories' => $categories]);
    }

    public function subcategories(){
    	$categories = Subcategory::orderBy('position','ASC')->get();
    	return response()->json(['categories' => $categories]);
    }

    public function childcategories(){
    	$categories = Grandcategory::orderBy('position','ASC')->get();
    	return response()->json(['categories' => $categories]);
    }
}
