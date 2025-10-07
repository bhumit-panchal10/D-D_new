<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRegister;
use Carbon\Carbon;
use App\Models\Expense;


class ExpenseController extends Controller
{
    // Display all maintenance records
    public function index()
    {
        $Expenses = Expense::where(['clinic_id' => auth()->user()->clinic_id])->orderBy('created_at', 'desc')->paginate(config('app.per_page'));
        return view('Expense.index', compact('Expenses'));
    }

    // Show create form
    public function create()
    {
        return view('Expense.create');
    }

    // Store new maintenance record
    public function store(Request $request)
    {

        $request->validate([
            'item_name'             => 'required|string|max:50',
            'amount'      => 'required|numeric|min:0',
            'enter_by'             => 'required|string|max:50',
            'mode'             => 'required',
        ]);

        $data = $request->all();
        $data['clinic_id'] = auth()->user()->clinic_id;

        Expense::create($data);


        return redirect()->route('Expense.index')->with('success', 'Expense added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $Expenses = Expense::findOrFail($id);
        return view('Expense.edit', compact('Expenses'));
    }

    // Update maintenance record
    public function update(Request $request, $id)
    {

        $request->validate([
            'item_name'             => 'required|string|max:50',
            'amount'      => 'required|numeric|min:0',
            'enter_by'             => 'required|string|max:50',
            'mode'             => 'required',
        ]);
        $Expense = Expense::findOrFail($id);
        $Expense->update($request->all());

        return redirect()->route('Expense.index')->with('success', 'Expense updated successfully.');
    }

    // Delete maintenance record
    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return redirect()->route('Expense.index')->with('success', 'Expense record deleted successfully.');
    }
}
