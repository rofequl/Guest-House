<?php

namespace App\Http\Controllers\API;

use App\income_source;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncomesourceController extends Controller
{
    public function index()
    {
        return income_source::latest()->paginate(10);
    }

    public function IncomeSourceAll()
    {
        return income_source::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'Required|max:191|unique:income_sources,name,',
        ]);

        $insert = new income_source();
        $insert->name = $request->name;
        $insert->save();
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'Required|max:191|unique:income_sources,name,'.$id,
        ]);

        $insert = income_source::findOrFail($id);
        $insert->name = $request->name;
        $insert->save();
    }

    public function destroy($id)
    {
        $department = income_source::findOrFail($id);
        $department->delete();
        return ['message'=>'User deleted'];
    }
}
