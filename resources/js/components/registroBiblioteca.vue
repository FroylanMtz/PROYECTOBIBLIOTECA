<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Registrar biblioteca</h3>


                <div class="card-body">

                    <form @submit.prevent="createProduct">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input v-model="form.description" type="text" name="description"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                            <has-error :form="form" field="description"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  </form>

                </div>
              </div>

            </div>
            <!-- /.card -->
          </div>
        </div>

        
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {

              // Create a new form instance
              form: new Form({
                  id : '',
                  name: '',
                  description: '',
              })
            }
        },
        methods: {

          loadCategories(){
            // if(this.$gate.isAdmin()){
              axios.get("api/category").then(({ data }) => (this.categories = data.data));
            // }
          },
          
          createCategory(){
              this.$Progress.start();

              this.form.post('api/category')
              .then((data)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadCategories();

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
            
        },
        created() {
            this.$Progress.start();

            //this.loadCategories();

            this.$Progress.finish();
        }
    }
</script>
