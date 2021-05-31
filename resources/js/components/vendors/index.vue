<template>
    <div class="card">
        <div class="card-header border-bottom p-1">
            <div class="head-label">
                <h6 class="mb-0">Vendors</h6>
            </div>
            <div class="dt-action-buttons text-right">
                <div class="dt-buttons flex-wrap d-inline-flex">
                    <button class="btn create-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#add-vendor">
                        <span>
                            <i data-feather="plus"></i>
                            Add New Record
                        </span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-md-4" style="margin-left: inherit;">
                <b-form-group
                    label="Sort"
                    label-for="sort-by-select"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                    v-slot="{ ariaDescribedby }"
                    >
                    <b-input-group size="sm">
                        <b-form-select
                        id="sort-by-select"
                        v-model="sortBy"
                        :options="sortOptions"
                        :aria-describedby="ariaDescribedby"
                        class="w-75"
                        >
                        <template #first>
                            <option value="">-- none --</option>
                        </template>
                        </b-form-select>

                        <b-form-select
                        v-model="sortDesc"
                        :disabled="!sortBy"
                        :aria-describedby="ariaDescribedby"
                        size="sm"
                        class="w-25"
                        >
                        <option :value="false">Asc</option>
                        <option :value="true">Desc</option>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <b-form-group
                label="Initial sort"
                label-for="initial-sort-select"
                label-cols-sm="3"
                label-align-sm="right"
                label-size="sm"
                class="mb-0"
                >
                    <b-form-select
                        id="initial-sort-select"
                        v-model="sortDirection"
                        :options="['asc', 'desc', 'last']"
                        size="sm"
                    ></b-form-select>
                </b-form-group>
            </div>
        </div>

        <!-- Filter Area -->
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-md-4" style="margin-left: inherit;">
                <b-form-group
                label="Filter"
                label-for="filter-input"
                label-cols-sm="3"
                label-align-sm="right"
                label-size="sm"
                class="mb-0"
                >
                <b-input-group size="sm">
                    <b-form-input
                    id="filter-input"
                    v-model="filter"
                    type="search"
                    placeholder="Type to Search"
                    ></b-form-input>

                    <b-input-group-append>
                    <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <b-form-group
                v-model="sortDirection"
                label="Filter On"
                description="Leave all unchecked to filter on all data"
                label-cols-sm="3"
                label-align-sm="right"
                label-size="sm"
                class="mb-0"
                v-slot="{ ariaDescribedby }"
                >
                    <b-form-checkbox-group
                        v-model="filterOn"
                        :aria-describedby="ariaDescribedby"
                        class="mt-1"
                    >
                        <b-form-checkbox value="id">ID</b-form-checkbox>
                        <b-form-checkbox value="name">Name</b-form-checkbox>
                        <b-form-checkbox value="brand">Brand</b-form-checkbox>
                        <b-form-checkbox value="category">Category</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-form-group>
            </div>
        </div>

        <!-- Per Page Area -->
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-md-4" style="margin-left: inherit;">
                <b-form-group
                label="Per page"
                label-for="per-page-select"
                label-cols-sm="6"
                label-cols-md="4"
                label-cols-lg="3"
                label-align-sm="right"
                label-size="sm"
                class="mb-0"
                >
                <b-form-select
                    id="per-page-select"
                    v-model="perPage"
                    :options="pageOptions"
                    size="sm"
                ></b-form-select>
                </b-form-group>
            </div>
        </div>

        <!-- Main table element -->
        <b-table
            :items="vendors"
            :busy="isLoading"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"
            :filter="filter"
            :filter-included-fields="filterOn"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            sort-icon-left
            responsive="sm"
            :tbody-transition-props="transProps"
            show-empty
            @filtered="onFiltered"
            >
            <template #table-busy>
                <div class="text-center my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading...</strong>
                </div>
            </template>
    
            <template #cell(brand)="row">
                <div v-if="row.item.category != null || undefined || ''">
                    {{ row.item.category.brand.name }}
                </div>
            </template>

            <template #cell(name)="row">
                <a :href="`/vendorss/products/` + row.item.id">
                    {{ row.value }}
                </a>
            </template>

            <template #cell(category)="row">
                {{ row.value.name }}
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" @click="edit(row.item)" data-toggle="modal" data-target="#edit-vendor" class="mr-1">
                    Edit
                </b-button>
                <b-button size="sm" @click="sendInfo(row.item.id)" data-toggle="modal" data-target="#delete-vendor" class="mr-1">
                    Delete
                </b-button>
                <b-button size="sm" @click="row.toggleDetails">
                    {{ row.detailsShowing ? 'Hide' : 'Show' }} Details
                </b-button>
            </template>

            <template #row-details="row">
                <b-card>
                    <ul>
                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>

        <!-- Delete Modal -->
        <div class="modal fade text-center" id="delete-vendor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Delete Vendor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Vendor</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" @click="destroy(selected_vendor_id)" class="btn btn-primary continue-btn">Delete</a>
                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        
        <edit-vendor></edit-vendor>

        <div class="row d-flex justify-content-between mx-0" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-md-12">
                <b-pagination
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
                size="sm"
                class="my-0 justify-content-center"
                ></b-pagination>
            </div>
        </div>
    </div>
