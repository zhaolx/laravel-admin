<template>
	<el-card class="map-card" shadow="never">
	  <div slot="header">
		<span>会员分布</span>
	  </div>
	 <div id="myChartChina" :style="{width: '100%', height: '550px'}"></div>
	 </el-card>
</template>

<script>
	import echarts from "echarts";
	import china from 'echarts/map/json/china.json'
	echarts.registerMap('china', china)  
	
	export default {
	    name: "ChinaMap",
		mounted() {
	        this.drawLine();
	    },
		methods: {
			drawLine(){
				// 基于准备好的dom，初始化echarts实例
				var myChartContainer = document.getElementById('myChartChina');       
				var resizeMyChartContainer = function(){
				  myChartContainer.style.width=(document.body.offsetWidth*0.8)+'px'//页面一半的大小
				} 
				 resizeMyChartContainer();
				var myChartChina = echarts.init(myChartContainer); 
		
				function randomData() {  
						return Math.round(Math.random()*500);  
					} 
				// 绘制图表
			   var optionMap = { 
				   title: {
				             text: "2020年全国会员分布",
				             subtext: "数据纯属系统虚构",
				           },
					  tooltip: {},
						 legend: {
									orient: 'vertical',
									left: 'left',
									data:['']
								},      
						 visualMap: {
									min: 0,
									max: 10000,
									left: '0',
									bottom: '10',
									splitNumber: 5,
									itemWidth: 10,
									itemHeight: 10,
									color: ['#0b50b9','#c3e2f4'],
									pieces: [
										{min: 10000},
										{min: 1000, max: 10000},
										{min: 100, max: 999},
										{min: 10, max: 99},
										{min: 1, max: 9},
									]
								},   
						 selectedMode: 'single',
						 series : [                         
									{
									  name: '全国会员分布', 
									  type: 'map',
									  zoom:1.2, //放大倍数
									  mapType: 'china',
									  itemStyle: {
											normal:{
												borderColor: 'rgba(0, 0, 0, 0.2)'
											},
											emphasis:{
												shadowOffsetX: 0,
												shadowOffsetY: 0,
												shadowBlur: 20,
												borderWidth: 0,
												shadowColor: 'rgba(0, 0, 0, 0.5)'
											}
										},
										showLegendSymbol: true,
										label: {
											normal: {
												show: true
											},
											emphasis: {
												show: true
											}
										},
										data:[               
											 {name: '北京',value: randomData() },
											  {name: '天津',value: randomData() },
											  {name: '上海',value: randomData() },
											  {name: '重庆',value: randomData() },
											  {name: '河北',value: randomData() },
											  {name: '河南',value: randomData() },
											  {name: '云南',value: randomData() },
											  {name: '辽宁',value: randomData() },
											  {name: '黑龙江',value: randomData() },
											  {name: '湖南',value: randomData() },
											  {name: '安徽',value: randomData() },
											  {name: '山东',value: randomData() },
											  {name: '新疆',value: randomData() },
											  {name: '江苏',value: randomData() },
											  {name: '浙江',value: randomData() },
											  {name: '江西',value: randomData() },
											  {name: '湖北',value: randomData() },
											  {name: '广西',value: randomData() },
											  {name: '甘肃',value: randomData() },
											  {name: '山西',value: randomData() },
											  {name: '内蒙古',value: randomData() },
											  {name: '陕西',value: randomData() },
											  {name: '吉林',value: randomData() },
											  {name: '福建',value: randomData() },
											  {name: '贵州',value: randomData() },
											  {name: '广东',value: randomData() },
											  {name: '青海',value: randomData() },
											  {name: '西藏',value: randomData() },
											  {name: '四川',value: randomData() },
											  {name: '宁夏',value: randomData() },
											  {name: '海南',value: randomData() },
											  {name: '台湾',value: randomData() },
											  {name: '香港',value: randomData() },
											  {name: '澳门',value: randomData() }
										]
									}
								]
					   }
			 
				myChartChina.setOption(optionMap);
				window.onresize=function(){
					resizeMyChartContainer();
					myChartChina.resize();
				}
			  }
		}
	}
</script>
<style scoped="scoped">
	.el-card{
		border: 0;
	}
</style>
