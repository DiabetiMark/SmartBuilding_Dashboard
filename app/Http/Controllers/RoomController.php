<?php

namespace App\Http\Controllers;

use DB;
use App\Room;
use App\SensorModule;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'roomName' => 'required|numeric',
            'roomDescription' => 'required|numeric',
        ]);

        $item = new Room;

        if ($this->setCreate($item, $request)) {
            return;
        }

        $error = [
            "status" => xxxx,
            "message" => "Room is niet aangemaakt",
        ];

        return response()->json($error, 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        return $rooms = Room::all();
    }

    public function showOne($id)
    {
        $room = Room::select('roomName', 'roomDescription')
        ->find($id);

        if($room !== null) 
        {
            return response()->json($room);
        }

        $error = [
            "status" => xxxx,
            "message" => 'Sensor module kon niet gevonden worden',
        ];

        return response()->json($error, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $reqeust)
    {
        $this->validate($request, [
            'roomName' => 'numeric',
            'roomDescription' => 'numeric',
        ]);

        $item = Room::find($id);

        if($item !== null){
            if($this->setUpdate($item, $request)){
                return;
            }

            $error = [
                "status" => xxxx,
                "message" => 'Het wijzigen van de room is niet gelukt',
            ];
            $errorCode = 405;
        } else {

            //if the category cannot be found
            $error = [
                "status" => xxxx,
                "message" => 'De room is niet gevonden',
            ];
            $errorCode = 404;
        }

        //return the response of the error in json
        return response()->json($error, $errorCode);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }

    //custom actions
    public function getUsers($id)
    {
        return $rooms = Room::find($id)->users;
    }

    public function getSensorModules($id)
    {
        return $rooms = Room::find($id)->SensorModules;
    }

    public function getAllValue($id){
        $modules = Room::find($id)->SensorModules;
        $data_register_sensor_modules = DB::table('data_register_sensor_module')->select('field_id', 'data_register_id', 'sensor_module_id')->get();
        $data_register = DataRegister::all();
        $fieldName = Field::all();
        foreach($modules as $module){
            unset($module->pivot);
            
            
        }
        return $rooms;
    }
}
