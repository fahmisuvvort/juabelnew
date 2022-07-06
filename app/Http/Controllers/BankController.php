<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['success' => true, 'results' => BankAccount::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => ['required'],
            'bank_office' => ['required'],
            'account_name' => ['required'],
            'account_number' => ['required'],
        ]);

        BankAccount::create([
            'bank_name' => $request->bank_name,
            'bank_office' => $request->bank_office,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
        ]);

        return response(['success' => true], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => ['required'],
            'bank_office' => ['required'],
            'account_name' => ['required'],
            'account_number' => ['required'],
        ]);
        $bank = BankAccount::findOrFail($id);

        $bank->update([
            'bank_name' => $request->bank_name,
            'bank_office' => $request->bank_office,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
        ]);

        return response(['success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank = BankAccount::findOrFail($id);

        $bank->delete();

        return response(['success' => true], 200);
    }
}
