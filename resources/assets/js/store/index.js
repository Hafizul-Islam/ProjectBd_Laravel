export default {

	state: {
       category: [],
       post    : [],
       tag     : [],
	},

	getters: {

    getCategoryFormGetters(state){ //take parameter state
      return state.category
    },
    getPostFromGetters(state){
      return state.post
    },
    getTagFormGetters(state){
      return state.tag
    }
	},

	actions: {
    allCategoryFromDatabase(context){
      axios.get("api/category")
        .then((response)=>{
          console.log(response.data.categories)
          context.commit("categories",response.data.categories) //categories will be run from mutation
        })
        .catch(()=>{
          console.log(response.data.Error)
        })
    },
    allPostFromDatabase(context){
      axios.get("api/post")
        .then((response)=>{
          console.log(response.data.post)
          context.commit("post",response.data.post) //categories will be run from mutation
        })
        .catch(()=>{
          console.log(response.data.Error)
        })
    },
    allTagFromDatabase(context){
      axios.get("api/tag")
        .then((response)=>{
          console.log(response.data.tag)
          context.commit("tag",response.data.tag) //categories will be run from mutation
        })
        .catch(()=>{
          console.log("Error........")
        })
    },
	},

	mutations: {
    categories(state,data) {
      return state.category = data
    },
    post(state,data){
      return state.post = data
    },
    tag(state,data){
      return state.tag = data
    }
	}
}