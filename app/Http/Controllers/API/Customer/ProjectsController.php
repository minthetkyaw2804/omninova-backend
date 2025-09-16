<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Customer\ProjectTypeResource;
use App\Models\ProjectType;
use App\Http\Resources\Customer\ProjectListResource;
use App\Http\Resources\Customer\ProjectDetailResource;
use App\Models\CompanyProject;

class ProjectsController extends Controller
{
    /**
     * Display all project types for the Customer.
     */
    public function getAllProjectTypes(){
        return response()->json([
            'message' => 'All project types fetched successfully',
            'data' => ProjectTypeResource::collection(ProjectType::all()),
        ], 200);
    }

    /**
     * Display all projects for the Customer.
     */
    public function getAllProjects(){
        return response()->json([
            'message' => 'All projects fetched successfully',
            'data' => ProjectListResource::collection(CompanyProject::all()),
        ], 200);
    }

    /**
     * Display a specific project with details for the Customer.
     */
    public function getProjectDetails($id){
        $project = CompanyProject::find($id);
        if(!$project){
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Project details fetched successfully',
            'data' => new ProjectDetailResource($project),
        ], 200);
    }
}
