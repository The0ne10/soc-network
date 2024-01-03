<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";
import Post from "@/components/post.vue";

const title = ref('')
const content = ref('')
const image = ref('')
const imageId = ref('')

const posts = ref([])
const errors = ref([])

const getPosts = () => {
    axios.get('http://localhost:8000/api/posts')
        .then(res => {
            posts.value = res.data.data
        })
}

const onFileChange = (e) => {
    image.value = e.target.files[0];

    const formData = new FormData();
    formData.append('image', image.value)

    axios.post('http://localhost:8000/api/post_images', formData)
        .then(res => {
            image.value = res.data.data
        })

};

onMounted(() => getPosts())


const store = () => {

    imageId.value = image.value ? image.value.id : null

    axios.post('http://localhost:8000/api/posts', {title: title.value, content: content.value, image_id: imageId.value})
        .then(res => {
            title.value = ''
            content.value = ''
            image.value = ''
            imageId.value = ''
            posts.value.unshift(res.data.data)
        })
        .catch(e => {
            errors.value = e.response.data.errors
        })
}


</script>

<template>
<div class="w-96 mx-auto">
    <div class="mb-4">
        <div>
            <input v-model="title" :class="['w-96 rounded-3xl p-2 border border-slate-400', errors.title ? '' : 'mb-3']" type="text" placeholder="title">
            <div v-if="errors.title">
                <p v-for="error in errors.title" class="text-red-500 mb-3">{{ error }}</p>
            </div>
        </div>
        <div>
            <textarea v-model="content" :class="['w-96 p-2 rounded-3xl border border-slate-400', errors.content ? '' : 'mb-3']" placeholder="content"></textarea>
            <div v-if="errors.content">
                <p v-for="error in errors.content" class="text-red-500 mb-3">{{ error }}</p>
            </div>
        </div>

        <div class="flex mb-3 items-center">
            <div>
                <input
                    type="file"
                    @change="onFileChange"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full
                    file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
            </div>
            <div>
                <a class="ml-3"  href="#">Cancel</a>
            </div>
        </div>
        <div v-if="image">
            <img :src="image.url" alt="preview">
        </div>
        <div>
            <a @click.prevent="store" class="block p-2 w-32 bg-sky-400 rounded-full text-white text-center hover:bg-white border
             border-sky-400 hover:text-black ml-auto" href="#">Publish</a>
        </div>
        </div>
    <div v-if="posts">
        <h1 class="text-center mb-8 pb-8 border-b border-gray-300">Posts</h1>
        <Post v-for="post in posts" v-bind="post" :post="post"/>
    </div>
</div>
</template>

<style scoped>

</style>
