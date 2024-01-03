<script>
import axios from "axios";
import router from "@/router/index.js";
export default {

    data() {
        return {
            email: null,
            password: null,
        }
    },

    methods: {
        login() {
            axios.get('http://localhost:8000/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('http://localhost:8000/login', {email: this.email, password: this.password})
                        .then(r => {
                            console.log(r)
                            localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN'])
                            router.push({name: 'user.personal'})
                        })
                        .catch(err => {
                            //
                        })
                    })
                }
            }
}

</script>

<template>
    <div class="w-96 mx-auto">
        <input v-model="email" type="email" placeholder="email" class="w-96 rounded-full border border-gray-300 mt-3 mb-3">
        <input v-model="password" type="password" placeholder="password" class="w-96 rounded-full border border-gray-300 mb-3">
        <input @click.prevent="login" type="submit" value="Login" class="bg-sky-500 rounded-full border border-red-500 w-16">
    </div>
</template>

<style scoped>

</style>
