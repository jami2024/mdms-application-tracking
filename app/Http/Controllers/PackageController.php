<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{

    public function packagesApplications()
    {
        return view('packages.applications');
    }

    public function finalPackagesApplications()
    {
        return view('final-packages.applications');
    }

}
