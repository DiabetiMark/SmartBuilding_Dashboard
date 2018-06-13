<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomUserController extends Controller
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
            'user_id' => 'required',
            'room_id' => 'required',
        ]);
        
        if(DB::table('room_user')->insert([
            'user_id' => $request->user_id,
            'room_id' => $request->room_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])){
            return app('App\Http\Controllers\UserController')->getAll();
        }

        $error = [
            "status" => xxxx,
            "message" => "Sensor module niet aangemaakt",
        ];

        return response()->json($error, 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SensorModule  $sensorModule
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        return $sensorModules = SensorModule::all();
    }

    public function showOne($id)
    {
        $sensorModule = SensorModule::select('moduleName')
        ->find($id);

        if($sensorModule !== null) 
        {
            return response()->json($sensorModule);
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
     * @param  \App\SensorModule  $sensorModule
     * @return \Illuminate\Http\Response
     */
    public function edit(SensorModule $sensorModule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SensorModule  $sensorModule
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $reqeust)
    {
        $this->validate($request, [
            'moduleName' => 'max:45',
        ]);

        $item = SensorModule::find($id);

        if($item !== null){
            if($this->setUpdate($item, $request)){
                return;
            }

            $error = [
                "status" => xxxx,
                "message" => 'Het wijzigen van de Sensor module is niet gelukt',
            ];
            $errorCode = 405;
        } else {

            //if the category cannot be found
            $error = [
                "status" => xxxx,
                "message" => 'De Sensor module is niet gevonden',
            ];
            $errorCode = 404;
        }

        //return the response of the error in json
        return response()->json($error, $errorCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SensorModule  $sensorModule
     * @return \Illuminate\Http\Response
     */
    public function destroy(SensorModule $sensorModule)
    {
        //
    }

    //custom actions
    public function getRooms($id)
    {
        return $rooms = SensorModule::find($id)->rooms;
    }

    public function getDataRegisters($id)
    {
        return $rooms = SensorModule::find($id)->dataRegisters;
    }
}
