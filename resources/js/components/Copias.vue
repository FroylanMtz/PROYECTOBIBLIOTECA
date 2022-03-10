<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <h1>Copias</h1>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de copias</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addNew" @click="newModal" >
                      <i class="fa fa-plus-square"></i>
                      Registrar copia
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
                      <th>Libro</th>
                      <th>Autores</th>
                      <th>Edición</th>
                      <th>Codigo de copia</th>
                      <th>Fecha adquisición</th>
                      <th>Obtención</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="copia in copias" :key="copia.id">

                      <td>{{copia.id}}</td>
                      <td> <img v-bind:src="'/images/libros/' + copia.libro[0].foto1" width="50px" height="70px" />  </td>
                      <td>{{copia.libro[0].titulo}}</td>
                      
                      
                      
                      <td>
                        
                        <span v-for="autor in copia.autores" :key="autor.id_autor">
                          {{ autor.nombreAutor + " " + autor.apellidoAutor + ", "}}

                        </span>
                      </td>

                      <td> {{copia.libro[0].edicion}} </td>
                      <td>{{copia.codigo}}</td>
                      

                      <td>{{copia.fecha_adquisicion }}</td>
                      <td>{{copia.obtencion}}</td>

                      <td>
                        <a href="#" @click="editModal(copia)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="eliminarCopia(copia.id)">
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

                    <h5 class="modal-title" v-show="!editmode">Registrar copia</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar información de la copia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? actualizarCopia() : registrarCopia()">
                    <div class="modal-body">
                      
                        
                        <div class="form-group">
                            <label>Libro</label>
                            <v-select id="vueLibro" v-model="form.nombreLibro" :options="libros" label="name" @input="cambioCopia"></v-select>
                        </div>

                        <div class="form-group">
                            <label>Código</label>
                            <input v-model="form.codigo" type="text" name="codigo"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('codigo') }">
                            <has-error :form="form" field="codigo"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Fecha de adquisición</label>
                            <input v-model="form.fecha_adquisicion" type="date" name="adquisicion"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('adquisicion') }">
                            <has-error :form="form" field="adquisicion"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Forma de obtención</label>
                            <input v-model="form.obtencion" type="text" name="obtencion"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('obtencion') }">
                            <has-error :form="form" field="obtencion"></has-error>
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
              copias : [],
              libros: [],

              // Create a new form instance
              form: new Form({
                  id : '',
                  id_libro: '',
                  libro: '',
                  codigo: '',
                  fecha_adquisicion: '',
                  obtencion: '',
                  nombreLibro: ''
              })
            }
        },
        methods: {

          cambioCopia(value){
            this.form.id_libro = value.id;
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
              this.autores_libro = []
              this.form.autor = []

          },
          
          cargarLibros(){

            axios.get("api/copia/libros").then(({ data }) => {
              for(var i = 0; i < data.data.length; i++){
                var autores = ""
                for(var j=0; j < data.data[i].autores.length; j++){
                   autores = autores + " " + data.data[i].autores[j].nombreAutor + " " + data.data[i].autores[j].apellidoAutor
                }
                var nombre = data.data[i].titulo + ' , ' + data.data[i].edicion + ' edicion, ' + autores;
                this.libros.push({ id: data.data[i].id, name:nombre })
              }
            });


          },
          cargarCopias(){
            // if(this.$gate.isAdmin()){
            axios.get("api/copia").then(({ data }) => {
              this.copias = data.data

              /*for(var i =0; i < data.data.length; i++){
                this.copias.push(data.data[i])
              }*/

              console.log(data.data);
              
            });
            // }
          },
          editModal(copia){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(copia);

              this.form.id_libro = copia.libro[0].id_libro
              
              var autores = ""
              for(var j=0; j < copia.autores.length; j++){
                   autores = autores + " " + copia.autores[j].nombreAutor + " " + copia.autores[j].apellidoAutor
              }
              var nombre = copia.libro[0].titulo + ' , ' + copia.libro[0].edicion + ' edicion, ' + autores;
              this.form.nombreLibro = nombre
          },


          actualizarCopia(){

            this.$Progress.start();
            // console.log('Editing data');
            this.form.put('api/copia/'+this.form.id)
            .then((response) => {
                // success
                $('#addNew').modal('hide');
                Toast.fire({
                  icon: 'success',
                  title: response.data.message
                });
                this.$Progress.finish();
                    //  Fire.$emit('AfterCreate');

                this.cargarCopias();
            })
            .catch(() => {
                this.$Progress.fail();
            });
            
          },
          eliminarCopia(id){
              Swal.fire({
                  title: '¿Esta seguro?',
                  text: "Esta acción no se puede revertir",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Si'
                  }).then((result) => {
                      if (result.value) {
                              this.form.delete('api/copia/'+id).then(()=>{
                                      Swal.fire(
                                      'Copia eliminada',
                                      'La información de la copia ha sido eliminada',
                                      'success'
                                      );
                                  this.cargarCopias();
                              }).catch((data)=> {
                                Swal.fire("Failed!", data.message, "warning");
                            });
                      }
                  })
          },


          registrarCopia(){
              this.$Progress.start();

              this.form.post('api/copia')
              .then((data)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.cargarCopias();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Ha ocurrido un error, vuelve a intentarlo'
                  });
              })
          }

        },
        

        mounted() {
            
        },
        created() {
            this.$Progress.start();
            this.cargarCopias();
            this.cargarLibros();
            this.$Progress.finish();
        }
    }
</script>
