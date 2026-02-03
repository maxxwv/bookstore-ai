
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { home } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';

const conv = ref('Type your text here...');
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: home().url,
    },
];
</script>

<template>
	<Head title="Conversation" />

	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="max-w-xl ml-auto mr-auto w-full">
			Here's the output:<br />
			<div>{{ conv }}</div>
		</div>
		<div class="max-w-xl ml-auto mr-auto w-full">
			<Textarea
				v-model="conv"
				class="mb-4"
			/>
			<Button
				variant="outline"
			>Submit</Button>
		</div>
	</AppLayout>
</template>

<style lang="css">

</style>