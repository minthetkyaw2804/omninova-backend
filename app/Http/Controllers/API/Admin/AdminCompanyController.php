<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\CompanyResource;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\CompanySocialMedia;
use App\Models\CompanyContact;

class AdminCompanyController extends Controller
{
    //Get Company Details
    public function getCompanyDetails(){
        return response()->json([
            'message' => 'Company details fetched successfully',
            'data' => new CompanyResource(Company::first()),
        ], 200);
    }

    //Edit Company Details
    public function editCompanyDetails(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'vision' => 'required',
            'goal' => 'required',
            'founded_date' => 'required|date',
            'address' => 'required',
        ]);

        $user = Auth::user();

        $company = Company::first();

        try{
            $company->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'vision' => $validatedData['vision'],
                'goal' => $validatedData['goal'],
                'founded_date' => $validatedData['founded_date'],
                'address' => $validatedData['address'],
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Company details updated successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Company details update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Replace Company Logo
    public function replaceCompanyLogo(Request $request){
        $validatedData = $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user = Auth::user();

        $company = Company::first();

        try{
            $oldLogo = basename($company->logo_url);
            $path = public_path('images/company/' . $oldLogo);
            if(File::exists($path)){
            File::delete($path);
            }

            $extension = $request->file('logo')->getClientOriginalExtension();
            $logoName = 'company_logo.' . $extension;
            $request->file('logo')->move(public_path('images/company'), $logoName);
            $logoUrl = url('images/company/' . $logoName);

            $company->update([
                'logo_url' => $logoUrl,
                'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Company logo updated successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Company logo update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Add Company Social Media
    public function addCompanySocialMedia(Request $request){
        $validatedData = $request->validate([
            'platform_name' => 'required',
            'page_url' => 'required|url',
        ]);

        $user = Auth::user();

        $company = Company::first();

        try{
            DB::beginTransaction();
            $newSocial = $company->companySocialMedias()->create([
            'platform_name' => $validatedData['platform_name'],
            'page_url' => $validatedData['page_url'],
            'created_user_id' => $user->id,
            ]);

            $company->update([
                'updated_user_id' => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Company social media added successfully',
                'data' => $newSocial,
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Company social media add failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Edit Company Social Media
    public function editCompanySocialMedia(Request $request, $id){
        $validatedData = $request->validate([
            'platform_name' => 'required',
            'page_url' => 'required|url',
        ]);

        $user = Auth::user();

        $companySocialMedia = CompanySocialMedia::find($id);

        if(!$companySocialMedia){
            return response()->json([
                'message' => 'Company social media not found',
            ], 404);
        }

        try{
            DB::beginTransaction();

            $companySocialMedia->update([
                'platform_name' => $validatedData['platform_name'],
                'page_url' => $validatedData['page_url'],
            ]);

            $companySocialMedia->company()->update([
                'updated_user_id' => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Company social media updated successfully',
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                    'message' => 'Company social media edit failed',
                    'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Delete Company Social Media
    public function deleteCompanySocialMedia($id){
        $companySocialMedia = CompanySocialMedia::find($id);

        if(!$companySocialMedia){
            return response()->json([
                'message' => 'Company social media not found',
            ], 404);
        }

        $user = Auth::user();

        try{
            DB::beginTransaction();

            $companySocialMedia->company()->update([
                'updated_user_id' => $user->id,
            ]);

            $companySocialMedia->delete();

            DB::commit();

            return response()->json([
                'message' => 'Company social media deleted successfully',
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Company social media delete failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Add Company Contact
    public function addCompanyContact(Request $request){
        $validatedData = $request->validate([
            'department' => 'required',
            'phone_number' => 'required',
        ]);

        $user = Auth::user();

        $company = Company::first();

        try{
            DB::beginTransaction();

            $newContact = $company->companyContacts()->create([
                'department' => $validatedData['department'],
                'phone_number' => $validatedData['phone_number'],
            ]);

            $company->update([
                'updated_user_id' => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Company contact added successfully',
                'data' => $newContact,
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Company contact add failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Edit Company Contact
    public function editCompanyContact(Request $request, $id){
        $validatedData = $request->validate([
            'department' => 'required',
            'phone_number' => 'required',
        ]);

        $companyContact = CompanyContact::find($id);

        if(!$companyContact){
            return response()->json([
                'message' => 'Company contact not found',
            ], 404);
        }

        $user = Auth::user();

        try{
            DB::beginTransaction();
            
            $companyContact->update([
                'department' => $validatedData['department'],
                'phone_number' => $validatedData['phone_number'],
            ]);

            $companyContact->company()->update([
                'updated_user_id' => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Company contact updated successfully',
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Company contact update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Delete Company Contact
    public function deleteCompanyContact($id){
        $companyContact = CompanyContact::find($id);

        if(!$companyContact){
            return response()->json([
                'message' => 'Company contact not found',
            ], 404);
        }
        $user = Auth::user();
        try{
            DB::beginTransaction();

            $companyContact->company()->update([
                'updated_user_id' => $user->id,
            ]);

            $companyContact->delete();

            DB::commit();

            return response()->json([
                'message' => 'Company contact deleted successfully',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Company contact delete failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Add Company Details
    public function addCompanyDetails(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'vision' => 'required',
            'goal' => 'required',
            'founded_date' => 'required|date',
            'address' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user = Auth::user();

        try{
            $logoUniqueName = uniqid() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images/company'), $logoUniqueName);
            $logoUrl = url('images/company/' . $logoUniqueName);

            $newCompany = Company::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'vision' => $validatedData['vision'],
            'goal' => $validatedData['goal'],
            'founded_date' => $validatedData['founded_date'],
            'address' => $validatedData['address'],
            'logo_url' => $logoUrl,
            'creator_user_id' => $user->id,
            'updated_user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Company details added successfully',
                'data' => $newCompany,
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Company details add failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
