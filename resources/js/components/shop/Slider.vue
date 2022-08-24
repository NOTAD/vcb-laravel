<template>
    <div class="slider-show" style="height: 100vh;">
        <img  v-for="(item,index) in slider" :key="index" :src="item.image" class="h-100"/>
    </div>
</template>
<script>

export default {
    name: "Slider",
    props: {
        slider: {
            Type: Object,
            default() {
                return {};
            },
        },
    }
}
</script>

<style scoped>

</style>
