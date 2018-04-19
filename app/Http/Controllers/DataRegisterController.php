<?php

namespace App\Http\Controllers;

use App\DataRegister;
use Illuminate\Http\Request;

class DataRegisterController extends Controller
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
            'value' => 'required|max:45',
        ]);

        $item = new DataRegister;

        if ($this->setCreate($item, $request)) {
            return;
        }

        $error = [
            "status" => xxxx,
            "message" => "dataregister niet aangemaakt",
        ];

        return response()->json($error, 404);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DataRegister  $dataRegister
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        return $users = DataRegister::all();
    }

    public function showOne($id)
    {
        $user = DataRegister::select('id', 'value', 'created_at')
        ->find($id);

        if($user !== null) 
        {
            return response()->json($product);
        }

        $error = [
            "status" => xxxx,
            "message" => 'Data register kon niet gevonden worden',
        ];

        return response()->json($error, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DataRegister  $dataRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(DataRegister $dataRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DataRegister  $dataRegister
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $reqeust)
    {
        $this->validate($request, [
            'value' => 'max:45',
        ]);

        $item = DataRegister::find($id);

        if($item !== null){
            if($this->setUpdate($item, $request)){
                return;
            }

            $error = [
                "status" => xxxx,
                "message" => 'Het wijzigen van de data register is niet gelukt',
            ];
            $errorCode = 405;
        } else {

            //if the category cannot be found
            $error = [
                "status" => xxxx,
                "message" => 'Data register kon niet gevonden worden',
            ];
            $errorCode = 404;
        }

        //return the response of the error in json
        return response()->json($error, $errorCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DataRegister  $dataRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataRegister $dataRegister)
    {
        //
    }
}
