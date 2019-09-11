<?php

namespace App\Http\Controllers\API;

use App\booking;
use App\room_info;
use App\room_type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{

    public function index()
    {
        return room_info::with('room_type')->latest()->paginate(10);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'room_type_id' => 'Required|max:191|unique:room_infos,room_type_id,',
            'rent' => 'Required|integer|digits_between:1,10',
            'qty' => 'Required|integer|digits_between:1,10',
            'details' => 'Required|regex:/^[a-zA-Z0-9 -.@&]+$/u',
            'image' => 'required',
        ]);

        //dd($request->all());
        $insert = new room_info();
        $insert->room_type_id = $request->room_type_id;
        $insert->rent = $request->rent;
        $insert->qty = $request->qty;
        $insert->details = $request->details;

        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('image/room/').$name);
            $insert->image = $name;
        }
        $insert->save();
    }

    public function RoomType()
    {
        return room_type::latest()->get();
    }

    public function RoomInfo()
    {
        return room_info::with('room_type')->select('id','room_type_id')->latest()->get();
    }

    public function RoomTypeStore(Request $request)
    {
        $this->validate($request, [
            'room_type' => 'Required|max:191|regex:/^[a-zA-Z0-9 -.@&]+$/u|unique:room_types,room_type,',
        ]);
        $insert = new room_type();
        $insert->room_type = $request->room_type;
        $insert->save();

        return $insert;
    }


    public function show($id)
    {
        return room_info::with('room_type','booking')->where('room_type_id',$id)->first();
    }

    public function RoomTypeBooking($id)
    {
        return booking::where('room_type_id',$id)->where('booking_from','>=',Carbon::today())
            ->orderBy('booking_from', 'asc')->get();
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'room_type_id' => 'Required|max:191|unique:room_infos,room_type_id,'.$id,
            'rent' => 'Required|integer|digits_between:1,10',
            'qty' => 'Required|integer|digits_between:1,10',
            'details' => 'Required|regex:/^[a-zA-Z0-9 -.@&]+$/u',
            'image' => 'required',
        ]);

        //dd(strlen($request->image));
        $insert = room_info::findOrFail($id);
        $insert->room_type_id = $request->room_type_id;
        $insert->rent = $request->rent;
        $insert->qty = $request->qty;
        $insert->details = $request->details;

        if($request->image && strlen($request->image) > 200){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('image/room/').$name);
            $insert->image = $name;
        }
        $insert->save();
    }


    public function destroy($id)
    {
        $dep = room_info::findOrFail($id);
        $dep->delete();
        return ['message' => 'Deleted'];
    }
}
