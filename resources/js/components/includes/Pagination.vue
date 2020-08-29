<template>
    <nav>
        <ul class="pagination">
            <li class="page-item" :class="{ 'disabled': meta.current_page === 1 }">
                <a class="page-link" href="#" @click="pageChange(meta.current_page-1)">
                    &laquo;
                </a>
            </li>
            <li class="page-item" :class="{ 'active': meta.current_page === page }" v-for="page in meta.last_page" :key="page">
                <a class="page-link" href="#" @click.prevent="pageChange(page)">
                    {{ page }}
                </a>
            </li>
            <li class="page-item" :class="{ 'disabled': meta.current_page === meta.last_page }">
                <a class="page-link" href="#" @click="pageChange(meta.current_page+1)">
                    &raquo;
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "Pagination",
        props: ['meta'],
        methods: {
            pageChange(page){
                this.$emit('pageChange', page);
            }
        }
    }
</script>