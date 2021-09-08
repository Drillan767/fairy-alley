<template>
    <editor
        api-key="eywu9q72sa0iwmaojefquqo1s5vhjb1v6wpbgjljqm92109l"
        :init="init"
        v-model="model"
    >

    </editor>
</template>

<script>
import Editor from '@tinymce/tinymce-vue';

export default {
    props: ['value'],
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
                    success(URL.createObjectURL(blobInfo.blob()));
                },
            }
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
