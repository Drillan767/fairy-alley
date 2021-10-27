<template>
    <label :for="id" class="block h-64 relative overflow-hidden rounded">
        <input
            :id="id"
            type="file"
            class="overlayed"
            :multiple="multiple"
            @change="handleUpload"
        />
        <span class="overlayed bg-gray-100 border-gray-200 border-2 text-gray-800 pointer-events-none flex justify-center items-center">
            <div class="flex h-full flex-col justify-center items-center cursor-pointer">
                <slot>
                  <strong>Sélectionnez un fichier, ou glissez-le ici</strong>
                </slot>
                <div class="text-gray-600 block text-sm">
                    <slot name="file" :files="files" :uploadInfo="uploadInfo">
                        {{ uploadInfo }}
                    </slot>
                </div>
            </div>
        </span>
    </label>
</template>

<script>

import { ref, computed } from 'vue'
export default {
    props: {
        file: {},
        currentFile: {
            type: Object,
            required: false,
        },
        id: {
            type: String,
            default: 'drag-and-drop-input'
        },
        multiple: { type: Boolean, default: false },
    },

    emits: ['input'],

    setup(props, { emit }) {
        const files = ref([])

        const uploadInfo = computed(() => {
            if (props.currentFile && files.value.length === 0) {
                return `Fichier actuel : ${props.currentFile.title}`;
            } else {
                return files.value.length === 1
                    ? files.value[0].name
                    : `${files.value.length} fichiers sélectionnés`
            }

        })

        const handleUpload = (e) => {
            files.value = Array.from(e.target.files) || []
            const response = props.multiple ? e.target.files : e.target.files[0]
            emit('input', response)
        }

        return {
            files,
            uploadInfo,
            handleUpload
        }
    },
}
</script>

<style scoped>
.overlayed {
    @apply absolute top-0 left-0 right-0 bottom-0 w-full block cursor-pointer;
}
</style>
