<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
              <h1>Bibliotecarios</h1>
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Lista de Personal</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-success" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Registrar bibliotecario
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tipo</th>
                      <th>Nombre (s)</th>
                      <th>Apellidos (s)</th>
                      <th>Correo</th>
                      <th>Fecha de registro</th>
                      

                      <th>Institucion</th>
                      <th>Biblioteca</th>

                      <th>Informacion</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="user in users" :key="user.id">

                      <td>{{user.id}}</td>
                      <td class="text-capitalize">{{user.type}}</td>
                      <td class="text-capitalize">{{user.name}}</td>
                      <td class="text-capitalize">{{user.last_name}}</td>
                      <td>{{ user.email  }}</td>
                      <!-- <td :inner-html.prop="user.email_verified_at | yesno"></td>-->
                      <td>{{user.created_at | myDate }}</td>

                      <td> {{user.nombre_institucion}} </td>
                      <td> {{user.nombre_biblioteca}}</td>

                      <td> 
                        <center> 
                          <a href="#" @click="infoModal(user)"> <i class="fas fa-info-circle"></i>  </a> 
                          
                        
                            

                            <a href="#" @click="obtenerDatosContacto(user)"> <i class="fas fa-id-card-alt"></i> </a>
                        </center> 
                        
                      </td>
                      
                      <td>

                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>
            <!-- /.card -->
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Registrar bibliotecario</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar bibliotecario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateUser() : createUser()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Apellido</label>
                            <input v-model="form.last_name" type="text" name="last_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                            <has-error :form="form" field="last_name"></has-error>
                        </div>
                        
                        <div class="form-group">
                            <label>Correo</label>
                            <input v-model="form.email" type="text" name="email"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                    
                        <div class="form-group">
                            <label>Constraseña de la cuenta</label>
                            <input v-model="form.password" type="password" name="password"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" autocomplete="false">
                            <has-error :form="form" field="password"></has-error>
                        </div>


                        <div class="form-group">
                            <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }" hidden>
                                <option value="admin">admin</option>
                                <option value="studen">studen</option>
                                <option value="profe">profe</option>
                            </select>
                            <!--<has-error :form="form" field="type"></has-error>-->
                        </div>

                        <div class="form-group">
                            <label>Institución</label>

                            <select id="selectInstitucion" v-model="form.id_institucion" class="form-control" @change="cambioInstitucion($event)" required>
                                <option v-for="inst in instituciones" v-bind:value="inst.id">
                                    {{ inst.nombre }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Biblioteca</label>

                            <select id="selectBiblioteca" v-model="form.id_biblioteca" class="form-control" required>
                                <option v-for="bibli in bibliotecas" v-bind:value="bibli.id">
                                    {{ bibli.nombre }}
                                </option>
                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Crear</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>




        <!-- Modal de datos personales -->
        <div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="addData" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" > Datos personales del empleado de biblioteca </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="guardarDatos()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input v-model="infoAlumno.fecha_nacimiento" type="date" name="fecha_nacimiento"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Telefono</label>
                            <input v-model="infoAlumno.telefono" type="text" name="telefono"
                                class="form-control">

                        </div>
                        
                        <div class="form-group">
                            <label>Detalles</label>
                            <input v-model="infoAlumno.detalles" type="text" name="detalles"
                                class="form-control" >
                        </div>
                    

                        <!-- DIRECCION DE VIVIENDA  -->
                        <div class="form-group">
                            <label>Calle</label>
                            <input v-model="infoAlumno.calle" type="text" name="calle"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Numero</label>
                            <input v-model="infoAlumno.numero" type="text" name="numero"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Colonia</label>
                            <input v-model="infoAlumno.colonia" type="text" name="colonia"
                                class="form-control" >
                        </div>
                        
                        <div class="form-group">
                            <label>Codigo Postal</label>
                            <input v-model="infoAlumno.codigopostal" type="text" name="codigopostal"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Poblado</label>
                            <input v-model="infoAlumno.poblado" type="text" name="poblado"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Municipio</label>
                            <input v-model="infoAlumno.municipio" type="text" name="muinicipio"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <input v-model="infoAlumno.estado" type="text" name="estado"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Pais</label>
                            <input v-model="infoAlumno.pais" type="text" name="pais"
                                class="form-control" >
                        </div>
                        

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
        
        <!-- Modal de datos del contacto-->
        <div class="modal fade" id="addContacto" tabindex="-1" role="dialog" aria-labelledby="addContacto" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" > Datos del contacto de emergencia </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="guardarDatosContacto()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input v-model="infoContacto.nombre" type="text" name="nombreContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Apellido</label>
                            <input v-model="infoContacto.apellidos" type="text" name="apellidoContacto"
                                class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label>Edad</label>
                            <input v-model="infoContacto.edad" type="number" name="edadContacto"
                                class="form-control" >
                        </div>
                    
                        <div class="form-group">
                            <label>Parentesco</label>
                            <input v-model="infoContacto.parentesco" type="text" name="parentescoContacto"
                                class="form-control" >
                        </div>
                        
                        <div class="form-group">
                            <label>correo</label>
                            <input v-model="infoContacto.correo" type="email" name="correoContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Telefono</label>
                            <input v-model="infoContacto.telefono" type="text" name="telefonoContacto"
                                class="form-control" >
                        </div>



                        
                        <!-- DIRECCION DE VIVIENDA  -->
                        <div class="form-group">
                            <label>Calle</label>
                            <input v-model="infoContacto.calle" type="text" name="calleContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Numero</label>
                            <input v-model="infoContacto.numero" type="text" name="numeroContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Colonia</label>
                            <input v-model="infoContacto.colonia" type="text" name="coloniaContacto"
                                class="form-control" >
                        </div>
                        
                        <div class="form-group">
                            <label>Codigo Postal</label>
                            <input v-model="infoContacto.codigopostal" type="text" name="codigopostalContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Poblado</label>
                            <input v-model="infoContacto.poblado" type="text" name="pobladoContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Municipio</label>
                            <input v-model="infoContacto.municipio" type="text" name="muinicipioContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <input v-model="infoContacto.estado" type="text" name="estadoContacto"
                                class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Pais</label>
                            <input v-model="infoContacto.pais" type="text" name="paisContacto"
                                class="form-control" >
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>



    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                users : {},
                id_info: '',
                instituciones: '',
                bibliotecas: '',
                inst_selec: '',
                infoEscolar:{
                    id: '',
                    ingreso: '',
                    finalizacion: '',
                    semestre: '',
                    carrera: '',
                    facultad: '',
                    codigo: '',
                    creditos: '',
                    estado: '',
                    id_biblioteca: ''
                },
                infoAlumno:{
                  id:'',
                  fecha_nacimiento: '',
                  telefono: '',
                  estatus: '',
                  detalles: '',
                  id_informacion: '',
                  id_direccionn: '',
                  calle: '',
                  numero: '',
                  colonia:'',
                  codigopostal:'',
                  poblado:'',
                  municipio: '',
                  estado: '',
                  pais: ''
                },
                infoContacto:{
                    id: '',
                    nombre: '',
                    apellidos: '',
                    edad:'',
                    parentesco: '',
                    correo: '',
                    telefono: '',
                    id_direccionn: '',
                    calle: '',
                    numero: '',
                    colonia:'',
                    codigopostal:'',
                    poblado:'',
                    municipio: '',
                    estado: '',
                    pais: ''


                },
                form: new Form({
                    id : '',
                    name: '',
                    type: 'admin',
                    last_name: '',
                    email: '',
                    password: '',
                    email_verified_at: '',
                    id_biblioteca: '',
                    id_institucion: ''
                })
            }
        },
        methods: {
            cambioInstitucion(event){

                //Bibliotecas
                this.inst_selec = event.target.value;
                axios.get("/api/biblioteca/"+event.target.value).then(({ data }) => {
                    //console.log(data.data);
                    this.bibliotecas = data.data
                });

            },
            obtenerInstituciones(){
                //instituciones
                axios.get("api/institucion").then(({ data }) => {
                    console.log(data.data);
                    this.instituciones = data.data
                });
            },

            obtenerDatosContacto( alumno ){

                $('#addContacto').modal('show');

                 axios.get('api/alumno/informacionContacto/' + alumno.id  ).then(({ data }) => {

            

              console.log(data.data);

                this.infoContacto.id = data.data[0].id_contacto
                this.infoContacto.nombre = data.data[0].nombres
                this.infoContacto.apellidos = data.data[0].apellidos
                this.infoContacto.edad = data.data[0].edad
                this.infoContacto.parentesco = data.data[0].parentesco
                this.infoContacto.correo = data.data[0].correo
                this.infoContacto.telefono = data.data[0].telefono

                this.infoContacto.id_direccionn = data.data[0].id_direccionn;
                this.infoContacto.calle = data.data[0].calle;
                this.infoContacto.numero  = data.data[0].numero;
                this.infoContacto.colonia = data.data[0].colonia;
                this.infoContacto.codigopostal = data.data[0].codigopostal;
                this.infoContacto.poblado = data.data[0].poblado;
                this.infoContacto.municipio  = data.data[0].municipio;
                this.infoContacto.estado  = data.data[0].estado;
                this.infoContacto.pais  = data.data[0].pais;

              });


            },
            guardarDatosContacto(){

               
               axios.post('api/alumno/informacionContacto',{
                                            id:this.infoContacto.id, 
                                            nombres: this.infoContacto.nombre,
                                            apellidos: this.infoContacto.apellidos,
                                            edad: this.infoContacto.edad, 
                                            parentesco: this.infoContacto.parentesco,
                                            correo: this.infoContacto.correo,
                                            telefono: this.infoContacto.telefono,
                                            
                                            id_direccion:this.infoContacto.id_direccionn,
                                            calle:this.infoContacto.calle,
                                            numero:this.infoContacto.numero,
                                            colonia:this.infoContacto.colonia,
                                            codigopostal:this.infoContacto.codigopostal,
                                            poblado:this.infoContacto.poblado,
                                            municipio:this.infoContacto.municipio,
                                            estado:this.infoContacto.estado,
                                            pais:this.infoContacto.pais
                })
              .then(response => {
                console.log(response);

                $('#addContacto').modal('hide');

                Toast.fire({
                      icon: 'success',
                      title: response.data.message
                  });
                this.$Progress.finish();

                this.infoContacto.id  = "";
                this.infoContacto.nombre = "";
                this.infoContacto.apellidos = "";
                this.infoContacto.edad = "";
                this.infoContacto.parentesco = "";
                this.infoContacto.correo = "";
                this.infoContacto.telefono = "";


                this.infoContacto.id_direccionn  = ""
                this.infoContacto.calle = ""
                this.infoContacto.numero  = ""
                this.infoContacto.colonia = ""
                this.infoContacto.codigopostal = ""
                this.infoContacto.poblado = ""
                this.infoContacto.municipio  = ""
                this.infoContacto.estado  = ""
                this.infoContacto.pais  = ""

                //this.cargarInstituciones();

              })
              .catch(()=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error, vuelva a intentarlo'
                });
              })



            },
            guardarDatosEscolares(){

                axios.post('api/alumno/informacionEscolar',{
                                            id:this.infoEscolar.id, 
                                            ingreso: this.infoEscolar.ingreso,
                                            finalizacion: this.infoEscolar.finalizacion,
                                            semestre: this.infoEscolar.semestre, 
                                            carrera: this.infoEscolar.carrera,
                                            facultad: this.infoEscolar.facultad,
                                            codigo: this.infoEscolar.codigo,
                                            creditos: this.infoEscolar.creditos,
                                            estado: this.infoEscolar.estado})
              .then(response => {
                console.log(response);

                $('#addEscolar').modal('hide');

                Toast.fire({
                      icon: 'success',
                      title: response.data.message
                  });
                this.$Progress.finish();

                this.infoEscolar.id = ""
                this.infoEscolar.ingreso = ""
                this.infoEscolar.finalizacion = ""
                this.infoEscolar.semestre = ""
                this.infoEscolar.carrera = ""
                this.infoEscolar.facultad = ""
                this.infoEscolar.codigo = ""
                this.infoEscolar.creditos = ""
                this.infoEscolar.estado = ""

                //this.cargarInstituciones();

              })
              .catch(()=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error, vuelva a intentarlo'
                });
              })


            },
          obtenerDatosEscolares( alumno ){
            
            $('#addEscolar').modal('show');

             axios.get('api/alumno/informacionEscolar/' + alumno.id  ).then(({ data }) => {
              //this.infoAlumno = data.data

            

              //console.log(data.data);

              this.infoEscolar.id = data.data[0].id_infoescolar
              this.infoEscolar.ingreso = data.data[0].ingreso
              this.infoEscolar.finalizacion = data.data[0].finalizacion
              this.infoEscolar.semestre = data.data[0].semestre
              this.infoEscolar.carrera = data.data[0].carrera
              this.infoEscolar.facultad = data.data[0].facultad
              this.infoEscolar.codigo = data.data[0].codigo
              this.infoEscolar.creditos = data.data[0].creditos
              this.infoEscolar.estado = data.data[0].estado
              

              });



          },
          guardarDatos(){

              axios.post('api/alumno/informacionPersonal',{id:this.infoAlumno.id_informacion, 
                                            fecha_nacimiento: this.infoAlumno.fecha_nacimiento,
                                            telefono:this.infoAlumno.telefono,
                                            estatus: this.infoAlumno.estatus, 
                                            detalles: this.infoAlumno.detalles,

                                            id_direccion: this.infoAlumno.id_direccionn,
                                            calle: this.infoAlumno.calle ,
                                            numero: this.infoAlumno.numero  ,
                                            colonia: this.infoAlumno.colonia ,
                                            codigopostal: this.infoAlumno.codigopostal ,
                                            poblado: this.infoAlumno.poblado ,
                                            municipio: this.infoAlumno.municipio  ,
                                            estado: this.infoAlumno.estado  ,
                                            pais: this.infoAlumno.pais  

                                            })
              .then(response => {
               // console.log(response);

                $('#addData').modal('hide');

                Toast.fire({
                      icon: 'success',
                      title: response.data.message
                  });
                this.$Progress.finish();

                this.infoAlumno.id = "";
                this.infoAlumno.fecha_nacimiento = "";
                this.infoAlumno.telefono = "";
                this.infoAlumno.detalles = "";
                this.infoAlumno.estatus = "";
                
                this.infoAlumno.id_direccionn  = ""
                this.infoAlumno.calle = ""
                this.infoAlumno.numero  = ""
                this.infoAlumno.colonia = ""
                this.infoAlumno.codigopostal = ""
                this.infoAlumno.poblado = ""
                this.infoAlumno.municipio  = ""
                this.infoAlumno.estado  = ""
                this.infoAlumno.pais  = ""

                //this.cargarInstituciones();

              })
              .catch(()=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error, vuelva a intentarlo'
                });
              })

          },
            infoModal(alumno){
              $('#addData').modal('show');

              axios.get('api/alumno/informacion/' + alumno.id  ).then(({ data }) => {
              //this.infoAlumno = data.data
                console.log(data.data[0]);
                this.infoAlumno.id = data.data[0].id_informacion
                this.infoAlumno.fecha_nacimiento = data.data[0].fecha_nacimiento
                this.infoAlumno.telefono = data.data[0].telefono
                this.infoAlumno.detalles = data.data[0].detalles
                this.infoAlumno.estatus = data.data[0].estatus
                this.infoAlumno.id_informacion = data.data[0].id_informacion

                this.infoAlumno.id_direccionn  = data.data[0].id_direccionn
                this.infoAlumno.calle = data.data[0].calle
                this.infoAlumno.numero  = data.data[0].numero
                this.infoAlumno.colonia = data.data[0].colonia
                this.infoAlumno.codigopostal = data.data[0].codigopostal
                this.infoAlumno.poblado = data.data[0].poblado
                this.infoAlumno.municipio  = data.data[0].municipio
                this.infoAlumno.estado  = data.data[0].estado
                this.infoAlumno.pais  = data.data[0].pais
              

              });
            },

            updateUser(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/actualizar/personal/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadUsers();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(user){
                //this.bibliotecas = [];
                

                this.editmode = true;
                this.form.reset();
                
                $('#addNew').modal('show');
                this.form.fill(user);
                this.form.type = "admin";

                axios.get("/api/biblioteca/"+this.form.id_institucion).then(({ data }) => {
                    console.log(data.data);
                    this.bibliotecas = data.data
                });
                
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                this.form.type = "admin";
                $('#addNew').modal('show');
            },
            deleteUser(id){
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "Esta accion no se puede revertir",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Si'
                    }).then((result) => {
                        if (result.value) {
                                this.form.delete('api/user/'+id).then(()=>{
                                        Swal.fire(
                                        'Alumno eliminado',
                                        'El alumno ha sido eliminado',
                                        'success'
                                        );
                                    this.loadUsers();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                    })
            },
          loadUsers(){
            this.$Progress.start();

            if(this.$gate.isAdmin()){
              axios.get("api/user/personal").then(({ data }) => (this.users = data.data));
            }

            this.$Progress.finish();
          },
          
          createUser(){

              this.form.post('api/personal/crear')
              .then((response)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });

                  this.$Progress.finish();
                  this.loadUsers();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          }

        },
        mounted() {
            

            console.log('User Component mounted.')
        },
        created() {
            
            this.$Progress.start();
            this.obtenerInstituciones();
            this.loadUsers();
            this.$Progress.finish();
        }
    }
</script>
