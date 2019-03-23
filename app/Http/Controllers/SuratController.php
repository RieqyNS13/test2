<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Surat;
class SuratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pengembangan'] = Magang::with(['konstruktor.user','pembimbing_asal'])->where('is_pengembangan',true)->where('user_id', auth()->user()->id)->first();
        $data['biodata'] = Biodata::where('user_id',auth()->user()->id)->first();
        $data['konstruktor'] = User::whereHas('roles', function($query){
            $query->where('name','konstruktor');
        })->get();
        //$data['penilaian'] = $this->getnilai(Magang::);
        return view('pages.pengembangan', $data);
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
   public function viewpdf(Request $request){
        $request->validate([
            'filename' => 'required'
        ]);
        $path = storage_path('app/'.$request->input('filename'));
        if(!file_exists($path))$path = storage_path($request->input('filename'));
        return response()->file($path);
    }
}
