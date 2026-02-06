<script setup lang="typescript">
	const props = defineProps({
		message: {
			type: String,
			required: true,
		},
		prompt_tokens: {
			type: Number,
			required: false,
		},
		completion_tokens: {
			type: Number,
			required: false,
		},
		message_type: {
			type: String,
		}
	});
</script>

<template>
	<Message :message="message" :prompt_tokens="prompt_tokens" :completion_tokens="completion_tokens" :message_type="message_type">
		<div :class="{{ props.message_type }}" class="message">
			<p>{{ props.message }}</p>
			<div v-if="props.message_type == 'AssistantMessage'">
				<div>Prompt Tokens: {{ props.prompt_tokens }}</div>
				<div>Completion Tokens: {{ props.completion_tokens }}</div>
			</div>
		</div>
	</Message>
</template>