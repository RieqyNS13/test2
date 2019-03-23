<template>
    <v-app>
      <v-container>
        
        <v-layout v-if="dataPengembangan.id">
           <v-flex xs12 v-if="dataPengembangan.is_completed">
            <v-card color="green darken-2" class="white--text">
              <v-card-title primary-title>
                <div>
                  <div class="headline">Pengembangan Sudah Selesai</div>
                  <span>Mulai Pengembangan: {{dataPengembangan.from}}</span><br>
                  <span>Selesai Pengembangan: {{dataPengembangan.until}}</span><br>
                  <span>Asal: {{dataPengembangan.asal}}</span><br>
                  <span>Pembimbing Asal: {{dataPengembangan.pembimbing_asal ? dataPengembangan.pembimbing_asal.name:'-'}}</span><br>

                  <span>Konstruktor: {{dataPengembangan.konstruktor ? dataPengembangan.konstruktor.user.name:'-'}}</span><br>
                  <div v-if="dataPengembangan.nilai_is_validate">
                  <v-btn @click="downloadPdfNilai(pengembangan.id)">Lihat Nilai</v-btn>
                </div>
                  <div v-else>
                    <v-btn>Nilai Belum Keluar, Silahkan Tunggu</v-btn>
                  </div>
                   <v-btn @click="downloadSertifikat(pengembangan.id)">Lihat Sertifikat</v-btn>
                </div>
              </v-card-title>
              <v-card-actions>
              </v-card-actions>
            </v-card>

          </v-flex>

          <v-flex xs12 v-else-if="dataPengembangan.is_validate">
            <v-card color="blue darken-2" class="white--text">
              <v-card-title primary-title>
                <div>
                  <div class="headline">Pengembangan Sudah Divalidasi</div>
                  <span>Mulai Pengembangan: {{dataPengembangan.from}}</span><br>
                  <span>Selesai Pengembangan: {{dataPengembangan.until}}</span><br>
                  <span>Asal: {{dataPengembangan.asal}}</span><br>
                  <span>Pembimbing Asal: {{dataPengembangan.pembimbing_asal ? dataPengembangan.pembimbing_asal.name:'-'}}</span><br>
                  <span>Konstruktor: {{dataPengembangan.konstruktor ? dataPengembangan.konstruktor.user.name:'-'}}</span><br>
                  <!--   <v-btn>Lihat Surat Permohonan</v-btn><br>
                     <v-btn >Lihat Surat Permohonan</v-btn> -->
                </div>
              </v-card-title>
              <v-card-actions>
             
              </v-card-actions>
            </v-card>
            <v-layout>
              <v-flex xs6>
                <br>
                <span class="headline mb-0">Biodata</span>

                <v-form
                        ref="form1"
                        v-model="valid2"
                        lazy-validation 
                        method="POST"
                        action="/biodata"
                      >
                    <input type="hidden" name="_token" :value="csrf">

                     <v-radio-group v-model="biodata.gender" prepend-icon="person" label="Jenis kelamin" name="gender">
                        <v-layout>
                          <v-flex xs6>
                            <v-radio
                              key="L"
                              label="Laki-Laki"
                              value="L"
                            ></v-radio>
                        </v-flex>
                        <v-flex xs6>
                             <v-radio
                              key="P"
                              label="Perempuan"
                              value="P"
                            ></v-radio>
                        </v-flex>
                      </v-layout>
                    </v-radio-group>

                    <input type="hidden" name="tgl_lahir" :value="biodata.tgl_lahir">

                    <v-text-field :rules="[rules.required]" :value="biodata.tempat_lahir" name="tempat_lahir" label="Tempat Lahir" prepend-icon="place"></v-text-field>

                     <v-menu
                    ref="menu3"
                    v-model="menu3"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    max-width="290px"
                    min-width="290px"
                  >
                    <v-text-field
                      slot="activator"
                      :rules="[rules.required]"
                      v-model="dateFormatted3"
                      label="Tgl Lahir"
                      hint="MM/DD/YYYY format"
                      persistent-hint
                      prepend-icon="event"
                      @blur="biodata.tgl_lahir = parseDate(dateFormatted3)"
                    ></v-text-field>
                    <v-date-picker v-model="biodata.tgl_lahir" no-title @input="menu3 = false"></v-date-picker>
                  </v-menu>

                  <v-text-field :rules="[rules.required]" name="nohp" :value="biodata.nohp" label="No. Hp" hint="No. Hp yang dapat dihubungi" prepend-icon="smartphone"></v-text-field>

                   <v-textarea name="alamat" hint="Alamat" :value="biodata.alamat" prepend-icon="pin_drop">
                   </v-textarea>
                     <v-btn
                      :disabled="!valid2"
                      color="success"
                      @click="submitBiodata"
                    >
                      Submit
                    </v-btn>
                      </v-form>
            </v-flex>

            <v-flex xs5 offset-xs1>
              <br>
                <span class="headline mb-0">Pembimbing</span>
                <v-form
                        ref="form2"
                        v-model="valid3"
                        lazy-validation 
                        method="POST"
                        action="/pengembangan/addkonstruktor"
                      >
                    <input type="hidden" name="_token" :value="csrf">

                    <v-text-field :rules="[rules.required]" :value="pembimbing.pembimbing_asal" name="pembimbing_asal" label="Pembimbing Aasal" prepend-icon="place"></v-text-field>

                   <!--  <input type="hidden" name="user_id" :value="pembimbing.konstruktor">
                         <v-select
                         :rules="[rules.required]"
                         v-model="pembimbing.konstruktor"
                        :items="dataKonstruktor"
                        label="Pembimbing Lapangan"
                        item-text="name"
                        item-value="id"
                        append-icon="person"

                        ></v-select> -->

                 
                     <v-btn
                      :disabled="!valid3"
                      color="success"
                      @click="submitPembimbing"
                    >
                      Submit
                    </v-btn>
                      </v-form>
            </v-flex>

            </v-layout>
          </v-flex>

          <v-flex xs12 v-else>
             <v-card color="orange darken-2" class="white--text">
              <v-card-title primary-title>
                <div>
                  <div class="headline">Menunggu Persetujuan Admin</div>
                  <span>Mulai Pengembangan: {{dataPengembangan.from}}</span><br>
                  <span>Selesai Pengembangan: {{dataPengembangan.until}}</span><br>
                  <span>Asal: {{dataPengembangan.asal}}</span>
                  <!--   <v-btn>Lihat Surat Permohonan</v-btn><br>
                     <v-btn >Lihat Surat Permohonan</v-btn> -->
                </div>
              </v-card-title>
              <v-card-actions>
                   <v-btn >Edit</v-btn><v-btn flat>Hapus</v-btn>
              </v-card-actions>
            </v-card>
            </v-flex>

        </v-layout>
        <v-layout v-else>
          <v-flex xs6>
             <v-form
    ref="form"
    v-model="valid"
    lazy-validation 
    enctype="multipart/form-data" 
    method="POST"
    action="/pengembangan"
  >

    <input type="hidden" name="from" :value="pengembangan.from">
    <input type="hidden" name="until" :value="pengembangan.until">
    <input type="hidden" name="_token" :value="csrf">

    <v-menu
          ref="menu1"
          v-model="menu1"
          :close-on-content-click="false"
          :nudge-right="40"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            :rules="[rules.required]"
            v-model="dateFormatted"
            label="Tgl Mulai Pengembangan"
            hint="MM/DD/YYYY format"
            persistent-hint
            prepend-icon="event"
            @blur="pengembangan.from = parseDate(dateFormatted)"
          ></v-text-field>
          <v-date-picker v-model="pengembangan.from" no-title @input="menu1 = false"></v-date-picker>
        </v-menu>

         <v-menu
          ref="menu2"
          v-model="menu2"
          :close-on-content-click="false"
          :nudge-right="40"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            :rules="[rules.required]"
            v-model="dateFormatted2"
            label="Tgl Selesai Pengembangan"
            hint="MM/DD/YYYY format"
            persistent-hint
            prepend-icon="event"
            @blur="pengembangan.until = parseDate(dateFormatted2)"
          ></v-text-field>
          <v-date-picker v-model="pengembangan.until" no-title @input="menu2 = false"></v-date-picker>
        </v-menu>

         <v-text-field
            :rules="[rules.required]"
            label="Asal"
            name="asal"
            placeholder="Teknik Informatika, UNNES"
            hint="Misal: 'Teknik Informatika, UNNES' atau 'RPL, SMK 8 SMG'"
            prepend-icon="school"
          ></v-text-field>

       <v-text-field
            label="Unggah Surat Permohonan"
            type="file"
            :rules="[rules.required]"
            name="surat_permohonan"
            prepend-icon="attach_file"
          ></v-text-field>

            <v-text-field
            label="Unggah Proposal"
            type="file"
             name="proposal"
            prepend-icon="attach_file"
          ></v-text-field>

             <v-btn
      :disabled="!valid"
      color="success"
      @click="submit"
    >
      Submit
    </v-btn>

    </v-form>  

          </v-flex>
        </v-layout>

      </v-container>
       

    </v-app>
