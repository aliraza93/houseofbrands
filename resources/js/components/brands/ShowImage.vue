<template>
    <div class="modal fade text-center" id="show-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Brand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <b-overlay
                            id="overlay-background"
                            :show="show"
                            :variant="variant"
                            :opacity="opacity"
                            :blur="blur"
                            rounded="sm"
                            >
                    <div class="modal-body">
                        <img :src="`/` + brand.logo" alt="" style="width: 20rem;">
                    </div>
                </b-overlay>
                <div class="modal-footer">
                    <a href="javascript:void(0);" @click="destroy(selected_brand_id)" class="btn btn-primary continue-btn">Delete</a>
                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                </div>
            </div>
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
            errors: null
        };
    },

    created() {
        var _this = this;
       
        EventBus.$on('show-image',function(id){
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
                    logo: response.data.logo
                };
                this.show = false
            })
        },
    },
};
</script>