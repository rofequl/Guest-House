<?php

namespace App\Http\Controllers\API;

use App\credit;
use App\debit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use function Symfony\Component\VarDumper\Dumper\esc;

class ReportController extends Controller
{
    public function index()
    {
        $debit = debit::select('date', DB::raw('SUM(amount) as total_debit'))->groupBy('date')->get()->toArray();
        $credit = credit::select('date', DB::raw('SUM(amount) as total_credit'))->groupBy('date')->get()->toArray();
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

        $chunk = $collection->paginate(10);
        return $chunk;
    }

    public function TotalBalanceSheet(Request $request)
    {
        $debit = debit::select('date', DB::raw('SUM(amount) as total_debit'))->groupBy('date')->get()->toArray();
        $credit = credit::select('date', DB::raw('SUM(amount) as total_credit'))->groupBy('date')->get()->toArray();
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
                $collection[$keys] += ['total_credit' => 0, 'bank_balance' => 0];
            }
            if (!isset($collections['total_debit'])) {
                $collection[$keys] += ['total_debit' => 0];
            }
            $collection[$keys] += ['profit_loss' => $collection[$keys]['total_credit'] - $collection[$keys]['total_debit']];
        }

        $chunk = $collection->sum('profit_loss');
        return $chunk;
    }

    public function month()
    {
        $debit = debit::select(DB::raw('MONTH(date) as month, YEAR(date) as year'))
            ->groupBy(DB::raw('YEAR(date) DESC, MONTH(date) DESC'))->get()->toArray();

        $credit = credit::select(DB::raw('MONTH(date) as month, YEAR(date) as year'))
            ->groupBy(DB::raw('YEAR(date) DESC, MONTH(date) DESC'))->get()->toArray();

        $data = array_unique(array_merge($debit, $credit), SORT_REGULAR);
        return $data;
    }

    public function search(Request $request)
    {
        $action = 'hide';
        $debit = debit::select('date', DB::raw('SUM(amount) as total_debit'));
        $credit = credit::select('date', DB::raw('SUM(amount) as total_credit'));
        if ($request->date != null) {
            $date = DateTime::createFromFormat('d/m/Y', $request->date);
            $debit = $debit->where('date', $date->format('Y-m-d'));
            $credit = $credit->where('date', $date->format('Y-m-d'));
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
}
