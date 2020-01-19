<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tag Table</h3>

                    <div class="card-tools">
                        <button class="btn btn-success" data-toggle="modal" data-target="#ADDCategory">Add Tag<i class="fas fa-user-plus fa-fw"></i></button>
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

                    <tr v-for="tag in getAllTag" :key='tag.id'>
                        <td>{{ tag.id }}</td>
                        <td>{{ tag.name }} </td>
                        <td>{{ tag.slug }}</td>
                        <td>{{ tag.created_at | formatDate }}</td>
                    <td>
                        <a href="#" data-id="tag.id" @click.prevent="update(tag)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        
                        <a href="#" @click.prevent = "deleteTag(tag.id)">
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
                        <h5 class="modal-title" id="addNewLabel">Add Tag</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editMode ? updateTag() : createTag()"  >
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
            createTag(){
                this.form.post("/api/tag")
                    .then( ()=>{
                        Toast.fire({
                            icon: 'success',
                            title: 'Tag created successfully'
                        })
                        this.reLoadTag()
                        this.form.reset()
                        $("#ADDCategory").modal('hide')
                        console.log('response')
                    })
                    .catch(()=>{
                        console.log("error function")
                    })

            },
            update(tag){
                console.log('update function')
                this.editMode = true
                 $("#ADDCategory").modal('show')
                this.form.reset()
                this.form.fill(tag)
                $('#btnSave').html('Update')
                $('#addNewLabel').html("Update tag")
            },
            updateTag(){
                this.form.put('api/tag/'+this.form.id)
                    .then((response)=>{
                        Toast.fire({
                            icon: 'success',
                            title: 'tag updated successfully'
                        })
                        this.reLoadTag()
                        console.log(response)
                        $("#ADDCategory").modal('hide')
                        this.editMode=false
                        $("#btnSave").html("Create")
                        this.form.reset()
                        $("#addNewLabel").html("Add Tag")

                    })
                    .catch(()=>{
                        console.log("erro something")
                    })
            },
            deleteTag(id){
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
                        this.form.delete('api/tag/'+id)
                        .then((response)=>{
                            Swal.fire(
                              'Deleted!',
                              'User deleted successfully',
                              'success'
                            )
                            console.log('response')
                            this.reLoadTag();
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
            reLoadTag(){
                this.$store.dispatch("allTagFromDatabase")
            }
        },
       
        // created() {
        //     this.loadCategory();
        // }

        mounted() {

            this.reLoadTag()

        },
        computed: {
            getAllTag(){ //final output from here
                return this.$store.getters.getTagFormGetters
            }
      },
    }
</script>
