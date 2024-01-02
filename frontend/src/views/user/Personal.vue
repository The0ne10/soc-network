<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";

const title = ref('')
const content = ref('')
const image = ref('')
const imageId = ref('')

const posts = ref([])

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
}


</script>

<template>
<div class="w-96 mx-auto">
    <div class="mb-4">
        <div>
            <input v-model="title" class="w-96 mb-3 rounded-full border border-slate-400" type="text" placeholder="title">
        </div>
        <div>
            <textarea v-model="content" class="w-96 mb-3 rounded-full border border-slate-400" placeholder="content"></textarea>
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
    <div>
        <h1 class="text-center mb-8 pb-8 border-b border-gray-300">Posts</h1>
        <div v-if="posts" v-for="post in posts" v-bind="post" class="mb-8 pb-8 border-b border-gray-300">
            <h1 class="text-xl text-center">{{ post.title }}</h1>
            <img class="my-3 mx-auto" v-if="post.image_url" :src="post.image_url" :alt="post.title" />
            <p>{{ post.content }}</p>
            <p class="mt-2 ml-auto text-sm text-right text-slate-500">{{ post.date }}</p>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>
