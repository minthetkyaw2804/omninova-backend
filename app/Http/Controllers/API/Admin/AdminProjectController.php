<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\ProjectResource;
use App\Models\CompanyProject;
use App\Http\Resources\Admin\ProjectTypeResource;
use App\Models\FeatureImage;
use App\Models\ProjectFeature;
use App\Models\ProjectType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminProjectController extends Controller
{
    // Get all projects
    public function getAllProjects(){
        return response()->json([
            'message' => 'All Projects fetched successfully',
            'data' => ProjectResource::collection(CompanyProject::all()),
        ], 200);
    }

    // Get a specific project
    public function getProject($id){
        $project = CompanyProject::find($id);

        if(!$project){
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json([
            'message' => 'Project details fetched successfully',
            'data' => new ProjectResource($project),
        ], 200);
    }

    // Get all project types
    public function getAllProjectTypes(){
        return response()->json([
            'message' => 'All Project Types fetched successfully',
            'data' => ProjectTypeResource::collection(ProjectType::all()),
        ], 200);
    }

    // Get a specific project type
    public function getProjectType($id){
        $projectType = ProjectType::find($id);

        if(!$projectType){
            return response()->json(['message' => 'Project type not found'], 404);
        }

        return response()->json([
            'message' => 'Project Type details fetched successfully',
            'data' => new ProjectTypeResource($projectType),
        ], 200);
    }

    // Create a new project
    public function createProject(Request $request){
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required',
            'project_type_id' => 'required|exists:project_types,id',
            'description' => 'required',
            'demo_url' => 'required|url',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try{
            $thumbnailUniqueName = uniqid() . '_' . $request->file('thumbnail')->getClientOriginalName();

            $request->file('thumbnail')->move(public_path('images/projects'), $thumbnailUniqueName);
            $thumbnailUrl = url('images/projects/' . $thumbnailUniqueName);
            
            $project = CompanyProject::create([
                'name' => $validatedData['name'],
                'project_type_id' => $validatedData['project_type_id'],
                'description' => $validatedData['description'],
                'demo_url' => $validatedData['demo_url'],
                'thumbnail_url' => $thumbnailUrl,
                'creator_user_id' => $user->id,
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Project created successfully',
                'data' => $project,
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Project creation failed',
                'error' => $e->getMessage(),
            ], 500);    
        }
    }

    // Create a new project type
    public function createProjectType(Request $request){
        $user = Auth::user();

        $validatedData = $request->validate([
            'type_name' => 'required|unique:project_types,type_name',
            'description' => 'required',
        ]);

        try{
            $projectType = ProjectType::create([
                'type_name' => $validatedData['type_name'],
                'description' => $validatedData['description'],
                'creator_user_id' => $user->id,
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Project type created successfully',
                'data' => $projectType,
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Project type creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Create Project Features
    public function createProjectFeatures(Request $request, $id){
        $project = CompanyProject::find($id);

        if(!$project){
            return response()->json(['message' => 'Project not found'], 404);
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg', 
        ]);

        try{
            DB::beginTransaction();

            $projectFeature = $project->projectFeatures()->create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
            ]);

            foreach($validatedData['images'] as $image){
                $imageOriginalName = $image->getClientOriginalName();
                $imageUniqueName = uniqid() . '_' . $imageOriginalName;
                $image->move(public_path('images/projects'), $imageUniqueName);
                $imageUrl = url('images/projects/' . $imageUniqueName);

                $projectFeature->featureImages()->create([
                    'image_name' => $imageOriginalName,
                    'image_url' => $imageUrl,
                ]);
            }

            $project->update([
                'updated_user_id' => $user->id,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Project feature created successfully',
                'data' => $projectFeature,
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Project feature creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Edit project type
    public function editProjectType(Request $request, $id){
        $projectType = ProjectType::find($id);

        if(!$projectType){
            return response()->json(['message' => 'Project type not found'], 404);
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'type_name' => 'requiredunique:project_types,type_name',
            'description' => 'required',
        ]);

        try{
            $projectType->update([
                'type_name' => $validatedData['type_name'],
                'description' => $validatedData['description'],
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Project type updated successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Project type update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete a specific project type
    public function deleteProjectType($id){
        $projectType = ProjectType::find($id);

        if(!$projectType){
            return response()->json(['message' => 'Project type not found'], 404);
        }

        try{
            DB::beginTransaction();
            if ($projectType->companyProjects && $projectType->companyProjects->count() > 0) {
                foreach ($projectType->companyProjects as $project) {
                    if ($project->projectFeatures && $project->projectFeatures->count() > 0) {
                        foreach ($project->projectFeatures as $feature) {
                            if ($feature->featureImages && $feature->featureImages->count() > 0) {
                                $feature->featureImages()->delete(); 
                            }
                            $feature->delete();
                        }
                    }
                    $project->delete();
                }
            }
            $projectType->delete();
            DB::commit();
            return response()->json([
                'message' => 'Project type and its children deleted successfully',
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Project type deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete Project (with features + images)
    public function deleteProject($id)
    {
        $project = CompanyProject::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            DB::beginTransaction();

            // Soft delete children only if they exist
            if ($project->projectFeatures && $project->projectFeatures->count() > 0) {
                foreach ($project->projectFeatures as $feature) {
                    if ($feature->featureImages && $feature->featureImages->count() > 0) {
                        $feature->featureImages()->delete();
                    }
                    $feature->delete();
                }
            }
            $project->delete();
            DB::commit();
            return response()->json([
                'message' => 'Project and its children deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Project deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Delete Project Feature
    public function deleteProjectFeature($id){
        $projectFeature = ProjectFeature::find($id);
        if(!$projectFeature){
            return response()->json(['message' => 'Project feature not found'], 404);
        }
    
        try{
            DB::beginTransaction();
            if ($projectFeature->featureImages && $projectFeature->featureImages->count() > 0) {
                $projectFeature->featureImages()->delete();
            }
            $projectFeature->delete();
            DB::commit();
            return response()->json([
                'message' => 'Project feature deleted successfully',
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Project feature deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    //Edit Project Details
    public function editProjectDetails(Request $request, $id){
        $project = CompanyProject::find($id);

        if(!$project){
            return response()->json(['message' => 'Project not found'], 404);
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required',
            'project_type_id' => 'required|exists:project_types,id',
            'description' => 'required',
            'demo_url' => 'required|url',
        ]);

        try{
            $project->update([
                'name' => $validatedData['name'],
                'project_type_id' => $validatedData['project_type_id'],
                'description' => $validatedData['description'],
                'demo_url' => $validatedData['demo_url'],
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Project details updated successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Project details update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Replace Project Thumbnail
    public function replaceProjectThumbnail(Request $request, $id){
        $project = CompanyProject::find($id);

        if(!$project){
            return response()->json(['message' => 'Project not found'], 404);
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try{
            $thumbnailUniqueName = uniqid() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $request->file('thumbnail')->move(public_path('images/projects'), $thumbnailUniqueName);
            $thumbnailUrl = url('images/projects/' . $thumbnailUniqueName);

            $deletingThumbnail = basename($project->thumbnail_url);
            $path = public_path('images/projects/' . $deletingThumbnail);
            File::delete($path);

            $project->update([
                'thumbnail_url' => $thumbnailUrl,
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Project thumbnail updated successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Project thumbnail update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Edit Project Feature
    public function editProjectFeatures(Request $request, $id){
        $projectFeature = ProjectFeature::find($id);

        if(!$projectFeature){
            return response()->json(['message' => 'Project feature not found'], 404);
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        try{
            DB::beginTransaction();

            $projectFeature->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
            ]);

            $projectFeature->companyProject()->update([
                'updated_user_id' => $user->id,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Project feature updated successfully',
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Project feature update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Add Project Features Images
    public function addProjectFeaturesImages(Request $request, $id){
        $projectFeature = ProjectFeature::find($id);

        if(!$projectFeature){
            return response()->json(['message' => 'Project feature not found'], 404);
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg', 
        ]);

        try{
            DB::beginTransaction();

            foreach($validatedData['images'] as $image){
                $imageOriginalName = $image->getClientOriginalName();
                $imageUniqueName = uniqid() . '_' . $imageOriginalName;
                $image->move(public_path('images/projects'), $imageUniqueName);
                $imageUrl = url('images/projects/' . $imageUniqueName);

                $projectFeature->featureImages()->create([
                    'image_name' => $imageOriginalName,
                    'image_url' => $imageUrl,
                ]);
            }

            $projectFeature->companyProject()->update([
                'updated_user_id' => $user->id,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Project feature images added successfully',
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Project feature images addition failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    //Delete Project Features Images
    public function deleteProjectFeaturesImages($id){
        $projectFeatureImage = FeatureImage::find($id);

        if(!$projectFeatureImage){
            return response()->json(['message' => 'Project feature image not found'], 404);
        }

        try{
            $user = Auth::user();

            $deletingImage = basename($projectFeatureImage->image_url);
            $path = public_path('images/projects/' . $deletingImage);
            File::delete($path);

            $projectFeatureImage->delete();

            return response()->json([
                'message' => 'Project feature image deleted successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Project feature image deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
