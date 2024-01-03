<script setup>
import {onMounted, ref, watch} from "vue";
import axios from "axios";
import {useRoute, useRouter} from "vue-router";

const token = ref(null)
const route = useRoute()
const router = useRouter()

const getToken = () => {
    token.value = localStorage.getItem('x_xsrf_token')
}


const logout = () => {
    axios.post('http://localhost:8000/logout')
        .then(res => {
            localStorage.removeItem('x_xsrf_token')
            router.push({name: 'user.login'}) // редирект в vue
        })
}

watch(() => route.path, () => {
    getToken()
});

onMounted(() => getToken())


</script>

<template>
    <div>

        <div class="flex justify-between p-8 w-96 mx-auto">
            <router-link class="text-sky-500 mr-1" v-if="!token" :to="{ name: 'user.login'}">Login</router-link>
            <router-link class="text-sky-500 mr-1" v-if="token" :to="{ name: 'user.index'}">Users</router-link>
            <router-link class="text-sky-500 mr-1" v-if="token" :to="{ name: 'user.feed'}">Feed</router-link>
            <router-link class="text-sky-500 mr-1" v-if="token" :to="{ name: 'user.personal'}">Personal</router-link>
            <router-link class="text-sky-500 mr-1" v-if="!token" :to="{ name: 'user.registration'}">Registration</router-link>
            <a v-if="token" class="text-sky-500 mr-1" @click.prevent="logout" href="#">Logout</a>
        </div>
        <router-view></router-view>
    </div>

</template>

<style>

</style>