<?php

namespace App\Http\Controllers\API;

use App\credit;
use App\debit;
use App\payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class PaymentController extends Controller
{
    public function index()
    {
        return payment::latest()->paginate(10);
    }

    public function PaymentAll()
    {
        return payment::all();
    }

    public function PaymentReport(Request $request)
    {
        $action = 'hide';
        $debit = debit::select('date', DB::raw('SUM(amount) as total_debit'));
        $credit = credit::select('date', DB::raw('SUM(amount) as total_credit'));
        if ($request->payment_id != null) {
            $debit = $debit->where('payment_id', $request->payment_id);
            $credit = $credit->where('payment_id', $request->payment_id);
            $action = 'show';
        }
        if ($request->month != null) {
            $month = $request->month;
            $month = explode("-", $month);
            $credit = $credit->whereYear('date', '=', $month[1])->whereMonth('date', '=', $month[0]);
            $debit = $debit->whereYear('date', '=', $month[1])->whereMonth('date', '=', $month[0]);
            $action = 'show';
        }

        $debit = $debit->groupBy('date')->get()->toArray();
        $credit = $credit->groupBy('date')->get()->toArray();
        $data = array_merge($debit, $credit);
        $collectionOld = new Collection($data);
        $collectionOld = $collectionOld->sortByDesc('date');
        $collection = new Collection();

        foreach ($collectionOld as $collectionOlds) {
            $filtered = $collection->where('date', $collectionOlds['date']);
            if ($filtered->isNotEmpty()) {
                $keys = $filtered->keys()->first();
                if (isset($collectionOlds['total_credit'])) {
                    $collection[$keys] += ['total_credit' => $collectionOlds['total_credit']];
                }
                if (isset($collectionOlds['total_debit'])) {
                    $collection[$keys] += ['total_debit' => $collectionOlds['total_debit']];
                }
            } else {
                if (isset($collectionOlds['total_credit'])) {
                    $collection->push(['date' => $collectionOlds['date'], 'total_credit' => $collectionOlds['total_credit']]);
                }
                if (isset($collectionOlds['total_debit'])) {
                    $collection->push(['date' => $collectionOlds['date'], 'total_debit' => $collectionOlds['total_debit']]);
                }
            }
        }

        foreach ($collection as $collections) {
            $filtered = $collection->where('date', $collections['date']);
            $keys = $filtered->keys()->first();
            if (!isset($collections['total_credit'])) {
                $collection[$keys] += ['total_credit' => 0];
            }
            if (!isset($collections['total_debit'])) {
                $collection[$keys] += ['total_debit' => 0];
            }
            $collection[$keys] += ['profit_loss' => $collection[$keys]['total_credit'] - $collection[$keys]['total_debit']];
        }

        $output = array(
            'sum' => $collection->sum('profit_loss'),
            'data' => $collection,
            'action' => $action,
        );
        return json_encode($output);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'Required|max:191|unique:payments,name,',
        ]);

        $insert = new payment();
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
            'name' => 'Required|max:191|unique:payments,name,' . $id,
        ]);

        $insert = payment::findOrFail($id);
        $insert->name = $request->name;
        $insert->save();
    }

    public function destroy($id)
    {
        $department = payment::findOrFail($id);
        $department->delete();
        return ['message' => 'User deleted'];
    }
}
