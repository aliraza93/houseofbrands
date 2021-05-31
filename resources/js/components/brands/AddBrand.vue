<template>
    <!-- Modal to add new record -->
  <div class="modal modal-slide-in fade" id="add-brand">
      <div class="modal-dialog sidebar-sm">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.stop.prevent="handleSubmit(add)" class="add-new-record modal-content pt-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <validation-provider
                            name="Name"
                            :rules="{ required: true, min: 3 }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-1" label="Brand Name" label-for="example-input-1">
                                <b-form-input
                                id="example-input-1"
                                name="example-input-1"
                                v-model="brand.name"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-1-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        <b-form-group>
                            <b-form-file
                                v-model="brand.logo"
                                :state="Boolean(brand.logo)"
                                placeholder="Choose a file or drop it here..."
                                drop-placeholder="Drop file here..."
                                @change="validateImage($event)"
                            ></b-form-file>
                        </b-form-group>
                    </div>
                    <div class="modal-footer">
                        <b-button variant="primary" :disabled="disableSubmitButton" type="submit" value="Submit">
                            {{saving ? "Submitting..." : "Submit"}}
                            <b-spinner v-if="saving" small type="grow"></b-spinner>
                        </b-button>
                        <b-button @click="resetForm()">Reset</b-button>
                    </div>
                </b-form>
            </validation-observer>
        </div>
  </div>
</template>

<script>
import { EventBus } from "../../vue-asset";

export default {
    data() {
        return {
            brand: {
                name: '',
                logo: null
            },
            saving: false,
            notificationSystem: {
                options: {
                    success: {
                        overlay: true,
                        position: "center",
                        timeout: 3000,
                        class: 'complete_notification'
                    },
                    error: {
                        position: "center",
                        timeout: 4000,
                        class: 'error_notification'
                    },
                    completed: {
                        position: 'center',
                        timeout: 1000,
                        class: 'complete_notification'
                    },
                    info: {
                        overlay: true,
                        zindex: 999,
                        position: 'center',
                        timeout: 3000,
                        class: 'info_notification',
                    }
                }
            },
            errors: null
        };
    },

    methods: {
        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.brand = {
                name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        },

        validateImage(event) {
            let _URL = window.URL || window.webkitURL
            this.brand.logo = event.target.files[0]
        },

        add() {
            console.log(this.brand)
            this.saving = true
            let data = new FormData();
            data.append('logo', this.brand.logo);
            data.append('name', this.brand.name)

            axios
            .post(base_url + "brands/brand", data)

            .then(response => {
                this.saving = false
                this.showMessage(response.data);
                this.brand = {
                    name: ''
                };
                $("#add-brand .close").click();
                EventBus.$emit("brands-added");
                // location.reload()
            })
            .catch(err => {
                if (err.response) {
                    this.saving = false
                    this.showMessage(err.response.data)
                }
            });
        },

        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.success );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },
    },

    computed: {
        disableSubmitButton() {
            return this.saving ? true: false
        }
    },
};
</script>