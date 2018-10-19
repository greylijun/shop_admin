<style>
    .footer {
        margin-bottom: 0px;
    }
</style>
<template>
    <div class="footer">
        <div class="pull-right">
            技术支持：<span v-html="config.authorCompany"></span>&nbsp;&nbsp;&nbsp;&nbsp; 联系电话：<span
                v-html="config.authorContact"></span>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                config: {
                    authorCompany: '俊哥独家制作',
                    authorContact: '18806202833'
                }
            }
        }
    }
</script>