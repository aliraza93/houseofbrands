<template>
    <!-- Modal to edit new record -->
    <div class="modal modal-slide-in fade" id="edit-product">
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
                            name="Product"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-6" label="Product Name" label-for="example-input-6">
                                <v-select :options="options"
                                    id="example-input-6"
                                    name="example-input-6"
                                    v-model="product.name"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="input-6-live-feedback">
                                </v-select>
                                <b-form-invalid-feedback id="input-6-live-feedback">{{ validationContext.errors[1] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="Video Link"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-7" label="Video Link" label-for="example-input-7">
                                <b-form-input
                                id="example-input-7"
                                name="example-input-7"
                                v-model="product.product_link"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-7-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-7-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="Color"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-8" label="Color" label-for="example-input-8">
                                <b-form-input
                                id="example-input-8"
                                name="example-input-8"
                                v-model="product.color"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-8-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-3-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        
                        <validation-provider
                            name="Size"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-9" label="Size" label-for="example-input-9">
                                <b-form-input
                                id="example-input-9"
                                name="example-input-9"
                                v-model="product.size"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-9-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-9-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="Price"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-10" label="Price" label-for="example-input-10">
                                <b-form-input
                                id="example-input-10"
                                name="example-input-10"
                                v-model="product.price"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-10-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-10-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        
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
import { EventBus } from "../../../vue-asset";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
    components: {vSelect},
    data() {
        return {
            product: {
                id: '',
                name: '',
                vendor_link: '',
                color: '',
                size: '',
                price: 0,
                products: {
                    code: '',
                    label: ''
                }
            },
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
            options: [],
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
       
        EventBus.$on('edit-product',function(id){
            _this.product.id = id;
            _this.getEditData(id);
        });
    },

    mounted() {
        this.getProducts();
    },

    methods: {
        getEditData(id){
            this.show = true
            axios.get(base_url+'vendorss/vendor-product/' + id + '/edit')
            .then(response => {
                this.product = {
                    id : response.data.id,
                    name: response.data.name,
                    vendor_link: response.data.vendor_link,
                    size: response.data.size,
                    price: response.data.price,
                    color: response.data.color,
                    // products: {
                    //     code: response.data.product_id,
                    //     label: response.data.name
                    // }
                };
                this.show = false
            })
        },

        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.product = {
                name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        },

        getProducts() {
            axios.get(base_url + 'vendorss/products-list').then(response => {
                var products = response.data
                var arrayAllProducts=[];
                products.forEach(element => {
                    var valueToPush = {};
                    valueToPush["label"] = element.name;
                    
                    valueToPush["code"] = element.id;
                    
                    arrayAllProducts.push(valueToPush);
                });
                this.options = arrayAllProducts;
            })
        },

        update() {
            this.saving = true
            axios
            .post(base_url + "vendorss/vendor/" + this.product.id + "/update", this.product)

            .then(response => {
                this.saving = false
                this.showMessage(response.data);
                this.product = {
                    name: ''
                };
                $("#edit-product .close").click();
                EventBus.$emit("vendors-added");
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