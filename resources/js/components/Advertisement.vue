<template>
    <div class="card px-3 pt-3" style="max-width: 32rem">
        <div>
            <ul>
                <li v-for="image in advertisement.images" :key="image.id">
                    <a :href="getImgUrl(image.path)"><img :src="getImgUrl(image.path)" alt="" width="120"></a>
                </li>
            </ul>
            <div class="row mb-3">
                <div class="col-6"></div>
                <div class="col-6 text-end">
                    <u>{{ format_date(advertisement.created_at) }}</u>
                </div>
            </div>
            <h5>{{ advertisement.title }}</h5>
            <p>
                {{ advertisement.description }}
            </p>
        </div>
        <!-- News block -->
    </div>
</template>

<script>
import moment from 'moment';
export default {
    data() {
        return {
            advertisement: {}
        }
    },
    created() {
        this.$axios.get(`/api/advertisement/show/${this.$route.params.id}`)
            .then(response => {
                this.advertisement = response.data;
            })
            .catch(function (error) {
                console.error(error);
            });
    },
    methods: {
        format_date(value){
            if (value) {
                return moment(String(value)).format('YYYY-MM-DD')
            }
        },
        getImgUrl(pic) {
            return '/uploads/profile_images/' + pic;
        },
    }
}
</script>
<style>
li{list-style-type: none;margin-bottom: 2px}
</style>
