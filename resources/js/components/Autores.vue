<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Autores</h1>
                <div class="card" v-if="$gate.isAdmin()">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Autores </h3>
                        <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success" @click="newModal">
                            <i class="fa fa-plus-square"></i>
                            Registrar autor
                        </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Nacionalidad</th>
                                    <th>Genero</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="autor in autores" :key="autor.id">
                                    <td>{{autor.id}}</td>
                                    <td>{{autor.nombres}}</td>
                                    <td>{{autor.apellidos}}</td>
                                    <td>{{autor.nacionalidad}}</td>
                                    <td>{{autor.genero}}</td>
                                    <td>{{autor.fecha_nacimiento | myDate}}</td>
                                    <td>
                                        <a href="#" @click="modalEditar(autor)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        |
                                        <a href="#" @click="eliminarAutor(autor.id)">
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
                    <h5 class="modal-title" v-show="!editmode">Agregar autor</h5>
                    <h5 class="modal-title" v-show="editmode">Actualizar autor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? actualizarAutor() : crearAutores()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input v-model="form.nombres" type="text" name="nombre" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Apellido</label>
                            <input v-model="form.apellidos" type="text" name="apellido" class="form-control" >
                        </div>
                    
                        <div class="form-group">
                            <label>Nacionalidad</label>
                            <input v-model="form.nacionalidad" type="text" name="nacionalidad" class="form-control">
                        </div>
                    
                        <div class="form-group">
                            <label>Sexo</label>
                            <select name="type" v-model="form.genero" id="type" class="form-control">
                                <option>Masculino</option>
                                <option>Femenino</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input v-model="form.fecha_nacimiento" type="date" name="fecha_nacimiento" class="form-control">
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
                autores : {},
                form: new Form({
                    id : '',
                    nombres : '',
                    apellidos: '',
                    nacionalidad: '',
                    genero: '',
                    fecha_nacimiento: '',
                })
            }
        },
        methods: {
            actualizarAutor(){
                this.$Progress.start();
                this.form.put('api/autor/'+this.form.id)
                .then((response) => {
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                    this.cargarAutores();
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
            eliminarAutor(id){
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "No podras revertir esta acción",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/autor/'+id).then(()=>{
                            Swal.fire(
                                'Eliminado',
                                'El registro ha sido eliminado',
                                'success'
                            );
                            this.cargarAutores();
                        }).catch((data)=> {
                            Swal.fire("Failed!", data.message, "warning");
                        });
                    }
                })
            },
            cargarAutores(){
                this.$Progress.start();
                if(this.$gate.isAdmin()){
                    axios.get("api/autor").then(({ data }) => {
                        this.autores = data.data
                        console.log(data);
                    });
                }
                this.$Progress.finish();
            },
            crearAutores(){
                this.form.post('api/autor')
                .then((response)=>{
                    $('#addNew').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });
                    this.$Progress.finish();
                    this.cargarAutores();
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
            this.cargarAutores();
            this.$Progress.finish();
        }
    }
</script>
