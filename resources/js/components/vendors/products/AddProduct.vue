<template>
    <!-- Modal to add new record -->
    <div class="modal modal-slide-in fade" id="add-product">
        <div class="modal-dialog sidebar-sm">
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
                <b-form @submit.stop.prevent="handleSubmit(add)" class="add-new-record modal-content pt-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <validation-provider
                            name="Product"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-2" label="Product Name" label-for="example-input-2">
                                <v-select :options="options"
                                    id="example-input-2"
                                    name="example-input-2"
                                    v-model="product.name"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="input-2-live-feedback">
                                </v-select>
                                <b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[1] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="Video Link"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-1" label="Video Link" label-for="example-input-1">
                                <b-form-input
                                id="example-input-1"
                                name="example-input-1"
                                v-model="product.vendor_link"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-1-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="Color"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-3" label="Color" label-for="example-input-3">
                                <b-form-input
                                id="example-input-3"
                                name="example-input-3"
                                v-model="product.color"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-3-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-3-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        
                        <validation-provider
                            name="Size"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-4" label="Size" label-for="example-input-4">
                                <b-form-input
                                id="example-input-4"
                                name="example-input-4"
                                v-model="product.size"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-4-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-4-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="Price"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                            >
                            <b-form-group id="example-input-group-5" label="Price" label-for="example-input-5">
                                <b-form-input
                                id="example-input-5"
                                name="example-input-5"
                                v-model="product.price"
                                :state="getValidationState(validationContext)"
                                aria-describedby="input-5-live-feedback"
                                ></b-form-input>

                                <b-form-invalid-feedback id="input-5-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        
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
import { EventBus } from "../../../vue-asset";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
    props: ['vendor'],
    components: {vSelect},

    data() {
        return {
            product: {
                name: '',
                vendor_link: '',
                color: '',
                size: '',
                price: 0
            },
            options: [],
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

    mounted() {
        this.getProducts();
    },

    methods: {
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

        add() {
            this.saving = true
            axios
            .post(base_url + "vendorss/product/" + this.vendor.id, this.product)

            .then(response => {
                this.saving = false
                this.showMessage(response.data);
                this.product = {
                    name: ''
                };
                $("#add-product .close").click();
                EventBus.$emit("products-added");
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