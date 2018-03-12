<template>
    <div class="alert alert-flash" :class="`alert-${message_type}`" role="alert" v-show="show">
        <i class="fa fa-fw fa-3x" :class="icon"></i> {{ body }}
    </div>
</template>

<script>
    export default {
        props: {
            message : {
                required : true
            },

            type : {
                default : 'success'
            }
        },

        data() {
            return {
                body: '',
                message_type : this.type,
                show: false,
            }
        },

        computed : {
            icon() {
                switch (this.message_type) {
                    case 'success' : return 'fa-check-square-o';
                    case 'warning' : return 'fa-exclamation-triangle';
                    case 'danger' : return 'fa-exclamation-circle';
                }
            }
        },

        mounted() {
            if (this.message) {
                this.flash(this.message);
            }

            Bus.$on('flash', (data) => {
                    this.flash(data);
                }
            );
        },

        methods: {
            flash({ message, type }) {

                if ( type == 'notify' ) {
                    return this.notify(message);
                }
                
                // console.log(message, type);
                this.notify(message, type);
                this.body = message;
                this.message_type = type;
                this.show = true;

                this.hide();
            },

            hide() {
                sleep(3000).then(() => {
                    this.show = false;
                });
            },

            notify(message, type) {
                let options;

                // Let's check if the browser supports notifications
                if (!("Notification" in window)) {
                    return false;
                }

                if ( !! type ) {
                    options = {
                        icon : `img/${type}.png`,
                        tag : type
                    }
                }

                if (Notification.permission === "granted") {
                    return new Notification(message, options);
                }

                if (Notification.permission !== "denied") {
                    Notification.requestPermission( (permission) => {
                        // If the user accepts, let's create a notification
                        if (permission === "granted") {
                            return new Notification(message, options);
                        }
                    });
                }

            }
        }
    };
</script>

<style>
    .alert-flash {
        display: flex;
        align-items: center;
        position: fixed;
        right: 1em;
        bottom: 1em;

        z-index: 1001;
        margin: 0;
    }
</style>