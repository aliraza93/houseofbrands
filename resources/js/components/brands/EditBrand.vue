<template>
    <!-- Modal to edit new record -->
  <div class="modal modal-slide-in fade" id="edit-brand">
      <div class="modal-dialog sidebar-sm">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.stop.prevent="handleSubmit(update)" class="add-new-record modal-content pt-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Record</h5>
                    </div>
                    <b-overlay
                            id="overlay-background"
                            :show="show"
                            :variant="variant"
                            :opacity="opacity"
                            :blur="blur"
                            rounded="sm"
                            >
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
                                <b-form-group v-if="image != null || undefined || ''">
                                    <b-img :src="`/` + image" style="width: 4rem; height: 45px;" rounded="circle" fluid alt="Responsive image"></b-img>
                                    <br>
                                    <span>Please choose a new image to upload and submit.</span>
                                </b-form-group>
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
                    </b-overlay>
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
                id: '',
                name: '',
                logo: null
            },
            image: '',
            variant: 'light',
            opacity: 0.85,
            blur: '2px',
            variants: [
                'transparent',
                'white',
                'light',
                'dark',
                'primary',
                'secondary',
                'success',
                'danger',
                'warning',
                'info',
            ],
            blurs: [
                { text: 'None', value: '' },
                '1px',
                '2px',
                '5px',
                '0.5em',
                '1rem'
            ],
            show: true,
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

    created() {
        var _this = this;
       
        EventBus.$on('edit-brand',function(id){
            _this.brand.id = id;
            _this.getEditData(id);
        });
    },

    mounted() {
        
    },

    methods: {
        getEditData(id){
            this.show = true
            axios.get(base_url+'brands/brand/' + id + '/edit')
            .then(response => {
                this.brand = {
                    id : response.data.id,
                    name: response.data.name,
                };
                this.image = response.data.logo
                this.show = false
            })
        },

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

        update() {
            this.saving = true
            let data = new FormData();
            data.append('logo', this.brand.logo);
            data.append('name', this.brand.name)

            axios
            .post(base_url + "brands/brand/" + this.brand.id + "/update", data)

            .then(response => {
                this.saving = false
                this.showMessage(response.data);
                this.brand = {
                    name: ''
                };
                $("#edit-brand .close").click();
                EventBus.$emit("brands-added");
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