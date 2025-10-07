<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::where(['clinic_id' => auth()->user()->clinic_id])->orderBy('created_at', 'desc')->paginate(config('app.per_page')); // Show latest first
        return view('vendor.index', compact('vendors'));
    }


    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $clinicId = auth()->user()->clinic_id;
        $request->validate([
            'company_name'          => 'required|string|min:3|max:100',
            'contact_person_name'   => 'required|string|min:3|max:100',
            'mobile' => [
                'required',
                'digits:10',
                Rule::unique('vendors')->where(function ($query) use ($clinicId) {
                    return $query->where('clinic_id', $clinicId);
                }),
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('vendors')->where(function ($query) use ($clinicId) {
                    return $query->where('clinic_id', $clinicId);
                }),
            ],
            'address' => 'nullable|string|max:255',
        ], [
            'mobile.unique' => 'This mobile number is already in use within your clinic.',
            'email.unique'  => 'This email ID is already in use within your clinic.',
        ]);
        $data = $request->all();
        $data['clinic_id'] = auth()->user()->clinic_id;

        Vendor::create($data);
        return redirect()->route('vendor.index')->with('success', 'Vendor added successfully.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        $currentPage = request()->input('page', 1); // Get the current page number

        return view('vendor.edit', compact('vendor', 'currentPage'));
    }

    public function update(Request $request, $id)
    {
        $clinicId = auth()->user()->clinic_id;
        $request->validate([
            'company_name'          => 'required|string|min:3|max:100',
            'contact_person_name'   => 'required|string|min:3|max:100',
            'mobile' => [
                'required',
                'digits:10',
                Rule::unique('vendors')->where(function ($query) use ($clinicId) {
                    return $query->where('clinic_id', $clinicId);
                })->ignore($id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('vendors')->where(function ($query) use ($clinicId) {
                    return $query->where('clinic_id', $clinicId);
                })->ignore($id),
            ],
            'address' => 'nullable|string|max:255',
        ], [
            'mobile.unique' => 'This mobile number is already in use within your clinic.',
            'email.unique'  => 'This email ID is already in use within your clinic.',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($request->all());

        // Get the current page number
        $currentPage = request()->input('page', 1);

        return redirect()->route('vendor.index', ['page' => $currentPage])->with('success', 'Vendor updated successfully.');
    }

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}
