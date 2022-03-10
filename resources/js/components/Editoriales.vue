<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Editoriales</h1>
                <div class="card" v-if="$gate.isAdmin()">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Editoriales </h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success" @click="newModal">
                            <i class="fa fa-plus-square"></i>
                            Registrar editorial
                        </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Empresa</th>
                                    <th>Localización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="editorial in editoriales" :key="editorial.id">
                                    <td>{{editorial.id}}</td>
                                    <td>{{editorial.nombre}}</td>
                                    <td>{{editorial.empresa}}</td>
                                    <td>{{editorial.localizacion}}</td>
                                    <td>
                                        <a href="#" @click="modalEditar(editorial)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        |
                                        <a href="#" @click="eliminarEditorial(editorial.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <h5 class="modal-title" v-show="!editmode">Agregar editorial</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar editorial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actualizarEditorial() : crearEditorial()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input v-model="form.nombre" type="text" name="nombre" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Empresa</label>
                            <input v-model="form.empresa" type="text" name="empresa" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Nacionalidad</label>
                            <input v-model="form.localizacion" type="text" name="localizacion" class="form-control">
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
                editoriales : {},
                form: new Form({
                    id : '',
                    nombre : '',
                    empresa: '',
                    localizacion: '',
                })
            }
        },
        methods: {
            actualizarEditorial(){
                this.$Progress.start();
                this.form.put('api/editorial/'+this.form.id)
                .then((response) => {
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                    this.cargarEditoriales();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            modalEditar(user){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            eliminarEditorial(id){
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "No podras revertir esta acción",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/editorial/'+id).then(()=>{
                            Swal.fire(
                                'Eliminado',
                                'El registro ha sido eliminado',
                                'success'
                            );
                            this.cargarEditoriales();
                        }).catch((data)=> {
                            Swal.fire("Failed!", data.message, "warning");
                        });
                    }
                })
            },
            cargarEditoriales(){
                this.$Progress.start();
                if(this.$gate.isAdmin()){
                    axios.get("api/editorial").then(({ data }) => {
                        this.editoriales = data.data
                    });
                }
                this.$Progress.finish();
            },
            crearEditorial(){
                this.form.post('api/editorial')
                .then((response)=>{
                    $('#addNew').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });
                    this.$Progress.finish();
                    this.cargarEditoriales();
                })
                .catch(()=>{
                    Toast.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error. Vuelva a intentarlo'
                    });
                })
            }
        },
        mounted() {
            console.log('User Component mounted.')
        },
        created() {
            this.$Progress.start();
            this.cargarEditoriales();
            this.$Progress.finish();
        }
    }
</script>
