
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Message from '@/components/Message.vue';
import { home } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import axios from 'axios';
import { Spinner } from '@/components/ui/spinner';

let message_content = '';
let input = '';
let conversation_id = '';
let loading = false;

const props = defineProps({
	conversation_id: {
		type: String,
		required: false
	},
	conversation: {
		type: String,
		required: false,
	},
	model: {
		type: Number,
		required: true,
		default: 0,
	},
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: home().url,
    },
];

function getResponseFromAi()
{
	loading = true;
	const response = axios.post(
		'ai/conversation/',
		{
			conversation_id: props.conversation_id,
			model: props.model,
			message_content: input
		}
	)
	.then(response => {
		console.log(response.data);
	})
	.catch(error => {
		console.log(error);
	})
	.finally(() => {
		loading = false;
	});
}
</script>

<template>
	<Head title="Conversation" />

	<AppLayout :breadcrumbs="breadcrumbs">
		<h2 class="max-w-xl ml-auto mr-auto w-full">
			Conversation:
		</h2>
		<div class="max-w-xl ml-auto mr-auto w-full p-3 grow border-white border rounded-md flex">
			<Spinner v-if="loading" class="size-6 self-center mr-auto ml-auto" />
		</div>
		<div class="max-w-xl ml-auto mr-auto w-full">
			<Textarea
				v-model="input"
				class="mb-4"
			/>
			<Button
				variant="outline"
				@click="getResponseFromAi"
			>Submit</Button>
		</div>
	</AppLayout>
</template>

<style lang="css">

</style>