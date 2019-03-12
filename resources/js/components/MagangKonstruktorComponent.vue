<template>
    <v-app>
      <v-container>
        <v-layout>
          <v-flex xs12>
             

             <v-data-table
                 v-model="selected"
                :headers="headers"
                :items="items"
                item-key="id"
                class="elevation-1"
                select-all
                :expand="expand"

                >
                <template slot="items" slot-scope="props">
                  <tr>
                   <td>
                    <v-checkbox
                      v-model="props.selected"
                      primary
                      hide-details
                    ></v-checkbox>
                  </td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.users.name }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.asal }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-html="props.item.konstruktor ? props.item.konstruktor.user.name:'<span class=\'red--text\'><i>--belum ada</i></span>'"></td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.from.toLocaleString() }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded">{{ props.item.until.toLocaleString() }}</td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-if="props.item.status.code==-1">
                   <span class="red--text">{{ props.item.status.description }}</span>
                 </td>
                  <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-else-if="props.item.status.code==0">
                    <span class="blue--text"> {{ props.item.status.description }}</span>
                </td>
                <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-else>
                    <span class="green--text"> {{ props.item.status.description }}</span>
                </td>

                 <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-if="!props.item.nilai_is_validate">
                    <span class="red--text">Belum divalidasi</span>
                </td>
                <td @click="setSubItem(props.index);props.expanded = !props.expanded" v-else>
                    <span class="green--text">Sudah divalidasi</span>
                </td>
                <td><!-- <v-btn color="primary"
                    dark
                    @click.stop="getNilaiPesertaMagang(props.item)"
                    small>Penilaian</v-btn> -->
                   <v-btn-toggle mandatory>
                    <v-btn @click.stop="getNilaiPesertaMagang(props.item)">
                      Penilaian
                    </v-btn>
                    <v-btn @click="downloadPdfNilai(props.item)">
                      Download Nilai
                    </v-btn>
                  </v-btn-toggle>
                  </td>
              </tr>
                </template>
                
                <template slot="expand" slot-scope="props">
                <v-card flat>
                  <v-card-text>
                      <v-form target="_blank" v-for="surat in magang.surats" :key="surat.id" method="POST" action="/konstruktor/viewpdf">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" name="filename" :value="surat.path_upload">
                        <v-btn type="submit" color="info">{{surat.jenis_surat.name}}</v-btn>
                      </v-form>  

                  </v-card-text>
                </v-card>
               </template>

                 <template slot="footer">
                  <td :colspan="headers.length+1">
                    <v-btn color="info"  @click="submitValidasi(true)" :disabled="selected.length> 0 ? false:true">Validasi</v-btn>
                      <v-btn color="error"  @click="submitValidasi(false)" :disabled="selected.length> 0 ? false:true">Belum Validasi</v-btn>


                  </td>
                </template>
          </v-data-table>  

          </v-flex>
        </v-layout>
      </v-container>

             <v-dialog
      v-model="dialog"
      width="499"
    >
     <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Form Penilaian
        </v-card-title>

        <v-card-text>
         <v-layout>
          <v-flex xs12>

            <v-card>
            <v-card-text primary-title>
               <span>Peserta magang: {{magang ? magang.users.name:''}}</span><br>
              <span>Tgl Mulai magang: {{magang ? magang.from.toLocaleString():''}} </span><br>
               <span>Tgl Selesai magang: {{magang ? magang.until.toLocaleString():''}} </span><br>
               <span>Asal: {{magang ? magang.asal:''}} </span>
            </v-card-text>

          </v-card>
          
          </v-flex>
         </v-layout>
          
            <v-tabs
              v-model="active"
              color="cyan"
              dark
              slider-color="yellow"
            >
              <v-tab
                v-for="(aspek, aspek_i) in penilaian"
                :key="aspek_i"
                ripple
              >
                {{aspek.name}}

              </v-tab>
              <v-tab-item
                v-for="(aspek, aspek_i) in penilaian"
                :key="aspek_i"
              >
                <v-card flat>
                  <v-card-text>

                    <div 
                    v-for="(subAspek, subAspek_i) in aspek.sub_aspek_nilai"
                    :key="subAspek_i">
                      <div v-if="aspek.name.includes('Non Teknis')">
                      <v-text-field 
                       hint="0-100"
                      :label="subAspek.name"
                      v-model="subAspek.nilai"
                      >
                      </v-text-field>
                    </div>
                    <div v-else>
                      <v-text-field v-if="subAspek.is_custom"
                        prepend-icon="edit"
                        @click:prepend="changeNilaiName(aspek_i, subAspek_i)"
                        hint="0-100"
                        :label="subAspek.name"
                        v-model="subAspek.nilai"
                        append-outer-icon="delete"
                        @click:append-outer="deleteNilai(aspek_i, subAspek_i)"
                      ></v-text-field>
                     <v-text-field v-else
                      prepend-icon="edit"
                      @click:prepend="changeNilaiName(aspek_i, subAspek_i)"
                      hint="0-100"
                      :label="subAspek.name"
                      v-model="subAspek.nilai"
                    ></v-text-field>
                   </div>
                  </div>

                  </v-card-text>
                </v-card>
              </v-tab-item>
            </v-tabs>

            <v-btn v-if="active==1" @click="dialog3=true">Tambah</v-btn>

        </v-card-text>
        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn
            color="primary"
            @click="submitNilai()"
          >
           Simpan Nilai
          </v-btn>
          <v-btn
            flat
            @click="dialog = false"
          >
           Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


 <v-dialog
      v-model="dialog2"
      width="400"
    >
     <v-card>
        <v-card-title
          primary-title
        >
          Edit Nama Nilai
        </v-card-title>

        <v-card-text>
         <v-layout>
          <v-flex xs12>
            <v-text-field v-if="penilaian_edit.aspek_index!=null"
              label="Nama baru"
              v-model="penilaian[penilaian_edit.aspek_index].sub_aspek_nilai[penilaian_edit.subAspek_index].name"

              ></v-text-field>
          
          </v-flex>
         </v-layout>
          
          
        </v-card-text>


        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn
            color="primary"
            @click="dialog2 = false"
          >
           Simpan 
          </v-btn>
          <v-btn
            flat
            @click="discardChange"
          >
           Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

