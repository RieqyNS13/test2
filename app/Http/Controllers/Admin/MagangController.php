<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Magang;
use App\Surat;

class MagangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pengembangan'] = Magang::with(['users','konstruktor.user','surats.jenis_surat'])->get();
        $data['konstruktor'] =  \App\User::whereHas('roles', function($query){
            $query->where('name','konstruktor');
        })->get();

        return view('pages.admin.pengembangan', $data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function load(){
       return Magang::with(['users','konstruktor.user','surats.jenis_surat'])->get();
    }
    public function validasi(Request $request){
        $data = $request->input('data');
        $validate = $request->input('validate');
        foreach($data as $pengembangan){
            $pengembangan_ = Magang::findOrFail($pengembangan['id']);
            $pengembangan_->is_validate = $validate;
            $pengembangan_->save();
        }
        return ['sukses'=>'success'];
    }
    public function completed(Request $request){
        $data = $request->input('data');
        $completed = $request->input('completed');
        foreach($data as $pengembangan){
            $pengembangan_ = Magang::findOrFail($pengembangan['id']);
            $pengembangan_->is_completed = $completed;
            $pengembangan_->save();
        }
        return ['sukses'=>'success'];
    }
    public function delete(Request $request){
        $data = $request->input();
        
        foreach($data as $pengembangan){
            $pengembangan_ = Magang::findOrFail($pengembangan['id']);
            $pengembangan_->delete();
        }
        return ['sukses'=>'success'];
    }

}
