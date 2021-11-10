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
                paste_use_dialog : false,
                paste_auto_cleanup_on_paste : true,
                paste_convert_headers_to_strong : false,
                paste_strip_class_attributes : "all",
                paste_remove_spans : true,
                paste_remove_styles : true,
                paste_text_sticky : true,
                plugins: [
                    'advlist autolink lists link charmap print preview anchor',
                    'image searchreplace visualblocks fullscreen',
                    'insertdatetime wordcount table, paste',
                ],
                toolbar:
                    'bold italic link| \
                    alignleft alignjustify alignright alignjustify | \
                    bullist numlist outdent indent | fontselect fontsizeselect | \
                    table image removeformat undo redo fullscreen',

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