</template>

<script>
    export default {
        props: ['dataPengembangan','dataBiodata','dataKonstruktor'],
         data () {
          return {
            biodata:{
              tempat_lahir:null,
              tgl_lahir:new Date().toISOString().substr(0, 10),
              nohp:null,
              gender:'L',
              alamat:null
            },
            pembimbing:{
              pembimbing_asal:null,
              konstruktor:null
            },
            valid: true,
            valid2: true,
            valid3: true,
            dateFormatted: this.formatDate(new Date().toISOString().substr(0, 10)),
            dateFormatted2: this.formatDate(new Date().toISOString().substr(0, 10)),
            dateFormatted3: this.formatDate(new Date().toISOString().substr(0, 10)),
            pengembangan:{
              from:new Date().toISOString().substr(0, 10),
              until:new Date().toISOString().substr(0, 10)
            },
            rules: {
              required: value => !!value || 'Required.'
            },
            menu1: false,
            menu2: false,
            menu3: false,
            items: [
              { title: 'Home', icon: 'dashboard' },
              { title: 'About', icon: 'question_answer' }
            ],
            right: null,
            csrf:document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        },
        mounted() {
            console.log('Component mounted.');
            console.log(this.dataPengembangan);
            console.log(this.dataBiodata);
            console.log(this.dataKonstruktor);
            this.pengembangan.id = this.dataPengembangan.id;
            if(this.dataBiodata.tgl_lahir){
              //this.biodata = this.dataBiodata;
            }
            if(this.dataPengembangan.konstruktor){
              this.pembimbing.konstruktor = this.dataPengembangan.konstruktor.user_id;
            }
            if(this.dataPengembangan.pembimbing_asal){
              this.pembimbing.pembimbing_asal = this.dataPengembangan.pembimbing_asal.name;
            }

        }, 
        computed: {
          computedDateFormatted () {
            return this.formatDate(this.date)
          }
        },
         watch: {
          'pengembangan.from':function(val, oldVal) {
            //alert(val);
            this.dateFormatted = this.formatDate(this.pengembangan.from)
          },
          'pengembangan.until':function(val, oldVal) {
            //alert(val);
            this.dateFormatted2 = this.formatDate(this.pengembangan.until)
          },
          'biodata.tgl_lahir':function(val, oldVal) {
            //alert(val);
            this.dateFormatted3 = this.formatDate(this.biodata.tgl_lahir)
          }
        },
         methods: {
          submit:function(){
             if(this.$refs.form.validate()){
                   Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                  if (result.value) {
                      this.$refs.form.$el.submit();
                  }
              });
             }
          },
          submitPembimbing:function(){
              if(this.$refs.form2.validate()){
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                  if (result.value) {
                      this.$refs.form2.$el.submit();
                  }
              });
             }
          },
          downloadPdfNilai:function(pengembangan_id){
              window.open("/penilaian/downloadPdf/"+pengembangan_id, "_blank");
          },
          downloadSertifikat:function(pengembangan_id){
              window.open('/pengembangan/downloadSertifikat/'+pengembangan_id, '_blank');
          },
          submitBiodata:function(){
             if(this.$refs.form1.validate()){
                   Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                  if (result.value) {
                      this.$refs.form1.$el.submit();
                  }
              });
             }
          },
          formatDate (date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${month}/${day}/${year}`
          },
          parseDate (date) {
            if (!date) return null
            const [month, day, year] = date.split('/')
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
         }
        },
    }
</script>
