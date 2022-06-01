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
        <form @submit.prevent="addAdvertisement">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" :class="{'is-invalid': v$.advertisement.title.$errors.length}" v-model="advertisement.title">
                        <div v-for="(error, index) of v$.advertisement.title.$errors" :key="index">
                            <div class="error-msg">{{ error.$message }}</div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea v-model="advertisement.description" :class="{'is-invalid': v$.advertisement.description.$errors.length}" class="form-control"></textarea>
                        <div v-for="(error, index) of v$.advertisement.description.$errors" :key="index">
                            <div class="error-msg">{{ error.$message }}</div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Files</label>
                        <input class="form-control" type="file" id="files" ref="files" multiple @change="handleFileUploads()"/>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <div v-for="(file, key) in files" class="file-listing">{{ file.name }}
                            <span class="remove-file" @click="removeFile(key)">Remove</span>
                        </div>
                    </div>
                    <br>
                    <div class="large-12 medium-12 small-12 cell">
                        <button @click.prevent="addFiles" class="btn btn-outline-success">Add Files</button>
                    </div>
                    <br><br>
                    <button class="btn btn-primary">Add Advertisement</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'

export default {
    setup: () => ({v$: useVuelidate()}),
    data() {
        return {
            advertisement: {
                title: null,
                description: null,
            },
            files: [],
            status_msg: '',
            maxImages: 3,
        }
    },
    validations() {
        return {
            advertisement: {
                title: { required },
                description: { required },
            },
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
        async addAdvertisement() {
            const isFormCorrect = await this.v$.$validate()
            if (!isFormCorrect) return

            let formData = new FormData();
            for (var i = 0; i < this.files.length; i++) {
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

            try {
                await this.$axios.get('/sanctum/csrf-cookie')
                await this.$axios.post('/api/advertisement/add', formData, config)
                await this.$router.push({name: 'home'})
            } catch (e) {
                console.error(e);
            }
        },
        removeFile(key) {
            this.files.splice(key, 1);
        },
        showNotification(message) {
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
.error-msg {
    color: #dc3545
}
</style>
