<template>
    <div class="player d-flex">
        <div class="col-auto avatar-container">
            <img src="" class="img-fluid rounded-circle avatar m-0" alt="">
        </div>
        <div class="col">
            <strong class="name border-bottom">Button</strong>
            <p class="m-0 text-center">0</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "ButtonComponent"
}
</script>

<style scoped>
.avatar{
    height: 50px;
    width: 50px;
    display: block;
}
.avatar-container{
    padding: 2px;
}
.player{
    border-radius: 27px;
    border: 1px solid black;
    width: 140px;
    background-color: silver;
}
.name{
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    width: 85px;
    display: block;
}
</style>
