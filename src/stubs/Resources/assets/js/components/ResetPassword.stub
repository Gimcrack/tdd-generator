<template>
    <div v-if="visible" class="reset-password-wrapper">
        <div class="reset-password">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="text-success">
                        <i class="fa fa-fw fa-check"></i>
                        Reset Password
                    </span>
                </div>
                <div class="panel-body">
                    <div class="alert alert-warning"><i class="fa fa-fw fa-exclamation-circle"></i>
                        Password must be at least 6 characters
                    </div>
                    <p>
                        <input v-model="password" placeholder="Password" type="password" class="form-control">
                    </p>
                    <p>
                        <input v-model="password_confirmation" placeholder="Confirm" type="password" class="form-control">
                    </p>
                </div>
                <div class="panel-footer">
                    <div class="btn-group">
                        <button :disabled="busy" :class="{disabled:busy}" @click.prevent="submit" class="btn btn-success btn-outline">Go</button>
                        <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="cancel" class="btn btn-danger btn-outline">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.listen();
        },

        data() {
            return {
                visible : false,
                password : null,
                password_confirmation : null,
                busy : false
            }
        },

        methods : {
            listen() {
                Bus.$on('ShowPasswordForm', (event) => {
                    this.show();
                })
            },

            show() {
                this.visible = true;
            },

            cancel() {
                this.busy = false;
                this.visible = false;
                this.password = null;
                this.password_confirmation = false;
            },

            submit() {
                return swal({
                  title: `Confirm Password Reset`,
                  text: "Are you sure you want to reset your password?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#bf5329",
                  confirmButtonText: `Yes, reset my password.`,
                }).then( this.resetPassword, this.ignore );
            },

            resetPassword() {
                this.busy = true;

                Api.post('profile/reset', { password : this.password, password_confirmation : this.password_confirmation })
                    .then( this.success, this.error );
            },

            ignore() {

            },

            success() {
                flash.success('Password Reset')

                this.busy = false;
                this.visible = false;
            },

            error(error) {
                flash.error('There was an error performing the operation. See the console for more details');
                console.error(error);

                this.busy = false;
            },
        }


    }
</script>

<style lang="less">
    .reset-password {
        width: 600px;
        min-height: 400px;

        .panel-heading {
            font-size: 24px;
        }

        .panel-footer {
            display: flex;
            justify-content: flex-end;
        }

        .panel-body {
            button {
                font-weight: bold;
            }
        }

        .partial-path-form {
            display: flex;

            input {
                flex: 1;
            }

            * + * {
                margin-left: 15px;
            }
        }
    }

    .reset-password-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        height: 100vh;
        width: 100vw;
        background: rgba(0,0,0,0.3);

        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>