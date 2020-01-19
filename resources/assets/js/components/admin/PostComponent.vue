<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users Table</h3>

                    <div class="card-tools">
                        <button class="btn btn-success" data-toggle="modal" data-target="#addPost">Add New<i class="fas fa-user-plus fa-fw"></i></button>
                    </div>
                </div>
             
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Body</th>
                        <th>Iamge</th>
                        <th>Time At</th>
                        <th>Action</th>
                    </tr> 

                    <tr v-for="post in getAllPost" :key='post.id'>
                        <td>{{ post.id }}</td>
                        <td>{{ post.title }} </td>
                        <td>{{ post.slug }}</td>
                        <td>{{ post.body }}</td>
                        <td><img :src="`img/profile/${post.image}`" class="profile-user-img img-fluid img-circle" style="height:40px; width:40px;"></td>
  
                        <td>{{ post.created_at | formatDate}}</td>

                        <td>
                            <a href="#" data-id="post.id" @click.prevent="update(post)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            |
                            <a href="#" @click.prevent="deletePost(post.id)">
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


        <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Add New post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editMode ? updatePost() : createPost()" enctype="multipart/form-data">
                    <div class="modal-body">
                         <div class="form-group">
                            <label for="title">Bost Title</label>
                            <input v-model="form.title" type="text" name="title"
                                placeholder="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                            <has-error :form="form" field="title"></has-error>
                        </div>

                         <div class="form-group">
                            <label for="Category">Select Category</label>
                            <select name="category[]" v-model="form.category" id="category" class="form-control" :class="{ 'is-invalid': form.errors.has('category') }">
                                <option value="" selected="">Select Category</option>
                                <option v-for="category in getAllCategory" 
                                        :value="category.id">
                                       {{ category.name }}
                                </option>

                            </select>
                            <has-error :form="form" field="category"></has-error>
                        </div>

                        <div class="form-group">
                            <label for="Tag">Select Tag</label>
                            <select name="tag[]" v-model="form.tag" id="tag" class="form-control" :class="{ 'is-invalid': form.errors.has('tag') }">
                                <option value="" selected="">Select Tag</option>
                                <option v-for="tag in getAllTag" 
                                        :value="tag.id">
                                       {{ tag.name }}
                                </option>

                            </select>
                            <has-error :form="form" field="tag"></has-error>
                        </div>
                        

                        <div class="form-group">
                            <label for="inputSkills" class="col-sm-2 col-form-label">Avatar</label>
                            <div class="col-sm-10">
                                <input type="file" @change='uploadPhoto' name="photo" class="form-control" :class="{ 'is-invalid': form.errors.has('photo') }">
                                <has-error :form="form" field="photo"></has-error> 
                            </div>
                        </div>
                        <img class="profile-user-img img-fluid img-circle" :src="getPhoto()" alt="Post picture">

                        <div class="form-group">
                            <label for="body">Post Body</label>
                            <textarea name="body" v-model="form.body" id="body" class="form-control" :class="{ 'is-invalid': form.errors.has('body') }"> </textarea>
                            <has-error :form="form" field="body"></has-error>
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
                tag:{},
                form: new Form({
                    id:'',
                    title:'',
                    slug:'',
                    category:'',
                    tag:'',
                    body:'',
                    user_id:this.$userId,
                    photo:'',
                })
            }
        },
        methods:{
            uploadPhoto(e){
                let file = e.target.files[0];
                let reader = new FileReader();  

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                    //console.log('RESULT', reader.result)
                        this.form.photo = reader.result;
                    }              
                     reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },
            //For getting Instant Uploaded Photo
            getPhoto(){
                //let photo = (this.form.photo.length > 100) ? this.form.photo : "img/profile/"+ this.form.photo;
                let photo =  this.form.photo
                return photo;
            },
            createPost(){
                this.form.post("/api/post")
                    .then( ()=>{
                        Toast.fire({
                            icon: 'success',
                            title: 'post created successfully'
                        })
                        this.reloadPost()
                        this.form.reset()
                        $("#addPost").modal('hide')
                        console.log('response')
                    })
                    .catch(()=>{
                        console.log("error function post create")
                    })

            },
            update(post){
                console.log('update function')
                this.editMode = true
                 $("#addPost").modal('show')
                this.form.reset()
                this.form.fill(post)
                $('#btnSave').html('Update')
                $('#addNewLabel').html("Update post")
            },
            updatePost(){
                this.form.put('api/post/'+this.form.id)
                    .then((response)=>{
                        Toast.fire({
                            icon: 'success',
                            title: 'post updated successfully'
                        })
                        this.reloadPost()
                        console.log(response)
                        $("#addPost").modal('hide')
                        this.editMode=false
                        $("#btnSave").html("Create")
                        this.form.reset()
                        $("#addNewLabel").html("Add post")

                    })
                    .catch(()=>{
                        console.log("erro something")
                    })
            },
            deletePost(id){
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
                        this.form.delete('api/post/'+id)
                        .then((response)=>{
                            Swal.fire(
                              'Deleted!',
                              'User deleted successfully',
                              'success'
                            )
                            console.log('response')
                            this.reloadPost();
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
            reloadPost(){
                console.log('reload fun')
                this.$store.dispatch("allPostFromDatabase")
                this.$store.dispatch("allCategoryFromDatabase")
                this.$store.dispatch("allTagFromDatabase")
            }
        },
       
        // created() {
        //     this.loadCategory();
        // }

        mounted() {
            console.log(this.$userId)
            this.reloadPost()

        },
        computed: {

            getAllPost(){ //final output from here
                return this.$store.getters.getPostFromGetters
            },
            getAllCategory(){
                return this.$store.getters.getCategoryFormGetters
            },
            getAllTag(){
                return this.$store.getters.getTagFormGetters
            }
      },
    }
</script>


<!-- // header.blade.php
<meta name="user-id" content="{{ Auth::user()->id }}">

// main.js
Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

// component

mounted() {
    console.log(this.$userId)
}
 -->