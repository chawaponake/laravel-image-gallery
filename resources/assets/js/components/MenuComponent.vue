<template>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <router-link :to="{ name: 'home' }" class="nav-link">Home</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'gallery' }" class="nav-link">Gallery</router-link>
        </li>
    </ul>
</template>

<script>
export default {

}
</script>