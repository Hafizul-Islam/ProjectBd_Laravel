
import Category from './components/admin/CategoryComponent.vue'
import Post from './components/admin/PostComponent.vue'
import Tag from './components/admin/TagComponent.vue'
export const routes = [
    { 
        path:'/category',
        component:Category
    },
    { 
        path:'/post',
        component:Post
    },
    { 
        path:'/tag',
        component:Tag
    },
];
