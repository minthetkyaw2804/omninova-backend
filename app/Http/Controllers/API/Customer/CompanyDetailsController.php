<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use App\Http\Resources\Customer\CompanyDetailsResource;

class CompanyDetailsController extends Controller
{
    /**
     * Display all the company details (comapny details, logo, aboutus, social media pages and company contacts) for the Customer.
     */
    public function getCompanyDetails()
    {
        $company = Company::with(['companySocialMedias', 'companyContacts'])->first();
        
        return response()->json([
            'message' => 'Company details fetched successfully',
            'data' => new CompanyDetailsResource($company),
        ], 200);
    }
}
