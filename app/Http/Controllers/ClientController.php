<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $clients = Client::whereHas('users', function($query) use ($user_id){
            $query->where('users.id',$user_id);
        })->take(12)->get();
        return $clients;
    }

    public function create()
    {
        return view('XXX');
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $data = $request->all();
        $validate = Client::validate($data,1,$user_id);
        if($validate!='success'){
            return view('XXX')->withWarnings($validate);
        }
        Client::addClient($data,$user_id);
    }

    public function show($id)
    {
        $user_id = Auth::user()->id;
        $client = Client::getClient($user_id, $id);
        return $client;
    }

    public function edit($id)
    {
        $user_id = Auth::user()->id;
        $client = Client::getClient($user_id, $id);
        return $client;
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $user_id = Auth::user()->id;
        $data = $request->all();
        $client = Client::getClient($user_id, $id);
        $validate = Client::validate($data,2,$user_id);
        if($validate!='success'){
            return view('XXX')->withWarnings($validate);
        }
        Client::updateClient($data,$client);
    }

    public function destroy($id)
    {
        $user_id = Auth::user()->id;
        $client = Client::getClient($user_id, $id);
        $client->delete();
        return view('XXX');
    }

}


