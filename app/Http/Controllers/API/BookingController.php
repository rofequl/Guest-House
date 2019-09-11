<?php

namespace App\Http\Controllers\API;

use App\credit;
use Carbon\Carbon;
use App\booking;
use App\room_info;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{

    public function index()
    {
        return booking::with('room_type')->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => "Required|date",
            'booking_no' => 'Required|integer|unique:bookings,booking_no,',
            'room_type_id' => 'Required|integer',
            'member_id' => 'Required|max:191',
            'name' => 'Required|max:191',
            'cell_no' => 'Required|max:191',
            'email' => 'Required|max:191',
            'address' => 'Required',
            'purpose' => 'Required|max:191',
            'no_guest' => 'Required|integer|digits_between:1,11',
            'booking_from' => "Required",
            'booking_to' => "Required",
            'room_rent' => 'Required|integer|digits_between:1,11',
            'room_qty' => 'Required|integer|digits_between:1,11',
            'total_amount' => 'Required|integer|digits_between:1,11',
            'payment_mood' => 'Required|max:191',
        ]);

        $date = new Carbon(DateTime::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'));
        $date1 = new Carbon(DateTime::createFromFormat('d/m/Y', $request->booking_from)->format('Y-m-d'));
        $date2 = new Carbon(DateTime::createFromFormat('d/m/Y', $request->booking_to)->format('Y-m-d'));
        $difference = $date1->diff($date2)->days + 1;

        $room_info = room_info::where('room_type_id', $request->room_type_id)->first();
        $room_qty = $room_info->qty;

        if ($difference == 1) {
            //$room_qty = $room_qty - booking::whereDate('booking_from', $date1)->get()->sum('room_qty');
            $room_qty = $room_qty - booking::whereDate('booking_from', '<=', $date1)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '>=', $date1)->get()->sum('room_qty');
        } else {
            $room_qty = $room_qty - booking::whereDate('booking_from', '>=', $date1)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '<=', $date2)->get()->sum('room_qty');
            $room_qty = $room_qty - booking::whereDate('booking_from', '<=', $date2)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '>', $date2)->get()->sum('room_qty');
            $room_qty = $room_qty - booking::whereDate('booking_from', '<', $date1)->whereDate('booking_to', '>', $date1)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '<', $date2)->get()->sum('room_qty');
        }
        if ($room_qty < $request->room_qty) {
            return response()->json(['room_qty' => 'There are only ' . $room_qty . ' room are available'], 422);
        }

        //return response()->json(['room_qty' => 'The Message'], 422);

        //dd($request->all());

        $insert = new booking();
        $insert->date = $date;
        $insert->booking_no = $request->booking_no;
        $insert->room_type_id = $request->room_type_id;
        $insert->member_id = $request->member_id;
        $insert->name = $request->name;
        $insert->cell_no = $request->cell_no;
        $insert->email = $request->email;
        $insert->address = $request->address;
        $insert->purpose = $request->purpose;
        $insert->no_guest = $request->no_guest;
        $insert->booking_from = $date1;
        $insert->booking_to = $date2;
        $insert->room_rent = $request->room_rent;
        $insert->room_qty = $request->room_qty;
        $insert->total_amount = $request->total_amount;
        $insert->payment_mood = $request->payment_mood;
        $insert->save();

        $credit = credit::orderBy('id', 'DESC')->first();
        if ($credit) {
            $creditId = $credit->id;
        } else {
            $creditId = 0;
        }

        $credit1 = new credit();
        $credit1->date = $date;
        $credit1->voucher_no = rand(100, 999) . '' . $creditId;
        $credit1->income_source_id = 0;
        $credit1->amount = $request->total_amount;
        $credit1->payment_id = $request->payment_mood;
        $credit1->save();

    }
    public function BookingRoomFree(Request $request)
    {
        $this->validate($request, [
            'room_type_id' => 'Required|integer',
            'booking_from' => "Required",
            'booking_to' => "Required",
        ]);

        $date1 = new Carbon(DateTime::createFromFormat('d/m/Y', $request->booking_from)->format('Y-m-d'));
        $date2 = new Carbon(DateTime::createFromFormat('d/m/Y', $request->booking_to)->format('Y-m-d'));
        $difference = $date1->diff($date2)->days + 1;

        $room_info = room_info::where('room_type_id', $request->room_type_id)->first();
        $room_qty = $room_info->qty;

        if ($difference == 1) {
            //$room_qty = $room_qty - booking::whereDate('booking_from', $date1)->get()->sum('room_qty');
            $room_qty = $room_qty - booking::whereDate('booking_from', '<=', $date1)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '>=', $date1)->get()->sum('room_qty');
        } else {
            $room_qty = $room_qty - booking::whereDate('booking_from', '>=', $date1)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '<=', $date2)->get()->sum('room_qty');
            $room_qty = $room_qty - booking::whereDate('booking_from', '<=', $date2)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '>', $date2)->get()->sum('room_qty');
            $room_qty = $room_qty - booking::whereDate('booking_from', '<', $date1)->whereDate('booking_to', '>=', $date1)->where('room_type_id', $request->room_type_id)
                    ->whereDate('booking_to', '<', $date2)->get()->sum('room_qty');
        }

        return $room_qty;

        //return response()->json(['room_qty' => 'The Message'], 422);

        //dd($request->all());

    }

    public function show($id)
    {
        //
    }

    public function BookingAllSchedule()
    {
        return booking::with('room_type')->whereDate('booking_to', '>=',Carbon::today())->get();
    }

    public function BookingScheduleSearch(Request $request)
    {
        return booking::with('room_type')->whereDate('booking_to', '>=',Carbon::today())
            ->where('room_type_id',$request->room_type_id)->get();
    }

    public function BookingAmount()
    {
        return booking::all()->sum('total_amount');
    }

    public function member($id)
    {
        return file_get_contents("http://gcms.issit.org/api/member_data/$id");

    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $department = booking::findOrFail($id);
        $department->delete();
        return ['message' => 'deleted'];
    }
}
