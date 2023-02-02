<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const search = ref('');
const loading = ref(false);
const answers = ref(null);
const searchPerformed = ref(false);

const possibleQuestions = [
    'How do I remove a teammate?',
    'What is the meaning of life?',
    'What should I eat for breakfast?',
];

const performSearch = () => {
    loading.value = true;
    searchPerformed.value = false;
    axios.get(route('api.ask'), {
        params: {
            question: search.value,
        }
    }).then(response => {
        answers.value = response.data;
        loading.value = false;
        searchPerformed.value = true;
    }).catch(error => {
        loading.value = false;
    });
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form method="GET" @submit.prevent="performSearch">
                    <div class="flex justify-between items-center space-x-4">
                        <TextInput
                            id="search"
                            v-model="search"
                            type="text"
                            class="mt-1 block w-full h-8"
                            placeholder="Ask any question?"
                            required
                        />

                        <PrimaryButton @click="performSearch" :disabled="loading" class="mt-1">Search</PrimaryButton>
                    </div>
                </form>
            </div>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 py-6" v-if="searchPerformed && !answers.length">
                    <div class="flex justify-start items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">I am sorry I don't know how to respond to that!</h2>
                            <p class="text-gray-600 text-sm mt-4">Try questions like:</p>
                            <ul>
                                <li class="text-gray-500 text-xs pl-4" v-for="question in possibleQuestions">
                                    {{ question }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </transition>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 py-6" v-if="loading">
                    <div class="bg-white shadow rounded my-4" v-for="i in 3">
                        <div class="p-6">
                            <p class="bg-gray-400 animate-pulse h-3 w-3/4 mb-2 rounded"></p>
                            <p class="bg-gray-400 animate-pulse h-3 w-2/4 mb-2 rounded"></p>
                            <p class="bg-gray-400 animate-pulse h-3 w-1/4 mb-2 rounded"></p>
                        </div>
                        <div class="bg-gray-50 px-6 py-4">
                            <p class="bg-gray-400 animate-pulse h-2 w-1/4 mb-2 rounded"></p>
                        </div>
                    </div>
                </div>
            </transition>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 py-6" v-if="!loading">
                    <div class="bg-white shadow rounded my-4" v-for="answer in answers">
                        <div class="p-6">
                            <p class="text-gray-800" v-html="answer.content"></p>
                        </div>
                        <div class="bg-gray-50 px-6 py-4">
                            <p class="text-gray-500">Confidence: {{ answer.confidence }}</p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>
