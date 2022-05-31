<template>
    <div>
        <h4 class="text-center">Add Advertisement</h4>
        <div
            v-if="status_msg"
            class="alert alert-danger"
            role="alert"
        >
            {{ status_msg }}
        </div>
        <div class="row">
            <div class="col-md-6">
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
                    <input class="form-control" type="file" id="files" ref="files" multiple @change="handleFileUploads()"/>
                </div>
                <div class="large-12 medium-12 small-12 cell">
                    <div v-for="(file, key) in files" class="file-listing">{{ file.name }}
                        <span class="remove-file" @click="removeFile(key)">Remove</span>
                    </div>
                </div><br>
                <div class="large-12 medium-12 small-12 cell">
                    <button @click="addFiles()" class="btn btn-outline-success">Add Files</button>
                </div><br><br>
                <button type="submit" class="btn btn-primary" @click="addAdvertisement()">Add Advertisement</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            advertisement: {},
            files: [],
            status_msg: '',
            maxImages: 3
        }
    },
    methods: {
        showMaxImagesError() {
            this.showNotification('You cannot upload more than ' + this.maxImages + ' images')
        },
        getInputFiles() {
            return this.$refs.files.files
        },
        openAddFilesMenu() {
            this.$refs.files.click()
        },
        addFiles() {
            if (this.files.length >= this.maxImages) {
                this.showMaxImagesError()
            } else {
                this.openAddFilesMenu()
            }
        },
        handleFileUploads() {
            let uploadedFiles = this.getInputFiles();

            if (uploadedFiles.length > this.maxImages) {
                this.showMaxImagesError()
            } else {
                this.files.push(...uploadedFiles)
            }
        },
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
        removeFile( key ) {
            this.files.splice(key, 1);
        },
        showNotification (message) {
            this.status_msg = message
            setTimeout(() => {
                this.status_msg = ''
            }, 5000)
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
<style>
input[type="file"] {
    position: absolute;
    top: -500px;
}
div.file-listing {
    width: 636px;
}
span.remove-file {
    color: red;
    cursor: pointer;
    float: right;
}
</style>
