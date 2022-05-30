<template>
    <div>
        <h4 class="text-center">All Advertisement</h4><br/>
        <div v-if="advertisements.length">
            <div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            @click="handleColumnHeaderClick(column)"
                        >
                            <div
                                :class="{'header-column': column.needSort}"
                                class="d-flex justify-content-between"
                            >
                                {{ column.label }}
                                <div v-if="column.key === sortParam">
                                    <i v-if="sortDecrease" class="bi bi-sort-down" />
                                    <i v-else class="bi bi-sort-up" />
                                </div>
                            </div>
                        </th>
                        <th v-if="isLoggedIn" style="width: 15%">Buttons</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="advertisement in advertisements" :key="advertisement.id">
                        <td>{{ advertisement.id }}</td>
                        <td><router-link :to="{name: 'advertisement', params: { id: advertisement.id }}">{{ advertisement.title }}</router-link></td>
                        <td>{{ advertisement.description }}</td>
                        <td>{{ format_date(advertisement.created_at) }}</td>
                        <td v-if="isLoggedIn && currentUserId === advertisement.user_id">
                            <div class="btn-group" role="group">
                                <router-link :to="{name: 'editadvertisement', params: { id: advertisement.id }}" class="btn btn-primary">Edit
                                </router-link>
                                <button class="btn btn-danger" @click="deleteAdvertisement(advertisement.id)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <paginate
                    v-model="page"
                    :page-count="last_page"
                    :page-range="3"
                    :margin-pages="2"
                    :click-handler="clickCallback"
                    :prev-text="'Prev'"
                    :next-text="'Next'"
                    :container-class="'pagination'"
                    :page-class="'page-item'"
                >
                </paginate>
            </div>
        </div>
        <button v-if="isLoggedIn" type="button" class="btn btn-info" @click="this.$router.push('/advertisement/add')">Add Advertisements</button>

    </div>
</template>

<script>
import moment from 'moment';
import Paginate from 'vuejs-paginate-next';

const createColumnData = (key, label, needSort = true) => ({ key, label, needSort })

export default {
    name: "Home",
    components: {
        paginate: Paginate,
    },
    data() {
        return {
            sortParam: '',
            sortDecrease: false,
            page: null,
            last_page: null,
            isLoggedIn: false,
            currentUserId: null,
            advertisements: [],
            columns: [
                createColumnData('id', 'ID'),
                createColumnData('title', 'Title'),
                createColumnData('description', 'Description', false),
                createColumnData('created_at', 'Created At'),
            ],
        }
    },
    created() {
        if (window.Laravel.user) {
            this.isLoggedIn = true
            this.currentUserId = window.Laravel.user.id
        }

        this.fetchItems()
    },
    watch: {
        sortParam() {
            this.fetchItems()
        },
        sortDecrease() {
            this.fetchItems()
        },
    },
    computed:{
        sortOrder() {
            return this.sortDecrease ? 'asc' : 'desc'
        },

        requestItemsOptions() {
            return {
                sort_order: this.sortOrder,
                sort: this.sortParam ? this.sortParam : 'id',
                page: 1,
            }
        },
    },
    methods: {
        handleColumnHeaderClick(column) {
            if (!column.needSort) return

            if (this.sortParam === column.key) {
                this.sortDecrease = !this.sortDecrease
            }

            this.sortParam = column.key
        },

        clickCallback (pageNum) {
            this.fetchItems(pageNum)
        },

        async fetchItems(page) {
            const params = page ? {
                ...this.requestItemsOptions,
                page
            } : this.requestItemsOptions

            const response = await this.requestItemsApi(params)

            this.advertisements = response.data.data;
            this.page = response.data.current_page;
            this.last_page = response.data.last_page;
        },

        async requestItemsApi(params) {
            let result

            try {
                result = await this.$axios.get('/api/advertisement', {params})
            } catch (e) {
                console.error(e)
            }

            return result
        },

        deleteAdvertisement(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/advertisement/delete/${id}`)
                    .then(response => {
                        let i = this.advertisements.map(item => item.id).indexOf(id);
                        this.advertisements.splice(i, 1)
                        this.$router.push({name: 'home'})
                        this.fetchItems()
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        format_date(value){
            if (value) {
                return moment(String(value)).format('YYYY-MM-DD hh:mm')
            }
        },
    }
}
</script>
<style lang="scss">
.header-column {
    color: blue;
    cursor: pointer;
}
.page-item {cursor: pointer}
</style>
