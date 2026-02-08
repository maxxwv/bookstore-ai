
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { home } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import axios from 'axios';
import { Spinner } from '@/components/ui/spinner';
import { User, Laptop } from 'lucide-vue-next';

let messages = ref(new Array());
let input = ref("");
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
	let currentInput = input.value;
	input.value = "";
	loading.value = true;
	let current_messages = messages.value;

	current_messages.push({
		message: currentInput,
		message_type: 'HumanMessage'
	});

	axios.post(
		'ai/conversation/',
		{
			conversation_id: conversation_id,
			model: props.model,
			message_content: currentInput
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
		<div class="max-w-xl ml-auto mr-auto w-full p-3 grow border-white border rounded-md flex overflow-y-scroll relative" id="output">
			<Spinner :class="{ visible: loading, invisible: !loading }" class="size-6 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-100" />
			<ul class="conversation grow">
				<li
					v-for="m in messages"
					:key="m.id"
					:class="m.message_type"
					class="border rounded-md max-w-9/10 select-none"
				>
					<Laptop v-if="m.message_type == 'AssistantMessage'" />
					<p>{{ m.message }}</p>
					<User v-if="m.message_type == 'HumanMessage'" />
				</li>
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
	#output {
		max-height: 100vh;
	}
	.conversation li {
		display: grid;
		gap: 7px;
		padding: 5px;
	}
	.conversation li + li {
		margin-top: 10px;
	}
	.AssistantMessage {
		border-color: #44403c;
		margin-right: auto;
		grid-template-columns: max-content auto;
	}
	.HumanMessage {
		border-color: #0f172a;
		margin-left: auto;
		grid-template-columns: auto max-content;
	}
</style>