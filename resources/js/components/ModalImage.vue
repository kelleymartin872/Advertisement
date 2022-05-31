<template>
    <Teleport to="body">
        <Transition>
            <div v-show="show" class="modal__wrapper">
                <div class="modal__bg" @click="handleBgClick"/>
                <img
                    class="modal__image"
                    :src="image"
                />
                <div class="modal__inner">
                    <slot/>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
export default {
    emits: ['close-modal'],
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        image: {
            type: String,
            default: '',
        },
    },
    methods: {
        emitClose() {
            this.$emit('close-modal')
        },
        handleBgClick() {
            this.emitClose()
        },
    },
}
</script>

<style lang="scss">
.modal {
    &__wrapper {
        top: 0;
        left: 0;
        position: fixed;
        z-index: 99999;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        padding: 100px;
    }

    &__bg {
        top: 0;
        left: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    &__image {
        z-index: 1;
        max-height: 100%;
        object-fit: scale-down;
    }
}

.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