</template>

<script>
import { EventBus } from "../../vue-asset";
import EditVendor from './EditVendor.vue';
export default {
    components: { EditVendor },

    data() {
        return {
            vendors: [],
            fields: [
                { key: 'id', label: 'No.', sortable: true, sortDirection: 'desc' },
                { key: 'name', label: 'Name', sortable: true, sortDirection: 'asc' },
                { key: 'brand', label: 'Brand', sortable: true, sortDirection: 'asc' },
                { key: 'category', label: 'Category', sortable: true, sortDirection: 'asc' },
                { key: 'actions', label: 'Actions' }
            ],
            transProps: {
                // Transition name
                name: 'flip-list'
            },
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15, { value: 100, text: "Show a lot" }],
            sortBy: '',
            sortDesc: false,
            sortDirection: 'asc',
            filter: null,
            filterOn: [],
            selected_vendor_id: '',
            isLoading: false,
            errors: null,
            notificationSystem: {
            options: {
                success: {
                    position: "center",
                    timeout: 3000,
                    class: 'success_notification'
                },
                error: {
                    overlay: true,
                    zindex: 999,
                    position: "center",
                    timeout: 3000,
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
        };
    },
    created() {
        var _this = this;
        this.get_vendors();
        EventBus.$on("vendors-added", function() {
            _this.get_vendors();
        });
    },

    mounted() {
        
    },

    methods: {
        get_vendors() {
            this.isLoading = true;
            axios
                .get(
                base_url +
                    "vendorss/vendor-list"
                )
                .then(response => {
                    this.vendors = response.data
                    // Set the initial number of items
                    this.totalRows = this.vendors.length
                    this.isLoading = false;
                })
                .catch(err => {
                if (err.response) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        showMessage(data) {
            if (data.status  == "success") {
                this.$toast.success(data.message, 'Success Alert', this.notificationSystem.options.info );
            } else {
                this.$toast.error(data.message, "Error Alert", this.notificationSystem.options.error);
            }
        },

        edit(item) {
            EventBus.$emit("edit-vendor", item.id);
        },

        info(item, index, button) {
            this.infoModal.title = `Row index: ${index}`
            this.infoModal.content = JSON.stringify(item, null, 2)
            this.$root.$emit('bv::show::modal', this.infoModal.id, button)
        },
        resetInfoModal() {
            this.infoModal.title = ''
            this.infoModal.content = ''
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        sendInfo(id) {
            this.selected_vendor_id = id;
        },

        destroy(id) {
            axios.delete(base_url + "vendorss/vendor/" + id)

            .then(response => {
                EventBus.$emit("vendors-added");
                $("#delete-vendor .close").click()
                this.showMessage(response.data);
            })
            .catch(err => {
                if (err.response) {
                    this.showMessage(err.response.data)
                }
            });
            
        }
    },

    computed: {
        show() {
            return this.vendors ? (this.vendors.length >= 1 ? true: false) : null
        },

        sortOptions() {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      }
    }
}
</script>

<style scoped>
    table#table-transition-example .flip-list-move {
        transition: transform 1s;
    }
</style>