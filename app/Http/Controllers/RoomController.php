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
            'name' => 'required|numeric',
            'description' => 'required|numeric',
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
        $room = Room::select('name', 'description')
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
            'name' => 'numeric',
            'description' => 'numeric',
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
        return $rooms = Room::find($id)->sensorModules;
    }

    public function getAllValues($id){
        $room = Room::find($id);
        unset($room->created_at);
        unset($room->updated_at);
        foreach($room->sensorModules as $module){
            $module->room_name = $room->roomName;
            unset($module->pivot);
            unset($module->room_id);
            unset($module->created_at);
            unset($module->updated_at);
            foreach($module->dataRegisters as $dataRegister){
                $dataRegister->field;
                unset($dataRegister->sensorModule_id);
                unset($dataRegister->field_id);
            }
            
        }
        return $room;
    }
}
