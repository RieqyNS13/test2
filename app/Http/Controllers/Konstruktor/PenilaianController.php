<?php

namespace App\Http\Controllers\Konstruktor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Penilaian;
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

        $magang = $request->input('magang');
        $aspeks = $request->input('penilaian');

        $this->checkHasMagang($magang['id']);

        $request->validate([
            'penilaian.*.sub_aspek_nilai.*.nilai'=> 'required|numeric|lte:100'
        ]);
        foreach($aspeks as $aspek){
            $newSubAspekIds = [];
            foreach($aspek['sub_aspek_nilai'] as $sub_aspek_nilai){

                if(isset($sub_aspek_nilai['id'])){
                    $check = Penilaian::whereHas('sub_aspek_nilai',function($query)use($sub_aspek_nilai){
                        $query->where('id', $sub_aspek_nilai['id']);
                    });
                    $sub_aspek_nilai_id = $sub_aspek_nilai['id'];
                }else{
                    $newSubAspek = \App\SubAspekNilai::create(['aspek_nilai_id'=>$aspek['id'],'name'=>$sub_aspek_nilai['name'], 'is_custom'=>true]);
                    $sub_aspek_nilai_id = $newSubAspek->id;
                }
                $newSubAspekIds[] = $sub_aspek_nilai_id;
                $penilaian = Penilaian::firstOrNew(['magang_id'=>$magang['id'], 'sub_aspek_nilai_id'=>$sub_aspek_nilai_id]);
                $penilaian->nilai = $sub_aspek_nilai['nilai'];
                $penilaian->custom_name = $sub_aspek_nilai['name'];
                $penilaian->save();
            }
            $removedSubAspek = \App\SubAspekNilai::where('aspek_nilai_id',$aspek['id'])->where('is_custom',true)->whereNotIn('id', $newSubAspekIds)->get();
            foreach($removedSubAspek as $removedSubAspek_)
                    $removedSubAspek_->delete();
        }
        return $this->getNilai($magang['id']);
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
    public function getnilai($magang_id){
        $this->checkHasMagang($magang_id);
        // $check = Penilaian::with('sub_aspek_nilai.aspek_nilai')->whereHas('sub_aspek_nilai', function($query){
        //     $query->where('is_custom',0)->whereHas('aspek_nilai', function($query2){
        //         $query2->where('id',1);
        //     });
        // })->where('magang_id',$magang_id)->count();
        $aspek = \App\AspekNilai::all();
        foreach($aspek as $aspek_){
            $subaspek = $aspek_->sub_aspek_nilai()->where('is_custom',false)->get();
            foreach($subaspek as $subaspek_){
                $penilaian = $subaspek_->penilaian()->where('magang_id',$magang_id)->first();

                $subaspek_->nilai = $penilaian!=null ? $penilaian->nilai:0;
                $subaspek_->name = $penilaian!=null ? $penilaian->custom_name:$subaspek_->name;
                $subaspek_->nilai_huruf = $this->konversiNilai($subaspek_->nilai);
            }
            $subAspekNotCustomized = $subaspek;

           //print_r($subAspekNotCustomized->toArray());
            $penilaian = Penilaian::whereHas('sub_aspek_nilai', function($query)use($aspek_){
                $query->where('is_custom',true)->where('aspek_nilai_id',$aspek_->id);
            })->where('magang_id',$magang_id)->get();
            //print_r($penilaian);die;
            $subAspekCustomized=[];
            foreach($penilaian as $penilaian_){
                $subaspek_ = $penilaian_->sub_aspek_nilai;
                $subaspek_->nilai = $penilaian_->nilai;
                $subaspek_->name = $penilaian_->custom_name;
                $subaspek_->nilai_huruf = $this->konversiNilai($penilaian_->nilai);
                $subAspekCustomized[] = $subaspek_->toArray();
            }
            //print_r($subAspekCustomized);die;
            $aspek_->sub_aspek_nilai = array_merge($subAspekNotCustomized->toArray(), $subAspekCustomized);
        }
        //print_r($aspek->toArray());die;
        return ['magang'=>\App\Magang::with('konstruktor.user','users')->findOrFail($magang_id), 'penilaian'=>$aspek];
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
    public function checkHasMagang($magang_id){
        return \App\Magang::whereHas('konstruktor', function($query){
            $query->where('user_id', auth()->user()->id);
        })->where('id',$magang_id)->firstOrFail();
    }
    public function downloadPdf($magang_id){
        $data = $this->getNilai($magang_id);
        $pdf = \PDF::loadView('pdf.penilaian',$data);
        return $pdf->stream(Carbon::now('Asia/Jakarta')->format('Y-m-d').' - '.$this->clean($data['magang']->users->name).' - '.$data['magang']->asal);
    }
}
