<template>
    <div class="card px-3 pt-3" style="max-width: 32rem">
        <div>
            <div class="image-list">
                <img
                    v-for="image in images"
                    :key="image.id"
                    :src="image.url"
                    alt=""
                    class="image-list__item"
                    @click="handleImageClick(image.url)"
                >
            </div>
            <div class="row mb-3">
                <div class="col-6"></div>
                <div class="col-6 text-end">
                    <u>{{ advertisement.created_at }}</u>
                </div>
            </div>
            <h5>{{ advertisement.title }}</h5>
            <p>
                {{ advertisement.description }}
            </p>
        </div>
        <!-- News block -->
    </div>
    <ModalImage
        :show="hasActiveImage"
        :image="activeImage"
        @close-modal="handleModalClose"
    />
</template>

<script>
import ModalImage from "./ModalImage";

export default {
    data() {
        return {
            advertisement: {},
            open: false,
            activeImage: null,
        }
    },
    components: {
        ModalImage
    },
    computed: {
        images() {
            const images = this.advertisement.images || []

            return images.map((item) => ({
                ...item,
                url: this.getImgUrl(item.path),
            }))
        },
        hasActiveImage() {
            return Boolean(this.activeImage)
        },
    },
    created() {
        this.$axios.get(`/api/advertisement/${this.$route.params.id}`)
            .then(response => {
                this.advertisement = response.data.data;
            })
            .catch(function (error) {
                console.error(error);
            });
    },
    methods: {
        getImgUrl(pic) {
            return '/uploads/profile_images/' + pic;
        },
        handleImageClick(image) {
            this.activeImage = image
        },
        handleModalClose() {
            this.activeImage = null
        },
    }
}
</script>
<style lang="scss">
.image-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 15px;

    &__item {
        height: 150px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        transition: 0.3s;

        &:hover {
            transform: scale(1.025);
        }

        &:active {
            transform: scale(0.975);
            box-shadow: none;
        }
    }
}
</style>
