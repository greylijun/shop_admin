<style>
#container{
     /*保证footer是相对于container位置绝对定位*/
    position:relative;  
    width:100%;
    min-height:100%; 
    /*设置padding-bottom值大于等于footer的height值，以保证main的内容能够全部显示出来而不被footer遮盖；*/  
    padding-bottom: 60px;  
    box-sizing: border-box;
}
.footer-auto{
    position:absolute;
    width: 100%;
    bottom:0px;
    left:0px;
}
</style>
<template>
	<el-container id='container'>
		<el-header style="padding: 0px;">
			<app-header></app-header>
		</el-header>
		<el-main>
			<div style="margin-top: 15px;">
			<div class="col-md-2">
				<app-nav></app-nav>
			</div>
			<div class="col-md-10">
				<router-view></router-view>
			</div>
		</div>
		</el-main>
		<el-footer class='footer-auto' style='height: 40px;'>
			<app-footer></app-footer>
		</el-footer>
	</el-container>
</template>
<script>
	import header from './commons/Header.vue';
	import nav from './commons/Nav.vue';
	import footer from './commons/Footer.vue';

	export default {
		components: {
			'app-header': header,
			'app-nav': nav,
			'app-footer': footer
		},
		mounted() {
		},
		data() {
			return {
				config: []
			}
		}
	}
</script>