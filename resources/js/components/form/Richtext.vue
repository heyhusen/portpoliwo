<template>
  <ValidationProvider v-slot="{ errors }" :name="name">
    <b-field
      :label="label"
      :type="{ 'is-danger': errors[0] }"
      :message="[message, ...errors]"
      :horizontal="false"
      :grouped="false"
    >
      <div class="control">
        <EditorMenuBar v-slot="{ commands, isActive }" :editor="editor">
          <b-field>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.bold() }"
                @click="commands.bold"
              >
                <b-icon icon="format-bold"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.italic() }"
                @click="commands.italic"
              >
                <b-icon icon="format-italic"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.strike() }"
                @click="commands.strike"
              >
                <b-icon icon="format-strikethrough-variant"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.underline() }"
                @click="commands.underline"
              >
                <b-icon icon="format-underline"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.code() }"
                @click="commands.code"
              >
                <b-icon icon="code-tags"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.paragraph() }"
                @click="commands.paragraph"
              >
                <b-icon icon="format-paragraph"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.heading({ level: 1 }) }"
                @click="commands.heading({ level: 1 })"
              >
                <b-icon icon="format-header-1"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.heading({ level: 2 }) }"
                @click="commands.heading({ level: 2 })"
              >
                <b-icon icon="format-header-2"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.heading({ level: 3 }) }"
                @click="commands.heading({ level: 3 })"
              >
                <b-icon icon="format-header-3"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.bullet_list() }"
                @click="commands.bullet_list"
              >
                <b-icon icon="format-list-bulleted-square"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.ordered_list() }"
                @click="commands.ordered_list"
              >
                <b-icon icon="format-list-numbered"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.blockquote() }"
                @click="commands.blockquote"
              >
                <b-icon icon="format-quote-close"></b-icon>
              </button>
            </p>
            <p class="control">
              <button
                type="button"
                class="button"
                :class="{ 'is-light': isActive.code_block() }"
                @click="commands.code_block"
              >
                <b-icon icon="code-tags"></b-icon>
              </button>
            </p>
            <p class="control">
              <button type="button" class="button" @click="commands.undo">
                <b-icon icon="undo"></b-icon>
              </button>
            </p>
            <p class="control">
              <button type="button" class="button" @click="commands.redo">
                <b-icon icon="redo"></b-icon>
              </button>
            </p>
          </b-field>
        </EditorMenuBar>
        <b-input
          class="is-hidden"
          :value="value"
          type="textarea"
          expanded
        ></b-input>
        <EditorContent class="richtext px-2 py-1" :editor="editor" />
      </div>
    </b-field>
  </ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate'
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
} from 'tiptap-extensions'

export default {
  name: 'FormRichtext',
  components: {
    ValidationProvider,
    EditorMenuBar,
    EditorContent,
  },
  props: {
    label: {
      type: String,
      default: '',
    },
    name: {
      type: String,
      default: 'name',
    },
    value: {
      type: String,
      default: '',
    },
    message: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      editor: null,
      emitAfterOnUpdate: false,
    }
  },
  watch: {
    value(val) {
      if (this.emitAfterOnUpdate) {
        this.emitAfterOnUpdate = false
        return
      }
      if (this.editor) this.editor.setContent(val)
    },
  },
  mounted() {
    this.editor = new Editor({
      extensions: [
        new Blockquote(),
        new BulletList(),
        new CodeBlock(),
        new HardBreak(),
        new Heading({ levels: [1, 2, 3] }),
        new HorizontalRule(),
        new ListItem(),
        new OrderedList(),
        new TodoItem(),
        new TodoList(),
        new Link(),
        new Bold(),
        new Code(),
        new Italic(),
        new Strike(),
        new Underline(),
        new History(),
      ],
      onUpdate: ({ getHTML }) => {
        this.emitAfterOnUpdate = true
        this.$emit('input', getHTML())
      },
      content: this.value,
    })
    this.editor.setContent(this.value)
  },
  beforeDestroy() {
    if (this.editor) this.editor.destroy()
  },
}
</script>

<style scoped>
.richtext {
  border: 1px solid hsl(0, 0%, 86%);
}
</style>
