<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row ">

          <div class="col-12">
            
            <h1>Prestamos</h1>

            <div class="card ">
              <div class="card-header">
                <h3 class="card-title">Lista de prestamos activos</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#agregarPrestamo" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Registrar prestamo
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Codigo escolar</th>
                      <th>Alumno / Profesor</th>
                      <th>Fecha</th>
                      <th>Fecha de devolución</th>
                      <th>Libro (s)</th>
                      <th>Estatus</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="prestamo in prestamos" :key="prestamo.id">

                      <td>{{prestamo.id}}</td>
                      <td>{{ prestamo.matricula}} </td>
                      <td>{{prestamo.nombreUsuario + " " + prestamo.apellidoUsuario }} </td>
                      <td>{{prestamo.fecha | myDate  }}</td>
                      <td>{{prestamo.fecha_devolucion | myDate }} </td>
                      <td>
                        

                        <a href="#" @click="verLibros(prestamo)">
                            <i class="fas fa-eye"></i>
                        </a>

                      </td>
                      <td>

                        
                        <span v-if="prestamo.estatus == 'Activo'" class="badge badge-success"> {{prestamo.estatus}} </span>
                        <span v-if="prestamo.estatus == 'Finalizado'" class="badge badge-primary"> {{prestamo.estatus}} </span>
                        <span v-if="prestamo.estatus == 'Retrasado'" class="badge badge-danger"> {{prestamo.estatus}} </span>

                      </td>
                      
                      <td>
                        <a href="#" @click="editModal(prestamo)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a href="#" @click="eliminarPrestamo(prestamo.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="agregarPrestamo" tabindex="-1" role="dialog" aria-labelledby="agregarPrestamo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Registrar prestamo</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar información del prestamo </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? actualizarPrestamo() : registrarPrestamo()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label> Alumno / Profesor </label>
                            <v-select v-model="form.nombreUsuario" :options="usuarios" label="name" @input="cambioUsuario"></v-select>
                        </div>

                        <div class="form-group">
                            <label>Fecha Devolución</label>
                            <input v-model="form.fecha_devolucion" type="date" name="description" class="form-control" required>
                        </div>

                          <div class="form-group">
                            <label>Libro</label>
                            <v-select id="vueLibro" v-model="form.nombreLibro" :options="libros" label="name" @input="cambioLibro"></v-select>
                          </div>

                          <label>Información del libro</label>

                          <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Portada</label>
                            <div class="col-sm-8">
  
                              <img v-bind:src="'/images/libros/' + foto" width="150px" height="200px" />  

                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Titulo</label>
                            <div class="col-sm-8">
                              <input v-model="titulo" class="form-control" type="text" id="titulo" name="titulo" disabled>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Edición</label>
                            <div class="col-sm-8">
                              <input v-model="edicion" class="form-control" type="text" id="edicion" name="editorial" disabled>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Autores</label>
                            <div class="col-sm-8">
                              <input v-model="autores" class="form-control" type="text" id="autores" name="autores" disabled>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Numero de copia</label>
                            <div class="col-sm-8">
                              <!--<input v-model="nocopia" class="form-control" type="text" id="nocopia" name="nocopia">-->
                               <v-select id="vueCopia"  :options="copias" label="name" @input="cambioCopia"></v-select>

                            </div>
                          </div>

                          <button type="button" class="btn btn-primary" @click="agregarLibro">Agregar</button>
      
                          <hr />

                          <br>
                          <label>Libros agregados</label>
                          <table class="table">

                          <thead>
                            <tr>
                              <th scope="col"> Id </th>
                              <th scope="col"> Titulo </th>
                              <th scope="col"> Autor(es) </th>
                              <th scope="col"> Edición </th>
                              <th scope="col"> No. Copia </th>
                              <th scope="col"> Eliminar </th>
                            </tr>
                          </thead>
                          <tbody>

                            <tr v-for="libroagregado in librosAgregados" :key="libroagregado.id">

                              <td>{{libroagregado.id }}</td>
                              <td>{{libroagregado.titulo }}</td>
                              <td>{{libroagregado.autores }}</td>
                              <td>{{libroagregado.edicion}} </td>
                              <td>{{libroagregado.copia }}</td>
                              <td>
                                <a href="#" @click="eliminarlibroagregado(libroagregado.id)">
                                  <i class="fa fa-trash red"></i>
                                </a> 
                              </td>
                            </tr>
                            
                          </tbody>
                        </table>


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



        <div class="modal fade "  id="vistalibros" tabindex="-1" role="dialog" aria-labelledby="vistalibros" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Libros prestados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <div class="modal-body">
                      
                      <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>Portada</th>
                              <th>Titulo</th>
                              <th>Edicion</th>
                              <th>Autor (es)</th>
                              <th>Editorial</th>
                              <th>No. Copia</th>
                              <th>ISBN</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="lp in librosPrestamo" :key="lp.id">

                              <td> <img v-bind:src="'/images/libros/' + lp.foto1" width="50px" height="70px" />  </td>
                              
                              <td> {{ lp.titulo }}</td>
                              <td> {{ lp.edicion }} </td>
                              <td>
                                    <label v-for="autor in lp.autores" :key="autor.id_autor">
                                      {{ autor.nombreAutor + " " + autor.apellidoAutor }}
                                    </label>
                              </td>
                              <td>{{lp.nombreEditorial}}</td>
                              <td>
                                {{ lp.codigoCopia }}

                              </td>
                              <td> {{ lp.isbn }} </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
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
              prestamos : {},
              editmode: false,
              libros: [],
              usuarios: [],
              librosAgregados: [],
              titulo: '',
              edicion: '',
              autores: '',
              nocopia: '',
              foto: 'defaultlibro.png',
              copias: [],
              id_libro: '',
              id_copia: '',
              librosPrestamo: [],
              form: new Form({
                  id : '',
                  fecha: '',
                  fecha_devolucion: '',
                  estatus: '',
                  id_libro: '',
                  nombreLibro: '',
                  id_usuario: '',
                  nombreUsuario: ''
              })
            }
        },
        methods: {
          verLibros(prestamo){
            $('#vistalibros').modal('show');
            ///prestamo/librosPrestamo

            axios.get("api/prestamo/librosPrestamo/"+prestamo.id).then(({ data }) => {
                this.librosPrestamo = data.data
              });


          },
          eliminarlibroagregado(id){
            
            var index_a_eliminar = -1
            for(var i=0; i < this.librosAgregados.length; i++){
              if( this.librosAgregados[i].id == id){
                index_a_eliminar = i
                break
              }
            }

            this.librosAgregados.splice(index_a_eliminar,1);
          },
          cambioUsuario(value){
            if(value == null){
              this.form.id_usuario = ""
            }else{
              this.form.id_usuario = value.id
            }
            //this.form.id_usuario = value.id
          },
          cambioCopia(value){
            if(value == null){
              this.nocopia = ""
              this.id_copia = ""
            }else{
              this.nocopia = value.name
              this.id_copia = value.id
            }

            
          },
          cambioLibro(value){
            this.copias = []
            if(!(value == null)){
              this.form.id_libro = value.id;
              this.titulo = value.titulo
              this.edicion = value.edicion
              this.autores = value.autores
              this.nocopia = ""
              this.foto = value.foto
              this.id_libro = value.id;

              if(value.foto == " "){
                this.foto = "defaultlibro.png"
              }

              axios.get("api/prestamo/copias/"+value.id).then(({ data }) => {
                for(var i = 0; i < data.data.length; i++){
                  this.copias.push({ id: data.data[i].id, name: data.data[i].codigo })
                }
              });

            }else{
              this.form.id_libro = "";
                this.titulo = ""
                this.edicion = ""
                this.autores = ""
                this.nocopia = ""
                this.foto = "defaultlibro.png"
                this.id_libro = "";
                this.form.nombreLibro = ""
            }
          },
          agregarLibro(){
            if(this.nocopia == ""){
              alert("Favor de llenar el numero de copia del libro")
            }else{
              var existe = false
              for(var i=0; i < this.librosAgregados.length; i++){
                if( this.librosAgregados[i].id == this.id_libro){
                  existe = true
                  break
                }
              }
              if(existe){
                alert("Este libro ya está agregado")
              }else{
                this.librosAgregados.push({id: this.id_libro, titulo: this.titulo, autores: this.autores, edicion: this.edicion, copia: this.nocopia, id_copia: this.id_copia})
                this.form.id_libro = "";
                this.titulo = ""
                this.edicion = ""
                this.autores = ""
                this.nocopia = ""
                this.foto = "defaultlibro.png"
                this.id_libro = "";
                this.form.nombreLibro = ""

                this.nocopia = ""
                this.copias = []
              }
            }
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              this.librosAgregados = [];
              $('#agregarPrestamo').modal('show');
          },

          editModal(prestamo){
              this.form.id_libro = "";
              this.titulo = ""
              this.edicion = ""
              this.autores = ""
              this.nocopia = ""
              this.foto = "defaultlibro.png"
              this.id_libro = "";
              this.form.nombreLibro = ""

              this.nocopia = ""
              this.copias = []
              this.editmode = true;
              this.librosAgregados = [];
              
              axios.get("api/prestamo/librosPrestamo/"+prestamo.id).then(({ data }) => {
                console.log(data.data);
                

                for(var i = 0; i < data.data.length; i++){
                  
                  var autores = ""
                  for(var j=0; j < data.data[i].autores.length; j++){
                    autores = autores + " " + data.data[i].autores[j].nombreAutor + " " + data.data[i].autores[j].apellidoAutor + " , "
                  }

                  //this.copias.push({ id: data.data[i].id, name: data.data[i].codigo })
                  this.librosAgregados.push({id: data.data[i].id, titulo: data.data[i].titulo, autores: autores, edicion: data.data[i].edicion, copia: data.data[i].prestamoLibro, id_copia: data.data[i].id_copia})

                }

              });
              this.form.reset();
              $('#agregarPrestamo').modal('show');
              this.form.fill(prestamo);
          },
          
          cargarAlumnos(){
            axios.get("api/prestamo/usuarios").then(({ data }) => {
              for(var i = 0; i < data.data.length; i++){
                var nombre = data.data[i].name + ' ' + data.data[i].last_name + ' , ' + data.data[i].matricula;
                this.usuarios.push({ id: data.data[i].id, name:nombre })
              }
            });
          },
          cargarLibros(){
            axios.get("api/copia/libros").then(({ data }) => {
              for(var i = 0; i < data.data.length; i++){
                var autores = ""
                for(var j=0; j < data.data[i].autores.length; j++){
                   autores = autores + " " + data.data[i].autores[j].nombreAutor + " " + data.data[i].autores[j].apellidoAutor + " , "
                }
                var nombre = data.data[i].titulo + ' , ' + data.data[i].edicion + ' edicion, ' + autores;
                this.libros.push({ id: data.data[i].id, name:nombre, edicion: data.data[i].edicion, autores: autores, titulo: data.data[i].titulo, foto: data.data[i].foto1 })
              }
            });
          },
          cargarPrestamos(){
              axios.get("api/prestamo").then(({ data }) => {
                this.prestamos = data
                
              });
          },
          registrarPrestamo(){
              
              var error = false
              if( this.form.id_usuario == "" ){
                alert("Complete el dato del usuario")
                error = true
              }
              console.log(this.librosAgregados);
              if( this.librosAgregados.length == 0 ){
                alert("Por favor agregue libros al prestamo")
                error = true
              }
              if(!error){
                this.$Progress.start();
                axios.post('api/prestamo',{id_usuario: this.form.id_usuario,
                                        fecha_devolucion: this.form.fecha_devolucion,
                                        libros: this.librosAgregados}).then(response => {
                  $('#agregarPrestamo').modal('hide');
                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });
                  this.$Progress.finish();
                  this.form.reset();
                  this.cargarPrestamos();
                })
                .catch(()=>{
                  Toast.fire({
                      icon: 'error',
                      title: 'Ha ocurrido un error al guardar el prestamo'
                  });
                })

              }
              
          },

          actualizarPrestamo(){
            var error = false
              if( this.form.id_usuario == "" ){
                alert("Complete el dato del usuario")
                error = true
              }
              console.log(this.librosAgregados);
              if( this.librosAgregados.length == 0 ){
                alert("Por favor agregue libros al prestamo")
                error = true
              }
              if(!error){
                this.$Progress.start();
                axios.post('api/prestamo/actualizar/'+this.form.id,{id_usuario: this.form.id_usuario,
                                        fecha_devolucion: this.form.fecha_devolucion,
                                        libros: this.librosAgregados}).then(response => {
                  
                  Toast.fire({
                        icon: 'success',
                        title: 'Prestamo actualizado correctamente'
                    });
                  this.$Progress.finish();
                  this.form.reset();
                  this.cargarPrestamos();
                  $('#agregarPrestamo').modal('hide');
                  //$('#addNew').modal('hide');
                })
                .catch(()=>{
                  Toast.fire({
                      icon: 'error',
                      title: 'Ha ocurrido un error al guardar el prestamo'
                  });
                })
              }
          },

            eliminarPrestamo(id){
                Swal.fire({
                    title: '¿Está seguro que quiere eliminar este registro?',
                    text: "Esta acción no se puede revertir",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Si'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/prestamo/'+id).then(()=>{
                            Swal.fire(
                                'Prestamo eliminado',
                                'La información del prestamo ha sido eliminada',
                                'success'
                            );
                            this.cargarPrestamos();
                        }).catch((data)=> {
                            Swal.fire("¡Falló!", data.message, "warning");
                        });
                    }
                })
            }

        },
        mounted() {
            
        },
        created() {
            this.$Progress.start();
            this.cargarLibros();
            this.cargarPrestamos();
            this.cargarAlumnos();
            this.$Progress.finish();
        }
    }
</script>