<!--dialog tambah nilai-->
<v-dialog
      v-model="dialog3"
      width="400"
    >
     <v-card>
        <v-card-title
          primary-title
        >
          Tambah Nilai
        </v-card-title>

        <v-card-text>
         <v-layout>
          <v-flex xs12>
            <v-text-field
              label="Nama nilai baru"
              v-model="namaNilaiBaru"
              ></v-text-field>
          
          </v-flex>
         </v-layout>
          
          
        </v-card-text>


        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn
            color="primary"
            @click="tambahNilai"
          >
           Tambah Nilai 
          </v-btn>
          <v-btn
            flat
            @click="dialog3 = false"
          >
           Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    </v-app>
</template>

<script>
    export default {
        props: ['dataMagang','dataFieldPenilaian'],
         data () {
          return {
            selected:[],
            expand:false,
            headers:[
              {
                text: 'Nama',
                align: 'left',
                sortable: false,
                value: 'name'
              },
                { text: 'Asal', value: 'asal' },
               { text: 'Konstruktor', value: 'konstruktor' },
              { text: 'Mulai Magang', value: 'from' },
              { text: 'Selesai Magang', value: 'until' },
              { text: 'Status Magang', value: 'status' },
              { text: 'Status Penilaian', value: 'status_nilai' },
              { text: 'Aksi', value: 'status' },
            ],
            magang:null,
            items:[],
            csrf:document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            dialog:false,
            dialog2:false,
            dialog3:false,
            active:null,
            text:'asu',
            penilaian:null,
            penilaian_edit:{
              aspek_index:null,
              subAspek_index:null,
              old:null
            },
            namaNilaiBaru:null,
          }
        },
        mounted() {

            console.log('Component mounted.');
            console.log(this.dataMagang);
            console.log(this.dataFieldPenilaian);
            this.items = this.dataMagang;
            this.addItemsMagangStatus();
            this.penilaian = this.dataFieldPenilaian;
            //alert(this.items[0].status.code);

        }, 
        computed: {
          computedDateFormatted () {
            return this.formatDate(this.date)
          }
        },
         watch: {
          
        },
         methods: {
          tambahNilai:function(){
            this.penilaian[this.active].sub_aspek_nilai.push({name:this.namaNilaiBaru,nilai:0,is_custom:1});
            this.namaNilaiBaru=null;
            this.dialog3= false;
            //alert(this.active);
          },
          deleteNilai:function(aspek_index, subAspek_index){
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                let hapus=this.penilaian[aspek_index].sub_aspek_nilai.splice(subAspek_index,1);
                // Swal.fire(
                //   'Deleted!',
                //   'Nilai telah dihapus',
                //   'success'
                // )
              }
            });
          },
          changeNilaiName:function(aspek_index, subAspek_index){
            //alert(this.penilaian[0].sub_aspek_nilai[0]);
            this.penilaian_edit.aspek_index=aspek_index;
            this.penilaian_edit.subAspek_index=subAspek_index;
            this.penilaian_edit.old = this.penilaian[aspek_index].sub_aspek_nilai[subAspek_index].name;
            this.dialog2=true;
          },
          discardChange:function(){
            this.penilaian[this.penilaian_edit.aspek_index].sub_aspek_nilai[this.penilaian_edit.subAspek_index].name=this.penilaian_edit.old;
            this.dialog2 = false
          },
          submitEditNilaiName:function(){
              this.dialog2=false;
          },
          getNilaiPesertaMagang:function(magang){
            console.log(magang);

             Swal.fire({
              title: 'Mengambil nilai peserta magang',
              onBeforeOpen: () => {
                Swal.showLoading()
              }
            });
            axios.get('/konstruktor/getnilai/'+magang.id).then((res)=>{
                this.penilaian = res.data.penilaian;
                this.magang = res.data.magang;
                this.dialog=true;
                Swal.close();
            });
          },
          addItemsMagangStatus:function(){
              this.items.forEach((v,k)=>{
              v.status = {}
              v.status.code = null;
              v.status.description=null;
              if(v.is_validate){
                v.status.code = 0;
                v.status.description = "Sudah divalidasi";
              }
              else {
                v.status.code = -1;
                v.status.description = "Belum divalidasi";
              }
              if(v.is_completed){
                v.status.code = 1;
                v.status.description = "Magang Sudah Selesai";
              }
            });
          },
          downloadPdfNilai:function(magang){
              window.open("/konstruktor/penilaian/downloadPdf/"+magang.id, "_blank");
          },
          submitNilai:function(){
            let data={'magang':this.magang,'penilaian':this.penilaian};
              Swal.fire({
                 title: 'Are you sure?',
                 text: "You won't be able to revert this!",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Yakin !',
                 showLoaderOnConfirm: true,
                 preConfirm: () => {
                    return axios.post("/konstruktor/penilaian", data).then(res => {
                       if (res.data.error) {
                          throw new Error(res.data.error)
                       }

                    }).catch(error => {
                       Swal.showValidationMessage(
                          'Error, nilai harus diantara 0-100'
                       );
                    });
                 },
                 allowOutsideClick: () => !Swal.isLoading()
              }).then((result) => {
                 if (result.value) {
                    Swal.fire(
                       'Good job!',
                       'Berhasil simpan nilai',
                       'success'
                    );
                    this.dialog=false;
                    //this.loadMagang();
                 }
              });  
          },
          submit:function(){
             if(this.$refs.form.validate()){
                this.$refs.form.$el.submit();
             }
          },
          loadMagang:function(){
            axios.get('/konstruktor/penilaian/load').then((res)=>{
              this.items = res.data;
              this.addItemsMagangStatus();
            });

          },
          submitValidasi:function(validate){
              console.log(this.selected);
              //return;
              let data={'validate':validate,'data':this.selected}
              Swal.fire({
               title: 'Are you sure?',
               text: "You won't be able to revert this!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yakin !',
               showLoaderOnConfirm: true,
               preConfirm: () => {
                  return axios.post("/konstruktor/penilaian/validasi", data).then(res => {
                     if (res.data.error) {
                        throw new Error(res.data.error)
                     }

                  }).catch(error => {
                     Swal.showValidationMessage(
                        `Request failed: ${error}`
                     );
                  });
               },
               allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
               if (result.value) {
                  Swal.fire(
                     'Good job!',
                     'Berhasil validasi nilai',
                     'success'
                  );
                  this.loadMagang();
               }
            });  
          },
          setSubItem:function(item_index){
            this.magang = this.dataMagang[item_index];
          }
        },
    }
</script>
