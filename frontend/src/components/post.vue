<script setup>

defineProps({
    post: 'post'
})

</script>

<template>
    <div class="w-96 mx-auto">
        <div>
            <div class="mb-8 pb-8 border-b border-gray-300">
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