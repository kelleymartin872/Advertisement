<template>
    <div>
        <h4 class="text-center">Add Advertisement</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addAdvertisement">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="advertisement.title">
                    </div><br>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea v-model="advertisement.description" class="form-control"></textarea>
                    </div><br>
                    <div class="form-group">
                        <label>Files</label>
                        <input class="form-control" type="file" id="files" ref="files" multiple v-on:change="handleFileUploads()"/>
                    </div><br>
                    <button type="submit" class="btn btn-primary">Add Advertisement</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            advertisement: {},
            files: ''
        }
    },
    methods: {
        addAdvertisement() {
            let formData = new FormData();
            for( var i = 0; i < this.files.length; i++ ){
                let file = this.files[i];
                formData.append('files[' + i + ']', file);
            }
            formData.append('title', this.advertisement.title);
            formData.append('description', this.advertisement.description);

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/advertisement/add', formData, config)
                    .then(response => {
                        this.$router.push({name: 'home'})
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        handleFileUploads(){
            this.files = this.$refs.files.files;
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
