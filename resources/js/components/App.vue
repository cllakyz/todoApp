<template>
    <div id="main">
        <header id="header">
            <Navbar />
        </header>
        <div id="content">
            <router-view />
        </div>
    </div>
</template>

<script>
import Navbar from './includes/Navbar';
export default {
    data(){
        return{

        }
    },
    components: {
        Navbar
    }
}
</script>
