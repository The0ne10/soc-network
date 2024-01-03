<script setup>
import axios from "axios";
import {onMounted, ref,} from "vue";

import Post from "@/components/post.vue";
import {useRoute} from "vue-router";
import Stat from "@/components/Stat.vue";

const route = useRoute()

const posts = ref([])
const userId = route.params.id
const stats = ref([])

const getPosts = () => {
    axios.get(`http://localhost:8000/api/users/${userId}/posts`)
        .then(res => {
            posts.value = res.data.data
        })
}

const getStat = () => {
    axios.post('http://localhost:8000/api/users/stats', {user_id: userId})
        .then(res => {
            stats.value = res.data.data
        })
}

onMounted(() => {
    getPosts()
    getStat()
})


</script>

<template>
    <div class="w-96 mx-auto">
        <Stat :stats="stats"/>
        <div v-if="posts">
            <h1 class="text-center mb-8 pb-8 border-b border-gray-300">Posts</h1>
            <Post v-for="post in posts" v-bind="post" :post="post"/>
        </div>
    </div>
</template>

<style scoped>

</style>
