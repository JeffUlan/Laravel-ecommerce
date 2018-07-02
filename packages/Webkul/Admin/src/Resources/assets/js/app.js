window.jQuery = window.$ = $ = require('jquery');
window.Vue = require('vue');
window.VeeValidate = require('vee-validate');

Vue.use(VeeValidate);

$(document).ready(function () {
    const app = new Vue({
        el: '#app',

        mounted: function() {
            this.addServerErrors()
            this.addFlashMessages()
        },

        methods: {
            onSubmit: function(e) {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        e.target.submit()
                    }
                });
            },

            addServerErrors: function() {
                var scope = null;
                for (var key in serverErrors) {
                    const field = this.$validator.fields.find({ name: key, scope: scope });
                    if (field) {
                        this.$validator.errors.add({
                            id: field.id,
                            field: key,
                            msg: serverErrors[key][0],
                            scope: scope,
                        });
                    }
                }
            },

            addFlashMessages: function() {
                const flashes = this.$refs.flashes

                flashMessages.forEach(function(flash) {
                    flashes.addFlash(flash)
                }, this);
            }
        }
    });
});