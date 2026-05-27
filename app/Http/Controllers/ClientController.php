<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */

        public function DisplayGreetings () {
            $name = "Juan";
            $address = "From San Carlos City ";
            
        // return view('greetings',['name'=>$name]);
        return view('greetings',compact('name','address'));
    }

    public function DisplayProfile(){
        return view('clientProfile');
    }
    public function DisplayDashboard(){
        return view('clientDashboard');
    }
    public function DisplayAboutus(){
        return view('clientAboutUs');
    }

    public function index()
    {
        //
        $grade = 50;
        $client = [
            "name" => "Lester",
            "sex" => "Male",
            "age" => 20,
            "course" => "BSIT"
        ];

        $clients = array(
            array("name" => "Lester","sex" => "Male","Address" => "San Carlos City"),
            array("name" => "Shin","sex" => "Male","Address" => "San Carlos City"),
            array("name" => "Mark","sex" => "Male","Address" => "San Carlos City")
        );
        // $clients = array();

        return view("client")->with('grade',$grade)->with('clients',$clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
