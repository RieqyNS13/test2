<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class PenilaianController extends Controller
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
    public function getnilai($pengembangan_id){
        $aspek = \App\AspekNilai::all();
        foreach($aspek as $aspek_){
            foreach($aspek_->sub_aspek_nilai as $subaspek_){
                $penilaian = $subaspek_->penilaian()->where('pengembangan_id',$pengembangan_id)->first();
                $subaspek_->nilai = $penilaian!=null ? $penilaian->nilai:0;
                $subaspek_->nilai_huruf = $this->konversiNilai($subaspek_->nilai);
            }
        }
        return ['pengembangan'=>\App\Magang::with('users')->findOrFail($pengembangan_id), 'penilaian'=>$aspek];
    }
    public function konversiNilai($nilai){
        if($nilai>85)return 'A';
        else if($nilai>80)return 'AB';
        else if($nilai>70)return 'B';
        else if($nilai>65)return 'BC';
        else if($nilai>60)return 'C';
        else if($nilai>55)return 'CD';
        else if($nilai>50)return 'D';
        else return 'E';
    }
    public function clean($string) {
        //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       return preg_replace('/[^A-Za-z0-9 ]/', '', $string); // Removes special chars.
    }
    public function downloadPdf($pengembangan_id){
        $pengembangan = \App\Magang::whereHas('users',function($query){
            $query->where('user_id',auth()->user()->id);
        })->findOrFail($pengembangan_id);
        if(!$pengembangan->nilai_is_validate)abort(404);
        
        $data = $this->getNilai($pengembangan_id);
        $pdf = \PDF::loadView('pdf.penilaian',$data);
        return $pdf->stream(Carbon::now('Asia/Jakarta')->format('Y-m-d').' - '.$this->clean($data['pengembangan']->users->name).' - '.$data['pengembangan']->asal);
    }
}
