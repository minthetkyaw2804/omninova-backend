<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\BlogResource;
use App\Models\Blog;
use App\Models\BlogImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminBlogsController extends Controller
{
    // Get all blogs
    public function getAllBlogs(){
        return response()->json([
            'message' => 'All Blogs fetched successfully',
            'data' => BlogResource::collection(Blog::all()),
        ], 200);
    }

    // Get a specific blog 
    public function getBlog($id){
        $blog = Blog::find($id);

        if(!$blog){
            return response()->json(['message' => 'Blog not found'], 404);
        }

        return response()->json([
            'message' => 'Blog details fetched successfully',
            'data' => new BlogResource($blog),
        ], 200);        
    }

    // Create a new blog
    public function createBlog(Request $request){
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg', 
        ]);

        $user = Auth::user();

        try{
            DB::beginTransaction();

            $blog = Blog::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'creator_user_id' => $user->id,
                'updated_user_id' => $user->id,
            ]);

            foreach($validatedData['images'] as $image){
                $imageOriginalName = $image->getClientOriginalName();
                $imageUniqueName = uniqid() . '_' . $imageOriginalName;

                $image->move(public_path('images/blogs'), $imageUniqueName);
                $imageUrl = url('images/blogs/' . $imageUniqueName);

                $blog->blogImages()->create([
                    'image_name' => $imageOriginalName,
                    'image_url' => $imageUrl,
                ]);
            };

            DB::commit();

            return response()->json([
                'message' => 'Blog created successfully',
                'data' => new BlogResource($blog),
            ], 201);
        } catch (\Exception $e){
            
            DB::rollBack();

            return response()->json([
                'message' => 'Blog creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Edit a specific blog
    public function editBlog(Request $request, $id){
        $blog = Blog::find($id);

        if(!$blog){
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $user = Auth::user();
        
        try{
            $blog->update([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Blog updated successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Blog update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Add images to a specific blog
    public function addImagesToBlog(Request $request, $id){
        $blog = Blog::find($id);

        if(!$blog){
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $validatedData = $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        try{
            foreach($validatedData['images'] as $image){
                $imageOriginalName = $image->getClientOriginalName();
                $imageUniqueName = uniqid() . '_' . $imageOriginalName;

                $image->move(public_path('images/blogs'), $imageUniqueName);
                $imageUrl = url('images/blogs/' . $imageUniqueName);

                $blog->blogImages()->create([
                    'image_name' => $imageOriginalName,
                    'image_url' => $imageUrl,
                ]);
            }

            $blog->update([
                'updated_user_id' => $user->id,
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Images added to blog successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Images addition failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete a specific blog image
    public function deleteBlogImage($id){
        $blogImage = BlogImage::find($id);

        if(!$blogImage){
            return response()->json(['message' => 'Blog image not found'], 404);
        }

        try{
            $user = Auth::user();
            
            $deletingImage = basename($blogImage->image_url);
            $path = public_path('images/blogs/' . $deletingImage);

            $blogImage->blog()->update([
                'updated_user_id' => $user->id,
                'updated_at' => now(),
            ]);
            
            File::delete($path);
            
            $blogImage->delete();

            return response()->json([
                'message' => 'Blog image deleted successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Blog image deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete a specific blog
    public function deleteBlog($id){
        $blog = Blog::find($id);

        if(!$blog){
            return response()->json(['message' => 'Blog not found'], 404);
        }

        try{
            DB::beginTransaction();

            $blog->blogImages()->delete();
            $blog->delete();

            DB::commit();

            return response()->json([
                'message' => 'Blog deleted successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Blog deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
