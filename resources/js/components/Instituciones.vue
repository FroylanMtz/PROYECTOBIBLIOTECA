<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
            <h1>Escuelas</h1>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Listado de escuelas</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addNew" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Registrar escuela
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Logotipo</th>
                      <th>Código</th>
                      <th>Nombre</th>
                      <th>Telefono</th>
                      <th>Extension</th>
                      
                      <th>Administracion</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="institucion in instituciones" :key="institucion.id">

                      <td>{{institucion.id}}</td>
                      <td> <img v-bind:src="'/images/' + institucion.logotipo" width="50px" height="50px" />  </td>
                      <td>{{institucion.codigo}}</td>
                      <td>{{institucion.nombre}}</td>
                      <td>{{institucion.telefono}}</td>
                      <td>{{institucion.extension}}</td>
                      <td>
                        <a href="#" @click="editModal(institucion)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="eliminarInstitucion(institucion.id)">
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

                    <h5 class="modal-title" v-show="!editmode">Registrar nueva institucion</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar información de institucion</h5>

                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? actualizarInstitucion() : registrarInstitucion()">
                    <div class="modal-body">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input v-model="form.nombre" type="text" name="nombre" class="form-control">

                                <label>Codigo</label>
                                <input v-model="form.codigo" type="text" name="codigo" class="form-control" >

                                <label>Telefono</label>
                                <input v-model="form.telefono" type="phone" name="telefono" class="form-control">

                                <label>Extension</label>
                                <input v-model="form.extension" type="text" name="extension" class="form-control">

                                <label>Logo</label>
                                <img :src="image" class="img-responsive" height="70" width="90">
                                <input id="input_logo" type="file" v-on:change="onImageChange" class="form-control">

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
              editmode: false,
              instituciones : {},
              form: new Form({
                  id : '',
                  codigo : '',
                  nombre: '',
                  telefono: '',
                  extension: '',
                  logotipo: '',
              })
            }
        },
        methods: {
          
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

          cargarInstituciones(){
            // if(this.$gate.isAdmin()){
              axios.get("api/institucion").then(({ data }) => {
                console.log(data.data);
                this.instituciones = data.data
              });
            // }
          },
          
          //registrarInstitucion(){
          registrarInstitucion(){
              this.$Progress.start();

              axios.post('api/institucion',{logotipo:this.form.logotipo,extension:this.form.extension,telefono:this.form.telefono,id: this.form.id, codigo:this.form.codigo,nombre:this.form.nombre,image: this.image}).then(response => {
                console.log(response);

                $('#addNew').modal('hide');

                Toast.fire({
                      icon: 'success',
                      title: response.data.message
                  });
                this.$Progress.finish();

                this.image = null;
                this.form.reset();

                this.cargarInstituciones();

              })
              .catch(()=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Some error occured! Please try again'
                });
              })
          },

            actualizarInstitucion(){
                this.$Progress.start();

                axios.post('api/institucion/update/'+this.form.id,{logotipo:this.form.logotipo,extension:this.form.extension,telefono:this.form.telefono,id: this.form.id, codigo:this.form.codigo,nombre:this.form.nombre,image: this.image}).then(response => {
                  console.log(response);

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
                      title: 'Some error occured! Please try again'
                  });
                })
            },

            editModal(user){

                this.editmode = true;
                this.image = null;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            eliminarInstitucion(id){
                Swal.fire({
                    title: '¿Esta seguro que desea eliminar la institución?',
                    text: "Esta acción no puede revertirse",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/institucion/'+id).then(()=>{
                                        Swal.fire(
                                        'Eliminado!',
                                        'La institucion ha sido eliminada',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.cargarInstituciones();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            }

        },
        mounted() {
            
        },
        created() {
            this.$Progress.start();

            this.cargarInstituciones();

            this.$Progress.finish();
        }
    }
</script>
