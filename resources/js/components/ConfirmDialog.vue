<template>
    <base-modal ref="basemodal">
        <v-card transparent elevation="0" width="400">
            <v-card-title> {{ title }} </v-card-title>
            <v-card-text> {{ message }} </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green" text @click="_cancel"> {{ cancelButton }} </v-btn>
                <v-btn color="red" text @click="_confirm"> {{ confirmButton }} </v-btn>
            </v-card-actions>
        </v-card>
    </base-modal>
</template>

<script>
import BaseModal from './BaseModal.vue'

export default {
    name: 'ConfirmDialog',
    components: { BaseModal },
    data: () => ({
        title: undefined,
        message: undefined,
        confirmButton: 'Aceitar',
        cancelButton: 'Fechar',
        resolvePromise: undefined,
        rejectPromise: undefined,
    }),
    methods: {
        show(opts = {}) {
            this.title = opts.title
            this.message = opts.message
            this.confirmButton = opts.confirmButton
            if (opts.cancelButton) {
                this.cancelButton = opts.cancelButton
            }

            this.$refs.basemodal.open()
            // Return promise so the caller can get results
            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve
                this.rejectPromise = reject
            })
        },
        _confirm() {
            this.$refs.basemodal.close()
            this.resolvePromise(true)
        },
        _cancel() {
            this.$refs.basemodal.close()
            this.resolvePromise(false)
            // Or you can throw an error
            // this.rejectPromise(new Error('User cancelled the dialogue'))
        },
    },
}
</script>