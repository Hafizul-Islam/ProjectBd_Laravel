<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Category Table</h3>

                    <div class="card-tools">
                        <button class="btn btn-success" data-toggle="modal" data-target="#ADDCategory">Add Category<i class="fas fa-user-plus fa-fw"></i></button>
                    </div>
                </div>
             
                 <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr> 

                    <tr v-for="category in getAllCategory" :key='category.id'>
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }} </td>
                        <td>{{ category.slug }}</td>
                        <td>{{ category.created_at | formatDate }}</td>
                    <td>
                        <a href="#" data-id="category.id" @click.prevent="update(category)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        
                        <a href="#" @click.prevent = "deleteCategory(category.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                    </td> 
                    </tr>
                    </tbody></table>
                </div> 
                <div class="card-footer">
                 
                </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="ADDCategory" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editMode ? updateCategory() : createCategory()"  >
                    <div class="modal-body">
                         <div class="form-group">
                            <input v-model="form.name" type="text" name="name"
                                placeholder="Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                         <div class="form-group">
                            <input v-model="form.slug" type="text" name="slug" disabled="true" 
                                placeholder="slug category" 
                                class="form-control" :class="{ 'is-invalid': form.errors.has('slug') }">
                            <has-error :form="form" field="slug"></has-error>
                        </div>
                    </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" id="btnSave" class="btn btn-primary">Create</button>
                           
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                input: '',
                editMode:false,
                categories: {},
                form: new Form({
                    id:'',
                    name:'',
                    slug:'',
                })
            }
        },
        methods:{
            createCategory(){
                this.form.post("/api/category")
                    .then( ()=>{
                        Toast.fire({
                            icon: 'success',
                            title: 'Category created successfully'
                        })
                        this.reLoadCategory()
                        this.form.reset()
                        $("#ADDCategory").modal('hide')
                        console.log('response')
                    })
                    .catch(()=>{
                        console.log("error function")
                    })

            },
            update(category){
                console.log('update function')
                this.editMode = true
                 $("#ADDCategory").modal('show')
                this.form.reset()
                this.form.fill(category)
                $('#btnSave').html('Update')
                $('#addNewLabel').html("Update Category")
            },
            updateCategory(){
                this.form.put('api/category/'+this.form.id)
                    .then((response)=>{
                        Toast.fire({
                            icon: 'success',
                            title: 'Category updated successfully'
                        })
                        this.reLoadCategory()
                        console.log(response)
                        $("#ADDCategory").modal('hide')
                        this.editMode=false
                        $("#btnSave").html("Create")
                        this.form.reset()
                        $("#addNewLabel").html("Add Category")

                    })
                    .catch(()=>{
                        console.log("erro something")
                    })
            },
            deleteCategory(id){
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result)=>{
                    if(result.value){
                        this.form.delete('api/category/'+id)
                        .then((response)=>{
                            Swal.fire(
                              'Deleted!',
                              'User deleted successfully',
                              'success'
                            )
                            console.log('response')
                            this.reLoadCategory();
                        }).catch(()=>{
                            Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Something went wrong!',
                              footer: '<a href>Why do I have this issue?</a>'
                            })

                        })
                    }
                })
            },
            reLoadCategory(){
                console.log('reload fun')
                this.$store.dispatch("allCategoryFromDatabase")
            }
        },
       
        // created() {
        //     this.loadCategory();
        // }

        mounted() {
            console.log('moun fun')
            this.reLoadCategory()

        },
        computed: {

            getAllCategory(){ //final output from here
                return this.$store.getters.getCategoryFormGetters
            }
      },
    }
</script>
