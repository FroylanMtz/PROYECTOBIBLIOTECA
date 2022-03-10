<template>
    <section class="content">
        <div class="container-fluid">
            <h1>Inicio</h1>
            <div class="row ">

                
                

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"> <i class="fas fa-graduation-cap"></i> </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Alumnos</span>
                            <span class="info-box-number">
                            100
                            <small>%</small>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"> <i class="fas fa-chalkboard-teacher"></i> </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Profesores</span>
                            <span class="info-box-number">41,410</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"> <i class="fas fa-book"></i> </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Libros</span>
                            <span class="info-box-number">760</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"> <i class="fas fa-undo-alt"></i> </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Prestamos realizados</span>
                            <span class="info-box-number">2,000</span>
                        </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- Left col -->
                <div class="col-md-12">
                    <!-- MAP & BOX PANE -->
                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ultimos prestamos realizados</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
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
                            <th>Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="prestamo in prestamos" :key="prestamo.id">

                            <td>{{prestamo.id}}</td>
                            <td>{{ prestamo.matricula}}</td>
                            <td>{{prestamo.nombreUsuario + " " + prestamo.apellidoUsuario }}</td>
                            <td>{{prestamo.fecha | myDate  }}</td>
                            <td>{{prestamo.fecha_devolucion | myDate }}</td>
                            <td>
                                <span v-if="prestamo.estatus == 'Activo'" class="badge badge-success"> {{prestamo.estatus}} </span>
                                <span v-if="prestamo.estatus == 'Finalizado'" class="badge badge-primary"> {{prestamo.estatus}} </span>
                                <span v-if="prestamo.estatus == 'Retrasado'" class="badge badge-danger"> {{prestamo.estatus}} </span>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>

                <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- /.card -->
            </div>

            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
</template>

<script>
    export default {
        data () {
            return {
              prestamos : {}
            }
        },

        methods: {
            cargarPrestamos(){
                axios.get("api/prestamo").then(({ data }) => {
                    this.prestamos = data
                    
                });
            },

        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.cargarPrestamos();
        }
    }
</script>
