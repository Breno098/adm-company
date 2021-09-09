<template>
    <transition name="fade">
        <v-dialog v-model="show" max-width="350">
            <slot></slot>
        </v-dialog>
    </transition>
</template>

<script>
export default {
    name: 'BaseModal',
    data: () => ({
        show: false,
    }),
    methods: {
        open() {
            this.show = true
        },
        close() {
            this.show = false
        },
    },
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>