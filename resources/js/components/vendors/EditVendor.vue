<template>
    <!-- Modal to edit new record -->
    <div class="modal modal-slide-in fade" id="edit-vendor">
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
                                name="Vendor Name"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-1" label="Vendor Name" label-for="example-input-1">
                                    <b-form-input
                                    id="example-input-1"
                                    name="example-input-1"
                                    v-model="vendor.name"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="input-1-live-feedback"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>

                            <!-- <validation-provider
                                name="Brand name"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-2" label="Brand Name" label-for="example-input-2">
                                    <v-select :options="vendoroptions"
                                        id="example-input-2"
                                        name="example-input-2"
                                        v-model="vendor.vendor"
                                        :state="getValidationState(validationContext)"
                                        aria-describedby="input-2-live-feedback">
                                    </v-select>
                                    <b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[1] }}</b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider> -->

                            <validation-provider
                                name="Category"
                                :rules="{ required: true, min: 3 }"
                                v-slot="validationContext"
                                >
                                <b-form-group id="example-input-group-2" label="Category" label-for="example-input-2">
                                    <v-select :options="categoriesoptions"
                                        id="example-input-2"
                                        name="example-input-2"
                                        v-model="vendor.categories"
                                        :value="vendor.category"
                                        :state="getValidationState(validationContext)"
                                        aria-describedby="input-2-live-feedback">
                                    </v-select>
                                    <b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[1] }}</b-form-invalid-feedback>
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
import { EventBus } from "../../vue-asset";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
    components: {vSelect},
    data() {
        return {
            vendor: {
                id: '',
                name: '',
                brand: '',
                catgeory: '',
                categories: {
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
            vendoroptions: [],
            categoriesoptions: [],
            brands: [],
            categories: [],
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
       
        EventBus.$on('edit-vendor',function(id){
            _this.vendor.id = id;
            _this.getEditData(id);
        });
    },

    mounted() {
        this.getBrands();
        this.getCategories();
    },

    methods: {
        getEditData(id){
            this.show = true
            axios.get(base_url+'vendorss/vendor/' + id + '/edit')
            .then(response => {
                this.vendor = {
                    id : response.data.id,
                    name: response.data.name,
                    category: response.data.category_id,
                    categories: {
                        code: response.data.id,
                        label: response.data.name
                    }
                };
                this.show = false
            })
        },

        //Form Validation
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        resetForm() {
            this.vendor = {
                name: ''
            };

            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        },

        getBrands() {
            axios.get(base_url + 'brands/brands-list').then(response => {
                this.brands = response.data
                var arrayAllBusinessUsers=[];
                this.brands.forEach(element => {
                    var valueToPush = {};
                    valueToPush["label"] = element.name;
                    
                    valueToPush["code"] = element.id;
                    
                    arrayAllBusinessUsers.push(valueToPush);
                });
                this.brandoptions = arrayAllBusinessUsers;
            })
        },

        getCategories() {
            axios.get(base_url + 'vendorss/categories-list').then(response => {
                this.categories = response.data
                var arrayAllBusinessUsers=[];
                this.categories.forEach(element => {
                    var valueToPush = {};
                    valueToPush["label"] = element.name;
                    
                    valueToPush["code"] = element.id;
                    
                    arrayAllBusinessUsers.push(valueToPush);
                });
                this.categoriesoptions = arrayAllBusinessUsers;
            })
        },

        update() {
            this.saving = true
            axios
            .post(base_url + "vendorss/vendor/" + this.vendor.id + "/update", this.vendor)

            .then(response => {
                this.saving = false
                this.showMessage(response.data);
                this.vendor = {
                    name: ''
                };
                $("#edit-vendor .close").click();
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