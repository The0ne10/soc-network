<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";

const users = ref([])

const getUsers = () => {
    axios.get('http://localhost:8000/api/users')
        .then(res => {
            users.value = res.data.data
        })
}

const toggleFollowing = (user) => {
    axios.post(`http://localhost:8000/api/users/${user.id}/toggle_followings`)
        .then(res => {
            user.is_followed = res.data.is_followed
        })
}

onMounted(() => getUsers());

</script>

<template>
    <div class="w-96 mx-auto">
        <div v-if="users">
            <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-300" v-for="user in users" v-bind="user">
                <router-link :to="{ name: 'user.show', params: {id: user.id}}">
                <p>{{ user.id }}</p>
                <p>{{ user.name }}</p>
                <p>{{ user.email }}</p>
                </router-link>
                <div>
                    <a @click.prevent="toggleFollowing(user)"
                       :class="['block p-2 w-32 bg-sky-400 rounded-full text-center', user.is_followed ? 'bg-white text-black border border-sky-400' : 'hover:bg-white border \
             border-sky-400 hover:text-black ml-auto']" href="#">{{ user.is_followed ? 'Unfollowed' : 'Follow' }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
