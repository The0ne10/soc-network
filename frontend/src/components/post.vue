<script setup>

import axios from "axios";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";

defineProps({
    post: 'post'
})

const route = useRoute()
const is_repost = ref(false)
const repostedId = ref('')
const title = ref('')
const content = ref('')
const body = ref('')
const errors = ref([])
const comments = ref([])
const isShowed = ref(false)
const answerComment = ref(null)
const commentId = ref(null)

const toggleLike = (post) => {
    axios.post(`http://localhost:8000/api/posts/${post.id}/toggle_likes`)
        .then(res => {
            post.is_liked = res.data.is_liked
            post.likes_count = res.data.likes_count
        })
}

const openRepost = () => {
    if (isPersonal()) return
    is_repost.value =  !is_repost.value
}

const repost = (post) => {
    if (isPersonal()) return
    axios.post(`http://localhost:8000/api/posts/${post.id}/repost`, {title: title.value, content: content.value})
        .then(res => {
            title.value = ''
            content.value = ''
        })
        .catch(e => {
            errors.value = e.response.data.errors
        })
}

const isPersonal = () => {
    return route.name === 'user.personal';
}

const storeComment = (post) => {
    commentId.value = answerComment.value ? answerComment.value.id : null
    axios.post(`http://localhost:8000/api/posts/${post.id}/comments`, {body: body.value, parent_id: commentId.value})
        .then(res => {
            body.value = ''
            comments.value.unshift(res.data.data)
            answerComment.value = null
            post.comments_count++
            isShowed.value = true
        })
}

const getComments = (post) => {
    axios.get(`http://localhost:8000/api/posts/${post.id}/comments`)
        .then(res => {
            comments.value = res.data.data
            isShowed.value = true
        })
}

const setParentId = (comment) => {
    answerComment.value = comment
}



</script>

<template>
    <div class="w-96 mx-auto">
        <div>
            <div class="mb-8 pb-8 border-b border-gray-300">
                <h1 class="text-xl text-center">{{ post.title }}</h1>
                <router-link class="text-sm text-slate-500" :to="{name: 'user.show', params: {id: post.user.id}}">{{ post.user.name}}</router-link>
                <img class="my-3 mx-auto" v-if="post.image_url" :src="post.image_url" :alt="post.title" />
                <p>{{ post.content }}</p>
                <div v-if="post.reposted_post" class="bg-gray-100 p-4 m-4 border border-gray-300">
                    <h1 class="text-xl">{{ post.reposted_post.title }}</h1>
                    <router-link class="text-sm text-slate-500" :to="{name: 'user.show', params: {id: post.reposted_post.user.id}}">{{ post.reposted_post.user.name}}</router-link>
                    <img class="my-3 mx-auto" v-if="post.reposted_post.image_url" :src="post.reposted_post.image_url"
                         :alt="post.reposted_post.title" />
                    <p>{{ post.reposted_post.content }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex">
                        <div class="flex mr-3">
                            <svg @click.prevent="toggleLike(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor"
                                 :class="['stroke-sky-500 cursor-pointer hover:fill-sky-500 w-6 h-6', post.is_liked ? 'fill-sky-500' : 'fill-white']">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <p class="ml-2">{{ post.likes_count }}</p>
                        </div>
                        <div class="flex">
                            <svg @click.prevent="openRepost" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 :class="['mr-2 stroke-sky-500 cursor-pointer hover:fill-sky-500 w-6 h-6 fill-white']">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                            </svg>
                            <p>{{ post.reposted_by_posts_count }}</p>
                        </div>
                    </div>
                    <p class="mt-2 ml-auto text-sm text-right text-slate-500">{{ post.date }}</p>
                </div>
                <div v-if="is_repost" class="mt-4">
                    <div>
                        <input v-model="title" :class="['w-96 rounded-3xl p-2 border border-slate-400', errors.title ? '' : 'mb-3']" type="text" placeholder="title">
                        <div v-if="errors.title">
                            <p class="text-red-500" v-for="error in errors.title">{{ error }}</p>
                        </div>
                    </div>
                    <div>
                        <textarea v-model="content" :class="['w-96 rounded-3xl p-2 border border-slate-400', errors.content ? '' : 'mb-3']" placeholder="content"></textarea>
                        <div v-if="errors.content">
                            <p class="text-red-500" v-for="error in errors.content">{{ error }}</p>
                        </div>
                    </div>
                    <div>
                        <a @click.prevent="repost(post)" class="block p-2 w-32 bg-sky-400 rounded-full text-white text-center hover:bg-white border
                                    border-sky-400 hover:text-black ml-auto" href="#">Publish</a>
                    </div>
                </div>
                <div v-if="post.comments_count > 0" class="mt-4">
                    <p v-if="!isShowed" class="cursor-pointer" @click.prevent="getComments(post)">Show {{ post.comments_count }} comments</p>
                    <p v-if="isShowed" @click.prevent="isShowed = !isShowed" class="cursor-pointer">Close</p>
                    <div v-if="comments && isShowed">
                        <div v-for="comment in comments" class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex mb-2">
                                <p class="text-sm mr-2">{{ comment.user.name }}</p>
                                <p @click.prevent="setParentId(comment)" class="text-sm text-sky-500 cursor-pointer">Answer</p>
                            </div>
                            <p><span class="text-sky-400">{{ comment.answered_for_user ? comment.answered_for_user + " "  + "<-" + " "  : '' }}</span>{{ comment.body }}</p>
                            <p class="text-right text-sm text-slate-300">{{ comment.date }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="mb-3">
                        <div class="flex">
                            <p v-if="answerComment" class="mr-2 items-center text-sky-300">Answered for {{ answerComment.user.name}}</p>
                            <p v-if="answerComment" @click.prevent="answerComment = null" class="cursor-pointer text-sm">Cancel</p>
                        </div>
                        <input v-model="body" class="w-96 p-2 mb-3 rounded-full border border-slate-400" type="text" placeholder="comments...">
                    </div>
                    <div>
                        <a @click.prevent="storeComment(post)" class="block p-2 w-32 bg-sky-400 rounded-full text-white text-center hover:bg-white border
             border-sky-400 hover:text-black ml-auto" href="#">comment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>