
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { render, h } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Message from '@/components/Message.vue';
import { home } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import axios from 'axios';
import { Spinner } from '@/components/ui/spinner';

let messages = ref(new Array());
let input = "";
let loading = ref(false);
let conversation_id: string | null = null;

const props = defineProps({
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
	loading.value = true;
	let current_messages = messages.value;

	current_messages.push({
		message: input,
		message_type: 'HumanMessage'
	});

	axios.post(
		'ai/conversation/',
		{
			conversation_id: conversation_id,
			model: props.model,
			message_content: input
		}
	)
	.then(response => {
		current_messages.push({
			message: response.data.message_content,
			prompt_tokens: response.data.promptTokens,
			completion_tokens: response.data.responseTokens,
			message_type: 'AssistantMessage',
		});
		messages.value = current_messages;
		conversation_id = response.data.conversation_id;
	})
	.catch(error => {
		console.log(error);
	})
	.finally(() => {
		loading.value = false;
	});
}
</script>

<template>
	<Head title="Conversation" />

	<AppLayout :breadcrumbs="breadcrumbs">
		<h2 class="max-w-xl ml-auto mr-auto w-full">
			Conversation:
		</h2>
		<div class="max-w-xl ml-auto mr-auto w-full p-3 grow border-white border rounded-md flex" id="output">
			<Spinner :class="{ visible: loading, invisible: !loading }" class="size-6 self-center mr-auto ml-auto" />
			<ul class="conversation">
				<li
					v-for="m in messages"
					:key="m.id"
					class="{{ m.message_type }}"
				>{{ m.message }}</li>
			</ul>
		</div>
		<div class="max-w-xl ml-auto mr-auto w-full">
			<Textarea
				v-model="input"
				class="mb-4"
			/>
			<Button
				variant="outline"
				@click="getResponseFromAi"
				aria-label="Submit"
				:disabled="loading"
			>Submit</Button>
		</div>
	</AppLayout>
</template>

<style lang="css">

</style>