<script setup>
import axios from "axios";
import router from "@/router/index.js";
import {onMounted, ref} from "vue";

const users = ref([])

const getUsers = () => {
    axios.get('http://localhost:8000/api/users')
        .then(res => {
            users.value = res.data.data
        })
}

onMounted(() => getUsers());

</script>

<template>
    <div class="w-96 mx-auto">
        <div v-if="users">
            <div class="mb-6 pb-6 border-b border-gray-300" v-for="user in users" v-bind="user">
                <router-link :to="{ name: 'user.show', params: {id: user.id}}">
                <p>{{ user.id }}</p>
                <p>{{ user.name }}</p>
                <p>{{ user.email }}</p>
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
