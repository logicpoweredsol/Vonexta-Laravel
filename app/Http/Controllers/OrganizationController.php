<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Service;
use App\Models\OrganizationServices;
use App\Models\Automation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\UserOrganization;
use DB;

class OrganizationController extends Controller
{
    //

    public function index(Request $request){
        $request->session()->forget('tab');
        $organizations = Organization::all();
        return view('organizations.index',compact('organizations'));
    }

    public function add(){
        return view('organizations.add');
    }

    public function save(Request $request){

        $tab = 'Organization-home';
        $request->session()->put('tab', $tab);

        $validated = $request->validate([
            'name' => 'required|unique:organizations|max:255',
            'phone' => 'required|unique:organizations|max:20',
            'address' => 'required|max:255',
            'city' => 'required|max:150',
            'state' => 'required|max:50',
            'zip' => 'required|max:10',
        ]);
        $organization = new Organization;
        $organization->name = $validated['name'];
        $organization->phone = $validated['phone'];
        $organization->address = $validated['address'];
        $organization->city = $validated['city'];
        $organization->state = $validated['state'];
        $organization->zip = $validated['zip'];
        $organization->address2 = isset($validated['address2']) ? $validated['address2']: "";
        if($organization->save()){
            $request->session()->flash('msg', 'Organization saved successfully');
            return redirect('organizations');
        }

        $request->session()->flash('error', 'Can\'t add organization');
        return back()->withInput();
    }

    public function view(Organization $organization, Request $request){

        $services = Service::all();
        $organizationServices = $organization->services()->get();
        $UserOrganization = UserOrganization::with('organization_user')->where('organization_id',$organization->id)->get();

        return view('organizations.edit',compact('organization','organizationServices', 'services','UserOrganization'));
    }

    public function edit(Organization $organization, Request $request){

        $tab = 'Organization-home';
        $request->session()->put('tab', $tab);

        $organization = Organization::findOrFail($request->id);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('organizations', 'name')->ignore($organization->id),
                'max:255'
            ],
            'phone' => [
                'required',
                Rule::unique('organizations', 'phone')->ignore($organization->id),
                'max:20'
            ],
            'address' => 'required|max:255',
            'city' => 'required|max:150',
            'state' => 'required|max:50',
            'zip' => 'required|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Use fill for mass-assigning attributes
        $organization->fill($request->except('_token', '_method', 'address2','id'));

        // Additional check for 'address2'
        $organization->address2 = $request->has('address2') ? $request->address2 : "";

        if ($organization->save()) {
            $request->session()->flash('msg', 'Organization updated successfully');
            return redirect('organizations');
        }

        $request->session()->flash('error', 'Can\'t update organization');
        return back()->withInput();
    }


}
