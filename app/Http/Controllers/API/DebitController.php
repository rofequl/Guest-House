<?php

namespace App\Http\Controllers\API;

use App\debit;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DebitController extends Controller
{

    public function index()
    {
        return debit::with('expenditure', 'payment')->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => "Required",
            'voucher_no' => 'Required|unique:debits,voucher_no,',
            'expenditure_id' => 'Required',
            'amount' => 'Required|integer|digits_between:1,10',
            'payment_id' => 'Required',
            'remarks' => 'max:191',
        ]);

        $date = DateTime::createFromFormat('d/m/Y', $request->date);

        $insert = new debit();
        $insert->date = $date->format('Y-m-d');
        $insert->voucher_no = $request->voucher_no;
        $insert->expenditure_id = $request->expenditure_id;
        $insert->amount = $request->amount;
        $insert->payment_id = $request->payment_id;
        $insert->remarks = $request->remarks;
        $insert->save();
    }

    public function DebitMonth()
    {
        return debit::select(DB::raw('MONTH(date) as month, YEAR(date) as year'))
            ->groupBy(DB::raw('YEAR(date) DESC, MONTH(date) DESC'))->get();

    }

    public function SearchDebitReport(Request $request)
    {
        $action = 'hide';
        $credit = debit::with( 'expenditure');
        if ($request->date != null) {
            $date = DateTime::createFromFormat('d/m/Y', $request->date);
            $credit = $credit->where('date', $date->format('Y-m-d'));
            $action = 'show';
        }

        if ($request->month != null) {
            $month = $request->month;
            $month = explode("-", $month);
            $credit = $credit->whereYear('date', '=', $month[1])
                ->whereMonth('date', '=', $month[0]);
            $action = 'show';
        }

        $credit = $credit->latest()->get();

        $output = array(
            'sum' => $credit->sum('amount'),
            'data' => $credit,
            'action' => $action,
        );
        return json_encode($output);

    }

    public function TotalDebit()
    {
        return debit::all()->sum('amount');

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'date' => "Required",
            'voucher_no' => 'Required',
            'expenditure_id' => 'Required',
            'payment_id' => 'Required',
            'amount' => 'Required|integer',
            'remarks' => 'max:191',
        ]);
        $date = DateTime::createFromFormat('d/m/Y', $request->date);
        $insert = debit::findOrFail($id);
        $insert->date = $date->format('Y-m-d');
        $insert->voucher_no = $request->voucher_no;
        $insert->expenditure_id = $request->expenditure_id;
        $insert->payment_id = $request->payment_id;
        $insert->amount = $request->amount;
        $insert->remarks = $request->remarks;
        $insert->save();
    }

    public function destroy($id)
    {
        $department = debit::findOrFail($id);
        $department->delete();
        return ['message' => 'deleted'];
    }
}
