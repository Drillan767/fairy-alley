<template>
    <select class="border-gray-300 w-full focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" ref="input">
        <option selected value="">{{ placeholder }}</option>
        <option v-for="(option, i) in choices" :key="i" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</template>

<script>
export default {
    props: {
        modelValue: String,
        choices: Array,
        placeholder: {
            type: String,
            default: 'Veuillez choisir un élément...'
        }
    },

    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
}
</script>
