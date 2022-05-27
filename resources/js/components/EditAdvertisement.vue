<template>
    <div>
        <h4 class="text-center">Edit Advertisement</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="updateAdvertisement">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="advertisement.title">
                    </div><br>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" v-model="advertisement.description">
                    </div><br>
                    <button type="submit" class="btn btn-primary">Update Advertisement</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            advertisement: {}
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/advertisement/edit/${this.$route.params.id}`)
                .then(response => {
                    this.advertisement = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        updateAdvertisement() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.put(`/api/advertisement/update/${this.$route.params.id}`, this.advertisement)
                    .then(response => {
                        this.$router.push({name: 'home'});
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>
