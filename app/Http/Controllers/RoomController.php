<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Room;
use App\SensorModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($room)
    {
        return view('/pages/room')->with('room', $room);
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
            'roomName' => 'required',
            'roomDescription' => 'required',
        ]);

        $item = new Room;

        if ($this->setCreate($item, $request)) {
            return $this->showAll();
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
    public function showAll(Request $request)
    {
        return Room::all();
        if($request->user('api')->role_id == 3){
            return $rooms = Room::all();
        } else {
            return User::find($request->user('api')->id)->rooms;
        }
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
    public function update($id, Request $request)
    {
        $this->validate($request, [
            
        ]);

        $item = Room::find($id);

        if($item !== null){
            if($this->setUpdate($item, $request)){
                return $this->showAll();
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
    public function destroy($id)
    {
        DB::table('room_user')->where('room_id', $id)->delete();
        SensorModule::where('room_id', $id)->update(['room_id' => null]);
        Room::find($id)->delete();

        $data = array(
            'rooms' => $this->showAll(),
            'modules' => app('App\Http\Controllers\SensorModuleController')->showAll(),
        );
        return $data;
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

    public function getAllValuesRoom($id){
        $room = Room::find($id);
        unset($room->created_at);
        unset($room->updated_at);
        foreach($room->sensorModules as $module){
            $module->room_name = $room->roomName;
            unset($module->pivot);
            unset($module->room_id);
            unset($module->created_at);
            unset($module->updated_at);
            foreach($module->sensors as $sensor){
                foreach($sensor->dataRegisters as $dataRegister){
                    $dataRegister->field;
                    unset($dataRegister->sensorModule_id);
                    unset($dataRegister->field_id);
                }
            }
        }
        return $room;
    }

    public function getAllValues(){
        $rooms = Room::all();
        foreach($rooms as $room){
            unset($room->created_at);
            unset($room->updated_at);
            foreach($room->sensorModules as $module){
                $module->room_name = $room->roomName;
                unset($module->pivot);
                unset($module->room_id);
                unset($module->created_at);
                unset($module->updated_at);
                foreach($module->sensors as $sensor){
                    foreach($sensor->dataRegisters as $dataRegister){
                        $dataRegister->field;
                        unset($dataRegister->sensorModule_id);
                        unset($dataRegister->field_id);
                    }
                }
            }
        }
        return $rooms;
    }
}
