<template>
    <div v-if="visible" class="batch-update-selected-wrapper">
        <form @submit.prevent="submit">
            <div class="batch-update-selected">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="text-success">
                            <i class="fa fa-fw fa-exclamation-circle"></i>
                            Batch File To Update Selected Clients
                        </span>
                    </div>
                    <div class="panel-body">
                        <p>
                            <label for="password">Password</label>
                            <input type="password" v-model="password" class="form-control full">
                        </p>
                        <p>
                            <label for="clients">Batch File</label>
                            <textarea id="clients" rows="6" v-model="batch" class="form-control full"></textarea>
                        </p>
                    </div>
                    <div class="panel-footer">
                        <div class="btn-group">
                            <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="cancel" class="btn btn-success btn-outline">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                clients : []
            }
        },

        computed : {
            batch() {
                return this.clients.map( o => {
                    return `psexec \\\\${o.model.name} -h -n 3 -u msb\\svckbox -p ${this.password} -accepteula -d \\\\dsjkb\\desoft$\\MSB_Virus_Sentry\\kill.bat`;
                }).join('\n');
            }
        },

        methods : {
            listen() {
                Bus.$on('BatchUpdateSelected', (event) => {
                    this.resetForm();
                    if ( !! event && !! event.clients ) this.clients = event.clients;
                    this.show();
                })
            },

            show() {
                this.visible = true;
            },

            cancel() {
                this.visible = false;
                this.resetForm();
            },

            resetForm() {
                this.busy = false;
                this.password = null;
                this.clients = [];
            },

            submit() {
                return swal({
                  title: `Confirm Password Reset`,
                  text: "Are you sure you want to reset the password on the selected client?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#bf5329",
                  confirmButtonText: `Yes, reset the admin password.`,
                }).then( this.resetPassword, this.ignore );
            },

            route() {
                if ( this.clients.length == 1) return this.resetPassword();

                return this.resetMany();
            },

            resetPassword() {
                this.busy = true;

                Api.post(`clients/${this.client}/admin-password-reset`, {
                        password : this.password,
                        password_confirmation : this.password_confirmation,
                        master_password : this.master_password,
                    })
                    .then( this.success, this.error );
            },

            resetMany() {
                this.busy = true;

                Api.post(`admin-password-reset`, {
                        clients : this.clients,
                        password : this.password,
                        password_confirmation : this.password_confirmation,
                        master_password : this.master_password,
                    })
                    .then( this.success, this.error );
            },

            ignore() {

            },

            success() {
                flash.success('Password Reset Requested')

                this.busy = false;
                this.visible = false;
            },

            error(error) {
                let message = ( !! error.response.data.password ) ? error.response.data.password[0]  : 'There was an error performing the operation. See the console for more details';
                flash.error(message);
                console.error(error.response);

                this.busy = false;
            },
        }


    }
</script>

<style lang="less">
    .batch-update-selected {
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

    .batch-update-selected-wrapper {
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
