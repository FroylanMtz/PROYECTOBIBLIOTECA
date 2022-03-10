<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
            
            <h1>Libros</h1>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de libros</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addNew" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Registrar libro
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Portada</th>
                      <th>ISBN</th>
                      <th>Titulo</th>
                      <th>Autor (es)</th>
                      <th>Idioma</th>
                      <th>Edición</th>
                      <th>Año</th>
                      <th>Genero</th>
                      <th>Descripcion</th>
                      <th>Fecha</th>
                      <th>Region</th>
                      <th>Editorial</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="libro in libros" :key="libro.id">

                      <td>{{ libro.id}}</td>
                      <td> <img v-bind:src="'/images/libros/' + libro.foto1" width="100px" height="130px" />  </td>
                      <td>{{ libro.isbn }}</td>
                      <td>{{ libro.titulo }}</td>
                      <td>
                        {{ libro.autor }}
                        <span v-for="autor in libro.autores" :key="autor.id_autor">
                          {{ autor.nombreAutor + " " + autor.apellidoAutor + "  "}}

                        </span>
                      </td>
                      <td>{{ libro.idioma }}</td>
                      <td>{{ libro.edicion }}</td>
                      <td>{{ libro.anio }}</td>
                      <td>{{ libro.genero }}</td>
                      <td>{{ libro.descripcion }}</td>
                      <td>{{ libro.fecha_lanzamiento }}</td>
                      <td>{{ libro.region }}</td>
                      
                      <td>{{ libro.nombreEditorial }}</td>

                      <td>
                        <a href="#" @click="editModal(libro)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="eliminarLibro(libro.id)">
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
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Registrar libro</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar libro</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? actualizarLibro() : registrarLibro()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Portada</label>
                            <img :src="image" class="img-responsive" height="70" width="90">
                            <input id="input_portada" type="file" v-on:change="onImageChange" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>ISBN</label>
                            <input v-model="form.isbn" type="text" name="isbn" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Titulo</label>
                            <input v-model="form.titulo" type="text" name="titulo" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Autor</label>
                            <br>
                            <v-select multiple id="vueAutor" v-model="autores_libro" :options="autores" label="name" @input="setSelected" ></v-select>
                        </div>

                        <div class="form-group">
                            <label>Idioma</label>
                            <input v-model="form.idioma" type="text" name="idioma" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Edición</label>
                            <input v-model="form.edicion" type="text" name="edicion" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Año</label>
                            <input v-model="form.anio" type="text" name="anio" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Genero</label>
                            <input v-model="form.genero" type="text" name="genero" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <input v-model="form.descripcion" type="text" name="descripcion" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Fecha de lanzamiento</label>
                            <input v-model="form.fecha_lanzamiento" type="date" name="lanzamiento" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Pais</label>
                            <input v-model="form.region" type="text" name="region" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Editorial</label>
                            <br>
                            <v-select id="vueEditorial" v-model="form.nombreEditorial" :options="editoriales" label="name" @input="cambioEditoriales"></v-select>
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
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
              image: '',
              libros : {},
              autores: [],
              autores_libro: [],
              editoriales: [],
              editmode: false,
              checkautoress: false,
              checkeditorialess: false,
              // Create a new form instance
              form: new Form({
                  id : '',
                  isbn: '',
                  titulo: '',
                  autor: '',
                  id_autor: '',
                  idioma: '',
                  edicion: '',
                  anio: '',
                  genero: '',
                  descripcion: '',
                  fecha_lanzamiento: '',
                  region: '',
                  foto1: '',
                  foto2: '',
                  editorial: '',
                  nombreEditorial: ''
              })
            }
        },
        methods: {
          newModal(){
              this.editmode = false;
              this.form.reset();
              this.image = null
              document.getElementById("input_portada").value = "";
              $('#addNew').modal('show');
              this.autores_libro = []
              this.form.autor = []

          },
          editModal(libro){
              this.editmode = true;
              document.getElementById("input_portada").value = "";
              this.image = null;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(libro);
              this.form.nombreEditorial = libro.nombreEditorial;
              this.autores_libro = []
              this.form.autor = []
              for(var i=0; i < libro.autores.length; i++ ){
                var nombre = libro.autores[i].nombreAutor + " " + libro.autores[i].apellidoAutor;
                this.autores_libro.push({ id: libro.autores[i].id_autor,  name:nombre });
              }
              this.form.autor = this.autores_libro
          },
          cambioEditoriales(value){
            this.form.editorial = value.id;
          },
          setSelected(value) {
            this.form.autor = value;
            console.log(value);
          },
          obtenerEditoriales(){
            axios.get("api/libro/editoriales").then(({ data }) => {
              for(var i = 0; i < data.data.length; i++){
                var nombre = data.data[i].nombre;
                this.editoriales.push({ id: data.data[i].id, name:nombre })
              }
            });
          },
          obtenerAutores(){
            axios.get("api/libro/autores").then(({ data }) => {
              for(var i = 0; i < data.data.length; i++){
                var nombre = data.data[i].nombres + ' ' + data.data[i].apellidos;
                this.autores.push({ id: data.data[i].id, name:nombre })
              }
            });
          },
          onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
          },
          createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
          },
          cargarLibros(){
            axios.get("api/libro").then(({ data }) => {
              this.libros = data.data
              console.log(data.data);
            });

          },
          actualizarLibro(){
            
            this.$Progress.start();
            axios.post('api/libro/update/'+this.form.id,{

                isbn: this.form.isbn,
                titulo:this.form.titulo,
                autor:this.form.autor,
                idioma: this.form.idioma,
                edicion:this.form.edicion,
                anio:this.form.anio,
                genero: this.form.genero,
                descripcion: this.form.descripcion,
                fecha_lanzamiento: this.form.fecha_lanzamiento,
                region: this.form.region,
                foto1: this.form.foto1,
                foto2: this.form.foto2,
                editorial: this.form.editorial,
                image: this.image
              
              }).then(response => {
              

              $('#addNew').modal('hide');

              Toast.fire({
                  icon: 'success',
                  title: response.data.message
              });

              this.$Progress.finish();
              this.cargarLibros();

            })
            .catch(()=>{
              Toast.fire({
                  icon: 'error',
                  title: 'Ha ocurrido un error, intente nuevamente'
              });
            })

          },
          eliminarLibro(id){
              Swal.fire({
                  title: '¿Está seguro?',
                  text: "No podras revertir esta acción",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
              }).then((result) => {
                  if (result.value) {
                      this.form.delete('api/libro/'+id).then(()=>{
                          Swal.fire(
                              'Eliminado',
                              'El registro ha sido eliminado',
                              'success'
                          );
                          this.cargarLibros();
                      }).catch((data)=> {
                          Swal.fire("Failed!", data.message, "warning");
                      });
                  }
              })

          },
          registrarLibro(){

              this.$Progress.start();

              axios.post('api/libro',{
                  isbn: this.form.isbn,
                  titulo:this.form.titulo,
                  autor:this.form.autor,
                  idioma: this.form.idioma,
                  edicion:this.form.edicion,
                  anio:this.form.anio,
                  genero: this.form.genero,
                  descripcion: this.form.descripcion,
                  fecha_lanzamiento: this.form.fecha_lanzamiento,
                  region: this.form.region,
                  foto1: this.form.foto1,
                  foto2: this.form.foto2,
                  editorial: this.form.editorial,
                  image: this.image
                }).then(response => {
                
                
                  console.log(response);
                  $('#addNew').modal('hide');
                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });
                  this.$Progress.finish();
                  this.image = null;
                  this.form.reset();
                  this.cargarLibros();

              })
              .catch(()=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error. Intenta nuevamente'
                });
              })
          }
        },
        mounted() {            
        },
        created() {
            this.$Progress.start();
            this.obtenerEditoriales();
            this.obtenerAutores();
            this.cargarLibros();
            this.$Progress.finish();
        }
    }
</script>
