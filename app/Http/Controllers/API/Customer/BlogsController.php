<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Customer\BlogDetailResource;
use App\Http\Resources\Customer\BlogListResource;
use App\Models\Blog;

class BlogsController extends Controller
{
    /**
     * Display all blogs with only title, creator, created date and thumbnail only for customer.
     */
    public function getAllBlogs()
    {
        return response()->json([
            'message' => 'All blogs fetched successfully',
            'data' => BlogListResource::collection(Blog::all()),
        ], 200); 
    }

    /**
     * Display requested blog with full details for customer.
     */
    public function getBlogDetails($id)
    {
        $blog = Blog::find($id);
        if(!$blog){
            return response()->json([
                'message' => 'Blog not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Blog details fetched successfully',
            'data' => new BlogDetailResource($blog),
        ], 200);
    }

}
