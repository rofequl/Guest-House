<?php

namespace App\Http\Controllers\API;

use App\expenditure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenditureController extends Controller
{

    public function index()
    {
        return expenditure::latest()->paginate(10);
    }

    public function ExpenditureAll()
    {
        return expenditure::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'Required|max:191|unique:expenditures,name,',
        ]);

        $insert = new expenditure();
        $insert->name = $request->name;
        $insert->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->validate($request, [
            'name' => 'Required|max:191|unique:expenditures,name,'.$id,
        ]);

        $insert = expenditure::findOrFail($id);
        $insert->name = $request->name;
        $insert->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = expenditure::findOrFail($id);
        $department->delete();
        return ['message'=>'User deleted'];
    }
}
