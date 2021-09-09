<template>
    <base-modal ref="basemodal">
        <v-card 
            transparent 
            elevation="0" 
            width="400" 
            :class="{ 'py-2': !confirmButton && !cancelButton }"
        >
            <v-card-title> 
                <div class="mx-auto"> {{ title }} </div>
            </v-card-title>

            <v-card-text class="text-center"> 
                {{ message }}
            </v-card-text>

            <v-card-text class="text-center py-2" v-if="!confirmeByType">
                <v-progress-circular v-if="loadingByType" :width="5" color="blue" :size="70" indeterminate></v-progress-circular>
                <v-icon :size="70" :color="colorByType"> {{ iconByType }} </v-icon>
            </v-card-text>

            <v-card-actions v-if="confirmButton || cancelButton">
                <v-spacer></v-spacer>
                <v-btn :color="colorCancelButton" dark @click="_cancel" v-if="cancelButton"> {{ textCancelButton }} </v-btn>
                <v-btn :color="colorConfirButton" dark @click="_confirm" v-if="confirmButton"> {{ textConfirmButton }} </v-btn>
            </v-card-actions>
        </v-card>
    </base-modal>
</template>

<script>
import BaseModal from './BaseModal.vue'

export default {
    name: 'FireDialog',
    components: { BaseModal },
    data: () => ({
        title: undefined,
        message: undefined,
        confirmButton: false,
        textConfirmButton: 'Aceitar',
        colorConfirButton: 'green',
        cancelButton: false,
        textCancelButton: 'Fechar',
        colorCancelButton: 'blue',
        resolvePromise: undefined,
        rejectPromise: undefined,
        icon: undefined,
        type: undefined,
        time: undefined
    }),
    computed: {
        confirmeByType(){
            return this.type === 'confirm';
        },
        iconByType(){
            return this.type == 'success' ? 'mdi-check-circle-outline' : 
                   this.type == 'error' ? 'mdi-minus-circle-outline' : 
                   this.type == 'warning' ? 'mdi-alert-circle-outline' : '';
        },
        colorByType(){
            return this.type == 'success' ? 'green' : 
                   this.type == 'error' ? 'red' : 
                   this.type == 'warning' ? 'orange' : '';
        },
        loadingByType(){
             return this.type === 'loading';
        }
    },
    methods: {
        show(opts = {}) {
            this.title = opts.title
            this.message = opts.message

            this.confirmButton = opts.confirmButton
            this.cancelButton = opts.cancelButton

            this.type = opts.type

            if (opts.colorConfirButton) {
                this.colorConfirButton = opts.colorConfirButton
            }

            if (opts.colorCancelButton) {
                this.colorCancelButton = opts.colorCancelButton
            }

            if (opts.textConfirmButton) {
                this.textConfirmButton = opts.textConfirmButton
            }

            if (opts.textCancelButton) {
                this.textCancelButton = opts.textCancelButton
            }

            if(opts.time){
                this.hide(opts.time);
            }

            this.$refs.basemodal.open()
            // Return promise so the caller can get results
            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve
                this.rejectPromise = reject
            })
        },
        confirm(opts = {}){
            return this.show({ type: 'confirm', confirmButton: true, cancelButton: true, ...opts });
        },
        success(opts = {}){
            return this.show({ ...opts, type: 'success' });
        },
        error(opts = {}){
            return this.show({ ...opts, type: 'error' });
        },
        warning(opts = {}){
            return this.show({ ...opts, type: 'warning' });
        },
        loading(opts = {}){
            return this.show({ ...opts, type: 'loading' });
        },
        hide(timeout = 0){
            setTimeout(() => this.$refs.basemodal.close(), timeout);
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