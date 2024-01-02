<script>
import axios from "axios";
import router from "@/router/index.js";
export default {
    data() {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
        }
    },
    methods: {
        register() {
            axios.get('http://localhost:8000/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('http://localhost:8000/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation
                        })
                        .then(res => {
                            localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
                            router.push({name: 'user.personal'})
                        })
                })
        }
    }
}

</script>

<template>
        <div class="w-96 mx-auto">
            <input v-model="name" type="text" placeholder="name" class="w-96 rounded-full border border-gray-300 mt-3 mb-3">
            <input v-model="email" type="email" placeholder="email" class="w-96 rounded-full border border-gray-300 mb-3">
            <input v-model="password" type="password" placeholder="password" class="w-96 rounded-full border border-gray-300 mb-3">
            <input v-model="password_confirmation" type="password" placeholder="password_confirmation" class="w-96 rounded-full border border-gray-300 mb-3">
            <input @click.prevent="register" type="submit" value="register" class="w-24 bg-sky-500 rounded-full border border-red-500">
        </div>
</template>

<style scoped>

</style>
