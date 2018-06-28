<?php

namespace App\Http\Controllers;

use DB;
use App\SensorModule;
use Illuminate\Http\Request;

class SensorModuleController extends Controller
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
            'moduleName' => 'required|max:45',
            'user_id' => 'required|numeric'
        ]);

        $item = new SensorModule;

        if ($this->setCreate($item, $request)) {
            return app('App\Http\Controllers\UserController')->getAllValues($request->user_id);
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
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'moduleName' => 'max:45',
        ]);


        $item = SensorModule::find($id);

        if($item !== null){
            if($request->room_id == -1){
                if(DB::table('sensormodules')->where('id', $id)->update(array('room_id' => null))){
                    return $this->showAll();
                }
            } else if($this->setUpdate($item, $request)){
                return $this->showAll();
            }

            $error = [
                "status" => 9999,
                "message" => 'Het wijzigen van de Sensor module is niet gelukt',
            ];
            $errorCode = 405;
        } else {

            //if the category cannot be found
            $error = [
                "status" => 9999,
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
