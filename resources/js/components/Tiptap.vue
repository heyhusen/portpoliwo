<template>
	<div class="space-y-1">
		<div v-if="editor" class="flex flex-wrap gap-1">
			<oc-button
				size="small"
				icon-left="bold"
				:inverted="editor.isActive('bold')"
				@click="editor.chain().focus().toggleBold().run()"
			/>
			<oc-button
				size="small"
				icon-left="italic"
				:inverted="editor.isActive('italic')"
				@click="editor.chain().focus().toggleItalic().run()"
			/>
			<oc-button
				size="small"
				icon-left="strikethrough"
				:inverted="editor.isActive('strike')"
				@click="editor.chain().focus().toggleStrike().run()"
			/>
			<oc-button
				size="small"
				icon-left="code"
				:inverted="editor.isActive('code')"
				@click="editor.chain().focus().toggleCode().run()"
			/>
			<oc-button
				size="small"
				icon-left="paragraph"
				:inverted="editor.isActive('paragraph')"
				@click="editor.chain().focus().toggleParagraph().run()"
			/>
			<oc-button
				size="small"
				label="h1"
				:inverted="editor.isActive('heading', { level: 1 })"
				@click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
			/>
			<oc-button
				size="small"
				label="h2"
				:inverted="editor.isActive('heading', { level: 2 })"
				@click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
			/>
			<oc-button
				size="small"
				label="h3"
				:inverted="editor.isActive('heading', { level: 3 })"
				@click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
			/>
			<oc-button
				size="small"
				label="h4"
				:inverted="editor.isActive('heading', { level: 4 })"
				@click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
			/>
			<oc-button
				size="small"
				label="h5"
				:inverted="editor.isActive('heading', { level: 5 })"
				@click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
			/>
			<oc-button
				size="small"
				label="h6"
				:inverted="editor.isActive('heading', { level: 6 })"
				@click="editor.chain().focus().toggleHeading({ level: 6 }).run()"
			/>
			<oc-button
				size="small"
				icon-left="list-ul"
				:inverted="editor.isActive('bulletList')"
				@click="editor.chain().focus().toggleBulletList().run()"
			/>
			<oc-button
				size="small"
				icon-left="list-ol"
				:inverted="editor.isActive('orderedList')"
				@click="editor.chain().focus().toggleOrderedList().run()"
			/>
			<oc-button
				size="small"
				icon-left="quote-right"
				:inverted="editor.isActive('blockquote')"
				@click="editor.chain().focus().toggleBlockquote().run()"
			/>
			<oc-button
				size="small"
				icon-left="undo"
				@click="editor.chain().focus().undo().run()"
			/>
			<oc-button
				size="small"
				icon-left="redo"
				@click="editor.chain().focus().redo().run()"
			/>
		</div>
		<editor-content :editor="editor" class="border rounded-md p-4" />
	</div>
</template>

<script setup>
import { toRefs, watch } from 'vue';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';

import OcButton from '@/components/Button.vue';

const props = defineProps({
	modelValue: {
		type: String,
		default: '',
	},
});
const { modelValue } = toRefs(props);

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
	content: modelValue.value,
	extensions: [StarterKit],
	onUpdate: ({ editor: edit }) => {
		emit('update:modelValue', edit.getHTML());
	},
	editorProps: {
		attributes: {
			class:
				'prose prose-sm sm:prose lg:prose-lg max-w-none focus:outline-none',
		},
	},
});

watch(modelValue, (value) => {
	const isSame = editor.value.getHTML() === value;
	if (isSame) {
		return;
	}
	editor.value.commands.setContent(value, false);
});
</script>
