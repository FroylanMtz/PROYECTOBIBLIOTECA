<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
            <h1>Bibliotecas</h1>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de bibliotecas</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addNew" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Registrar biblioteca
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Código</th>
                      <th>Nombre</th>
                      <th>Facultad</th>
                      <th>Edificio</th>
                      <th>Aula</th>
                      <th>Piso</th>
                      <th>Institucion</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="biblioteca in bibliotecas" :key="biblioteca.id">

                      <td>{{biblioteca.id}}</td>
                      <td>{{biblioteca.codigo}}</td>
                      <td>{{biblioteca.nombre}} </td>
                      <td>{{biblioteca.facultad}}</td>
                      <td>{{biblioteca.edificio}}</td>
                      <td>{{biblioteca.aula}}</td>
                      <td>{{biblioteca.piso}}</td>

                      <td>

                        <span v-for="inst in instituciones" v-if="inst.id===biblioteca.id_institucion">
                          {{ inst.nombre }}
                        </span>

                      </td>

                      <td>
                        <a href="#" @click="editModal(biblioteca)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="eliminarInstitucion(biblioteca.id)">
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

                    <h5 class="modal-title" v-show="!editmode">Registrar nueva biblioteca</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar información de biblioteca</h5>

                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? actualizarInstitucion() : registrarInstitucion()">
                    <div class="modal-body">
                            <div class="form-group">

                                
                                <div class="form-group">
                                    <label>Institución</label>

                                    <select v-model="form.id_institucion" id="type" class="form-control" required>
                                        <option v-for="inst in instituciones" v-bind:value="inst.id">
                                            {{ inst.nombre }}
                                        </option>
                                    </select>
                                    <!--<has-error :form="form" field="type"></has-error>-->
                                </div>

                                <label>Código</label>
                                <input v-model="form.codigo" type="text" name="codigo"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('codigo') }">
                                <has-error :form="form" field="codigo"></has-error>

                                <label>Nombre</label>
                                <input v-model="form.nombre" type="text" name="nombre"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('nombre') }">
                                <has-error :form="form" field="nombre"></has-error>

                                <label>Facultad</label>
                                <input v-model="form.facultad" type="text" name="facultad"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('facultad') }">
                                <has-error :form="form" field="facultad"></has-error>


                                <label>Edificio</label>
                                <input v-model="form.edificio" type="text" name="edificio"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('edificio') }">
                                <has-error :form="form" field="edificio"></has-error>

                                <label>Aula</label>
                                <input v-model="form.aula" type="text" name="aula"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('aula') }">
                                <has-error :form="form" field="aula"></has-error>

                                <label>Piso</label>
                                <input v-model="form.piso" type="text" name="piso"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('piso') }">
                                <has-error :form="form" field="piso"></has-error>

                                


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
              editmode: false,
              bibliotecas : {},
              instituciones: {},
              form: new Form({
                  id : '',
                  codigo: '',
                  nombre: '',
                  facultad: '',
                  edificio: '',
                  aula: '',
                  piso: '',
                  id_institucion: '',
              })
            }
        },
        methods: {

          obtenerInstituciones(){
              //instituciones
              axios.get("api/institucion").then(({ data }) => {
                  console.log(data.data);
                  this.instituciones = data.data
              });
          },

          cargarInstituciones(){
            // if(this.$gate.isAdmin()){
              axios.get("api/biblioteca").then(({ data }) => (this.bibliotecas = data.data));
            // }
          },
          
          registrarInstitucion(){
              this.$Progress.start();

              console.log(this.form);

              this.form.post('api/biblioteca')
              .then((response)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });

                  this.$Progress.finish();
                  this.cargarInstituciones();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Ha ocurrido un eror, intente nuevamente'
                  });
              })

          },

            actualizarInstitucion(){
                this.$Progress.start();

                this.form.put('api/biblioteca/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                    this.cargarInstituciones();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },

            editModal(biblioteca){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(biblioteca);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            eliminarInstitucion(id){
                Swal.fire({
                    title: '¿Estas seguro que deseas eliminar esta biblioteca?',
                    text: "Esta acción no puede revertirse",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                    }).then((result) => {

                         if (result.value) {
                                this.form.delete('api/biblioteca/'+id).then(()=>{
                                        Swal.fire(
                                        '¡Eliminado!',
                                        'La biblioteca ha sido eliminada',
                                        'success'
                                        );
                                    this.cargarInstituciones();
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

            this.obtenerInstituciones();
          
            this.cargarInstituciones()

            this.$Progress.finish();
        }
    }
</script>
