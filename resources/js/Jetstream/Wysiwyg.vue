<template>
    <editor
        :apiKey="tiny"
        :init="init"
        v-model="model"
    >

    </editor>
</template>

<script>
import Editor from '@tinymce/tinymce-vue';
import { emitter } from '../Modules/emitter'

export default {
    props: ['value', 'tiny'],
    // props: ['value'],
    components: {
        editor: Editor,
    },

    data () {
        return {
            init: {
                language: 'fr_FR',
                entity_encoding: 'raw',
                height: 500,
                menubar: false,
                plugins: [
                    'advlist autolink lists link charmap print preview anchor',
                    'image searchreplace visualblocks fullscreen',
                    'insertdatetime wordcount',
                ],
                toolbar:
                    'bold italic link| \
                    alignleft alignjustify | \
                    bullist numlist outdent indent | \
                    image removeformat undo redo fullscreen',

                images_upload_handler: function (blobInfo, success, failure) {
                    const blob = URL.createObjectURL(blobInfo.blob());
                    emitter.emit('image-added', {
                        file: blobInfo.blob(),
                        blob: blob,
                    })
                    success(blob);
                },
            }
        }
    },

    methods: {
        image_upload_handler(blobInfo, success, failure) {
            const blob = URL.createObjectURL(blobInfo.blob())
            this.emitter.emit('image-added', blob)
            return success(blob);
        }
    },

    computed: {
        model: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            }
        }
    }
}
</script>
