<script setup>
import axios from "axios";
import {onMounted, ref, } from "vue";

import Post from "@/components/post.vue";

const posts = ref([])


const getPosts = () => {
    axios.get(`http://localhost:8000/api/users/followed_posts`)
        .then(res => {
            posts.value = res.data.data
        })
}

onMounted(() => getPosts())


</script>

<template>
<div class="w-96 mx-auto">
    <div v-if="posts">
        <h1 class="text-center mb-8 pb-8 border-b border-gray-300">Posts</h1>
        <Post v-for="post in posts" v-bind="post" :post="post"/>
    </div>
</div>
</template>

<style scoped>

</style>
